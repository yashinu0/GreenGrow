<?php

namespace App\Service;

use App\Entity\Commande;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class PaiementService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function processPayment(Commande $commande, string $modePaiement): bool
    {
        try {
            // Vérifier si le montant est valide
            if ($commande->getPrixtotalCommande() <= 0) {
                throw new \Exception("Le montant du paiement doit être supérieur à 0");
            }

            // Mettre à jour le mode de paiement
            $commande->setModepaiementCommande($modePaiement);
            
            // Mettre à jour le statut de la commande
            $commande->setStatueCommande("en_preparation");

            // Persister les changements
            $this->entityManager->persist($commande);
            $this->entityManager->flush();

            return true;
        } catch (\Exception $e) {
            // Log l'erreur
            error_log($e->getMessage());
            return false;
        }
    }
}
