<?php


namespace App\Service;

use App\Entity\User;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Stripe;
use Stripe\StripeClient;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class StripeService
{
    protected $session;
    protected $cartService;
    protected $charge;

    public function getCharge()
    {
        return $this->charge;
    }

    public function __construct(SessionInterface $session, CartService $cartService)
    {
        $this->session = $session;
        $this->cartService = $cartService;
    }

    public function createPayment(User $user)
    {
        $stripe = new StripeClient($_ENV['STRIPE_CLIENT']);
        Stripe::setApiKey($_ENV['STRIPE_SECRET']);

        $token = $stripe->tokens->create([
            'card' => $this->session->get('card')->getData()
        ]);

        $customer = Customer::create([
            'description' => 'Customer for ' . $user->getEmail(),
            'email' => $user->getEmail(),
            'source' => $token['id']
        ]);

        $this->charge = Charge::create([
            'amount' => $this->cartService->getTotal() * 100,
            'currency' => 'eur',
            'customer' => $customer['id']
        ]);

        if ($this->charge['id']) {
            $this->cartService->deleteCart();
            return true;
        } else {
            return false;
        }
    }
}
