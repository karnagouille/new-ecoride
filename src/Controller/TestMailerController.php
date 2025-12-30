<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class TestMailerController extends AbstractController
{
    #[Route('/test-mail', name: 'test_mail')]
    public function index(MailerInterface $mailer): Response
    {
        $email = (new Email())
            ->from('gonzalesalexis999@gmail.com')
            ->to('gonzalesalexis999@gmail.com')
            ->subject('Test Mail')
            ->text('Ceci est un test');

        try {
            $mailer->send($email);
            return new Response('Mail envoyé !');
        } catch (\Exception $e) {
            return new Response('Erreur : ' . $e->getMessage());
        }
    }
}
