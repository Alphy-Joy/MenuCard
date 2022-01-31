<?php

namespace App\Controller;

use App\Entity\Dishes;
use App\Form\DishesType;
use App\Repository\DishesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

#[Route('/dishes', name: 'dishes.')]
class DishesController extends AbstractController
{
    #[Route('/', name: 'list')]
    public function index(DishesRepository $dr): Response
    {
        $dishes = $dr->findAll();
        return $this->render('dishes/index.html.twig', [
            'dishes' => $dishes,
        ]);
    }
    #[Route('/create', name: 'create')]
    public function create(Request $request){
        $dishes = new Dishes();
        //Form
        $form = $this->createForm(DishesType::class,$dishes);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            //Entity Manager
            $em = $this->getDoctrine()->getManager();
            $image = $request->files->get('dishes')['attachImage'];
            if($image){
                $fileName = md5(uniqid()).'.'.$image->guessClientExtension();
            }
            $image->move(
                $this->getParameter('image_folder'),
                $fileName
            );
            $dishes->setImage($fileName);
            $em->persist($dishes);
            $em->flush();
            return $this->redirect($this->generateUrl('dishes.list'));
        }
        
        //Response
        //return new Response("New dish has been added");

        return $this->render('dishes/create.html.twig', [
            'createForm' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete($id,DishesRepository $dr){
        $em = $this->getDoctrine()->getManager();
        $dish = $dr->find($id);
        $em->remove($dish);
        $em->flush();
        $this->addFlash('success','Dish deleted successfully');
        return $this->redirect($this->generateUrl('dishes.list'));

    }

    #[Route('/displayImg/{id}', name: 'displayImg')]

    public function displayImg(Dishes $dishes){
        return $this->render('dishes/display.html.twig', [
            'dishes' => $dishes,
        ]);
    }
}
