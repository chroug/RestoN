<?php

namespace App\Factory;

use App\Entity\Restaurant;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends PersistentObjectFactory<Restaurant>
 */
final class RestaurantFactory extends PersistentObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
    }

    #[\Override]
    public static function class(): string
    {
        return Restaurant::class;
    }


    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    #[\Override]
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Restaurant $restaurant): void {})
        ;
    }

    protected function defaults(): array|callable
    {
        return [
            'nom' => self::faker()->company() . ' Resto',
            'adresse' => self::faker()->streetAddress(),
            'ville' => self::faker()->city(),
            'codePostal' => self::faker()->postcode(),
            'telephone' => self::faker()->phoneNumber(),
            'estOuvert' => self::faker()->randomElement(['Ouvert', 'Fermé']),
        ];
    }
}
