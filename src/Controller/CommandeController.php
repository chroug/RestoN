<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Repository\CommandeRepository;
use App\Repository\PlatsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
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
    public function valider(SessionInterface $session, PlatsRepository $platsRepository, EntityManagerInterface $em): Response
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

        foreach ($panier as $id => $quantite) {
            $plat = $platsRepository->find($id);

            if ($plat) {
                for ($i = 0; $i < $quantite; $i++) {
                    $commande->addPlat($plat);
                }
            }
        }

        $em->persist($commande);
        $em->flush();

        $session->remove('cart');

        $this->addFlash('success', 'Commande validée avec succès !');

        return $this->redirectToRoute('app_mes_commandes');
    }
}
