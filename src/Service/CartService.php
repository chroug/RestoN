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
        $session = $this->requestStack->getSession();
        $cart = $session->get('cart', []);

        if (!empty($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }

        $session->set('cart', $cart);
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
}
