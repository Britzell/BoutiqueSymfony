<?php


namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use Cocur\Slugify\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/product/create", name="product_create")
     */
    public function createProduct(Request $request)
    {
        dump($request->request);
        $product = new Product();
        $repository = $this->getDoctrine()->getRepository(Category::class);
        $categories = $repository->findAll();
        dump($categories);

        $form = $this->createFormBuilder($product)
            ->add('name', TextType::class)
            ->add('content', TextareaType::class)
            ->add('price', NumberType::class)
            ->add('category', ChoiceType::class, [
                'choices' => $categories,
                'choice_label' => 'name',
            ])
            ->add('add', SubmitType::class)
            ->getForm()
        ;

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $slugify = new Slugify();
            $product->setSlug($slugify->slugify($product->getName()));
            $product->setCreatedAt(new \DateTime());
            $this->getDoctrine()->getManager()->persist($product);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('product_view', ['slug' => $product->getSlug()]);
        }

        return $this->render('Product/createProduct.html.twig', [
            'productForm' => $form->createView(),
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
            $product->setPrice(random_int(1, 999) . '.99');
            $this->getDoctrine()->getManager()->persist($product);
        }

        $this->getDoctrine()->getManager()->flush();

        return $this->redirect('/');
    }
}