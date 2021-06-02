<?php

namespace App\Repository;

use App\Entity\Movies;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MoviesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Movies::class);
    }

    /**
     * @return Movies[]
     */
    public function findAllMoviesInfo($id)
    {
        return $this->createQueryBuilder('m')
                    ->andWhere('m.id = :id')
                    ->innerJoin('m.actors','a')
                    ->innerJoin('m.director','d')
                    ->innerJoin('m.production','p')
                    ->innerJoin('m.media','md')
                    ->addSelect('a','d','p','md')
                    ->setParameter('id',$id)
                    ->getQuery()
                    ->getResult();
    }

    /**
     * @return Movies[] 
     */
    public function findAllMoviesOrderBy()
    {
        return $this->createQueryBuilder('m')
            ->orderBy('m.id','DESC')
            ->getQuery()
            ->getResult();        
    }

    public function findAllMovie($currentPage =1,$limit):Paginator
    {
     $qb= $this->createQueryBuilder('m')
               ->orderBy('m.id', 'DESC')
               ->innerJoin('m.director','d')
                ->innerJoin('m.actors','a')
                ->innerJoin('m.production','p')
                ->innerJoin('m.media','md')
                ->addSelect('d','a','p','md')
               ->getQuery();
   
               $paginator = $this->paginate($qb, $currentPage, $limit);

               return $paginator;

    }
    public function paginate($dql, $page = 1, $limit=1)
    {
       $paginator = new Paginator($dql);
       $paginator->setUseOutputWalkers(false); 
       $paginator->getQuery()
           ->setFirstResult($limit * ($page - 1)) // OffsetS
           ->setMaxResults($limit); // Limit
          

       return $paginator;
    }
}
?>