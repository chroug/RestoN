<?php

namespace App\Controller;

use App\Entity\Patron;
use App\Form\PatronType;
use App\Repository\PatronRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/patron')]
#[IsGranted('ROLE_GERANT')] // Sécurisé pour le Gérant uniquement
class AdminPatronController extends AbstractController
{
    #[Route('/', name: 'app_admin_patron_index', methods: ['GET'])]
    public function index(PatronRepository $patronRepository): Response
    {
        return $this->render('admin_patron/index.html.twig', [
            'patrons' => $patronRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_patron_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): Response
    {
        $patron = new Patron();
        // On passe 'is_edit' => false pour rendre le mot de passe obligatoire
        $form = $this->createForm(PatronType::class, $patron, ['is_edit' => false]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $patron->setRoles(['ROLE_PATRON']);
            $patron->setIsVerified(true); // On valide automatiquement le compte créé par l'admin

            // Hachage du mot de passe
            $plainPassword = $form->get('plainPassword')->getData();
            if ($plainPassword) {
                $patron->setPassword($passwordHasher->hashPassword($patron, $plainPassword));
            }

            // Gestion de la liaison Restaurant
            $restaurant = $patron->getRestaurant();
            if ($restaurant) {
                $restaurant->setPatron($patron);
                $em->persist($restaurant);
            }

            $em->persist($patron);
            $em->flush();

            $this->addFlash('success', 'Le compte Patron a été créé avec succès.');
            return $this->redirectToRoute('app_admin_patron_index');
        }

        return $this->render('admin_patron/new.html.twig', [
            'form' => $form->createView(),
            'patron' => $patron
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_patron_edit', methods: ['GET', 'POST'])]
    public function edit(Patron $patron, Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): Response
    {
        $form = $this->createForm(PatronType::class, $patron, ['is_edit' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Changement de mot de passe optionnel
            $plainPassword = $form->get('plainPassword')->getData();
            if ($plainPassword) {
                $patron->setPassword($passwordHasher->hashPassword($patron, $plainPassword));
            }

            // Mise à jour liaison Restaurant
            $restaurant = $patron->getRestaurant();
            if ($restaurant) {
                $restaurant->setPatron($patron);
            }

            $em->flush();

            $this->addFlash('success', 'Le compte Patron a été modifié.');
            return $this->redirectToRoute('app_admin_patron_index');
        }

        return $this->render('admin_patron/edit.html.twig', [
            'form' => $form->createView(),
            'patron' => $patron
        ]);
    }

    #[Route('/{id}', name: 'app_admin_patron_delete', methods: ['POST'])]
    public function delete(Patron $patron, EntityManagerInterface $em, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete'.$patron->getId(), $request->request->get('_token'))) {
            // Si on supprime le patron, on détache le restaurant pour ne pas le supprimer en cascade (selon tes besoins)
            if ($patron->getRestaurant()) {
                $patron->getRestaurant()->setPatron(null);
            }

            $em->remove($patron);
            $em->flush();
            $this->addFlash('success', 'Patron supprimé.');
        }

        return $this->redirectToRoute('app_admin_patron_index');
    }
}
