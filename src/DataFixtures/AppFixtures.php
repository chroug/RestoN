<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\Gerant;
use App\Entity\Patron;
use App\Entity\Serveur;
use App\Factory\AvisFactory;
use App\Factory\CategoryFactory;
use App\Factory\ClientFactory;
use App\Factory\CommandeFactory;
use App\Factory\GerantFactory;
use App\Factory\LigneCommandeFactory;
use App\Factory\PatronFactory;
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
        $passwordPatron = $this->hasher->hashPassword(new Patron(), 'password');
        $passwordServeur = $this->hasher->hashPassword(new Serveur(), 'password');
        $passwordClient = $this->hasher->hashPassword(new Client(), 'password');

        GerantFactory::createOne([
            'email' => 'admin@resto.com',
            'password' => $passwordGerant,
            'nom' => 'Admin',
            'prenom' => 'System',
            'roles' => ['ROLE_GERANT'],
            'isVerified' => true
        ]);

        ClientFactory::createMany(10, [
            'password' => $passwordClient,
            'isVerified' => true
        ]);

        CategoryFactory::createOne(['name' => 'Entrée']);
        CategoryFactory::createOne(['name' => 'Plat']);
        CategoryFactory::createOne(['name' => 'Dessert']);

        $mainResto = RestaurantFactory::createOne([
            'nom' => 'Le Gourmet Symfony',
            'estOuvert' => 'Ouvert',
            'patron' => PatronFactory::new([
                'email' => 'patron@gourmet.com',
                'password' => $passwordPatron,
                'nom' => 'Boss',
                'roles' => ['ROLE_PATRON'],
                'isVerified' => true
            ]),
        ]);

        $autresRestos = RestaurantFactory::createMany(5, [
            'patron' => PatronFactory::new([
                'password' => $passwordPatron,
                'roles' => ['ROLE_PATRON'],
                'isVerified' => true
            ])
        ]);

        $tousLesRestos = array_merge([$mainResto], $autresRestos);

        foreach ($tousLesRestos as $restaurant) {


            $platsDuResto = [];

            for ($i = 0; $i < 15; $i++) {
                $rand = rand(1, 100);

                if ($rand <= 80) {
                    $category = CategoryFactory::find(['name' => 'Plat']);
                } elseif ($rand <= 90) {
                    $category = CategoryFactory::find(['name' => 'Entrée']);
                } else {
                    $category = CategoryFactory::find(['name' => 'Dessert']);
                }

                $plat = PlatsFactory::createOne([
                    'restaurant' => $restaurant,
                    'platsStocks' => StockFactory::new()->many(1),
                    'category' => $category,
                ]);

                $platsDuResto[] = $plat;
            }

            
            $serveursDuResto = ServeurFactory::createMany(2, [
                'password' => $passwordServeur,
                'isVerified' => true,
                'restaurant' => $restaurant
            ]);


            $commandes = CommandeFactory::createMany(5, [
                'restaurant' => $restaurant,
                'client' => ClientFactory::random(),
                'serveur' => $serveursDuResto[array_rand($serveursDuResto)],
            ]);

            foreach ($commandes as $commande) {
                $randomKey = array_rand($platsDuResto);
                $platAuHasard = $platsDuResto[$randomKey];

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
