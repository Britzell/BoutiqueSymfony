<?php


namespace App\Controller;


use App\Service\PaypalService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CheckoutController extends AbstractController
{
    /**
     * @Route("/checkout", name="checkout_verify")
     */
    public function checkoutVerify(PaypalService $paypalService)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $token = $paypalService->token();
        dd($token);
        return $this->render('Checkout/verify.html.twig');
    }
}
