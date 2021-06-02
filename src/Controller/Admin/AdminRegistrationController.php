<?php
namespace App\Controller\Admin;

use App\Entity\Admin;
use App\Entity\Movies;
use App\Form\Type\User\RegistrationFormType;
use App\Form\Type\AdminRegistrationFormType;
use App\Security\StubAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class AdminRegistrationController extends AbstractController
{
    /**
     * @Route("/aregister", name="admin_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $admin = new Admin();
        $form = $this->createForm(AdminRegistrationFormType::class, $admin);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) 
        {
            // encode the plain password
            $admin->setPassword(
                $passwordEncoder->encodePassword(
                    $admin,
                    $form->get('plainPassword')->getData()
                ));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($admin);
            $entityManager->flush();
            return $this->redirectToRoute('app_login');
        }
        return $this->render('AdminRegistration.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    } 
}