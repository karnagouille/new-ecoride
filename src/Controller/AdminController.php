<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Form\NewemployeaccountType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class AdminController extends AbstractController
{

    #[IsGranted('ROLE_ADMIN')]

    #[Route('/admin', name: 'admin')]
    public function index(UserRepository $userRepository): Response
    {
        $employes = $userRepository->findAllemployees();

        return $this->render('adminaccount/admin.html.twig',[
            'employes'=>$employes,
        ]);
    }




    #[Route('/admin/form', name: 'admin_form')]
    public function Adminform(EntityManagerInterface $em,Request $request,UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(NewemployeaccountType::class,$user);
        $form->handleRequest($request);
        


        if ( $form->isSubmitted() && $form->isValid()){
        
            $user->setroles(['ROLE_EMPLOYE']);
            $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);

            $em->persist($user);
            $em->flush();
            
            return $this->redirectToRoute('admin');
        };
        
        return $this->render('adminaccount/adminform.html.twig',[
            'form'=>$form,
        ]);
    }


}
