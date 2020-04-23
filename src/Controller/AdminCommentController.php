<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\AdminCommentType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class AdminCommentController extends AbstractController
{
    /**
     * @Route("/admin/comments", name="admin_comments_list")
     */
    public function index(CommentRepository $repo)
    {
        return $this->render('admin/comment/index.html.twig', [
            'comments' => $repo->findAll(),
        ]);
    }

    /**
     * Permet d'éditer un commentaire
     * @Route("/admin/comments/{id}/edit", name="admin_comments_edit")
     * @param Comment $comment
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function edit(Comment $comment,Request $request,EntityManagerInterface $manager)
    {
        $form = $this->createForm(AdminCommentType::class,$comment);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $manager->persist($comment);
            $manager->flush();

            $this->addFlash("success","Le commentaire a bien été modifié");
            return $this->redirectToRoute('admin_comments_list');
        }
        return $this->render('admin/comment/edit.html.twig',[
            'comment' => $comment,
            'adminComment' => $form->createView()
        ]);
    }

    /**
     * Suppression d'un commentaire
     * @Route("/admin/comments/{id}/delete", name="admin_comments_delete")
     * @param Comment $comment
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(Comment $comment,EntityManagerInterface $manager)
    {
        $manager->remove($comment);
        $manager->flush();
        $this->addFlash('success',"Le commentaire {$comment->getId()} a bien été supprimé !");

        return $this->redirectToRoute('admin_comments_list');
    }
}
