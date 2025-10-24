<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentaireType;
use App\Repository\CommentRepository;
use App\Repository\CarpoolingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class RenseignementController extends AbstractController
{
    #[Route('/renseignement/{id}', name: 'renseignement', methods: ['GET', 'POST'])]
    public function comment(CommentRepository $commentRepository,EntityManagerInterface $em,Request $request,CarpoolingRepository $carpoolingRepository,int $id): Response {

        $currentUser = $this->getUser();
        $trajet = $carpoolingRepository->find($id);

        if (!$trajet) {
            throw $this->createNotFoundException("Trajet non trouvé.");
        }

        $conducteur = $trajet->getUser();

        // Création d'un nouveau commentaire
        $comment = new Comment();
        $comment->setUser($currentUser);
        $comment->setDriver($conducteur);
        $comment->setTrajet($trajet); // 👈 important pour relier le commentaire au trajet

        $form = $this->createForm(CommentaireType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('renseignement', ['id' => $id]);
        }

        $currentUser = $this->getUser();
        $canComment = false;

        foreach ($trajet->getParticipants() as $participant) {
            $user = $participant->getUser();
        if ($user && $currentUser && $user === $currentUser) {
            $canComment = true;
            break;
            }
        }


        $comments = $commentRepository->findBy(
            ['driver' => $conducteur, 'trajet' => $trajet],
            ['id' => 'DESC']
        );

        return $this->render('renseignement.html.twig', [
            'conducteur' => $conducteur,
            'comment' => $comment,
            'trajet' => $trajet,
            'participant' => $currentUser,
            'form' => $form->createView(),
            'comments' => $comments,
            'canComment' => $canComment,
        ]);
    }

    #[Route('/renseignementshow/{id}', name: 'renseignementshow', methods: ['GET'])]
    public function commentShow(Comment $comment): Response
    {
        return $this->render('renseignement.html.twig', [
            'comment' => $comment,
        ]);
    }
}
