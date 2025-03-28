<?php
include("connexionBDD.php");
// Récupération des données du formulaire
$mail = $_POST['adresse_mail'];
$motPasse = $_POST['motPasse'];

// Vérification des informations de connexion
$requete = $bdd->query( "SELECT motPasse FROM Lien WHERE adresse_mail = mail");
$utilisateur = $requete->fetchAll();

if ($utilisateur && password_verify($motPasse, $utilisateur['motPasse'])) {
    // Connexion réussie
    header('Location: FrediAcceuil.html'); // Redirection vers la page d'accueil
    exit();
} else {
    // Échec de la connexion
    echo "Adresse mail ou mot de passe incorrect.";
}
?>