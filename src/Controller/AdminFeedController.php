<?php

namespace App\Controller;

use App\Entity\Feed;
use App\Repository\FeedRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin', name: 'admin_')]
class AdminFeedController extends AbstractController
{
    #[Route('/feed', name: 'feed_list')]
    public function index(FeedRepository $feedRepository): Response
    {
        $feeds = $feedRepository->findBy([], ['date_feed' => 'DESC']);

        return $this->render('admin/feed/index.html.twig', [
            'feeds' => $feeds,
        ]);
    }

    #[Route('/feed/{id}', name: 'feed_show')]
    public function show(Feed $feed): Response
    {
        return $this->render('admin/feed/show.html.twig', [
            'feed' => $feed,
        ]);
    }

    #[Route('/admin/feed/{id}/toggle-status', name: 'feed_status')]
    public function toggleStatus(Feed $feed, EntityManagerInterface $entityManager): Response
    {
        $feed->setIsProcessed(!$feed->isProcessed());
    
        $entityManager->flush();
    
        $this->addFlash('success', 'Statut du feedback mis à jour avec succès.');
    
        return $this->redirectToRoute('admin_feed_list', ['id' => $feed->getId()]);
    }


    #[Route('/feed/{id}/delete', name: 'feed_delete')]
    public function delete(Feed $feed, FeedRepository $repository): Response
    {
        $repository->remove($feed, true);
        
        $this->addFlash('success', 'Le feed a été supprimé avec succès');
        
        return $this->redirectToRoute('admin_feed_list');
    }


    
}