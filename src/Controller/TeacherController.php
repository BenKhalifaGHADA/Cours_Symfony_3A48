<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TeacherController extends AbstractController
{
    #[Route('/teacher', name: 'app_teacher')]
    public function index(): Response
    {
        return $this->render('teacher/index.html.twig', [
            'controller_name' => 'TeacherController',
        ]);
    }
    #[Route('/teacher/{name}',name: 'showTeacher')]
    //Route('URL','Nom de la route')
    //$n , _n
    public function showTeacher($name):Response{
       return $this->render('teacher/showTeacher.html.twig',['name'=>$name]);
       //render('view',"tableau des paramètres")
    }

    #[Route('/gotoindex',name:'gotoindex')]
    public function goToIndex(){
       //La redirection
        return $this->redirectToRoute('app_student');
    }
}
