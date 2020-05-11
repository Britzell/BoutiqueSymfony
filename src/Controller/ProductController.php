<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/products")
     */
    public function listProducts()
    {
        return $this->render('Product/listProducts.html.twig');
    }

    /**
     * @Route("/products/view")
     */
    public function viewProduct()
    {
        return $this->render('Product/viewProduct.html.twig');
    }
}