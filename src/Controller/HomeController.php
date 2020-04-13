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
     * @Route("/", name="homepage") 
     */
    public function home(){
        $noms = [
            'Durand'=>'Visiteur',
            'Dupont'=>'Contributeur',
            'Astaire'=>'Membre',
            'Letellier'=>'Admin'
        ];
        return $this->render('home.html.twig',[
            'title'=>'Accueil',
            'acces'=>'visiteur',
            'tableau'=>$noms
        ]);
    }

    /**
     * Montre la page qui salue l'utilisateur
     * @Route("/hello/{nom}", name="hello-utilisateur")
     * @Route("/hello", name="hello-base")
     * @Route("/profil/{nom}/acces/{acces}", name="hello-profil")
     * @return void
     */
    public function hello($nom="anonyme",$acces="visiteur"){
        return $this->render("hello.html.twig",[
            'title' => 'Page de profil',
            'name' => $nom,
            'acces' => $acces
        ]);
    }
}