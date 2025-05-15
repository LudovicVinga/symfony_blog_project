<?php

namespace App\Controller\Admin\Profile;

use App\Entity\User;
use DateTimeImmutable;
use App\Form\EditProfileFormType;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\EditProfilePasswordFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/admin')]
final class ProfileController extends AbstractController
{
    #[Route('/profile/index', name: 'app_admin_profile_index', methods: ['GET'])]
    public function index(): Response
    {
        //Recuperer l'utilisateur connecté
        // car l'utilisateur qui demande a voir son profil, c'est l'utilisateur connécté.
        $admin = $this->getUser(); 

        return $this->render('pages/admin/profile/index.html.twig', [
            "admin" => $admin
        ]);
    }


    #[Route('/profile/edit', name: 'app_admin_profile_edit', methods: ['GET','POST'])]
    public function editProfile(Request $request, EntityManagerInterface $entityManager): Response
    {
        /**
         * @var User
         */
        $admin = $this->getUser();

        $form = $this->createForm(EditProfileFormType::class, $admin);
        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid() )
        {
            $admin->setUpdatedAt(new DateTimeImmutable());

            $entityManager->persist($admin);
            $entityManager->flush();

            $this->addFlash('success', 'Le profil a bien été modifié.');

            return $this->redirectToRoute('app_admin_profile_index');
        }

        return $this->render('pages/admin/profile/edit_profile.html.twig', [
            "form" => $form->createView()
        ]);
    }


    #[Route('/profile/edit/password', name: 'app_admin_profile_edit_password', methods: ['GET', 'POST'])]
    public function editPassword(Request $request, UserPasswordHasherInterface $hasher, EntityManagerInterface $entityManager): Response
    {
        /**
         * @var User
         */
        $admin = $this->getUser();

        $form = $this->createForm(EditProfilePasswordFormType::class, null);

        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid() )
        {
            
            // $data = $request->request->all();
            // dd($data['edit_profile_password_form']['plainPassword']['first']);

            $data = $form->getData();
            $newPlainPassword = $data['plainPassword'];

            $passwordHashed = $hasher->hashPassword($admin, $newPlainPassword);
            $admin->setPassword($passwordHashed);
            $admin->setUpdatedAt(new DateTimeImmutable());

            $entityManager->persist($admin);
            $entityManager->flush();

            $this->addFlash('success', 'Votre mot de passe a bien été modifié.');

            return $this->redirectToRoute('app_admin_profile_index');
        }

        return $this->render('pages/admin/profile/edit_password.html.twig', [
            "form" => $form->createView()
        ]);
    }
}
