<?php


namespace App\Controller;


use App\Entity\Category;
use App\Service\ProductService;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    private $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * @Route("/products", name="products")
     */
    public function actionIndex(Request $request, PaginatorInterface $paginator)
    {
        $categories = $this->productService->getCategory();
        $productQuery = $this->productService->getLatestPaginate();

        $products = $paginator->paginate($productQuery, $request->query->getInt('page', 1), 9);

        return $this->render('products.html.twig', ['categories' => $categories,
            'products' => $products
        ]);
    }

    /**
     * @Route("/products/{slug}", name="category_products")
     */
    public function categoryView(Request $request, PaginatorInterface $paginator, $slug)
    {
        $categories = $this->productService->getCategory();
        $productQuery = $this->productService->getOneCategoryPaginate($slug);

        $products = $paginator->paginate($productQuery, $request->query->getInt('page', 1), 3);

        return $this->render('products.html.twig', ['categories' => $categories,
            'products' => $products
        ]);
    }

    /**
     * @Route("/product{id}", name="one_product", requirements={"id"="\d+"})
     */
    public function productDetails($id)
    {
        $categories = $this->productService->getCategory();
        $product = $this->productService->getOneProduct($id);
        $productsRecommended = $this->productService->getRecommendedProduct(5);

        return $this->render('product.html.twig', ['categories' => $categories,
            'productDetails' => $product['0'],
            'productsRecommended' => $productsRecommended
        ]);
    }

}