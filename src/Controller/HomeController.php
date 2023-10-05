<?php

namespace App\Controller;

use App\Entity\Emprunt;
use App\Entity\Livre;
use App\Repository\LivreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/')]
class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home_index', methods: ['GET','POST'])]
    public function index(LivreRepository $livreRepository): Response
    {
        return $this->render('home/index.html.twig', [
            'livres' => $livreRepository->findAll(),
        ]);
    }

    #[Route('/livre/{id}', name: 'app_home_show', methods: ['GET'])]
    public function show(Livre $livre, Emprunt $emprunt): Response
    {
        return $this->render('home/show.html.twig', [
            'livre' => $livre,
            'emprunt' => $emprunt,
        ]);
    }
}
