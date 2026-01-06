<?php

namespace App\Controller;

use App\Repository\PlatsStockRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class StockController extends AbstractController
{
    #[Route('/stock', name: 'app_stock')]
    public function index(PlatsStockRepository $repository): Response
    {
        return $this->render('stock/index.html.twig', [
            'stocks' => $repository->findAll(),
        ]);
    }
}
