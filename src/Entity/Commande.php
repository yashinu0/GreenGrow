<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_commande = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le statut de la commande ne peut pas être vide")]
    #[Assert\Choice(
        choices: ["en_preparation", "en_route", "livree", "annulee"],
        message: "Le statut {{ value }} n'est pas valide. Les statuts possibles sont : en_preparation, en_route, livree, annulee"
    )]
    private ?string $statue_commande = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotNull(message: "La date de commande est requise")]
    #[Assert\Type("\DateTimeInterface")]
    private ?\DateTimeInterface $date_commande = null;

    #[ORM\Column]
    #[Assert\NotNull(message: "Le prix total est requis")]
    #[Assert\Positive(message: "Le prix total doit être positif")]
    #[Assert\Range(
        min: 0.01,
        max: 10000,
        notInRangeMessage: "Le prix total doit être entre {{ min }}€ et {{ max }}€"
    )]
    private ?float $prixtotal_commande = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le mode de paiement est requis")]
    #[Assert\Choice(
        choices: ["carte", "especes", "cheque"],
        message: "Le mode de paiement {{ value }} n'est pas accepté. Les modes acceptés sont : carte, especes, cheque"
    )]
    private ?string $modepaiement_commande = null;

    #[ORM\ManyToOne(inversedBy: 'Commande_livreur')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: "Un livreur doit être assigné à la commande")]
    private ?Livreur $livreur_commande = null;

    public function getIdCommande(): ?int
    {
        return $this->id_commande;
    }

    public function getStatueCommande(): ?string
    {
        return $this->statue_commande;
    }

    public function setStatueCommande(string $statue_commande): static
    {
        $this->statue_commande = $statue_commande;
        return $this;
    }

    public function getDateCommande(): ?\DateTimeInterface
    {
        return $this->date_commande;
    }

    public function setDateCommande(\DateTimeInterface $date_commande): static
    {
        $this->date_commande = $date_commande;
        return $this;
    }

    public function getPrixtotalCommande(): ?float
    {
        return $this->prixtotal_commande;
    }

    public function setPrixtotalCommande(float $prixtotal_commande): static
    {
        $this->prixtotal_commande = $prixtotal_commande;
        return $this;
    }

    public function getModepaiementCommande(): ?string
    {
        return $this->modepaiement_commande;
    }

    public function setModepaiementCommande(string $modepaiement_commande): static
    {
        $this->modepaiement_commande = $modepaiement_commande;
        return $this;
    }

    public function getLivreurCommande(): ?Livreur
    {
        return $this->livreur_commande;
    }

    public function setLivreurCommande(?Livreur $livreur_commande): static
    {
        $this->livreur_commande = $livreur_commande;
        return $this;
    }
}
