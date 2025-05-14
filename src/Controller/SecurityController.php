<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Form\ForgotPasswordType;
use App\Repository\UtilisateurRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use Doctrine\ORM\EntityManagerInterface;


class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
public function login(
    AuthenticationUtils $authenticationUtils,
    Request $request,
    HttpClientInterface $httpClient,
    UtilisateurRepository $utilisateurRepository
): Response {
    // Vérification reCAPTCHA v3
    $errorMessage = null;
    $recaptchaKey = $_ENV['RECAPTCHA3_KEY'];
    $recaptchaResponse = $request->request->get('recaptcha_response');

    if ($recaptchaResponse) {
        $response = $httpClient->request('POST', 'https://www.google.com/recaptcha/api/siteverify', [
            'body' => [
                'secret' => $_ENV['RECAPTCHA3_SECRET'],
                'response' => $recaptchaResponse,
            ],
        ]);

        $data = $response->toArray();
        if (!$data['success'] || $data['score'] < 0.5) {
            $errorMessage = 'Validation reCAPTCHA échouée, veuillez réessayer.';
        }
    }

    // Gestion utilisateur déjà connecté
    if ($user = $this->getUser()) {
        // Récupération de la version fraîche depuis la base
        $freshUser = $utilisateurRepository->find($user->getUserIdentifier());

        if (!$freshUser || !$freshUser->getIsActive()) {
            // Déconnexion forcée
            $request->getSession()->invalidate();
            $this->container->get('security.token_storage')->setToken(null);

            $errorMessage = 'Votre compte est désactivé. Contactez l\'administrateur.';
            return $this->render('security/login.html.twig', [
                'error' => new AuthenticationException($errorMessage),
                'recaptcha_key' => $recaptchaKey,
            ]);
        }

        // Redirection selon le rôle
        return $this->redirectToRoute(
            in_array('ROLE_ADMIN', $freshUser->getRoles()) ? 'backvues' : 'app_user'
        );
    }

    // Gestion des erreurs d'authentification
    $error = $authenticationUtils->getLastAuthenticationError();
    $lastUsername = $authenticationUtils->getLastUsername();

    // Vérification état du compte pour l'email saisi
    if ($lastUsername) {
        $user = $utilisateurRepository->findOneBy(['email_user' => $lastUsername]);
        
        if ($user && !$user->getIsActive()) {
            $errorMessage = 'Ce compte est désactivé. Contactez l\'administrateur.';
            $error = new AuthenticationException($errorMessage);
        }
    }

    // Ajout d'erreur reCAPTCHA si nécessaire
    if ($errorMessage && !$error) {
        $error = new AuthenticationException($errorMessage);
    }

    return $this->render('security/login.html.twig', [
        'last_username' => $lastUsername,
        'error' => $error,
        'recaptcha_key' => $recaptchaKey,
    ]);
}
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/forgotPassword', name: 'app_forgetpassword')]
    public function testEmail(MailerInterface $mailer): Response
    {
        $email = (new TemplatedEmail())
            ->from(new Address('gharbi.selim@esprit.tn', 'Test'))
            ->to('gharbi.selim@esprit.tn') // Remplacez par une adresse e-mail valide
            ->subject('Test Email')
            ->text('Ceci est un test.');

        try {
            $mailer->send($email);
            return new Response('E-mail envoyé avec succès!');
        } catch (\Exception $e) {
            return new Response('Échec de l\'envoi de l\'e-mail: ' . $e->getMessage());
        }
    }
}
