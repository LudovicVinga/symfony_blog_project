<?php

namespace App\Controller\Admin\Category;

use App\Entity\Category;
use App\Form\AdminCategoryFormType;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin')]
final class CategoryController extends AbstractController
{
    #[Route('/category/index', name: 'app_admin_category_index', methods:['GET'])]
    public function index(): Response
    {
        return $this->render('pages/admin/category/index.html.twig', [
            
        ]);
    }

    #[Route('/category/create', name: 'app_admin_category_create', methods:['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $category = new Category();

        $form = $this->createForm(AdminCategoryFormType::class, $category);

        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid() )
        {
            $category->setCreatedAt(new DateTimeImmutable());
            $category->setUpdatedAt(new DateTimeImmutable());

            $entityManager->persist($category);

            $entityManager->flush();

            $this->addFlash('success', "Nouvelle catégorie : {$category->getName()} ajoutée.");

            return $this->redirectToRoute('app_admin_category_index');
        }

        return $this->render('pages/admin/category/create.html.twig', [
            "form" => $form->createView()
        ]);
    }
}
