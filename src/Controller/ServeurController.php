<?php

namespace App\Controller;

use App\Repository\PlatsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/pro')]
#[IsGranted('ROLE_SERVEUR')]
class ServeurController extends AbstractController
{
    #[Route('/', name: 'app_serveur_dashboard')]
    public function index(): Response
    {
        return $this->render('serveur/index.html.twig');
    }

    #[Route('/plats', name: 'app_serveur_plats')]
    public function voirPlats(PlatsRepository $platsRepository): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        $restaurant = $user->getRestaurant();

        if (!$restaurant) {
            throw $this->createAccessDeniedException("Votre compte serveur n'est lié à aucun restaurant !");
        }
        $plats = $platsRepository->findBy(['restaurant' => $restaurant]);

        return $this->render('serveur/plats.html.twig', [
            'plats' => $plats,
            'restaurant' => $restaurant
        ]);
    }
}
