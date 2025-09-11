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
        $carpooling = new Carpooling();
        $form = $this->createForm(CarpoolingType::class,$carpooling);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){ 

        /** @var \App\Entity\User $user */

            $user = $this->getUser(); 
            $userCars = $user->getCars();

            if (!$userCars->isEmpty()) {
                $carpooling->setCar($userCars->first());

            } else {
                $this->addFlash('error', 'Vous devez avoir une voiture pour créer un trajet.');
                return $this->redirectToRoute('newroute');
            }

            $em->persist($carpooling);
            $em->flush();

            $this->addFlash('success', 'Trajet enregistré !');
            return $this->redirectToRoute('newroute');
        }


        return $this->render('route/newroute.html.twig',[
            'form'=> $form->createview()
        ]);
    }
}
