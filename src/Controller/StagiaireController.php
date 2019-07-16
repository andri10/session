<?php

namespace App\Controller;

use App\Entity\Stagiaire;
use App\Repository\StagiaireRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StagiaireController extends AbstractController
{
    /**
     * @Route("/stagiaire", name="stagiaire")
     */
    public function index(StagiaireRepository $repo)
    {

        $stagiaire = $repo->findAll();

        return $this->render('stagiaire/index.html.twig', [
            'controller_name' => 'Liste des stagiaires',
            'stagiaires' => $stagiaire
        ]);
    }

    /**
     * @Route("/stagiaire/{id}", name="show_stagiaire")
     */
    public function show(Stagiaire $stagiaire)
    {
        
        return $this->render('stagiaire/show.html.twig', [
            'stagiaire' => $stagiaire
        ]);
    }


}
