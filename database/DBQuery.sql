CREATE DATABASE DB_APP;
USE DB_APP;
show TABLES;
CREATE TABLE IF NOT EXISTS patient (
    id_patient INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    date_naissance DATE NOT NULL,
    genre VARCHAR(10) NOT NULL,
    statut_marital VARCHAR(20) NOT NULL,
    cin VARCHAR(10) NOT NULL UNIQUE,
    telephone VARCHAR(20) NOT NULL,
    adresse VARCHAR(200)
);

CREATE TABLE IF NOT EXISTS staff_medical (
    id_staff INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    specialite VARCHAR(100) NOT NULL,
    cin VARCHAR(10) NOT NULL UNIQUE,
    email VARCHAR(150) NOT NULL UNIQUE,
    telephone VARCHAR(20) NOT NULL,
    adresse VARCHAR(200),
    date_embauche DATE NOT NULL,
    salaire DECIMAL(10,2),
    role VARCHAR(50) NOT NULL
);

CREATE TABLE IF NOT EXISTS file_attente (
    id_file INT AUTO_INCREMENT PRIMARY KEY,
    date DATETIME NOT NULL,
    statut VARCHAR(50) NOT NULL,
    id_patient INT NOT NULL,
    id_staff INT NOT NULL,
    FOREIGN KEY (id_patient) REFERENCES patient(id_patient) ON DELETE CASCADE,
    FOREIGN KEY (id_staff) REFERENCES staff_medical(id_staff) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS consultation (
    id_consultation INT AUTO_INCREMENT PRIMARY KEY,
    date_consultation DATETIME NOT NULL,
    diagnostic_path VARCHAR(500),
    traitement_path VARCHAR(500),
    ordonnance_path VARCHAR(500),
    scanner_path VARCHAR(500),
    analyse_path VARCHAR(500),
    id_patient INT NOT NULL,
    id_staff INT NOT NULL,
    FOREIGN KEY (id_patient) REFERENCES patient(id_patient) ON DELETE CASCADE,
    FOREIGN KEY (id_staff) REFERENCES staff_medical(id_staff) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS users (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL,
    id_staff INT,
    FOREIGN KEY (id_staff) REFERENCES staff_medical(id_staff) ON DELETE SET NULL
);

INSERT INTO Users (username, password, role) VALUES ('admin', '12345', 'Administrateur');


