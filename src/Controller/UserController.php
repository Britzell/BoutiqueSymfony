<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/sign-in")
     */
    public function signIn()
    {
        return $this->render('User/signIn.html.twig');
    }

    /**
     * @Route("/sign-up")
     */
    public function signUp()
    {
        return $this->render('User/signUp.html.twig');
    }
}