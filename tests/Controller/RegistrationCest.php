<?php

namespace App\Tests\Controller;

use App\Entity\Client;
use App\Tests\Support\ControllerTester;

class RegistrationCest
{
    public function tryToRegister(ControllerTester $I)
    {
        $I->amOnPage('/register');
        $I->seeResponseCodeIs(200);

        $I->fillField('registration_form[email]', 'nouveau.client@test.com');
        $I->fillField('registration_form[plainPassword]', 'password123');
        $I->fillField('registration_form[nom]', 'Dupont');
        $I->fillField('registration_form[prenom]', 'Jean');
        $I->fillField('registration_form[telephone]', '0601020304');

        $I->checkOption('registration_form[agreeTerms]');

        $I->click('button[type="submit"]');

        $I->seeInRepository(Client::class, [
            'email' => 'nouveau.client@test.com',
            'nom' => 'Dupont'
        ]);
    }
}
