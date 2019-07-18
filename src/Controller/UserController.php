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


class UserController extends AbstractController
{
    /**
     * @Route("/user", name="get_all_users")
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
     * @Route("/user/add", name="add_users")
     * @Route("/user/edit/{id}", name="edit_users")
     */
    public function addUser(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $passwordEncoder, User $user = null){
        if(!$user){
            $user = new User();
            $form = $this->createForm(UserType::class, $user)
        ->add('submit', SubmitType::class, ['label'=>'Ajouter', 'attr'=>['class'=>'btn-primary btn-block']]);
        }
        else{
        $form = $this->createForm(UserType::class, $user)
        ->add('submit', SubmitType::class, ['label'=>'Modifier', 'attr'=>['class'=>'btn-primary btn-block']])
        ->remove('plainPassword');
    }
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            if($request->request->get("plainPassword")){
                $pass = $form->get("plainPassword")->getData();
                $password = $passwordEncoder->encodePassword($user, $pass);
                $user->setPassword($password);
            }

            $manager->persist($user);
            $manager->flush();
            $this->addFlash('success', 'Votre formateur à bien été créé.');

            return $this->redirectToRoute('get_all_users');
        }

        return $this->render('user/add.html.twig', [
            'controller_name' => 'UserController',
            'form' => $form->createView(),
            'editMode' => $user->getId() !== null
        ]);
    }

    /**
     * @Route("/user/{id}", name="user")
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
