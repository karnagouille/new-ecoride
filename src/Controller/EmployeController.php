<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class EmployeController extends AbstractController
{


    #[IsGranted('ROLE_EMPLOYE')]
    #[Route('/employecomment', name: 'employecomment')]
    public function employecomment(CommentRepository $CommentRepository): Response
    {
        $comments = $CommentRepository->findBy(['statut'=> Comment::STATUT_NOT_CHECKED ]);
        return $this->render('employeaccount/employe.html.twig',[
            'comments' => $comments,
        ]);
        
    }

    #[Route('/employe/{id}', name: 'employe')]
    public function index(CommentRepository $CommentRepository, EntityManagerInterface $em,int $id): Response
    {

        $comment = $CommentRepository->find($id);

        if ($comment && $comment->getStatut() === Comment::STATUT_NOT_CHECKED) {
            $comment->setStatut(Comment::STATUT_CHECKED);
            $em->flush();
        }

        return $this->redirectToRoute('employecomment');
    }



    
    #[Route('/remove/{id}', name: 'remove',methods:['POST'])]
    public function remove(Comment $comment, EntityManagerInterface $em,Request $request): Response
    {


        if($this->isCsrfTokenValid('delete'.$comment->getId(),$request->request->get('_token')))
        {
            $em->remove($comment);
            $em->flush();
        }

        return $this->redirectToRoute('employecomment');
    }
}
