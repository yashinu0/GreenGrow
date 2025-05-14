<?php

namespace App\Entity;

use App\Repository\ReclamationMessageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReclamationMessageRepository::class)]
class ReclamationMessage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Reclamation::class, inversedBy: "messages")]
    private ?Reclamation $reclamation = null;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class)]
    #[ORM\JoinColumn(name: "sender_id", referencedColumnName: "id_user")]
    private ?Utilisateur $sender = null;

    #[ORM\Column(type: "text")]
    private ?string $content = null;

    #[ORM\Column(type: "datetime")]
    private ?\DateTimeInterface $sentAt = null;

    #[ORM\Column(type: "boolean")]
    private bool $isFromAdmin = false;

    public function __construct()
    {
        $this->sentAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReclamation(): ?Reclamation
    {
        return $this->reclamation;
    }

    public function setReclamation(?Reclamation $reclamation): self
    {
        $this->reclamation = $reclamation;
        return $this;
    }

    public function getSender(): ?Utilisateur
    {
        return $this->sender;
    }

    public function setSender(?Utilisateur $sender): self
    {
        $this->sender = $sender;
        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function getSentAt(): ?\DateTimeInterface
    {
        return $this->sentAt;
    }

    public function setSentAt(\DateTimeInterface $sentAt): self
    {
        $this->sentAt = $sentAt;
        return $this;
    }

    public function getIsFromAdmin(): bool
    {
        return $this->isFromAdmin;
    }

    public function setIsFromAdmin(bool $isFromAdmin): self
    {
        $this->isFromAdmin = $isFromAdmin;
        return $this;
    }
}