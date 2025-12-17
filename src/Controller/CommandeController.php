<?php

namespace App\Controller;

use App\Repository\CommandeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CommandeController extends AbstractController
{
    #[Route('/commandes', name: 'app_commandes')]
    public function index(CommandeRepository $repository): Response
    {
        return $this->render('commande/index.html.twig', [
            'commandes' => $repository->findAll(),
        ]);
    }
}
