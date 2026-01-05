<?php

namespace App\Entity;

use App\Repository\HoraireRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HoraireRepository::class)]
class Horaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    private ?string $jour = null;

    #[ORM\Column(type: 'time', nullable: true)]
    private ?\DateTimeInterface $ouvertureMidi = null;

    #[ORM\Column(type: 'time', nullable: true)]
    private ?\DateTimeInterface $fermetureMidi = null;

    #[ORM\Column(type: 'time', nullable: true)]
    private ?\DateTimeInterface $ouvertureSoir = null;

    #[ORM\Column(type: 'time', nullable: true)]
    private ?\DateTimeInterface $fermetureSoir = null;

    #[ORM\Column]
    private bool $ferme = false;

    #[ORM\ManyToOne(inversedBy: 'horaires')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Restaurant $restaurant = null;

    public function getId(): ?int { return $this->id; }

    public function getJour(): ?string { return $this->jour; }
    public function setJour(string $jour): static { $this->jour = $jour; return $this; }

    public function getOuvertureMidi(): ?\DateTimeInterface { return $this->ouvertureMidi; }
    public function setOuvertureMidi(?\DateTimeInterface $ouvertureMidi): static { $this->ouvertureMidi = $ouvertureMidi; return $this; }

    public function getFermetureMidi(): ?\DateTimeInterface { return $this->fermetureMidi; }
    public function setFermetureMidi(?\DateTimeInterface $fermetureMidi): static { $this->fermetureMidi = $fermetureMidi; return $this; }

    public function getOuvertureSoir(): ?\DateTimeInterface { return $this->ouvertureSoir; }
    public function setOuvertureSoir(?\DateTimeInterface $ouvertureSoir): static { $this->ouvertureSoir = $ouvertureSoir; return $this; }

    public function getFermetureSoir(): ?\DateTimeInterface { return $this->fermetureSoir; }
    public function setFermetureSoir(?\DateTimeInterface $fermetureSoir): static { $this->fermetureSoir = $fermetureSoir; return $this; }

    public function isFerme(): bool { return $this->ferme; }
    public function setFerme(bool $ferme): static { $this->ferme = $ferme; return $this; }

    public function getRestaurant(): ?Restaurant { return $this->restaurant; }
    public function setRestaurant(?Restaurant $restaurant): static { $this->restaurant = $restaurant; return $this; }
}
