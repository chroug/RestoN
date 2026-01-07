<?php

namespace App\Entity;

use App\Repository\RestaurantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RestaurantRepository::class)]
class Restaurant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresse = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ville = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $codePostal = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $telephone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $estOuvert = null;

    #[ORM\Column(nullable: true)]
    private ?int $nombrePlaces = null;

    // --- RELATIONS ---

    #[ORM\OneToMany(targetEntity: Horaire::class, mappedBy: 'restaurant', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $horaires;

    #[ORM\OneToMany(targetEntity: Plats::class, mappedBy: 'restaurant')]
    private Collection $plats;

    /**
     * @var Collection<int, Commande>
     */
    #[ORM\OneToMany(targetEntity: Commande::class, mappedBy: 'restaurant')]
    private Collection $commandes;

    /**
     * @var Collection<int, Avis>
     */
    #[ORM\OneToMany(targetEntity: Avis::class, mappedBy: 'restaurant')]
    private Collection $avis;

    /**
     * @var Collection<int, Serveur>
     */


    #[ORM\OneToOne(inversedBy: 'restaurant', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Patron $patron = null;

    public function __construct()
    {
        $this->plats = new ArrayCollection();
        $this->commandes = new ArrayCollection();
        $this->horaires = new ArrayCollection();

        $this->avis = new ArrayCollection();
        $this->serveurs = new ArrayCollection();
    }

    // --- GETTERS & SETTERS ---

    public function getId(): ?int { return $this->id; }

    public function getNom(): ?string { return $this->nom; }
    public function setNom(string $nom): static { $this->nom = $nom; return $this; }

    public function getAdresse(): ?string { return $this->adresse; }
    public function setAdresse(?string $adresse): static { $this->adresse = $adresse; return $this; }

    public function getVille(): ?string { return $this->ville; }
    public function setVille(?string $ville): static { $this->ville = $ville; return $this; }

    public function getCodePostal(): ?string { return $this->codePostal; }
    public function setCodePostal(?string $codePostal): static { $this->codePostal = $codePostal; return $this; }

    public function getTelephone(): ?string { return $this->telephone; }
    public function setTelephone(?string $telephone): static { $this->telephone = $telephone; return $this; }

    public function getEstOuvert(): ?string { return $this->estOuvert; }
    public function setEstOuvert(?string $estOuvert): static { $this->estOuvert = $estOuvert; return $this; }

    public function getNombrePlaces(): ?int { return $this->nombrePlaces; }
    public function setNombrePlaces(?int $nombrePlaces): static { $this->nombrePlaces = $nombrePlaces; return $this; }

    // --- LOGIQUE HORAIRES ---

    /**
     * @return Collection<int, Horaire>
     */
    public function getHoraires(): Collection
    {
        return $this->horaires;
    }

    public function addHoraire(Horaire $horaire): static
    {
        if (!$this->horaires->contains($horaire)) {
            $this->horaires->add($horaire);
            $horaire->setRestaurant($this);
        }
        return $this;
    }

    public function removeHoraire(Horaire $horaire): static
    {
        if ($this->horaires->removeElement($horaire)) {
            if ($horaire->getRestaurant() === $this) {
                $horaire->setRestaurant(null);
            }
        }
        return $this;
    }

    // --- LOGIQUE PLATS ---

    public function getPlats(): Collection { return $this->plats; }

    public function addPlat(Plats $plat): static {
        if (!$this->plats->contains($plat)) {
            $this->plats->add($plat);
            $plat->setRestaurant($this);
        }
        return $this;
    }

    public function removePlat(Plats $plat): static {
        if ($this->plats->removeElement($plat)) {
            // set the owning side to null (unless already changed)
            if ($plat->getRestaurant() === $this) {
                $plat->setRestaurant(null);
            }
        }
        return $this;
    }

    // --- LOGIQUE COMMANDES ---

    public function getCommandes(): Collection { return $this->commandes; }

    public function addCommande(Commande $commande): static {
        if (!$this->commandes->contains($commande)) {
            $this->commandes->add($commande);
            $commande->setRestaurant($this);
        }
        return $this;
    }

    public function removeCommande(Commande $commande): static {
        if ($this->commandes->removeElement($commande)) {
            if ($commande->getRestaurant() === $this) {
                $commande->setRestaurant(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection<int, Avis>
     */
    public function getAvis(): Collection
    {
        return $this->avis;
    }

    public function addAvi(Avis $avi): static
    {
        if (!$this->avis->contains($avi)) {
            $this->avis->add($avi);
            $avi->setRestaurant($this);
        }
        return $this;
    }

    public function removeAvi(Avis $avi): static
    {
        if ($this->avis->removeElement($avi)) {
            if ($avi->getRestaurant() === $this) {
                $avi->setRestaurant(null);
            }
        }
        return $this;
    }

    /**
     * Calcule la note moyenne du restaurant
     */
    public function getNoteMoyenne(): ?float
    {
        $avis = $this->getAvis();

        if ($avis->isEmpty()) {
            return null;
        }

        $total = 0;
        foreach ($avis as $unAvis) {
            $total += $unAvis->getNote();
        }
        return round($total / count($avis), 1);
    }

    /**
     * @return Collection<int, Serveur>
     */
    public function getServeurs(): Collection
    {
        return $this->serveurs;
    }

    public function addServeur(Serveur $serveur): static
    {
        if (!$this->serveurs->contains($serveur)) {
            $this->serveurs->add($serveur);
            $serveur->setRestaurant($this);
        }

        return $this;
    }

    public function removeServeur(Serveur $serveur): static
    {
        if ($this->serveurs->removeElement($serveur)) {
            // set the owning side to null (unless already changed)
            if ($serveur->getRestaurant() === $this) {
                $serveur->setRestaurant(null);
            }
        }

        return $this;
    }

    public function getPatron(): ?Patron
    {
        return $this->patron;
    }

    public function setPatron(?Patron $patron): static
    {
        $this->patron = $patron;

        return $this;
    }

}
