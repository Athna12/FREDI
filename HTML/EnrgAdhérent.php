<?php
    include("connexionBDD.php");
    $numero_licence = $_POST['numero_licence'];
    $mail = $_POST['adresse_mail'];
    $motPasse = $_POST['motPasse'];

    $requete = "INSERT INTO Lien (num_licence, adresse_mail, mot_passe) 
    VALUES('$numero_licence','$mail','$motPasse')";
    
    if ($bdd->exec($requete) !== false) {
        header("Location: confirmation création de compte.html");
        exit();
    } else {
        echo "Erreur lors de l'insertion des données.";
        exit();
    }
?>