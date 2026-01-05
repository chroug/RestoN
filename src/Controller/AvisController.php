<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Entity\Client;
use App\Entity\Restaurant; // 👈 On importe Restaurant au lieu de Commande
use App\Form\AvisType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AvisController extends AbstractController
{
    #[Route('/restaurant/{id}/avis', name: 'app_avis_new')]
    #[IsGranted('ROLE_CLIENT')]
    public function new(Restaurant $restaurant, Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();


        if (!$user instanceof Client) {
            throw $this->createAccessDeniedException("Seuls les clients peuvent noter.");
        }

        $avis = new Avis();
        $form = $this->createForm(AvisType::class, $avis);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $avis->setClient($user);
            $avis->setRestaurant($restaurant);
            $avis->setDate(new \DateTimeImmutable());

            $em->persist($avis);
            $em->flush();

            $this->addFlash('success', 'Merci ! Votre avis a été publié.');

            return $this->redirectToRoute('app_restaurant_show', [
                'id' => $restaurant->getId(),
                '_fragment' => 'section-avis'
            ]);
        }

        return $this->render('avis/new.html.twig', [
            'form' => $form->createView(),
            'restaurant' => $restaurant
        ]);
    }
}
