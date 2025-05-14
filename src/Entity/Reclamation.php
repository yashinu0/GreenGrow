<?php

namespace App\Entity;

use App\Repository\ReclamationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReclamationRepository::class)]
class Reclamation
{
    public const STATUS_EN_ATTENTE = 'En attente';
    public const STATUS_EN_COURS = 'En cours';
    public const STATUS_RESOLU = 'Résolu';
    public const STATUS_FERME = 'Fermé';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Veuillez choisir un type de réclamation')]
    private ?string $description_rec = null;

    #[ORM\Column(length: 255)]
    private ?string $statut_rec = null;

    #[ORM\Column(type: "datetime")]
    private ?\DateTimeInterface $date_rec = null;

    #[ORM\Column(type: "text", nullable: true)]
    #[Assert\NotBlank(message: 'Veuillez décrire votre problème')]
    #[Assert\Length(
        min: 10,
        max: 1000,
        minMessage: 'Votre message doit faire au moins {{ limit }} caractères',
        maxMessage: 'Votre message ne peut pas dépasser {{ limit }} caractères'
    )]
    private ?string $message_reclamation = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $historiqueConversations = null;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: "reclamations")]
    #[ORM\JoinColumn(name: "utilisateur_id", referencedColumnName: "id_user", nullable: false)]
    private ?Utilisateur $utilisateur = null;

    #[ORM\ManyToOne(targetEntity: Produit::class, inversedBy: "reclamations")]
    #[ORM\JoinColumn(name: "produit_id", referencedColumnName: "id")]
    private ?Produit $produit = null;

    #[ORM\OneToMany(mappedBy: "reclamation", targetEntity: ReclamationMessage::class, orphanRemoval: true)]
    private Collection $messages;

    #[ORM\OneToMany(mappedBy: 'reclamation', targetEntity: ChatMessage::class, orphanRemoval: true)]
    private Collection $chatMessages;

    public function __construct()
    {
        $this->date_rec = new \DateTime();
        $this->statut_rec = self::STATUS_EN_ATTENTE;
        $this->messages = new ArrayCollection();
        $this->chatMessages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescriptionRec(): ?string
    {
        return $this->description_rec;
    }

    public function setDescriptionRec(string $description_rec): self
    {
        $this->description_rec = $description_rec;
        return $this;
    }

    public function getStatutRec(): ?string
    {
        return $this->statut_rec;
    }

    public function setStatutRec(string $statut_rec): self
    {
        $this->statut_rec = $statut_rec;
        return $this;
    }

    public function getDateRec(): ?\DateTimeInterface
    {
        return $this->date_rec;
    }

    public function setDateRec(\DateTimeInterface $date_rec): self
    {
        $this->date_rec = $date_rec;
        return $this;
    }

    public function getMessageReclamation(): ?string
    {
        return $this->message_reclamation;
    }

    public function setMessageReclamation(?string $message_reclamation): self
    {
        $this->message_reclamation = $message_reclamation;
        return $this;
    }

    public function getHistoriqueConversations(): ?string
    {
        return $this->historiqueConversations;
    }

    public function setHistoriqueConversations(?string $historiqueConversations): self
    {
        $this->historiqueConversations = $historiqueConversations;
        return $this;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): self
    {
        $this->utilisateur = $utilisateur;
        return $this;
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): self
    {
        $this->produit = $produit;
        return $this;
    }

    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(ReclamationMessage $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setReclamation($this);
        }
        return $this;
    }

    public function removeMessage(ReclamationMessage $message): self
    {
        if ($this->messages->removeElement($message)) {
            if ($message->getReclamation() === $this) {
                $message->setReclamation(null);
            }
        }
        return $this;
    }

    public function getChatMessages(): Collection
    {
        return $this->chatMessages;
    }

    public function addChatMessage(ChatMessage $chatMessage): self
    {
        if (!$this->chatMessages->contains($chatMessage)) {
            $this->chatMessages->add($chatMessage);
            $chatMessage->setReclamation($this);
        }
        return $this;
    }

    public function removeChatMessage(ChatMessage $chatMessage): self
    {
        if ($this->chatMessages->removeElement($chatMessage)) {
            if ($chatMessage->getReclamation() === $this) {
                $chatMessage->setReclamation(null);
            }
        }
        return $this;
    }
}