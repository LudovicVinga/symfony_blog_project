<?php

namespace App\Controller\Admin\Post;

use App\Entity\Post;
use App\Form\AdminPostFormType;
use App\Repository\PostRepository;
use DateTimeImmutable;
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
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {

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
}
