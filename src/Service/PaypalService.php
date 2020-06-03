<?php


namespace App\Service;

use App\Entity\User;
use Payum\Core\Model\Payment;
use Payum\Core\Payum;
use Payum\Core\Request\Capture;
use Payum\Core\Request\GetHumanStatus;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PaypalService
{
    protected $session;
    protected $cartService;
    protected $payum;

    public function __construct(SessionInterface $session, CartService $cartService, Payum $payum)
    {
        $this->session = $session;
        $this->cartService = $cartService;
        $this->payum = $payum;
    }

    public function createPayment(User $user)
    {
        $storage = $this->payum->getStorage(Payment::class);

        $payment = $storage->create();
        $payment->setNumber(uniqid());
        $payment->setCurrencyCode('EUR');
        $payment->setTotalAmount($this->cartService->getTotal() * 100);
        $payment->setDescription('Customer for ' . $user->getEmail());
        $payment->setClientId($user->getId());
        $payment->setClientEmail($user->getEmail());

        $storage->update($payment);

        $captureToken = $this->payum->getTokenFactory()->createCaptureToken(
            'paypal',
            $payment,
            'checkout_done'
        );

        return $captureToken->getTargetUrl();
    }

    public function donePayment(Request $request)
    {
        $token = $this->payum->getHttpRequestVerifier()->verify($request);

        $identity = $token->getDetails();
        $model = $this->payum->getStorage($identity->getClass())->find($identity);

        $gateway = $this->payum->getGateway($token->getGatewayName());

        // you can invalidate the token. The url could not be requested any more.
        // $this->payum->getHttpRequestVerifier()->invalidate($token);

        // Once you have token you can get the model from the storage directly.
        //$identity = $token->getDetails();
        //$details = $payum->getStorage($identity->getClass())->find($identity);

        // or Payum can fetch the model for you while executing a request (Preferred).
        $gateway->execute($status = new GetHumanStatus($token));
        $details = $status->getFirstModel();

        // you have order and payment status
        // so you can do whatever you want for example you can just print status and payment details.

        return new JsonResponse(array(
            'status' => $status->getValue(),
            'details' => iterator_to_array($details),
        ));

        $token = $this->payum->getHttpRequestVerifier()->verify($request);
        $gateway = $this->payum->getGateway($token->getGatewayName());

        $gateway->execute($status = new GetHumanStatus($token));
        $payment = $status->getFirstModel();

        return [
            'status' => $status->getValue(),
            'payment' => [
                'total_amount' => $payment->getTotalAmount(),
                'currency_code' => $payment->getCurrencyCode(),
                'details' => $payment->getDetails(),
            ],
        ];
    }
}