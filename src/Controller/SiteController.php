<?php


namespace App\Controller;


use App\Entity\Category;
use App\Entity\Product;
use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SiteController extends AbstractController
{
    private $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * @Route("/", name="home")
     */
    public function indexAction()
    {
//        $category = new Category();
//        $category->setName('glasses');
//
//        $product = new Product();
//        $product->setTitle('Ochki temnie otlichnie');
//        $product->setPrice(29.99);
//        $product->setDescription('otlichnie luchshie esge i temnie');
//        $product->setAvailability(1);
//        $product->setBrand('dg');
//        $product->setImage('image/product.jpg');
//        $product->setIsNew(1);
//       // $product->setIsRecommended(1);
//        //$product->setProductCode(1);
//        $product->generateCodeProduct();
//        $product->setStatus(1);
//
//        $product->setCategory_id($category);
//        $category->setStatus(1);
//        $category->setSortOrder(8);
//
//        $em = $this->getDoctrine()->getManager();
//        $em->persist($category);
//        $em->persist($product);
//        $em->flush();

//        return new Response('Saved'.$product->getId() );

        $categories = $this->productService->getCategory();
        $products = $this->productService->getLastProducts();
        $productsRecommended = $this->productService->getRecommendedProduct(5);

        return $this->render('index.html.twig', ['productsList' => $products,
                                        'categories' => $categories,
                                        'productsRecommended' => $productsRecommended
        ]);
    }
}