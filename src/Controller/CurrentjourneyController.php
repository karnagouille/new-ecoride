<?php

namespace App\Controller;

use App\Entity\Carpooling;
use Doctrine\ORM\Mapping\Id;
use App\Repository\CarpoolingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;


final class CurrentjourneyController extends AbstractController
{
    #[Route('/currentjourney', name: 'currentjourney')]
    public function index(CarpoolingRepository $carpoolingRepository): Response
    {
        

        $user = $this->getUser();
        $trajets = $carpoolingRepository->findByUserOrParticipation($this->getUser());

        return $this->render('route/currentjourney.html.twig',[
        'user'=>$user, 
        'trajets'=>$trajets,
        ]);
        }

 // Changement de statut et vérification de l'utilisateur connecté
        #[Route('/trajet/{id}/changer-statut', name: 'change_statut')]
    public function changerStatut(Carpooling $trajet, EntityManagerInterface $em,MailerInterface $mailer): Response
        {
            if ($trajet->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
    }

        if ($trajet->getStatut() === Carpooling::STATUT_RIEN || $trajet->getStatut() === null) {
            $trajet->setStatut(Carpooling::STATUT_EN_COURS);
<<<<<<< Updated upstream
        } elseif ($trajet->getStatut() === Carpooling::STATUT_EN_COURS) {
            $trajet->setStatut(Carpooling::STATUT_TERMINE);
        }
            $em->flush();
=======
            break;
        case Carpooling::STATUT_EN_COURS:

            $trajet->setStatut(Carpooling::STATUT_TERMINE);

            foreach ($trajet->getParticipants() as $participant) {
                $user = $participant->getUser();
            if ($user && $user->getEmail()) {

            $email = (new Email()) 
                ->from('gonzalesalexis999@gmail.com')
                ->to($user->getEmail())
                ->subject('Confirmation de trajet 🚗')
                ->text(sprintf(
                    "Bonjour %s,\nVotre trajet de %s à %s est maintenant terminé.\n N'hésitez pas à laisser un commentaire\nMerci d’avoir utilisé EcoRide !",
                    $user->getName(),
                    $trajet->getStartTown(),
                    $trajet->getEndTown()
                ));
            $mailer->send($email);
        }
    }
            break; 
        }
        $em->flush();
        return $this->redirectToRoute('currentjourney');
>>>>>>> Stashed changes

    // Redirection vers la page principale des trajets
    return $this->render('currentjourney');
}



}