<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Commande;
use App\Entity\Livreur;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class VoiceCommandController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private CommandeRepository $commandeRepository;

    public function __construct(EntityManagerInterface $entityManager, CommandeRepository $commandeRepository)
    {
        $this->entityManager = $entityManager;
        $this->commandeRepository = $commandeRepository;
    }

    #[Route('/commande/vocale', name: 'app_commande_vocale')]
    public function index(): Response
    {
        return $this->render('commande/commande_vocale.html.twig');
    }

    #[Route('/api/process-voice-command', name: 'api_process_voice_command', methods: ['POST'])]
    public function processVoiceCommand(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            $command = strtolower($data['command'] ?? '');

            if (empty($command)) {
                return $this->json([
                    'success' => false,
                    'message' => 'Aucune commande reçue'
                ]);
            }

            // Nettoyer et normaliser la commande
            $command = trim(strtolower($command));
            
            // Debug: Afficher la commande reçue
            error_log("Commande reçue: " . $command);

            // Traiter les différents types de commandes
            if (str_contains($command, 'nouvelle commande') || str_contains($command, 'créer commande')) {
                // Extraire le montant de la commande
                if (preg_match('/(\d+)(?:\s+euros?)?/', $command, $matches)) {
                    $montant = floatval($matches[1]);
                } else {
                    $montant = 0.01; // Montant par défaut
                }

                // Détecter le mode de paiement avec debug
                $modePaiement = 'carte'; // Par défaut
                
                // Debug: Vérifier chaque condition
                error_log("Vérification du mode de paiement...");
                
                if (str_contains($command, 'espèce') || str_contains($command, 'especes')) {
                    error_log("Mode espèces détecté");
                    $modePaiement = 'especes';
                } 
                elseif (str_contains($command, 'carte')) {
                    error_log("Mode carte détecté");
                    $modePaiement = 'carte';
                } 
                elseif (str_contains($command, 'en ligne')) {
                    error_log("Mode en ligne détecté");
                    $modePaiement = 'en_ligne';
                }
                
                error_log("Mode de paiement choisi: " . $modePaiement);

                // Créer la nouvelle commande
                $commande = new Commande();
                $commande->setDateCommande(new \DateTime());
                $commande->setStatueCommande('en_preparation');
                $commande->setPrixtotalCommande($montant);
                $commande->setModepaiementCommande($modePaiement);

                // Assigner un livreur
                $livreur = $this->entityManager->getRepository(Livreur::class)->findOneBy([]);
                if (!$livreur) {
                    throw new \Exception('Aucun livreur disponible');
                }
                $commande->setLivreurCommande($livreur);

                $this->entityManager->persist($commande);
                $this->entityManager->flush();

                return $this->json([
                    'success' => true,
                    'message' => sprintf('Commande créée avec succès pour %d euros, paiement par %s', $montant, $this->formatModePaiement($modePaiement)),
                    'redirectUrl' => $this->generateUrl('app_commande_show', ['id_commande' => $commande->getIdCommande()])
                ]);
            } 
            elseif (str_contains($command, 'liste des commandes')) {
                return $this->json([
                    'success' => true,
                    'message' => 'Redirection vers la liste des commandes',
                    'redirectUrl' => $this->generateUrl('app_commande_index')
                ]);
            }
            else {
                return $this->json([
                    'success' => false,
                    'message' => 'Commande non reconnue. Essayez par exemple "nouvelle commande pour 50 euros espèces" ou "liste des commandes"'
                ]);
            }
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Erreur lors du traitement de la commande: ' . $e->getMessage()
            ]);
        }
    }

    private function formatModePaiement(string $mode): string
    {
        return match($mode) {
            'especes' => 'espèces',
            'carte' => 'carte bancaire',
            'en_ligne' => 'paiement en ligne',
            default => $mode
        };
    }
}
