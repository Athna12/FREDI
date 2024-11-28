<?php
include("connexion.php");
$numLicence = $_POST['numLicence'];
$ligueSportive = $_POST['ligueSportive'];
$nom = $_POST['nom'];
$prenom = $_POST['prenom'];
$sexe = $_POST['sexe'];
$numTel = $_POST['numTel'];
$adresse = $_POST['adresse'];
$ville = $_POST['ville'];
$CP = $_POST['CP'];

$requete = "INSERT INTO utilisateur (numLicence, ligueSportive, nom, prenom, sexe, numTel, adresse, codePostal, ville) 
VALUES('$numLicence','$ligueSportive', '$nom', '$prenom', '$sexe', '$numTel', '$adresse', '$CP', '$ville')";
$bdd -> exec( statement: $requete);
?>