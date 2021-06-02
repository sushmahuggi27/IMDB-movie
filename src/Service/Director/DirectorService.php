<?php

namespace App\Service\Director;

use App\Entity\Director;
use App\Controller\DirectorController;
use App\Service\Director\DirectorServiceInterface;
use App\Repository\DirectorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Exception;

class DirectorService implements DirectorServiceInterface
{
    /**
     * @var DirectorRepository
     */
    private $directorRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    function __construct(
        DirectorRepository $directorRepository, 
        EntityManagerInterface $entityManager)
    {
        $this->directorRepository = $directorRepository;
        $this->entityManager = $entityManager;
    }

    public function getAllDirector(): Array 
    {
        return $this->directorRepository->findAll();
    }

    public function getDirector($id):Director
    {
        $director = $this->directorRepository->find($id);
        if($director == null) {
            throw new Exception("Director details with respect to the id does not exist");
        }
        else {
            return $director;
        }
    }

    public function addDirector(Director $director): Director
    {
        try {
            $this->entityManager->persist($director);
            $this->entityManager->flush();
            $director = $this->directorRepository->findOneBy(["id"=>$director->getId()]);
            return $director;
        } catch(Exception $e) {
            throw new Exception("The name you entered already exists. Enter Unique name.");
        }
    }
    
    public function deleteDirector($id)
    {
        $directors = $this->directorRepository->findOneBy(['id' =>$id]);
        $this->entityManager->remove($directors);
        $this->entityManager->flush();   
    }

    public function updateDirector(Director $director): Director
    {
        $this->entityManager->persist($director);
        $this->entityManager->flush();
        return $director;
    }
}

