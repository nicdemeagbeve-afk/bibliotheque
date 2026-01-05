-- ========================================
-- BIBLIOTHÈQUE NUMÉRIQUE - BASE DE DONNÉES COMPLÈTE
-- Date: 3 Janvier 2026
-- ========================================

-- Création de la base de données
CREATE DATABASE IF NOT EXISTS `bibliotheques_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `bibliotheques_db`;

-- ========================================
-- TABLE: LIVRES
-- ========================================
CREATE TABLE IF NOT EXISTS `livres` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titre` varchar(100) NOT NULL,
  `auteur` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `maison_edition` varchar(100) DEFAULT NULL,
  `nombre_exemplaire` int(11) DEFAULT NULL,
  `image_data` longblob DEFAULT NULL,
  `image_type` varchar(50) DEFAULT NULL,
  `pdf_data` longblob DEFAULT NULL,
  `pdf_type` varchar(50) DEFAULT NULL,
  `date_creation` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ========================================
-- TABLE: LECTEURS
-- ========================================
CREATE TABLE IF NOT EXISTS `lecteurs` (
  `id_lecteur` int(11) NOT NULL AUTO_INCREMENT,
  `nom_lecteur` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `date_inscription` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_lecteur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ========================================
-- TABLE: LISTE_LECTURE
-- ========================================
CREATE TABLE IF NOT EXISTS `liste_lecture` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_livre` int(11) NOT NULL,
  `id_lecteur` varchar(128) DEFAULT NULL,
  `date_ajout` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_livre` (`id_livre`),
  CONSTRAINT `fk_livre` FOREIGN KEY (`id_livre`) REFERENCES `livres` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ========================================
-- TABLE: FAVORIS
-- ========================================
CREATE TABLE IF NOT EXISTS `favoris` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_livre` int(11) NOT NULL,
  `id_lecteur` varchar(128) DEFAULT NULL,
  `date_ajout` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_favori` (`id_livre`, `id_lecteur`),
  KEY `fk_livre_favori` (`id_livre`),
  CONSTRAINT `fk_livre_favori` FOREIGN KEY (`id_livre`) REFERENCES `livres` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ========================================
-- TABLE: HISTORIQUE
-- ========================================
CREATE TABLE IF NOT EXISTS `historique` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_livre` int(11) NOT NULL,
  `id_lecteur` varchar(128) DEFAULT NULL,
  `date_consultation` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_livre_historique` (`id_livre`),
  CONSTRAINT `fk_livre_historique` FOREIGN KEY (`id_livre`) REFERENCES `livres` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ========================================
-- DONNÉES D'EXEMPLE
-- ========================================
-- Décommentez les lignes suivantes pour ajouter des données d'exemple

/*
INSERT INTO `livres` (`titre`, `auteur`, `description`, `maison_edition`, `nombre_exemplaire`) VALUES
('Exemple Livre 1', 'Auteur 1', 'Description du livre 1', 'Maison d\'édition 1', 5),
('Exemple Livre 2', 'Auteur 2', 'Description du livre 2', 'Maison d\'édition 2', 3),
('Exemple Livre 3', 'Auteur 3', 'Description du livre 3', 'Maison d\'édition 3', 7);

INSERT INTO `lecteurs` (`nom_lecteur`, `email`) VALUES
('Lecteur 1', 'lecteur1@example.com'),
('Lecteur 2', 'lecteur2@example.com');
*/

-- ========================================
-- FIN DU SCRIPT SQL
-- ========================================
-- La base de données est prête à l'emploi!
-- Les tables sont créées avec les bonnes relations et contraintes.
-- Les données d'exemple sont commentées et peuvent être décommentées si nécessaire.
