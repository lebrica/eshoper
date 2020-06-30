<?php


namespace App\Controller;

use App\Service\FeedbackService;
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
    private $feedbackService;

    public function __construct(ProductService $productService, FeedbackService $feedbackService)
    {
        $this->productService = $productService;
        $this->feedbackService = $feedbackService;
    }

    /**
     * @Route("/products", name="products")
     */
    public function actionIndex(Request $request, PaginatorInterface $paginator)
    {
        $filters = $this->getFilters($request);

        $products = $this->productService->findProductsPaginate($filters, $request->query->getInt('page', 1), 9);

        return $this->render('products.html.twig', ['products' => $products]);
    }

    /**
     * @Route("/products/{category}", name="category_products")
     */
    public function categoryView(Request $request, PaginatorInterface $paginator, $category)
    {
        $filters = $this->getFilters($request, $category);

        $products = $this->productService->findProductsPaginate($filters, $request->query->getInt('page', 1), 9);

        return $this->render('products.html.twig', ['products' => $products]);
    }

    /**
     * @Route("/product/{id}", name="one_product", requirements={"id"="\d+"})
     */
    public function productDetails($id)
    {
        $product = $this->productService->getOneProduct($id);
        $productsRecommended = $this->productService->findRecommendedProduct(5);
        $productFeedback = $this->feedbackService->findLastComments($id);
        $avgRating = round($this->feedbackService->findAvgRatingProduct($id));
        $countComments = $this->feedbackService->findCommentsOneProduct($id);

        return $this->render('product.html.twig', [
            'countComments' => $countComments,
            'avgRating' => $avgRating,
            'productFeedback' => $productFeedback,
            'productDetails' => $product['0'],
            'productsRecommended' => $productsRecommended,
        ]);
    }

    /**
     * @Route("/product/comments/{id}", name="product_comments", requirements={"id"="\d+"})
     */
    public function allCommentsProduct($id)
    {
        $productFeedback = $this->feedbackService->findLastComments($id, 30);
        $avgRating = round($this->feedbackService->findAvgRatingProduct($id));
        $countComments = $this->feedbackService->findCommentsOneProduct($id);

        return $this->render('comments_product.html.twig', [
            'countComments' => $countComments,
            'avgRating' => $avgRating,
            'productFeedback' => $productFeedback,
        ]);
    }

    private function getFilters($request, $category = null)
    {
        if (!$request->query->get('submit_price')) {
            return ['category' => $category];
        }
        return [
            'category' => $category,
            'amount1' => $request->query->get('amount1'),
            'amount2' => $request->query->get('amount2'),
        ];
    }
}