<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MyaccountController extends AbstractController
{
    #[Route('/myaccount', name: 'myaccount')]
    public function index(): Response
    {
        return $this->render('/myaccount/myaccount.html.twig');
    }
}
