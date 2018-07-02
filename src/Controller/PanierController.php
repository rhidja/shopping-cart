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
use Symfony\Component\HttpFoundation\JsonResponse;


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

        $form = $this->createViderPanierForm();

        return $this->render('panier/index.html.twig', [
            'panier' => $panier,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/ajouter", name="panier_ajouter", methods="POST")
     */
    public function ajouter(Request $request): Response
    {
        $panier = $this->getPanier();

        $element = new Element();
        $form = $this->createForm(ElementType::class, $element);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

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
        }

        $this->get('session')->set('panier', $panier);

        return $this->redirectToRoute('panier_index');
    }

    /**
     * @Route("/modifier", name="panier_modifier", methods="POST")
     */
    public function modifier(Request $request): JsonResponse
    {
        $panier = $this->getPanier();

        $produitId = $request->get('produit_id');
        $quantity = $request->get('quantity');


        if (!$panier->getElements()->isEmpty()) {

            $elements = $panier->getElements();
            foreach ($elements as $key => $element) {
                if($element->getProduit()->getId() == $produitId){
                    if($quantity == 0 ){
                        $panier->getElements()->removeElement($element);
                    }else{
                        $panier->getElements()->get($key)->setQuantity($quantity);
                    }

                    $response=["status" => "ok", "message" => "Quantity mise à jours"];
                    break;
                }
            }
        }else{
            $response=["status" => "ko", "message" => "Produit non existant"];
        }

        $this->get('session')->set('panier', $panier);

        return new JsonResponse($response);
    }

    /**
     * @Route("/element/supprimer", name="panier_element_supprimer", methods="POST")
     */
    public function supprimer(Request $request): Response
    {
        $panier = $this->getPanier();

        $produitId = $request->get('produit_id');

        if (!$panier->getElements()->isEmpty()) {

            $elements = $panier->getElements();
            foreach ($elements as $element) {
                if($element->getProduit()->getId() == $produitId){
                    $panier->getElements()->removeElement($element);
                    break;
                }
            }
        }

        $this->get('session')->set('panier', $panier);

        return $this->redirectToRoute('panier_index');
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

    private function createViderPanierForm()
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('panier_vider'))
            ->setMethod('POST')
            ->getForm()
        ;
    }

    public function getPanier()
    {
        $panier = $this->get('session')->get('panier');

        if($panier == null){
            $panier = new Panier();
            $this->get('session')->set('panier', $panier);
        }

        return $panier;
    }
}
