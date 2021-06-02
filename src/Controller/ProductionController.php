<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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


class ProductionController extends AbstractController
{ 
  /**
   * @Route("/addproduction",name="add_production_route")
   */
  public function addProduction(Request $request):Response
  {
    $production = new Production();
    $form = $this->createForm(ProductionType::class,$production);
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid())
    {
      $em = $this->getDoctrine()->getManager();
      $em->persist($production);
      $em->flush();
      $this->addFlash('message','Production data saved');
      return $this->redirectToRoute('view_editmovies_route');
    }
    return $this->render('add/production.html.twig',['form'=>$form->createView()]);
  }

  /**
  * @Route("/viewproduction",name="view_production_route")
  */
  public function viewProduction():Response
  {   
    $em = $this->getDoctrine()->getManager();
    $production = $em->getRepository(Production::class)->findAll();
    return $this->render('ViewProduction.html.twig',['production'=>$production]);   
  }

  /**
   * @Route("/editproduction", name="view_editproduction_route")
   */
  public function editProduction():Response
  {
    $em = $this->getDoctrine()->getManager();
    $production = $em->getRepository(Production::class)->findAllProductionOrderBy();
    return $this->render('ViewProduction.html.twig',['production'=>$production]);  
  }

  /**
  * @Route("/updateproduction/{id}",name="update_production_route")
  */
  public function updateProduction(Request $request, $id):Response
  {
    $production = $this->getDoctrine()->getRepository(Production::class)->find($id);
    $production->setName($production->getName());
    $production->setCountry($production->getCountry());
    $production->setDescription($production->getDescription());
    $form = $this->createForm(ProductionType::class, $production);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) 
    {
      $em = $this->getDoctrine()->getManager();
      $em->persist($production);
      $em->flush();
      $this->addFlash('message', 'production data updated_successfully');
      return $this->redirectToRoute('view_production_route');
    }
    return $this->render('UpdateProduction.html.twig', ['form' => $form->createView()]);
  }

  /**
  *@Route("/deleteproduction/{id}",name = "delete_production_route")
  */
  public function deleteProduction(Request $request ,$id):Response
  {
    $em = $this->getDoctrine()->getManager();
    $production = $em->getRepository(production::class)->findOneById($id);
    $em->remove($production);
    $em->flush();
    $this->addFlash('meassage','production data deleted sucessfully');
    return $this->redirectToRoute('view_production_route');
  }
}
?>