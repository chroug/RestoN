<?php

namespace App\Factory;

use App\Entity\Plats;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;
use FakerRestaurant\Provider\fr_FR\Restaurant as RestaurantProvider;

/**
 * @extends PersistentObjectFactory<Plats>
 */
final class PlatsFactory extends PersistentObjectFactory
{
    public function __construct()
    {
    }

    #[\Override]
    public static function class(): string
    {
        return Plats::class;
    }

    #[\Override]
    /** @var Restaurant $faker */
    protected function defaults(): array|callable
    {
        $faker = self::faker();
        $faker->addProvider(new RestaurantProvider($faker));

        return [

            'nom' => $faker->foodName(),

            'prix' => $faker->randomFloat(2, 9, 25),
            'restaurant' => RestaurantFactory::new(),
        ];
    }

    #[\Override]
    protected function initialize(): static
    {
        return $this;
    }
}
