<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Service\ShopbagService;
use App\Repository\PurshaseRepository;
use App\Repository\ProductRepository;
use App\Repository\PurshaseProductsRepository;
use App\Repository\MenuRepository;
use App\Repository\IngredientsRepository;
use App\Entity\Purshase;
use App\Entity\Menu;
use App\Entity\Product;
use App\Entity\PurshaseMenus;
use App\Entity\PurshaseProducts;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\MailSenderService;
use App\Service\PushNotificationService;
use Symfony\Component\Mailer\MailerInterface;
use App\Repository\UserRepository;
use App\Repository\PushTokensRepository;


class PurshasesController extends AbstractController
{

    /**
     * @Route("/purshases", name="purshases")
     */
    public function purshases(PurshaseRepository $purshasesRepo)
    {
      $user = $this->getUser();
      $total = array();
      $purshases = $purshasesRepo->findBy(
        ['user' => $user]
      );

      foreach( $purshases as $purshase ){
        $total[ $purshase->getId() ] =  $purshase->getTotal();
      }

      return $this->render('purshases.html.twig',[
        'purshases' => $purshases,
        'total' => $total
      ]);
    }

    /**
    * @Route("/recap", name="recap")
    */
    public function recap(ShopbagService $shopService) {
        $shopBag = $shopService->getShopbag();
        $user = $this->getUser();
        $amount = $shopService->getTotalAmount();

        if($user->getFidelityPoint()%9 == 0 && $user->getFidelityPoint() != 0) {
          $amount = $amount / 2;
        }

        \Stripe\Stripe::setApiKey('sk_test_xX7AdRlMhvKQZs1wImypS5wY00MDBgmTIc');

        if($shopService->getTotalAmount()) {
          $intent = \Stripe\PaymentIntent::create([
              'amount' => $amount*100,
              'currency' => 'eur'
          ]);
          $clientSecret = $intent->client_secret;
        } else {
          $clientSecret = "";
        }

        return $this->render('recap.html.twig',[
            'shopbag' => $shopBag,
            'total' => $amount,
            'client_secret' => $clientSecret
        ]);
    }

    /**
    * @Route("/shopbag/add", name="addToMenuShopbag")
    */
    public function addToMenuShopbag(Request $request, MenuRepository $menuRepository, ProductRepository $repo, ShopbagService $shopbag, IngredientsRepository $ingredientRepo) {

      $data = $request->request->all();
      $menu = new PurshaseMenus();
      $menu->setId(-1);
      $menu->setFormule($menuRepository->find($data["menuId"]));
      $menu->setCustomerComment($data["comment"]);

      foreach($data["products"] as $productId) {
        $product = $repo->find($productId);
        $product->resetIngredients();
        if($product->getCategory()->getName() == "sandwich"){
            $ing = [];
            foreach($data["ingredients"] as $ingredientId){
                $ingredient = $ingredientRepo->find($ingredientId);
                $product->addIngredient($ingredient);
                $ing[] = $ingredient->getName();
            }
            $menu->setIngredients(implode(',', $ing));
        }
        $menu->addContent($product);
      }
      $shopbag->addMenu($menu);

      return $this->json(['menu' => $menu]);
    }

    /**
    * @Route("/shopbag/remove/{index}", name="removeFromShopbag")
  */
    public function removeFromShopbag(int $index, ShopbagService $shopbag) {
        $shopbag->removeMenu($index);
        return $this->json(['menuId' => $index]);
    }

    //partie supplements

    /**
    * @Route("/shopbag/addProduct", name="addProductToShopbag")
    */
    public function addProductToShopbag(Request $request, ProductRepository $productRepository, ShopbagService $shopbag) {
        $data = $request->request->all();
        $purshaseProduct = new PurshaseProducts();
        $purshaseProduct->setProduct($productRepository->find($data["id"]));
        $purshaseProduct->setId(-1);
        $shopbag->addAdditionalProduct($purshaseProduct);

        return $this->json($purshaseProduct);
    }

    /**
    * @Route("/shopbag/removeProduct/{index}", name="removeProductFromShopbag")
    */
    public function removeProductFromShopbag(int $index, ShopbagService $shopbag) {
        $shopbag->removeAdditionalProduct($index);
        return $this->json(['productId' => $index]);
    }

    /**
    * @Route("/process_purchase", name="process_purchase", condition="request.isXMLHttpRequest()")
    */
    public function process_purchase(Request $request,
        PushTokensRepository $tokensRepo, PushNotificationService $pushService,
        MailSenderService $mailService,
        MailerInterface $mailer,
        PurshaseRepository $purchaseRepo,
        ShopbagService $shopService,
        EntityManagerInterface $em) {

      $data = $request->request->all();
      $shopbag = $shopService->getShopbag();
      $user = $this->getUser();

      $purchase = new Purshase();
      $purchase->setPaid($data["paid"]);


      //compute trustScore
      $purchaseNumber = sizeof($user->getPurshases());
      $score = 0;

      if($purchaseNumber < 3) {
        $score = $score + 2;
      } else if($purchaseNumber < 6) {
        $score = $score + 4;
      } else if($purchaseNumber < 9) {
        $score = $score + 6;
      }

      $amount = $shopService->getTotalAmount();
      if($amount < 10) {
        $score = $score + 4;
      } else if($amount < 20) {
        $score = $score + 3;
      } else if($amount < 30) {
        $score = $score + 2;
      } else if($amount < 40) {
        $score = $score + 1;
      }

      $dateStr = $data["deliveryHour"];
      $date = date_create_from_format("Y-m-d H:i:s", $dateStr);
      $purchase->setDeliveryHour($date);
      $purchase->setDate(date('c'));
      $purchase->setUser($this->getUser());
      $purchase->setStatus("waiting");
      $purchase->setTrustScore($score);

      if($user->getFidelityPoint()%9 == 0 && $user->getFidelityPoint() != 0) {
        $purchase->setTotal($shopService->getTotalAmount()/2);
      } else {
        $purchase->setTotal($shopService->getTotalAmount());
      }

      foreach($shopbag["menu"] as $menu) {
        $purchase->addPurshaseMenus($menu);
      }

      $fetchedProduct = [];
      $supplementToFlush = [];

      // updating qty
      foreach($shopbag["supplement"] as $supplement) {
        $supplement->setQty(1);
        if(in_array($supplement->getProduct(), $fetchedProduct)) {
          foreach ($supplementToFlush as $supp) {
            if($supp->getProduct() == $supplement->getProduct()) {
              $supp->setQty($supp->getQty() + 1);
            }
          }
        } else {
          $supplementToFlush[] = $supplement;
          $fetchedProduct[] = $supplement->getProduct();
        }
      }

      $shopbag["supplement"] = $supplementToFlush;

      foreach($shopbag["supplement"] as $supplement) {
          $purchase->addPurshaseProduct($supplement);
      }

      if($purchase->getTotalAmount() >= 10 || ($user->getFidelityPoint()%9 == 0 && $user->getFidelityPoint() != 0)) {
        $user = $this->getUser();
        $user->setFidelityPoint($user->getFidelityPoint() + 1);
        $em->flush();
      }

      $em->persist($purchase);
      $em->flush();

      // send validation mail
      $mailService->buildSuccessfullPurchase($purchase);
      $mailService->sendEmail();

      // send app push notification
      // retrieve all device tokens
      $tokens = $tokensRepo->findAll();
      // send notification to devices
      $pushService->sendNewPurchaseNotification($purchase, $tokens);

      $shopService->resetShopbag();

      return $this->json([
        "status" => 201
      ]);
    }

    /**
    * @Route("/updatePurchase", name="updatePurchase")
    */
    public function updatePurchase(Request $request, MailSenderService $mailService, PurshaseRepository $purchaseRepo, EntityManagerInterface $em) {
        $data = $request->request->all();

        $purchase = $purchaseRepo->find($data["purchaseId"]);
        $purchase->setStatus($data["status"]);

        $mailService->buildCommandStatus($purchase);
        $mailService->sendEmail();

        $em->persist($purchase);
        $em->flush();

        return $this->json($data);
    }

    /**
    * @Route("/sendNotifications", name="sendNotifications")
    */
    public function sendNotifications(
        Request $request,
        MailSenderService $mailService,
        PurshaseRepository $purchaseRepo,
        PushTokensRepository $tokensRepo,
        PushNotificationService $pushService) {

        $data = json_decode($request->getContent(), true);

        $purchase = $purchaseRepo->find($data["id"]);

        // send validation mail
        $mailService->buildSuccessfullPurchase($purchase);
        $mailService->sendEmail();

        // send app push notification
        // retrieve all device tokens
        $tokens = $tokensRepo->findAll();
        // send notification to devices
        $pushService->sendNewPurchaseNotification($purchase, $tokens);

        return $this->json($data);
    }

    /**
    * @Route("/test", name="test")
    */
    public function test(Request $request, MailSenderService $mailService, PurshaseRepository $purchaseRepo, EntityManagerInterface $em) {
        $data = $request->request->all();

        $purchase = $purchaseRepo->find(1118);
        // send validation mail
        $mailService->buildCommandStatus($purchase);
        $mailService->sendEmail();

        return $this->render("test.html.twig");
    }
}
