<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Serveur extends User
{
    #[ORM\Column(length: 50, nullable: true)]
    private ?string $matricule = null;

    public function getMatricule(): ?string
    {
        return $this->matricule;
    }

    public function setMatricule(?string $matricule): static
    {
        $this->matricule = $matricule;

        return $this;
    }
}
