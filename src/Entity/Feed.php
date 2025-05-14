<?php

namespace App\Entity;

use App\Repository\FeedRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FeedRepository::class)]
class Feed
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    #[Assert\Email]
    private ?string $email_feed = null;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank]
    private ?string $commentaire_feed = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    private ?string $subject_feed = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $date_feed = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    private ?string $name_feed = null;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private ?bool $isProcessed = false;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    private ?string $sentiment = 'NEUTRAL';

    public function __construct()
    {
        $this->date_feed = new \DateTime('now');
    }

    public function getId(): ?int { return $this->id; }

    public function getEmailFeed(): ?string { return $this->email_feed; }
    public function setEmailFeed(string $email_feed): self { $this->email_feed = $email_feed; return $this; }

    public function getCommentaireFeed(): ?string { return $this->commentaire_feed; }
    public function setCommentaireFeed(string $commentaire_feed): self { $this->commentaire_feed = $commentaire_feed; return $this; }

    public function getSubjectFeed(): ?string { return $this->subject_feed; }
    public function setSubjectFeed(string $subject_feed): self { $this->subject_feed = $subject_feed; return $this; }

    public function getDateFeed(): ?\DateTimeInterface { return $this->date_feed; }
    public function setDateFeed(\DateTimeInterface $date_feed): self { $this->date_feed = $date_feed; return $this; }

    public function getNameFeed(): ?string { return $this->name_feed; }
    public function setNameFeed(string $name_feed): self { $this->name_feed = $name_feed; return $this; }

    public function isProcessed(): bool{ return $this->isProcessed; }
    public function setIsProcessed(bool $isProcessed): self { $this->isProcessed = $isProcessed; return $this; }

    public function getSentiment(): ?string
    {
        return $this->sentiment;
    }

    public function setSentiment(?string $sentiment): self
    {
        $this->sentiment = $sentiment;
        return $this;
    }
}