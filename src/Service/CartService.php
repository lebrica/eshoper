<?php


namespace App\Service;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
    private $session;
    private $productRepository;

    public function __construct(SessionInterface $session, ProductRepository $productRepository)
    {
        $this->session = $session;
        $this->productRepository = $productRepository;
    }

    public function addProduct(int $id, int $count = 1): int
    {
        $productInCart = array();

        $arrProductInSession = $this->session->get('products');

        if (isset($arrProductInSession)) {
            $productInCart = $arrProductInSession;
        }
        if (array_key_exists($id, $productInCart)) {
            $productInCart[$id] += $count;
        } else {
            $productInCart[$id] = 1;
        }
        $this->session->set('products', $productInCart);

        return $this->countItem();
    }

    public function getProductInCart()
    {
        $arrProductInSession = $this->session->get('products');
        if (isset($arrProductInSession)) {
            return $arrProductInSession;
        }
        return false;
    }

    public function deleteProductInCart(int $id)
    {
        $arrProductInSession = $this->session->get('products');
        unset($arrProductInSession[$id]);
        $this->session->set('products', $arrProductInSession);
    }

    public function countItem(): int
    {
        $arrProductInSession = $this->session->get('products');

        if (isset($arrProductInSession)) {
            $count = 0;
            foreach ($arrProductInSession as $id => $quantity) {
                $count += $quantity;
            }
            return $count;
        } else {
            return 0;
        }
    }

    public function getTotalPrice(array $products): int
    {
        $productsInCart = $this->getProductInCart();
        $total = 0;

        if ($productsInCart) {
            foreach ($products as $product) {
                $qty = $productsInCart[$product->getId()];
                $productSum = $qty * $product ->getPrice();
                $total += $productSum;
            }
       }
        return $total;
    }

    public function getProductsByIds(array $ids): array
    {
        return $this->productRepository->findProductsByIds($ids);
    }
}