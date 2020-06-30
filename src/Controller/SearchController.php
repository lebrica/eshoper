<?php


namespace App\Controller;

use App\Service\SearchService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    /**
     * @Route("/product/search", name="search_product")
     */
    public function actionIndex(Request $request, SearchService $searchService)
    {
        $title = $request->query->get('search');

        $products = $searchService->findProductByTitlePaginate($title, $request->query->getInt('page', 1), 6);

        return $this->render('products.html.twig', ['products' => $products]);
    }
}