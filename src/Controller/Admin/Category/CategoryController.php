<?php

namespace App\Controller\Admin\Category;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CategoryController extends AbstractController
{
    #[Route('/admin/category/category', name: 'app_admin_category_category')]
    public function index(): Response
    {
        return $this->render('admin/category/category/index.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }
}
