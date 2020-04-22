<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * Permet d'afficher la page de mon compte
     * @Route("/account", name="user_account")
     * @IsGranted("ROLE_USER")
     * @return Response
     */
    public function myAccount()
    {
        return $this->render('user/index.html.twig', [
            'user' => $this->getUser()
        ]);
    }

    /**
     * Affiche la liste des réservations de l'utilisateur
     * @Route("/user/bookings", name="user_bookings")
     * @IsGranted("ROLE_USER")
     * @return Response
     */
    public function bookings()
    {
        return $this->render('user/bookings.html.twig');
    }

    /**
     * Permet d'afficher le récapitulatif d'un paiement d'une réservation
     * @Route("/user/payment", name="user_payment")
     * @return Response
     */
    public function payment()
    {
        return $this->render('user/payment.html.twig');
    }
    
    /**
     * @Route("/user/{slug}", name="user_show")
     */
    public function index(User $user)
    {
        return $this->render('user/index.html.twig', [
            'user' => $user
        ]);
    }


}
