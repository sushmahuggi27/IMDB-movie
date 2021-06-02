<?php

namespace App\Controller\API;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Media\MediaServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Media;
use App\Repository\MediaRepository;
use Exception;

class MediaController
{ 
  /**
   * @var MediaServiceInterface
   */
  private $mediaService;

  function __construct(MediaServiceInterface $mediaService)
  {
    $this->mediaService = $mediaService;
  }

  /**
   * @Route("/media/all", name = "get_all_med", methods = {"GET"})
   */
  public function getAllMedia(): JsonResponse
  {
    $medias = $this->mediaService->getAllMedia();
    $mediaData = [];
    foreach ($medias as $media)
    {
      $mediaData[] = $media->toArray();
    }
    return new JsonResponse($mediaData, Response::HTTP_OK);
  }

  /**
   * @Route("/media/{id}", name = "get_med", methods = {"GET"})
   */
  public function getMedia($id): JsonResponse
  {
    try {
      $media = $this->mediaService->getMedia($id);
    } catch (Exception $e) {
      return new JsonResponse([$e->getMessage()], Response::HTTP_CREATED);
    }
    if(empty($media))
    {
      throw new NotFoundHttpException('Media data not available');
    }
    $data = $media->toArray();
    return new JsonResponse($data);
  }

  /**
   * @Route("/media/add", name = "add_med_route", methods = "POST")
   */
  public function addMedia(Request $request): JsonResponse
  {
    $media = new Media();
    $data = json_decode($request->getContent(), true);
    $media->setVedio($data['vedio']);
    $image = $data['image'];
    $file = file_get_contents($image);
    $fileName = basename($image);
    $ex = pathinfo($fileName, PATHINFO_EXTENSION);
    $unique = md5(uniqid()).'.'.$ex;
    $save = "/var/www/html/imdb/public/MediaImage/";
    $fullsave = $save.$unique;
    file_put_contents($fullsave, $file);
    $media->setimage($unique);
    try{
      $media = $this->mediaService->addMedia($media);
    } catch(\Exception $e) {
      return new JsonResponse(['status' => 'Adding media failed'], Response::HTTP_OK);
    }
    return new JsonResponse(['status' => 'Media data saved'], Response::HTTP_OK);
  }

  /**
   * @Route("/media/delete/{id}", name = "delete_med_route", methods = {"DELETE"})
   */
  public function deleteMedia($id): JsonResponse
  {
    try{
      $this->mediaService->deleteMedia($id);
    } catch(\Exception $e) {
      return new JsonResponse(['status' => 'Deletion failed'], Response::HTTP_OK);
    }
    return new JsonResponse(['status' => 'Media data deleted'], Response::HTTP_OK);
  }

  /**
   * @Route("/media/update/{id}", name = "update_med_route", methods = {"PUT"})
   */
  public function updateMedia(Request $request, $id): JsonResponse
  {
    try {
      $media = $this->mediaService->getMedia($id);
    } catch (Exception $e) {
      return new JsonResponse([$e->getMessage()], Response::HTTP_CREATED);
    }
    
    $data = json_decode($request->getContent(), true);
    empty($data['vedio']) ? true : $media->setVedio($data['vedio']);
    if(!empty($data['image']))
    {
      $image = $data['image'];
      $file = file_get_contents($image);
      $fileName = basename($image);
      $ex = pathinfo($fileName, PATHINFO_EXTENSION);
      $unique = md5(uniqid()).'.'.$ex;
      $save = "/var/www/html/imdb/public/MediaImage/";
      $fullsave = $save.$unique;
      file_put_contents($fullsave, $file);
      $media->setimage($unique);
    }
    $media = $this->mediaService->updateMedia($media);
    return new JsonResponse(['status' => 'Media data updated'], Response::HTTP_OK);
  }  
}
?>