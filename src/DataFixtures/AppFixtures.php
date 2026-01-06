<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\Gerant;
use App\Entity\Serveur;
use App\Factory\AvisFactory;
use App\Factory\ClientFactory;
use App\Factory\CommandeFactory;
use App\Factory\GerantFactory;
use App\Factory\LigneCommandeFactory;
use App\Factory\PlatsFactory;
use App\Factory\RestaurantFactory;
use App\Factory\ServeurFactory;
use App\Factory\StockFactory;
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
        $passwordGerant = $this->hasher->hashPassword(new Gerant(), 'password');
        $passwordServeur = $this->hasher->hashPassword(new Serveur(), 'password');
        $passwordClient = $this->hasher->hashPassword(new Client(), 'password');

        GerantFactory::createOne([
            'email' => 'admin@resto.com',
            'password' => $passwordGerant,
            'nom' => 'Patron',
            'prenom' => 'Chef',
            'roles' => ['ROLE_GERANT'],
            'isVerified' => true
        ]);

        ServeurFactory::createMany(3, [
            'password' => $passwordServeur,
            'isVerified' => true
        ]);

        ClientFactory::createMany(10, [
            'password' => $passwordClient,
            'isVerified' => true
        ]);

        $mainResto = RestaurantFactory::createOne([
            'nom' => 'Le Gourmet Symfony',
            'estOuvert' => 'Ouvert',
        ]);

        $otherRestos = RestaurantFactory::createMany(11);
        $tousLesRestos = array_merge([$mainResto], $otherRestos);

        foreach ($tousLesRestos as $restaurant) {
            $platsDuResto = PlatsFactory::createMany(15, [
                'restaurant' => $restaurant,
                'platsStocks' => StockFactory::new()->many(1),
            ]);

            $commandes = CommandeFactory::createMany(5, [
                'restaurant' => $restaurant,
                'client' => ClientFactory::random(),
                'serveur' => ServeurFactory::random(),
            ]);

            foreach ($commandes as $commande) {
                $platAuHasard = $platsDuResto[array_rand($platsDuResto)];

                LigneCommandeFactory::createMany(rand(1, 4), [
                    'commande' => $commande,
                    'plat' => $platAuHasard,
                ]);
            }

            AvisFactory::createMany(rand(3, 8), [
                'restaurant' => $restaurant,
                'client' => ClientFactory::random(),
            ]);
        }
    }
}
