<?php

namespace App\Factory;

use App\Entity\Patron;
use App\Repository\PatronRepository;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @extends PersistentObjectFactory<Patron>
 */
final class PatronFactory extends PersistentObjectFactory
{
    private $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public static function class(): string
    {
        return Patron::class;
    }

    protected function defaults(): array|callable
    {
        return [
            'email' => self::faker()->unique()->email(),
            'roles' => ['ROLE_PATRON'],
            'password' => 'password',
            'nom' => self::faker()->lastName(),
            'prenom' => self::faker()->firstName(),
            'isVerified' => true,
        ];
    }

    protected function initialize(): static
    {
        return $this->afterInstantiate(function(Patron $patron): void {
            $patron->setPassword($this->hasher->hashPassword($patron, $patron->getPassword()));
        });
    }
}
