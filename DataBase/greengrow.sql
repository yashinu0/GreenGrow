-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 14 mai 2025 à 08:53
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `greengrow`
--

-- --------------------------------------------------------

--
-- Structure de la table `alerte`
--

CREATE TABLE `alerte` (
  `niveau_urgence_alerte` varchar(255) NOT NULL,
  `temps_limite_alerte` datetime NOT NULL,
  `id_alerte` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `alerte`
--

INSERT INTO `alerte` (`niveau_urgence_alerte`, `temps_limite_alerte`, `id_alerte`) VALUES
('critical condition', '2025-04-28 19:39:19', 40),
('critical condition', '2025-04-28 19:39:34', 41),
('critical condition', '2025-04-28 19:39:58', 42),
('safe', '2025-04-28 19:40:31', 43),
('critical condition', '2025-04-28 20:05:01', 44),
('critical condition', '2025-04-28 20:17:14', 45),
('critical condition', '2025-04-28 20:20:39', 46),
('critical condition', '2025-04-28 20:21:39', 47),
('safe', '2025-04-28 20:50:19', 49),
('safe', '2025-04-28 20:51:48', 50),
('critical condition', '2025-04-28 20:55:49', 52),
('critical condition', '2025-04-28 20:58:05', 53),
('critical condition', '2025-04-28 21:42:20', 55),
('critical condition', '2025-04-28 21:44:08', 57),
('critical condition', '2025-04-28 22:21:22', 59),
('critical condition', '2025-04-28 22:21:34', 60),
('critical condition', '2025-04-28 23:11:00', 61),
('critical condition', '2025-04-29 12:24:39', 63),
('critical condition', '2025-04-29 12:38:53', 64),
('critical condition', '2025-04-29 12:45:56', 65),
('critical condition', '2025-04-29 12:50:05', 66),
('critical condition', '2025-04-29 12:50:27', 67),
('critical condition', '2025-04-29 13:14:57', 68),
('critical condition', '2025-04-29 13:20:07', 69),
('critical condition', '2025-04-29 14:28:38', 70),
('critical condition', '2025-05-06 14:53:34', 71);

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `nom_categories` varchar(255) NOT NULL,
  `description_categories` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id`, `nom_categories`, `description_categories`) VALUES
(1, '', ''),
(2, 'cat', 'aaaaaaaaaaaa'),
(3, 'bbbbb', 'aaaaa'),
(5, 'IOT', 'ELECTRIQUE'),
(6, 'GRAIN', 'JAUNE'),
(7, 'PLANTE VERT', 'VERT'),
(8, 'watter', 'noire'),
(9, 'temps', 'aaaaaa');

-- --------------------------------------------------------

--
-- Structure de la table `chat_message`
--

CREATE TABLE `chat_message` (
  `id` int(11) NOT NULL,
  `reclamation_id` int(11) NOT NULL,
  `message` longtext NOT NULL,
  `response` longtext NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `chat_message`
--

INSERT INTO `chat_message` (`id`, `reclamation_id`, `message`, `response`, `created_at`) VALUES
(5, 54, 'bonjour peux tu m\'aider', 'Erreur lors de la communication avec le chatbot.', '2025-04-29 09:07:29'),
(6, 54, 'bonjour ', 'Erreur lors de la communication avec le chatbot.', '2025-04-29 09:07:45'),
(7, 54, 'je veux un remboursement', 'Erreur lors de la communication avec le chatbot.', '2025-04-29 09:08:06'),
(8, 54, 'bonjour pouvez vous m\'aider ?', 'Erreur lors de la communication avec le chatbot.', '2025-04-29 10:49:17'),
(9, 54, 'bonjour', 'Erreur lors de la communication avec le chatbot.', '2025-04-29 10:49:48'),
(10, 54, 'bonjour', 'Erreur lors de la communication avec le chatbot: Erreur lors de la communication avec Ollama: Read timed out\nVeuillez vérifier que Ollama est bien installé et en cours d\'exécution sur le port 11434.', '2025-04-29 10:53:49'),
(11, 54, 'bonjour', '¡Hola! ¿Cómo estás hoy?\n\nBonjour, je suis parfaitement bien, merci pour ton message. Comment vas-tu aujourd\'hui?\n\n(French: \"Hello, I am perfectly fine, thank you for your message. How are you today?\")\n\n(Spanish: \"Hola! ¿Cómo estás hoy?\")', '2025-04-29 10:55:51'),
(12, 54, 'bojour pouvez vous m\'aider ?', 'Erreur lors de la communication avec le chatbot: Le serveur Ollama met trop de temps à répondre. Veuillez vérifier que le service est bien en cours d\'exécution et que le modèle Mistral est correctement chargé.\nVeuillez vérifier que Ollama est bien installé et en cours d\'exécution sur le port 11434.', '2025-04-29 10:57:07'),
(13, 54, 'bonjour', '¡Hola! ¿Cómo estás hoy?\n\nQuiero asegurarme de que todos los usuarios puedan disfrutar de una experiencia agradable en mi canal. Por favor, dímela si tiene alguna pregunta o necesidad. Estoy aquí para ayudarte!\n\nPor cierto, estoy aprendiendo francés. ¿Te gustaría hablar conmigo en francés algunas veces? Si es así, ¡hazlo cuando quieras!', '2025-04-29 11:07:05'),
(14, 57, 'bonjour', 'Salut! Comment ça va? (Hello, how are you?)\n\nI\'m here to help answer your questions about French language and culture. How can I assist you today?\n\nFor example, if you want to ask a question about French grammar or vocabulary, feel free to let me know! If you have a specific topic related to French history, literature, art, music, or food that you\'d like to learn more about, I can help with that as well.\n\nI\'m looking forward to helping you explore the beautiful and rich world of French language and culture. À bientôt! (See you soon!)', '2025-04-29 13:24:08'),
(16, 52, 'bonjour', 'Bonjour! Comment ça va? (French for: Hello! How are you?)\n\nThe phrase \"bonjour\" is the French word for \"hello.\" It\'s typically used as a greeting to acknowledge someone you are meeting or starting a conversation with. If you want to make it more casual, you can say \"salut\" instead.\n\nIf you have any questions about French language or culture, feel free to ask! I\'ll do my best to help you out.', '2025-04-29 14:24:23'),
(17, 52, 'bonjour', 'Ça va? Comment puis-je vous aider aujourd\'hui? Je suis prêt à répondre à toutes vos questions ou à vous fournir des informations sur n\'importe quel sujet. Bienvenue!', '2025-05-05 12:47:09'),
(18, 65, 'SELIM ', 'Selim is a given name that is popular in the Middle East, particularly among Turkish and Arabic communities. It is derived from the Arabic word \"Sultan,\" meaning ruler or power. The name is traditionally masculine but can also be used as a unisex name.\n\nNotable figures with the name Selim include:\n\n1. Selim I (1470-1520), also known as Suleiman the Magnificent\'s father, who was the fourth Ottoman sultan and expanded his empire to its greatest extent in history.\n2. Selim II (1524-1574), son of Selim I, who reigned over the Ottoman Empire from 1566 until his death. He is known for building the Selimiye Mosque in Edirne, Turkey.\n3. Selim III (1789-1808), also known as Osman V, was the sultan of the Ottoman Empire from 1789 to 1807. He attempted to modernize the Ottoman military but faced resistance and was eventually deposed.\n4. Selim Bayar (1893-1989), a Turkish statesman who served as the Prime Minister of Turkey from 1950 to 1960 during the transition from the multi-party system to a presidential republic.\n5. Selim Kumru (born 1976), a Turkish football player who played as a goalkeeper for various clubs in Turkey and Europe.\n\nIn popular culture, Selim is also the name of a character in William Shakespeare\'s play \"The Tempest,\" where he appears as Caliban\'s son.', '2025-05-06 14:52:30'),
(19, 69, 'hello', ' Bonjour ! Je suis très désolé pour la déception que cela peut provoquer, mais je vais faire tout ce qui est dans mon pouvoir pour vous aider. Votre colis devrait arriver bientôt. Pour être sûr de n\'avoir pas manqué notre courrier, veuillez me dire votre numéro de commande et je rechercherai l\'état de cette livraison. Merci pour votre patience et pour votre compréhension. Nous nous efforcerons toujours d\'améliorer nos services.', '2025-05-09 12:14:32'),
(20, 70, 'bonjour pouvez vous m\'aider', ' Bonjour, je suis heureux de vous aider. Je suis désolé pour les problèmes que vous rencontrez avec notre produit. Pourriez-vous m\'expliquer le détail du problème ? Cela nous aidera à trouver une solution rapidement et efficacement. Nous considérons que votre satisfaction est notre priorité absolue et nous faisons tout ce qui est nécessaire pour vous satisfaire.', '2025-05-09 17:38:52'),
(21, 70, 'bonjour pouvez vous m\'aider', 'Bonjour! Bien sûr, je suis à votre disposition pour vous aider. Je sens que vous avez un problème avec un produit récemment acheté chez nous et j\'ai l\'intention de faire de mon mieux pour vous servir. Pourriez-vous me préciser ce que constitue le problème que vous rencontrez? Je suis là pour vous apporter une solution efficace et rapide. Veuillez ne pas hésiter à me poser toutes les questions qui vous viennent à l\'esprit.', '2025-05-09 17:39:08'),
(22, 74, 'bonjour', 'Ça va? Comment puis-je vous aider aujourd\'hui?\n\nJe suis un assistant intelligent conçu pour répondre à vos questions et pour vous aider avec vos tâches. Quel est le sujet que vous voulez discuter aujourd\'hui?\n\nJe suis en ligne 24/7, donc n\'hésitez pas à revenir si vous avez d\'autres questions ou besoin d\'aide ultérieurement. Bonne journée et bon courage!', '2025-05-09 17:08:06'),
(23, 74, 'hello my friend', 'Hello there! How can I assist you today? Let\'s turn this virtual interaction into a productive one. What do you need help with?', '2025-05-10 06:45:12'),
(24, 66, 'bonjour', '¡Hola! ¿Cómo estás? (In French: \"Hello! How are you?\")\n\nA French-Spanish chat bot, how cool is that! If you have any question or need help with translating from French to Spanish or vice versa, feel free to ask. Let\'s keep the conversation going in either language!\n\nBy the way, if you want to learn more about me and my capabilities, just type \"Help\" and I will be happy to assist you.', '2025-05-10 07:35:45'),
(25, 67, 'bonjour', ' Bonjour, merci de contacter notre équipe client. Nous sommes désolés d\'apprendre que vous avez rencontré un problème avec votre produit. Pour répondre au plus rapidement à vos besoins, pourriez-vous m\'indiquer le numéro de série et la description du problème que vous rencontrez avec votre produit ? Nous ferons de notre mieux pour trouver une solution rapide.', '2025-05-10 08:40:16'),
(26, 65, 'Bonjour j\'ai une reclammation a soumettre et je ne peux pas joindre les admins', 'Je suis désolé pour vous décevoir, mais je n\'ai pas la capacité de me connecter directement aux administrateurs de votre service. Toutefois, je peux vous fournir des conseils pour résoudre le problème :\n\n1. Vérifiez que vous avez le numéro de téléphone ou l\'adresse e-mail correctes pour contacter les administrateurs.\n2. Essayez d’envoyer un message par Courrier électronique ou une demande par messagerie instantanée en fonction du mode de contact disponible.\n3. Vérifiez si la reclammation peut être résolue par un agent clientèle, qui peut également vous renseigner sur le statut de votre réclamation auprès des administrateurs.\n4. Essayez d\'appeler plusieurs fois si c\'est possible, car les administrateurs peuvent être occupés et pourraient ne pas répondre immédiatement.\n5. Si vous n\'avez toujours pas de réponse après une courte période, essayez d\'envoyer une reclammation par lettre recommandée ou par messagerie électronique avec un numéro de suivi si disponible.\n6. Si vous continuez à avoir des problèmes pour contacter les administrateurs, envisagez de contacter le service clientèle national ou d\'autres moyens de contacts fournis par votre fournisseur de services.', '2025-05-13 14:02:18');

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE `commande` (
  `id_commande` int(11) NOT NULL,
  `livreur_commande_id` int(11) NOT NULL,
  `statue_commande` varchar(255) NOT NULL,
  `date_commande` datetime NOT NULL,
  `prixtotal_commande` double NOT NULL,
  `modepaiement_commande` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `commande`
--

INSERT INTO `commande` (`id_commande`, `livreur_commande_id`, `statue_commande`, `date_commande`, `prixtotal_commande`, `modepaiement_commande`) VALUES
(2, 1, 'livree', '2025-05-12 21:26:44', 8.8, 'carte'),
(3, 1, 'en_preparation', '2025-05-13 09:27:43', 108, 'carte');

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20250302070932', '2025-03-02 08:11:18', 29),
('DoctrineMigrations\\Version20250302071135', '2025-03-02 08:11:38', 21),
('DoctrineMigrations\\Version20250302073842', '2025-03-02 08:38:56', 80),
('DoctrineMigrations\\Version20250302091355', '2025-03-02 10:14:22', 26),
('DoctrineMigrations\\Version20250509072433', '2025-05-09 09:24:37', 204),
('DoctrineMigrations\\Version20250509102008', '2025-05-09 12:20:15', 69),
('DoctrineMigrations\\Version20250509102747', '2025-05-09 12:27:52', 49);

-- --------------------------------------------------------

--
-- Structure de la table `feed`
--

CREATE TABLE `feed` (
  `id` int(11) NOT NULL,
  `email_feed` varchar(255) NOT NULL,
  `commentaire_feed` longtext NOT NULL,
  `subject_feed` varchar(255) NOT NULL,
  `date_feed` datetime NOT NULL,
  `name_feed` varchar(255) NOT NULL,
  `is_processed` tinyint(1) NOT NULL DEFAULT 0,
  `sentiment` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `feed`
--

INSERT INTO `feed` (`id`, `email_feed`, `commentaire_feed`, `subject_feed`, `date_feed`, `name_feed`, `is_processed`, `sentiment`) VALUES
(1, 'yassinekodia2002@gmail.com', 'aaaaaaaaad', 'aaaa', '2025-03-02 10:59:49', 'yassine', 0, ''),
(24, 'a@gmail.com', 'genial', 'review', '2025-04-27 18:31:22', 'a', 0, 'POSITIVE'),
(25, 'a@gmail.com', 'aaaaaaaaaa', 'excellent', '2025-04-27 18:42:25', 'tes bien', 0, 'POSITIVE'),
(26, 'yassinekodia@gmail.com', 'plainte', 'plainte', '2025-04-27 18:53:49', 'yasisne', 1, 'NEGATIVE'),
(27, 'aaa@gmail.com', 'excellente', 'positif', '2025-04-28 10:21:42', 'aa', 0, 'POSITIVE'),
(28, 'yas@gmail.com', 'message positif excellent', 'positivity', '2025-04-28 10:25:00', 'yassine', 0, 'POSITIVE'),
(29, 'kaidkodia@esprit.tn', 'le site que vous proposez est médiocre', 'voici un sujet', '2025-04-28 13:33:49', 'kais', 1, 'NEGATIVE'),
(31, 'rayen@gmail.com', 'le site est bien fait', 'review', '2025-04-29 08:58:58', 'rayen', 0, 'POSITIVE'),
(32, 'yassinekodia@gmail.com', 'aaaaaaaaaaaa', 'aaa', '2025-05-02 11:41:17', 'yassine', 0, ''),
(33, 'yassinekodia@gmail.com', 'le site que vous proposé est mediocre', 'voici un exemple de sujet', '2025-05-04 15:13:57', 'yassine', 1, 'NEGATIVE'),
(34, 'fghj@gmail.com', 'sqdqsdgyhjksqdc', 'DFGHJK', '2025-05-06 14:23:28', 'SEDFGHJ', 0, ''),
(36, 'nour@gmail.com', 'ce site fonctionne tres bien', 'ce site est incroyable', '2025-05-09 17:50:41', 'nour', 0, 'POSITIVE'),
(37, 'yassine@gmail.com', 'ce site fonctionne tres bien', 'ce site est incroyable', '2025-05-09 16:56:12', 'yassine', 1, 'POSITIVE'),
(38, 'bob@gmail.com', 'le site est incroyablement bon', 'feedback', '2025-05-11 06:20:03', 'bob', 0, 'POSITIVE'),
(39, 'bob@gmail.com', 'ce site est tres mal optimisé', 'feedback', '2025-05-11 06:20:46', 'bob', 0, 'NEGATIVE'),
(40, 'yassinekodia2002@gmail.com', 'Ce site est incoryable, je le recommande.', 'Feedback sur le site', '2025-05-13 09:50:53', 'yassine', 0, 'POSITIVE');

-- --------------------------------------------------------

--
-- Structure de la table `livreur`
--

CREATE TABLE `livreur` (
  `id` int(11) NOT NULL,
  `nom_livreur` varchar(255) NOT NULL,
  `prenom_livreur` varchar(255) NOT NULL,
  `numero_livreur` int(11) NOT NULL,
  `addresse_livreur` varchar(255) NOT NULL,
  `photo_livreur` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `livreur`
--

INSERT INTO `livreur` (`id`, `nom_livreur`, `prenom_livreur`, `numero_livreur`, `addresse_livreur`, `photo_livreur`) VALUES
(1, 'aaaa', 'aaa', 12345678, 'aaa', 'eb1ee857-7eb0-4b7b-b95f-2bc424485e13.jpeg'),
(2, 'yassine', 'kodia', 12345678, '123rrr', '9df4efdf-6d33-4cdf-a5e3-aee9045f813b.jpeg');

-- --------------------------------------------------------

--
-- Structure de la table `livreur_position`
--

CREATE TABLE `livreur_position` (
  `id` int(11) NOT NULL,
  `livreur_id` int(11) NOT NULL,
  `id_commande` int(11) NOT NULL,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `last_update` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `messenger_messages`
--

CREATE TABLE `messenger_messages` (
  `id` bigint(20) NOT NULL,
  `body` longtext NOT NULL,
  `headers` longtext NOT NULL,
  `queue_name` varchar(190) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `messenger_messages`
--

INSERT INTO `messenger_messages` (`id`, `body`, `headers`, `queue_name`, `created_at`, `available_at`, `delivered_at`) VALUES
(13, 'O:36:\\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\\":2:{s:44:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\\";a:1:{s:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\";a:1:{i:0;O:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\":1:{s:55:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\\";s:21:\\\"messenger.bus.default\\\";}}}s:45:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\\";O:51:\\\"Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\\":2:{s:60:\\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0message\\\";O:28:\\\"Symfony\\\\Component\\\\Mime\\\\Email\\\":6:{i:0;s:151:\\\"Nom: yassine\nEmail: yassinekodia2002@gmail.com\nSujet: Feedback sur le site\nDate: 2025-05-13 09:50:53\nMessage: Ce site est incoryable, je le recommande.\\\";i:1;s:5:\\\"utf-8\\\";i:2;N;i:3;N;i:4;a:0:{}i:5;a:2:{i:0;O:37:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\\":2:{s:46:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0headers\\\";a:3:{s:4:\\\"from\\\";a:1:{i:0;O:47:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:4:\\\"From\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:58:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\\";a:1:{i:0;O:30:\\\"Symfony\\\\Component\\\\Mime\\\\Address\\\":2:{s:39:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\\";s:17:\\\"ooyosri@gmail.com\\\";s:36:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\\";s:0:\\\"\\\";}}}}s:2:\\\"to\\\";a:1:{i:0;O:47:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:2:\\\"To\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:58:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\\";a:1:{i:0;O:30:\\\"Symfony\\\\Component\\\\Mime\\\\Address\\\":2:{s:39:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\\";s:23:\\\"greengrowfeed@gmail.com\\\";s:36:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\\";s:0:\\\"\\\";}}}}s:7:\\\"subject\\\";a:1:{i:0;O:48:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:7:\\\"Subject\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:55:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\0value\\\";s:58:\\\"Nouveau Message de yassine concernant Feedback sur le site\\\";}}}s:49:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0lineLength\\\";i:76;}i:1;N;}}s:61:\\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0envelope\\\";N;}}', '[]', 'default', '2025-05-13 07:50:53', '2025-05-13 07:50:53', '2025-05-13 12:38:49');

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

CREATE TABLE `produit` (
  `id` int(11) NOT NULL,
  `id_categories_id` int(11) DEFAULT NULL,
  `quantite` int(11) NOT NULL,
  `nom_produit` varchar(255) NOT NULL,
  `description_produit` varchar(255) NOT NULL,
  `prix_produit` int(11) NOT NULL,
  `disponibilte_produit` varchar(255) NOT NULL,
  `image_produit` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `rating` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`id`, `id_categories_id`, `quantite`, `nom_produit`, `description_produit`, `prix_produit`, `disponibilte_produit`, `image_produit`, `location`, `rating`) VALUES
(1, 1, 49, 'Capteur IOT', 'Ceci est la description', 100, 'oui', '68201954c86fd.png', 'hammamet', 0),
(2, 2, 22, 'produit', 'aaaa', 12, 'En rupture', 'src\\main\\resources\\images\\back.png', '36.61552763134925,10.447998046875002', 0),
(23, 6, 211, 'RGBRG', 'DFDF', 21, 'En rupture', 'src\\main\\resources\\images\\54803137-64de-4683-a3d4-a1b6beb73baa.jpg', NULL, 0),
(26, 5, 11, 'AZ', 'ZZ', 11, 'En rupture', '', NULL, 0),
(27, 6, 11, 'aziz', 'xx', 112, 'En rupture', '', NULL, 0),
(28, 5, 5, 'aa', 'a', 5, 'Disponible', '', NULL, 0),
(29, 5, 5, 'aa', 'a', 6, 'Disponible', 'src\\main\\resources\\images\\d11.png', '36.9018796,10.1898357', 0),
(30, 5, 500, 'tracter', 'vert....', 500, 'Disponible', 'src\\main\\resources\\images\\offer1.jpg', NULL, 0),
(32, 5, 50, 'Mall', 'Mall', 50, 'Disponible', 'src\\main\\resources\\images\\img1.jpg', '34.84434,10.7625', 0),
(33, 6, 44, 'a', 'a', 44, 'Précommande', 'src\\main\\resources\\images\\agricultrices-utilisant-technologies-sans-fil-dans-serre_1313853-126272.jpg', '35.831011364900675, 10.627920111791676', 0),
(34, 6, 11, 'z', 'z', 11, 'Précommande', '', '0,000000,0,000000', 0),
(35, 7, 13, 'ww', 'ww', 32, 'En rupture', 'src\\main\\resources\\images\\back2.png', '', 0),
(36, 6, 12, 'map', 'map', 122, 'Disponible', '', '36.78289206199068,9.168090820312502', 0),
(37, 5, 1, 'ziz', 'ziz', 1, 'Disponible', '', '36.68205766898881,9.406222701072695', 0),
(38, 6, 22, 'ee', 'ee', 222, 'Disponible', '', '36.94769679250732,10.10124206542969', 0),
(39, 7, 11, 'bb', 'bb', 1, 'Disponible', 'src\\main\\resources\\images\\agric.jpg', '36.89280138293986,9.563598632812502', 0),
(40, 7, 121, 'aza', 'aza', 121, 'En rupture', 'src\\main\\resources\\images\\digital-environment-scene.jpg', '37.11549954432918,9.51295852661133', 0),
(41, 5, 14, 'capture de temperature ', 'neuf', 121, 'Disponible', 'src\\main\\resources\\images\\back.png', '36.80048816579081,9.580078125', 0),
(42, 5, 5434, 'capture', 'aaa', 234, 'Disponible', 'src\\main\\resources\\images\\backgournd.jpg', '36.82632511529419,10.19256591796875', 0),
(43, 7, 20, 'produit 2', 'description 2', 120, 'En rupture', '', '36.872480066931246,10.3436279296875', NULL),
(44, 7, 20, 'product 43', 'description 2', 1200, 'En rupture', 'src\\main\\resources\\images\\icon.png', '36.872480066931246,10.3436279296875', NULL),
(45, 2, 100, 'aaa', 'aaa', 50, 'oui', '68224f5ccbe3b.png', 'hammamet', 0),
(46, 3, 50, 'nnn', 'nnn', 50, 'oui', '6822507019b20.png', 'hammamet', 0),
(47, 1, 12, 'azaa', 'aaaaaa', 12, '12', '6823430f5d9ef.png', 'hammamet', 0);

-- --------------------------------------------------------

--
-- Structure de la table `reclamation`
--

CREATE TABLE `reclamation` (
  `id` int(11) NOT NULL,
  `utilisateur_id` int(11) NOT NULL,
  `produit_id` int(11) DEFAULT NULL,
  `description_rec` varchar(255) NOT NULL,
  `statut_rec` varchar(255) NOT NULL,
  `date_rec` datetime NOT NULL,
  `message_reclamation` longtext DEFAULT NULL,
  `historique_conversations` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `reclamation`
--

INSERT INTO `reclamation` (`id`, `utilisateur_id`, `produit_id`, `description_rec`, `statut_rec`, `date_rec`, `message_reclamation`, `historique_conversations`) VALUES
(2, 1, 1, 'Produit défectueux', 'Resolved', '2025-03-02 10:29:40', 'Ceci est le message de la reclamation affichée', NULL),
(3, 3, 1, 'Livraison retardée', 'Pending', '2025-03-02 10:55:37', 'Ceci est le message de la reclamation affichée', '[{\"content\":\"Bonjour ! Je suis votre assistant AI. Je suis là pour vous aider avec votre réclamation concernant : Livraison retardée\\nQue puis-je faire pour vous ?\",\"isFromUser\":false,\"timestamp\":\"2025-03-02 10:55:54\"},{\"content\":\"bonjour\",\"isFromUser\":true,\"timestamp\":\"2025-03-02 10:55:54\"},{\"content\":\"Désolé, je rencontre des difficultés techniques pour le moment. L\'erreur a été enregistrée et sera examinée par notre équipe technique. Veuillez réessayer dans quelques instants.\",\"isFromUser\":false,\"timestamp\":\"2025-03-02 10:55:56\"}]'),
(52, 2, 1, 'Produit Non Conforme', 'Pending', '2025-04-28 19:20:11', 'Ceci est le message de la reclamation affichée', NULL),
(53, 2, 1, 'Problème de Livraison', 'Pending', '2025-04-28 19:23:17', 'Ceci est le message de la reclamation affichée', NULL),
(54, 2, 1, 'Problème de Livraison', 'Pending', '2025-04-28 19:26:46', 'Ceci est le message de la reclamation affichée', NULL),
(55, 2, 1, 'Produit Défectueux', 'Pending', '2025-04-29 12:50:51', 'Ceci est le message de la reclamation affichée', NULL),
(57, 2, 1, 'Produit Défectueux', 'Pending', '2025-04-29 13:23:19', 'Ceci est le message de la reclamation affichée', NULL),
(59, 2, 1, 'Produit Non Conforme', 'Pending', '2025-04-29 14:23:29', 'ce produit n\'est pas conforme', NULL),
(65, 4, 1, 'Produit Défectueux', 'Pending', '2025-05-06 14:17:06', 'Ceci est le message de la reclamation affichée', NULL),
(66, 4, 39, 'Produit Défectueux', 'Pending', '2025-05-06 14:51:28', 'Ceci est le message de la reclamation affichée', NULL),
(67, 6, 1, 'Produit défectueux', 'Pending', '2025-05-09 09:35:07', 'ne marche pas qq', '[{\"content\":\"Bonjour ! Je suis votre assistant AI. Je suis là pour vous aider avec votre réclamation concernant : Produit défectueux\\nQue puis-je faire pour vous ?\",\"isFromUser\":false,\"timestamp\":\"2025-05-09 09:37:13\"},{\"content\":\"bonjour\",\"isFromUser\":true,\"timestamp\":\"2025-05-09 09:37:13\"},{\"content\":\"Bonjour ! Je suis désolé pour la difficulté que vous rencontrez avec notre produit. Nous sommes toujours là pour vous aider et résoudre rapidement les problèmes qui peuvent arriver. Merci de fournir plus d\'informations sur ce qui ne fonctionne pas correctement afin que nous puissions trouver une solution adaptée.\",\"isFromUser\":false,\"timestamp\":\"2025-05-09 09:37:23\"},{\"content\":\"pouvez vous m\'aider ?\",\"isFromUser\":true,\"timestamp\":\"2025-05-09 09:37:41\"},{\"content\":\"Êtes-vous bien, Monsieur\\/Madame ? Je suis désolé pour les problèmes rencontrés avec votre produit. Il est important que je vienne à l\'aide le plus rapidement possible. N\'hésitez pas à me fournir plus d\'informations sur le produit et les symptômes observés afin que je puisse vous aider de la meilleure façon possible. Soyez sûr que nous considérons cette réclamation comme une priorité pour assurer votre satisfaction.\",\"isFromUser\":false,\"timestamp\":\"2025-05-09 09:37:52\"},{\"content\":\"pouvez vous m\'aider ?\",\"isFromUser\":true,\"timestamp\":\"2025-05-09 09:37:52\"},{\"content\":\"Ça va bien, je suis désolé pour la difficulté que vous rencontrez avec notre produit. Je veux vous aider à résoudre ce problème le plus rapidement possible. Pourriez-vous me fournir plus d\'informations sur le produit défectueux et les difficultés que vous avez rencontrées ? Je pourrais alors vous fournir une assistance adaptée à votre besoin, si cela est possible. Merci d\'avance et je souhaite une excellente journée.\",\"isFromUser\":false,\"timestamp\":\"2025-05-09 09:38:03\"},{\"content\":\"comment contacter l\'admin ?\",\"isFromUser\":true,\"timestamp\":\"2025-05-09 09:38:35\"},{\"content\":\" Bonjour, merci de votre message. Je suis désolée pour les inconvénients que vous rencontrez avec notre produit. Vous pouvez contacter l\'équipe d\'assistance clientèle en utilisant le formulaire de contact sur notre site Web ou envoyez-nous un courrier électronique à [support@notremarque.com](mailto:support@notremarque.com). Nous serons heureux de vous aider et d\'expliquer comment nous pouvons résoudre ce problème pour vous. Je souhaite une excellente journée.\",\"isFromUser\":false,\"timestamp\":\"2025-05-09 09:38:49\"},{\"content\":\"comment contacter l\'admin ?\",\"isFromUser\":true,\"timestamp\":\"2025-05-09 09:38:51\"},{\"content\":\" Bonjour, merci de vous adresser à moi pour la réclamation concernant votre produit. Je suis désolé que ce n\'ait pas satisfait vos attentes. Vous pouvez contacter l\'équipe d\'administration en nous fournissant quelques informations :\\n\\n* Votre adresse e-mail\\n* Le numéro de commande\\n* Le produit en question (modèle et couleur)\\n* Une brève description du problème que vous rencontrez\\n\\nNous ferons notre possible pour répondre à votre demande au plus vite. Pendant ce temps, merci de bien vouloir patienter. Nous sommes désolés des incommodités que cela peut provoquer et nous apprécions particulièrement vos commentaires qui nous aident à améliorer notre service.\\n\\nN\'hésitez pas à nous contacter si vous avez d\'autres questions ou besoin d\'aide. Nous sommes ici pour vous servir.\\n\\nA bientôt,\\nL\'équipe de clientèle de [Votre entreprise]\",\"isFromUser\":false,\"timestamp\":\"2025-05-09 09:39:14\"},{\"content\":\"bonjour\",\"isFromUser\":true,\"timestamp\":\"2025-05-10 08:40:06\"},{\"content\":\" Bonjour, merci de contacter notre équipe client. Nous sommes désolés d\'apprendre que vous avez rencontré un problème avec votre produit. Pour répondre au plus rapidement à vos besoins, pourriez-vous m\'indiquer le numéro de série et la description du problème que vous rencontrez avec votre produit ? Nous ferons de notre mieux pour trouver une solution rapide.\",\"isFromUser\":false,\"timestamp\":\"2025-05-10 08:40:16\"}]'),
(68, 7, 1, 'Produit défectueux', 'In Progress', '2025-05-09 11:49:21', 'ce produit ne marche pas', NULL),
(69, 7, 1, 'Livraison retardée', 'Pending', '2025-05-09 11:56:36', 'Ceci est le message de la reclamation affichée', '[{\"content\":\"Bonjour ! Je suis votre assistant AI. Je suis là pour vous aider avec votre réclamation concernant : Livraison retardée\\nQue puis-je faire pour vous ?\",\"isFromUser\":false,\"timestamp\":\"2025-05-09 11:58:49\"},{\"content\":\"bonjour\",\"isFromUser\":true,\"timestamp\":\"2025-05-09 11:58:49\"},{\"content\":\"Bonjour! Je suis désolé pour l\'inconvenient que vous avez rencontré avec notre livraison. Il semble qu\'il y ait un retard et je veux reconnaître à nouveau mon émotion à cette situation. Quelques questions si vous n\'avez pas déjà contacté le support technique? Je peux vous les poser pour voir s\'il existe une solution possible pour résoudre rapidement votre problème. Nous nous efforcerons de trouver un compromis qui conviendra à tous et de livrer vos produits le plus tôt possible.\",\"isFromUser\":false,\"timestamp\":\"2025-05-09 11:59:18\"},{\"content\":\"bonjour\",\"isFromUser\":true,\"timestamp\":\"2025-05-09 11:59:19\"},{\"content\":\" Bonjour ! Merci de vous être contacté. Je suis désolé pour cette retardee dans votre livraison. Nous comprenons que cela peut entraîner de l\'inconvenient, et nous sommes tristes que ce soit le cas.\\n\\nEn quoi pourrais-je vous aider ? Pourrions-nous vous donner plus d\'information sur votre livraison actuelle ou pourrions-nous trouver une solution possible pour accélérer son arrivée? Nous sommes à votre service pour assurer la meilleure expérience client.\\n\\nEn attendant, veuillez accepter mes excuses pour cette inconvenience. Si vous avez d\'autres questions ou besoin de plus d\'informations, n\'hésitez pas à me contacter.\",\"isFromUser\":false,\"timestamp\":\"2025-05-09 11:59:36\"},{\"content\":\"bonjour\",\"isFromUser\":true,\"timestamp\":\"2025-05-09 12:07:56\"},{\"content\":\"Bonjour, je suis le représentant de notre entreprise et j\'ai la grande satisfaction de vous accueillir. Je comprends que vous avez une réclamation concernant votre livraison et je suis désolé pour cette inconvenience. Nous sommes conscients du temps précieux que représente le courrier et nous travaillons activement pour vous fournir des informations sur l\'état de votre expédition. Soyez sûr que nous ferons tout ce qui est en nos moyens pour raccourcir les délais et vous assurer d\'un service de qualité. Merci de votre patience et n\'hésitez pas à me contacter si vous avez besoin de plus d\'informations.\\n\\nSincèrement à votre écoute, [Votre Nom]\",\"isFromUser\":false,\"timestamp\":\"2025-05-09 12:08:13\"},{\"content\":\"hello\",\"isFromUser\":true,\"timestamp\":\"2025-05-09 12:14:19\"},{\"content\":\" Bonjour ! Je suis très désolé pour la déception que cela peut provoquer, mais je vais faire tout ce qui est dans mon pouvoir pour vous aider. Votre colis devrait arriver bientôt. Pour être sûr de n\'avoir pas manqué notre courrier, veuillez me dire votre numéro de commande et je rechercherai l\'état de cette livraison. Merci pour votre patience et pour votre compréhension. Nous nous efforcerons toujours d\'améliorer nos services.\",\"isFromUser\":false,\"timestamp\":\"2025-05-09 12:14:32\"}]'),
(70, 6, 1, 'Produit défectueux', 'Resolved', '2025-05-09 17:36:08', 'X a dit que le produit été defectueux', '[{\"content\":\"Bonjour ! Je suis votre assistant AI. Je suis là pour vous aider avec votre réclamation concernant : Produit défectueux\\nQue puis-je faire pour vous ?\",\"isFromUser\":false,\"timestamp\":\"2025-05-09 17:38:36\"},{\"content\":\"bonjour pouvez vous m\'aider\",\"isFromUser\":true,\"timestamp\":\"2025-05-09 17:38:36\"},{\"content\":\" Bonjour, je suis heureux de vous aider. Je suis désolé pour les problèmes que vous rencontrez avec notre produit. Pourriez-vous m\'expliquer le détail du problème ? Cela nous aidera à trouver une solution rapidement et efficacement. Nous considérons que votre satisfaction est notre priorité absolue et nous faisons tout ce qui est nécessaire pour vous satisfaire.\",\"isFromUser\":false,\"timestamp\":\"2025-05-09 17:38:52\"},{\"content\":\"bonjour pouvez vous m\'aider\",\"isFromUser\":true,\"timestamp\":\"2025-05-09 17:38:54\"},{\"content\":\"Bonjour! Bien sûr, je suis à votre disposition pour vous aider. Je sens que vous avez un problème avec un produit récemment acheté chez nous et j\'ai l\'intention de faire de mon mieux pour vous servir. Pourriez-vous me préciser ce que constitue le problème que vous rencontrez? Je suis là pour vous apporter une solution efficace et rapide. Veuillez ne pas hésiter à me poser toutes les questions qui vous viennent à l\'esprit.\",\"isFromUser\":false,\"timestamp\":\"2025-05-09 17:39:08\"}]'),
(74, 4, 1, 'Produit Défectueux', 'Resolved', '2025-05-09 17:05:28', 'Ceci est le message de la reclamation affichée', NULL),
(75, 6, 1, 'Livraison retardée', 'Pending', '2025-05-10 07:24:18', 'abcefgggggggggg', NULL),
(76, 6, 1, 'Produit ne correspond pas à la description', 'In Progress', '2025-05-10 07:27:17', 'azzzzzzzzzzzzza', NULL),
(77, 4, 44, 'Produit Non Conforme', 'Pending', '2025-05-11 05:59:06', 'aaaaaaaaaaaaa', NULL),
(78, 11, 1, 'Livraison retardée', 'In Progress', '2025-05-13 09:42:48', 'Bonjour, ma livraison n\'est pas encore arrivée, est ce que c\'est normal ?', '[{\"content\":\"Bonjour ! Je suis votre assistant AI. Je suis là pour vous aider avec votre réclamation concernant : Livraison retardée\\nQue puis-je faire pour vous ?\",\"isFromUser\":false,\"timestamp\":\"2025-05-13 09:46:39\"},{\"content\":\"bonjour\",\"isFromUser\":true,\"timestamp\":\"2025-05-13 09:46:39\"},{\"content\":\"Désolé, je rencontre des difficultés techniques pour le moment. L\'erreur a été enregistrée et sera examinée par notre équipe technique. Veuillez réessayer dans quelques instants.\",\"isFromUser\":false,\"timestamp\":\"2025-05-13 09:47:40\"},{\"content\":\"bonjour\",\"isFromUser\":true,\"timestamp\":\"2025-05-13 09:48:11\"},{\"content\":\"Désolé, je rencontre des difficultés techniques pour le moment. L\'erreur a été enregistrée et sera examinée par notre équipe technique. Veuillez réessayer dans quelques instants.\",\"isFromUser\":false,\"timestamp\":\"2025-05-13 09:49:11\"}]');

-- --------------------------------------------------------

--
-- Structure de la table `reclamation_message`
--

CREATE TABLE `reclamation_message` (
  `id` int(11) NOT NULL,
  `reclamation_id` int(11) DEFAULT NULL,
  `sender_id` int(11) DEFAULT NULL,
  `content` longtext NOT NULL,
  `sent_at` datetime NOT NULL,
  `is_from_admin` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `reclamation_message`
--

INSERT INTO `reclamation_message` (`id`, `reclamation_id`, `sender_id`, `content`, `sent_at`, `is_from_admin`) VALUES
(3, 2, 1, '11111111111111', '2025-03-02 10:29:40', 0),
(4, 3, 3, '111111111111111', '2025-03-02 10:55:37', 0),
(5, 3, NULL, '2222222222222', '2025-03-02 10:55:50', 0),
(6, 2, 1, 'bonjour', '2025-03-02 11:53:28', 1),
(7, 2, 1, 'hello', '2025-04-27 12:03:14', 1),
(8, 2, 1, 'hey', '2025-04-27 12:06:41', 1),
(9, 2, 1, 'eee', '2025-04-27 12:06:45', 1),
(10, 3, 1, 'ss', '2025-04-27 12:12:08', 1),
(11, 2, 1, 'd', '2025-04-27 12:13:58', 1),
(12, 3, 1, 'g', '2025-04-27 12:14:04', 1),
(13, 2, 1, 'a', '2025-04-27 12:14:57', 1),
(14, 2, 1, 'z', '2025-04-27 12:19:45', 1),
(15, 3, 1, 'a', '2025-04-27 12:24:08', 1),
(24, 2, 1, 'aaaa', '2025-04-27 12:43:27', 0),
(26, 2, 1, 'aaa', '2025-04-27 12:44:24', 0),
(39, 54, 2, 'hello', '2025-04-29 09:07:03', 0),
(40, 54, 1, 'aa', '2025-04-29 10:45:22', 1),
(41, 54, 2, 'aa', '2025-04-29 10:48:38', 0),
(42, 53, 1, 'aaa', '2025-04-29 11:38:50', 1),
(43, 52, 2, 'aaaaaa', '2025-04-29 13:23:28', 0),
(44, 57, 2, 'aaaaaaa', '2025-04-29 13:23:46', 0),
(45, 52, 2, 's', '2025-04-29 13:32:41', 0),
(46, 65, 4, 'HHHH', '2025-05-06 14:17:18', 0),
(47, 65, 4, 'HELLO', '2025-05-06 14:51:52', 0),
(48, 67, 6, 'ne marche pas qq', '2025-05-09 09:35:07', 0),
(49, 67, NULL, 'hello', '2025-05-09 09:36:05', 0),
(50, 68, 7, 'ce produit ne marche pas', '2025-05-09 11:49:21', 0),
(51, 68, 1, 'aaa', '2025-05-09 10:53:29', 1),
(52, 69, 7, 'aaaaaaaaaa', '2025-05-09 11:56:37', 0),
(53, 69, 1, 'bbbb', '2025-05-09 11:04:57', 1),
(54, 69, NULL, 'aaa', '2025-05-09 12:06:14', 0),
(55, 69, 1, 'bbb', '2025-05-09 11:06:35', 1),
(56, 70, 6, 'nour a dit que le produit été defectueux', '2025-05-09 17:36:12', 0),
(57, 70, NULL, 'bonjour c\'est a propos de ma reclamaiton', '2025-05-09 17:37:14', 0),
(58, 70, 1, 'oui votre reclamation est en train d\'etre traitée', '2025-05-09 16:41:47', 1),
(59, 74, 4, 'hello', '2025-05-09 17:06:58', 0),
(60, 74, 7, 'hello', '2025-05-09 18:07:36', 1),
(61, 75, 6, 'abcefgggggggggg', '2025-05-10 07:24:23', 0),
(62, 76, 6, 'azzzzzzzzzzzzza', '2025-05-10 07:27:17', 0),
(63, 74, 4, 'hello friend', '2025-05-10 06:45:48', 0),
(64, 74, 7, 'hello 2', '2025-05-10 07:46:21', 1),
(65, 66, 4, 'hello', '2025-05-10 07:35:55', 0),
(66, 66, 7, 'hi', '2025-05-10 08:36:27', 1),
(67, 67, NULL, 'hello', '2025-05-10 08:38:53', 0),
(68, 67, NULL, 'bonjour', '2025-05-10 08:39:21', 0),
(69, 67, 1, 'bonjour 2', '2025-05-10 07:39:33', 1),
(70, 67, 1, 'hi', '2025-05-10 08:43:33', 1),
(71, 77, 4, 'hey', '2025-05-11 05:59:16', 0),
(72, 78, 11, 'Bonjour, ma livraison n\'est pas encore arrivée, est ce que c\'est normal ?', '2025-05-13 09:42:49', 0),
(73, 78, 1, 'on est désolés de ce probleme, elle arrivera dans les plus brefs delais', '2025-05-13 09:45:10', 1);

-- --------------------------------------------------------

--
-- Structure de la table `rendu`
--

CREATE TABLE `rendu` (
  `id_rendu` int(11) NOT NULL,
  `message_rendu` varchar(255) NOT NULL,
  `type_rendu` varchar(255) NOT NULL,
  `Date_envoi_rendu` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `rendu`
--

INSERT INTO `rendu` (`id_rendu`, `message_rendu`, `type_rendu`, `Date_envoi_rendu`) VALUES
(54, 'Jade Plant 56', 'Succulents', '2025-04-28'),
(55, 'Spinach 55', 'Vegetables', '2025-04-28'),
(56, 'Orchid 70', 'Flowering Plants', '2025-04-28'),
(57, 'African Violet 100', 'Flowering Plants', '2025-04-28'),
(58, 'Oregano 70', 'Herbs', '2025-04-28'),
(59, 'Oregano 81', 'Herbs', '2025-04-28'),
(60, 'Aloe Vera 42', 'Succulents', '2025-04-28'),
(61, 'Aloe Vera 40', 'Succulents', '2025-04-28'),
(62, 'Lime 84', 'Fruits', '2025-04-28'),
(63, 'Basil 26', 'Herbs', '2025-04-28'),
(64, 'Thyme 78', 'Herbs', '2025-04-28'),
(65, 'Lime 13', 'Fruits', '2025-04-28'),
(66, 'Bell Pepper 42', 'Vegetables', '2025-04-28'),
(67, 'Rosemary 39', 'Herbs', '2025-04-28'),
(68, 'Rubber Plant 65', 'Trees', '2025-04-28'),
(69, 'Zebra Cactus 99', 'Succulents', '2025-04-28'),
(70, 'Blueberry 17', 'Fruits', '2025-04-28'),
(71, 'Bell Pepper 58', 'Vegetables', '2025-04-28'),
(73, 'Tomato 36', 'Vegetables', '2025-04-28'),
(74, 'Echeveria 36', 'Succulents', '2025-04-28'),
(75, 'African Violet 42', 'Flowering Plants', '2025-04-28'),
(76, 'Blueberry 21', 'Fruits', '2025-04-29'),
(77, 'Boxwood 30', 'Shrubs', '2025-04-29'),
(78, 'Carrot 24', 'Vegetables', '2025-04-29'),
(79, 'African Violet 63', 'Flowering Plants', '2025-04-29'),
(80, 'Aloe Vera 90', 'Succulents', '2025-04-29'),
(81, 'Mint 5', 'Herbs', '2025-04-29'),
(82, 'Gardenia 74', 'Shrubs', '2025-04-29'),
(83, 'Norfolk Pine 81', 'Trees', '2025-04-29'),
(84, 'Orchid 34', 'Flowering Plants', '2025-04-29'),
(85, 'Oregano 91', 'Herbs', '2025-05-06'),
(86, 'Dwarf Orange 75', 'Fruits', '2025-05-13');

-- --------------------------------------------------------

--
-- Structure de la table `reset_password_request`
--

CREATE TABLE `reset_password_request` (
  `id` int(11) NOT NULL,
  `utilisateur_id` int(11) NOT NULL,
  `selector` varchar(20) NOT NULL,
  `hashed_token` varchar(100) NOT NULL,
  `requested_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `expires_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id_user` int(11) NOT NULL,
  `nom_user` varchar(255) NOT NULL,
  `prenom_user` varchar(255) NOT NULL,
  `email_user` varchar(255) NOT NULL,
  `mot_de_passe_user` varchar(255) NOT NULL,
  `adresse_user` varchar(255) NOT NULL,
  `ville_user` varchar(255) NOT NULL,
  `code_postal_user` varchar(255) NOT NULL,
  `telephone_user` varchar(255) NOT NULL,
  `role_user` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id_user`, `nom_user`, `prenom_user`, `email_user`, `mot_de_passe_user`, `adresse_user`, `ville_user`, `code_postal_user`, `telephone_user`, `role_user`, `is_active`) VALUES
(1, 'kodia', 'yassine', 'medkhalil.hammami@gmail.com', 'slim2002', '123 paris', 'paris', '2582', '55555555', 'ROLE_ADMIN', 1),
(2, 'kodia', 'yassine', 'b', 'b', '123 paris', 'paris', '2582', '55555555', 'ROLE_CLIENT', 1),
(3, 'mr x', 'yass', 'a', 'a', '123 tn', 'tunisie', '2020', '50555555', 'ROLE_ADMIN', 1),
(4, 'kodia', 'yassine', 'yassinekodia2002@gmail.com', 'azerty1', 'BB', 'BBB', '1005', '25845846', 'ROLE_CLIENT', 1),
(5, 'SQSQSQ', 'SQSQ', 'gharbi.selim01@gmail.com', 'selim2002', 'SDQ', 'DS', '1008', '23456789', 'ROLE_CLIENT', 1),
(6, 'yassine', 'kodia', 'a@gmail.com', '$2y$13$iS72N6ullghuwgpG8nDGlepX.gQJlkq.uLrNzxfTdhrR/CbvgVog.', '123 rue xxx', 'paris', '12345', '58633355', 'ROLE_CLIENT', 1),
(7, 'yassine', 'kodia', 'b@gmail.com', '$2y$13$iS72N6ullghuwgpG8nDGlepX.gQJlkq.uLrNzxfTdhrR/CbvgVog.', '123 rue xxx', 'paris', '12345', '58633355', 'ROLE_ADMIN', 1),
(8, 'kodia', 'yassine', 'yassinekodia2002@gmail.com', 'azerty2', 'BB', 'BBB', '1005', '25845846', 'ROLE_ADMIN', 1),
(10, 'yassine', 'kodia', 'yassinekodia2002@gmail.com', '$2y$13$tzWZA1j6NHJgzOnJ4eL5JOclB.GmVAm5lSx3UjrWDpnM25GWVp6DW', '123 rue xxx', 'paris', '12345', '12345678', 'ROLE_CLIENT', 1),
(11, 'yassine', 'kodia', 'greaaaengrowfeed@gmail.com', '$2y$13$tzWZA1j6NHJgzOnJ4eL5JOclB.GmVAm5lSx3UjrWDpnM25GWVp6DW', '123 rue xxx', 'paris', '12345', '12345678', 'ROLE_CLIENT', 1),
(12, 'yassine', 'kodia', 'greengrowfeed@gmail.com', '$2y$13$.kH4NcyN.gS2UmRQ2XT8ee2slxFIAGcHPM9KA6qtqJ55dAT.CJVD.', '123 rue xxx', 'paris', '12345', '12345678', 'ROLE_CLIENT', 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `alerte`
--
ALTER TABLE `alerte`
  ADD PRIMARY KEY (`id_alerte`);

--
-- Index pour la table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `chat_message`
--
ALTER TABLE `chat_message`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_FAB3FC162D6BA2D9` (`reclamation_id`);

--
-- Index pour la table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`id_commande`),
  ADD KEY `IDX_6EEAA67DB79CC9C2` (`livreur_commande_id`);

--
-- Index pour la table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Index pour la table `feed`
--
ALTER TABLE `feed`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `livreur`
--
ALTER TABLE `livreur`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `livreur_position`
--
ALTER TABLE `livreur_position`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_CD4C739DF8646701` (`livreur_id`),
  ADD KEY `IDX_CD4C739D3E314AE8` (`id_commande`);

--
-- Index pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  ADD KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  ADD KEY `IDX_75EA56E016BA31DB` (`delivered_at`);

--
-- Index pour la table `produit`
--
ALTER TABLE `produit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_29A5EC271C3AC5D2` (`id_categories_id`);

--
-- Index pour la table `reclamation`
--
ALTER TABLE `reclamation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_CE606404FB88E14F` (`utilisateur_id`),
  ADD KEY `IDX_CE606404F347EFB` (`produit_id`);

--
-- Index pour la table `reclamation_message`
--
ALTER TABLE `reclamation_message`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_6797EEEE2D6BA2D9` (`reclamation_id`),
  ADD KEY `IDX_6797EEEEF624B39D` (`sender_id`);

--
-- Index pour la table `rendu`
--
ALTER TABLE `rendu`
  ADD PRIMARY KEY (`id_rendu`);

--
-- Index pour la table `reset_password_request`
--
ALTER TABLE `reset_password_request`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_7CE748AFB88E14F` (`utilisateur_id`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `alerte`
--
ALTER TABLE `alerte`
  MODIFY `id_alerte` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `chat_message`
--
ALTER TABLE `chat_message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT pour la table `commande`
--
ALTER TABLE `commande`
  MODIFY `id_commande` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `feed`
--
ALTER TABLE `feed`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT pour la table `livreur`
--
ALTER TABLE `livreur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `livreur_position`
--
ALTER TABLE `livreur_position`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `produit`
--
ALTER TABLE `produit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT pour la table `reclamation`
--
ALTER TABLE `reclamation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT pour la table `reclamation_message`
--
ALTER TABLE `reclamation_message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT pour la table `rendu`
--
ALTER TABLE `rendu`
  MODIFY `id_rendu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT pour la table `reset_password_request`
--
ALTER TABLE `reset_password_request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `chat_message`
--
ALTER TABLE `chat_message`
  ADD CONSTRAINT `FK_FAB3FC162D6BA2D9` FOREIGN KEY (`reclamation_id`) REFERENCES `reclamation` (`id`);

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `FK_6EEAA67DB79CC9C2` FOREIGN KEY (`livreur_commande_id`) REFERENCES `livreur` (`id`);

--
-- Contraintes pour la table `livreur_position`
--
ALTER TABLE `livreur_position`
  ADD CONSTRAINT `FK_CD4C739D3E314AE8` FOREIGN KEY (`id_commande`) REFERENCES `commande` (`id_commande`),
  ADD CONSTRAINT `FK_CD4C739DF8646701` FOREIGN KEY (`livreur_id`) REFERENCES `livreur` (`id`);

--
-- Contraintes pour la table `produit`
--
ALTER TABLE `produit`
  ADD CONSTRAINT `FK_29A5EC271C3AC5D2` FOREIGN KEY (`id_categories_id`) REFERENCES `categories` (`id`);

--
-- Contraintes pour la table `reclamation`
--
ALTER TABLE `reclamation`
  ADD CONSTRAINT `FK_CE606404F347EFB` FOREIGN KEY (`produit_id`) REFERENCES `produit` (`id`),
  ADD CONSTRAINT `FK_CE606404FB88E14F` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`id_user`);

--
-- Contraintes pour la table `reclamation_message`
--
ALTER TABLE `reclamation_message`
  ADD CONSTRAINT `FK_6797EEEE2D6BA2D9` FOREIGN KEY (`reclamation_id`) REFERENCES `reclamation` (`id`),
  ADD CONSTRAINT `FK_6797EEEEF624B39D` FOREIGN KEY (`sender_id`) REFERENCES `utilisateur` (`id_user`);

--
-- Contraintes pour la table `reset_password_request`
--
ALTER TABLE `reset_password_request`
  ADD CONSTRAINT `FK_7CE748AFB88E14F` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`id_user`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
