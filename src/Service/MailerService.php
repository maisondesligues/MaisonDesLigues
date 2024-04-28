<?php

namespace App\Service;

// ---------------------------------------------------------------------------------------------------

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

// ---------------------------------------------------------------------------------------------------

class MailerService
{
    private $mailer;

    /**
     * Constructeur de la classe MailerService
     */
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * Envoie un mail 
     */
    public function sendEmail(string $from, string $to, string $subject, string $textBody, string $htmlBody = null)
    {
        $email = (new Email())
            ->from($from)
            ->to($to)
            ->subject($subject)
            ->text($textBody);

        if ($htmlBody) {
            $email->html($htmlBody);
        }

        $this->mailer->send($email);
    }
}
