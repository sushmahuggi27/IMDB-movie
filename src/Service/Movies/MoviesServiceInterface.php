<?php

namespace App\Service\Movies;

use App\Controller\MoviesController;
use App\Entity\Movies;
use App\Service\Movies\MoviesService;

Interface MoviesServiceInterface
{
    public function getAllMovies(): Array;
    public function getMovies($id): Movies;
    public function addMovies(Movies $movies): Movies;
    public function deleteMovies($id);
    public function updateMovies(Movies $movies): Movies;
    public function addAllMoviesDetails(Movies $movies): Movies;
}
