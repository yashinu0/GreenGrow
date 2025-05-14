<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Form\Commande1Type;
use App\Repository\CommandeRepository;
use App\Service\PaiementService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

#[Route('/commandee')]
final class CommandeController extends AbstractController
{
    private PaiementService $paiementService;

    public function __construct(PaiementService $paiementService)
    {
        $this->paiementService = $paiementService;
    }

    #[Route(name: 'app_commande_index', methods: ['GET'])]
    public function index(CommandeRepository $commandeRepository): Response
    {
        return $this->render('commande/index.html.twig', [
            'commandes' => $commandeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_commande_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $commande = new Commande();
        $form = $this->createForm(Commande1Type::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($commande);
            $entityManager->flush();

            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commande/new.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

    #[Route('/{id_commande}', name: 'app_commande_show', methods: ['GET'])]
    #[ParamConverter('commande', options: ['mapping' => ['id_commande' => 'id_commande']])]
    public function show(Commande $commande): Response
    {
        return $this->render('commande/show.html.twig', [
            'commande' => $commande,
        ]);
    }

    #[Route('/{id_commande}/edit', name: 'app_commande_edit', methods: ['GET', 'POST'])]
    #[ParamConverter('commande', options: ['mapping' => ['id_commande' => 'id_commande']])]
    public function edit(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Commande1Type::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commande/edit.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

    #[Route('/{id_commande}', name: 'app_commande_delete', methods: ['POST'])]
    #[ParamConverter('commande', options: ['mapping' => ['id_commande' => 'id_commande']])]
    public function delete(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commande->getIdCommande(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($commande);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id_commande}/paiement', name: 'app_commande_paiement', methods: ['GET', 'POST'])]
    #[ParamConverter('commande', options: ['mapping' => ['id_commande' => 'id_commande']])]
    public function paiement(Request $request, Commande $commande): Response
    {
        if ($request->isMethod('POST')) {
            $modePaiement = $request->request->get('mode_paiement');
            
            if ($this->paiementService->processPayment($commande, $modePaiement)) {
                $this->addFlash('success', 'Paiement effectué avec succès !');
                return $this->redirectToRoute('facture_afficher', ['id_commande' => $commande->getIdCommande()]);
            } else {
                $this->addFlash('error', 'Une erreur est survenue lors du paiement.');
            }
        }

        return $this->render('commande/paiement.html.twig', [
            'commande' => $commande,
        ]);
    }
}
