<?php

namespace App\Form;
namespace App\Factory;

use App\Entity\Avis;
use App\Factory\ClientFactory;
use App\Factory\RestaurantFactory;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends PersistentObjectFactory<Avis>
 */
final class AvisFactory extends PersistentObjectFactory
{
    public function __construct()
    {
    }

    #[\Override]
    public static function class(): string
    {
        return Avis::class;
    }

    #[\Override]
    protected function defaults(): array|callable
    {
        return [

            'note' => self::faker()->numberBetween(1, 5),

            'commentaire' => self::faker()->optional(0.8)->realText(100),

            'date' => \DateTimeImmutable::createFromMutable(self::faker()->dateTimeBetween('-6 months', 'now')),

            'client' => ClientFactory::new(),
            'restaurant' => RestaurantFactory::new(),
        ];
    }

    #[\Override]
    protected function initialize(): static
    {
        return $this;
    }
}
