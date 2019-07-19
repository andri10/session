<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Entity\Stagiaire;
use App\Form\FormationType;
use App\Repository\FormationRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/formation")
 */
class FormationController extends AbstractController
{
    /**
     * @Route("/", name="formation")
     */
    public function index(FormationRepository $repo)
    {
        $formations = $repo->findAll();

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
     * @Route("/{id}/addstagiaire", name="addStagiaire_formation")
     */
    public function addStagiaire(Formation $formation, Request $request, ObjectManager $manager)
    {

        $form = $this->createFormBuilder($formation)
                     /* ->add('NbPlace', IntegerType::class, [
                        "attr" => [
                            "maxNb" => $formation->getNbPlace(),
                            "disabled" => "disabled"
                        ]
                    ]) */
                     ->add('stagiaires', CollectionType::class, [
                        'entry_type' => EntityType::class,
                        'entry_options' => [
                            'label' => "choisir :",
                            'class' => Stagiaire::class,
                            'choice_label' => "nomPrenom"
                        ],
                        'allow_add' => true,
                        'allow_delete' => true,
                    ])
                     ->add('save', SubmitType::class, ['label' => 'ADD'])
                     ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager->persist($formation);
            $manager->flush();

            return $this->redirectToRoute('show_formation', [
                'id' => $formation->getId()
            ]);
        }

        return $this->render('formation/addStagiaire.html.twig', [
            'form' => $form->createView(),
            'formation' => $formation
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
