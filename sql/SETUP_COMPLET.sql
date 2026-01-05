CREATE TABLE IF NOT EXISTS `livres` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `titre` varchar(100) NOT NULL,
  `auteur` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `maison_edition` varchar(100) DEFAULT NULL,
  `nombre_exemplaire` int(11) DEFAULT 1,
  `image_data` longblob DEFAULT NULL COMMENT 'Image du livre en BLOB',
  `image_type` varchar(50) DEFAULT NULL COMMENT 'Type MIME de l\'image (image/jpeg, image/png, etc)',
  `pdf_data` longblob DEFAULT NULL COMMENT 'PDF du livre en BLOB',
  `pdf_type` varchar(50) DEFAULT NULL COMMENT 'Type MIME du PDF (application/pdf)',
  `date_creation` timestamp DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_titre` (`titre`),
  INDEX `idx_auteur` (`auteur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `lecteurs` (
  `id_lecteur` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `nom_lecteur` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL UNIQUE,
  `mot_de_passe` varchar(255) NOT NULL COMMENT 'Mot de passe hashé avec password_hash()',
  `role` enum('user', 'admin') DEFAULT 'user' COMMENT 'user = lecteur normal, admin = administrateur',
  `date_inscription` timestamp DEFAULT CURRENT_TIMESTAMP,
  `dernier_acces` timestamp NULL DEFAULT NULL COMMENT 'Dernière connexion',
  INDEX `idx_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE IF NOT EXISTS `liste_lecture` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `id_livre` int(11) NOT NULL,
  `id_lecteur` varchar(128) DEFAULT NULL COMMENT 'Session ID ou lecteur ID',
  `date_ajout` timestamp DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `uniq_lecture` (`id_livre`, `id_lecteur`),
  KEY `fk_livre_lecture` (`id_livre`),
  CONSTRAINT `fk_livre_lecture` FOREIGN KEY (`id_livre`) REFERENCES `livres` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE IF NOT EXISTS `favoris` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `id_livre` int(11) NOT NULL,
  `id_lecteur` varchar(128) DEFAULT NULL COMMENT 'Session ID ou lecteur ID',
  `date_ajout` timestamp DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `uniq_favori` (`id_livre`, `id_lecteur`),
  KEY `fk_livre_favori` (`id_livre`),
  CONSTRAINT `fk_livre_favori` FOREIGN KEY (`id_livre`) REFERENCES `livres` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE IF NOT EXISTS `historique` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `id_livre` int(11) NOT NULL,
  `id_lecteur` varchar(128) DEFAULT NULL COMMENT 'Session ID ou lecteur ID',
  `date_consultation` timestamp DEFAULT CURRENT_TIMESTAMP,
  KEY `fk_livre_historique` (`id_livre`),
  CONSTRAINT `fk_livre_historique` FOREIGN KEY (`id_livre`) REFERENCES `livres` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `livres` (`titre`, `auteur`, `description`, `maison_edition`, `nombre_exemplaire`) 
VALUES 
('Le Seigneur des Anneaux', 'J.R.R. Tolkien', 'L\'histoire épique de Frodon et de l\'anneau unique', 'Pocket', 5),
('1984', 'George Orwell', 'Un roman dystopique sur un régime totalitaire', 'Gallimard', 3),
('Le Petit Prince', 'Antoine de Saint-Exupéry', 'Un conte philosophique poétique', 'Gallimard', 10);

INSERT INTO `lecteurs` (`nom_lecteur`, `email`) 
VALUES 
('Jean Dupont', 'jean.dupont@example.com'),
('Marie Martin', 'marie.martin@example.com');

