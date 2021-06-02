<?php

namespace App\Repository;

use App\Entity\Review;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Review::class);
    }

     /**
     * @return Review[]
     */
    public function findAllReviewInfo($id)
    {
        return $this->createQueryBuilder('r')
                    ->andWhere('r.movies = :id')
                    ->innerJoin('r.user','u')
                    ->addSelect('u')
                    ->setParameter('id',$id)
                    ->getQuery()
                    ->getResult();
    }  
}
?>