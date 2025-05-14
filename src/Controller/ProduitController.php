<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use App\Repository\CategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/produit')]
final class ProduitController extends AbstractController
{
    #[Route(name: 'app_produit_index', methods: ['GET'])]
    public function index(ProduitRepository $produitRepository, CategoriesRepository $categoriesRepository): Response
    {
        $produits = $produitRepository->findAll();
        $categories = $categoriesRepository->findAll();

        // Calculer le nombre de produits par catégorie
        $produitsParCategorie = [];
        foreach ($categories as $category) {
            $produitsParCategorie[$category->getNomCategories()] = count($category->getProduits());
        }

        return $this->render('produit/index.html.twig', [
            'produits' => $produits,
            'produitsParCategorie' => $produitsParCategorie,
        ]);
    }

    #[Route('/new', name: 'app_produit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image_produit')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle exception if something happens during file upload
                }

                $produit->setImageProduit($newFilename);
            }

            $entityManager->persist($produit);
            $entityManager->flush();

            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_produit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image_produit')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle exception if something happens during file upload
                }

                $produit->setImageProduit($newFilename);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_produit_show', methods: ['GET'])]
    public function show(Produit $produit): Response
    {
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('/{id}', name: 'app_produit_delete', methods: ['POST'])]
    public function delete(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->request->get('_token'))) {
            $entityManager->remove($produit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/rate', name: 'app_produitss_rate', methods: ['POST'])]
    public function rate(Request $request, EntityManagerInterface $entityManager, ProduitRepository $produitRepository): JsonResponse
    {
        $rating = $request->request->get('rating');
        $productId = $request->request->get('id');
    
        if (!$rating || !$productId) {
            return new JsonResponse(['error' => 'Invalid data'], Response::HTTP_BAD_REQUEST);
        }
    
        $produit = $produitRepository->find($productId);
    
        if (!$produit) {
            return new JsonResponse(['error' => 'Product not found'], Response::HTTP_NOT_FOUND);
        }
    
        $produit->setRating((float)$rating);
        $entityManager->persist($produit);
        $entityManager->flush();
    
        return new JsonResponse(['message' => 'Rating saved successfully!']);
    }
    
    #[Route('/{id}/decrease_quantity', name: 'app_produit_decrease_quantity', methods: ['POST'])]
public function decreaseQuantity(Request $request, Produit $produit, EntityManagerInterface $entityManager): JsonResponse
{
    if ($produit->getQuantite() > 0) {
        $produit->setQuantite($produit->getQuantite() - 1);
        $entityManager->persist($produit);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Quantity decreased successfully!', 'newQuantity' => $produit->getQuantite()]);
    }

    return new JsonResponse(['error' => 'No more products available.'], Response::HTTP_BAD_REQUEST);
}
    
}