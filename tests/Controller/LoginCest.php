<?php

namespace App\Tests\Controller;

use App\Entity\Client;
use App\Tests\Support\ControllerTester;

class LoginCest
{
    public function tryToLogin(ControllerTester $I)
    {
        $hasher = $I->grabService('security.user_password_hasher');
        $clientPourHash = new Client();
        $passwordHashed = $hasher->hashPassword($clientPourHash, 'password');

        $I->haveInRepository(Client::class, [
            'email' => 'login.user@test.com',
            'password' => $passwordHashed,
            'roles' => ['ROLE_CLIENT'],
            'isVerified' => true,
            'nom' => 'LoginNom',
            'prenom' => 'LoginPrenom'
        ]);

        $I->amOnPage('/login');

        $I->fillField('email', 'login.user@test.com');
        $I->fillField('password', 'password');

        $I->click('button[type="submit"]');

        $I->dontSeeCurrentUrlEquals('/login');
    }
}
