<?php

namespace App\Controller;

use App\Entity\Stagiaire;
use App\Form\StagiaireType;
use App\Repository\StagiaireRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/stagiaire")
 */
class StagiaireController extends AbstractController
{
    /**
     * @Route("/", name="stagiaire")
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
     * @Route("/add", name="add_stagiaire")
     * @Route("/edit/{id}", name="edit_stagiaire")
     */
    public function addOrEdit(Stagiaire $stagiaire = null, Request $request, ObjectManager $manager)
    {
        if (!$stagiaire) {
            $stagiaire = new Stagiaire();
        }

        $form = $this->createForm(StagiaireType::class, $stagiaire);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($stagiaire);
            $manager->flush();

            return $this->redirectToRoute('show_stagiaire', [
                'id' => $stagiaire->getId()
            ]);
        }

        return $this->render('stagiaire/add_edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete_stagiaire")
     */
    public function delete(Stagiaire $stagiaire, ObjectManager $manager)
    {
        $manager->remove($stagiaire);
        $manager->flush();

        return $this->redirectToRoute('liste_stagiaire');
    }

    /**
     * @Route("/{id}", name="show_stagiaire")
     */
    public function show(Stagiaire $stagiaire)
    {

        return $this->render('stagiaire/show.html.twig', [
            'stagiaire' => $stagiaire
        ]);
    }



}
