<?php

namespace App\Entity;

use App\Repository\PatronRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PatronRepository::class)]
class Patron extends User
{
}
