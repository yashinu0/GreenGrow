<?php

namespace App\Controller;

use App\Entity\Feed;
use App\Form\FeedType;
use App\Service\MailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3Validator;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use App\Service\FeedbackSentimentService;

class FeedController extends AbstractController
{
    #[Route('/feed', name: 'app_feed')]
    public function index(Request $request, EntityManagerInterface $entityManager, MailService $mailService, ValidatorInterface $validator, Recaptcha3Validator $recaptcha3Validator, FeedbackSentimentService $sentimentService): Response
    {
        $feed = new Feed();
        $form = $this->createForm(FeedType::class, $feed);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $violations = $validator->validate($feed, null);
            $score = $recaptcha3Validator->getLastResponse()->getScore();

            if (count($violations) > 0 || ($score !== null && $score < 0.9)) {
                $this->addFlash('danger', 'Votre connexion semble suspecte. Score reCAPTCHA : ' . ($score));
                return $this->redirectToRoute('app_feed');
            }

            // Analyse du sentiment
            $sentiment = $sentimentService->analyze($feed->getCommentaireFeed());
            $feed->setSentiment($sentiment);

            // Set the date first
            $feed->setDateFeed(new \DateTime());
            
            // Persist the feed
            $entityManager->persist($feed);
            $entityManager->flush();

            // Try to send the email
            try {
                $mailService->sendEmail(
                    'greengrowfeed@gmail.com',  // From address
                    'greengrowfeed@gmail.com',  // To address
                    'Nouveau Message de ' . $feed->getNameFeed() . ' concernant ' . $feed->getSubjectFeed(),
                    'Nom: ' . $feed->getNameFeed() . "\n" .
                    'Email: ' . $feed->getEmailFeed() . "\n" .
                    'Sujet: ' . $feed->getSubjectFeed() . "\n" .
                    'Date: ' . $feed->getDateFeed()->format('Y-m-d H:i:s') . "\n" .
                    'Message: ' . $feed->getCommentaireFeed()
                );
                $this->addFlash('success', 'Votre message a été envoyé avec succès.');
            } catch (TransportExceptionInterface $e) {
                // Log the error
                error_log('Erreur lors de l\'envoi de l\'email: ' . $e->getMessage());
                $this->addFlash('warning', 'Votre message a été enregistré mais il y a eu un problème lors de l\'envoi de l\'email.');
            }

            return $this->redirectToRoute('app_feed');
        }

        return $this->render('feed/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}