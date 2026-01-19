<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



class HomeController extends AbstractController
{
    #[Route('/home', name: 'homepage', requirements: ['_locale' => 'en|es'], methods: ['GET'])]
    public function index(): Response
    {
        //Definimos una cláusula de guarda para redirigir a los usuarios ya autenticados
        if (!$this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('defaults/homepage.html.twig');
    }
}