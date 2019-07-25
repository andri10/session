<?php

namespace App\Controller;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Duree;
use App\Entity\Formation;
use App\Entity\Stagiaire;
use App\Form\ModulesType;
use App\Form\FormationType;
use Doctrine\ORM\EntityRepository;
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

// Include Dompdf required namespaces
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
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
     * @Route("/stagiaire/{id}/pdf", name="pdf")
     */
    public function generationPdf(Stagiaire $stagiaire)
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('formation/mypdf.html.twig', [
            'title' => "Welcome to our PDF Test",
            'stagiaire' => $stagiaire
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => false
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

        $dateD = $form['dateDebut']->getData();
        $dateF = $form['dateFin']->getData();

        if ($form->isSubmitted() && $form->isValid()) {
            
            if($dateF < $dateD) {
                $this->addFlash('danger', 'La date de debut de session doit être supérieur à la date fin de session.');

                return $this->render('formation/add_edit.html.twig', [
                    'form' => $form->createView()
                ]);
            }

            if($formation){
                $this->addFlash('success', 'La session a bien été modifiée.');
            } else {
                $this->addFlash('success', 'La session a bien été crée.');
            }
        
            $manager->persist($formation);
            $manager->flush();

            return $this->redirectToRoute('show_formation', [
                'id' => $formation->getId()
            ]);
        }

        return $this->render('formation/add_edit.html.twig', [
            'form' => $form->createView(),
            'editMode' => $formation->getId() !== null
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
                            'query_builder' => function (EntityRepository $er) {
                                return $er->createQueryBuilder('s')
                                    ->orderBy('s.nom, s.prenom', 'ASC');
                                    
                            },
                            'choice_label' => "nomPrenom",
                            'by_reference' => false
                        ],
                        'allow_add' => true,
                        'allow_delete' => true,
                    ])
                     ->add('save', SubmitType::class, ['label' => 'ADD'])
                     ->getForm();
        
        $form->handleRequest($request);
        
        /*TODO:*/

        /* $newStagiaires = $form['stagiaires']->getData();
        
        $stagiaires = $formation->getStagiaires();

        dump($stagiaires);
        dump($newStagiaires); */


        if ($form->isSubmitted() && $form->isValid()) {

            /* if($newStagiaires !== $stagiaires) {
                $this->addFlash('danger', 'Peut pas avoir deux fois le même stagiaire.');

                return $this->render('formation/addStagiaire.html.twig', [
                    'form' => $form->createView(),
                    'formation' => $formation
                ]);
            } */

            $manager->persist($formation);
            $manager->flush();

            $this->addFlash('success', 'add Stagiaire OK.');

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

            $this->addFlash('success', 'add Module OK.');

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
     * @Route("/{id}//delete/stagiaire/{idstagiaire}", name="deleteStagaire_formation")
     * @Entity("stagiaire", expr="repository.find(idstagiaire)")
     */
    public function deleteStagaire(ObjectManager $manager, Formation $formation, Stagiaire $stagiaire)
    {
        
        $stagiaire->removeFormation($formation);

        $manager->flush();

        $this->addFlash('success', 'delete Stagiaire OK.');

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

        $this->addFlash('success', 'delete Formation OK.');

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
