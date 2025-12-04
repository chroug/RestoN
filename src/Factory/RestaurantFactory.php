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
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    #[\Override]
    protected function defaults(): array|callable
    {
        return [
            'adresse' => self::faker()->text(255),
            'codePostal' => self::faker()->text(255),
            'estOuvert' => self::faker()->text(255),
            'nom' => self::faker()->text(255),
            'telephone' => self::faker()->text(255),
            'ville' => self::faker()->text(255),
        ];
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
}
