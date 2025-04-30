<?php

namespace App\Controller\Admin\Category;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
final class CategoryController extends AbstractController
{
    #[Route('/category', name: 'app_admin_category_index', methods:['GET'])]
    public function index(): Response
    {
        return $this->render('pages/admin/category/index.html.twig', [
            
        ]);
    }
}
