<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Purshase;
use Faker\Provider\DateTime;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
// Entities
use App\Entity\ProductCategories;
use App\Entity\Menu;
use App\Entity\Product;
use App\Entity\Ingredients;
use App\Entity\User;

class AppFixtures extends Fixture
{
    private $userRepo;
    private $encoder;

    public function __construct(UserRepository $userRepo, UserPasswordEncoderInterface $encoder)
    {
        $this->userRepo = $userRepo;
        $this->encoder = $encoder;
    }


    public function load(ObjectManager $manager)
    {

        //$user = $this->userRepo->find(14);

        /**
        * Generate users account
        */
        $erwann = new User();
        $antoine = new User();

        $erwann
            ->setEmail("erwann.leroux1@gmail.com")
            ->setRoles(["ROLE_ADMIN"])
            ->setPassword($this->encoder->encodePassword($erwann, "azerty123"))
            ->setFirstname("Erwann")
            ->setLastname("LE ROUX")
            ->setAddress("La Chosterie")
            ->setCp("61230")
            ->setCity("Ménil-hubert en exmes")
            ->setPhone("0649786217")
            ->setFidelityPoint(0)
            ->setDiscordId("301993628979691520");

        $antoine
            ->setEmail("21504712@etu.unicaen.fr")
            ->setRoles(["ROLE_ADMIN"])
            ->setPassword($this->encoder->encodePassword($antoine, "azerty123"))
            ->setFirstname("Antoine")
            ->setLastname("GONZALEZ")
            ->setAddress("Rue du php")
            ->setCp("14000")
            ->setCity("Caen")
            ->setPhone("0616158962")
            ->setFidelityPoint(0)
            ->setDiscordId("335062294012624909");

        $manager->persist($erwann);
        $manager->persist($antoine);


        /**
        * Categories for products
        */
        $boisson  = new ProductCategories();
        $accomp   = new ProductCategories();
        $sauce    = new ProductCategories();
        $sandwich = new ProductCategories();

        $boisson->setId(1)->setName("boisson");
        $accomp->setId(2)->setName("accompagnement");
        $sauce->setId(3)->setName("sauce");
        $sandwich->setId(4)->setName("sandwich");

        $manager->persist($boisson);
        $manager->persist($accomp);
        $manager->persist($sauce);
        $manager->persist($sandwich);

         /**
         * Ingrédients
         */
        $tomate = new Ingredients();
        $viande_kebab = new Ingredients();
        $oignons = new Ingredients();
        $cornichons = new Ingredients();
        $pain_burger = new Ingredients();
        $pain_kebab = new Ingredients();
        $cheddar = new Ingredients();
        $salade = new Ingredients();
        $classique_menu = new Ingredients();
        $blanche = new Ingredients();
        $ketchup = new Ingredients();
        $mayo = new Ingredients();
        $samu = new Ingredients();
        $algerienne = new Ingredients();
        $andalouse = new Ingredients();
        $pain_galette = new Ingredients();
        $steack = new Ingredients();
        $poulet = new Ingredients();

        $steack
            ->setName("steack haché")
            ->setPicPath("images/ingredients/steack.png")
            ->setStockQty(10)
            ->setType("garniture");

        $tomate
            ->setName("tomate")
            ->setPicPath("images/ingredients/tomate.png")
            ->setStockQty(10)
            ->setType("garniture");

        $salade
             ->setName("salade")
             ->setPicPath("images/ingredients/salade.png")
             ->setStockQty(10)
             ->setType("garniture");

        $viande_kebab
            ->setName("viande_kebab")
            ->setPicPath("images/ingredients/viande_kebab.png")
            ->setStockQty(20)
            ->setType("garniture");

        $oignons
            ->setName("oignons")
            ->setPicPath("images/ingredients/oignons.png")
            ->setStockQty(24)
            ->setType("garniture");

        $cornichons
            ->setName("cornichons")
            ->setPicPath("images/ingredients/cornichon.png")
            ->setStockQty(23)
            ->setType("garniture");

        $pain_burger
            ->setName("pain_burger")
            ->setPicPath("images/ingredients/pain_burger.png")
            ->setStockQty(23)
            ->setType("pain");

        $pain_kebab
            ->setName("pain_kebab")
            ->setPicPath("images/ingredients/pain_kebab.png")
            ->setStockQty(23)
            ->setType("pain");

        $pain_galette
             ->setName("pain_galette")
             ->setPicPath("images/ingredients/pain_galette.png")
             ->setStockQty(23)
             ->setType("pain");

        $cheddar
            ->setName("tranche_cheddar")
            ->setPicPath("images/ingredients/tranche_cheddar.png")
            ->setStockQty(4)
            ->setType("garniture");

        $poulet
            ->setName("croquette de poulet")
            ->setPicPath("images/ingredients/poulet.png")
            ->setStockQty(4)
            ->setType("garniture");

        $blanche
            ->setName("sauce blanche")
            ->setPicPath("")
            ->setStockQty(34)
            ->setType("sauce");

        $ketchup
            ->setName("ketchup")
            ->setPicPath("")
            ->setStockQty(10)
            ->setType("sauce");

        $mayo
            ->setName("sauce mayonnaise")
            ->setPicPath("")
            ->setStockQty(10)
            ->setType("sauce");

        $samu
            ->setName("sauce samourai")
            ->setPicPath("")
            ->setStockQty(10)
            ->setType("sauce");

        $algerienne
            ->setName("sauce algerienne")
            ->setPicPath("")
            ->setStockQty(10)
            ->setType("sauce");

        $andalouse
            ->setName("sauce andalouse")
            ->setPicPath("")
            ->setStockQty(10)
            ->setType("sauce");

        $ingredientList = [$tomate, $viande_kebab, $oignons, $cornichons,
        $pain_burger, $pain_kebab, $cheddar, $salade, $pain_galette,
        $blanche, $ketchup, $mayo, $samu, $algerienne, $andalouse, $steack, $poulet];

        foreach($ingredientList as $ing) {
            $manager->persist($ing);
        }

        /**
        * Products
        */
        $kebab = new Product();
        $galette = new Product();
        $cheese = new Product();
        $mega = new Product();
        $giant = new Product();
        $chicken = new Product();

        $kebab
            ->setName("Sandwich Kebab")
            ->setPrice(5)
            ->setCategory($sandwich)
            ->addIngredient($tomate)
            ->addIngredient($viande_kebab)
            ->addIngredient($oignons)
            ->addIngredient($salade)
            ->addIngredient($pain_kebab);

        $galette
            ->setName("Galette")
            ->setPrice(5)
            ->setCategory($sandwich)
            ->addIngredient($tomate)
            ->addIngredient($viande_kebab)
            ->addIngredient($oignons)
            ->addIngredient($salade)
            ->addIngredient($pain_galette);

        $cheese
            ->setName("Cheese Burger")
            ->setPrice(5)
            ->setCategory($sandwich)
            ->addIngredient($tomate)
            ->addIngredient($steack)
            ->addIngredient($oignons)
            ->addIngredient($salade)
            ->addIngredient($pain_burger)
            ->addIngredient($cheddar);

        $mega
            ->setName("Mega Burger")
            ->setPrice(7)
            ->setCategory($sandwich)
            ->addIngredient($tomate)
            ->addIngredient($cornichons)
            ->addIngredient($steack)
            ->addIngredient($oignons)
            ->addIngredient($salade)
            ->addIngredient($pain_burger);

        $giant
            ->setName("Geant Burger")
            ->setPrice(6)
            ->setCategory($sandwich)
            ->addIngredient($tomate)
            ->addIngredient($cornichons)
            ->addIngredient($steack)
            ->addIngredient($oignons)
            ->addIngredient($salade)
            ->addIngredient($pain_burger);

        $chicken
            ->setName("Chicken Burger")
            ->setPrice(5)
            ->setCategory($sandwich)
            ->addIngredient($tomate)
            ->addIngredient($cornichons)
            ->addIngredient($poulet)
            ->addIngredient($oignons)
            ->addIngredient($salade)
            ->addIngredient($pain_burger);

        $cola = new Product();
        $fanta = new Product();
        $iceTea = new Product();
        $orangina = new Product();
        $evian = new Product();

        $cola
            ->setName("Coca Cola")
            ->setPrice(1)
            ->setCategory($boisson)
            ->setPicture("images/coca.png")
            ->setDescription("Boisson Fraiche");

        $fanta
            ->setName("Fanta")
            ->setPrice(1)
            ->setCategory($boisson)
            ->setPicture("images/fanta.png")
            ->setDescription("Boisson Fraiche");

        $iceTea
            ->setName("Ice Tea")
            ->setPrice(1)
            ->setCategory($boisson)
            ->setPicture("images/lipton.png")
            ->setDescription("Boisson Fraiche");

        $orangina
            ->setName("Orangina")
            ->setPrice(1)
            ->setCategory($boisson)
            ->setPicture("images/orangina.png")
            ->setDescription("Boisson Fraiche");

        $evian
            ->setName("Evian 33cl")
            ->setPrice(0.5)
            ->setCategory($boisson)
            ->setPicture("images/evian.png")
            ->setDescription("Boisson Fraiche");

        $potatoes = new Product();
        $frites = new Product();

        $potatoes
            ->setName("Potatoes")
            ->setPrice(1)
            ->setCategory($accomp)
            ->setPicture("images/frite.png")
            ->setDescription("Frites savoureuses et croustillantes");

        $frites
            ->setName("Frites")
            ->setPrice(1)
            ->setCategory($accomp)
            ->setPicture("images/potatoes.png")
            ->setDescription("Potatoes savoureuses et croustillantes");

        $products = [$frites, $potatoes, $evian, $orangina,
            $fanta, $iceTea, $cola, $kebab, $galette, $cheese,
            $mega, $giant, $chicken];

        foreach($products as $product) {
            $manager->persist($product);
        }

        /**
        * Menus
        */
        $classique_menu = new Menu();
        $galette_menu = new Menu();
        $cheese_menu = new Menu();
        $geant_menu = new Menu();
        $mega_menu = new Menu();
        $chicken_menu = new Menu();

        $classique_menu
            ->setPrice(7.5)
            ->setName("Classique")
            ->setPicture("images/menu_classique.png")
            ->setCategory("kebab")
            ->setSandwich($kebab)
            ->setDescription("Kebab classique comprenant une boisson et un accompagnement");

        $galette_menu
            ->setPrice(7.5)
            ->setName("Galette")
            ->setPicture("images/menu_galette.png")
            ->setCategory("kebab")
            ->setSandwich($galette)
            ->setDescription("Kebab avec une galette comprenant une boisson et un accompagnement");

        $cheese_menu
            ->setPrice(5.5)
            ->setName("Cheese")
            ->setPicture("images/menu_cheese.png")
            ->setCategory("burger")
            ->setSandwich($cheese)
            ->setDescription("Hamburger avec cheddar AOP comprenant une boisson et un accompagnement");

        $geant_menu
            ->setPrice(7.5)
            ->setName("Géant")
            ->setPicture("images/menu_giant.png")
            ->setCategory("burger")
            ->setSandwich($giant)
            ->setDescription("Hamburger double steack comprenant une boisson et un accompagnement");

        $mega_menu
            ->setPrice(9)
            ->setName("Mega")
            ->setPicture("images/menu_mega.png")
            ->setCategory("burger")
            ->setSandwich($mega)
            ->setDescription("Hamburger triple steack comprenant une boisson et un accompagnement");

        $chicken_menu
            ->setPrice(7.5)
            ->setName("Chicken")
            ->setPicture("images/menu_chicken.png")
            ->setCategory("burger")
            ->setSandwich($chicken)
            ->setDescription("Hamburger au poulet comprenant une boisson et un accompagnement");

        $menus = [$classique_menu, $galette_menu, $cheese_menu, $geant_menu, $mega_menu, $chicken_menu];

        foreach($menus as $menu) {
            $manager->persist($menu);
        }

        /**
        * Faker part
        */

        $faker = \Faker\Factory::create('fr_FR');
        $userList = [$erwann, $antoine];
        for($i = 0; $i < 100; $i++) {
            $user = new User();

            $city = $faker->city;
            $mail = $faker->email;
            $postal_code = $faker->postcode();
            $phone = "06".$faker->randomNumber(8);
            $fsname = $faker->firstname;
            $lstname = $faker->lastname;
            $street = $faker->streetAddress;

            $user
                ->setEmail($mail)
                ->setRoles(["ROLE_MEMBER"])
                ->setPassword($this->encoder->encodePassword($user, "azerty123"))
                ->setFirstname($fsname)
                ->setLastname($lstname)
                ->setAddress($street)
                ->setCp(substr($postal_code, 0, 5))
                ->setCity($city)
                ->setPhone($phone)
                ->setFidelityPoint(0);

            $manager->persist($user);
            // generate 10 purchase per user
            for($j = 0; $j < 10; $j++) {
                $purchase = new Purshase();
                $purchase->setUser($user);
                $purchase->setStatus("waiting");

                $date = \Faker\Provider\DateTime::dateTimeBetween($startDate = '-55 days', $endDate = 'now', $timezone = null);
                $purchase->setDate($date->format("c"));
                $purchase->setPaid($faker->numberBetween($min = 0, $max = 1));
                $purchase->setTrustScore($faker->numberBetween($min = 1, $max = 10));
                $purchase->setDeliveryHour($date);
                $purchase->setTotal($faker->numberBetween($min = 10, $max = 50));
                $manager->persist($purchase);
            }
        }

        $manager->flush();
    }
}
