<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
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

    public function save($object)
    {
        $this->_em->persist($object);
        $this->_em->flush();

        return $object;
    }

    public function update()
    {
        $this->_em->flush();
    }

    public function delete($object)
    {
        $this->_em->remove($object);
        $this->_em->flush();

        return $object;
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

    public function findLastProducts($count)
    {
        return $this->createQueryBuilder('product')
            ->where('product.status = 1')
            ->orderBy('product.id', 'DESC')
            ->setMaxResults($count)
            ->getQuery()
            ->execute()
            ;
    }

    public function findProductsByIds(array $ids)
    {
        return $this->createQueryBuilder('product')
            ->where('product.status = 1')
            ->andWhere('product.id IN (:ids)')
            ->setParameter('ids', $ids)
            ->getQuery()
            ->execute()
            ;
    }

    public function findProductsPaginate(array $filter): QueryBuilder
    {
        $qb = $this->createQueryBuilder('product')
            ->where('product.status = 1');
            if (isset($filter['category'])) {
                $qb
                    ->join('product.category', 'category')
                    ->where('category.name = :category')
                    ->setParameter('category', $filter['category'])
                ;
            }
             if (isset($filter['amount1'], $filter['amount2'])) {
                $qb
                     ->andWhere('product.price BETWEEN :price1 AND :price2')
                     ->setParameter('price1', $filter['amount1'])
                     ->setParameter('price2', $filter['amount2'])
                ;
        }
         return $qb;
    }

    public function findProductByTitlePaginate($title): QueryBuilder
    {
        return $this->createQueryBuilder('product')
            ->orderBy('product.id', 'DESC')
            ->where('product.status = 1')
            ->where("product.title LIKE :title")
            ->setParameter('title', '%'.$title.'%')
            ;
    }
}
