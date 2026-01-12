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

    #[Route('/new', name: 'app_patron_serveur_new')]
    public function new(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $hasher): Response
    {
        $serveur = new User();

        $serveur->setRestaurant($this->getUser()->getRestaurant());

        $serveur->setRoles(['ROLE_SERVEUR']);

        $form = $this->createForm(ServeurType::class, $serveur, ['is_edit' => false]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $form->get('plainPassword')->getData();
            if ($password) {
                $serveur->setPassword($hasher->hashPassword($serveur, $password));
            }

            $em->persist($serveur);
            $em->flush();

            $this->addFlash('success', 'Le serveur a bien été ajouté !');
            return $this->redirectToRoute('app_patron_serveur_index');
        }

        return $this->render('patron_serveur/new.html.twig', [
            'form' => $form->createView(),
            'editMode' => false
        ]);
    }
}
