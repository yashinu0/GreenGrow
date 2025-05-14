<?php

namespace App\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
class Utilisateur implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_user = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: "Le nom est obligatoire.")]
    #[Assert\Length(min: 3, max: 255, minMessage: "Le nom doit comporter au moins 3 caractères.")]
    #[Assert\Regex(pattern: "/^[a-zA-Z -]+$/", message: "Le nom ne doit contenir que des lettres.")]
    private ?string $nom_user = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: "Le prénom est obligatoire.")]
    #[Assert\Length(min: 3, max: 255, minMessage: "Le prénom doit comporter au moins 3 caractères.")]
    #[Assert\Regex(pattern: "/^[a-zA-Z -]+$/", message: "Le prénom ne doit contenir que des lettres.")]
    private ?string $prenom_user = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: "L'email est obligatoire.")]
    #[Assert\Email(message: "L'email '{{ value }}' n'est pas valide.")]
    private ?string $email_user = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: "Le mot de passe est obligatoire.")]
    #[Assert\Length(min: 8, minMessage: "Le mot de passe doit comporter au moins 8 caractères.")]
    private ?string $mot_de_passe_user = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: "L'adresse est obligatoire.")]
    private ?string $Adresse_user = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: "La ville est obligatoire.")]
    private ?string $Ville_user = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: "Le code postal est obligatoire.")]
    #[Assert\Regex(pattern: "/^\d{4,6}$/", message: "Le code postal doit contenir entre 4 et 6 chiffres.")]
    private ?string $Code_postal_user = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: "Le téléphone est obligatoire.")]
    #[Assert\Regex(pattern: "/^\d{8}$/", message: "Le numéro de téléphone doit comporter exactement 8 chiffres.")]
    private ?string $Telephone_user = null;

    #[ORM\Column(type: 'string')]
    private string $role_user = 'ROLE_CLIENT';

    #[ORM\Column(type: 'boolean')]
    private bool $isActive = true;

    #[ORM\OneToMany(mappedBy: 'utilisateur', targetEntity: Reclamation::class)]
    private Collection $reclamations;
    
    public function __construct()
    {
        $this->role_user = 'ROLE_CLIENT';
        $this->reclamations = new ArrayCollection();
    }

    public function getReclamations(): Collection
    {
        return $this->reclamations;
    }

    public function addReclamation(Reclamation $reclamation): self
    {
        if (!$this->reclamations->contains($reclamation)) {
            $this->reclamations->add($reclamation);
            $reclamation->setUtilisateur($this);
        }
        return $this;
    }

    public function removeReclamation(Reclamation $reclamation): self
    {
        if ($this->reclamations->removeElement($reclamation)) {
            if ($reclamation->getUtilisateur() === $this) {
                $reclamation->setUtilisateur(null);
            }
        }
        return $this;
    }

    public function getIsActive(): bool
    {
        return $this->isActive;
    }
    
    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;
        return $this;
    }

    public function getRoles(): array
    {
        return [$this->role_user ?? 'ROLE_CLIENT'];
    }

    public function getUserIdentifier(): string
    {
        return $this->email_user;
    }

    public function getIdUser(): ?int
    {
        return $this->id_user;
    }

    public function getNomUser(): ?string
    {
        return $this->nom_user;
    }

    public function setNomUser(string $nom_user): static
    {
        $this->nom_user = $nom_user;
        return $this;
    }

    public function getPrenomUser(): ?string
    {
        return $this->prenom_user;
    }

    public function setPrenomUser(string $prenom_user): static
    {
        $this->prenom_user = $prenom_user;
        return $this;
    }

    public function getEmailUser(): ?string
    {
        return $this->email_user;
    }

    public function setEmailUser(string $email_user): static
    {
        $this->email_user = $email_user;
        return $this;
    }

    public function getMotDePasseUser(): ?string
    {
        return $this->mot_de_passe_user;
    }

    public function setMotDePasseUser(string $mot_de_passe_user): self
    {
        $this->mot_de_passe_user = $mot_de_passe_user;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->mot_de_passe_user;
    }

    public function setPassword(string $mot_de_passe_user): static
    {
        $this->mot_de_passe_user = $mot_de_passe_user;
        return $this;
    }

    public function getRoleUser(): ?string
    {
        return $this->role_user;
    }

    public function setRoleUser(string $role_user): self
    {
        $this->role_user = $role_user;
        return $this;
    }

    public function getAdresseUser(): ?string
    {
        return $this->Adresse_user;
    }

    public function setAdresseUser(string $Adresse_user): static
    {
        $this->Adresse_user = $Adresse_user;
        return $this;
    }

    public function getCodePostalUser(): ?string
    {
        return $this->Code_postal_user;
    }

    public function setCodePostalUser(string $Code_postal_user): static
    {
        $this->Code_postal_user = $Code_postal_user;
        return $this;
    }

    public function getVilleUser(): ?string
    {
        return $this->Ville_user;
    }

    public function setVilleUser(string $Ville_user): static
    {
        $this->Ville_user = $Ville_user;
        return $this;
    }

    public function getTelephoneUser(): ?string
    {
        return $this->Telephone_user;
    }

    public function setTelephoneUser(string $Telephone_user): static
    {
        $this->Telephone_user = $Telephone_user;
        return $this;
    }

    public function eraseCredentials(): void
    {
    }
}