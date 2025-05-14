<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Positive]
    private ?int $quantite = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private ?string $nom_produit = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private ?string $description_produit = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Positive]
    private ?int $prix_produit = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private ?string $disponibilte_produit = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255)]
    private ?string $image_produit = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255)]
    private ?string $location = null;

    #[ORM\ManyToOne(inversedBy: 'produits')]
    private ?Categories $id_categories = null;

    #[ORM\Column]
    private ?float $rating = null;

    #[ORM\OneToMany(mappedBy: 'produit', targetEntity: Reclamation::class)]
    private Collection $reclamations;

    public function __construct()
    {
        $this->reclamations = new ArrayCollection();
        $this->rating = 0.0;
    }

    public function getReclamations(): Collection
    {
        return $this->reclamations;
    }

    public function addReclamation(Reclamation $reclamation): self
    {
        if (!$this->reclamations->contains($reclamation)) {
            $this->reclamations->add($reclamation);
            $reclamation->setProduit($this);
        }
        return $this;
    }

    public function removeReclamation(Reclamation $reclamation): self
    {
        if ($this->reclamations->removeElement($reclamation)) {
            if ($reclamation->getProduit() === $this) {
                $reclamation->setProduit(null);
            }
        }
        return $this;
    }

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

    public function getNomProduit(): ?string
    {
        return $this->nom_produit;
    }

    public function setNomProduit(string $nom_produit): static
    {
        $this->nom_produit = $nom_produit;
        return $this;
    }

    public function getDescriptionProduit(): ?string
    {
        return $this->description_produit;
    }

    public function setDescriptionProduit(string $description_produit): static
    {
        $this->description_produit = $description_produit;
        return $this;
    }

    public function getPrixProduit(): ?int
    {
        return $this->prix_produit;
    }

    public function setPrixProduit(int $prix_produit): static
    {
        $this->prix_produit = $prix_produit;
        return $this;
    }

    public function getDisponibilteProduit(): ?string
    {
        return $this->disponibilte_produit;
    }

    public function setDisponibilteProduit(string $disponibilte_produit): static
    {
        $this->disponibilte_produit = $disponibilte_produit;
        return $this;
    }

    public function getImageProduit(): ?string
    {
        return $this->image_produit;
    }

    public function setImageProduit(?string $image_produit): static
    {
        $this->image_produit = $image_produit;
        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): static
    {
        $this->location = $location;
        return $this;
    }

    public function getIdCategories(): ?Categories
    {
        return $this->id_categories;
    }

    public function setIdCategories(?Categories $id_categories): static
    {
        $this->id_categories = $id_categories;
        return $this;
    }

    public function __toString(): string
    {
        return $this->nom_produit;
    }

    public function getRating(): ?float
    {
        return $this->rating;
    }

    public function setRating(float $rating): static
    {
        $this->rating = $rating;
        return $this;
    }
}