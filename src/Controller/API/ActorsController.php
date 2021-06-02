<?php

namespace App\Controller\API;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Actors\ActorsServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Actors;
use App\Repository\ActorsRepository;
use Exception;

class ActorsController
{ 
  /**
   * @var ActorsServiceInterface
   */
  private $actorsService;

  function __construct(ActorsServiceInterface $actorsService)
  {
    $this->actorsService = $actorsService;
  }

  /**
   * @Route("/actors/all", name = "get_all_act", methods = {"GET"})
   */
  public function getAllActors(): JsonResponse
  {
    $actor = $this->actorsService->getAllActors();
    $actorsData = [];
    foreach ($actor as $actors)
    {
      $actorsData[] = $actors->toArray();
    }
    return new JsonResponse($actorsData, Response::HTTP_OK);
  }

  /**
   * @Route("/actors/{id}", name = "get_act", methods = {"GET"})
   */
  public function getActors($id): JsonResponse
  {
    try {
      $actors = $this->actorsService->getActors($id);
    } catch (Exception $e) {
      return new JsonResponse([$e->getMessage()], Response::HTTP_CREATED);
    }
    if(empty($actors))
    {
      throw new NotFoundHttpException('Actors data not available');
    }
    $data = $actors->toArray();
    return new JsonResponse($data);
  }

  /**
   * @Route("/actors/add", name = "add_act_route", methods = "POST")
   */
  public function addActors(Request $request): JsonResponse
  {
    $actors = new Actors();
    $data = json_decode($request->getContent(), true);
    $actors->setName($data['name']);
    $actors->setGender($data['gender']);
    $actors->setEmail($data['email']);

    $profile = $data['profile'];
    $file = file_get_contents($profile);
    $fileName = basename($profile);
    $ex = pathinfo($fileName, PATHINFO_EXTENSION);
    $unique = md5(uniqid()).'.'.$ex;
    $save = "/var/www/html/imdb/public/ActorsImage/";
    $fullsave = $save.$unique;
    file_put_contents($fullsave, $file);
    $actors->setProfile($unique);
    try {
      $actors = $this->actorsService->addActors($actors);
    } catch(\Exception $e) {
      return new JsonResponse(['status' => 'Invalid data given. Adding failed'], Response::HTTP_OK);
    }
    return new JsonResponse(['status' => 'Actors data saved'], Response::HTTP_OK);
  }

  /**
   * @Route("/actors/delete/{id}", name = "delete_act_route", methods = {"DELETE"})
   */
  public function deleteActors($id): JsonResponse
  {
    try {
      $this->actorsService->deleteActors($id);
    } catch(\Exception $e) {
      return new JsonResponse(['status' => 'Id not found'], Response::HTTP_OK);
    }
    return new JsonResponse(['status' => 'Actors data deleted'], Response::HTTP_OK);
  }

  /**
   * @Route("/actors/update/{id}", name = "update_act_route", methods = {"PUT"})
   */
  public function updateActors(Request $request, $id): JsonResponse
  {
    try {
      $actors = $this->actorsService->getActors($id);
    } catch (Exception $e) {
      return new JsonResponse([$e->getMessage()], Response::HTTP_CREATED);
    }
    
    $data = json_decode($request->getContent(), true);
    empty($data['name']) ? true : $actors->setName($data['name']);
    empty($data['gender']) ? true : $actors->setGender($data['gender']);
    empty($data['email']) ? true : $actors->setEmail($data['email']);
    if(!empty($data['profile']))
    {
      $profile = $data['profile'];
      $file = file_get_contents($profile);
      $fileName = basename($profile);
      $ex = pathinfo($fileName, PATHINFO_EXTENSION);
      $unique = md5(uniqid()).'.'.$ex;
      $save = "/var/www/html/imdb/public/ActorsImage/";
      $fullsave = $save.$unique;
      file_put_contents($fullsave, $file);
      $actors->setProfile($unique);
    }
    $actors = $this->actorsService->updateActors($actors);
    return new JsonResponse(['status' => 'Actors data updated'], Response::HTTP_OK);
  }  
}
?>