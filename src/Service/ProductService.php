<?php


namespace App\Service;

use App\Entity\Category;
use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\FeedbackRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;


class ProductService
{
    private $productRepository;
    private $categoryRepository;
    private $feedbackRepository;
    private $paginator;

    const SHOW_BY_DEFAULT = 6;

    public function __construct(
        ProductRepository $productRepository,
        CategoryRepository $categoryRepository,
        FeedbackRepository $feedbackRepository,
        PaginatorInterface $paginator)
    {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
        $this->feedbackRepository = $feedbackRepository;
        $this->paginator = $paginator;
    }

    public function findLastProducts(int $count = self::SHOW_BY_DEFAULT): array
    {
        return $this->productRepository->findLastProducts($count);
    }

    public function findAllCategory(): array
    {
        return $this->categoryRepository->findAllCategory();
    }

    public function findRecommendedProduct(int $count = self::SHOW_BY_DEFAULT): array
    {
        return $this->productRepository->findRecommended($count);
    }

    public function findProductsPaginate(array $filters, int $page, int $limit): PaginationInterface
    {
        $qb = $this->productRepository->findProductsPaginate($filters);
        return $this->paginator->paginate($qb, $page, $limit);
    }

    public function getOneProduct(int $id): array
    {
        return $this->productRepository->findOne($id);
    }

    public function addNewProduct(array $data): Product
    {
        $product = new Product();
        $category = $this->categoryRepository->findOneBy(['sort_order' => $data['category']]);

        $product->setImage($data['image-path']);
        $product->setTitle($data['title']);
        $product->setPrice($data['price']);
        $product->setBrand($data['brand']);
        $product->setCategory_id($category);
        $product->setDescription($data['description']);
        $product->setProductCode(rand());
        $product->setNew($data['new']);
        $product->setRecommended($data['recommended']);
        $product->setAvailability($data['availability']);
        $product->setStatus($data['status']);

        return $this->productRepository->save($product);
    }

    public function addNewCategory(array $data)
    {
        $category = new Category();
        $lastSortOrder = $this->categoryRepository->findLastCategorySortOrder();

        $category->setName($data['category']);
        $category->setStatus($data['status-category']);
        $category->setSortOrder($lastSortOrder+1);

        return $this->productRepository->save($category);
    }

    public function deleteProduct(int $productCode): ?Product
    {
        $product = $this->productRepository->findOneBy(['product_code' => $productCode]);
        if ($product === null) {
            return null;
        }
        return $this->productRepository->delete($product);
    }

    public function changeStatusCategory($nameCategory): Category
    {
        $category = $this->categoryRepository->findOneBy(['name' => $nameCategory]);
        if ($category->getStatus() === 1) {
            $category->setStatus(0);
        } else {
            $category->setStatus(1);
        }
        $this->productRepository->update();

        return $category;
    }
}