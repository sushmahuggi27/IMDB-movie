<?php
namespace App\Controller\User;

use App\Entity\User;
use App\Entity\Movies;
use App\Form\Type\User\RegistrationFormType;
use App\Security\StubAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use App\Repository\UserRepository;
use App\Entity\Review;
use App\Form\Type\ReviewType;

class UserRegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) 
        {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                ));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('app_ulogin');
        }
        return $this->render('user/UserRegistration.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }  

    /**
    * @Route("/usermovies", name="view_usermovies_route")
     */
    public function userMovies():Response
    {
        $em = $this->getDoctrine()->getManager();
        $movies = $em->getRepository(Movies::class)->findAllMoviesOrderBy();
        return $this->render('user/UserView.html.twig',['movies'=>$movies]);  
    }

    /**
     *@Route("/viewmoreuser/{id}",name = "view_more_user")
     */
    public function viewMoreUser(Request $request ,$id):Response
    {
        $em = $this->getDoctrine()->getManager();
        $movies = $em->getRepository(Movies::class)->findAllMoviesInfo($id);
       //dd($movies);
        return $this->render('user/ViewFullUserMovie.html.twig',['movies'=>$movies]); 
    }
    
}