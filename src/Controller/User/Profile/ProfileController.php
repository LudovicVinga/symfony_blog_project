<?php

namespace App\Controller\User\Profile;

use App\Entity\User;
use DateTimeImmutable;
use Doctrine\ORM\EntityManager;
use App\Form\EditProfileFormType;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\EditProfilePasswordFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/user')]
final class ProfileController extends AbstractController
{
    #[Route('/user/profile/index', name: 'app_user_profile_index')]
    public function index(): Response
    {
        $user = $this->getUser();

        return $this->render('pages/user/profile/index.html.twig', [
            "user" => $user
        ]);
    }



    #[Route('/user/profile/edit', name: 'app_user_profile_edit', methods:['GET','POST'])]
    public function editProfile(Request $request, EntityManagerInterface $entityManager): Response
    {

        /**
         *  @var User
         */
        $user = $this->getUser();

        $form = $this->createForm(EditProfileFormType::class, $user);
        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid() )
        {
            $user->setUpdatedAt(new DateTimeImmutable());

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success','Le profil a bien été modifié');

            return $this->redirectToRoute('app_user_profile_index');
        }    
        return $this->render('pages/user/profile/edit_profile.html.twig', [
            "form" => $form->createView()
        ]);
    }



    #[Route('/user/profile/edit/password', name: 'app_user_profile_edit_password', methods:['GET','POST'])]
    public function editPassword(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $hasher): Response
    {
        /**
         *  @var User
         */
        $user = $this->getUser();

        $form = $this->createForm(EditProfilePasswordFormType::class, null);
        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid() )
        {
            $data = $form->getData();
            $newPlainPassword = $data['plainPassword'];

            $passwordHashed = $hasher->hashPassword($user, $newPlainPassword);
            $user->setPassword($passwordHashed);

            $user->setUpdatedAt(new DateTimeImmutable());

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Votre mot de passe a bien été modifié.');

            return $this->redirectToRoute('app_user_profile_index');
        }

        return $this->render('pages/user/profile/edit_password.html.twig', [
            "form" => $form->createView()
        ]);
    }
}
