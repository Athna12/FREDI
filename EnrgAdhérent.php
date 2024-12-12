<?php
include("connexion.php");
$numero_licence = $_POST['numero_licence'];
$mail = $_POST['mail'];
$motPasse = $_POST['motPasse'];

$requete = "INSERT INTO utilisateur (numero_licence, mail, motPasse) 
VALUES('$numero_licence','$mail','$motPasse')";
$bdd -> exec( statement: $requete);
?>