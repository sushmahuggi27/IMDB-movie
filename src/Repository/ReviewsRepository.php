<?php

namespace App\Repository;

use App\Entity\Reviews;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Reviews|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reviews|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reviews[]    findAll()
 * @method Reviews[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReviewsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reviews::class);
    }

   /**
     * @return Reviews[]
     */
    public function findAllReviewInfo($id)
    {
        return $this->createQueryBuilder('r')
                    ->andWhere('r.movies = :id')
                    ->innerJoin('r.user','u')
                    ->innerJoin('r.movies','m')
                    ->addSelect('u','m')
                    ->setParameter('id',$id)
                    ->getQuery()
                    ->getResult();
    }  
}
