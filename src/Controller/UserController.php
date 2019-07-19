<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
     * @Route("/{id}", name="user")
     */
    public function show(User $user)
    {
        $users = $this->getDoctrine()
        ->getRepository(User::class)
        ->findAll();

        return $this->render('user/show.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete_user")
     */
    public function delete(ObjectManager $manager, User $user){
        $manager->remove($user);
        $manager->flush();

        return $this->redirectToRoute('get_all_users');
    }

}
