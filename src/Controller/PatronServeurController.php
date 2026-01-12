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
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

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
    public function new(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $hasher,
        MailerInterface $mailer
    ): Response
    {
        $serveur = new User();
        $serveur->setRestaurant($this->getUser()->getRestaurant());
        $serveur->setRoles(['ROLE_SERVEUR']);
        $serveur->setIsVerified(true);

        $form = $this->createForm(ServeurType::class, $serveur, ['is_edit' => false]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $plainPassword = bin2hex(random_bytes(4));

            $serveur->setPassword($hasher->hashPassword($serveur, $plainPassword));

            $em->persist($serveur);
            $em->flush();

            $email = (new Email())
                ->from('no-reply@reston.fr')
                ->to($serveur->getEmail())
                ->subject('Bienvenue chez RestoN - Vos accès')
                ->html(
                    '<h3>Bienvenue dans l\'équipe !</h3>' .
                    '<p>Votre compte serveur a été créé par votre patron.</p>' .
                    '<p>Voici vos identifiants pour vous connecter :</p>' .
                    '<ul>' .
                    '<li><strong>Email :</strong> ' . $serveur->getEmail() . '</li>' .
                    '<li><strong>Mot de passe provisoire :</strong> ' . $plainPassword . '</li>' .
                    '</ul>' .
                    '<p>Pensez à changer votre mot de passe après la première connexion.</p>' .
                    '<a href="http://127.0.0.1:8000/login">Se connecter</a>'
                );

            $mailer->send($email);

            $this->addFlash('success', 'Serveur recruté ! Un email avec ses identifiants lui a été envoyé.');
            return $this->redirectToRoute('app_patron_serveur_index');
        }

        return $this->render('patron_serveur/new.html.twig', [
            'form' => $form->createView(),
            'editMode' => false
        ]);
    }

    #[Route('/{id}/edit', name: 'app_patron_serveur_edit')]
    public function edit(User $serveur, Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $hasher): Response
    {
        if ($serveur->getRestaurant() !== $this->getUser()->getRestaurant()) {
            throw $this->createAccessDeniedException("Vous ne pouvez pas modifier ce serveur.");
        }

        $form = $this->createForm(ServeurType::class, $serveur, ['is_edit' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $form->get('plainPassword')->getData();
            if ($password) {
                $serveur->setPassword($hasher->hashPassword($serveur, $password));
            }

            $em->flush();

            $this->addFlash('success', 'Serveur modifié avec succès !');
            return $this->redirectToRoute('app_patron_serveur_index');
        }

        return $this->render('patron_serveur/new.html.twig', [
            'form' => $form->createView(),
            'editMode' => true
        ]);
    }

    #[Route('/{id}/delete', name: 'app_patron_serveur_delete')]
    public function delete(User $serveur, EntityManagerInterface $em): Response
    {
        if ($serveur->getRestaurant() === $this->getUser()->getRestaurant()) {
            $em->remove($serveur);
            $em->flush();
            $this->addFlash('success', 'Serveur supprimé.');
        }
        return $this->redirectToRoute('app_patron_serveur_index');
    }
}
