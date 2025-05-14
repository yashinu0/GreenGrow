<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Entity\ReclamationMessage;
use App\Entity\Utilisateur;
use App\Form\ReclamationMessageType;
use App\Form\AdminReclamationStatusType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Psr\Log\LoggerInterface;

#[Route('/admin')]
class AdminReclamationController extends AbstractController
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    #[Route('/reclamation', name: 'admin_reclamation_index')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException('Accès refusé. Vous devez être administrateur.');
        }

        try {
            $this->cleanOrphanedReclamations($entityManager);

            $reclamations = $entityManager->getRepository(Reclamation::class)
                ->createQueryBuilder('r')
                ->select('r', 'u', 'p')
                ->leftJoin('r.utilisateur', 'u')
                ->leftJoin('r.produit', 'p')
                ->where('u.id_user IS NOT NULL')
                ->andWhere('p.id IS NOT NULL')
                ->orderBy('r.date_rec', 'DESC')
                ->getQuery()
                ->getResult();

            return $this->render('admin/reclamation/index.html.twig', [
                'reclamations' => $reclamations,
            ]);
        } catch (\Exception $e) {
            $this->logger->error('Erreur lors du chargement des réclamations: ' . $e->getMessage());
            $this->addFlash('error', 'Une erreur est survenue lors du chargement des réclamations.');
            return $this->redirectToRoute('admin_dashboard');
        }
    }

    private function cleanOrphanedReclamations(EntityManagerInterface $entityManager): void
    {
        try {
            $orphanedReclamations = $entityManager->getRepository(Reclamation::class)
                ->createQueryBuilder('r')
                ->leftJoin('r.utilisateur', 'u')
                ->leftJoin('r.produit', 'p')
                ->where('u.id_user IS NULL OR p.id IS NULL')
                ->getQuery()
                ->getResult();

            foreach ($orphanedReclamations as $reclamation) {
                $entityManager->remove($reclamation);
                $this->logger->info('Suppression de la réclamation orpheline ID: ' . $reclamation->getId());
            }

            if (count($orphanedReclamations) > 0) {
                $entityManager->flush();
                $this->addFlash('warning', count($orphanedReclamations) . ' réclamation(s) orpheline(s) ont été supprimées.');
            }
        } catch (\Exception $e) {
            $this->logger->error('Erreur lors du nettoyage des réclamations orphelines: ' . $e->getMessage());
        }
    }

    #[Route('/reclamation/{id}', name: 'admin_reclamation_show')]
    public function show(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException('Accès refusé. Vous devez être administrateur.');
        }

        try {
            $reclamation = $entityManager->getRepository(Reclamation::class)
                ->createQueryBuilder('r')
                ->select('r', 'u', 'p', 'm')
                ->leftJoin('r.utilisateur', 'u')
                ->leftJoin('r.produit', 'p')
                ->leftJoin('r.messages', 'm')
                ->where('r.id = :id')
                ->andWhere('u.id_user IS NOT NULL')
                ->andWhere('p.id IS NOT NULL')
                ->setParameter('id', $id)
                ->getQuery()
                ->getOneOrNullResult();

            if (!$reclamation) {
                throw $this->createNotFoundException('Réclamation non trouvée ou invalide');
            }

            $statusForm = $this->createForm(AdminReclamationStatusType::class, $reclamation);
            $statusForm->handleRequest($request);

            if ($statusForm->isSubmitted() && $statusForm->isValid()) {
                $entityManager->flush();
                $this->addFlash('success', 'Le statut de la réclamation a été mis à jour.');
                return $this->redirectToRoute('admin_reclamation_show', ['id' => $reclamation->getId()]);
            }

            $message = new ReclamationMessage();
            $messageForm = $this->createForm(ReclamationMessageType::class, $message);
            $messageForm->handleRequest($request);

            if ($messageForm->isSubmitted() && $messageForm->isValid()) {
                $message->setReclamation($reclamation);
                $message->setSentAt(new \DateTime());
                $message->setIsFromAdmin(true);
                $message->setSender($this->getUser());

                $entityManager->persist($message);
                $entityManager->flush();

                $this->addFlash('success', 'Votre message a été ajouté.');
                return $this->redirectToRoute('admin_reclamation_show', ['id' => $reclamation->getId()]);
            }

            return $this->render('admin/reclamation/show.html.twig', [
                'reclamation' => $reclamation,
                'status_form' => $statusForm->createView(),
                'message_form' => $messageForm->createView(),
            ]);
        } catch (\Exception $e) {
            $this->logger->error('Erreur lors du chargement de la réclamation: ' . $e->getMessage());
            $this->addFlash('error', 'Une erreur est survenue lors du chargement de la réclamation.');
            return $this->redirectToRoute('admin_reclamation_index');
        }
    }
}