<?php

namespace App\Controller;

use App\Entity\Plats;
use App\Form\PlatsType;
use App\Repository\PlatsRepository;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/patron/plats')]
#[IsGranted('ROLE_PATRON')]
class ProPlatsController extends AbstractController
{
    #[Route('/', name: 'app_pro_plats_index', methods: ['GET'])]
    public function index(PlatsRepository $platsRepository): Response
    {
        $restaurant = $this->getUser()->getRestaurant();

        if (!$restaurant) {

            throw $this->createNotFoundException("Aucun restaurant associé à votre compte.");
        }

        $plats = $platsRepository->findBy(['restaurant' => $restaurant]);

        return $this->render('pro_plats/index.html.twig', [
            'plats' => $plats,
        ]);
    }

    #[Route('/new', name: 'app_pro_plats_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $plat = new Plats();
        $plat->setRestaurant($this->getUser()->getRestaurant());

        $form = $this->createForm(PlatsType::class, $plat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($plat);
            $entityManager->flush();

            return $this->redirectToRoute('app_pro_plats_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pro_plats/new.html.twig', [
            'plat' => $plat,
            'form' => $form,
        ]);
    }
}
