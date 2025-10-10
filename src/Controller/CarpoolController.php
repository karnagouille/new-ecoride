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
            $priceOrder = $form->get('price')->getData();
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
        if ($trajet->getStatut() === Carpooling::STATUT_RIEN || $trajet->getStatut() === null) {
            $trajet->setStatut(Carpooling::STATUT_EN_COURS);
        } elseif ($trajet->getStatut() === Carpooling::STATUT_EN_COURS) {
            $trajet->setStatut(Carpooling::STATUT_TERMINE);
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
            $em->persist($participant);

    
        if ($trajet->getPassenger() > 0) {
            $trajet->setPassenger($trajet->getPassenger() - 1);
}
            $em->flush();

        return $this->redirectToRoute('currentjourney');

}


}
