<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Element;
use App\Form\ElementType;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/')]
class ProduitController extends AbstractController
{
    #[Route('/', name: 'app_produits_index', methods: ['GET'])]
    public function index(ProduitRepository $produitRepository): Response
    {
        return $this->render('produit/index.html.twig', [
            'produits' => $produitRepository->findAllOrderByNom()
        ]);
    }

    #[Route('/{id}', name: 'app_produits_show', methods: ['GET'])]
    public function show(Produit $produit = null): Response
    {
        if (null === $produit) {
            $this->addFlash('notice', ['type' => 'danger', 'title' =>'Oops!', 'message' => "Ce produit n'existe pas."]);

            return $this->redirectToRoute('app_produits_index');
        }

        $element = new Element();
        $element->setProduit($produit);
        $element->setQuantity(1);
        $form = $this->createForm(ElementType::class, $element);

        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
            ]
        );
    }
}
