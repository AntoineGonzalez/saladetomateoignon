<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\ProductRepository;
use App\Repository\MenuRepository;
use App\Entity\Product;
use App\Entity\Menu;
use App\Entity\PurshaseMenus;
use App\Entity\PurshaseProducts;
use Doctrine\ORM\EntityManagerInterface;

class ShopbagService
{
    private $shopbag;

    private $session;

    public function __construct(EntityManagerInterface $em, SessionInterface $session, MenuRepository $menuRepo, ProductRepository $productRepo)
    {
        $this->session = $session;

        $bag = $this->session->get('shopbag');

        if(!is_array($bag)) {
            $this->shopbag = [
                "menu" => [],
                "supplement" => []
            ];
            $this->session->set('shopbag', $this->shopbag);
        } else {
            $this->shopbag = $bag;

            foreach ($this->shopbag["supplement"] as $supplement) {
              $supplement->setProduct($em->merge($supplement->getProduct()));
            }

            foreach ($this->shopbag["menu"] as $menu) {
              $menu->setFormule($em->merge($menu->getFormule()));
              $content = $menu->getContent();
              foreach ($content as $prod) {
                $menu->removeContent($prod);
                $prod->setCategory($em->merge($prod->getCategory()));
                $menu->addContent($em->merge($prod));
              }
            }
        }
    }

    public function getShopbag()
    {
       return $this->shopbag;
    }

    public function getTotalAmount() {
        $amount = 0;
        foreach($this->shopbag["menu"] as $menu) {
            $amount += $menu->getFormule()->getPrice();
        }

        foreach($this->shopbag["supplement"] as $purshasedProduct) {
            $amount += $purshasedProduct->getProduct()->getPrice();
        }

        return $amount;
    }

    public function addMenu(PurshaseMenus $menu) {
        $this->shopbag["menu"][] = $menu;
        $this->session->set('shopbag', $this->shopbag);
    }

    public function removeMenu($index) {
        unset($this->shopbag["menu"][$index]);
        $this->shopbag["menu"] = array_values($this->shopbag["menu"]);
        $this->session->set('shopbag', $this->shopbag);
    }

    public function addAdditionalProduct(PurshaseProducts $product) {
        $this->shopbag["supplement"][] = $product;
        $this->session->set('shopbag', $this->shopbag);
    }

    public function removeAdditionalProduct($index) {
        unset($this->shopbag["supplement"][$index]);
        $this->shopbag["supplement"] = array_values($this->shopbag["supplement"]);
        $this->session->set('shopbag', $this->shopbag);
    }

    public function resetShopbag() {
      $this->shopbag = [
          "menu" => [],
          "supplement" => []
      ];
      $this->session->set('shopbag', $this->shopbag);
    }
}

?>
