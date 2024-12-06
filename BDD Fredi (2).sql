CREATE TABLE `Utilisateur`(
    `numLicence` INT NOT NULL COMMENT 'NOT NULL',
    `ligueSportive` VARCHAR(255) NOT NULL COMMENT 'NOT NULL',
    `nom` CHAR(255) NOT NULL COMMENT 'NOT NULL',
    `prenom` CHAR(255) NOT NULL,
    `sexe` CHAR(255) NOT NULL COMMENT 'NOT NULL',
    `mail` VARCHAR(255) NOT NULL COMMENT 'NOT NULL',
    `motPasse` VARCHAR(255) NOT NULL COMMENT 'NOT NULL',
    `numTel` INT NOT NULL COMMENT 'NOT NULL',
    `adresse` VARCHAR(255) NOT NULL COMMENT 'NOT NULL',
    `ville` VARCHAR(255) NOT NULL COMMENT 'NOT NULL',
    `codePostal` INT NOT NULL COMMENT 'NOT NULL',
    PRIMARY KEY(`numLicence`)
);
CREATE TABLE `Borderau`(
    `numOrdreRecu` VARCHAR(255) NOT NULL,
    `date` DATE NOT NULL,
    `motif` CHAR(255) NOT NULL,
    `trajet` CHAR(255) NOT NULL,
    `kmParcourus` INT NOT NULL,
    `coutTrajet` FLOAT(53) NOT NULL,
    `peages` FLOAT(53) NOT NULL,
    `repas` FLOAT(53) NOT NULL,
    `hebergement` FLOAT(53) NOT NULL,
    `total` FLOAT(53) NOT NULL,
    `montantTotalFraisDeplacement` FLOAT(53) NOT NULL,
    `id_numLicence` INT NOT NULL,
    PRIMARY KEY(`numOrdreRecu`)
);
CREATE TABLE `Clubs`(
    `idClub` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `nomClub` CHAR(255) NOT NULL,
    `id_numLicence` INT NOT NULL
);
ALTER TABLE
    `Clubs` ADD CONSTRAINT `clubs_id_numlicence_foreign` FOREIGN KEY(`id_numLicence`) REFERENCES `Utilisateur`(`numLicence`);
ALTER TABLE
    `Borderau` ADD CONSTRAINT `borderau_id_numlicence_foreign` FOREIGN KEY(`id_numLicence`) REFERENCES `Utilisateur`(`numLicence`);