<?php

namespace App\Security;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof \App\Entity\Utilisateur) {
            return;
        }

        if (!$user->getIsActive()) {
            throw new CustomUserMessageAccountStatusException("Votre compte a été désactivé. Contactez l'administrateur.");
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
        // Ici, on ne fait rien après l'authentification
    }
}
