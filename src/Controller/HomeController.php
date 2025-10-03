<?php

namespace App\Controller;

use App\Form\SearchCarpoolingType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\CarpoolingRepository;


final class HomeController extends AbstractController
{
    // Page d'accueil avec formulaire
    #[Route('/home', name: 'home')]
    public function index(): Response
    {
        $form = $this->createForm(SearchCarpoolingType::class);

        return $this->render('home.html.twig', [
            'form' => $form->createView(),
            'trajets' => [],
        ]);
    }

    // Page de résultats
    #[Route('/home/search', name: 'homesearchcarpool', methods: ['GET','POST'])]
    public function search(Request $request,CarpoolingRepository $carpoolingRepository ): Response
    {
    
        $form = $this->createForm(SearchCarpoolingType::class);
        $form->handleRequest($request);

        $trajets = [];

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $trajets =$carpoolingRepository->findAll();

            $trajets = $carpoolingRepository->searchCarpool(
            $data->getStartTown(),
            $data->getEndTown(),
            $data->getPassenger(),
            $data->getStartAt(),
            $data->getHour(),
            $data->getPrice(),
            $data->getTraveltime(),
            $data->getElectric(),
            $data->getNote()

            );
        }

        return $this->render('searchcarpool.html.twig', [
            'form' => $form->createView(),
            'trajets' => $trajets,
        ]);
    }
    
}
