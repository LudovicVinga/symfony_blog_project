<?php

namespace App\Controller\Admin\Setting;

use DateTimeImmutable;
use App\Entity\Setting;
use Doctrine\ORM\EntityManager;
use App\Form\AdminSettingFormType;
use App\Repository\SettingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



#[Route('/admin')]
final class SettingController extends AbstractController
{
    #[Route('/setting/index', name: 'app_admin_setting_index', methods:['GET'])]
    public function index(SettingRepository $settingRepository): Response
    {

        $settings = $settingRepository->findAll();
        $setting = $settings[0];

        return $this->render('pages/admin/setting/index.html.twig', [
            "setting" => $setting
        ]);
    }


    #[Route('/setting/edit{id<\d+>}', name: 'app_admin_setting_edit', methods:['GET', 'POST'])]
    public function edit(Setting $setting, Request $request, EntityManagerInterface $entityManager): Response
    {

        $form = $this->createForm(AdminSettingFormType::class, $setting);

        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid() )
        {
            // $setting->setUser($this->getUser()); //Qui a modifié les settings
            $setting->setUpdatedAt(new DateTimeImmutable());

            $entityManager->persist($setting);
            $entityManager->flush();

            $this->addFlash('success', 'Les paramètres ont bien été modifiés.');

            return $this->redirectToRoute('app_admin_setting_index');
        }


        return $this->render('pages/admin/setting/edit.html.twig',[
            "form" => $form->createView()
        ] );
    }
}
