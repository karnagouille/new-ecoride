<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class NewrouteController extends AbstractController
{
    #[Route('/newroute', name: 'newroute')]
    public function index(): Response
    {
        return $this->render('/route/newroute.html.twig');
    }
}
