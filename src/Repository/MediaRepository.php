<?php

namespace App\Repository;

use App\Entity\Media;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MediaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Media::class);
    }
    /**
     * @return Media[] 
     */
    public function findAllMediaOrderBy()
    {
        return $this->createQueryBuilder('md')
            ->orderBy('md.id','DESC')
            ->getQuery()
            ->getResult();        
    }
}
?>