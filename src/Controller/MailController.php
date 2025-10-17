<?php
namespace App\Controller;

use App\Entity\Carpooling;
use Symfony\Component\Mime\Email;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MailController extends AbstractController
{
    #[Route('/send-mail/{id}', name: 'send_mail')]
    public function index(int $id, EntityManagerInterface $em, MailerInterface $mailer): Response
    {
        // 🔹 1. Récupération du trajet
        $trajet = $em->getRepository(Carpooling::class)->find($id);

        if (!$trajet || count($trajet->getParticipants()) === 0) {
            $this->addFlash('error', 'Aucun participant trouvé pour ce trajet.');
            return $this->redirectToRoute('myaccount');
        }

        $participants = $trajet->getParticipants();
        
        // 🔹 3. Envoi du mail à chaque participant
        foreach ($participants as $participant) {
            $user = $participant->getUser();
            if ($user && $user->getEmail()) {
                $email = (new Email())
                    ->from('gonzalesalexis999@gmail.com')
                    ->to($user->getEmail())
                    ->subject('Confirmation de trajet 🚗')
                    ->text(sprintf(
                        "Bonjour %s,\nVotre trajet de %s à %s est maintenant terminé.\nMerci d’avoir utilisé EcoRide !",
                        $user->getName(),
                        $trajet->getStartTown(),
                        $trajet->getEndTown()
                    ));

                $mailer->send($email);
            }
        }

        // 🔹 4. Réponse finale après la boucle
        return new Response('✅ E-mail envoyé à tous les participants !');
    }
}
