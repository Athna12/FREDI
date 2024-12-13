<?php
include("connexionBDD.php");
$numero_licence = $_POST['numero_licence'];
$mail = $_POST['mail'];
$motPasse = $_POST['motPasse'];

$requete = "INSERT INTO Lien (num_licence, mail, mot_passe) 
VALUES('$numero_licence','$mail','$motPasse')";
$bdd -> exec( statement: $requete);
?>