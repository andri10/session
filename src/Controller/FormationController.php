<?php

namespace App\Controller;

use App\Entity\Duree;
use App\Entity\Formation;
use App\Entity\Stagiaire;
use App\Form\ModulesType;
use App\Form\FormationType;
use App\Repository\FormationRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;

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
     * @Route("/{id}/edit", name="edit_formation")
     */
    public function add(Formation $formation = null, Request $request, ObjectManager $manager)
    {
        if(!$formation) {
            $formation = new Formation();
        }

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
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/addstagiaire", name="addStagiaire_formation")
     */
    public function addStagiaire(Formation $formation, Request $request, ObjectManager $manager)
    {
        
        $form = $this->createFormBuilder($formation)
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
     * @Route("/{id}/addmodule", name="addModule_formation")
     */
    public function addModule(Formation $formation, Request $request, ObjectManager $manager)
    {

        $form = $this->createForm(ModulesType::class, $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager->persist($formation);
            $manager->flush();

            return $this->redirectToRoute('show_formation', [
                'id' => $formation->getId()
            ]);
        }


        return $this->render('formation/addModule.html.twig', [
            'form' => $form->createView(),
            'formation' => $formation
        ]);
    }

    /**
     * @Route("/{idformation}/{idstagiaire}/delete/stagiaire", name="deleteStagaire_formation")
     * @Entity("stagiaire", expr="repository.find(idstagiaire)")
     */
    public function deleteStagaire(ObjectManager $manager, Formation $formation, Stagiaire $stagiaire)
    {
        
        $stagiaire->removeFormation($formation);

        $manager->flush();

        return $this->redirectToRoute('show_formation', [
            'id' => $formation->getId()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete_formation")
     */
    public function delete(Formation $formation, ObjectManager $manager)
    {
        $manager->remove($formation);
        $manager->flush();

        return $this->redirectToRoute('formation');
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
