<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/api/products')]
class ProductController extends AbstractController
{
    #[Route('/', name: 'api_products_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository): JsonResponse
    {
        $products = $productRepository->findAllOrderByName();

        $formatted = [];
        foreach ($products as $product) {
            $formatted[] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'description' => $product->getDescription(),
            ];
        }

        return new JsonResponse($formatted);
    }

    #[Route('/{id}', name: 'api_products_show', methods: ['GET'])]
    public function show(Product $product = null): JsonResponse
    {
        if (empty($product)) {
            return new JsonResponse(['message' => "No product matches this ID."], Response::HTTP_NOT_FOUND);
        }

        $formatted = [
            'id' => $product->getId(),
            'name' => $product->getName(),
            'description' => $product->getDescription(),
        ];

        return new JsonResponse($formatted);
    }
}
