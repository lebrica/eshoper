<?php


namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Service\CartService;
use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function indexAction(Request $request)
    {
        $products = $this->productService->findLastProducts();
        $productsRecommended = $this->productService->findRecommendedProduct(5);

        return $this->render('index.html.twig', ['productsList' => $products,
                                        'productsRecommended' => $productsRecommended,
        ]);
    }
}