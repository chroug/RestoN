<?php

namespace App\Service;

use App\Repository\PlatsRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class CartService
{
    private $requestStack;
    private $platsRepository;

    public function __construct(RequestStack $requestStack, PlatsRepository $platsRepository)
    {
        $this->requestStack = $requestStack;
        $this->platsRepository = $platsRepository;
    }

    public function add(int $id): void
    {
        $panier = $this->requestStack->getSession()->get('cart', []);

        $platAjoute = $this->platsRepository->find($id);


        if (!empty($panier)) {
            $premierId = array_key_first($panier);
            $premierPlat = $this->platsRepository->find($premierId);

            if ($premierPlat) {
                if ($premierPlat->getRestaurant() !== $platAjoute->getRestaurant()) {
                    $panier = [];
                }
            } else {
                $panier = [];
            }
        }

        if (!empty($panier[$id])) {
            $panier[$id]++;
        } else {
            $panier[$id] = 1;
        }

        $this->requestStack->getSession()->set('cart', $panier);
    }

    public function remove(int $id): void
    {
        $session = $this->requestStack->getSession();
        $cart = $session->get('cart', []);

        if (!empty($cart[$id])) {
            unset($cart[$id]);
        }
        $session->set('cart', $cart);
    }

    public function decrease(int $id): void
    {
        $panier = $this->requestStack->getSession()->get('cart', []);

        if (!empty($panier[$id])) {
            if ($panier[$id] > 1) {
                $panier[$id]--;
            } else {
                unset($panier[$id]);
            }
        }
        $this->requestStack->getSession()->set('cart', $panier);
    }

    public function getFullCart(): array
    {
        $session = $this->requestStack->getSession();
        $cart = $session->get('cart', []);
        $cartWithData = [];

        foreach ($cart as $id => $quantity) {
            $plat = $this->platsRepository->find($id);
            if ($plat) {
                $cartWithData[] = [
                    'plat' => $plat,
                    'quantity' => $quantity
                ];
            }
        }

        return $cartWithData;
    }

    public function getTotal(): float
    {
        $total = 0;
        $cartWithData = $this->getFullCart();
        foreach ($cartWithData as $item) {
            $total += $item['plat']->getPrix() * $item['quantity'];
        }
        return $total;
    }

    public function removeCart(): void
    {
        $session = $this->requestStack->getSession();
        $session->remove('cart');
    }
}
