<?php

namespace App\Controller\Visitor\Contact;

use App\Entity\Contact;
use App\Form\VisitorContactFormType;
use App\Repository\SettingRepository;
use App\Service\EmailSenderService;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ContactController extends AbstractController
{

    public function __construct(private EmailSenderService $emailSenderService)
    {
        
    }


    #[Route('/contact', name: 'app_visitor_contact', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager, SettingRepository $settingRepository): Response
    {

        $settings = $settingRepository->findAll();
        $setting = $settings[0];

        $contact = new Contact();

        $form = $this->createForm(VisitorContactFormType::class, $contact);

        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid() )
        {
            
            /** @var User */
            $user = $this->getUser(); //On récupère l'utilisateur connecté
            
            if ( $user )
            {
                if ( $user->getEmail() === $contact->getEmail() )
                {
                    $contact->setUser($this->getUser());
                }
            }
            
            $contact->setCreatedAt(new DateTimeImmutable());

            $entityManager->persist($contact);
            $entityManager->flush();


            // Envoi du message par email a Jean Dupont (service)
            $this->emailSenderService->sendEmail([
                "email_sender"           => "medecine-du-monde@gmail.com",
                "email_sender_full_name" => "Jean Dupont",
                "email_recipient"        => "medecine-du-monde@gmail.com",
                "subject"                => "Prise de contact par un tier",
                "html_template"          => "emails/contact.html.twig",
                "context"                => [
                    "contact_first_name" => $contact->getFirstName(),
                    "contact_last_name"  => $contact->getLastName(),
                    "contact_email"      => $contact->getEmail(),
                    "contact_phone"      => $contact->getPhone(),
                    "contact_message"    => $contact->getMessage(),
                ]
            ]);

            $this->addFlash('success', 'Le message a bien été envoyé.');

            return $this->redirectToRoute('app_visitor_contact');
        }

        return $this->render('pages/visitor/contact/create.html.twig', [
            "form" => $form->createView(),
            "setting" => $setting
        ]);
    }
}
