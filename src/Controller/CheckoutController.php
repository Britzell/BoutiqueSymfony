<?php


namespace App\Controller;


use App\Entity\Checkout\Card;
use App\Entity\Checkout\GatewayConfig;
use App\Form\CheckoutType;
use App\Form\ConfigPaypalGatewayConfigType;
use App\Service\CartService;
use App\Service\PaypalService;
use App\Service\StripeService;
use Payum\Core\Payum;
use Payum\Core\Request\GetHumanStatus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CheckoutController extends AbstractController
{
    public function check(SessionInterface $session)
    {
        if ($session->get('cart') === null || $session->get('cart') === [])
            return $this->redirectToRoute('cart_list');
        /*if ($session->get('card') === null || $session->get('card') === [])
            return $this->redirectToRoute('checkout_add_card');*/
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

        $form = $this->createFormBuilder();

        return $this->render('Checkout/verify.html.twig', [
            // 'card' => $session->get('card'),
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
    public function buy(SessionInterface $session)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        if ($this->check($session))
            return $this->check($session);

        $error = null;

        if (true) { // paypal true
            return $this->redirectToRoute('checkout_paypal');
        } else {
            $error = 'Erreur lors du paiement Paypal';
        }

        if (false) { // stripe true
            $session->remove('cart');
        } else {
            $error = 'Erreur lors du paiement par carte';
        }

        return $this->render('Checkout/buy.html.twig', [
            'error' => $error,
        ]);
    }

    /**
     * @Route("/checkout/paypal", name="checkout_paypal")
     */
    public function paypalPayment(Request $request, PaypalService $paypalService)
    {
        return $this->redirect($paypalService->createPayment($this->getUser()));
    }

    /**
     * @Route("/checkout/paypal/done", name="checkout_paypal_done")
     */
    public function paypalDone(Request $request, PaypalService $paypalService)
    {
        return $paypalService->donePayment($request);
    }

    /**
     * @Route("/checkout/card", name="checkout_stripe")
     */
    public function stripePayment(StripeService $stripeService)
    {
        if ($stripeService->createPayment($this->getUser()) === false)
            $error = 'Erreur lors du paiement stipe';
    }

    /**
     * @Route("/checkout/done", name="checkout_done")
     */
    public function doneCheckout(Request $request)
    {
        dd($request);
        $error = null;
        $payment = $paypalService->donePayment($request);
        if ($payment['status'] === 'captured')
            $error = 'Merci de votre achat !';

        return $this->render('Checkout/buy.html.twig', [
            'error' => $error,
        ]);
    }
}
