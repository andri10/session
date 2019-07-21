<?php

namespace App\Controller;

use App\Entity\Module;
use App\Form\ModuleType;
use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/categorie")
 */
class CategorieController extends AbstractController
{
    /**
     * @Route("/", name="categorie")
     */
    public function index()
    {
        $categories = $this->getDoctrine()
        ->getRepository(Categorie::class)
        ->findAll();

        return $this->render('categorie/index.html.twig', [
            'controller_name' => 'CategorieController',
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/add", name="add_categorie")
     */
    public function addCategorie(Request $request, ObjectManager $manager){

        $categorie = new Categorie();

        $form = $this->createForm(CategorieType::class, $categorie)
                     ->add('submit', SubmitType::class, [
                         'label'=>'Ajouter',
                         'attr'=>['class'=>'btn-primary btn-block']]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager->persist($categorie);
            $manager->flush();

            $this->addFlash('success', 'Votre catégorie a bien été créée.');

            return $this->redirectToRoute('show_categorie', [
                "id" => $categorie->getId()
            ]);
        }

        return $this->render('categorie/add_edit.html.twig', [
            'controller_name' => 'CategorieController',
            'form' => $form->createView()
        ]);
    }

      /**
     * @Route("/{id}/module/add", name="add_module")
     */
    public function addModule(Categorie $categorie, Request $request, ObjectManager $manager)
    {

        $module = new Module();

        $form = $this->createForm(ModuleType::class, $module)
        ->add('submit', SubmitType::class, ['label'=>'Ajouter', 'attr'=>['class'=>'btn-primary btn-block']]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $module->setCategorie($categorie);

            $manager->persist($module);
            $manager->flush();

            return $this->redirectToRoute('show_categorie', [
                'id' => $categorie->getId()
            ]);
        }

        return $this->render('categorie/addModule.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    

    /**
     * @Route("/delete/module/{id}", name="delete_module")
     */
    public function deleteModule(ObjectManager $manager, Module $module){

        $manager->remove($module);
        $manager->flush();

        return $this->redirectToRoute('show_categorie', [
            'id' => $module->getCategorie()->getId()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete_categorie")
     */
    public function deleteCategorie(ObjectManager $manager, Categorie $categorie){
        $manager->remove($categorie);
        $manager->flush();

        return $this->redirectToRoute('categorie');
    }

    /**
     * @Route("/{id}", name="show_categorie")
     */
    public function show(Categorie $categorie)
    {
        $categories = $this->getDoctrine()
        ->getRepository(Categorie::class)
        ->findAll();

        return $this->render('categorie/show.html.twig', [
            'categorie' => $categorie
        ]);
    }

}