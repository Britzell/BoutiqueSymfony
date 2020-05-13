<?php


namespace App\Service;


use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
    protected $session;
    protected $productRepo;

    public function __construct(SessionInterface $session, ProductRepository $productRepo)
    {
        $this->session = $session;
        $this->productRepo = $productRepo;
    }

    public function addProduct(int $id)
    {
        $cart = $this->session->get('cart', []);

        if (!empty($cart[$id]))
            $cart[$id]++;
        else
            $cart[$id] = 1;

        $this->session->set('cart', $cart);
    }

    public function deleteProduct(int $id)
    {
        $cart = $this->session->get('cart', []);

        if (isset($cart[$id]))
            unset($cart[$id]);

        $this->session->set('cart', $cart);
    }

    public function deleteCart()
    {
        $this->session->set('cart', []);
    }

    public function getCart(): array
    {
        $cart = $this->session->get('cart', []);
        $cartData = [];

        foreach ($cart as $id => $quantity) {
            $cartData[] = [
                'product' => $this->productRepo->find($id),
                'quantity' => $quantity,
            ];
        }
        return $cartData;
    }

    public function getTotal(): float
    {
        $total = 0;

        foreach ($this->getCart() as $item) {
            $total += $item['product']->getPrice() * $item['quantity'];
        }
        return $total;
    }
}