<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailService
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmail(string $from, string $to, string $subject, string $content)
    {
        $email = (new Email())
            ->from("ooyosri@gmail.com")
            ->to($to)
            ->subject($subject)
            ->text($content);

        $this->mailer->send($email);
    }
}
