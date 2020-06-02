<?php


namespace App\Controller;


use App\Entity\Category;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category/add/{nb}")
     */
    public function addProduct($nb = 1)
    {
        for ($i = 0; $i < $nb; $i++) {
            $category = new Category();
            $category->setName('Name');
            $category->setSlug('category-' . random_int(100, 999));
            $this->getDoctrine()->getManager()->persist($category);
        }

        $this->getDoctrine()->getManager()->flush();

        return $this->redirect('/');
    }

    /**
     * @Route("/categories", name="category_list")
     */
    public function listCategory()
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        return $this->render('Category/listCategory.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/category/{id}/{slug}", name="category_view")
     */
    public function viewCategory(int $id)
    {
        $products = $this->getDoctrine()->getRepository(Product::class)->findBy(['category' => $id]);
        return $this->render('Product/listProducts.html.twig', [
            'products' => $products
        ]);
    }
}
