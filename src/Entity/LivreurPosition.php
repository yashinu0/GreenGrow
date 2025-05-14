<?php

namespace App\Entity;

use App\Repository\LivreurPositionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: LivreurPositionRepository::class)]
class LivreurPosition
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: "Le livreur doit être spécifié")]
    private ?Livreur $livreur = null;

    #[ORM\Column]
    #[Assert\NotNull(message: "La latitude est requise")]
    #[Assert\Range(
        min: -90,
        max: 90,
        notInRangeMessage: "La latitude doit être comprise entre {{ min }}° et {{ max }}°"
    )]
    private ?float $latitude = null;

    #[ORM\Column]
    #[Assert\NotNull(message: "La longitude est requise")]
    #[Assert\Range(
        min: -180,
        max: 180,
        notInRangeMessage: "La longitude doit être comprise entre {{ min }}° et {{ max }}°"
    )]
    private ?float $longitude = null;

    #[ORM\Column]
    #[Assert\NotNull(message: "La date de mise à jour est requise")]
    #[Assert\Type("\DateTimeImmutable")]
    private ?\DateTimeImmutable $lastUpdate = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: "id_commande", referencedColumnName: "id_commande", nullable: false)]
    #[Assert\NotNull(message: "La commande doit être spécifiée")]
    private ?Commande $commande = null;

    public function __construct()
    {
        $this->lastUpdate = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLivreur(): ?Livreur
    {
        return $this->livreur;
    }

    public function setLivreur(Livreur $livreur): self
    {
        $this->livreur = $livreur;
        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): self
    {
        $this->latitude = $latitude;
        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): self
    {
        $this->longitude = $longitude;
        return $this;
    }

    public function getLastUpdate(): ?\DateTimeImmutable
    {
        return $this->lastUpdate;
    }

    public function setLastUpdate(\DateTimeImmutable $lastUpdate): self
    {
        $this->lastUpdate = $lastUpdate;
        return $this;
    }

    public function getCommande(): ?Commande
    {
        return $this->commande;
    }

    public function setCommande(?Commande $commande): self
    {
        $this->commande = $commande;
        return $this;
    }
}
