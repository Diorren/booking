<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfileType;
use App\Entity\PasswordUpdate;
use App\Form\PasswordUpdateType;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{
    /**
     * Permet d'afficher une page de connexion
     * @Route("/login", name="account_login")
     * @return Response
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();
        return $this->render('account/login.html.twig', [
            'hasError' => $error !== null,
            'username' => $username
        ]);
    }

    /**
     * Permet de se déconnecter
     * @Route("/logout", name="account_logout")
     * @return void
     */
    public function logout(){ 
        // Tout se passe dans le fichier security.yaml 
    }

    /**
     * Permet d'afficher une page pour s'inscrire
     * @Route("/register", name="account_register")
     * @return Response
     */
    public function register(Request $request,EntityManagerInterface $manager,UserPasswordEncoderInterface $encoder)
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On encode the password
            $user->setHash(
                $encoder->encodePassword(
                    $user,
                    $user->getHash()
                )
            );
            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('account_login');
        }
        return $this->render('account/register.html.twig',[
            'registration' => $form->createView()
        ]);
    }

    /**
     * Affichage de la page de profile d'un user
     * @Route("/profile", name="account_profile")
     * @return Response
     */
    public function profile(Request $request,EntityManagerInterface $manager){

        $user = $this->getUser();

        $form = $this->createForm(ProfileType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $manager->persist($user);
            $manager->flush();
            $this->addFlash('success',"Le informations de votre profil ont bien été modifiées");
        }
        return $this->render('account/profile.html.twig',[
            'profile' => $form->createView()
        ]);
    }

    /**
     * Permet la modification du mot de passe
     * @Route("account/password-update", name="account_password")
     * @return Response
     */
    public function updatePassword(Request $request,EntityManagerInterface $manager,UserPasswordEncoderInterface $encoder)
    {
        $passwordUpdate = new PasswordUpdate();
        $user = $this->getUser();

        $form = $this->createForm(PasswordUpdateType::class,$passwordUpdate);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            // On vérifie que le mot de passe soit le bon
            if(!password_verify($passwordUpdate->getOldPassword(),$user->getHash())){

                // message d'erreur
                $form->get('oldPassword')->addError(new FormError("Votre mot de passe actuel est incorrect"));
            } else {
                // On récupère le nouveau mot de passe
                $newPassword = $passwordUpdate->getNewPassword();

                // On crypte le nouveau mot de passe
                $hash = $encoder->encodePassword($user,$newPassword);

                // On modifie le nouveau mdp dans le setter
                $user->setHash($hash);

                // On enregistre
                $manager->persist($user);
                $manager->flush();

                // On ajoute un message
                $this->addFlash("success","Votre nouveau mot de passe a bien été enregistré");
                
                // On redirige
                return $this->redirectToRoute('account_profile');
            }
        }
        return $this->render('account/password.html.twig',[
            'passwordUpdate' => $form->createView()
        ]);
    }
}
