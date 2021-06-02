<?php

namespace App\Controller\API;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Director\DirectorServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Director;
use App\Repository\DirectorRepository;
use Exception;

class DirectorController
{ 
  /**
   * @var DirectorServiceInterface
   */
  private $directorService;

  function __construct(DirectorServiceInterface $directorService)
  {
    $this->directorService = $directorService;
  }

  /**
   * @Route("/director/all", name = "get_all_dir", methods = {"GET"})
   */
  public function getAllDirector(): JsonResponse
  {
    $directors = $this->directorService->getAllDirector();
    $directorData = [];
    foreach ($directors as $director)
    {
      $directorData[] = $director->toArray();
    }
    return new JsonResponse($directorData, Response::HTTP_OK);
  }

  /**
   * @Route("/director/{id}", name = "get_dir", methods = {"GET"})
   */
  public function getDirector($id): JsonResponse
  {
    try {
      $director = $this->directorService->getDirector($id);
    } catch (Exception $e) {
      return new JsonResponse([$e->getMessage()], Response::HTTP_CREATED);
    }
    if(empty($director))
    {
      throw new NotFoundHttpException('Directors data not available');
    }
    $data = $director->toArray();
    return new JsonResponse($data);
  }

  /**
   * @Route("/director/add", name = "add_dir_route", methods = "POST")
   */
  public function addDirector(Request $request): JsonResponse
  {
    $director = new Director();
    $data = json_decode($request->getContent(), true);
    $director->setName($data['name']);
    $director->setCountry($data['country']);
    $director->setEmail($data['email']);

    $profile = $data['profile'];
    $file = file_get_contents($profile);
    $fileName = basename($profile);
    $ex = pathinfo($fileName, PATHINFO_EXTENSION);
    $unique = md5(uniqid()).'.'.$ex;
    $save = "/var/www/html/imdb/public/DirectorImage/";
    $fullsave = $save.$unique;
    file_put_contents($fullsave, $file);
    $director->setProfile($unique);
    if(empty($director) )
    {
      throw new NotFoundHttpException('Expecting mandatory parameters!');
    }
    try {
      $director = $this->directorService->addDirector($director);
      return new JsonResponse(['status' => 'Directors data added sucessfully','id'=>$director->getId()], Response::HTTP_CREATED);
    } catch(Exception $e) {
      return new JsonResponse($e->getMessage(), Response::HTTP_CREATED);
    }
  }

  /**
   * @Route("/director/delete/{id}", name = "delete_dir_route", methods = {"DELETE"})
   */
  public function deleteDirector($id): JsonResponse
  {
    try{
      $this->directorService->deleteDirector($id);
    } catch(\Exception $e) {
      return new JsonResponse(['status' => 'Invalid ID'], Response::HTTP_OK);
    }
    return new JsonResponse(['status' => 'Director data deleted'], Response::HTTP_OK);
  }

  /**
   * @Route("/director/update/{id}", name = "update_dir_route", methods = {"PUT"})
   */
  public function updateDirector(Request $request, $id):JsonResponse
  {
    try {
      $director = $this->directorService->getDirector($id);
    } catch (Exception $e) {
      return new JsonResponse([$e->getMessage()], Response::HTTP_CREATED);
    }
    $data = json_decode($request->getContent(), true);
    empty($data['name']) ? true : $director->setName($data['name']);
    empty($data['country']) ? true : $director->setCountry($data['country']);
    empty($data['email']) ? true : $director->setEmail($data['email']);
    if(!empty($data['profile']))
    {
      $profile = $data['profile'];
      $file = file_get_contents($profile);
      $fileName = basename($profile);
      $ex = pathinfo($fileName, PATHINFO_EXTENSION);
      $unique = md5(uniqid()).'.'.$ex;
      $save = "/var/www/html/imdb/public/DirectorImage/";
      $fullsave = $save.$unique;
      file_put_contents($fullsave, $file);
      $director->setProfile($unique);
    }
    $director = $this->directorService->updateDirector($director);
    return new JsonResponse(['status' => 'director data updated'], Response::HTTP_OK);
  }
}
?>