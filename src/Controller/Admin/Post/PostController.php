<?php

namespace App\Controller\Admin\Post;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
final class PostController extends AbstractController
{
    #[Route('/post/index', name: 'app_admin_post_index', methods:['GET'])]
    public function index(): Response
    {
        return $this->render('pages/admin/post/index.html.twig', [
            
        ]);
    }
}
