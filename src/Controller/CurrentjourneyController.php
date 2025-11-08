<?php

namespace App\Controller;

use App\Entity\Carpooling;
use App\Repository\CarpoolingRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ParticipantRepository;
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