<?php

namespace App\Entity;

use App\Repository\RenduRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RenduRepository::class)]
class Rendu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_rendu  = null;

    #[ORM\Column(length: 255)]
    private ?string $message_rendu = null;

    #[ORM\Column(length: 255)]
    private ?string $type_rendu = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $Date_envoi_rendu = null;

    public function getid_rendu  (): ?int
    {
        return $this->id_rendu;
    }

    public function getMessageRendu(): ?string
    {
        return $this->message_rendu;
    }

    public function setMessageRendu(string $message_rendu): static
    {
        $this->message_rendu = $message_rendu;

        return $this;
    }

    public function getTypeRendu(): ?string
    {
        return $this->type_rendu;
    }

    public function setTypeRendu(string $type_rendu): static
    {
        $this->type_rendu = $type_rendu;

        return $this;
    }

    public function getDateEnvoiRendu(): ?\DateTimeInterface
    {
        return $this->Date_envoi_rendu;
    }

    public function setDateEnvoiRendu(\DateTimeInterface $Date_envoi_rendu): static
    {
        $this->Date_envoi_rendu = $Date_envoi_rendu;

        return $this;
    }
}