<?php

namespace App\Repository;

use App\Entity\Actors;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActorsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Actors::class);
    }
    /**
     * @return Actors[] 
     */
    public function findAllActorsOrderBy()
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.id','DESC')
            ->getQuery()
            ->getResult();        
    }
}
?>