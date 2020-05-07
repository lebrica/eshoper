<?php


namespace App\Service;


use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

class ProductService
{
    private $productRepository;
    private $categoryRepository;

    const SHOW_BY_DEFAULT = 6;

    public function __construct(ProductRepository $productRepository, CategoryRepository $categoryRepository)
    {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function getLastProducts(int $count = self::SHOW_BY_DEFAULT): array
    {
        return $this->productRepository->findLatest($count);
    }

    public function getCategory(): array
    {
        return $this->categoryRepository->all();
    }

    public function getRecommendedProduct(int $count = self::SHOW_BY_DEFAULT): array
    {
        return $this->productRepository->findRecommended($count);
    }

    public function getOneCategoryPaginate(string $category): object
    {
        return $this->productRepository->findOneCategoryProducts($category);
    }

    public function getLatestPaginate(): object
    {
        return $this->productRepository->findLatestPaginate();
    }

    public function getOneProduct(int $id): array
    {
        return $this->productRepository->findOne($id);
    }
}