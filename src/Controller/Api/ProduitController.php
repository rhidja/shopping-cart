<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/api/produits')]
class ProduitController extends AbstractController
{
    #[Route('/', name: 'api_produits_index', methods: ['GET'])]
    public function index(ProduitRepository $produitRepository): JsonResponse
    {
        $produits = $produitRepository->findAllOrderByNom();

        $formatted = [];
        foreach ($produits as $produit) {
            $formatted[] = [
                'id' => $produit->getId(),
                'nom' => $produit->getNom(),
                'description' => $produit->getDescription(),
            ];
        }

        return new JsonResponse($formatted);
    }

    #[Route('/{id}', name: 'api_produits_show', methods: ['GET'])]
    public function show(Produit $produit = null): JsonResponse
    {
        if (empty($produit)) {
            return new JsonResponse(['message' => "Aucun produit ne correspond a cette Id"], Response::HTTP_NOT_FOUND);
        }

        $formatted = [
            'id' => $produit->getId(),
            'nom' => $produit->getNom(),
            'description' => $produit->getDescription(),
        ];

        return new JsonResponse($formatted);
    }
}
