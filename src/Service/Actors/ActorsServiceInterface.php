<?php

namespace App\Service\Actors;

use App\Controller\ActorsController;
use App\Entity\Actors;
use App\Service\Actors\ActorsService;

Interface ActorsServiceInterface
{
    public function getAllActors(): Array;
    public function getActors($id): Actors;
    public function addActors(Actors $actors): Actors;
    public function deleteActors($id);
    public function updateActors(Actors $actors): Actors;
}