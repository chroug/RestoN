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

#[Route('/admin/plats')]
final class AdminPlatsController extends AbstractController
{
    #[Route(name: 'app_admin_plats_index', methods: ['GET'])]
    public function index(PlatsRepository $platsRepository): Response
    {
        return $this->render('admin_plats/index.html.twig', [
            'plats' => $platsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_plats_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $plat = new Plats();
        $form = $this->createForm(PlatsType::class, $plat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($plat);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_plats_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_plats/new.html.twig', [
            'plat' => $plat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_plats_show', methods: ['GET'])]
    public function show(Plats $plat): Response
    {
        return $this->render('admin_plats/show.html.twig', [
            'plat' => $plat,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_plats_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Plats $plat, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PlatsType::class, $plat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_plats_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_plats/edit.html.twig', [
            'plat' => $plat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_plats_delete', methods: ['POST'])]
    public function delete(Request $request, Plats $plat, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$plat->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($plat);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_plats_index', [], Response::HTTP_SEE_OTHER);
    }
}
