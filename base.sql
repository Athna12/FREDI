CREATE TABLE IF NOT EXISTS Lignes_frais(
   adresse_mail VARCHAR(50),
   datee DATE,
   motif VARCHAR(50),
   km INT,
   trajet VARCHAR(50),
   cout_peage DECIMAL(15,2),
   cout_repas DECIMAL(15,2),
   cout_hebergement DECIMAL(15,2),
   km_valide INT,
   peage_valide DECIMAL(15,2),
   repas_valide DECIMAL(15,2),
   hebergement_valide DECIMAL(15,2),
   PRIMARY KEY(adresse_mail, datee)
);

CREATE TABLE IF NOT EXISTS Motifs(
   libelle VARCHAR(50),
   adresse_mail VARCHAR(50),
   datee DATE,
   PRIMARY KEY(libelle),
   FOREIGN KEY(adresse_mail, datee) REFERENCES Lignes_frais(adresse_mail, datee)
);

CREATE TABLE IF NOT EXISTS lien(
   num_licence VARCHAR(50),
   adresse_mail VARCHAR(50),
   mot_passe VARCHAR(50),
   PRIMARY KEY(num_licence, adresse_mail)
);

CREATE TABLE IF NOT EXISTS Ligues(
   numero INT,
   Nom VARCHAR(50),
   sigle VARCHAR(50),
   president VARCHAR(50),
   PRIMARY KEY(numero)
);

CREATE TABLE IF NOT EXISTS Demandeurs(
   adresse_mail VARCHAR(50),
   nom VARCHAR(50),
   prenom VARCHAR(50),
   rue VARCHAR(50),
   cp INT,
   ville VARCHAR(50),
   num_recu INT,
   adresse_mail_1 VARCHAR(50),
   datee DATE,
   num_licence VARCHAR(50),
   adresse_mail_2 VARCHAR(50),
   PRIMARY KEY(adresse_mail),
   FOREIGN KEY(adresse_mail_1, datee) REFERENCES Lignes_frais(adresse_mail, datee),
   FOREIGN KEY(num_licence, adresse_mail_2) REFERENCES lien(num_licence, adresse_mail)
);

CREATE TABLE IF NOT EXISTS Adherents(
   numero_licence VARCHAR(50),
   Nom VARCHAR(50),
   Prenom VARCHAR(50),
   num_ligue VARCHAR(50),
   num_licence VARCHAR(50),
   adresse_mail VARCHAR(50),
   PRIMARY KEY(numero_licence),
   FOREIGN KEY(num_licence, adresse_mail) REFERENCES lien(num_licence, adresse_mail)
);