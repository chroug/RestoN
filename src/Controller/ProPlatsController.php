<?php

namespace App\Controller;

use App\Entity\Plats;
use App\Form\PlatsType;
use App\Repository\PlatsRepository;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

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

    #[Route('/{id}/edit', name: 'app_pro_plats_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Plats $plat, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {

        if ($plat->getRestaurant() !== $this->getUser()->getRestaurant()) {
            throw $this->createAccessDeniedException("Vous ne pouvez pas modifier le plat d'un autre restaurant.");
        }

        $form = $this->createForm(PlatsType::class, $plat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('kernel.project_dir').'/public/uploads/plats',
                        $newFilename
                    );

                    $plat->setImage($newFilename);
                } catch (FileException $e) {

                }
            }

            $entityManager->flush();

            $this->addFlash('success', 'Le plat a été modifié avec succès !');
            return $this->redirectToRoute('app_pro_plats_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pro_plats/edit.html.twig', [
            'plat' => $plat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pro_plats_delete', methods: ['POST'])]
    public function delete(Request $request, Plats $plat, EntityManagerInterface $entityManager): Response
    {

        if ($plat->getRestaurant() !== $this->getUser()->getRestaurant()) {
            throw $this->createAccessDeniedException();
        }

        if ($this->isCsrfTokenValid('delete'.$plat->getId(), $request->request->get('_token'))) {
            $entityManager->remove($plat);
            $entityManager->flush();
            $this->addFlash('success', 'Le plat a été supprimé.');
        }

        return $this->redirectToRoute('app_pro_plats_index', [], Response::HTTP_SEE_OTHER);
    }
}
