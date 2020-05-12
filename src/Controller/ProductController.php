<?php


namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/products", name="product_list")
     */
    public function listProducts()
    {
        $products = $this->getDoctrine()->getRepository(Product::class)->findAll();
        return $this->render('Product/listProducts.html.twig', [
            'products' => $products,
        ]);
    }

    /**
     * @Route("/product/{slug}", name="product_view")
     */
    public function viewProduct(Product $product)
    {
        return $this->render('Product/viewProduct.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * @Route("/product/add/{nb}")
     */
    public function addProduct($nb = 1)
    {
        $repository = $this->getDoctrine()->getRepository(Category::class);
        $repository = $repository->findAll();

        for ($i = 0; $i < $nb; $i++) {
            $product = new Product();
            $product->setName('Name');
            $product->setContent('Content');
            $product->setSlug('product-' . random_int(100, 999));
            $product->setCategory($repository[random_int(0, count($repository)-1)]);
            $product->setCreatedAt(new \DateTime());
            $this->getDoctrine()->getManager()->persist($product);
        }

        $this->getDoctrine()->getManager()->flush();

        return $this->redirect('/');
    }
}