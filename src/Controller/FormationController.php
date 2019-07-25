<?php

namespace App\Controller;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Duree;
use App\Entity\Salle;
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

// Include Dompdf required namespaces
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
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
    {   // Findall récupère tous les champs de la table $formations
        $formations = $repo->findAll();
        // Renvoie sur la vue demandée
        return $this->render('formation/index.html.twig', [
            'controller_name' => 'FormationController',
            // Permet d'utiliser dans twig la varibale $formations défini plus haut
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
        // Si $formation n'existe, on demande de créer un new objet formation avec tous ces champs
        if(!$formation) {
            $formation = new Formation();
        }

        // Création d'un formulaire reprenant le form créé dans FormationType avec en paramètre les données de l'Entity Formation
        $form = $this->createForm(FormationType::class, $formation);

        // Permet de garder la requete en attendant de valider le formulaire dans GET ou POST
        $form->handleRequest($request);

        // Récupère dans le form les données de Date début et fin
        $dateD = $form['dateDebut']->getData();
        $dateF = $form['dateFin']->getData();

        // Si tout est remplis et valider ...
        if ($form->isSubmitted() && $form->isValid()) {
            // Si date fin est inférieur a date début donc affiche erreur
            if($dateF < $dateD) {
                $this->addFlash('danger', 'La date de debut de session doit être supérieur à la date fin de session.');
                // Et reste sur la vue actuelle du formulaire
                return $this->render('formation/add_edit.html.twig', [
                    'form' => $form->createView()
                ]);
            }
            // Si la formation existe, il met à jour les données moidifiées
            if($formation){
                $this->addFlash('success', 'La session a bien été modifiée.');
                // Sinon créé un nouvel obket formation
            } else {
                $this->addFlash('success', 'La session a bien été crée.');
            }
            // Prépare la requete
            $manager->persist($formation);
            // Execute la requete
            $manager->flush();
            // Si tout OK, reirige vers la vue de l'objet créé ou modifié
            return $this->redirectToRoute('show_formation', [
                'id' => $formation->getId()
            ]);
        }
        // Renvoi sur la vue du formulaire
        return $this->render('formation/add_edit.html.twig', [
            'form' => $form->createView(),
            // Permet de savoir si l'objet existe ou non
            'editMode' => $formation->getId() !== null
        ]);
    }

    /**
     * @Route("/{id}/addstagiaire", name="addStagiaire_formation")
     */
    public function addStagiaire(Formation $formation, Request $request, ObjectManager $manager)
    {
        
        $form = $this->createFormBuilder($formation)
                    // Permet d'ajouter plusieurs champs dans un form
                     ->add('stagiaires', CollectionType::class, [
                         // Défini le type de sélection (Ici on utilise une Entity)
                        'entry_type' => EntityType::class,
                        'entry_options' => [
                            'label' => "choisir :",
                            // Défini exactement l'Entity utilisé
                            'class' => Stagiaire::class,
                            // Permet de trier les champs (ici ordre alpha pour nom & prenom)
                            'query_builder' => function (EntityRepository $er) {
                                return $er->createQueryBuilder('s')
                                    ->orderBy('s.nom, s.prenom', 'ASC');
                                    
                            },
                            // Se réfère au toString créé
                            'choice_label' => "nomPrenom",
                            // Qu'il n'applique la réference de CollectionType mais l'autre de l'entity
                            
                        ],
                        'by_reference' => false,
                        'allow_add' => true
                    ])
                     ->add('save', SubmitType::class, ['label' => 'ADD'])
                     ->getForm();
        // Permet de garder la requete en attendant de valider le formulaire dans GET ou POST
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

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
        // Création d'un formulaire à partir de celui créé dans ModulesType
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
     * @Route("/{id}/addsalle", name="addSalle_formation")
     */
    public function addSalle(Formation $formation, Request $request, ObjectManager $manager, Salle $salle)
    {

        $form = $this->createFormBuilder($formation)
                ->add('salle', EntityType::class, [
                    'class' => Salle::class,
                    'choice_label' => 'nom'
                ])
                ->add('save', SubmitType::class, [
                    'label'=> 'Valider'
                ])
                ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $formation->setSalle($salle);

            $manager->persist($formation);
            $manager->flush();

            $this->addFlash('success', 'add Salle OK.');

            return $this->redirectToRoute('show_formation', [
                'id' => $formation->getId()
            ]);
        }


        return $this->render('formation/addSalle.html.twig', [
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
        // Permet de supprimer le stagiaire de la formation
        $stagiaire->removeFormation($formation);
        // Execute la requete
        $manager->flush();
        // Affiche un bandeau avec message quand ça fonctionne
        $this->addFlash('success', 'delete Stagiaire OK.');

        return $this->redirectToRoute('show_formation', [
            'id' => $formation->getId()
        ]);
    }

    /**
     * @Route("/delete", name="delete_formation")
     */
    public function delete(Request $request, ObjectManager $manager)
    {
        $id = $request->query->get("id");
        $formation = $manager->getRepository(Formation::class)->findOneBy(['id' => $id]);
        // Supprime une formation
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
        // Permet de récupérer la collection durees de l'Entity formation
        $durees = $formation->getDurees();
        // Déclaration d'un variable 
        $categories = [];
        // On parcours la collection durees
        foreach ($durees as $duree) { 
            // Si la condition $catégories n'existe pas dans durée...
            if(!in_array($duree->getModule()->getCategorie(), $categories)) {
                // ALors on  met dans le tableau $categories, en clé l'id de la catégorie en relation avec la durée et en valeur les modules(tableau associatif) tableau multidimensionnels qui aura les modules
                $categories[$duree->getModule()->getCategorie()->getIntitule()][] = $duree;
            }
        }

        return $this->render('formation/show.html.twig', [
            'formation' => $formation,
            'modulesByCat' => $categories
        ]);
    }
}
