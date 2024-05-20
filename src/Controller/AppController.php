<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{

    #[Route(path: '/', name: 'app_index')]
    public function index() : Response
    {
        return $this->render('app/index.html.twig');
    }

    #[Route(path: '/services', name: 'services')]
    public function services() : Response
    {
        return $this->render('app/services.html.twig');
    }

    #[Route(path: '/about', name: 'about')]
    public function about() : Response
    {
        return $this->render('app/about.html.twig');
    }

    #[Route(path: '/contact', name: 'contact')]
    public function contact() : Response
    {
        return $this->render('app/contact.html.twig');
    }

}