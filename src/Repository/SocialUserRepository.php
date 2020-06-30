<?php

namespace App\Repository;

use App\Entity\SocialUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SocialUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method SocialUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method SocialUser[]    findAll()
 * @method SocialUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SocialUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SocialUser::class);
    }

}
