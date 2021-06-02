<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;
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

class DirectorController extends AbstractController
{ 
  /**
   * @Route("/adddirector",name="add_director_route")
   */
  public function addDirector(Request $request):Response
  {
    $director = new Director();
    $form = $this->createForm(DirectorType::class,$director);
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid())
    {
      $file = $form->get('profile')->getData();
      $fileName = md5(uniqid()).'.'.$file->guessExtension();
      $file->move( $this->getParameter('directorProfile'), $fileName); 
      $director->setProfile($fileName); 
      $em = $this->getDoctrine()->getManager();
      $em->persist($director);
      $em->flush();
      $this->addFlash('message','Director data saved');
      return $this->redirectToRoute('show_movie_route');
    }
    return $this->render('add/director.html.twig',['form'=>$form->createView()]);
  }

  /**
  * @Route("/viewdirector",name="view_director_route")
  */
  public function viewDirector():Response
  {   
    $em = $this->getDoctrine()->getManager();
    $director = $em->getRepository(Director::class)->findAll();
    return $this->render('ViewDirector.html.twig',['director'=>$director]);   
  }

  /**
   * @Route("/editdirector", name="view_editdirector_route")
   */
  public function editDirector():Response
  {
    $em = $this->getDoctrine()->getManager();
    $director = $em->getRepository(Director::class)->findAllDirectorOrderBy();
    return $this->render('ViewDirector.html.twig',['director'=>$director]);  
  }

  /**
  * @Route("/updatedirector/{id}",name="update_director_route")
  */
  public function updateDirector(Request $request, $id):Response
  {
    $em = $this->getDoctrine()->getManager();
    $director = $em->getRepository(Director::class)->find($id);
    $form =$this->createForm(DirectorType::class,$director);
    $oldFile = $director->getProfile();
    $oldPath = $this->getParameter('directorProfile').'/'.$director->getProfile();
    $profileFile = new File($oldPath);
    $director->setProfile($profileFile );    
    $form->handleRequest($request);
    if ($form->isSubmitted()&& $form->isValid())
    {
      if($director->getProfile()!=null)
      {       
        $file = $form->get('profile')->getData();
        $fileName = md5(uniqid()).'.'.$file->guessExtension();
        $file->move( $this->getParameter('directorProfile'), $fileName);
        $director->setProfile($fileName);
        $this->addFlash("message","New image updated"); 
      }
      else
      {
        $director->setProfile($oldFile);
        $this->addFlash("message",""); 
      }
      $em->persist($director);
      $em->flush();
      $this->addFlash("message","Director data updated sucessfully");
      return $this->redirectToRoute('view_director_route');
    }
    return $this->render('UpdateDirector.html.twig',['form'=>$form->createView(),'director'=>$oldFile]);
  }

  /**
  *@Route("/deletedirector/{id}",name = "delete_director_route")
  */
  public function deleteDirector(Request $request ,$id):Response
  {
    $em = $this->getDoctrine()->getManager();
    $director = $em->getRepository(Director::class)->findOneById($id);
    $em->remove($director);
    $em->flush();
    $this->addFlash('message','Director data deleted sucessfully');
    return $this->redirectToRoute('view_director_route');
  }
}
?>