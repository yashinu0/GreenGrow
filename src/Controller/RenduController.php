<?php

namespace App\Controller;

use App\Entity\Rendu;
use App\Form\RenduType;
use App\Repository\RenduRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/rendu')]
final class RenduController extends AbstractController
{
    #[Route(name: 'app_rendu_index', methods: ['GET'])]
    public function index(RenduRepository $renduRepository): Response
    {
        return $this->render('rendu/index.html.twig', [
            'rendus' => $renduRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_rendu_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $rendu = new Rendu();
        $form = $this->createForm(RenduType::class, $rendu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($rendu);
            $entityManager->flush();

            return $this->redirectToRoute('app_rendu_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('rendu/new.html.twig', [
            'rendu' => $rendu,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_rendu_show', methods: ['GET'])]
    public function show(Rendu $rendu): Response
    {
        return $this->render('rendu/show.html.twig', [
            'rendu' => $rendu,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_rendu_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Rendu $rendu, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RenduType::class, $rendu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_rendu_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('rendu/edit.html.twig', [
            'rendu' => $rendu,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_rendu_delete', methods: ['POST'])]
    public function delete(Request $request, Rendu $rendu, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$rendu->getid_rendu(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($rendu);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_rendu_index', [], Response::HTTP_SEE_OTHER);
    }
}
