<?php

namespace App\Factory;

use App\Entity\Stock;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends PersistentObjectFactory<Stock>
 */
final class StockFactory extends PersistentObjectFactory
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
        return Stock::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */


    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    #[\Override]
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Stock $stock): void {})
        ;
    }

    protected function defaults(): array|callable
    {
        return [
            'nom' => self::faker()->word(),
            'quantite' => self::faker()->randomFloat(2, 0, 100),
            'unite' => self::faker()->randomElement(['kg', 'L', 'pcs']),
        ];
    }
}
