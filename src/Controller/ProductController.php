<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\CartItem;
use App\Entity\Product;
use App\Form\ItemType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/')]
class ProductController extends AbstractController
{
    #[Route('/', name: 'app_products_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findAllOrderByName(),
        ]);
    }

    #[Route('/{slug:product}', name: 'app_products_show', methods: ['GET'])]
    public function show(?Product $product = null): Response
    {
        if (null === $product) {
            $this->addFlash('notice', ['type' => 'danger', 'title' => 'Oops!', 'message' => 'Product does not exist.']);

            return $this->redirectToRoute('app_products_index');
        }

        $item = new CartItem();
        $item->setProduct($product);
        $item->setQuantity(1);
        $form = $this->createForm(ItemType::class, $item);

        return $this->render('product/show.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }
}
