<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Carpooling;
use App\Entity\CreditTransaction;
use App\Repository\CarpoolingRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ParticipantRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

final class CurrentjourneyController extends AbstractController
{
    #[Route('/currentjourney', name: 'currentjourney')]
    public function index(CarpoolingRepository $carpoolingRepository,): Response
    {
        $user = $this->getUser();
        $trajets = $carpoolingRepository->findByUserOrParticipation($user);


        return $this->render('route/currentjourney.html.twig', [
            'user' => $user,
            'trajets' => $trajets,
        ]);
    }

    // Changement de statut avec vérification utilisateur
    #[Route('/trajet/{id}/statut', name: 'statut', methods: ['POST'])]
    public function changerStatut(Carpooling $trajet, EntityManagerInterface $em,Request $request,ManagerRegistry $doctrine): Response
    {
        
<<<<<<< HEAD
        if (!$this->isCsrfTokenValid('statut' . $trajet->getId(), $request->request->get('_token'))) {
    throw $this->createAccessDeniedException('Token CSRF invalide');
}

        switch ($trajet->getStatut()) {
            case Carpooling::STATUT_RIEN:
            case null:
                $trajet->setStatut(Carpooling::STATUT_EN_COURS);
                break;

            case Carpooling::STATUT_EN_COURS:
                $trajet->setStatut(Carpooling::STATUT_TERMINE);

                
                $platformUserId = 1;
                // Décrémentation de tous les participants
                $platformUser = $doctrine->getRepository(User::class)->find($platformUserId);
=======

        $user = $this->getUser();
        $trajets = $carpoolingRepository->findByUserOrParticipation($this->getUser());

        return $this->render('route/currentjourney.html.twig',[
        'user'=>$user, 
        'trajets'=>$trajets,
        ]);
        }
    
 // Changement de statut et vérification de l'utilisateur connecté
    #[Route('/trajet/{id}/changer-statut', name: 'change_statut')]
public function changerStatut(Carpooling $trajet, EntityManagerInterface $em, MailerInterface $mailer): Response
{
    // Vérification que l'utilisateur est bien le conducteur
    if ($trajet->getUser() !== $this->getUser()) {
        throw $this->createAccessDeniedException();
    }

    if ($trajet->getStatut() === Carpooling::STATUT_RIEN || $trajet->getStatut() === null) {
        $trajet->setStatut(Carpooling::STATUT_EN_COURS);
    } elseif ($trajet->getStatut() === Carpooling::STATUT_EN_COURS) {
        $trajet->setStatut(Carpooling::STATUT_TERMINE);

        // Envoi des emails à tous les participants
        foreach ($trajet->getParticipants() as $participant) {
            $user = $participant->getUser();
            if ($user && $user->getEmail()) {
                $email = (new Email())
                    ->from('gonzalesalexis999@gmail.com')
                    ->to($user->getEmail())
                    ->subject('Confirmation de trajet 🚗')
                    ->text(sprintf(
                        "Bonjour %s,\nVotre trajet de %s à %s est maintenant terminé.\nN'hésitez pas à laisser un commentaire.\nMerci d’avoir utilisé EcoRide !",
                        $user->getName(),
                        $trajet->getStartTown(),
                        $trajet->getEndTown()
                    ));
                $mailer->send($email);
            }
        }
    }

    $em->flush();

    return $this->redirectToRoute('currentjourney');
}




#[Route('/trajet/{id}/cancel', name: 'annulation')]
    public function canceltrajet(int $id,CarpoolingRepository $CarpoolingRepository,ParticipantRepository $participantRepository, EntityManagerInterface $em): Response
        {
>>>>>>> dev

            foreach ($trajet->getParticipants() as $participant) {
                $userParticipant = $participant->getUser();
                $chauffeur = $trajet->getUser();
                $amount = $trajet->getCreditTransactions()->first()->getAmount();
                $platform = 2;
                $chauffeurAmount = $amount - $platform;

                // Transaction vers le chauffeur
                $transactionChauffeur = new CreditTransaction();
                $transactionChauffeur->setSender($userParticipant);
                $transactionChauffeur->setReceiver($chauffeur);
                $transactionChauffeur->setAmount($chauffeurAmount);
                $transactionChauffeur->setCarpooling($trajet);

                // Transaction vers la plateforme
                $transactionPlatform = new CreditTransaction();
                $transactionPlatform->setSender($userParticipant);
                $transactionPlatform->setReceiver($platformUser);
                $transactionPlatform->setAmount($platform);
                $transactionPlatform->setCarpooling($trajet);

                // Ajuster les crédits
                $userParticipant->setCredit($userParticipant->getCredit() - $amount);
                $chauffeur->setCredit($chauffeur->getCredit() + $chauffeurAmount);
                $platformUser->setCredit($platformUser->getCredit() + $platform);

                // Persist
                $em->persist($transactionChauffeur);
                $em->persist($transactionPlatform);
                $em->persist($userParticipant);
                $em->persist($chauffeur);
                $em->persist($platformUser);

                // Ajouter aux collections
                $chauffeur->addReceivedTransaction($transactionChauffeur);
                $userParticipant->addSentTransaction($transactionChauffeur);
                $platformUser->addReceivedTransaction($transactionPlatform);
                $userParticipant->addSentTransaction($transactionPlatform);
            }



    }
        $em->flush();
            return $this->redirectToRoute("currentjourney");

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

<<<<<<< HEAD
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
=======
            $em->remove($participant);
            $trajet->removeParticipant($participant);
            $trajet->setPassenger($trajet->getPassenger() + 1);
            $em->persist($trajet);
            }

$em->flush();

    return $this->redirectToRoute('currentjourney');


}
}
>>>>>>> dev
