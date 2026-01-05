<?php

namespace App\Entity;

use App\Repository\PlatsStockRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlatsStockRepository::class)]
class PlatsStock
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // 👇 Voici le champ quantité que tu voulais
    #[ORM\Column]
    private ?int $quantite = null;

    // 👇 Le lien avec le Plat (Indispensable pour savoir de quel plat on parle)
    #[ORM\ManyToOne(inversedBy: 'platsStocks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Plats $plat = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getPlat(): ?Plats
    {
        return $this->plat;
    }

    public function setPlat(?Plats $plat): static
    {
        $this->plat = $plat;

        return $this;
    }
}
