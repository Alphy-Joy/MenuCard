<?php

namespace App\Controller;

use App\Repository\DishesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(DishesRepository $dr)
    { 
        $dish = $dr->findAll();
        $random = array_rand($dish,2);
        return $this->render('home/index.html.twig', [
            'random1' => $dish[$random[0]],
            'random2' => $dish[$random[1]]
        ]);
    }
}
