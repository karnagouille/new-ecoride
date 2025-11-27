<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Carpooling;
use App\Form\EmailsearchType;
use App\Form\NewemployeaccountType;
use App\Repository\UserRepository;
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

    #[Route('/admin', name: 'admin')]
    #[IsGranted('ROLE_ADMIN')]
    public function admin(UserRepository $userRepository, EntityManagerInterface $em, Request $request): Response
    {
        
        $form = $this->createForm(EmailsearchType::class);
        $form->handleRequest($request);


       if ($request->isMethod('POST') && $request->request->has('toggle_user_id')) {
    $id = $request->request->get('toggle_user_id');
    $user = $userRepository->find($id);
    if ($user) {
        $user->setIsActive(!$user->isActive());
        $em->flush();
    }
}

        $users = [];
        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();
            if ($email) {
                $users = $userRepository->searchByEmail($email);
            }
        }

        return $this->render('adminaccount/admin.html.twig', [
            'users' => $users,
            'form' => $form->createView(),
        ]);
    }

    // Création d'un nouvel utilisateur
    #[Route('/admin/form', name: 'admin_form')]
    #[IsGranted('ROLE_ADMIN')]
    public function adminForm(EntityManagerInterface $em, Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(NewemployeaccountType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setRoles(['ROLE_EMPLOYE']);
            $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);
            $user->setCredit(20); // Crédit initial

            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('admin');
        }

        return $this->render('adminaccount/adminform.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // Graphique : covoiturages par jour
    #[Route('/admin/week', name: 'week')]
    #[IsGranted('ROLE_ADMIN')]
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

    // Graphique : crédits par jour
    #[Route('/admin/credit', name: 'credit')]
    #[IsGranted('ROLE_ADMIN')]
    public function credit(CarpoolingRepository $carpoolingRepository): Response
    {
        $trajets = $carpoolingRepository->findBy(['statut' => Carpooling::STATUT_TERMINE]);

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
                    $totalCredits += count($trajet->getParticipants()) * 2;
                }
            }
            $creditsParJour[] = $totalCredits;
        }

        return $this->json([
            'weekCredits' => $creditsParJour
        ]);
    }
}
