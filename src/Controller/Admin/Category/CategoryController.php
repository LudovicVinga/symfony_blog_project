<?php

namespace App\Controller\Admin\Category;

use App\Entity\Category;
use App\Form\AdminCategoryFormType;
use App\Repository\CategoryRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin')]
final class CategoryController extends AbstractController
{

    #[Route('/category/index', name: 'app_admin_category_index', methods:['GET'])]
    public function index(CategoryRepository $categoryRepository): Response
    {

        $categories = $categoryRepository->findAll();

        return $this->render('pages/admin/category/index.html.twig', [
            "categories" => $categories
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


    #[Route('/category/edit/{id<\d+>}', name: 'app_admin_category_edit', methods:['GET', 'POST'])]
    public function edit(Category $category, Request $request, EntityManagerInterface $entityManager): Response
    {

        // $category = $categoryRepository->find($id);
        
        // if ( $category === null )
        // {
        //     return $this->redirectToRoute('app_admin_category_index');
        // }

        // Préparer le formulaire de modification
        $form = $this->createForm(AdminCategoryFormType::class, $category);

        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid() )
        {
            $category->setUpdatedAt(new DateTimeImmutable());

            $entityManager->persist($category);
            $entityManager->flush();

            $this->addFlash('success', "La catégorie {$category->getName()} a été modifiée.");

            return $this->redirectToRoute('app_admin_category_index');
        }

        return $this->render('pages/admin/category/edit.html.twig', [
            'form' => $form->createView(),
            'category' => $category
        ]);
    }


    #[Route('/category/delete/{id<\d+>}', name: 'app_admin_category_delete', methods:['POST'])]
    public function delete(Category $category, Request $request, EntityManagerInterface $entityManager): Response
    {

        if ( $this->isCsrfTokenValid("delete_category_{$category->getId()}", $request->request->get('csrf_token')) )
        {
            
            $categoryName = $category->getName();
            $entityManager->remove($category);
            $entityManager->flush();
            
            $this->addFlash('success', "La catégorie \"{$categoryName}\" ainsi que tous ses articles ont bien été suprimés.");
        }

        return $this->redirectToRoute('app_admin_category_index');
    }
}
