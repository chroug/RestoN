<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ServeurType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/patron/serveurs')]
#[IsGranted('ROLE_PATRON')]
class PatronServeurController extends AbstractController
{
    #[Route('/', name: 'app_patron_serveur_index')]
    public function index(UserRepository $userRepository): Response
    {
        $restaurant = $this->getUser()->getRestaurant();

        $serveurs = $userRepository->findBy([
            'restaurant' => $restaurant,
        ]);

        return $this->render('patron_serveur/index.html.twig', [
            'serveurs' => $serveurs,
        ]);
    }
}
