<?php

namespace App\Controller;

use App\Form\SearchCarpoolingType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\CarpoolingRepository;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class HomeController extends AbstractController
{
    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
    ){}

    // Page d'accueil avec formulaire
    #[Route('/home', name: 'home')]
    public function index(Request $request, CarpoolingRepository $carpoolingRepository): Response
    {
        $form = $this->createForm(SearchCarpoolingType::class,null,[
            'method' => 'GET',
            'action' => $this->urlGenerator->generate('searchcarpool'),
        ]);

        return $this->render('home.html.twig', [
            'form' => $form->createView(),
            'trajets' => [],
        ]);
    }

#[Route('/homesearch', name: 'homesearch' ,methods: [ 'GET'])]
    public function search(Request $request, CarpoolingRepository $carpoolingRepository): Response
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
            return $this->redirectToRoute('searchcarpool');
        }
        return $this->render('searchcarpool.html.twig', [
            'form' => $form->createView(),
            'trajets' => $trajets,
            'user'=> $user
        ]);
    }


}
