<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;
use App\Entity\Comments;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;
use App\Repository\ProductCategoriesRepository;
use App\Repository\CommentsRepository;
use App\Repository\MenuRepository;
use App\Repository\PurshaseRepository;
use App\Repository\PurshaseMenuProductRepository;
use App\Repository\ProductRepository;
use App\Repository\IngredientsRepository;
use App\Service\ShopbagService;


class MainController extends AbstractController
{

    /**
     * @Route("/", name="root")
     */
    public function index()
    {
        return $this->render('home.html.twig');
    }

    /**
     * @Route("/menus", name="menus")
     */
    public function menus(MenuRepository $menuRepository,
                          ProductCategoriesRepository $categoriesRepository,
                          IngredientsRepository $ingredientsRepo,
                          Request $request,
                          ShopbagService $shopService)
    {
        $shopBag = $shopService->getShopbag();

        $menus = $menuRepository->findAll();

        $drinks = $categoriesRepository->findOneBy(['name' => 'boisson']);
        $sauces = $ingredientsRepo->findBy(['type' => 'sauce']);
        $accompaniements = $categoriesRepository->findOneBy(['name' => 'accompagnement']);

        //dump($shopBag);

        return $this->render('menus.html.twig',[
                'menus' => $menus,
                'drinks' => $drinks->getProducts(),
                'sauces' => $sauces,
                'accompaniements' => $accompaniements->getProducts(),
                'shopbag' => $shopBag
        ]);
    }

    /**
     * @Route("/garanties", name="garanties")
     */
    public function garanties()
    {
        return $this->render('garanties.html.twig');
    }

    /**
     * @Route("/atelier", name="atelier")
     */
    public function atelier(IngredientsRepository $ingredientsRepo)
    {
        $ingredients = $ingredientsRepo->findAll();
        return $this->render('atelier.html.twig',[
            'ingredients' => $ingredients,
        ]);
    }

    /**
     * @Route("/comments", name="comments")
     */
    public function comment(CommentsRepository $commentsRepository,
                            Request $request,
                            UserRepository $userRepository,
                            EntityManagerInterface $manager)
    {
        $comments = $commentsRepository->findBy(array(), array('date' => 'DESC'));

        $comment = new Comments();
        $user    = $this->getUser();
        $comment->setUser($user);
        $comment->setDate(date_create());

        $form = $this->createFormBuilder($comment)
            ->add('stars', IntegerType::class, [
                'invalid_message' => 'Vous devez choisir une note entre 1 et 5',
                'label' => "Etoiles",
                'required' => true,
            ])
            ->add('title', TextType::class, [
                'required' => true,
                'label' => "Titre"
            ])
            ->add('content', TextareaType::class, [
                'required' => true,
                'label' => "Message"
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Ajouter'
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($comment);
            $manager->flush();
            return $this->redirect($request->getUri());
        }

        return $this->render('comment.html.twig', [
            'form' => $form->createView(),
            'comments' => $comments
        ]);
    }

    /**
     * @Route("/myaccount", name="myaccount")
     */
    public function userinfo(Request $request,
                            UserRepository $userRepository,
                            EntityManagerInterface $manager,
                            UserPasswordEncoderInterface $encoder)
    {
        $user = $this->getUser();

        $form = $this->createFormBuilder($user)
            ->add('email', EmailType::class, [
                'label' => "Adresse mail",
                'required' => true,
            ])
            ->add('phone', TelType::class, [
                'required' => true,
                'label' => "Téléphone"
            ])
            ->add('firstname', TextType::class, [
                'required' => true,
                'label' => "Prénom"
            ])
            ->add('lastname', TextType::class, [
                'required' => true,
                'label' => "Nom"
            ])
            ->add('address', TextType::class, [
                'label' => "Rue, Lieu-dit"
            ])
            ->add('cp', TextType::class, [
                'label' => "Code postal"
            ])
            ->add('DiscordID', TextType::class, [
                'required' => false,
                'label' => "ID Discord"
            ])
            ->add('city', TextType::class, [
                'label' => "Ville"
            ])
            ->add('save', SubmitType::class, ['label' => 'Modifier'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $user->getPassword();
            $encoded = $encoder->encodePassword($user, $plainPassword);
            $user->setPassword($encoded);

            $manager->persist($user);
            $manager->flush();
            $this->addFlash('success', 'Vos informations personnelles ont été modifié avec succès');
        }

        return $this->render('userinfo.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/fidelity", name="fidelity")
     */
    public function fidelity(PurshaseRepository $repo){
        $user = $this->getUser();
        $fidelityCount = $user->getFidelityPoint();
        return $this->render('fidelity.html.twig', ["fidelityCount" => $fidelityCount]);
    }


}
