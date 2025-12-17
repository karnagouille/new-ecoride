<?php

namespace App\Controller;



use App\Entity\Car;
use App\Entity\Carpooling;
use App\Form\CarpoolingType;
use App\Entity\CreditTransaction;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class NewrouteController extends AbstractController
{
    #[Route('/newroute', name: 'newroute')]
    public function index(Request $request,EntityManagerInterface $em,): Response
    {
        /** @var \App\Entity\User $user */
    
            $user = $this->getUser(); 
            $userCars = $em->getRepository(Car::class)->findBy(['user' => $user]);
            

            $trajet = new Carpooling();
            $form = $this->createForm(CarpoolingType::class, $trajet, [
            'csrf_token_id' => 'form',
            'user_cars' => $userCars,
            ]);


            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){ 

            $trajet->setUser($user);
            $em->persist($trajet);

            $prixDuTrajet = (float) $form->get('price')->getData();
                    // Créer une transaction initiale pour que le prix soit défini
            $transaction = new CreditTransaction();
            $transaction->setCarpooling($trajet);
            $transaction->setAmount($prixDuTrajet); 
            $transaction->setSender($user); 
            $transaction->setReceiver($user); 

            $em->persist($transaction);
            $em->flush();
            return $this->redirectToRoute('myaccount');
        }


        return $this->render('route/newroute.html.twig',[
            'form'=> $form->createView()
        ]);
    }
}
