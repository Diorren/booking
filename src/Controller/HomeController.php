<?php

// namespace : chemin du controller

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// Pour créer une page : 1- une fonction publique (classe)  2- une route  3- une réponse

class HomeController extends AbstractController{

    /**
     * Création de notre 1ère route
     * @Route("/")
     * 
     */
    public function home(){

        return new Response("<h1>Ma première page</h1>");
    }
}