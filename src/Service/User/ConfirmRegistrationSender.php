<?php

namespace App\Service\User;

use App\Entity\User;
use App\Service\Mail\RegistrationMail;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class ConfirmRegistrationSender
{
    public function __construct(private readonly RouterInterface $router, private readonly RegistrationMail $registrationMail)
    {
    }

    public function sendConfirmationEmail(User $user): void
    {
        $route = $this->router->generate('app_register_confirm', ['token' => $user->getToken()], UrlGeneratorInterface::ABSOLUTE_URL);
        $this->registrationMail->send($user->getEmail(), $user->getUsername(), $route);
    }
}
