<?php
include("connexionBDD.php");
// Traitement.php - Traitement du formulaire de note de frais
    // Traitement des lignes de frais
   $requete = "INSERT INTO lignes_frais 
   (datee, motif, km, trajet,cout_trajet, cout_peage,
       cout_repas, cout_hebergement, km_valide, hebergement, total)
    VALUES 
    (:date_deplacement, :motif, :trajet, :km, :cout_trajet,
        :peages, :repas, :hebergement, :total)";
    $bdd->exec($requete);
    
    // Rediriger vers la page de confirmation
    header("Location: frais-benevolat-formulaire.html");
    exit();

?>
