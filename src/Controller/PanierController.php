<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\Element;
use App\Form\ElementType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;


#[Route('/panier')]
class PanierController extends AbstractController
{
    public function __construct(private RequestStack $requestStack)
    {
    }

    #[Route('/', name: 'app_panier_index', methods: ['GET'])]
    public function panier(Request $request): Response
    {
        $session = $request->getSession();

        $panier = $session->get('panier');

        if($panier == null){
            $panier = new Panier();
            $session->set('panier', $panier);
        }

        $form = $this->createViderPanierForm();

        return $this->render('panier/index.html.twig', [
            'panier' => $panier,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/ajouter', name: 'app_panier_ajouter', methods: ['POST'])]
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

        $request->getSession()->set('panier', $panier);

        return $this->redirectToRoute('app_panier_index');
    }

    #[Route('/modifier', name: 'app_panier_modifier', methods: ['POST'])]
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

        $request->getSession()->set('panier', $panier);

        return new JsonResponse($response);
    }

    #[Route('/element/supprimer', name: 'app_panier_element_supprimer', methods: ['POST'])]
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

        $request->getSession()->set('panier', $panier);

        return $this->redirectToRoute('app_panier_index');
    }

    #[Route('/vider', name: 'app_panier_vider', methods: ['POST'])]
    public function vider(Request $request): Response
    {
        $panier = new Panier();
        $request->getSession()->set('panier', $panier);

        return $this->redirectToRoute('app_panier_index');
    }

    private function createViderPanierForm(): FormInterface
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('app_panier_vider'))
            ->setMethod('POST')
            ->getForm()
        ;
    }

    public function getPanier(): Panier
    {
        $session = $this->requestStack->getSession();

        $panier = $session->get('panier');

        if($panier == null){
            $panier = new Panier();
            $session->set('panier', $panier);
        }

        return $panier;
    }
}
