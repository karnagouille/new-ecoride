<?php

namespace App\Controller;

use App\Entity\Carpooling;
use App\Repository\CarpoolingRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ParticipantRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


final class CurrentjourneyController extends AbstractController
{
    #[Route('/currentjourney', name: 'currentjourney')]
    public function index(CarpoolingRepository $carpoolingRepository): Response
    {
        

        $user = $this->getuser();
        $trajets = $carpoolingRepository->findByUserOrParticipation($this->getUser());

        return $this->render('route/currentjourney.html.twig',[
        'user'=>$user, 
        'trajets'=>$trajets,
        ]);
        }
    
 // Changement de statut et vérification de l'utilisateur connecté
        #[Route('/trajet/{id}/changer-statut', name: 'change_statut')]
    public function changerStatut(Carpooling $trajet, EntityManagerInterface $em): Response
        {
            if ($trajet->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
    }

        switch ($trajet->getStatut()) {
        case Carpooling::STATUT_RIEN:
        case null:
            $trajet->setStatut(Carpooling::STATUT_EN_COURS);
            break;
        case Carpooling::STATUT_EN_COURS:
            $trajet->setStatut(Carpooling::STATUT_TERMINE);
            break; 
        }
        $em->flush();
        return $this->render('currentjourney');

    }

#[Route('/trajet/{id}/cancel', name: 'annulation')]
    public function canceltrajet(int $id,CarpoolingRepository $CarpoolingRepository,ParticipantRepository $participantRepository, EntityManagerInterface $em): Response
        {

            $trajet = $CarpoolingRepository->find($id);

            if (!$trajet) {
                throw $this->createNotFoundException('Trajet introuvable.');
            }

            if ($trajet->getUser() === $this->getUser()) {

                $trajet->setStatut(Carpooling::STATUT_ANNULEE);
                $em->persist($trajet);

            } else {
                $participant = $participantRepository->findOneBy([
                    'carpooling' => $trajet,
                    'user' => $this->getUser()
            ]);

            $em->remove($participant);
            $trajet->removeParticipant($participant);
            $trajet->setPassenger($trajet->getPassenger() + 1);
            $em->persist($trajet);
}

$em->flush();

    return $this->redirectToRoute('currentjourney');


}
}