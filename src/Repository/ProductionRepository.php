<?php

namespace App\Repository;

use App\Entity\Production;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Production::class);
    }

    /**
     * @return Production[] 
     */
    public function findAllProductionOrderBy()
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.id','DESC')
            ->getQuery()
            ->getResult();        
    }
}
?>