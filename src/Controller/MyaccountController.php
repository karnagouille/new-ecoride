<?php

namespace App\Controller;

use App\Entity\Car;
use App\Form\NewcarType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\String\Slugger\SluggerInterface;

final class MyaccountController extends AbstractController
{
    #[Route('/myaccount', name: 'myaccount',methods:['GET','POST'])]
    public function index(Request $request,EntityManagerInterface $em,SluggerInterface $slugger): Response
{
    $car = new Car();    
    $form = $this->createForm(NewcarType::class,$car);
    $form->handleRequest($request);
    
    if($form->isSubmitted() && $form->isValid()){


        $brandName = $form->get('brand')->getData(); 
        $brand = $em->getRepository(\App\Entity\Brand::class)->findOneBy(['libelle' => $brandName]);

        if (!$brand) {

            $brand = new \App\Entity\Brand();
            $brand->setLibelle($brandName);
            $em->persist($brand);
        }

        $car->setBrand($brand); 

        $car->setUser($this->getUser());

        $slug = strtolower($slugger->slug($car->getModel() . '-' . uniqid()));
        $car->setSlug($slug);

        $em->persist($car);
        $em->flush();

        $this->addFlash('success', 'Formulaire enregistré !');
        return $this->redirectToRoute('myaccount');
    }
    $cars = $em->getRepository(Car::class)->findAll();

    return $this->render('myaccount/myaccount.html.twig',[
    
        'form'=>$form->createView(),
        'cars'=>$cars,
        'car'=>$car,
        ]);
    }

    #[Route('/car/{slug}', name: 'car_show')]
public function show(Car $car, EntityManagerInterface $em, string $slug): Response
{
    $car = $em->getRepository(Car::class)->findOneBy(['slug' => $slug]);

    if (!$car) {
        throw $this->createNotFoundException('Voiture introuvable.');
    }

    return $this->render('myaccount/mycars.html.twig', [
        'car' => $car
    ]);
}

#[Route('car/{id}/remove', name: 'car_remove',methods:['POST'])]
public function remove(EntityManagerInterface $em,Request $request,Car $car): Response
{

    if ($this->isCsrfTokenValid('delete'.$car->getId(),$request->request->get('_token')))
    {
        $em->remove($car);
        $em->flush();
    }
    return $this->redirectToRoute('myaccount');

}






}
