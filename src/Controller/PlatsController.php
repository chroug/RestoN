<?php

namespace App\Controller;

use App\Entity\Plats;
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

    #[Route('/plat/{id}', name: 'app_plat_show', methods: ['GET'])]
    public function show(Plats $plat): Response
    {
        return $this->render('plats/show.html.twig', [
            'plat' => $plat,
        ]);
    }
}
