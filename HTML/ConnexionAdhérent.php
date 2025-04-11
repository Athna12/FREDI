<?php
include("connexionBDD.php");

if (!isset($_POST['adresse_mail']) || !isset($_POST['motPasse'])) {
    header('Location: FrediConnexion.html');
    exit();
}

$mail = $_POST['adresse_mail'];
$motPasse = $_POST['motPasse'];

$requete = $bdd->prepare("SELECT motPasse FROM Lien WHERE adresse_mail = :mail");
$requete->execute(['mail' => $mail]);
$utilisateur = $requete->fetch();

if ($utilisateur) {
    // Démarrer la session et stocker les infos utilisateur
    session_start();
    $_SESSION['adresse_mail'] = $mail;
    $_SESSION['connecte'] = true;
    
    // Redirection vers la page d'accueil
    header('Location: FrediAcceuil.html');
    exit();
} else {
    // Redirection vers la page de connexion en cas d'échec
    header('Location: FrediConnexion.html');
    exit();
}
?>