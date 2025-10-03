<?php

namespace App\Controller;

use App\Entity\Carpooling;
use App\Form\Carpooling1Type;
use App\Repository\CarpoolingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/startbutton')]
final class StartbuttonController extends AbstractController
{
    #[Route(name: 'app_startbutton_index', methods: ['GET'])]
    public function index(CarpoolingRepository $carpoolingRepository): Response
    {
        return $this->render('startbutton/index.html.twig', [
            'carpoolings' => $carpoolingRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_startbutton_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $carpooling = new Carpooling();
        $form = $this->createForm(Carpooling1Type::class, $carpooling);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($carpooling);
            $entityManager->flush();

            return $this->redirectToRoute('app_startbutton_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('startbutton/new.html.twig', [
            'carpooling' => $carpooling,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_startbutton_show', methods: ['GET'])]
    public function show(Carpooling $carpooling): Response
    {
        return $this->render('startbutton/show.html.twig', [
            'carpooling' => $carpooling,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_startbutton_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Carpooling $carpooling, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Carpooling1Type::class, $carpooling);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_startbutton_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('startbutton/edit.html.twig', [
            'carpooling' => $carpooling,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_startbutton_delete', methods: ['POST'])]
    public function delete(Request $request, Carpooling $carpooling, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$carpooling->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($carpooling);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_startbutton_index', [], Response::HTTP_SEE_OTHER);
    }
}
