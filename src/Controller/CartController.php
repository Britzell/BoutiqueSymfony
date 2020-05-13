<?php


namespace App\Controller;

use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="cart_list")
     */
    public function listCart(CartService $cartService)
    {
        return $this->render('Cart/listCart.html.twig', [
            'cartData' => $cartService->getCart(),
            'total' => $cartService->getTotal(),
        ]);
    }

    /**
     * @Route("/cart/add/{id}", name="cart_add_product")
     */
    public function addProductToCart(int $id, CartService $cartService)
    {
        $cartService->addProduct($id);
        return $this->redirectToRoute('cart_list');
    }

    /**
     * @Route("/cart/delete/{id}", name="cart_delete_product")
     */
    public function deleteProductToCart(int $id, CartService $cartService)
    {
        $cartService->deleteProduct($id);
        return $this->redirectToRoute('cart_list');
    }

    /**
     * @Route("/cart/delete", name="cart_delete")
     */
    public function deleteCart(CartService $cartService)
    {
        $cartService->deleteCart();
        return $this->redirectToRoute('cart_list');
    }
}