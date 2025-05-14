<?php
namespace App\Service;

use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

    class EmailSenderService
    {
        public function __construct(private MailerInterface $mailer)
        {
            
        }

        /**
         * Cette méthode permet d'enovyer l'email
         *
         * @param array $data
         * @return void
         */
        public function sendEmail(array $data = []): void
        {
            $emailSender = $data['email_sender'];
            $emailSenderFullName = $data['email_sender_full_name'];
            $emailRecipient = $data['email_recipient'];
            $subject = $data['subject'];
            $htmlTemplate = $data['html_template'];
            $context = $data['context'];

            $email = (new TemplatedEmail())
                ->from(new Address($emailSender, $emailSenderFullName))
                ->to((string) $emailRecipient)
                ->subject($subject)
                ->htmlTemplate($htmlTemplate)
                ->context($context);

            try
            {
                $this->mailer->send($email);
            }
            catch (TransportExceptionInterface $th)
            {
                throw $th;
            }
        }
    }