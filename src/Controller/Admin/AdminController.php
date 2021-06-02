<?php

namespace App\Controller\Admin;

use App\Form\Type\AdminType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\Tools\Pagination\Paginator ;
use App\Entity\Admin;

class AdminController extends AbstractController
{
    /**
     * @Route("/adminlogin",name="admin_login_route")
    */
    public function loginAdmin(Request $request):Response
    {
        $em = $this->getDoctrine()->getManager();
        $admin = $em->getRepository(Admin::class)->findOneById(1); 
        $password = $admin->getPassword();  
        $form =$this->createForm(AdminType::class,$admin);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) 
        {
            if($password==$form['password']->getData())
            {
                $this->addFlash('meassage','Login sucessfull');
                return  $this->redirectToRoute('view_editmovies_route');
            }
            else 
            {
                $this->addFlash('meassage','Login failed');
            }      
        }
        return $this->render('Admin.html.twig',['form'=>$form->createView()]);
    }
}
?>