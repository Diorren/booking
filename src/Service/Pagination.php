<?php

namespace App\Service;

use Twig\Environment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class Pagination
{
    // 1 - Utiliser la pagination à partir de n'importe quelle entité (on doit préciser l'entité concernée)
    private $entityClass;
    private $limit = 10;
    private $currentPage = 1;
    private $manager;
    private $twig;
    private $route;
    private $templatePath;

    public function __construct(RequestStack $request,EntityManagerInterface $manager,Environment $twig,$templatePath)
    {
        $this->route = $request->getCurrentRequest()->attributes->get('_route');
        $this->manager = $manager;
        $this->twig = $twig;
        $this->templatePath = $templatePath;
    }

    public function display()
    {
        // Appelle le moteur twig et on précise quel template on veut Utiliser
        $this->twig->display($this->templatePath,[

            // options nécessaires à l'affichage des données
            // variables : route / page / pages
            'page' => $this->currentPage,
            'pages' => $this->getPages(),
            'route' => $this->route
        ]);
    }

    public function setEntityClass($entityClass)
    {
        // Ma donnée $entityClass = donnée qui va m'être envoyée
        $this->entityClass = $entityClass;
        return $this;
    }

    public function getEntityClass()
    {
        return $this->entityClass;
    }

    // 2 - Quelle est la limite ?
    public function getLimit()
    {
        return $this->limit;
    }

    public function setLimit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    // 3 - Sur quelle page je me trouve actuellement
    public function getPage()
    {
        return $this->currentPage;
    }

    public function setPage($page)
    {
        $this->currentPage = $page;
        return $this;
    }

    // 4 - On va chercher le nombre de pages au total pages
    public function getData()
    {
        if(empty($this->entityClass)){

            throw new \Exception("setEntityClass n'a pas été renseigné dans le controller correspondant");
        }
        
        // Calculer l'offset
        $offset = $this->currentPage * $this->limit - $this->limit;

        // Demande au repo de trouver les éléments
        // On va chercher le bon repository
        $repo = $this->manager->getRepository($this->entityClass);

        // On construit notre requête
        $data = $repo->findBy([],[],$this->limit,$offset);
        return $data;
    }

    public function getPages()
    {
        $repo = $this->manager->getRepository($this->entityClass);
        $total = count($repo->findAll());
        $pages = ceil($total / $this->limit);
        return $pages;
    }

    public function getRoute()
    {
        return $this->route;
    }

    public function setRoute($route)
    {
        $this->route = $route;
        return $this;
    }

    public function getTemplatePath()
    {
            return $this->templatePath;
    }

    public function setTemplatePath($templatePath)
    {
            $this->templatePath = $templatePath;

            return $this;
    }
}