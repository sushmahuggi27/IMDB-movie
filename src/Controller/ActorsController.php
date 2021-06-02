<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpFoundation\File\File;
use App\Form\Type\MoviesType;
use App\Form\Type\ActorsType;
use App\Form\Type\DirectorType;
use App\Form\Type\ProductionType;
use App\Form\Type\MediaType;
use App\Entity\Actors;
use App\Entity\Movies;
use App\Entity\Director;
use App\Entity\Production;
use App\Entity\Media;

class ActorsController extends AbstractController
{ 
  /**
   * @Route("/addactors",name="add_actors_route")
   */
  public function addActors(Request $request):Response
  {
    $actors = new Actors();
    $form = $this->createForm(ActorsType::class,$actors);
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid())
    {
      $file = $form->get('profile')->getData();
      $fileName = md5(uniqid()).'.'.$file->guessExtension();
      $file->move( $this->getParameter('actorsProfile'), $fileName); 
      $actors->setProfile($fileName); 
      $em = $this->getDoctrine()->getManager();
      $em->persist($actors);
      $em->flush();
      $this->addFlash('message','Actors data saved');
      return $this->redirectToRoute('show_movie_route');
    }
    return $this->render('add/actors.html.twig',['form'=>$form->createView()]);
  }

  /**
  * @Route("/viewactors",name="view_actors_route")
  */
  public function viewActors():Response
  {   
    $em = $this->getDoctrine()->getManager();
    $actors = $em->getRepository(Actors::class)->findAll();
    return $this->render('ViewActors.html.twig',['actors'=>$actors]);   
  }

  /**
   * @Route("/editactors", name="view_editactors_route")
   */
  public function editActors():Response
  {
    $em = $this->getDoctrine()->getManager();
    $actors = $em->getRepository(Actors::class)->findAllActorsOrderBy();
    return $this->render('ViewActors.html.twig',['actors'=>$actors]);  
  }

  /**
  * @Route("/updateactors/{id}",name="update_actors_route")
  */
  public function updateActors(Request $request, $id):Response
  {
    $em = $this->getDoctrine()->getManager();
    $actors = $em->getRepository(Actors::class)->find($id);
    $form =$this->createForm(ActorsType::class,$actors);
    $oldFile = $actors->getProfile();
    $oldPath = $this->getParameter('actorsProfile').'/'.$actors->getProfile();
    $profileFile = new File($oldPath);
    $actors->setProfile($profileFile );    
    $form->handleRequest($request);
    if ($form->isSubmitted()&& $form->isValid())
    {
      if($actors->getProfile()!=null)
      {       
        $file = $form->get('profile')->getData();
        $fileName = md5(uniqid()).'.'.$file->guessExtension();
        $file->move( $this->getParameter('actorsProfile'), $fileName);
        $actors->setProfile($fileName);
        $this->addFlash("message","new image updated"); 
      }
      else
      {
        $actors->setProfile($oldFile);
        $this->addFlash("message",""); 
      }
      $em->persist($actors);
      $em->flush();
      $this->addFlash("message","Actors data updated sucessfully");
      return $this->redirectToRoute('view_editactors_route');
    }
    return $this->render('UpdateActors.html.twig',['form'=>$form->createView(),'actors'=>$oldFile]);
  }

  /**
  *@Route("/deleteactors/{id}",name = "delete_actors_route")
  */
  public function deleteActors(Request $request ,$id):Response
  {
    $em = $this->getDoctrine()->getManager();
    $actors = $em->getRepository(Actors::class)->findOneById($id);
    $em->remove($actors);
    $em->flush();
    $this->addFlash('meassage','Actors data deleted sucessfully');
    return $this->redirectToRoute('view_actors_route');
  }
}
?>