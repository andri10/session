<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\Categorie;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="get_all_users")
     */
    public function index()
    {
        $users = $this->getDoctrine()
        ->getRepository(User::class)
        ->findAll();

        return $this->render('user/index.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route("/add", name="add_users")
     */
    public function addUser(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $passwordEncoder){

        $user = new User();
        $form = $this->createForm(UserType::class, $user)
        ->add('submit', SubmitType::class, ['label'=>'Ajouter', 'attr'=>['class'=>'btn-primary btn-block']]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            $manager->persist($user);
            $manager->flush();
            $this->addFlash('success', 'Votre formateur a bien été créé.');

            return $this->redirectToRoute('get_all_users');
        }

        return $this->render('user/add.html.twig', [
            'controller_name' => 'UserController',
            'form' => $form->createView()
        ]);
    }

    /**
     * * @Route("/edit", name="edit_users")
     */
    public function editUser(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $passwordEncoder){

        $form = $this->createForm(UserType::class, /* Permet à l'user de se modifier uniquement*/ $this->getUser())
        ->add('submit', SubmitType::class, ['label'=>'Modifier', 'attr'=>['class'=>'btn-primary btn-block']])
        ->remove('email')
        ->remove('plainPassword');
        $this->getUser()->setPlainPassword("danstoncul");
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager->flush();
            $this->addFlash('success', 'Le formateur a bien été modifié.');

            return $this->redirectToRoute('get_all_users');
        }

        return $this->render('user/edit.html.twig', [
            'controller_name' => 'UserController',
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/addcategorie", name="addCategorie_user")
     */
    public function addCategoriee(User $user, Request $request, ObjectManager $manager)
    {
        
        $form = $this->createFormBuilder($user)
                     ->add('categories', CollectionType::class, [
                        'entry_type' => EntityType::class,
                        'entry_options' => [
                            'label' => "choisir :",
                            'class' => Categorie::class,
                            'choice_label' => "intitule"
                        ],
                        'allow_add' => true,
                        'allow_delete' => true,
                    ])
                     ->add('save', SubmitType::class, ['label' => 'ADD'])
                     ->getForm();

        $this->getUser()->setPlainPassword("danstoncul");
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('user', [
                'id' => $user->getId()
            ]);
        }

        return $this->render('user/add_categorie.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }

    /**
     * @Route("/{id}/delete/categorie", name="deleteCategorie_user")
     */
    public function deleteCategorie(ObjectManager $manager, Categorie $categorie, UserInterface $user) {

        $categorie->removeUser($user);

        $manager->flush();

        return $this->redirectToRoute('user', [
            'id' => $user->getId()
        ]);

    }

    /**
     * @Route("/{id}/delete", name="delete_user")
     */
    public function delete(ObjectManager $manager, User $user){

        $manager->remove($user);
        $manager->flush();

        return $this->redirectToRoute('get_all_users');
    }

    /**
     * @Route("/{id}", name="user")
     */
    public function show(User $user, $id)
    {
        $users = $this->getDoctrine()
        ->getRepository(User::class)
        ->find($id);

        return $this->render('user/show.html.twig', [
            'user' => $user
        ]);
    }

}
