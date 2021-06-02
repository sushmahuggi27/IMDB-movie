<?php

namespace App\Controller\API;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Production\ProductionServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Production;
use App\Repository\ProductionRepository;
use Exception;
class ProductionController
{ 
  /**
  * @var ProductionServiceInterface
  */
  private $productionService;

  function __construct(ProductionServiceInterface $productionService)
  {
    $this->productionService = $productionService;
  }

  /**
   * @Route("/production/all", name = "get_all_pr", methods = {"GET"})
   */
  public function getAllProduction(): JsonResponse
  {
    try {
      $productions = $this->productionService->getAllProduction();
      $productionData = [];
      foreach ($productions as $production)
      {
        $productionData[] = $production->toArray();
      }
    } catch(\Exception $e) {
      return new JsonResponse(['status' => 'Display failed'], Response::HTTP_NOT_FOUND);
    }
    return new JsonResponse($productionData, Response::HTTP_OK);
  }

  /**
   * @Route("/production/{id}", name = "get_pr", methods = {"GET"})
   */
  public function getProduction($id): JsonResponse
  {
    try {
      $production = $this->productionService->getProduction($id);
    } catch (Exception $e) {
      return new JsonResponse([$e->getMessage()], Response::HTTP_CREATED);
    }
    if(empty($production))
    {
      throw new NotFoundHttpException('Production data not available');
    }
    $data = $production->toArray();
    return new JsonResponse($data);
  }

  /**
   * @Route("/production/add", name = "add_pr_route", methods = {"POST"})
   */
  public function addProduction(Request $request): JsonResponse
  {
    $production = new Production();
    $data = json_decode($request->getContent(), true);
    $production->setName($data['name']);
    $production->setCountry($data['country']);
    $production->setDescription($data['description']);
    try{
      $production = $this->productionService->addProduction($production);
    } catch(\Exception $e) {
      return new JsonResponse(['status' => 'Adding data failed'], Response::HTTP_OK);
    }
    return new JsonResponse(['status' => 'Production data saved'], Response::HTTP_OK);
  }

  /**
   * @Route("/production/delete/{id}", name = "delete_pr_route", methods = {"DELETE"})
   */
  public function deleteProduction($id): JsonResponse
  {
    try{
      $this->productionService->deleteProduction($id);
    } catch(\Exception $e) {
      return new JsonResponse(['status' => 'Deletion failed'], Response::HTTP_OK);
    }
    return new JsonResponse(['status' => 'Production data deleted'], Response::HTTP_OK);
  }

  /**
   * @Route("/production/update/{id}", name = "update_pr_route", methods = {"PUT"})
   */
  public function updateProduction(Request $request, $id): JsonResponse
  {
    try {
      $production = $this->productionService->getProduction($id);  
    } catch (Exception $e) {
      return new JsonResponse([$e->getMessage()], Response::HTTP_CREATED);
    }
    $data = json_decode($request->getContent(), true);
    empty($data['name']) ? true : $production->setName($data['name']);
    empty($data['country']) ? true : $production->setCountry($data['country']);
    empty($data['description']) ? true : $production->setDescription($data['description']);
    $production = $this->productionService->updateProduction($production);
    return new JsonResponse(['status' => 'Production data updated'], Response::HTTP_OK);
  }  
}
?>