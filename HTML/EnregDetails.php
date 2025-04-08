<?php
include __DIR__ . "/connexionBDD.php";

// Debugging: Affiche le contenu de $_POST pour vérifier les données reçues
if (defined('PHPUNIT_TEST')) {
    file_put_contents('php://stderr', print_r($_POST, true));
}

// Validation des données d'entrée
// Vérifie que tous les champs obligatoires sont remplis
$requiredFields = ['numero_licence', 'ligueSportive', 'nom', 'prenom', 'sexe', 'numTel', 'adresse', 'ville', 'CP'];
foreach ($requiredFields as $field) {
    if (!isset($_POST[$field]) || trim($_POST[$field]) === '') {
        if (defined('PHPUNIT_TEST')) {
            file_put_contents('php://stderr', "Validation échouée pour le champ : $field\n");
            throw new Exception("Erreur : Tous les champs obligatoires doivent être remplis.");
        }
        echo "Erreur : Tous les champs obligatoires doivent être remplis.";
        exit(); // Arrête l'exécution si un champ est vide
    }
}

// Vérifie si un utilisateur avec le même numéro de licence existe déjà
$numero_licence = $_POST['numero_licence'];

// Validation du format du numéro de licence (12 caractères alphanumériques)
if (!preg_match('/^[A-Za-z0-9]{12}$/', $numero_licence)) {
    echo "Erreur : Le numéro de licence doit contenir exactement 12 caractères (lettres ou chiffres).";
    exit();
}

// Validation du code postal (5 chiffres)
if (!preg_match('/^\d{5}$/', $_POST['CP'])) {
    echo "Erreur : Le code postal doit contenir exactement 5 chiffres.";
    exit();
}

// Validation du numéro de téléphone (format français)
if (!preg_match('/^0[1-9][0-9]{8}$/', $_POST['numTel'])) {
    echo "Erreur : Le numéro de téléphone doit être au format français valide.";
    exit();
}

try {
    $requete = $bdd->prepare("SELECT COUNT(*) FROM utilisateur WHERE numero_licence = :numero_licence");
    $requete->execute(['numero_licence' => $numero_licence]);
    if ($requete->fetchColumn() > 0) {
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
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
    exit();
}
?>