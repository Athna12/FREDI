CREATE TABLE `Adhérent`(
    `numero_licence` INT NOT NULL COMMENT 'NOT NULL',
    `nom` VARCHAR(255) NOT NULL COMMENT 'NOT NULL',
    `prenom` VARCHAR(255) NOT NULL COMMENT 'NOT NULL',
    `numero_ligues` INT NOT NULL,
    PRIMARY KEY(`numero_licence`)
);
CREATE TABLE `Demandeurs`(
    `adresse_mail` CHAR(255) NOT NULL,
    `nom` CHAR(255) NOT NULL,
    `prenom` CHAR(255) NOT NULL,
    `rue` CHAR(255) NOT NULL,
    `cp` CHAR(255) NOT NULL,
    `ville` CHAR(255) NOT NULL,
    `num_recu` INT NOT NULL,
    PRIMARY KEY(`adresse_mail`)
);
CREATE TABLE `Lien`(
    `num_licence` BIGINT UNSIGNED NOT NULL,
    `adresse_mail` CHAR(255) NOT NULL,
    `mot_passe` VARCHAR NOT NULL
);
CREATE TABLE `Lignes-frais`(
    `id_adresse_mail` CHAR(255) NOT NULL,
    `date` CHAR(255) NOT NULL,
    `motif` CHAR(255) NOT NULL,
    `trajet` CHAR(255) NOT NULL,
    `km` FLOAT(53) NOT NULL,
    `cout_peage` FLOAT(53) NOT NULL,
    `cout_repas` FLOAT(53) NOT NULL,
    `cout_hebergement` FLOAT(53) NOT NULL,
    `km_valide` FLOAT(53) NOT NULL,
    `peage_valide` FLOAT(53) NOT NULL,
    `repas_valide` FLOAT(53) NOT NULL,
    `hebergement_valide` FLOAT(53) NOT NULL,
    PRIMARY KEY(`id_adresse_mail`)
);
CREATE TABLE `Ligues`(
    `numero` INT NOT NULL,
    `nom` CHAR(255) NOT NULL,
    `sigle` CHAR(255) NOT NULL,
    `president` CHAR(255) NOT NULL,
    PRIMARY KEY(`numero`)
);
CREATE TABLE `Motifs`(
    `libelle` CHAR(255) NOT NULL,
    PRIMARY KEY(`libelle`)
);
ALTER TABLE
    `Lignes-frais` ADD CONSTRAINT `lignes_frais_id_adresse_mail_foreign` FOREIGN KEY(`id_adresse_mail`) REFERENCES `Demandeurs`(`adresse_mail`);
ALTER TABLE
    `Lien` ADD CONSTRAINT `lien_adresse_mail_foreign` FOREIGN KEY(`adresse_mail`) REFERENCES `Demandeurs`(`adresse_mail`);
ALTER TABLE
    `Adhérent` ADD CONSTRAINT `adhérent_numero_ligues_foreign` FOREIGN KEY(`numero_ligues`) REFERENCES `Ligues`(`numero`);
ALTER TABLE
    `Lignes-frais` ADD CONSTRAINT `lignes_frais_motif_foreign` FOREIGN KEY(`motif`) REFERENCES `Motifs`(`libelle`);
ALTER TABLE
    `Lien` ADD CONSTRAINT `lien_num_licence_foreign` FOREIGN KEY(`num_licence`) REFERENCES `Adhérent`(`numero_licence`);