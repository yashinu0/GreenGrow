<?php
// src/Controller/FalertController.php
namespace App\Controller;

use App\Entity\Alerte;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class FalertController extends AbstractController
{
    #[Route('/falert', name: 'app_falert')]
    public function index(): Response
    {
        return $this->render('falert/index.html.twig', [
            'controller_name' => 'FalertController',
        ]);
    }

    #[Route('/save-alert', name: 'app_save_alert', methods: ['POST'])]
    public function saveAlert(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);

        // Save alert to the `alerte` table
        $alerte = new Alerte();
        $alerte->setNiveauUrgenceAlerte($data['urgency']);
        $alerte->setTempsLimiteAlerte(new \DateTime());

        $entityManager->persist($alerte);
        $entityManager->flush();

        return $this->json(['status' => 'success', 'message' => 'Alert saved successfully.']);
    }
}