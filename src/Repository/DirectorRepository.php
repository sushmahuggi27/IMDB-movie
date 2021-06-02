<?php

namespace App\Repository;

use App\Entity\Director;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DirectorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Director::class);
    }
    /**
     * @return Director[] 
     */
    public function findAllDirectorOrderBy()
    {
        return $this->createQueryBuilder('d')
            ->orderBy('d.id','DESC')
            ->getQuery()
            ->getResult();        
    }
}
?>