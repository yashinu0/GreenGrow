<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\ProfileType;
use App\Form\UtilisateurType;
use App\Form\VendorType; 
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Controller\count;

#[Route('/utilisateur')]
final class UtilisateurController extends AbstractController
{
    #[Route(name: 'app_utilisateur_index', methods: ['GET'])]
    public function index(UtilisateurRepository $utilisateurRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $utilisateurRepository->createQueryBuilder('u')->getQuery();
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1), // Page actuelle
            3 // Nombre d'utilisateurs par page
        );

        return $this->render('utilisateur/index.html.twig', [
            'utilisateurs' => $utilisateurRepository->findAll(),
            'pagination' => $pagination,
        ]);
    }

    #[Route('/new', name: 'app_utilisateur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $utilisateur = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $passwordHasher->hashPassword($utilisateur, 'monmotdepasse');
            $utilisateur->setPassword($hashedPassword);

            $entityManager->persist($utilisateur);
            $entityManager->flush();

            return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('utilisateur/new.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }

    #[Route('/{id_user<\d+>}', name: 'app_utilisateur_show', methods: ['GET'])]
    public function show(int $id_user, UtilisateurRepository $utilisateurRepository): Response
    {
        $utilisateur = $utilisateurRepository->find($id_user);

        if (!$utilisateur) {
            throw $this->createNotFoundException("Utilisateur non trouvé");
        }

        return $this->render('utilisateur/show.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }

    #[Route('/{id_user}/edit', name: 'app_utilisateur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Utilisateur $utilisateur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('utilisateur/edit.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }

    #[Route('/utilisateur/{id_user}', name: 'app_utilisateur_delete', methods: ['POST'])]
    public function delete(Utilisateur $utilisateur, Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $utilisateur->getIdUser(), $request->request->get('_token'))) {
            $entityManager->remove($utilisateur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/inscription', name: 'app_inscription')]
    public function inscription(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $utilisateur = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $utilisateur);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (empty($utilisateur->getRoles())) {
                $utilisateur->setRoleUser('ROLE_CLIENT');
            }

            // Hachage du mot de passe avant de sauvegarder
            $hashedPassword = $passwordHasher->hashPassword($utilisateur, $utilisateur->getPassword());
            $utilisateur->setPassword($hashedPassword);

            $entityManager->persist($utilisateur);
            $entityManager->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('frontend/inscription.html.twig', [
            'form' => $form->createView(),
        ]);
    }
   
    #[Route('/inscription-vendeur', name: 'app_inscription_vendeur')]
    public function inscriptionVendeur(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $utilisateur = new Utilisateur();
        $form = $this->createForm(VendorType::class, $utilisateur);
    
        $form->handleRequest($request);
        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'Veuillez corriger les erreurs du formulaire.');

        }
        
        if ($form->isSubmitted() && $form->isValid()) {
            // Définir le rôle et le statut spécifiques
            $utilisateur->setRoleUser('ROLE_VENDOR');
            $utilisateur->setIsActive(false); // Désactiver jusqu'à validation
    
            // Hachage du mot de passe
            $hashedPassword = $passwordHasher->hashPassword($utilisateur, $utilisateur->getMotDePasseUser());
            $utilisateur->setMotDePasseUser($hashedPassword);
    
            $entityManager->persist($utilisateur);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_login');
        }
    
        return $this->render('frontend/inscriptionV.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id_user}/toggle-status', name: 'app_utilisateur_toggle_status', methods: ['POST'])]
    public function toggleStatus(Utilisateur $utilisateur, EntityManagerInterface $entityManager,MailerInterface $mailer): Response
    {
        $utilisateur->setIsActive(!$utilisateur->getIsActive()); // Inverser l'état
        $email = (new TemplatedEmail())
        ->from(new Address('gharbi.selim@esprit.tn', 'SmartFarmer'))
        ->to($utilisateur->getEmailUser() ) // Remplacez par une adresse e-mail valide
        ->subject('Votre compte a été désactivé')
->html("
    <div style='font-family: Arial, sans-serif; text-align: center; background-color: #f4f4f4; padding: 20px;'>
        <div style='max-width: 500px; background: white; padding: 20px; border-radius: 8px; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);'>
            <h2 style='color: #d9534f;'>Compte Désactivé</h2>
            <p style='color: #333; font-size: 16px;'>Bonjour,</p>
            <p style='color: #555; font-size: 16px;'>
                Votre compte a été désactivé. Si vous pensez que c'est une erreur, veuillez contacter le support.
            </p>
            <a href='mailto:Greengrowfeed@gmail.com' style='display: inline-block; background-color: #d9534f; color: white; padding: 10px 15px; border-radius: 5px; text-decoration: none; font-size: 16px;'>
                Contacter le support
            </a>
        </div>
    </div>
")
;

   
        $entityManager->persist($utilisateur); // Sauvegarde
        $entityManager->flush();
    
        return $this->redirectToRoute('app_utilisateur_index');
    }
    /*#[Route('/search', name: 'app_utilisateur_search', methods: ['GET'])]
    public function search(Request $request, UtilisateurRepository $utilisateurRepository, PaginatorInterface $paginator): Response
    {
        $query = $request->query->get('search');
        $page = $request->query->getInt('page', 1);
        
        $qb = $utilisateurRepository->createQueryBuilder('u');
        
        if ($query) {
            $qb->where('u.prenomUser LIKE :query OR u.nomUser LIKE :query OR u.emailUser LIKE :query')
               ->setParameter('query', '%' . $query . '%');
        }
        
        $pagination = $paginator->paginate(
            $qb->getQuery(),
            $page,
            3
        );
    
        return $this->render('utilisateur/_user_table.html.twig', [
            'pagination' => $pagination,
        ]);
    }
    

*/

#[Route('/search', name: 'user_search', methods: ['POST'])]
    public function search(Request $request, UtilisateurRepository $userRepository): JsonResponse
    {
        $query = $request->request->get('query');

        $users = $userRepository->search($query);

        return $users;
    }


    #[Route('/inactive-users', name: 'app_utilisateur_inactive', methods: ['GET'])]
public function showInactiveUsers(UtilisateurRepository $utilisateurRepository): Response
{
    // Récupérer les utilisateurs désactivés
    $inactiveUsers = $utilisateurRepository->findInactiveUsers();

    return $this->render('utilisateur/inactive_users.html.twig', [
        'utilisateurs' => $inactiveUsers,
    ]);
}

// src/Controller/UtilisateurController.php

#[Route('/statistiques', name: 'app_utilisateur_statistiques', methods: ['GET'])]
public function statistiques(UtilisateurRepository $utilisateurRepository): Response
{
    // Récupérer les utilisateurs désactivés
    $utilisateursDesactives = $utilisateurRepository->findBy(['isActive' => false]);

    // Calcul des statistiques
    $totalDesactives = count($utilisateursDesactives); // Total d'utilisateurs désactivés
    $roleStats = []; // Pour stocker la répartition par rôle

    foreach ($utilisateursDesactives as $utilisateur) {
        $role = $utilisateur->getRoleUser();
        if (!isset($roleStats[$role])) {
            $roleStats[$role] = 1;
        } else {
            $roleStats[$role]++;
        }
    }

    // Retourner une réponse avec les statistiques
    return $this->render('utilisateur/statistiques.html.twig', [
        'totalDesactives' => $totalDesactives,
        'roleStats' => $roleStats, // Passer les données des rôles
    ]);
}
#[Route('/{id_user}/edit-profile', name: 'app_utilisateur_edit_profile', methods: ['GET', 'POST'])]
public function editProfile(Request $request, Utilisateur $utilisateur, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
{
    // Vérifiez si l'utilisateur connecté est celui qui modifie son profil
    if ($this->getUser() !== $utilisateur) {
        throw $this->createAccessDeniedException("Vous ne pouvez pas modifier ce profil.");
    }

    // Créez un formulaire pour modifier les informations de l'utilisateur
    $form = $this->createForm(ProfileType::class, $utilisateur);
    

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Si le mot de passe a été modifié, nous le hachons à nouveau
        

        // Enregistrez les modifications dans la base de données
        $entityManager->flush();

        // Redirigez l'utilisateur vers sa page de profil
        return $this->redirectToRoute('app_user', ['id_user' => $utilisateur->getIdUser()]);
    }

    return $this->render('frontend/profil.html.twig', [
        'form' => $form->createView(),
        'utilisateur' => $utilisateur,
    ]);
}

   
    }
