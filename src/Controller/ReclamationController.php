<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Entity\ReclamationMessage;
use App\Entity\Produit;
use App\Entity\Utilisateur;
use App\Form\ReclamationType;
use App\Form\ReclamationMessageType;
use App\Form\ReclamationStatusType;
use App\Service\OllamaService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Psr\Log\LoggerInterface;
use App\Entity\ChatMessage;

class ReclamationController extends AbstractController
{
    private $httpClient;
    private $ollamaService;
    private $logger;

    public function __construct(
        HttpClientInterface $httpClient, 
        OllamaService $ollamaService,
        LoggerInterface $logger
    ) {
        $this->httpClient = $httpClient;
        $this->ollamaService = $ollamaService;
        $this->logger = $logger;
    }

    private function notifyAdmin(Reclamation $reclamation)
    {
        try {
            $this->httpClient->request('POST', 'http://localhost:3000/notify', [
                'json' => [
                    'id' => $reclamation->getId(),
                    'dateRec' => $reclamation->getDateRec()->format('Y-m-d H:i:s'),
                    'descriptionRec' => $reclamation->getDescriptionRec(),
                    'messageReclamation' => $reclamation->getMessageReclamation(),
                    'produit' => [
                        'id' => $reclamation->getProduit() ? $reclamation->getProduit()->getId() : null,
                        'nomProduit' => $reclamation->getProduit() ? $reclamation->getProduit()->getNomProduit() : null
                    ]
                ]
            ]);
        } catch (\Exception $e) {
        }
    }

    #[Route('/reclamation', name: 'app_reclamation_list')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user instanceof Utilisateur) {
            return $this->redirectToRoute('app_login');
        }

        $reclamations = $entityManager->getRepository(Reclamation::class)->findBy(['utilisateur' => $user]);
        return $this->render('reclamation/index.html.twig', [
            'reclamations' => $reclamations,
        ]);
    }

    #[Route('/reclamation/nouvelle/{productId}', name: 'app_reclamation_new')]
    public function new(Request $request, EntityManagerInterface $entityManager, int $productId): Response
    {
        $user = $this->getUser();
        if (!$user instanceof Utilisateur) {
            throw new AccessDeniedException('Vous devez être connecté pour créer une réclamation.');
        }

        $produit = $entityManager->getRepository(Produit::class)->find($productId);
        if (!$produit) {
            throw $this->createNotFoundException('Produit non trouvé');
        }
        
        $reclamation = new Reclamation();
        $reclamation->setProduit($produit);
        $reclamation->setDateRec(new \DateTime());
        $reclamation->setUtilisateur($user);
        $reclamation->setStatutRec('Pending');
        
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->persist($reclamation);
                
                $message = new ReclamationMessage();
                $message->setReclamation($reclamation);
                $message->setContent($reclamation->getMessageReclamation());
                $message->setIsFromAdmin(false);
                $message->setSentAt(new \DateTime());
                $message->setSender($user);
                
                $entityManager->persist($message);
                $entityManager->flush();

                try {
                    $this->notifyAdmin($reclamation);
                } catch (\Exception $e) {
                    $this->logger->error('Erreur lors de la notification admin: ' . $e->getMessage());
                }

                $this->addFlash('success', 'Votre réclamation a été soumise avec succès.');
                return $this->redirectToRoute('app_reclamation_show', ['id' => $reclamation->getId()]);
            } catch (\Exception $e) {
                $this->logger->error('Erreur lors de la création de la réclamation: ' . $e->getMessage());
                $this->addFlash('error', 'Une erreur est survenue lors de l\'enregistrement de votre réclamation.');
            }
        }

        return $this->render('reclamation/new.html.twig', [
            'form' => $form->createView(),
            'produit' => $produit,
        ]);
    }

    #[Route('/reclamation/{id}', name: 'app_reclamation_show')]
    public function show(Reclamation $reclamation, Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user instanceof Utilisateur) {
            return $this->redirectToRoute('app_login');
        }

        if (!$reclamation) {
            throw $this->createNotFoundException('Réclamation non trouvée');
        }

        if ($reclamation->getUtilisateur() !== $user && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à voir cette réclamation');
        }

        $statusForm = $this->createForm(ReclamationStatusType::class, $reclamation);
        $statusForm->handleRequest($request);

        if ($statusForm->isSubmitted() && $statusForm->isValid()) {
            try {
                $entityManager->flush();
                $this->addFlash('success', 'Le statut a été mis à jour avec succès.');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de la mise à jour du statut.');
            }
        }

        $message = new ReclamationMessage();
        $messageForm = $this->createForm(ReclamationMessageType::class, $message);
        $messageForm->handleRequest($request);

        if ($messageForm->isSubmitted() && $messageForm->isValid()) {
            try {
                $message->setReclamation($reclamation)
                    ->setSender(null)
                    ->setSentAt(new \DateTime());

                $entityManager->persist($message);
                $entityManager->flush();

                $this->addFlash('success', 'Votre message a été envoyé avec succès.');
                return $this->redirectToRoute('app_reclamation_show', ['id' => $reclamation->getId()]);
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de l\'envoi du message.');
            }
        }

        return $this->render('reclamation/show.html.twig', [
            'reclamation' => $reclamation,
            'status_form' => $statusForm->createView(),
            'message_form' => $messageForm->createView(),
        ]);
    }

    #[Route('/admin/reclamation', name: 'admin_reclamation_index')]
    public function adminIndex(EntityManagerInterface $entityManager): Response
    {
        $reclamations = $entityManager->getRepository(Reclamation::class)->findAll();
        return $this->render('admin/reclamation/index.html.twig', [
            'reclamations' => $reclamations,
        ]);
    }

    #[Route('/admin/reclamation/{id}', name: 'admin_reclamation_show')]
    public function adminShow(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $reclamation = $entityManager->getRepository(Reclamation::class)->find($id);
        if (!$reclamation) {
            throw $this->createNotFoundException('Réclamation non trouvée');
        }

        $statusForm = $this->createForm(ReclamationStatusType::class, $reclamation);
        $statusForm->handleRequest($request);

        if ($statusForm->isSubmitted() && $statusForm->isValid()) {
            try {
                $entityManager->flush();
                $this->addFlash('success', 'Le statut a été mis à jour avec succès.');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de la mise à jour du statut.');
            }
        }

        $message = new ReclamationMessage();
        $messageForm = $this->createForm(ReclamationMessageType::class, $message);
        $messageForm->handleRequest($request);

        if ($messageForm->isSubmitted() && $messageForm->isValid()) {
            try {
                $message->setReclamation($reclamation)
                    ->setSender($this->getUser())
                    ->setIsFromAdmin(true)
                    ->setSentAt(new \DateTime());

                $entityManager->persist($message);
                $entityManager->flush();

                $this->addFlash('success', 'Votre message a été envoyé avec succès.');
                return $this->redirectToRoute('admin_reclamation_show', ['id' => $reclamation->getId()]);
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de l\'envoi du message.');
            }
        }

        return $this->render('admin/reclamation/show.html.twig', [
            'reclamation' => $reclamation,
            'status_form' => $statusForm->createView(),
            'message_form' => $messageForm->createView(),
        ]);
    }

    #[Route('/admin/check-new-reclamations', name: 'admin_check_new_reclamations')]
    public function checkNewReclamations(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $lastId = $request->query->get('lastId', 0);
        
        $newReclamation = $entityManager->getRepository(Reclamation::class)
            ->createQueryBuilder('r')
            ->where('r.id > :lastId')
            ->setParameter('lastId', $lastId)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
        
        return new JsonResponse([
            'hasNew' => $newReclamation !== null
        ]);
    }

    #[Route('/reclamation/{id}/ai-assist', name: 'app_reclamation_ai_assist', methods: ['GET', 'POST'])]
    public function aiAssist(
        Reclamation $reclamation,
        Request $request,
        EntityManagerInterface $entityManager,
        SessionInterface $session
    ): Response {
        if (!in_array($reclamation->getStatutRec(), ['Pending', 'In_progress'])) {
            $this->addFlash('error', 'L\'assistant AI n\'est disponible que pour les réclamations en attente ou en cours.');
            return $this->redirectToRoute('app_reclamation_show', ['id' => $reclamation->getId()]);
        }

        if (!$reclamation) {
            throw $this->createNotFoundException('Réclamation non trouvée');
        }

        $sessionKey = 'chat_history_' . $reclamation->getId();
        $messages = [];
        
        if ($reclamation->getHistoriqueConversations()) {
            try {
                $messages = json_decode($reclamation->getHistoriqueConversations(), true) ?: [];
            } catch (\Exception $e) {
                $this->logger->error('Erreur lors du décodage de l\'historique: ' . $e->getMessage());
            }
        }
        
        if (empty($messages)) {
            $messages = $session->get($sessionKey, []);
        }

        if (empty($messages)) {
            $messages[] = [
                'content' => "Bonjour ! Je suis votre assistant AI. Je suis là pour vous aider avec votre réclamation concernant : " . $reclamation->getDescriptionRec() . "\nQue puis-je faire pour vous ?",
                'isFromUser' => false,
                'timestamp' => (new \DateTime())->format('Y-m-d H:i:s')
            ];
        }

        if ($request->isMethod('POST')) {
            $userMessage = trim($request->request->get('message'));
            
            if (!empty($userMessage)) {
                try {
                    $messages[] = [
                        'content' => $userMessage,
                        'isFromUser' => true,
                        'timestamp' => (new \DateTime())->format('Y-m-d H:i:s')
                    ];

                    $prompt = sprintf(
                        "Tu es un assistant client professionnel et empathique. Contexte: Une réclamation client concernant '%s'.\nMessage du client: %s\nRéponds de manière professionnelle et empathique en français.",
                        $reclamation->getDescriptionRec(),
                        $userMessage
                    );
                    
                    try {
                        $response = $this->ollamaService->generateResponse($prompt);
                        
                        if (!isset($response['text']) || empty(trim($response['text']))) {
                            throw new \Exception('Réponse vide reçue de l\'API');
                        }
                        
                        $messages[] = [
                            'content' => $response['text'],
                            'isFromUser' => false,
                            'timestamp' => (new \DateTime())->format('Y-m-d H:i:s')
                        ];

                        $session->set($sessionKey, $messages);
                        try {
                            $reclamation->setHistoriqueConversations(json_encode($messages, JSON_UNESCAPED_UNICODE));
                            $entityManager->persist($reclamation);

                            $chatMessage = new ChatMessage();
                            $chatMessage->setReclamation($reclamation);
                            $chatMessage->setMessage($userMessage);
                            $chatMessage->setResponse($response['text']);
                            $chatMessage->setCreatedAt(new \DateTimeImmutable());

                            $entityManager->persist($chatMessage);
                            $entityManager->flush();
                        } catch (\Exception $e) {
                            $this->logger->error('Erreur lors de la sauvegarde de l\'historique: ' . $e->getMessage());
                        }
                        
                    } catch (\Exception $e) {
                        $this->logger->error('Erreur lors de la génération de réponse: ' . $e->getMessage(), [
                            'exception' => $e,
                            'reclamation_id' => $reclamation->getId(),
                            'user_message' => $userMessage
                        ]);
                        throw $e;
                    }

                } catch (\Exception $e) {
                    $this->logger->error('Erreur AI: ' . $e->getMessage());
                    
                    $messages[] = [
                        'content' => "Désolé, je rencontre des difficultés techniques pour le moment. L'erreur a été enregistrée et sera examinée par notre équipe technique. Veuillez réessayer dans quelques instants.",
                        'isFromUser' => false,
                        'timestamp' => (new \DateTime())->format('Y-m-d H:i:s')
                    ];
                    
                    $session->set($sessionKey, $messages);
                    try {
                        $reclamation->setHistoriqueConversations(json_encode($messages, JSON_UNESCAPED_UNICODE));
                        $entityManager->persist($reclamation);
                        $entityManager->flush();
                    } catch (\Exception $saveError) {
                        $this->logger->error('Erreur lors de la sauvegarde de l\'historique: ' . $saveError->getMessage());
                    }
                }
            }

            return $this->redirectToRoute('app_reclamation_ai_assist', ['id' => $reclamation->getId()]);
        }

        return $this->render('reclamation/ai_assist.html.twig', [
            'reclamation' => $reclamation,
            'messages' => $messages
        ]);
    }
}