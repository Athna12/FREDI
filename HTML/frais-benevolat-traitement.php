<?php
include("connexionBDD.php");
// Traitement.php - Traitement du formulaire de note de frais
    // Traitement des lignes de frais
    $date = $_POST['datee'];
    $lemotif = $_POST['motif'];
    $lekm = $_POST['km'];
    $letrajet = $_POST['trajet'];
    $coutPeage = $_POST['cout_peage'];
    $coutRepas = $_POST['cout_repas'];
    $coutHebergement = $_POST['cout_hebergement'];




    $requete = "INSERT INTO lignes_frais 
    (datee, motif, km, trajet, cout_peage,
    cout_repas, cout_hebergement)
    VALUES 
    (:date, :lemotif, :lekm, :letrajet, :coutPeage,
    :coutRepas, :coutHebergement)";
    $bdd->exec($requete);
    
    // Rediriger vers la page de confirmation
    header("Location: FrediAcceuil.html");
    exit();

?>
