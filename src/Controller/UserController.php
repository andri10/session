<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


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
}
