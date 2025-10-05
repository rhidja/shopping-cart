<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Form\ItemType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/cart')]
class CartController extends AbstractController
{
    public function __construct(private RequestStack $requestStack)
    {
    }

    #[Route('/', name: 'app_cart_index', methods: ['GET'])]
    public function cart(Request $request): Response
    {
        $session = $request->getSession();

        $cart = $session->get('cart');

        if($cart == null){
            $cart = new Cart();
            $session->set('cart', $cart);
        }

        $form = $this->createViderCartForm();

        return $this->render('cart/index.html.twig', [
            'cart' => $cart,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/add', name: 'app_cart_add', methods: ['POST'])]
    public function add(Request $request): Response
    {
        $cart = $this->getCart();

        $item = new CartItem();
        $form = $this->createForm(ItemType::class, $item);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if (!$cart->getItems()->isEmpty()) {
                $exists = false;
                $items = $cart->getItems();

                foreach ($items as $key => $elem) {
                    if($elem->getProduct()->getId() == $item->getProduct()->getId()){
                        $quantity = $elem->getQuantity() + $item->getQuantity();
                        $cart->getItems()->get($key)->setQuantity($quantity);
                        $exists = true;
                        break;
                    }
                }

                if(!$exists){
                    $cart->addItem($item);
                }
            }else{
                $cart->addItem($item);
            }
        }

        $request->getSession()->set('cart', $cart);

        return $this->redirectToRoute('app_cart_index');
    }

    #[Route('/update', name: 'app_cart_update', methods: ['POST'])]
    public function modify(Request $request): JsonResponse
    {
        $cart = $this->getCart();

        $productId = $request->get('product_id');
        $quantity = $request->get('quantity');


        if (!$cart->getItems()->isEmpty()) {

            $items = $cart->getItems();
            foreach ($items as $key => $item) {
                if($item->getProduct()->getId() == $productId){
                    if($quantity == 0 ){
                        $cart->getItems()->removeElement($item);
                    }else{
                        $cart->getItems()->get($key)->setQuantity($quantity);
                    }

                    $response=["status" => "ok", "message" => "Quantity is updated"];
                    break;
                }
            }
        }else{
            $response=["status" => "ko", "message" => "Product not found"];
        }

        $request->getSession()->set('cart', $cart);

        return new JsonResponse($response ?? []);
    }

    #[Route('/item/delete', name: 'app_cart_item_delete', methods: ['POST'])]
    public function supprimer(Request $request): Response
    {
        $cart = $this->getCart();

        $productId = $request->get('product_id');

        if (!$cart->getItems()->isEmpty()) {

            $items = $cart->getItems();
            foreach ($items as $item) {
                if($item->getProduct()->getId() == $productId){
                    $cart->getItems()->removeElement($item);
                    break;
                }
            }
        }

        $request->getSession()->set('cart', $cart);

        return $this->redirectToRoute('app_cart_index');
    }

    #[Route('/empty', name: 'app_cart_empty', methods: ['POST'])]
    public function vider(Request $request): Response
    {
        $cart = new Cart();
        $request->getSession()->set('cart', $cart);

        return $this->redirectToRoute('app_cart_index');
    }

    private function createViderCartForm(): FormInterface
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('app_cart_empty'))
            ->setMethod('POST')
            ->getForm()
        ;
    }

    public function getCart(): Cart
    {
        $session = $this->requestStack->getSession();

        $cart = $session->get('cart');

        if($cart == null){
            $cart = new Cart();
            $session->set('cart', $cart);
        }

        return $cart;
    }
}
