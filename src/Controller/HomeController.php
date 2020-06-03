<?php


namespace App\Controller;

use App\Entity\Checkout\GatewayConfig;
use Payum\Core\Payum;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        return $this->render('Home/home.html.twig');
    }
}
