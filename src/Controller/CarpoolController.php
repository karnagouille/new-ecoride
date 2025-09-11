<?php

namespace App\Controller;

use App\Entity\Carpooling;
use App\Form\CarpoolingType;
use App\Repository\CarpoolingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class CarpoolController extends AbstractController
{
    #[Route('/carpool', name: 'carpool')]
    public function index(Request $request, EntityManagerInterface $em, CarpoolingRepository $carpoolingRepository): Response
    {
    
        $form = $this->createForm(CarpoolingType::class);
        $form->handleRequest($request);

        $trajets =$carpoolingRepository->findAll();

        if ($form->isSubmitted() && $form->isValid()) {


            // Récupérer les filtres non mappés
            $priceOrder  = $form->get('price')->getData();
            $traveltime  = $form->get('traveltime')->getData();
            $electric    = $form->get('electric')->getData();
            $note        = $form->get('note')->getData();

            $trajets = array_filter($trajets, function($trajet) use ($traveltime, $electric, $note) {
                if ($traveltime && $trajet->getTraveltime() != $traveltime) return false;
                if ($electric !== null && $trajet->getElectric() != $electric) return false;
                if ($note && $trajet->getNote() < $note) return false;
                return true;
            });

            if ($priceOrder) {
                usort($trajets, function($a, $b) use ($priceOrder) {
                    $prixA = floatval($a->getPrice());
                    $prixB = floatval($b->getPrice());
                    return ($priceOrder === 'asc') ? $prixA <=> $prixB : $prixB <=> $prixA;
                });
            }
        }
        return $this->render('carpool.html.twig', [
            'form' => $form->createView(),
            'trajets' => $trajets,
        ]);
    }
}
