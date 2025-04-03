<?php
    include("connexionBDD.php");
    $numero_licence = $_POST['numero_licence'];
    $mail = $_POST['adresse_mail'];
    $motPasse = $_POST['motPasse'];

    $requete = "INSERT INTO Lien (num_licence, adresse_mail, motPasse) 
    VALUES('$numero_licence','$mail','$motPasse')";
    $bdd->exec($requete);
    if ($bdd->exec($requete) !== false) {
        // Rediriger vers la page de confirmation après une insertion réussie
    } else {
        // Gérer l'erreur si l'insertion échoue
        echo "Erreur lors de l'insertion des données.";
        exit();
    }
    header("Location: confirmation création de compte.html");
    exit(); // Assurez-vous de terminer le script après la redirection
    ?>