<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/produits")
 */
class ProduitController extends Controller
{
    /**
     * @Route("/", name="produits_index", methods="GET")
     */
    public function index(ProduitRepository $produitRepository): Response
    {
        return $this->render('produit/index.html.twig', ['produits' => $produitRepository->findAllOrderByNom()]);
    }

    /**
     * @Route("/{id}", name="produits_show", methods="GET")
     */
    public function show(Produit $produit = null): Response
    {
        if (null === $produit) {
            $this->addFlash('notice', ['type' => 'danger', 'title' =>'Oops!', 'message' => "Ce produit n'existe pas."]);

            return $this->redirectToRoute('produits_index');
        }

        return $this->render('produit/show.html.twig', ['produit' => $produit]);
    }
}
