<?php

namespace App\Factory;

use App\Entity\Serveur;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends PersistentObjectFactory<Serveur>
 */
final class ServeurFactory extends PersistentObjectFactory
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
        return Serveur::class;
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
            'roles' => ['ROLE_SERVEUR'],
            'password' => 'password',
            'nom' => self::faker()->lastName(),
            'prenom' => self::faker()->firstName(),
            'telephone' => self::faker()->phoneNumber(),
            'matricule' => 'SRV-' . self::faker()->unique()->randomNumber(5),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    #[\Override]
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Serveur $serveur): void {})
        ;
    }
}
