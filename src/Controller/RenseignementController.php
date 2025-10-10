<?php

namespace App\Controller;

use App\Entity\Carpooling;
use App\Form\SearchCarpoolingType;
use App\Repository\CarpoolingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class RenseignementController extends AbstractController
{
    #[Route('/renseignement/{id}', name: 'renseignement',methods: [ 'GET'])]
    public function index(carpoolingRepository $carpoolingRepository, int $id):response
    { 
        
        $trajet = $carpoolingRepository->find($id);
        $conducteur = $trajet->getUser();



        return $this->render('renseignement.html.twig', [
            'conducteur'=>$conducteur,
            'trajet'=>$trajet,

        ]);
    }

    

}
