<?php

namespace App\Controller\Admin\Contact;

use App\Entity\Contact;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



#[Route('/admin')]
final class ContactController extends AbstractController
{
    #[Route('/admin/contact/index', name: 'app_admin_contact_index', methods:['GET'])]
    public function index(ContactRepository $contactRepository): Response
    {

        $contacts = $contactRepository->findAll();

        return $this->render('pages/admin/contact/index.html.twig', [
            "contacts" => $contacts
        ]);
    }


    #[Route('/contact/delete/{id<\d>}', name: 'app_admin_contact_delete', methods:['GET','POST'])]
    public function delete(Contact $contact, Request $request, EntityManagerInterface $entityManager): Response
    {
        if ( $this->isCsrfTokenValid("delete_contact_{$contact->getId()}", $request->request->get('csrf_token')) )
        {
            $entityManager->remove($contact);
            $entityManager->flush();

            $this->addFlash('success', 'Le contact a bien été supprimé.');
        }

        return $this->redirectToRoute('app_admin_contact_index');
    }
}
