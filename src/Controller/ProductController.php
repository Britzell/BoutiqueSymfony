<?php


namespace App\Controller;

use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/products", name="product_list")
     */
    public function listProducts()
    {
        $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();

        return $this->render('Product/listProducts.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/product/{id}/view", name="product_view")
     */
    public function viewProduct($id)
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);

        dump($article);

        if ($article === null)
            throw $this->createNotFoundException('The article with ID "' . $id . '" is not found');

        return $this->render('Product/viewProduct.html.twig', [
            'article' => $article,
        ]);
    }
}