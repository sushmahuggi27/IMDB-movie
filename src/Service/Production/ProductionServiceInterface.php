<?php

namespace App\Service\Production;

use App\Controller\ProductionController;
use App\Entity\Production;
use App\Service\Production\ProductionService;

Interface ProductionServiceInterface
{
    public function getAllProduction(): Array;
    public function getProduction($id): Production;
    public function addProduction(Production $production): Production;
    public function deleteProduction($id);
    public function updateProduction(Production $production): Production;
}
