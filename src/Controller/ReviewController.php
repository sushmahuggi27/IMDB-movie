<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Movies;
use App\Entity\Director;
use App\Entity\Actors;
use App\Entity\Production;
use App\Entity\Media;
use App\Entity\User;
use App\Entity\Reviews;
use App\Form\Type\ReviewType;

class ReviewController extends AbstractController
{
  /**
  * @Route("/addreview/{id}",name="add_review_route")
  */
  public function addReview(Request $request, $id):Response
  {
    $review = new Reviews();
    $movies = $this->getDoctrine()->getRepository(Movies::class)->find($id);
    $uid = $this->getUser()->getId();
    $user = $this->getDoctrine()->getRepository(User::class)->find($uid);
    //dd($useri);   
    $form = $this->createForm(ReviewType::class,$review);
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid())
    {
      $rating =$form['rating']->getData();
      $reviews=$form['reviews']->getData();

      $review->setRating($rating);
      $review->setReviews($reviews);

      $review->setMovies($movies);
      $review->setUser($user);

      $em = $this->getDoctrine()->getManager();
      $em->persist($review);
      $em->flush();
      $this->addFlash('message','Review data saved');
      //return $this->redirectToRoute('view_more_user');
    }
    return $this->render('add/review.html.twig',['form'=>$form->createView()]);
  }

  /**
   * @Route("/viewreview/{id}", name="view_review_route")
   */
  public function viewReview(Request $request, $id):Response
  {
    $em = $this->getDoctrine()->getManager();
    $reviews = $em->getRepository(Reviews::class)->findAllReviewInfo($id);
    //dd($reviews);
    //$reviews = $em->getRepository(Reviews::class)->findAll();  
    return $this->render('user/viewReview.html.twig',['reviews'=>$reviews]);  
  }

  /**
  *@Route("/deletereview/{id}",name = "delete_review_route")
  */
  public function deletereview(Request $request ,$id):Response
  {
    $em = $this->getDoctrine()->getManager();
    $review = $em->getRepository(Reviews::class)->findOneById($id);
    $em->remove($review);
    $em->flush();
    $this->addFlash('meassage','review data deleted sucessfully');
    return $this->redirectToRoute('view_usermovies_route');
  }
}
?>