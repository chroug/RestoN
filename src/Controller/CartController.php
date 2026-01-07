<?php

namespace App\Controller;

use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Commande;
use App\Entity\LigneCommande;
use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
class CartController extends AbstractController
{
    #[Route('/mon-panier', name: 'cart_index')]
    public function index(CartService $cartService): Response
    {
        return $this->render('cart/index.html.twig', [
            'items' => $cartService->getFullCart(),
            'total' => $cartService->getTotal()
        ]);
    }

    #[Route('/cart/add/{id}', name: 'cart_add')]
    public function add(int $id, CartService $cartService, Request $request): Response
    {
        $cartService->add($id);
        $this->addFlash('success', 'Plat ajouté au panier !');
        return $this->redirect($request->headers->get('referer'));
    }

    #[Route('/cart/remove/{id}', name: 'cart_remove')]
    public function remove(int $id, CartService $cartService, Request $request): Response
    {
        $cartService->remove($id);
        return $this->redirect($request->headers->get('referer'));
    }
    #[Route('/cart/validate', name: 'cart_validate')]
    public function validate(CartService $cartService, EntityManagerInterface $em): Response
    {
        $panier = $cartService->getFullCart();

        if (empty($panier)) {
            $this->addFlash('warning', 'Votre panier est vide !');
            return $this->redirectToRoute('cart_index');
        }

        $commande = new Commande();
        $commande->setDate(new \DateTimeImmutable());
        $commande->setStatut('EN_ATTENTE');
        $commande->setAEmporter(true);


        $user = $this->getUser();

        if (!$user instanceof Client) {
            $this->addFlash('danger', 'Vous devez être un client pour passer commande.');
            return $this->redirectToRoute('app_home');
        }
        $commande->setClient($user);
        $premierArticle = $panier[0];
        $restaurant = $premierArticle['plat']->getRestaurant();
        if (!$restaurant) {
            $this->addFlash('danger', 'Erreur technique : Restaurant introuvable.');
            return $this->redirectToRoute('cart_index');
        }

        $commande->setRestaurant($restaurant);

        foreach ($panier as $item) {
            $ligne = new LigneCommande();
            $ligne->setPlat($item['plat']);
            $ligne->setQuantite($item['quantity']);

            $commande->addLigneCommande($ligne);

            $em->persist($ligne);
        }

        $em->persist($commande);
        $em->flush();

        $cartService->removeCart();

        $this->addFlash('success', 'Commande validée ! Le restaurant va la préparer.');

        return $this->redirectToRoute('app_home');
    }
#[Route('/cart/summary', name: 'cart_summary')]
    public function summary(CartService $cartService): Response
{
    return $this->render('cart/_summary.html.twig', [
        'items' => $cartService->getFullCart(),
        'total' => $cartService->getTotal()
    ]);
}
}
