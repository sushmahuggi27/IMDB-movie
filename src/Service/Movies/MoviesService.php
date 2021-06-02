<?php

namespace App\Service\Movies;

use App\Entity\Movies;
use App\Controller\MoviesController;
use App\Service\Movies\MoviesServiceInterface;
use App\Repository\MoviesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Exception;

class MoviesService implements MoviesServiceInterface
{
    /**
     * @var MoviesRepository
     */
    private $moviesRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    function __construct(
        MoviesRepository $moviesRepository, 
        EntityManagerInterface $entityManager)
    {
        $this->moviesRepository = $moviesRepository;
        $this->entityManager = $entityManager;
    }

    public function getAllMovies(): Array 
    {
        return $this->moviesRepository->findAll();
    }

    public function getMovies($id): Movies
    {
        $movies = $this->moviesRepository->find($id);
        if($movies == null) {
            throw new Exception("Movies details with respect to the id does not exist");
        } else {
        return $movies;
        }
    }

    public function addMovies(Movies $movies): Movies
    {
        $this->entityManager->persist($movies);
        $this->entityManager->flush();
        $movies = $this->moviesRepository->findOneBy(["id" => $movies->getId()]);
        return $movies;
    }
    
    public function deleteMovies($id)
    {
        $movies = $this->moviesRepository->findOneBy(['id' =>$id]);
        $this->entityManager->remove($movies);
        $this->entityManager->flush();   
    }

    public function updateMovies(Movies $movies): Movies
    {
        $this->entityManager->persist($movies);
        $this->entityManager->flush();
        return $movies;
    }

    public function addAllMoviesDetails(Movies $movies): Movies
    {
        $this->entityManager->persist($movies);
        $this->entityManager->flush();
        $movies = $this->moviesRepository->findOneBy(["id" => $movies->getId()]);
        return $movies; 
    }
}

