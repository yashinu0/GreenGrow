<?php

namespace App\Controller;

use App\Entity\Alerte;
use App\Form\AlerteType;
use App\Repository\AlerteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/alerte')]
final class AlerteController extends AbstractController
{
    #[Route(name: 'app_alerte_index', methods: ['GET'])]
    public function index(AlerteRepository $alerteRepository): Response
    {
        return $this->render('alerte/index.html.twig', [
            'alertes' => $alerteRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_alerte_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $alerte = new Alerte();
        $form = $this->createForm(AlerteType::class, $alerte);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($alerte);
            $entityManager->flush();

            return $this->redirectToRoute('app_alerte_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('alerte/new.html.twig', [
            'alerte' => $alerte,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_alerte_show', methods: ['GET'])]
    public function show(Alerte $alerte): Response
    {
        return $this->render('alerte/show.html.twig', [
            'alerte' => $alerte,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_alerte_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Alerte $alerte, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AlerteType::class, $alerte);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_alerte_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('alerte/edit.html.twig', [
            'alerte' => $alerte,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_alerte_delete', methods: ['POST'])]
    public function delete(Request $request, Alerte $alerte, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$alerte->getid_alerte(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($alerte);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_alerte_index', [], Response::HTTP_SEE_OTHER);
    }
}
