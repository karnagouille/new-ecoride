<?php

namespace App\Controller;

use App\Entity\Car;
use App\Form\NewcarType;
use App\Form\ProfileFormType;
use App\Repository\CarpoolingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


final class MyaccountController extends AbstractController
{


    #[Route('/profile', name: 'profile_show')]
    public function profileshow(): Response
    { 
         /** @var \App\Entity\User $user */
        $user = $this->getUser(); 


        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour accéder à cette page.');
        }

        return $this->render('myaccount/myaccount.html.twig', [
        'user' => $user,

    ]);

    }


#[Route('/profil/{id}/edit', name: 'profile_edit',methods:['GET','POST'])]
    public function profileedit(Request $request,EntityManagerInterface $em,SluggerInterface $slugger,CarpoolingRepository $carpoolingRepository,#[Autowire('%uploads_directory%')] string $uploadDirectory): Response
    { 

          /** @var \App\Entity\User $user */
        $user = $this->getUser(); 

        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour accéder à cette page.');
        }

        $profileform = $this->createForm(ProfileFormType::class, $user, [
        'csrf_token_id' => 'profile_form',
        ]);
        $profileform->handleRequest($request);

        if ($profileform->isSubmitted() && $profileform->isValid()) {
            /** @var UploadedFile $photo */
            $photoFile = $profileform->get('photo')->getData();

            
            if ($photoFile) {
                $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$photoFile->guessExtension();

                try {
                    $photoFile->move($uploadDirectory, $newFilename);
                } catch (FileException $e) {
                                    }

                $user->setPhoto($newFilename);
                $em->flush();
            }
            return $this->redirectToRoute('myaccount');
            }
        return $this->render('myaccount/profile.html.twig', [
            'profileform' => $profileform->createView(),
            'user' => $user,
        ]);
    }




    #[Route('/myaccount', name: 'myaccount',methods:['GET','POST'])]
    public function index(Request $request,EntityManagerInterface $em,SluggerInterface $slugger,CarpoolingRepository $carpoolingRepository,#[Autowire('%uploads_directory%')] string $uploadDirectory): Response
{

        $car = new Car();    
        $form = $this->createForm(NewcarType::class, $car, [
        'csrf_token_id' => 'new_car_form',
        ]);

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
        
        return $this->redirectToRoute('myaccount');
    }
    $user = $this->getUser();
    $trajets = $carpoolingRepository->findByUserOrParticipation($user);

    $cars = $em->getRepository(Car::class)->findBy([
        'user'=>$user,
    ]);
            $user = $this->getUser();




    return $this->render('myaccount/myaccount.html.twig',[
        
        'form'=>$form->createView(),
        'cars'=>$cars,
        'car'=>$car,
        'user'=>$user,
        'trajets' => $trajets,
        ]);
    }






                                // Formulaire Voiture


    #[Route('/car/{slug}', name: 'car_show')]
public function show(Car $car, EntityManagerInterface $em, string $slug): Response
{
    $car = $em->getRepository(Car::class)->findOneBy(['slug' => $slug]);

    if (!$car) {
        throw $this->createNotFoundException('Voiture introuvable.');
    }

    return $this->redirectToRoute('car_edit', ['id' => $car->getId()]);
    
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

#[Route('/car/{id}/edit', name: 'car_edit',)]
public function edit(Request $request, Car $car, EntityManagerInterface $em): Response
{ 

    $form = $this->createForm(NewcarType::class, $car);
    $form->handleRequest($request);

    if($form->isSubmitted()&& $form->isValid()){

        $brandName = $form->get('brand')->getData();
        $brand = $em->getRepository(\App\Entity\Brand::class)->findOneBy(['libelle' => $brandName]);
        if (!$brand) {
            $brand = new \App\Entity\Brand();
            $brand->setLibelle($brandName);
            $em->persist($brand);
        }

        $car->setBrand($brand);
        $em->flush();
    }
        return $this->render('myaccount/mycars.html.twig', [
        'form' => $form->createView(),
        'car' => $car,
        ]);
    }



}


