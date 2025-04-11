<?php
    include("connexionBDD.php");
    $numero_licence = $_POST['numero_licence'];
    $mail = $_POST['adresse_mail'];
    $motPasse = $_POST['motPasse'];

    try {
        $requete = "INSERT INTO Lien (num_licence, adresse_mail, motPasse) 
        VALUES('$numero_licence','$mail','$motPasse')";
        $bdd->exec($requete);
    } catch (PDOException $e) {
        // On ignore l'erreur de duplicata et on continue
        // Les autres erreurs seront aussi ignorées
    }

    // Redirection dans tous les cas
    header("Location: confirmation création de compte.html");
    exit();
?>