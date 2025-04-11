<?php
include("connexionBDD.php");

// Vérifie que les variables POST nécessaires sont définies
if (!isset($_POST['adresse_mail']) || !isset($_POST['motPasse'])) {
    header('Location: FrediConnexion.html');
    exit();
}

// Récupération des données du formulaire
$mail = $_POST['adresse_mail'];
$motPasse = $_POST['motPasse'];

// Vérification des informations de connexion
$requete = $bdd->prepare("SELECT motPasse FROM Lien WHERE adresse_mail = :mail");
$utilisateur = $requete->fetch();

if ($utilisateur && password_verify($motPasse, $utilisateur['motPasse'])) {
    // Connexion réussie
    header('Location: FrediAcceuil.html');
    exit();
} else {
    // En cas d'échec, redirection vers la page de connexion
    header('Location: FrediConnexion.html');
    exit();
}
?>