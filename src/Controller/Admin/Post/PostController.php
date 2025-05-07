<?php

namespace App\Controller\Admin\Post;

use App\Entity\Post;
use DateTimeImmutable;
use App\Form\AdminPostFormType;
use App\Repository\PostRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin')]
final class PostController extends AbstractController
{
    #[Route('/post/index', name: 'app_admin_post_index', methods:['GET'])]
    public function index(PostRepository $postRepository): Response
    {

        $posts = $postRepository->findAll();

        

        return $this->render('pages/admin/post/index.html.twig', [
            "posts" => $posts
        ]);
    }


    #[Route('/post/create', name: 'app_admin_post_create', methods:['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager, CategoryRepository $categoryRepository): Response
    {

        if ( $categoryRepository->count() == 0 )
        {
            $this->addFlash('warning', "Vous devez créer au moins une catégorie pour rédiger des articles.");
            return $this->redirectToRoute('app_admin_category_index');
        }

        $post = new Post();
        
        $form = $this->createForm(AdminPostFormType::class, $post);

        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid() )
        {
            $post->setUser($this->getUser());
            $post->setCreatedAt(new DateTimeImmutable());
            $post->setUpdatedAt(new DateTimeImmutable());
            $post->setIsPublished(false);

            $entityManager->persist($post);
            $entityManager->flush();

            $this->addFlash('success', 'L\'article a bien été sauvegardé mais non publié.');

            return $this->redirectToRoute('app_admin_post_index');

        }

        return $this->render("pages/admin/post/create.html.twig", [
            "form" => $form->createView()
        ]);
    }


    #[Route('/post/show/{id<\d+>}', name: 'app_admin_post_show', methods:['GET'])]
    public function show(Post $post): Response
    {
        return $this->render('pages/admin/post/show.html.twig', [
            "post" => $post
        ]);
    }


    #[Route('/post/edit/{id<\d+>}', name: 'app_admin_post_edit', methods:['GET', 'POST'])]
    public function edit(Post $post, Request $request, EntityManagerInterface $entityManager): Response
    {

        $form = $this->createForm(AdminPostFormType::class, $post);

        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid() )
        {
            $post->setUser($this->getUser());
            $post->setUpdatedAt(new DateTimeImmutable());

            $entityManager->persist($post);
            $entityManager->flush();

            $this->addFlash('success', 'L\'article a été modifié.');

            return $this->redirectToRoute('app_admin_post_index');
        }

        return $this->render('pages/admin/post/edit.html.twig', [
            "post" => $post,
            "form" => $form
        ]);
    }


    #[Route('/post/delete/{id<\d+>}', name: 'app_admin_post_delete', methods:['POST'])]
    public function delete(Post $post, Request $request, EntityManagerInterface $entityManager): Response
    {
        if ( $this->isCsrfTokenValid("delete_post_{$post->getId()}", $request->request->get('csrf_token')) )
        {

            $title = $post->getTitle();

            $entityManager->remove($post);
            $entityManager->flush();

            $this->addFlash('success', "{$title} a bien été supprimé." );

        }

        return $this->redirectToRoute('app_admin_post_index');
    }


    #[Route('/post/publish/{id<\d+>}', name: 'app_admin_post_publish', methods:['POST'])]
    public function publish(Post $post, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Si le jeton de sécurité n'est pas validé
        if ( ! $this->isCsrfTokenValid("publish_post_{$post->getId()}", $request->request->get('csrf_token')) )
        {
            return $this->redirectToRoute('app_admin_post_index');
        }

        if ( $post->isPublished() === false )
        {
            // Publions l'article

            // Mettons isPublished a true
            $post->setIsPublished(true);
            // Mettons a jour la date de publication
            $post->setPublishedAt(new DateTimeImmutable());
            $post->setUpdatedAt(new DateTimeImmutable());

            // Générer le message flash
            $this->addFlash('success', "L'article a bien été publié.");

        }
        else
        {
            // Retirons l'article de la liste des publications sur le blog

            // Mettons a isPublished a false
            $post->setIsPublished(false);
            // Mettons a null la date de publication
            $post->setPublishedAt(null);
            $post->setUpdatedAt(new DateTimeImmutable());

            // Générer le message flash
            $this->addFlash('success', "L'article a bien été retiré de la liste de publication.");

        }

        // Mettre a jour les informations dans la BDD
        $entityManager->persist($post);
        $entityManager->flush();
        // Effectuer la redirection vers la page listant les articles
        // Arreter l'execution du script
        return $this->redirectToRoute('app_admin_post_index');
    }
}
