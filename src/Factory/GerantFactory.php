<?php

namespace App\Factory;

use App\Entity\Gerant;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends PersistentObjectFactory<Gerant>
 */
final class GerantFactory extends PersistentObjectFactory
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
        return Gerant::class;
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
            'email' => self::faker()->unique()->email(),
            'roles' => ['ROLE_GERANT'],
            'password' => 'password',
            'nom' => self::faker()->lastName(),
            'prenom' => self::faker()->firstName(),
            'telephone' => self::faker()->phoneNumber(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    #[\Override]
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Gerant $gerant): void {})
        ;
    }
}
