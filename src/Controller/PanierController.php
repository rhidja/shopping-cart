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

        if($panier == null){
            $panier = new Panier();
            $this->get('session')->set('panier', $panier);
        }

        $form = $this->createPanierForm();

        return $this->render('panier/index.html.twig', [
            'panier' => $panier,
            'form' => $form->createView(),
        ]);
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

            if($panier == null){
                $panier = new Panier();
                $this->get('session')->set('panier', $panier);
            }

            if (!$panier->getElements()->isEmpty()) {
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
            }else{
                $panier->addElement($element);
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
     * @Route("/vider", name="panier_vider", methods="POST")
     */
    public function vider(Request $request): Response
    {
        $panier = new Panier();
        $this->get('session')->set('panier', $panier);

        return $this->redirectToRoute('panier_index');
    }

    private function createPanierForm()
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('panier_vider'))
            ->setMethod('POST')
            ->getForm()
        ;
    }

    public function ifEmptySession()
    {
        # code...
    }
}
