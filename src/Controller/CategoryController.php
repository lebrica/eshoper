<?php


namespace App\Controller;


use App\Entity\Category;
use App\Entity\Product;
use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    private $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * @return Response
     * @Route("/login", name="login")
     */
    public function indexView()
    {
        return $this->render('login.html.twig');
    }
}