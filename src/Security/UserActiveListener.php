<?php

namespace App\Security;

use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserActiveListener implements EventSubscriberInterface
{
    private $tokenStorage;
    private $utilisateurRepository;
    private $urlGenerator;

    public function __construct(
        TokenStorageInterface $tokenStorage,
        UtilisateurRepository $utilisateurRepository,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->utilisateurRepository = $utilisateurRepository;
        $this->urlGenerator = $urlGenerator;
    }

    public static function getSubscribedEvents()
    {
        return [
            RequestEvent::class => 'onKernelRequest',
        ];
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $token = $this->tokenStorage->getToken();
        
        if (!$token) {
            return;
        }

        $user = $token->getUser();

        if ($user instanceof Utilisateur) {
            // Rafraîchir l'utilisateur depuis la base
            $freshUser = $this->utilisateurRepository->find($user->getIdUser());

            if (!$freshUser || !$freshUser->getIsActive()) {
                // Invalider la session
                $this->tokenStorage->setToken(null);
                $event->getRequest()->getSession()->invalidate();

                // Rediriger vers la page de login
                $response = new RedirectResponse($this->urlGenerator->generate('app_login'));
                $event->setResponse($response);
            }
        }
    }
}