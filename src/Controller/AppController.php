<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    /**
     * @Route("/", name="app.home")
     */
    public function index()
    {
        return $this->render('app/index.html.twig');
    }

    /**
     * @Route("/contact", name="app.contact")
     */
    public function contact()
    {
        return $this->render('app/contact.html.twig');
    }


    /**
     * @Route("/services", name="app.services")
     */
    public function services()
    {
        return $this->render('app/services.html.twig');
    }
}
