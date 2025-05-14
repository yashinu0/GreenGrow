<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Commande;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\SvgWriter;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Color\Color;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mime\Address;
use Dompdf\Dompdf;
use Dompdf\Options;

class FactureController extends AbstractController
{
    private $params;
    private $logger;
    private $mailer;

    public function __construct(ParameterBagInterface $params, LoggerInterface $logger, MailerInterface $mailer)
    {
        $this->params = $params;
        $this->logger = $logger;
        $this->mailer = $mailer;
    }

    #[Route('/commande/{id_commande}/qrcode', name: 'commande_qrcode')]
    public function generateQrCode(int $id_commande): Response
    {
        try {
            // Générer l'URL absolue
            $url = $this->generateUrl('facture_afficher', 
                ['id_commande' => $id_commande], 
                UrlGeneratorInterface::ABSOLUTE_URL
            );

            // Créer le QR code
            $qrCode = QrCode::create($url)
                ->setSize(300)
                ->setMargin(10)
                ->setErrorCorrectionLevel(ErrorCorrectionLevel::High)
                ->setForegroundColor(new Color(0, 0, 0))
                ->setBackgroundColor(new Color(255, 255, 255));

            // Créer le writer
            $writer = new SvgWriter();
            
            // Générer le QR code
            $result = $writer->write($qrCode);

            // Log le succès
            $this->logger->info('QR code généré avec succès', [
                'url' => $url,
                'size' => $qrCode->getSize()
            ]);

            return new Response(
                $result->getString(),
                Response::HTTP_OK,
                ['Content-Type' => 'image/svg+xml']
            );

        } catch (\Exception $e) {
            // Log l'erreur
            $this->logger->error('Erreur lors de la génération du QR Code', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Créer une image SVG d'erreur simple
            $svg = sprintf(
                '<svg xmlns="http://www.w3.org/2000/svg" width="300" height="300">
                    <rect width="100%%" height="100%%" fill="#f8d7da"/>
                    <text x="50%%" y="45%%" text-anchor="middle" fill="#721c24" font-family="Arial" font-size="16">
                        Erreur QR Code
                    </text>
                    <text x="50%%" y="55%%" text-anchor="middle" fill="#721c24" font-family="Arial" font-size="12">
                        %s
                    </text>
                </svg>',
                htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8')
            );

            return new Response(
                $svg,
                Response::HTTP_INTERNAL_SERVER_ERROR,
                ['Content-Type' => 'image/svg+xml']
            );
        }
    }

    #[Route('/commande/{id_commande}/envoyer-facture', name: 'app_envoyer_facture', methods: ['GET', 'POST'])]
    public function envoyerFacture(Request $request, int $id_commande, EntityManagerInterface $entityManager): Response
    {
        try {
            $this->logger->info('Début de la fonction envoyerFacture', ['id_commande' => $id_commande]);

            $emailTo = $request->request->get('email');
            if (!$emailTo) {
                $this->addFlash('error', 'Veuillez fournir une adresse email.');
                return $this->redirectToRoute('facture_afficher', ['id_commande' => $id_commande]);
            }

            $commande = $entityManager->getRepository(Commande::class)->find($id_commande);
            if (!$commande) {
                throw $this->createNotFoundException('Commande non trouvée.');
            }

            $htmlContent = $this->renderView('emails/facture_email.html.twig', [
                'commande' => $commande,
                'serverIp' => $this->params->get('SERVER_IP')
            ]);

            try {
                $email = (new Email())
                    ->from(new Address('test@example.com', 'Service Facturation'))
                    ->to($emailTo)
                    ->subject('Votre facture pour la commande #' . $id_commande)
                    ->html($htmlContent)
                    ->text('Voici votre facture en pièce jointe.');

                $this->mailer->send($email);
                
                $this->logger->info('Email envoyé avec succès', [
                    'to' => $emailTo,
                    'subject' => 'Votre facture pour la commande #' . $id_commande
                ]);
                
                $this->addFlash('success', 'La facture a été envoyée avec succès à ' . $emailTo);
                
            } catch (\Exception $e) {
                $this->logger->error('Erreur lors de l\'envoi de l\'email', [
                    'error' => $e->getMessage(),
                    'to' => $emailTo,
                    'trace' => $e->getTraceAsString()
                ]);
                
                $this->addFlash('error', 'Erreur lors de l\'envoi de l\'email: ' . $e->getMessage());
                return $this->redirectToRoute('facture_afficher', ['id_commande' => $id_commande]);
            }

            return $this->redirectToRoute('facture_afficher', ['id_commande' => $id_commande]);

        } catch (\Exception $e) {
            $this->logger->error('Erreur générale', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            $this->addFlash('error', 'Une erreur est survenue: ' . $e->getMessage());
            return $this->redirectToRoute('facture_afficher', ['id_commande' => $id_commande]);
        }
    }

    #[Route('/commande/{id_commande}/facture', name: 'facture_afficher')]
    public function afficherFacture(int $id_commande, EntityManagerInterface $entityManager): Response
    {
        try {
            // Récupérer la commande
            $commande = $entityManager->getRepository(Commande::class)->find($id_commande);

            if (!$commande) {
                $this->logger->error('Commande non trouvée', ['id_commande' => $id_commande]);
                throw $this->createNotFoundException('Commande non trouvée.');
            }

            // Rendre la vue avec la commande
            return $this->render('facture/facture.html.twig', [
                'commande' => $commande
            ]);
        } catch (\Exception $e) {
            // Log l'erreur
            $this->logger->error('Erreur affichage facture', ['error' => $e->getMessage()]);
            
            return new Response(
                'Une erreur est survenue lors de l\'affichage de la facture: ' . $e->getMessage(),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    #[Route('/commande/{id_commande}/facture/pdf', name: 'facture_pdf')]
    public function generatePdf(int $id_commande, EntityManagerInterface $entityManager): Response
    {
        try {
            // Récupérer la commande
            $commande = $entityManager->getRepository(Commande::class)->find($id_commande);

            if (!$commande) {
                throw $this->createNotFoundException('Commande non trouvée.');
            }

            // Générer le contenu HTML de la facture
            $html = $this->renderView('facture/facture_pdf.html.twig', [
                'commande' => $commande,
                'date' => new \DateTime()
            ]);

            // Configurer Dompdf
            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isPhpEnabled', true);

            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            // Générer le nom du fichier
            $filename = sprintf('facture_%s.pdf', $id_commande);

            // Retourner le PDF
            return new Response(
                $dompdf->output(),
                Response::HTTP_OK,
                [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => sprintf('attachment; filename="%s"', $filename)
                ]
            );

        } catch (\Exception $e) {
            $this->logger->error('Erreur génération PDF', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            throw $this->createNotFoundException('Erreur lors de la génération du PDF: ' . $e->getMessage());
        }
    }

    #[Route('/commande/{id_commande}/facture/email', name: 'facture_email')]
    public function sendFactureEmail(Commande $commande, MailerInterface $mailer, EntityManagerInterface $entityManager): Response
    {
        try {
            // Générer le PDF
            $pdfContent = $this->generatePdfContent($commande->getIdCommande(), $entityManager);

            // Créer l'email
            $email = (new Email())
                ->from(new Address('chatteechatte@gmail.com', 'Service Facturation'))
                ->to('chatteechatte@gmail.com')  // Vous pouvez changer ceci plus tard pour l'email du client
                ->subject('Votre facture #' . $commande->getIdCommande())
                ->text('Veuillez trouver ci-joint votre facture pour la commande #' . $commande->getIdCommande())
                ->html('<p>Bonjour,</p>
                       <p>Veuillez trouver ci-joint votre facture pour la commande #' . $commande->getIdCommande() . '.</p>
                       <p>Merci de votre confiance !</p>')
                ->attach($pdfContent, 'facture-' . $commande->getIdCommande() . '.pdf', 'application/pdf');

            // Envoyer l'email
            $mailer->send($email);

            // Message flash de confirmation
            $this->addFlash('success', 'La facture a été envoyée par email avec succès !');

        } catch (\Exception $e) {
            // En cas d'erreur, afficher un message d'erreur
            $this->addFlash('error', 'Erreur lors de l\'envoi de l\'email : ' . $e->getMessage());
        }

        // Rediriger vers la page de la commande
        return $this->redirectToRoute('app_commande_show', ['id_commande' => $commande->getIdCommande()]);
    }

    private function generatePdfContent(int $id_commande, EntityManagerInterface $entityManager): string
    {
        try {
            // Récupérer la commande
            $commande = $entityManager->getRepository(Commande::class)->find($id_commande);

            if (!$commande) {
                throw $this->createNotFoundException('Commande non trouvée.');
            }

            // Configure Dompdf
            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isRemoteEnabled', true);

            // Instantiate Dompdf
            $dompdf = new Dompdf($options);
            
            // Generate HTML content with current date
            $html = $this->renderView('facture/facture_pdf.html.twig', [
                'commande' => $commande,
                'date' => new \DateTime()
            ]);

            // Load HTML to Dompdf
            $dompdf->loadHtml($html);

            // Set paper size and orientation
            $dompdf->setPaper('A4', 'portrait');

            // Render the PDF
            $dompdf->render();

            // Return the raw PDF content
            return $dompdf->output();

        } catch (\Exception $e) {
            $this->logger->error('Erreur génération PDF', ['error' => $e->getMessage()]);
            throw new \RuntimeException('Erreur lors de la génération du PDF: ' . $e->getMessage());
        }
    }
}
