-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : dim. 19 avr. 2026 à 08:53
-- Version du serveur : 10.4.27-MariaDB
-- Version de PHP : 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `bproject_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `clients`
--

CREATE TABLE `clients` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `clients`
--

INSERT INTO `clients` (`id`, `name`, `email`, `phone`, `company`, `created_at`) VALUES
(1, 'Obi', 'obi@test.com', '66010102', 'Almanna Company', '2026-04-19 04:36:13'),
(2, 'Khalid', 'khalid@test.com', '66000907', 'Soukkabir', '2026-04-19 04:36:13'),
(3, 'ousmane adji', 'oussou@test.com', '66010506', 'MoovBouti', '2026-04-19 05:27:05');

-- --------------------------------------------------------

--
-- Structure de la table `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `client_id` int(11) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `progress` int(3) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `projects`
--

INSERT INTO `projects` (`id`, `title`, `description`, `client_id`, `start_date`, `end_date`, `status`, `progress`, `created_at`) VALUES
(1, 'Deliver Fast', 'Deliver Fast est une appli de livraison de nourriture...', 1, '2026-04-04', '2026-04-07', 'En cours', 30, '2026-04-19 04:36:13'),
(2, 'comptia-pro', 'comptia-pro est une application de comptabilité de la zone cemac en respectant les règles OHADA et travail sur deux modes :hors ligne et cloud...', 2, '2026-04-07', '2026-06-10', 'En cours', 10, '2026-04-19 05:25:59');

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `roles`
--

INSERT INTO `roles` (`id`, `name`) VALUES
(1, 'Admin'),
(2, 'Chef de projet'),
(3, 'Développeur'),
(4, 'Stagiaire');

-- --------------------------------------------------------

--
-- Structure de la table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `project_id` int(11) DEFAULT NULL,
  `assigned_to` int(11) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `priority` enum('Basse','Moyenne','Haute') NOT NULL DEFAULT 'Moyenne',
  `due_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `tasks`
--

INSERT INTO `tasks` (`id`, `title`, `description`, `project_id`, `assigned_to`, `status`, `priority`, `due_date`, `created_at`) VALUES
(1, 'Créer interface login', 'Développer la page de connexion avec validation des utilisateurs', 1, 3, 'En cours', 'Basse', '2026-04-24', '2026-04-19 04:36:14'),
(2, 'Iniatialiser le mono reporepo Turbo avec 10 packages:desktop, renderer...', 'architecture de base', 2, 5, 'Terminé', 'Moyenne', '2026-04-10', '2026-04-19 05:33:02'),
(3, 'Page d\'accueil', '', 1, 3, 'À faire', 'Moyenne', '2026-04-28', '2026-04-19 05:34:53'),
(4, 'Configurer TypeScript v5 strict mode(tsconfig.base.json)', '', 2, 5, 'Bloquée', 'Haute', '2026-04-25', '2026-04-19 05:38:45'),
(5, 'création de la base de données', '', 1, 3, 'Terminé', 'Haute', '2026-04-10', '2026-04-19 05:43:01'),
(6, 'Tableau de bord admin', '', 1, 2, 'En cours', 'Moyenne', '2026-04-23', '2026-04-19 05:44:26');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role_id`, `created_at`) VALUES
(1, 'Ali', 'admin@test.com', '$2y$10$R78Qr9AaoUofJlEE8YvYsOt8D3hbO7M6PK9wAw2kZ42usXR10Y6yW', 1, '2026-04-19 04:36:13'),
(2, 'Hassan', 'hassan@test.com', '$2y$10$fbwegs1bOGv5fJ5ju0VC1OBV6RETBpcc7Z0E1u9Rcw4FcOkGEPKfu', 2, '2026-04-19 04:36:13'),
(3, 'Dev Junior', 'dev@test.com', 'dev123', 3, '2026-04-19 04:36:13'),
(4, 'Stagiaire bruno', 'stagiaire@test.com', '$2y$10$MR5W2Tz2kZHLGv9uiVglDOsv2WDmbczpERcG2eTvgxu67cwwV.toG', 4, '2026-04-19 04:36:13'),
(5, 'Dev Saleh', 'saleh@test.com', '$2y$10$8B.zWWT.pYgQdq4Gk/STfuQXAS8cFkF5Nuo8rtH0Q1aYfjd9/w.f.', 3, '2026-04-19 05:19:17'),
(6, 'stagiaire Yaro', 'yaro@test.com', '$2y$10$03/PHEcJg6A7P6f1NcQs/.g1LIrteZxWv5E79POfC3RCNXKoY6/OW', 4, '2026-04-19 05:21:31');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`);

--
-- Index pour la table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_id` (`project_id`),
  ADD KEY `assigned_to` (`assigned_to`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`);

--
-- Contraintes pour la table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`),
  ADD CONSTRAINT `tasks_ibfk_2` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
