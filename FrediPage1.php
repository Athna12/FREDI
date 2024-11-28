<?php
include("connexion.php");
$mail = $_POST['mail'];
$motPasse = $_POST['motPasse'];

$requete = "INSERT INTO utilisateur (mail, motPasse) 
VALUES('$mail','$motPasse')";
$bdd -> exec( statement: $requete);
?>