<?php

namespace App\Factory;

use App\Entity\PlatsStock;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends PersistentObjectFactory<PlatsStock>
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
        return PlatsStock::class;
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
    protected function initialize(): StockFactory
    {
        return $this
            // ->afterInstantiate(function(Stock $stock): void {})
        ;
    }

    protected function defaults(): array|callable
    {
        return [
            'plat' => PlatsFactory::new(),
            'quantite' => self::faker()->numberBetween(0, 50),
        ];
    }
}
