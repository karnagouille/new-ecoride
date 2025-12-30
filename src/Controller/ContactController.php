<?php

namespace App\Controller;

use App\Form\ContactType;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function index(DocumentManager $dm, Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();

            // Envoi du mail
            $email = (new Email())
                ->from('gonzalesalexis999@gmail.com') // l'email du formulaire
                ->replyTo($contact->getEmail()) 
                ->to('gonzalesalexis999@gmail.com') // ton adresse de réception
                ->subject('Nouveau message de contact')
                ->text("Message : " . $contact->getMessage());

            try {
                $mailer->send($email);
                $this->addFlash('success', 'Votre message a été envoyé !');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Le mail n’a pas pu être envoyé : '.$e->getMessage());
            }

            // Sauvegarde dans MongoDB
            $dm->persist($contact);
            $dm->flush();

            return $this->redirectToRoute('contact'); // redirection après envoi
        }

        return $this->render('contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
