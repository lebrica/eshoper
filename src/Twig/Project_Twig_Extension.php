<?php


namespace App\Twig;

use App\Service\CartService;
use App\Service\ProductService;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class Project_Twig_Extension extends AbstractExtension implements GlobalsInterface
{
    private $cartService;
    private $productService;

    public function __construct(CartService $cartService, ProductService $productService)
    {
        $this->productService = $productService;
        $this->cartService = $cartService;
    }

    public function getGlobals(): array
    {
        $categories = $this->productService->findAllCategory();
        $countInCart = $this->cartService->countItem();
        return [
            'categories' => $categories,
            'count_in_cart' => $countInCart,
        ];
    }
}