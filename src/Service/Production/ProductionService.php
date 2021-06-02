<?php

namespace App\Service\Production;

use App\Entity\Production;
use App\Controller\ProductionController;
use App\Service\Production\ProductionServiceInterface;
use App\Repository\ProductionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Exception;

class ProductionService implements ProductionServiceInterface
{
    /**
     * @var ProductionRepository
     */
    private $productionRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    function __construct(
        ProductionRepository $productionRepository, 
        EntityManagerInterface $entityManager)
    {
        $this->productionRepository = $productionRepository;
        $this->entityManager = $entityManager;
    }

    public function getAllProduction(): Array 
    {
        return $this->productionRepository->findAll();
    }

    public function getProduction($id): Production
    {
        $production = $this->productionRepository->find($id);
        if($production == null) {
            throw new Exception("Production details with respect to the id does not exist");
        } else {
        return $production;
        }
    }

    public function addProduction(Production $production): Production
    {
        $this->entityManager->persist($production);
        $this->entityManager->flush();
        $productions = $this->productionRepository->findOneBy(["id" => $production->getId()]);
        return $productions;
    }
    
    public function deleteProduction($id)
    {
        $productions = $this->productionRepository->findOneBy(['id' =>$id]);
        $this->entityManager->remove($productions);
        $this->entityManager->flush();   
    }

    public function updateProduction(Production $production): Production
    {
        $this->entityManager->persist($production);
        $this->entityManager->flush();
        return $production;
    }
}

