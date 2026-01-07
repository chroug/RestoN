<?php

namespace App\Controller;

use App\Entity\Horaire;
use App\Entity\Restaurant;
use App\Form\RestaurantType;
use App\Repository\CommandeRepository; // 👈 AJOUT IMPORTANT
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/restaurant')]
class AdminRestaurantController extends AbstractController
{
    #[Route('/{id}/edit', name: 'app_admin_restaurant_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Restaurant $restaurant,
        EntityManagerInterface $em,
        UserRepository $userRepository,
        CommandeRepository $commandeRepository // 👈 INJECTION ICI
    ): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        if (!$this->isGranted('ROLE_GERANT')) {
            if ($restaurant->getPatron() !== $user) {
                return $this->redirectToRoute('app_admin_restaurant_edit', [
                    'id' => $user->getRestaurant()->getId()
                ]);
            }
        }

        // --- 📊 VRAIES STATISTIQUES ---

        // 1. Les Serveurs (Filtrés par rôle)
        $allStaff = $userRepository->findBy(['restaurant' => $restaurant]);
        $serveurs = array_filter($allStaff, function($u) {
            return in_array('ROLE_SERVEUR', $u->getRoles());
        });

        // 2. Le Chiffre d'Affaires Réel
        $chiffreAffaires = $commandeRepository->findChiffreAffaires($restaurant);

        // 3. Le Nombre de Commandes du Jour Réel
        $nbCommandesJour = $commandeRepository->countCommandesDuJour($restaurant);

        // --- FIN STATISTIQUES ---

        // Gestion des horaires par défaut (si vide)
        if ($restaurant->getHoraires()->isEmpty()) {
            $jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
            foreach ($jours as $jour) {
                $horaire = new Horaire();
                $horaire->setJour($jour);
                $horaire->setOuvertureMidi(new \DateTime('12:00'));
                $horaire->setFermetureMidi(new \DateTime('14:30'));
                $horaire->setOuvertureSoir(new \DateTime('19:00'));
                $horaire->setFermetureSoir(new \DateTime('22:30'));

                $restaurant->addHoraire($horaire);
            }
        }

        $form = $this->createForm(RestaurantType::class, $restaurant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($restaurant);
            $em->flush();

            $this->addFlash('success', 'Votre restaurant a été mis à jour avec succès !');

            return $this->redirectToRoute('app_admin_restaurant_edit', ['id' => $restaurant->getId()]);
        }

        return $this->render('admin_restaurant/edit.html.twig', [
            'restaurant' => $restaurant,
            'form' => $form,
            // On envoie les vraies données à la vue
            'serveurs' => $serveurs,
            'chiffreAffaires' => $chiffreAffaires,
            'nbCommandesJour' => $nbCommandesJour,
        ]);
    }
}
