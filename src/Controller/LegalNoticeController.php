<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class LegalNoticeController extends AbstractController
{
    #[Route('/legal/notice', name: 'legalnotice')]
    public function index(): Response
    {
        return $this->render('legalnotice.html.twig', [
            'controller_name' => 'LegalNoticeController',
        ]);
    }
}
