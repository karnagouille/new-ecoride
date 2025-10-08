<?php

namespace App\Controller;



use App\Entity\Carpooling;
use App\Form\CarpoolingType;
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
        $trajet = new Carpooling();
        $form = $this->createForm(CarpoolingType::class, $trajet, [
        'csrf_token_id' => 'form',
        ]);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){ 

        /** @var \App\Entity\User $user */

            $user = $this->getUser(); 
            $userCars = $user->getCars();

            if (!$userCars->isEmpty()) {
                $trajet->setCar($userCars->first());

            } else {
                $this->addFlash('error', 'Vous devez avoir une voiture pour créer un trajet.');
                return $this->redirectToRoute('newroute');
            }
            $trajet->setUser($user);
            $em->persist($trajet);
            $em->flush();

            $this->addFlash('success', 'Trajet enregistré !');
            return $this->redirectToRoute('myaccount');
        }


        return $this->render('route/newroute.html.twig',[
            'form'=> $form->createview()
        ]);
    }
}
