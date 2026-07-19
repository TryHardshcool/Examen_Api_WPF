-- Base de données : gestion_vehicules
CREATE DATABASE IF NOT EXISTS gestion_vehicules CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE gestion_vehicules;

CREATE TABLE IF NOT EXISTS utilisateurs (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    nom          VARCHAR(100) NOT NULL,
    email        VARCHAR(150) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Utilisateur de test : email admin@test.com / mot de passe : password123
-- (le hash ci-dessous correspond a "password123" avec password_hash())
INSERT INTO utilisateurs (nom, email, mot_de_passe) VALUES
('Admin', 'admin@test.com', '$2y$10$H2hg/y/2mNwvNYR0szgY5eQWL1SpLNvJ66PkPpB4g8L415h7HMTRS');

CREATE TABLE IF NOT EXISTS vehicules (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    marque         VARCHAR(100) NOT NULL,
    modele         VARCHAR(100) NOT NULL,
    immatriculation VARCHAR(20) NOT NULL UNIQUE,
    annee          INT NOT NULL,
    couleur        VARCHAR(50),
    statut         ENUM('disponible', 'en_service', 'en_maintenance') NOT NULL DEFAULT 'disponible',
    created_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Quelques données de test
INSERT INTO vehicules (marque, modele, immatriculation, annee, couleur, statut) VALUES
('Toyota', 'Hilux', 'DK-1234-AB', 2022, 'Blanc', 'disponible'),
('Renault', 'Duster', 'DK-5678-CD', 2021, 'Gris', 'en_service'),
('Hyundai', 'Tucson', 'DK-9012-EF', 2023, 'Noir', 'en_maintenance');