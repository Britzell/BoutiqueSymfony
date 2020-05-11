<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ErrorController extends AbstractController
{
    /**
     * @Route("/not-found")
     */
    public function notFound()
    {
        return $this->render('Error/notFound.html.twig');
    }
}