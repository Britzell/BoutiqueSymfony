<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/sign-in", name="sign_in")
     */
    public function signIn()
    {
        return $this->render('User/signIn.html.twig');
    }

    /**
     * @Route("/sign-up", name="sign_up")
     */
    public function signUp()
    {
        return $this->render('User/signUp.html.twig');
    }
}