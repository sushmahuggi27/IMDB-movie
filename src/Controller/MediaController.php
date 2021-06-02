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

class MediaController extends AbstractController
{ 
  /**
   * @Route("/addmedia",name="add_media_route")
   */
  public function addMedia(Request $request):Response
  {
    $media = new Media();
    $form = $this->createForm(MediaType::class,$media);
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid())
    {
      $file = $form->get('image')->getData();
      $fileName = md5(uniqid()).'.'.$file->guessExtension();
      $file->move( $this->getParameter('mediaProfile'), $fileName); 
      $media->setImage($fileName); 
      $em = $this->getDoctrine()->getManager();
      $em->persist($media);
      $em->flush();
      $this->addFlash('message','Media data saved');
      return $this->redirectToRoute('show_movie_route');
    }
    return $this->render('add/media.html.twig',['form'=>$form->createView()]);
  }

  /**
  * @Route("/viewmedia",name="view_media_route")
  */
  public function viewProduction():Response
  {   
    $em = $this->getDoctrine()->getManager();
    $media = $em->getRepository(Media::class)->findAll();
    return $this->render('ViewMedia.html.twig',['media'=>$media]);   
  }

  /**
   * @Route("/editmedia", name="view_editmedia_route")
   */
  public function editMedia():Response
  {
    $em = $this->getDoctrine()->getManager();
    $media = $em->getRepository(Media::class)->findAllMediaOrderBy();
    return $this->render('ViewMedia.html.twig',['media'=>$media]);  
  }

  /**
  * @Route("/updatemedia/{id}",name="update_media_route")
  */
  public function updateMedia(Request $request, $id):Response
  {
    $em = $this->getDoctrine()->getManager();
    $media = $em->getRepository(Media::class)->find($id);
    $form =$this->createForm(MediaType::class,$media);
    $oldFile = $media->getImage();
    $oldPath = $this->getParameter('mediaProfile').'/'.$media->getImage();
    $profileFile = new File($oldPath);
    $media->setImage($profileFile );    
    $form->handleRequest($request);
    if ($form->isSubmitted()&& $form->isValid())
    {
      if($media->getImage()!=null)
      {       
        $file = $form->get('image')->getData();
        $fileName = md5(uniqid()).'.'.$file->guessExtension();
        $file->move( $this->getParameter('mediaProfile'), $fileName);
        $media->setImage($fileName);
        $this->addFlash("message","New image updated"); 
      }
      else
      {
        $media->setImage($oldFile);
        $this->addFlash("message",""); 
      }
      $em->persist($media);
      $em->flush();
      $this->addFlash("message","Media data updated sucessfully");
      return $this->redirectToRoute('view_media_route');
    }
    return $this->render('UpdateMedia.html.twig',['form'=>$form->createView(),'media'=>$oldFile]);
  }

  /**
  *@Route("/deletemedia/{id}",name = "delete_media_route")
  */
  public function deleteMedia(Request $request ,$id):Response
  {
    $em = $this->getDoctrine()->getManager();
    $media = $em->getRepository(Media::class)->findOneById($id);
    $em->remove($media);
    $em->flush();
    $this->addFlash('meassage','Media data deleted sucessfully');
    return $this->redirectToRoute('view_media_route');
  }
}
?>