<?php

namespace App\Entity;

use App\Repository\CategoriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CategoriesRepository::class)]
class Categories
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private ?string $nom_categories = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private ?string $description_categories = null;

    /**
     * @var Collection<int, Produit>
     */
    #[ORM\OneToMany(targetEntity: Produit::class, mappedBy: 'id_categories')]
    private Collection $produits;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomCategories(): ?string
    {
        return $this->nom_categories;
    }

    public function setNomCategories(string $nom_categories): static
    {
        $this->nom_categories = $nom_categories;

        return $this;
    }

    public function getDescriptionCategories(): ?string
    {
        return $this->description_categories;
    }

    public function setDescriptionCategories(string $description_categories): static
    {
        $this->description_categories = $description_categories;

        return $this;
    }

    /**
     * @return Collection<int, Produit>
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produit $produit): static
    {
        if (!$this->produits->contains($produit)) {
            $this->produits->add($produit);
            $produit->setIdCategories($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): static
    {
        if ($this->produits->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getIdCategories() === $this) {
                $produit->setIdCategories(null);
            }
        }

        return $this;
    }
}