<?php

namespace App\Security;

use App\Repository\UtilisateurRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\SecurityRequestAttributes;


class Authenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';
    private UserPasswordHasherInterface $passwordHasher;
    private UtilisateurRepository $utilisateurRepository;

    public function __construct(private UrlGeneratorInterface $urlGenerator, UtilisateurRepository $utilisateurRepository, UserPasswordHasherInterface $passwordHasher)
    {
        $this->utilisateurRepository = $utilisateurRepository;
        $this->passwordHasher = $passwordHasher;
    }
    public function authenticate(Request $request): Passport
    {
        $emailUser = $request->get('email_user');
        $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $emailUser);
    
        // Récupérer l'utilisateur
        $user = $this->utilisateurRepository->findOneBy(['email_user' => $emailUser]);
    
        // Vérifier si l'utilisateur existe et s'il est actif
        if (!$user || !$user->getIsActive()) {
            throw new \Exception('Votre compte est désactivé. Veuillez contacter l\'administrateur.');
        }
    
        return new Passport(
            new UserBadge($emailUser, function ($userIdentifier) {
                return $this->utilisateurRepository->findOneBy(['email_user' => $userIdentifier]);
            }),
            new PasswordCredentials($request->get('mot_de_passe_user')),
            [
                new CsrfTokenBadge('authenticate', $request->get('_csrf_token')),
                new RememberMeBadge(),
            ]
        );
    }
    


    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        
        $user = $token->getUser();
       

        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            return new RedirectResponse($this->urlGenerator->generate('homeback')); // Page admin
        }

    return new RedirectResponse($this->urlGenerator->generate('backvues')); // Page client

        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse($this->urlGenerator->generate('app_user'));
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}