<?php

namespace App\Repository;

use App\Entity\Userlogs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Userlogs|null find($id, $lockMode = null, $lockVersion = null)
 * @method Userlogs|null findOneBy(array $criteria, array $orderBy = null)
 * @method Userlogs[]    findAll()
 * @method Userlogs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserlogsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Userlogs::class);
    }  
}