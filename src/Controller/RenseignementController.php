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
    public function index(Carpooling $trajet,):response
    {

        return $this->render('renseignement.html.twig', [
            'user'=>$this->getUser(),
            'trajet'=>$trajet,

        ]);
    }

    

}
