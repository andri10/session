<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

/** @Route("/admin") */
class AdminController extends Controller {

    /**
     * @Route("/")
     */
    public function index() {
        return $this->render('admin/index.html.twig', ['mainNavAdmin' => true, 'title' => 'Espace Admin']);
    }

}