<?php

namespace App\Service\Actors;

use App\Entity\Actors;
use App\Controller\ActorsController;
use App\Service\Actors\ActorsServiceInterface;
use App\Repository\ActorsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Exception;

class ActorsService implements ActorsServiceInterface
{
    /**
     * @var ActorsRepository
     */
    private $actorsRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    function __construct(
        ActorsRepository $actorsRepository, 
        EntityManagerInterface $entityManager)
    {
        $this->actorsRepository = $actorsRepository;
        $this->entityManager = $entityManager;
    }

    public function getAllActors(): Array 
    {
        return $this->actorsRepository->findAll();
    }

    public function getActors($id): Actors
    {
        $actors = $this->actorsRepository->find($id);
        if($actors == null) {
            throw new Exception("Actor details with respect to the id does not exist");
        }
        else {
            return $actors;
        }
    }

    public function addActors(Actors $actors): Actors
    {
        $this->entityManager->persist($actors);
        $this->entityManager->flush();
        $actors = $this->actorsRepository->findOneBy(["id" => $actors->getId()]);
        return $actors;
    }
    
    public function deleteActors($id)
    {
        $actor = $this->actorsRepository->findOneBy(['id' =>$id]);
        $this->entityManager->remove($actor);
        $this->entityManager->flush();   
    }

    public function updateActors(Actors $actors): Actors
    {
        $this->entityManager->persist($actors);
        $this->entityManager->flush();
        return $actors;
    }
}

