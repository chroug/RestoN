<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Repository\CommandeRepository;
use App\Repository\PlatsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

class CommandeController extends AbstractController
{
    #[Route('/mes-commandes', name: 'app_mes_commandes')]
    public function index(CommandeRepository $commandeRepository): Response
    {

        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $commandes = $commandeRepository->findBy(
            ['client' => $this->getUser()],
            ['date' => 'DESC']
        );

        return $this->render('commande/index.html.twig', [
            'commandes' => $commandes,
        ]);
    }

    #[Route('/commande/valider', name: 'app_commande_valider')]
    public function valider(Request $request, SessionInterface $session, PlatsRepository $platsRepository, EntityManagerInterface $em): Response
    {
        if (!$this->getUser()) {
            $this->addFlash('danger', 'Connectez-vous pour commander !');
            return $this->redirectToRoute('app_login');
        }

        $panier = $session->get('cart', []);

        if (empty($panier)) {
            $this->addFlash('warning', 'Votre panier est vide.');
            return $this->redirectToRoute('app_home');
        }

        $commande = new Commande();
        $commande->setDate(new \DateTimeImmutable());
        $commande->setStatut(1);
        $commande->setClient($this->getUser());
        $choix = $request->request->get('type', 'emporter');
        $commande->setAemporter($choix === 'emporter');
        $commande->setNumeroTable(0);

        $total = 0;
        $restaurantTrouve = null;

        foreach ($panier as $id => $quantite) {
            $plat = $platsRepository->find($id);

            if ($plat) {
                if (!$restaurantTrouve && $plat->getRestaurant()) {
                    $restaurantTrouve = $plat->getRestaurant();
                    $commande->setRestaurant($restaurantTrouve);
                }

                $total += $plat->getPrix() * $quantite;
                for ($i = 0; $i < $quantite; $i++) {
                    $commande->addPlat($plat);
                }
            }
        }

        $commande->setTotal($total);

        if (!$commande->getRestaurant()) {
            $this->addFlash('danger', 'Impossible de valider : aucun restaurant identifié.');
            return $this->redirectToRoute('app_home');
        }

        $em->persist($commande);
        $em->flush();

        $session->remove('cart');

        $this->addFlash('success', 'Commande validée avec succès !');

        return $this->redirectToRoute('app_mes_commandes');
    }

    #[Route('/espace-serveur', name: 'app_serveur_commandes')]
    #[IsGranted('ROLE_SERVEUR', message: "Vous n'êtes pas serveur !")]
    public function espaceServeur(CommandeRepository $commandeRepository): Response
    {
        $user = $this->getUser();
        $restaurant = $user->getRestaurant();

        if (!$restaurant) {
            throw $this->createAccessDeniedException("Erreur : Votre compte serveur n'est lié à aucun restaurant !");
        }

        $commandes = $commandeRepository->findBy(
            ['restaurant' => $restaurant],
            ['date' => 'DESC']
        );

        return $this->render('commande/serveur.html.twig', [
            'commandes' => $commandes,
        ]);
    }

    #[Route('/serveur/commande/{id}/etat/{etat}', name: 'app_serveur_changer_etat')]
    #[IsGranted('ROLE_SERVEUR')]
    public function changerEtat(Commande $commande, int $etat, EntityManagerInterface $em): Response
    {
        $commande->setStatut($etat);
        $em->flush();
        $this->addFlash('success', 'Statut de la commande mis à jour !');

        return $this->redirectToRoute('app_serveur_commandes');
    }
}
