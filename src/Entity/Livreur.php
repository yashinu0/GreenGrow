<?php

namespace App\Entity;

use App\Repository\LivreurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: LivreurRepository::class)]
class Livreur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_livreur = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom_livreur = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Le numéro de téléphone est obligatoire")]
    #[Assert\Regex(
        pattern: "/^[0-9]{8}$/",
        message: "Le numéro doit contenir exactement 8 chiffres"
    )]
    private ?int $numero_livreur = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "L'adresse email est obligatoire")]
    #[Assert\Email(
        message: "L'adresse '{{ value }}' n'est pas une adresse email valide."
    )]
    private ?string $addresse_livreur = null;

    #[ORM\Column(length: 255)]
    private ?string $photo_livreur = null;

    /**
     * @var Collection<int, Commande>
     */
    #[ORM\OneToMany(targetEntity: Commande::class, mappedBy: 'livreur_commande')]
    private Collection $Commande_livreur;

    public function __construct()
    {
        $this->Commande_livreur = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomLivreur(): ?string
    {
        return $this->nom_livreur;
    }

    public function setNomLivreur(string $nom_livreur): static
    {
        $this->nom_livreur = $nom_livreur;

        return $this;
    }

    public function getPrenomLivreur(): ?string
    {
        return $this->prenom_livreur;
    }

    public function setPrenomLivreur(string $prenom_livreur): static
    {
        $this->prenom_livreur = $prenom_livreur;

        return $this;
    }

    public function getNumeroLivreur(): ?int
    {
        return $this->numero_livreur;
    }

    public function setNumeroLivreur(int $numero_livreur): static
    {
        $this->numero_livreur = $numero_livreur;

        return $this;
    }

    public function getAddresseLivreur(): ?string
    {
        return $this->addresse_livreur;
    }

    public function setAddresseLivreur(string $addresse_livreur): static
    {
        $this->addresse_livreur = $addresse_livreur;

        return $this;
    }

    public function getPhotoLivreur(): ?string
    {
        return $this->photo_livreur;
    }

    public function setPhotoLivreur(string $photo_livreur): static
    {
        $this->photo_livreur = $photo_livreur;

        return $this;
    }

    /**
     * @return Collection<int, Commande>
     */
    public function getCommandeLivreur(): Collection
    {
        return $this->Commande_livreur;
    }

    public function addCommandeLivreur(Commande $commandeLivreur): static
    {
        if (!$this->Commande_livreur->contains($commandeLivreur)) {
            $this->Commande_livreur->add($commandeLivreur);
            $commandeLivreur->setLivreurCommande($this);
        }

        return $this;
    }

    public function removeCommandeLivreur(Commande $commandeLivreur): static
    {
        if ($this->Commande_livreur->removeElement($commandeLivreur)) {
            // set the owning side to null (unless already changed)
            if ($commandeLivreur->getLivreurCommande() === $this) {
                $commandeLivreur->setLivreurCommande(null);
            }
        }

        return $this;
    }
}
