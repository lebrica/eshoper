<?php


namespace App\Service;

use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\ProductRepository;

class SearchService
{
    private $productRepository;
    private $paginator;

    public function __construct(ProductRepository $productRepository, PaginatorInterface $paginator)
    {
        $this->productRepository = $productRepository;
        $this->paginator = $paginator;
    }

    public function findProductByTitlePaginate(string $title, int $page, int $limit): PaginationInterface
    {
        $qb = $this->productRepository->findProductByTitlePaginate($title);
        return $this->paginator->paginate($qb, $page, $limit);
    }
}