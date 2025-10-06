<?php

namespace App\Controller;

use App\Entity\Carpooling;
use App\Repository\CarpoolingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


final class CurrentjourneyController extends AbstractController
{
    #[Route('/currentjourney', name: 'currentjourney')]
    public function index(CarpoolingRepository $carpoolingRepository): Response
    {
        

        $user = $this->getuser();
        $trajets = $carpoolingRepository->findby([
            'user'=> $this->getUser()
        ]);

        return $this->render('route/currentjourney.html.twig',[
        'user'=>$user, 
        'trajets'=>$trajets,
        ]);
        }

        #[Route('/trajet/{id}/changer-statut', name: 'change_statut')]
    public function changerStatut(Carpooling $trajet, EntityManagerInterface $em): Response
        {
        if ($trajet->getStatut() === Carpooling::STATUT_RIEN || $trajet->getStatut() === null) {
            $trajet->setStatut(Carpooling::STATUT_EN_COURS);
        } elseif ($trajet->getStatut() === Carpooling::STATUT_EN_COURS) {
            $trajet->setStatut(Carpooling::STATUT_TERMINE);
        }
            $em->flush();

    // Redirection vers la page principale des trajets
    return $this->redirectToRoute('currentjourney');
}






}