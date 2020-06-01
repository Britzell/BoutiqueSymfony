<?php


namespace App\Service;

use Curl\Curl;
use Symfony\Component\Dotenv\Dotenv;

class PaypalService
{

    private $curl;

    public function __construct()
    {
        $this->curl = new Curl();
        $this->curl->setOpt(CURLOPT_SSL_VERIFYPEER, 0);
    }

    public function token()
    {
        $this->curl->setHeader('Accept: application/x-www-form-urlencoded', 'Accept-Language: en_US');
        $this->curl->setBasicAuthentication($_ENV['PAYPAL_CLIENT'], $_ENV['PAYPAL_SECRET']);
        $this->curl->post('https://api.sandbox.paypal.com/v1/oauth2/token', [
            'grant_type' => 'client_credentials'
        ]);
        if ($this->curl->error) {
            return $this->curl->error_message;
        } else {
            return $this->curl->response;
        }

    }
}
