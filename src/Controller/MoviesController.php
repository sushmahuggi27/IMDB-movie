<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use App\Form\Type\AddExistingType;
use App\Form\Type\ActorsType;
use App\Form\Type\DirectorType;
use App\Form\Type\ProductionType;
use App\Form\Type\MediaType;
use App\Entity\Actors;
use App\Entity\Movies;
use App\Entity\Director;
use App\Entity\Production;
use App\Entity\Media;
use App\Entity\Review;
use App\Entity\ReviewType;

class MoviesController extends AbstractController
{
  /**
   * @Route("/",name="home")
   */
  public function home():Response
  {
    return $this->render('include/header.html.twig');  
  }

  /**
   * @Route("/addexisting",name="add_new")
   */
  public function addExtingAction(Request $request):Response
  {
    $em = $this->getDoctrine()->getManager();
    $form =$this->createForm(AddExistingType::class);
    $form->handleRequest($request);     
    if ($form->isSubmitted() && $form->isValid())
    {
      $file = $form->get('poster')->getData();       
      $title = $form->get('title')->getData();
      $relDate = $form->get('relDate')->getData();
      $type = $form->get('type')->getData();
      $description = $form->get('description')->getData();
      $language = $form->get('language')->getData();
      $movie = new Movies();
      $fileName = md5(uniqid()).'.'.$file->guessExtension();
      $file->move( $this->getParameter('moviePoster'), $fileName);
      $movie->setPoster($fileName);
      $movie->setDescription($description);
      $movie->setTitle($title);
      $movie->setLanguage($language);
      $movie->setRelDate($relDate);
      $movie->setType($type);

      $data = $form->get('actors')->getData();
      $m = $data->getId();
      $actors = $em->getRepository(Actors::class)->find($m);
      $movie->addActors($actors);

      $data = $form->get('director')->getData();
      $m =$data->getId();
      $director= $em->getRepository(Director::class)->find($m);
      $movie->addDirector($director);

      $data = $form->get('production')->getData();
      $m =$data->getId();
      $production= $em->getRepository(Production::class)->find($m);
      $movie->addProduction($production);

      $data = $form->get('media')->getData();
      $m =$data->getId();
      $media= $em->getRepository(Media::class)->find($m);
      $movie->addMedia($media);
      $em->persist($movie);
      $em->flush();
      $this->addFlash('message','movie data saved sucessfully ');
      return  $this->redirectToRoute('show_movie_route'); 
    }
    return $this->render('Add.html.twig',['form'=>$form->createView()]);
  }  

  /**
   * @Route("/editmovies", name="view_editmovies_route")
   */
  public function editMovies():Response
  {
    $em = $this->getDoctrine()->getManager();
    $movies = $em->getRepository(Movies::class)->findAllMoviesOrderBy();
    return $this->render('ViewMovies.html.twig',['movies'=>$movies]);  
  }

  /**
  *@Route("/viewmore/{id}",name = "view_more")
  */
  public function viewMore(Request $request ,$id):Response
  {
    $em = $this->getDoctrine()->getManager();
    $movies = $em->getRepository(Movies::class)->findAllMoviesInfo($id);
    return $this->render('ViewFullMovie.html.twig',['movies'=>$movies]); 
  }

  /**
  * @Route("/updatemovies/{id}",name="update_movies_route")
  */
  public function updateMovies(Request $request, $id):Response
  {
    $em = $this->getDoctrine()->getManager();
    $movies = $em->getRepository(Movies::class)->find($id);
    //dd($movies);
    $form =$this->createForm(AddExistingType::class,$movies);
    $oldFile = $movies->getPoster();
    $oldPath = $this->getParameter('moviePoster').'/'.$movies->getPoster();
    $profileFile = new File($oldPath);
    $movies->setPoster($profileFile );    
    $form->handleRequest($request);
    if ($form->isSubmitted()&& $form->isValid())
    {
      if($movies->getPoster()!=null)
      {       
        $file = $form->get('poster')->getData();
        $fileName = md5(uniqid()).'.'.$file->guessExtension();
        $file->move( $this->getParameter('moviePoster'), $fileName);
        $movies->setPoster($fileName);
        $this->addFlash("message","new image updated"); 
      }
      else
      {
        $movies->setPoster($oldFile);
        $this->addFlash("message",""); 
      }
      $em->persist($movies);
      $em->flush();
      $this->addFlash("message","Movies data updated sucessfully");
      return $this->redirectToRoute('show_movie_route');
    }
    return $this->render('UpdateMovies.html.twig',['form'=>$form->createView(),'movies'=>$oldFile]);
  }

  /**
  *@Route("/deletemovies/{id}",name = "delete_movies_route")
  */
  public function deletemovies(Request $request ,$id):Response
  {
    $em = $this->getDoctrine()->getManager();
    $movies = $em->getRepository(Movies::class)->findOneById($id);
    $em->remove($movies);
    $em->flush();
    $this->addFlash('meassage','Movies data deleted sucessfully');
    return $this->redirectToRoute('view_editmovies_route');
  }

  /**
     *@Route("/viewmoreadmin/{id}",name = "view_more_admin")
     */
    public function viewMoreUser(Request $request ,$id):Response
    {
        $em = $this->getDoctrine()->getManager();
        $movies = $em->getRepository(Movies::class)->findAllMoviesInfo($id);
        return $this->render('ViewFullMovie.html.twig',['movies'=>$movies]); 
    }

  /**
   * @Route("/viewreview/{id}", name="view_review_route")
   */
  public function viewReview(Request $request, $id):Response
  {
    $em = $this->getDoctrine()->getManager();
    $reviews = $em->getRepository(Reviews::class)->findAllReviewInfo($id); 
    return $this->render('viewAdminReview.html.twig',['reviews'=>$reviews]);  
  }

  /**
   * @Route("/showmovies/{currentPage}",name="show_movie_route")
   *
   */
  public function movieShowAction($currentPage = 2):Response
  {   
    $em = $this->getDoctrine()->getManager();
    $limit=1;
    $movies = $em->getRepository(Movies::class)->findAllMovie($currentPage,$limit);

    $maxPages = ceil($movies->count()/$limit);
    $thisPage = $currentPage;

    return $this->render('ViewMovies.html.twig',['movies'=>$movies,'maxPages'=>$maxPages,'thisPage'=>$thisPage]); 
  }
}
?>