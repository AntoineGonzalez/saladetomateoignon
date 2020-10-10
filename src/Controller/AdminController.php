<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PurshaseRepository;
use App\Repository\IngredientsRepository;

class AdminController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function dashboard(PurshaseRepository $repoPurchase)
    {
        $purchaseTotal = $this->countPurchases($repoPurchase);

        return $this->render('admin/dashboard.html.twig', [
            'purshases_number' => $purchaseTotal,
        ]);
    }

    /**
     * @Route("/stocks", name="stocks")
     */
    public function stocks(IngredientsRepository $ingredientRepo)
    {
        $ingredients = $ingredientRepo->findAll();

        return $this->render('admin/stocks.html.twig', [
            'ingredients' => $ingredients
        ]);
    }

    public function countPurchases($repoPurchase) {
        $totalArticles = $repoPurchase->createQueryBuilder('p')
            ->select('count(p.id)')
            ->getQuery()
            ->getSingleScalarResult();
        return $totalArticles;
    }
}
