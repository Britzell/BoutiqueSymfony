<?php


namespace App\Service;

use App\Entity\User;
use Payum\Core\Model\Payment;
use Payum\Core\Request\Capture;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PaypalService extends AbstractController
{
    protected $session;
    protected $cartService;

    public function __construct(SessionInterface $session, CartService $cartService)
    {
        $this->session = $session;
        $this->cartService = $cartService;
    }

    public function createPayment(User $user, $payum)
    {
        $payment = new Payment();
        $payment->setNumber(uniqid());
        $payment->setCurrencyCode('EUR');
        $payment->setTotalAmount($this->cartService->getTotal() * 100);
        $payment->setDescription('Customer for ' . $user->getEmail());
        $payment->setClientId($user->getId());
        $payment->setClientEmail($user->getEmail());

        $gateway = $payum->getGateway('offline');
        $gateway->execute(new Capture($payment));
        dump($payment);
        dd($gateway);
    }
}
