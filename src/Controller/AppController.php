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
     * @Route("/about", name="app.about")
     */
    public function about()
    {
        return $this->render('app/about.html.twig');
    }


    /**
     * @Route("/pricing", name="app.pricing")
     */
    public function pricing()
    {
        return $this->render('app/pricing.html.twig');
    }
}
