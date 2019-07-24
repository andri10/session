<?php

namespace App\Controller;

use App\Entity\Salle;
use App\Form\SalleType;
use App\Entity\Ressource;
use App\Form\RessourceType;
use App\Form\RessourcesType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/ressource")
 */
class RessourceController extends AbstractController
{
    /**
     * @Route("/", name="ressource")
     */
    public function index()
    {
        $salles = $this->getDoctrine()
        ->getRepository(Salle::class)
        ->findAll();

        return $this->render('ressource/index.html.twig', [
            'controller_name' => 'SalleController',
            'salles' => $salles
        ]);
    }

    /**
     * @Route("/salle/add", name="add_salle")
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

        return $this->render('ressource/add_edit.html.twig', [
            'controller_name' => 'RessourceController',
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/addressource", name="addRessource_ressource")
     */
    public function addRessource(Salle $salle, Request $request, ObjectManager $manager)
    {

        $form = $this->createForm(RessourcesType::class, $salle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager->persist($salle);
            $manager->flush();

            $this->addFlash('success', 'add Ressource OK.');

            return $this->redirectToRoute('show_salle', [
                'id' => $salle->getId()
            ]);
        }


        return $this->render('ressource/addRessource.html.twig', [
            'form' => $form->createView(),
            'salle' => $salle
        ]);
    }


    /**
     * @Route("/delete/{id}", name="delete_salle")
     */
    public function deleteSalle(ObjectManager $manager, Salle $salle){
        $manager->remove($salle);
        $manager->flush();

        return $this->redirectToRoute('ressource');
    }

    /**
     * @Route("/{id}", name="show_salle")
     */
    public function show(Salle $salle)
    {
        $salles = $this->getDoctrine()
        ->getRepository(Salle::class)
        ->findAll();

        return $this->render('ressource/show.html.twig', [
            'salle' => $salle
        ]);
    }
}
