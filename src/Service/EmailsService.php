<?php

declare(strict_types=1);

namespace App\Service;

use Twig\Environment;

class EmailsService
{
    private $mailer;
    private $templating;

    public function __construct(\Swift_Mailer $mailer, Environment $templating)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    public function emailConfirmation(string $userName, string $userEmail, string $userToken)
    {
        $message = (new \Swift_Message('Email confirmation'))
            ->setFrom('blog.noveo@gmail.com')
            ->setTo($userEmail)
            ->setBody(
                $this->templating->render(
                    'emails/email_confirmation.html.twig',
                    ['name' => $userName,
                     'token' => $userToken,
                    ]
                ),
                'text/html'
            )
        ;
        $this->mailer->send($message);
    }
}
