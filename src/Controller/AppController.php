<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class AppController extends AbstractController
{
    #[Route(path: '/', name: 'app_index')]
    public function index(): Response
    {
        return $this->render('app/index.html.twig');
    }

    #[Route(path: '/services', name: 'services')]
    public function services(): Response
    {
        return $this->render('app/services.html.twig');
    }

    #[Route(path: '/services/php-systems-architecture', name: 'service_php_systems_architecture')]
    public function phpSystemsArchitecture(): Response
    {
        return $this->render('app/service/php-systems-architecture.html.twig');
    }

    #[Route(path: '/services/automated-php-refactoring', name: 'service_automated_php_refactoring')]
    public function automatedPhpRefactoring(): Response
    {
        return $this->render('app/service/automated-php-refactoring.html.twig');
    }

    #[Route(path: '/services/database-refactoring', name: 'service_database_refactoring')]
    public function databaseRefactoring(): Response
    {
        return $this->render('app/service/database-refactoring.html.twig');
    }

    #[Route(path: '/faq', name: 'faq')]
    public function faq(): Response
    {
        return $this->render('app/faq.html.twig');
    }

    #[Route(path: '/contact', name: 'contact')]
    public function contact(): Response
    {
        return $this->render('app/contact.html.twig');
    }
}
