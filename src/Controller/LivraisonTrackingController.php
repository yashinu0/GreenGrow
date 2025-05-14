<?php

namespace App\Controller;

use App\Entity\LivreurPosition;
use App\Entity\Commande;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

#[Route('/livraison')]
class LivraisonTrackingController extends AbstractController
{
    #[Route('/{id_commande}/track', name: 'livraison_track')]
    public function track(int $id_commande, EntityManagerInterface $entityManager): Response
    {
        $commande = $entityManager->getRepository(Commande::class)->find($id_commande);
        
        if (!$commande) {
            throw $this->createNotFoundException('Commande non trouvée');
        }

        // Vérifier si une position existe, sinon en créer une par défaut
        $position = $entityManager->getRepository(LivreurPosition::class)
            ->findOneBy(['commande' => $commande]);

        if (!$position) {
            $position = new LivreurPosition();
            $position->setCommande($commande);
            $position->setLivreur($commande->getLivreurCommande());
            $position->setLatitude(36.8065); // Position par défaut (Tunis)
            $position->setLongitude(10.1815);
            $position->setLastUpdate(new \DateTimeImmutable());
            
            $entityManager->persist($position);
            $entityManager->flush();
        }

        return $this->render('livraison/track.html.twig', [
            'commande' => $commande
        ]);
    }

    #[Route('/{id_commande}/livreur', name: 'livraison_livreur')]
    public function livreurInterface(int $id_commande, EntityManagerInterface $entityManager): Response
    {
        $commande = $entityManager->getRepository(Commande::class)->find($id_commande);
        
        if (!$commande) {
            throw $this->createNotFoundException('Commande non trouvée');
        }

        // Vérifier si une position existe, sinon en créer une par défaut
        $position = $entityManager->getRepository(LivreurPosition::class)
            ->findOneBy(['commande' => $commande]);

        if (!$position) {
            $position = new LivreurPosition();
            $position->setCommande($commande);
            $position->setLivreur($commande->getLivreurCommande());
            $position->setLatitude(36.8065); // Position par défaut (Tunis)
            $position->setLongitude(10.1815);
            $position->setLastUpdate(new \DateTimeImmutable());
            
            $entityManager->persist($position);
            $entityManager->flush();
        }

        return $this->render('livraison/livreur.html.twig', [
            'commande' => $commande
        ]);
    }

    #[Route('/{id_commande}/position', name: 'api_livraison_position', methods: ['POST'])]
    public function updatePosition(
        Request $request,
        int $id_commande,
        EntityManagerInterface $entityManager,
        HubInterface $hub
    ): JsonResponse {
        $commande = $entityManager->getRepository(Commande::class)->find($id_commande);
        
        if (!$commande) {
            return new JsonResponse(['error' => 'Commande non trouvée'], 404);
        }

        $data = json_decode($request->getContent(), true);
        
        if (!isset($data['latitude']) || !isset($data['longitude'])) {
            return new JsonResponse(['error' => 'Coordonnées manquantes'], 400);
        }

        // Mettre à jour ou créer la position
        $position = $entityManager->getRepository(LivreurPosition::class)
            ->findOneBy(['commande' => $commande]);

        if (!$position) {
            $position = new LivreurPosition();
            $position->setLivreur($commande->getLivreurCommande());
            $position->setCommande($commande);
        }

        $position->setLatitude($data['latitude']);
        $position->setLongitude($data['longitude']);
        $position->setLastUpdate(new \DateTimeImmutable());

        $entityManager->persist($position);
        $entityManager->flush();

        // Publier la mise à jour via Mercure
        $update = new Update(
            "livraison/{$commande->getIdCommande()}",
            json_encode([
                'latitude' => $position->getLatitude(),
                'longitude' => $position->getLongitude(),
                'lastUpdate' => $position->getLastUpdate()->format('Y-m-d H:i:s')
            ])
        );

        $hub->publish($update);

        return new JsonResponse(['status' => 'Position mise à jour']);
    }

    #[Route('/{id_commande}/position', name: 'api_get_livraison_position', methods: ['GET'])]
    public function getPosition(int $id_commande, EntityManagerInterface $entityManager): JsonResponse
    {
        $commande = $entityManager->getRepository(Commande::class)->find($id_commande);
        
        if (!$commande) {
            return new JsonResponse(['error' => 'Commande non trouvée'], 404);
        }

        $position = $entityManager->getRepository(LivreurPosition::class)
            ->findOneBy(['commande' => $commande]);

        if (!$position) {
            // Créer une position par défaut
            $position = new LivreurPosition();
            $position->setCommande($commande);
            $position->setLivreur($commande->getLivreurCommande());
            $position->setLatitude(36.8065); // Position par défaut (Tunis)
            $position->setLongitude(10.1815);
            $position->setLastUpdate(new \DateTimeImmutable());
            
            $entityManager->persist($position);
            $entityManager->flush();
        }

        return new JsonResponse([
            'latitude' => $position->getLatitude(),
            'longitude' => $position->getLongitude(),
            'lastUpdate' => $position->getLastUpdate()->format('Y-m-d H:i:s')
        ]);
    }
}
