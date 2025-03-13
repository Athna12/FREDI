<?php
include("connexionBDD.php"); // Assure-toi que ce fichier établit bien la connexion à la BDD
 // Récupération et validation des données du formulaire
 $numero_licence = isset($_POST['numero_licence']) ? trim($_POST['numero_licence']) : null;
 $mail = isset($_POST['adresse_mail']) ? trim($_POST['adresse_mail']) : null;
 $motPasse = isset($_POST['motPasse']) ? trim($_POST['motPasse']) : null;
 $nom = isset($_POST['nom']) ? trim($_POST['nom']) : "Nom inconnu";
 $prenom = isset($_POST['prenom']) ? trim($_POST['prenom']) : "Prénom inconnu";
 $rue = isset($_POST['rue']) ? trim($_POST['rue']) : "Rue inconnue";
 $cp = isset($_POST['cp']) ? trim($_POST['cp']) : "00000";
 $ville = isset($_POST['ville']) ? trim($_POST['ville']) : "Ville inconnue";
 $num_recu = rand(1000, 9999); // Générer un numéro reçu aléatoire

// Vérifications avant insertion
if (!$numero_licence || strlen($numero_licence) > 12) {
    die("Erreur : Numéro de licence invalide.");
}

if (!$mail || !filter_var($mail, FILTER_VALIDATE_EMAIL)) {
    die("Erreur : Adresse email invalide.");
}

if (!$motPasse) {
    die("Erreur : Mot de passe invalide.");
}

// Vérifier si l'email existe dans Demandeurs
$checkMail = $bdd->prepare("SELECT adresse_mail FROM Demandeurs WHERE adresse_mail = :mail");
$checkMail->execute(['mail' => $mail]);

if ($checkMail->rowCount() == 0) {
    // Insérer l'adresse mail dans Demandeurs si elle n'existe pas encore
    $insertMail = $bdd->prepare("INSERT INTO Demandeurs (adresse_mail, nom, prenom, rue, cp, ville, num_recu) 
                                VALUES (:mail, :nom, :prenom, :rue, :cp, :ville, :num_recu)");
    $insertMail->execute([
        'mail' => $mail,
        'nom' => $nom,
        'prenom' => $prenom,
        'rue' => $rue,
        'cp' => $cp,
        'ville' => $ville,
        'num_recu' => $num_recu
    ]);
}

// Insérer les données dans la table Lien
$sql = "INSERT INTO Lien (num_licence, adresse_mail, mot_passe) VALUES(:num_licence, :mail, :motPasse)";
$stmt = $bdd->prepare($sql);
$stmt->execute([
    'num_licence' => $numero_licence,
    'mail' => $mail,
    'motPasse' => $motPasse
]);

echo "Insertion réussie !";

?>