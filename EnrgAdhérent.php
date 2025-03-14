<?php
    include("connexionBDD.php");
    $numero_licence = $_POST['numero_licence'];
    $mail = $_POST['adresse_mail'];
    $motPasse = $_POST['motPasse'];

    $requete = "INSERT INTO Lien (num_licence, adresse_mail, motPasse) 
    VALUES('$numero_licence','$mail','$motPasse')";
    $bdd -> exec(statement: $requete);
?>