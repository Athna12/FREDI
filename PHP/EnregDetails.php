<?php
include("connexion.php");
$numero_licence = $_POST['numero_licence'];
$ligueSportive = $_POST['ligueSportive'];
$nom = $_POST['nom'];
$prenom = $_POST['prenom'];
$sexe = $_POST['sexe'];
$numTel = $_POST['numTel'];
$adresse = $_POST['adresse'];
$ville = $_POST['ville'];
$CP = $_POST['CP'];

$requete = "INSERT INTO utilisateur (numero_licence, ligueSportive, nom, prenom, sexe, numTel, adresse, codePostal, ville) 
VALUES('$numero_licence','$ligueSportive', '$nom', '$prenom', '$sexe', '$numTel', '$adresse', '$CP', '$ville')";
$bdd->exec($requete);
?>