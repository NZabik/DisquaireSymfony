<?php

namespace App\Controller;

use App\Entity\Disque;
use App\Form\DisqueType;
use App\Repository\DisqueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/disque')]
class DisqueController extends AbstractController
{
    #[Route('/', name: 'app_disque_index', methods: ['GET'])]
    public function index(DisqueRepository $disqueRepository): Response
    {
        return $this->render('disque/index.html.twig', [
            'disques' => $disqueRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_disque_show', methods: ['GET'])]
    public function show(Disque $disque): Response
    {
        return $this->render('disque/show.html.twig', [
            'disque' => $disque,
        ]);
    }
}
