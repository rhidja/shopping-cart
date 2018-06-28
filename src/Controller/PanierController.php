<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\Produit;
use App\Entity\Element;
use App\Form\ElementType;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/panier")
 */
class PanierController extends Controller
{
    /**
     * @Route("/", name="panier_index", methods="GET")
     */
    public function panier(): Response
    {
        $panier = $this->get('session')->get('panier');
        //$this->get('session')->set('panier', null);

        // print '<pre>';
        // print_r($panier);
        // print '<pre>';

        return $this->render('panier/index.html.twig', ['panier' => $panier]);
    }

    /**
     * @Route("/plus", name="panier_plus", methods="POST")
     */
    public function plus(Request $request): Response
    {
        $element = new Element();
        $form = $this->createForm(ElementType::class, $element);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $panier = $this->get('session')->get('panier');

            if ($panier === null) {
                $panier = new Panier();
                $panier->addElement($element);
            }else{
                $exists = false;
                $elements = $panier->getElements();

                foreach ($elements as $key => $elem) {
                    if($elem->getProduit()->getId() == $element->getProduit()->getId()){
                        $quantity = $elem->getQuantity() + $element->getQuantity();
                        $panier->getElements()->get($key)->setQuantity($quantity);
                        $exists = true;
                        break;
                    }
                }

                if(!$exists){
                    $panier->addElement($element);
                }
            }

            $this->get('session')->set('panier', $panier);
        }

        return $this->redirectToRoute('panier_index');
    }

    /**
     * @Route("/moins", name="panier_moins", methods="POST")
     */
    public function moins(Request $request): Response
    {
        return $this->render('produit/show.html.twig', ['produit' => $produit]);
    }

    /**
     * @Route("/moins", name="panier_vider", methods="POST")
     */
    public function vider(Request $request): Response
    {
        $this->get('session')->set('panier', null);
    }
}
