# Système de Gestion E-commerce

Un système e-commerce complet développé avec Symfony, offrant une gestion complète des produits, des commandes, des réclamations et des utilisateurs.

## Fonctionnalités Principales

### Pour les Clients
- **Gestion du Compte**
  - Inscription et authentification
  - Profil utilisateur personnalisable
  - Historique des commandes
  - Gestion des adresses de livraison

- **Catalogue Produits**
  - Navigation par catégories
  - Recherche avancée
  - Filtres et tri
  - Détails produits avec images
  - Système de notation et avis

- **Panier et Commandes**
  - Panier d'achat
  - Processus de paiement sécurisé
  - Suivi des commandes
  - Historique des transactions
  - Suivi en temps réel de la livraison
  - Choix des options de livraison
  - Estimation des délais de livraison

- **Service Client**
  - Système de réclamations
  - Chat avec assistant AI
  - Messagerie avec le support
  - FAQ et centre d'aide

### Pour les Administrateurs
- **Tableau de Bord**
  - Vue d'ensemble des ventes
  - Statistiques et rapports
  - Gestion des utilisateurs
  - Gestion des produits et catégories

- **Gestion des Commandes**
  - Suivi des commandes
  - Gestion des livraisons
  - Traitement des retours
  - Facturation

- **Gestion des Livraisons**
  - Interface de gestion des transporteurs
  - Suivi des colis en temps réel
  - Gestion des zones de livraison
 
- **Gestion des Réclamations**
  - Interface de gestion des réclamations
  - Système de messagerie intégré
  - Historique des conversations
  - Gestion des statuts

- **Gestion du Catalogue**
  - CRUD des produits
  - Gestion des catégories
  - Gestion des stocks
  - Gestion des promotions

## Technologies Utilisées

- **Backend**
  - Symfony 6.x
  - PHP 8.x
  - Doctrine ORM
  - API Platform

- **Frontend**
  - Twig Templates
  - Bootstrap 5
  - Font Awesome
  - MDB (Material Design Bootstrap)
  - JavaScript/jQuery
  - AJAX

- **Base de données**
  - MySQL/PostgreSQL
  - Redis (pour le cache)

- **Services**
  - Système de paiement intégré
  - Service d'envoi d'emails
  - Assistant AI pour le support
  - Système de notifications
  - Intégration avec les transporteurs
  - Système de suivi des livraisons
  - API de géolocalisation

## Prérequis

- PHP 8.1 ou supérieur
- Composer
- MySQL 5.7+ ou PostgreSQL 12+
- Node.js et NPM
- Symfony CLI

## Installation

1. Cloner le repository
```bash
git clone [URL_DU_REPO]
```

2. Installer les dépendances PHP
```bash
composer install
```

3. Installer les dépendances JavaScript
```bash
npm install
```

4. Configurer l'environnement
```bash
cp .env.example .env
```
Modifier les variables dans le fichier `.env` :
```env
DATABASE_URL="mysql://user:password@127.0.0.1:3306/db_name"
MAILER_DSN="smtp://user:pass@smtp.example.com:port"
PAYMENT_API_KEY="your_payment_api_key"
AI_ASSISTANT_API_KEY="your_ai_api_key"
```

5. Créer la base de données et exécuter les migrations
```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

6. Charger les fixtures (données de test)
```bash
php bin/console doctrine:fixtures:load
```

7. Compiler les assets
```bash
npm run build
```

8. Lancer le serveur
```bash
symfony server:start
```

## Structure du Projet

```
├── assets/                 # Assets frontend (JS, CSS, images)
├── config/                 # Configuration Symfony
├── migrations/            # Migrations Doctrine
├── public/               # Point d'entrée public
├── src/
│   ├── Controller/      # Contrôleurs
│   ├── Entity/         # Entités Doctrine
│   ├── Repository/     # Repositories
│   ├── Service/        # Services métier
│   └── Form/           # Formulaires
├── templates/           # Templates Twig
└── tests/              # Tests unitaires et fonctionnels
```

## Tests

```bash
# Tests unitaires
php bin/phpunit

# Tests fonctionnels
php bin/phpunit --testsuite functional
```

## Déploiement

1. Configurer le serveur web (Apache/Nginx)
2. Configurer les variables d'environnement de production
3. Optimiser les performances :
```bash
composer install --no-dev --optimize-autoloader
php bin/console cache:clear --env=prod
php bin/console cache:warmup --env=prod
```

## Sécurité

- Authentification sécurisée
- Protection CSRF
- Validation des données
- Gestion des permissions
- Chiffrement des données sensibles
- Protection contre les injections SQL
- Rate limiting

## Contribution

Les contributions sont les bienvenues ! N'hésitez pas à :
1. Fork le projet
2. Créer une branche pour votre fonctionnalité
3. Commiter vos changements
4. Pousser vers la branche
5. Ouvrir une Pull Request

## Licence

Ce projet est sous licence MIT. Voir le fichier `LICENSE` pour plus de détails.

## Support

Pour toute question ou problème :
- Ouvrir une issue dans le repository GitHub
- Consulter la documentation
- Contacter l'équipe de support

## Roadmap

- [ ] Intégration de nouveaux moyens de paiement
- [ ] Système de fidélité
- [ ] Application mobile
- [ ] API REST complète
- [ ] Système de recommandation
- [ ] Intégration des réseaux sociaux
- [ ] Optimisation des routes de livraison
- [ ] Système de livraison en point relais
- [ ] Intégration de nouveaux transporteurs
- [ ] Système de livraison express
- [ ] Application mobile pour les livreurs 