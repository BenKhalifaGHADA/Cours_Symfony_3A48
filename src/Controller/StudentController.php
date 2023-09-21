<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StudentController extends AbstractController
{
    #[Route('/student', name: 'app_student')]
    public function index(): Response
    {
        return $this->render('student/index.html.twig', [
            'controller_name' => 'StudentController',
        ]);
    }

    // /home/4
    #[Route('/home/{id}',name:'home')]
    public function home($id){
        //return new Response("Hello 3A".$id);
        //render (view, paramètres)
        return $this->render('student/home.html.twig',['valeur'=>$id]);
    }

}
