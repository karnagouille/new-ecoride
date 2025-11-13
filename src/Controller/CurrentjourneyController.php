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
        $user = $this->getUser();
        $trajets = $carpoolingRepository->findByUserOrParticipation($user);

        return $this->render('route/currentjourney.html.twig', [
            'user' => $user,
            'trajets' => $trajets,
        ]);
    }

    // Changement de statut avec vérification utilisateur
    #[Route('/trajet/{id}/changer-statut', name: 'change_statut', methods: ['POST'])]
    public function changerStatut(Carpooling $trajet, EntityManagerInterface $em): Response
    {
        
        if ($trajet->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        dump($trajet->getParticipants());
        die;

        switch ($trajet->getStatut()) {
            case Carpooling::STATUT_RIEN:
            case null:
                $trajet->setStatut(Carpooling::STATUT_EN_COURS);
                break;

            case Carpooling::STATUT_EN_COURS:
                $trajet->setStatut(Carpooling::STATUT_TERMINE);

                // Décrémentation de tous les participants
                foreach ($trajet->getParticipants() as $participant) {
                    $userParticipant = $participant->getUser();
                    $userParticipant->setCredit($userParticipant->getCredit() - 2);
                    $em->persist($userParticipant);
                }

                break;

            case Carpooling::STATUT_ANNULEE:
                $trajet->setStatut(Carpooling::STATUT_RIEN);
                break;
        }

        $em->flush();

        return $this->redirectToRoute('currentjourney');
    }

    #[Route('/trajet/{id}/cancel', name: 'annulation')]
    public function canceltrajet(int $id,CarpoolingRepository $carpoolingRepository,ParticipantRepository $participantRepository,EntityManagerInterface $em): Response {
        $trajet = $carpoolingRepository->find($id);

        if (!$trajet) {
            throw $this->createNotFoundException('Trajet introuvable.');
        }

        if ($trajet->getUser() === $this->getUser()) {
            // Annulation par le conducteur
            $trajet->setStatut(Carpooling::STATUT_ANNULEE);
        } else {
            // Annulation par un participant
            $participant = $participantRepository->findOneBy([
                'carpooling' => $trajet,
                'user' => $this->getUser(),
            ]);

            if ($participant) {
                $em->remove($participant);
                $trajet->removeParticipant($participant);
                $trajet->setPassenger($trajet->getPassenger() + 1);
            }
        }

        $em->flush();

        return $this->redirectToRoute('currentjourney');
    }
}
