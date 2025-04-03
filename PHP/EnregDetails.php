<?php
include __DIR__ . "/connexionBDD.php";

// Debugging: Affiche le contenu de $_POST pour vérifier les données reçues
if (defined('PHPUNIT_TEST')) {
    file_put_contents('php://stderr', print_r($_POST, true));
}

// Validation des données d'entrée
// Vérifie que tous les champs obligatoires sont remplis
if (empty($_POST['numero_licence']) || empty($_POST['ligueSportive']) || empty($_POST['nom']) || 
    empty($_POST['prenom']) || empty($_POST['sexe']) || empty($_POST['numTel']) || 
    empty($_POST['adresse']) || empty($_POST['ville']) || empty($_POST['CP'])) {
    echo "Erreur : Tous les champs obligatoires doivent être remplis.";
    exit(); // Arrête l'exécution si un champ est vide
}

// Vérifie si un utilisateur avec le même numéro de licence existe déjà
$numero_licence = $_POST['numero_licence'];
$requete = $bdd->prepare("SELECT COUNT(*) FROM utilisateur WHERE numero_licence = :numero_licence");
$requete->execute(['numero_licence' => $numero_licence]);
if ($requete->fetchColumn() > 0) {
    // Affiche une erreur si un doublon est détecté
    echo "Erreur : Un utilisateur avec ce numéro de licence existe déjà.";
    exit();
}

// Insertion des données
// Prépare les données pour l'insertion dans la base
$ligueSportive = $_POST['ligueSportive'];
$nom = $_POST['nom'];
$prenom = $_POST['prenom'];
$sexe = $_POST['sexe'];
$numTel = $_POST['numTel'];
$adresse = $_POST['adresse'];
$ville = $_POST['ville'];
$CP = $_POST['CP'];

// Exécute la requête d'insertion
$requete = $bdd->prepare("INSERT INTO utilisateur (numero_licence, ligueSportive, nom, prenom, sexe, numTel, adresse, codePostal, ville) 
VALUES (:numero_licence, :ligueSportive, :nom, :prenom, :sexe, :numTel, :adresse, :CP, :ville)");
if ($requete->execute([
    'numero_licence' => $numero_licence,
    'ligueSportive' => $ligueSportive,
    'nom' => $nom,
    'prenom' => $prenom,
    'sexe' => $sexe,
    'numTel' => $numTel,
    'adresse' => $adresse,
    'CP' => $CP,
    'ville' => $ville
])) {
    // Redirection après insertion réussie
    header("Location: confirmation_creation_de_compte.html");
    exit(); // Termine le script après la redirection
} else {
    // Affiche une erreur si l'insertion échoue
    echo "Erreur : Une erreur est survenue lors de l'insertion.";
    exit();
}
?>