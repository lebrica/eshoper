<?php

namespace App\Repository;

use App\Entity\Feedback;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Feedback|null find($id, $lockMode = null, $lockVersion = null)
 * @method Feedback|null findOneBy(array $criteria, array $orderBy = null)
 * @method Feedback[]    findAll()
 * @method Feedback[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FeedbackRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Feedback::class);
    }

    public function findLastComment($id, $count)
    {
        return $this->createQueryBuilder('feedback')
            ->join('feedback.product', 'product')
            ->join('feedback.user', 'user')
            ->where('product.id = :id')
            ->select('user.name', 'feedback.date', 'feedback.rating', 'feedback.comments', 'product.id')
            ->setParameter('id', $id)
            ->orderBy('feedback.date', 'DESC')
            ->setMaxResults($count)
            ->getQuery()
            ->execute()
            ;
    }

    public function findCommentsOneProduct($id)
    {
        return $this->createQueryBuilder('feedback')
            ->select('count(feedback.product)')
            ->where('feedback.product = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    public function findAvgRatingOneProduct($id)
    {
        return $this->createQueryBuilder('feedback')
            ->select('avg(feedback.rating)')
            ->where('feedback.product = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }
}
