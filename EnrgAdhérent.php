<?php
include("connexionBDD.php"); // Assure-toi que ce fichier établit bien la connexion à la BDD

// Récupération et validation des données du formulaire
$numero_licence = isset($_POST['numero_licence']) ? trim($_POST['numero_licence']) : null;
$mail = isset($_POST['adresse_mail']) ? trim($_POST['adresse_mail']) : null;
$motPasse = isset($_POST['motPasse']) ? trim($_POST['motPasse']) : null;

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

// Préparer la requête sécurisée
$sql = "INSERT INTO Lien (num_licence, adresse_mail, mot_passe) VALUES(:num_licence, :mail, :motPasse)";
$stmt = $bdd->prepare($sql);
$stmt->execute([
    'num_licence' => $numero_licence,
    'mail' => $mail,
    'motPasse' => $motPasse
]);

echo "Insertion réussie !";
?>