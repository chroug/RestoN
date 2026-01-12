<?php

namespace App\Tests\Controller;

use App\Entity\Category;
use App\Entity\Client;
use App\Entity\Commande;
use App\Entity\Patron;
use App\Entity\Plats;
use App\Entity\PlatsStock;
use App\Entity\Restaurant;
use App\Tests\Support\ControllerTester;

class CartCest
{
    public function tryOrderProcess(ControllerTester $I)
    {
        $patronId = $I->haveInRepository(Patron::class, [
            'email' => 'patron.cart@test.com',
            'password' => 'password',
            'roles' => ['ROLE_PATRON'],
            'isVerified' => true,
            'nom' => 'PatronNom',
            'prenom' => 'PatronPrenom'
        ]);
        $patron = $I->grabEntityFromRepository(Patron::class, ['id' => $patronId]);

        $restaurantId = $I->haveInRepository(Restaurant::class, [
            'nom' => 'Resto Cart',
            'patron' => $patron,
            'estOuvert' => 'Ouvert'
        ]);
        $restaurant = $I->grabEntityFromRepository(Restaurant::class, ['id' => $restaurantId]);

        $categoryId = $I->haveInRepository(Category::class, ['name' => 'Plat']);
        $category = $I->grabEntityFromRepository(Category::class, ['id' => $categoryId]);

        $platId = $I->haveInRepository(Plats::class, [
            'nom' => 'Burger Controller',
            'prix' => 15.00,
            'restaurant' => $restaurant,
            'category' => $category,
            'description' => 'Miam'
        ]);
        $plat = $I->grabEntityFromRepository(Plats::class, ['id' => $platId]);

        $I->haveInRepository(PlatsStock::class, [
            'quantite' => 50,
            'plat' => $plat
        ]);

        $clientId = $I->haveInRepository(Client::class, [
            'email' => 'client.cart@test.com',
            'password' => 'password',
            'roles' => ['ROLE_CLIENT'],
            'isVerified' => true,
            'nom' => 'ClientNom',
            'prenom' => 'ClientPrenom'
        ]);
        $client = $I->grabEntityFromRepository(Client::class, ['id' => $clientId]);

        $I->amLoggedInAs($client);

        $I->amOnPage('/cart/add/' . $plat->getId());

        $I->amOnPage('/mon-panier');
        $I->see('Burger Controller');
        $I->see('15');

        $I->amOnPage('/cart/validate');
        $I->seeCurrentUrlEquals('/');
        $I->see('Commande validée');

        $I->seeInRepository(Commande::class, [
            'client' => $client,
            'restaurant' => $restaurant
        ]);
    }
}
