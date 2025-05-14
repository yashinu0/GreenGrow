<?php

namespace App\Entity;

use App\Repository\AlerteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AlerteRepository::class)]
class Alerte
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_alerte  = null;

    #[ORM\Column(length: 255)]
    private ?string $Niveau_urgence_alerte = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $temps_limite_alerte = null;

    public function getid_alerte (): ?int
    {
        return $this->id_alerte;
    }

    public function getNiveauUrgenceAlerte(): ?string
    {
        return $this->Niveau_urgence_alerte;
    }

    public function setNiveauUrgenceAlerte(string $Niveau_urgence_alerte): static
    {
        $this->Niveau_urgence_alerte = $Niveau_urgence_alerte;

        return $this;
    }

    public function getTempsLimiteAlerte(): ?\DateTimeInterface
    {
        return $this->temps_limite_alerte;
    }

    public function setTempsLimiteAlerte(\DateTimeInterface $temps_limite_alerte): static
    {
        $this->temps_limite_alerte = $temps_limite_alerte;

        return $this;
    }
}