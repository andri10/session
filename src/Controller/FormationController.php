<?php

namespace App\Controller;

use App\Entity\Module;
use App\Entity\Categorie;
use App\Entity\Formation;
use App\Repository\DureeRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/formation")
 */
class FormationController extends AbstractController
{
    /**
     * @Route("/liste", name="formation")
     */
    public function index()
    {
        $formations = $this->getDoctrine()
            ->getRepository(Formation::class)
            ->findAll();

        return $this->render('formation/index.html.twig', [
            'controller_name' => 'FormationController',
            'formations' => $formations
        ]);
    }

    /**
     * @Route("/{id}", name="show_formation")
     */
    public function show(Formation $formation, Module $module, DureeRepository $repo)
    {
        $duree = $repo->findAll();

        return $this->render('formation/show.html.twig', [
            'formation' => $formation,
            'module' => $module,
            'duree' => $duree,
        ]);
    }
}
