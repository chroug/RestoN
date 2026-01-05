<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\Gerant;
use App\Entity\Serveur;
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
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Préparation des mots de passe
        $passwordGerant = $this->hasher->hashPassword(new Gerant(), 'password');
        $passwordServeur = $this->hasher->hashPassword(new Serveur(), 'password');
        $passwordClient = $this->hasher->hashPassword(new Client(), 'password');

        StockFactory::createMany(20);

        // 1. Gérant
        $gerant = GerantFactory::createOne([
            'email' => 'admin@resto.com',
            'password' => $passwordGerant,
            'nom' => 'Patron',
            'prenom' => 'Chef',
            'roles' => ['ROLE_GERANT'],
            'isVerified' => true
        ]);

        $resto = RestaurantFactory::createOne([
            'nom' => 'Le Gourmet Symfony',
            'estOuvert' => 'Ouvert',
        ]);

        PlatsFactory::createMany(10, [
            'restaurant' => $resto,
        ]);

        // 2. Serveurs
        ServeurFactory::createMany(3, [
            'password' => $passwordServeur,
            'isVerified' => true
        ]);

        // 3. Clients
        ClientFactory::createMany(10, [
            'password' => $passwordClient,
            'isVerified' => true
        ]);

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
    }
}
