<?php

namespace App\DataFixtures;

use App\Factory\StockFactory;
use App\Factory\ClientFactory;
use App\Factory\CommandeFactory;
use App\Factory\GerantFactory;
use App\Factory\LigneCommandeFactory;
use App\Factory\PlatsFactory;
use App\Factory\RestaurantFactory;
use App\Factory\ServeurFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $gerant = GerantFactory::createOne([
            'email' => 'admin@resto.com',
            'password' => 'password',
            'nom' => 'Patron',
            'prenom' => 'Chef',
            'roles' => ['ROLE_GERANT']
        ]);

        $resto = RestaurantFactory::createOne([
            'nom' => 'Le Gourmet Symfony',
            'estOuvert' => 'Ouvert',
        ]);

        PlatsFactory::createMany(10, [
            'restaurant' => $resto,
        ]);

        ServeurFactory::createMany(3, ['password' => 'password']);
        ClientFactory::createMany(10, ['password' => 'password']);

        $commandes = CommandeFactory::createMany(20, [
            'restaurant' => $resto,
            'client' => ClientFactory::random(),
            'serveur' => ServeurFactory::random(),
        ]);

        foreach ($commandes as $commande) {
            LigneCommandeFactory::createMany(rand(1, 4), [
                'commande' => $commande,
                'plat' => PlatsFactory::random(),
            ]);
        }

        StockFactory::createMany(10);
    }
}
