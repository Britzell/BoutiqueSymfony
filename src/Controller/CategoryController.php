<?php


namespace App\Controller;


use App\Entity\Category;
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
}