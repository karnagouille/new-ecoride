<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Carpooling;
use App\Repository\UserRepository;
use App\Form\NewemployeaccountType;
use App\Repository\CarpoolingRepository;
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
    public function index(EntityManagerInterface $em,UserRepository $userRepository, User $user): Response
    {
        $employes = $userRepository->findAllemployees();


        return $this->render('adminaccount/admin.html.twig',[
            'employes'=>$employes,
            'user'=> $user,
            
        ]);
    }



    #[Route('/admin/toggle/{id}', name: 'find')]
    public function find(EntityManagerInterface $em,UserRepository $userRepository,int $id): Response
    {
        

        $user = $userRepository->find($id);

        if($user->isActive()){
            $user->setIsActive(false);
        }else{
            $user->setIsActive(true);
        }
        $em->persist($user);
        $em->flush();

        return $this->render('adminaccount/admin.html.twig',[
            'user'=> $user,
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
            
            $user->setCredit(20); // Ajout de crédit à la création du compte

            $em->persist($user);
            $em->flush();
            
            return $this->redirectToRoute('admin');
        };
        
        return $this->render('adminaccount/adminform.html.twig',[
            'form'=>$form,
        ]);
    }

// graphique du nombre de covoiturage journalier et hebdo

  #[Route('/admin/week', name: 'week')]
public function week(CarpoolingRepository $carpoolingRepository): Response
{
    $trajets = $carpoolingRepository->findBy(['statut' => Carpooling::STATUT_TERMINE]);

    $week = [];
    $monday = new \DateTime('monday this week');
    for ($i = 0; $i < 7; $i++) {
        $week[] = (clone $monday)->modify("+$i day");
    }

    $nbTrajetsParJour = [];
    foreach ($week as $day) {
        $start = (clone $day)->setTime(0,0,0);
        $end   = (clone $day)->modify('+1 day')->setTime(0,0,0);
        $nbTrajetsParJour[] = count(array_filter($trajets, fn($t) => $t->getStartAt() >= $start && $t->getStartAt() < $end));
    }

    return $this->json([
        'weekTrajets' => $nbTrajetsParJour
    ]);
}




// graphique de gain de crédits journalier et hebdo

#[Route('/admin/credit', name: 'credit')]
public function credit(CarpoolingRepository $carpoolingRepository, EntityManagerInterface $doctrine): Response
{
    $trajets = $carpoolingRepository->findBy(['statut' => Carpooling::STATUT_TERMINE]);

    // Admin platform user
    $platformUser = $doctrine->getRepository(User::class)->find(1);

    $week = [];
    $monday = new \DateTime('monday this week');
    for ($i = 0; $i < 7; $i++) {
        $week[] = (clone $monday)->modify("+$i day");
    }

    $creditsParJour = [];
    foreach ($week as $day) {
        $start = (clone $day)->setTime(0,0,0);
        $end   = (clone $day)->modify('+1 day')->setTime(0,0,0);

        $totalCredits = 0;
        foreach ($trajets as $trajet) {
            if ($trajet->getStartAt() >= $start && $trajet->getStartAt() < $end) {
                $totalCredits += count($trajet->getParticipants()) * 2; // 2 crédits par participant
            }
        }
        $creditsParJour[] = $totalCredits;
    }

    return $this->json([
        'weekCredits' => $creditsParJour
    ]);
}



        }





    






