<?php

namespace App\Service\Director;

use App\Controller\DirectorController;
use App\Entity\Director;
use App\Service\Director\DirectorService;

Interface DirectorServiceInterface
{
    public function getAllDirector(): Array;
    public function getDirector($id): Director;
    public function addDirector(Director $director): Director;
    public function deleteDirector($id);
    public function updateDirector(Director $director): Director;
}
