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
}
