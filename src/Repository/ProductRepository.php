<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findOne($id)
    {
        return $this->createQueryBuilder('product')
            ->where('product.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->execute()
            ;
    }

    public function findLatestPaginate()
    {
        return $this->createQueryBuilder('product')
            ->where('product.status = 1')
            ->orderBy('product.id', 'DESC')
            ->getQuery()
        ;
    }

    public function findOneCategoryProducts(string $category)
    {
        return $this->createQueryBuilder('product')
            ->where('product.status = 1')
            ->join('product.category', 'category')
            ->andWhere("category.name = :name")
            ->setParameter('name', $category)
            ->getQuery()
            ;
    }

    public function findRecommended($count)
    {
        return $this->createQueryBuilder('product')
            ->where('product.status = 1')
            ->andWhere('product.recommended = 1')
            ->orderBy('product.id', 'DESC')
            ->setMaxResults($count)
            ->getQuery()
            ->execute()
            ;
    }

    public function findLatest($count)
    {
        return $this->createQueryBuilder('product')
            ->where('product.status = 1')
            ->orderBy('product.id', 'DESC')
            ->setMaxResults($count)
            ->getQuery()
            ->execute()
            ;
    }

}
