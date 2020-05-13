<?php


namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\ProductComment;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
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
        $product = new Product();
        $repository = $this->getDoctrine()->getRepository(Category::class);
        $categories = $repository->findAll();

        $form = $this->createFormBuilder($product)
            ->add('name', TextType::class)
            ->add('content', TextareaType::class)
            ->add('price', NumberType::class)
            ->add('category', ChoiceType::class, [
                'choices' => $categories,
                'choice_label' => 'name',
            ])
            ->add('add', SubmitType::class, [
                'label' => 'Ajouter'
            ])
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
            'pageTitle' => 'Créer un produit',
            'productForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/product/{id}/edit", name="product_edit")
     */
    public function editProduct(int $id, Request $request)
    {
        $repositoryCategory = $this->getDoctrine()->getRepository(Category::class);
        $categories = $repositoryCategory->findAll();
        $repositoryProduct = $this->getDoctrine()->getRepository(Product::class);
        $product = $repositoryProduct->find($id);

        $form = $this->createFormBuilder($product)
            ->add('name', TextType::class)
            ->add('content', TextareaType::class)
            ->add('price', NumberType::class)
            ->add('category', ChoiceType::class, [
                'choices' => $categories,
                'choice_label' => 'name',
            ])
            ->add('add', SubmitType::class, [
                'label' => 'Mettre à jour'
            ])
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
            'pageTitle' => 'Mise à jour du produit',
            'productForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/product/{id}/delete", name="product_delete")
     */
    public function deleteProduct(int $id, Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Product::class);
        $product = $repository->find($id);

        if ($product === null)
            throw $this->createNotFoundException("Le produit '$id' n'existe pas");

        $em = $this->getDoctrine()->getManager();
        $em->remove($product);
        $em->flush();

        return $this->redirectToRoute('product_list');
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
     * @Route("/product/comment/{id}/create", name="product_comment_create")
     */
    public function createComment(int $id, Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $repository = $this->getDoctrine()->getRepository(Product::class);
        $product = $repository->find($id);
        $comment = new ProductComment();
        $comment->setProduct($product);

        $form = $this->createFormBuilder($comment)
            ->add('content', TextareaType::class)
            ->add('add', SubmitType::class, [
                'label' => 'Publier'
            ])
            ->getForm()
        ;

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setCreatedAt(new \DateTime());
            $comment->setUser($this->getUser());
            $this->getDoctrine()->getManager()->persist($comment);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('product_view', ['slug' => $product->getSlug()]);
        }

        return $this->render('Product/createComment.html.twig', [
            'commentForm' => $form->createView(),
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