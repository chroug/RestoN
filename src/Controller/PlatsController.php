<?php

namespace App\Controller;

use App\Repository\PlatsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PlatsController extends AbstractController
{
    #[Route('/plats', name: 'app_plats')]
    public function index(PlatsRepository $repository): Response
    {
        $lesPlats = $repository->findAll();
        return $this->render('plats/index.html.twig', [
            'plats' => $lesPlats,
        ]);
    }
}
