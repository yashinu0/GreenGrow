<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class ProduitssController extends AbstractController
{
    #[Route('/produitss', name: 'app_produitss')]
    public function index(ProduitRepository $produitRepository): Response
    {
        $produits = $produitRepository->findAll();

        return $this->render('produitss/index.html.twig', [
            'produits' => $produits,
        ]);
    }

    #[Route('/produitss/{id}', name: 'app_produitss_show', methods: ['GET'])]
    public function show(Produit $produit): Response
    {
        return $this->render('produitss/show.html.twig', [
            'produit' => $produit,
        ]);
    }
}