<?php


namespace App\Controller;


use App\Entity\Checkout\Card;
use App\Form\CheckoutType;
use App\Service\CartService;
use App\Service\PaypalService;
use App\Service\StripeService;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Stripe;
use Stripe\StripeClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CheckoutController extends AbstractController
{
    public function check(SessionInterface $session)
    {
        if ($session->get('cart') === null || $session->get('cart') === [])
            return $this->redirectToRoute('cart_list');
        if ($session->get('card') === null || $session->get('card') === [])
            return $this->redirectToRoute('checkout_add_card');
        return false;
    }

    /**
     * @Route("/checkout", name="checkout_verify")
     */
    public function checkoutVerify(CartService $cartService, SessionInterface $session)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        if ($this->check($session))
            return $this->check($session);

        return $this->render('Checkout/verify.html.twig', [
            'card' => $session->get('card'),
            'cartData' => $cartService->getCart(),
            'total' => $cartService->getTotal(),
        ]);
    }

    /**
     * @Route("/checkout/add/card", name="checkout_add_card")
     */
    public function addCard(Request $request, SessionInterface $session)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $card = new Card();
        $form = $this->createForm(CheckoutType::class, $card);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $card->setNumber(str_replace(' ', '', $card->getNumber()));
            $card->setExpYear('20' . $card->getExpYear());
            $session->set('card', $card);
            return $this->redirectToRoute('checkout_verify');
        }

        return $this->render('Checkout/addCard.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/checkout/buy", name="checkout_buy")
     */
    public function buy(StripeService $stripeService, SessionInterface $session)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        if ($this->check($session))
            return $this->check($session);

        $error = null;
        if ($stripeService->createPayment($this->getUser()) === false)
            $error = 'Erreur lors du paiement';

        if ($error === null) {
            $error = 'Merci de votre achat !';
            $session->remove('cart');
        }

        return $this->render('Checkout/buy.html.twig', [
            'error' => $error
        ]);
    }
}
