<?php

namespace App\Controller;

use App\Entity\Carpooling;
use App\Entity\Participant;
use App\Form\SearchCarpoolingType;
use App\Repository\CarpoolingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class CarpoolController extends AbstractController
{
    #[Route('/carpool', name: 'searchcarpool' ,methods: [ 'GET'])]
    public function index(Request $request, CarpoolingRepository $carpoolingRepository): Response
    {
        $user = $this->getuser();
        $form = $this->createForm(SearchCarpoolingType::class, null, [
            'method' => 'GET',
        ]);
        $form->handleRequest($request);


        $trajets = [];

        if ($form->isSubmitted() && $form->isValid()) {

            $carpooling = $form->getData(); 
            $priceOrder = $form->get('price')->getData(); // 'asc' ou 'desc'
            $travelTime = $form->get('traveltime')->getData();
            $isElectric = $form->get('electric')->getData();
            $note = $form->get('note')->getData();


            $trajets = $carpoolingRepository->searchCarpool(
                $carpooling->getStartTown(),
                $carpooling->getEndTown(),
                $carpooling->getPassenger(),
                $carpooling->getStartAt(),
                $carpooling->getHour(),
                $priceOrder,
                $travelTime,
                $isElectric,
                $note,
            );
        }
        
        return $this->render('searchcarpool.html.twig', [
            'form' => $form->createView(),
            'trajets' => $trajets,
            'user'=> $user
        ]);
    }

    #[Route('/trajet/{id}/changer-statut', name: 'changer_statut')]
    public function changerStatut(Carpooling $trajet, EntityManagerInterface $em): Response
        {


        switch ($trajet->getStatut()) {
        case Carpooling::STATUT_RIEN:
        case null:
            $trajet->setStatut(Carpooling::STATUT_EN_COURS);
            break;
        case Carpooling::STATUT_EN_COURS:
            $trajet->setStatut(Carpooling::STATUT_TERMINE);
            break;
        case Carpooling::STATUT_ANNULEE:
            $trajet->setStatut(Carpooling::STATUT_RIEN);
            break;
            
        }
        $em->flush();
        return $this->redirectToRoute('currentjourney');
    }

    #[Route('/trajet/{id}/participer', name: 'participer')]
    public function participer(EntityManagerInterface $em, int $id): Response
        {
            $trajet = $em->getRepository(Carpooling::class)->find($id);

        if(!$trajet){
                throw $this->createNotFoundException('
                Trajet non trouvé'.$id
            );
        }
            $participant = new Participant();
            $participant->setUser($this->getUser());
            $participant->setCarpooling($trajet);

            $transaction = $trajet->getCreditTransactions()->first();
            $amount = $transaction ? $transaction->getAmount() : 0; // 0 ou une valeur par défaut si aucune transaction

            
            if($participant->getUser()->getcredit() < $amount){
                $this->addFlash('error', "Crédits insuffisants : vous devez recharger.");

                
            return $this->redirectToRoute('currentjourney');
            }

            $em->persist($participant);

    
        if ($trajet->getPassenger() > 0) {
            $trajet->setPassenger($trajet->getPassenger() - 1);
}
            $em->flush();

        return $this->redirectToRoute('currentjourney');

}


}
