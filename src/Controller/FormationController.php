<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Form\FormationType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/formation")
 */
class FormationController extends AbstractController
{
    /**
     * @Route("/", name="formation")
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
     * @Route("/add", name="add_formation")
     */
    public function add(Request $request, ObjectManager $manager)
    {

        $formation = new Formation();

        $form = $this->createForm(FormationType::class, $formation);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager->persist($formation);
            $manager->flush();

            return $this->redirectToRoute('show_formation', [
                'id' => $formation->getId()
            ]);
        }

        return $this->render('formation/add_edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/addstagiaire", name="addStagiaire_formation")
     */
    public function addStagiaire(Request $request, ObjectManager $manager)
    {

        $form = $this->createFormBuilder(FormationType::class, $formation);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager->persist($formation);
            $manager->flush();

            return $this->redirectToRoute('show_formation', [
                'id' => $formation->getId()
            ]);
        }

        return $this->render('formation/add_edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="show_formation")
     */
    public function show(Formation $formation)
    {
        $durees = $formation->getDurees();
        $categories = [];

        foreach ($durees as $duree) { 
            if(!in_array($duree->getModule()->getCategorie(), $categories)) {
                $categories[$duree->getModule()->getCategorie()->getIntitule()][] = $duree;
            }
        }

        return $this->render('formation/show.html.twig', [
            'formation' => $formation,
            'modulesByCat' => $categories
        ]);
    }
}
