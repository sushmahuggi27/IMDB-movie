<?php

namespace App\Controller\API;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Movies\MoviesServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Exception;
use App\Entity\Movies;
use App\Repository\MoviesRepository;
use App\Entity\Actors;
use App\Service\Actors\ActorsServiceInterface;
use App\Entity\Director;
use App\Service\Director\DirectorServiceInterface;
use App\Service\Media\MediaServiceInterface;
use App\Service\Production\ProductionServiceInterface;


class MoviesController
{ 
  /**
   * @var MoviesServiceInterface
   */
  private $moviesService;

  function __construct(MoviesServiceInterface $moviesService, 
                      ActorsServiceInterface $actorsService, 
                      DirectorServiceInterface $directorService, 
                      MediaServiceInterface $mediaService, 
                      ProductionServiceInterface $productionService)
  {
    $this->moviesService = $moviesService;
    $this->actorsService = $actorsService;
    $this->directorService = $directorService;
    $this->mediaService = $mediaService;
    $this->moviesService = $moviesService;
  }

  /**
   * @Route("/movies/all", name = "get_all_mov", methods = {"GET"})
   */
  public function getAllMovies(): JsonResponse
  {
    $movie = $this->moviesService->getAllMovies();
    $moviesData = [];
    foreach ($movie as $movies)
    {
      $moviesData[] = $movies->toArray();
    }
    return new JsonResponse($moviesData, Response::HTTP_OK);
  }

  /**
   * @Route("/movies/{id}", name = "get_mov", methods = {"GET"})
   */
  public function getMovies($id): JsonResponse
  {
    try {
      $movies = $this->moviesService->getMovies($id);
    } catch (Exception $e) {
      return new JsonResponse([$e->getMessage()], Response::HTTP_CREATED);
    }
    if(empty($movies))
    {
      throw new NotFoundHttpException('Movies data not available');
    }
    $data = $movies->toArray();
    return new JsonResponse($data);
  }

  /**
   * @Route("/movies/add", name = "add_mov_route", methods = "POST")
   */
  public function addMovies(Request $request): JsonResponse
  {
    $movies = new Movies();
    $data = json_decode($request->getContent(), true);
    $movies->setTitle($data['title']);
    $movies->setRelDate($data['relDate']);
    $movies->setLanguage($data['language']);
    $movies->setType($data['type']);
    $movies->setDescription($data['description']);
    $poster = $data['poster'];
    $file = file_get_contents($poster);
    $fileName = basename($poster);
    $ex = pathinfo($fileName, PATHINFO_EXTENSION);
    $unique = md5(uniqid()).'.'.$ex;
    $save = "/var/www/html/imdb/public/MoviesImage/";
    $fullsave = $save.$unique;
    file_put_contents($fullsave, $file);
    $movies->setposter($unique);
    try{
      $movies = $this->moviesService->addMovies($movies);
    } catch(\Exception $e) {
      return new JsonResponse(['status' => 'Adding movies failed'], Response::HTTP_OK);
    }
    return new JsonResponse(['status' => 'Movies data saved'], Response::HTTP_OK);
  }

  /**
   * @Route("/movies/delete/{id}", name = "delete_mov_route", methods = {"DELETE"})
   */
  public function deleteMovies($id): JsonResponse
  {
    try{
      $this->moviesService->deleteMovies($id);
    } catch(\Exception $e) {
      return new JsonResponse(['status' => 'Deletion failed'], Response::HTTP_OK);
    }
    return new JsonResponse(['status' => 'Movies data deleted'], Response::HTTP_OK);
  }

  /**
   * @Route("/movies/update/{id}", name = "update_mov_route", methods = {"PUT"})
   */
  public function updateMovies(Request $request, $id): JsonResponse
  {
    try {
      $movies = $this->moviesService->getMovies($id);
    } catch (Exception $e) {
      return new JsonResponse([$e->getMessage()], Response::HTTP_CREATED);
    }
    $data = json_decode($request->getContent(), true);
    empty($data['title']) ? true : $movies->setTitle($data['title']);
    empty($data['relDate']) ? true : $movies->setRelDate($data['relDate']);
    empty($data['language']) ? true : $movies->setLanguage($data['language']);
    empty($data['type']) ? true : $movies->setType($data['type']);
    empty($data['description']) ? true : $movies->setDescription($data['description']);

    if(!empty($data['poster']))
    {
      $poster = $data['poster'];
      $file = file_get_contents($poster);
      $fileName = basename($poster);
      $ex = pathinfo($fileName, PATHINFO_EXTENSION);
      $unique = md5(uniqid()).'.'.$ex;
      $save = "/var/www/html/imdb/public/MoviesImage/";
      $fullsave = $save.$unique;
      file_put_contents($fullsave, $file);
      $movies->setPoster($unique);
    }
    $movies = $this->moviesService->updateMovies($movies);
    return new JsonResponse(['status' => 'Movies data updated'], Response::HTTP_OK);
  }

  /**
   * @Route("/movies/addall/{id}", name = "add_all_det", methods = {"POST"})
   */
  public function addAllMoviesDetails(Request $request, $id): JsonResponse
  {
    $movies = $this->moviesService->getMovies($id);
    $data = json_decode($request->getContent(), true);
    foreach($data['actors'] as $ac)
    {
      $act = $ac['id'];
      $actors = $this->actorsService->getActors($act);
      $movies->addActors($actors);
      $movies = $this->moviesService->addMovies($movies);
    }
    foreach($data['director'] as $di)
    {
      $dir = $di['id'];
      $director = $this->directorService->getDirector($dir);
      $movies->addDirector($director);
      $movies = $this->moviesService->addMovies($movies);
    }
    foreach($data['media'] as $me)
    {
      $med = $me['id'];
      $media = $this->mediaService->getMedia($med);
      $movies->addMedia($media);
      $movies = $this->moviesService->addMovies($movies);
    }
    foreach($data['movies'] as $pr)
    {
      $pro = $pr['id'];
      $movies = $this->moviesService->getmovies($pro);
      $movies->addmovies($movies);
      $movies = $this->moviesService->addMovies($movies);
    }
    $movies = $this->moviesService->addAllMoviesDetails($movies);
    return new JsonResponse(['status' => 'Movies all data added'], Response::HTTP_OK);
  } 
}
?>