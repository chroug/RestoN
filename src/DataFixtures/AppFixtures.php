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
            'email' => 'admin@test.com',
            'password' => $passwordGerant,
            'nom' => 'Admin',
            'prenom' => 'Fixe',
            'roles' => ['ROLE_GERANT'],
            'isVerified' => true
        ]);

        $clientFixe = ClientFactory::createOne([
            'email' => 'client@test.com',
            'password' => $passwordClient,
            'nom' => 'Client',
            'prenom' => 'Fixe',
            'roles' => ['ROLE_CLIENT'],
            'isVerified' => true
        ]);

        $patronFixe = PatronFactory::createOne([
            'email' => 'patron@test.com',
            'password' => $passwordPatron,
            'nom' => 'Patron',
            'prenom' => 'Fixe',
            'roles' => ['ROLE_PATRON'],
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
            'patron' => $patronFixe,
        ]);

        ServeurFactory::createOne([
            'email' => 'serveur@test.com',
            'password' => $passwordServeur,
            'nom' => 'Serveur',
            'prenom' => 'Fixe',
            'roles' => ['ROLE_SERVEUR'],
            'isVerified' => true,
            'restaurant' => $mainResto
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
                    'prix' => rand(1000, 4000) / 100
                ]);

                $platsDuResto[] = $plat;
            }

            $serveursDuResto = ServeurFactory::createMany(2, [
                'password' => $passwordServeur,
                'isVerified' => true,
                'restaurant' => $restaurant
            ]);

            $commandes = CommandeFactory::createMany(15, [
                'restaurant' => $restaurant,
                'client' => rand(0, 1) ? $clientFixe : ClientFactory::random(),
                'serveur' => $serveursDuResto[array_rand($serveursDuResto)],
                'date' => \DateTimeImmutable::createFromMutable(self::faker()->dateTimeBetween('-1 month', 'now')),
                'total' => 0
            ]);

            foreach ($commandes as $commande) {
                $totalCommande = 0;
                $nbLignes = rand(1, 5);

                for ($k = 0; $k < $nbLignes; $k++) {
                    $platAuHasard = $platsDuResto[array_rand($platsDuResto)];

                    LigneCommandeFactory::createOne([
                        'commande' => $commande,
                        'plat' => $platAuHasard,
                        'quantite' => 1,
                    ]);

                    $totalCommande += $platAuHasard->getPrix();
                }

                $commande->setTotal($totalCommande);
            }

            AvisFactory::createMany(rand(3, 8), [
                'restaurant' => $restaurant,
                'client' => ClientFactory::random(),
            ]);
        }

        $manager->flush();
    }

    protected static function faker(): \Faker\Generator
    {
        return \Zenstruck\Foundry\faker();
    }
}
