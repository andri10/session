<?php

namespace App\Controller;

use App\Entity\Salle;
use App\Form\SalleType;
use App\Entity\Ressource;
use App\Form\RessourceType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/salle")
 */
class SalleController extends AbstractController
{
    /**
     * @Route("/", name="salle")
     */
    public function index()
    {
        $salles = $this->getDoctrine()
        ->getRepository(Salle::class)
        ->findAll();

        return $this->render('salle/index.html.twig', [
            'controller_name' => 'SalleController',
            'salles' => $salles
        ]);
    }

    /**
     * @Route("/add", name="add_salle")
     */
    public function addSalle(Request $request, ObjectManager $manager){

        $salle = new Salle();

        $form = $this->createForm(SalleType::class, $salle)
                     ->add('submit', SubmitType::class, [
                         'label'=>'Ajouter',
                         'attr'=>['class'=>'btn-primary btn-block']]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager->persist($salle);
            $manager->flush();

            $this->addFlash('success', 'Votre salle a bien été créée.');

            return $this->redirectToRoute('show_salle', [
                "id" => $salle->getId()
            ]);
        }

        return $this->render('salle/add_edit.html.twig', [
            'controller_name' => 'SalleController',
            'form' => $form->createView()
        ]);
    }

          /**
     * @Route("/{id}/ressource/add", name="add_ressource")
     */
    public function addRessource(Salle $salle, Request $request, ObjectManager $manager)
    {

        $ressource = new Ressource();

        $form = $this->createForm(RessourceType::class, $ressource)
        ->add('submit', SubmitType::class, ['label'=>'Ajouter', 'attr'=>['class'=>'btn-primary btn-block']]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $ressource->setSalle($salle);

            $manager->persist($ressource);
            $manager->flush();

            return $this->redirectToRoute('show_salle', [
                'id' => $salle->getId()
            ]);
        }

        return $this->render('salle/addRessource.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/delete/{id}", name="delete_salle")
     */
    public function deleteSalle(ObjectManager $manager, Salle $salle){
        $manager->remove($salle);
        $manager->flush();

        return $this->redirectToRoute('salle');
    }

    /**
     * @Route("/{id}", name="show_salle")
     */
    public function show(Salle $salle)
    {
        $salles = $this->getDoctrine()
        ->getRepository(Salle::class)
        ->findAll();

        return $this->render('salle/show.html.twig', [
            'salle' => $salle
        ]);
    }
}
