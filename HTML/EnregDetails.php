<?php
include __DIR__ . "/connexionBDD.php";

// Débogage : Affiche le contenu de $_POST pour vérifier les données reçues
if (defined('PHPUNIT_TEST')) {
    file_put_contents('php://stderr', print_r($_POST, true));
}

// Fonction pour gérer la réponse (redirection ou message selon l'environnement)
if (!function_exists('sendResponse')) {
    function sendResponse($location = null, $message = null) {
        if (defined('PHPUNIT_TEST')) {
            if ($message) {
                echo $message;
            } else {
                echo "SUCCES"; // Indique une opération réussie en environnement de test
            }
        } else {
            if ($location) {
                header("Location: " . $location);
                exit();
            }
            if ($message) {
                echo $message;
                exit();
            }
        }
    }
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
        sendResponse(null, "Erreur : Tous les champs obligatoires doivent être remplis.");
        return;
    }
}

// Vérifie si un utilisateur avec le même numéro de licence existe déjà
$numero_licence = $_POST['numero_licence'];

// Validation du format du numéro de licence (format plus flexible)
if (!preg_match('/^[A-Za-z0-9]{6,12}$/', $numero_licence)) {
    sendResponse(null, "Erreur : Le numéro de licence doit contenir entre 6 et 12 caractères (lettres ou chiffres).");
    return;
}

// Validation du code postal (5 chiffres)
if (!preg_match('/^\d{5}$/', $_POST['CP'])) {
    sendResponse(null, "Erreur : Le code postal doit contenir exactement 5 chiffres.");
    return;
}

// Validation du numéro de téléphone (format français)
if (!preg_match('/^0[1-9][0-9]{8}$/', $_POST['numTel'])) {
    sendResponse(null, "Erreur : Le numéro de téléphone doit être au format français valide.");
    return;
}

try {
    $requete = $bdd->prepare("SELECT COUNT(*) FROM utilisateur WHERE numero_licence = :numero_licence");
    $requete->execute(['numero_licence' => $numero_licence]);
    if ($requete->fetchColumn() > 0) {
        sendResponse(null, "Erreur : Un utilisateur avec ce numéro de licence existe déjà.");
        return;
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
        sendResponse("confirmation_creation_de_compte.html");
    } else {
        sendResponse(null, "Erreur : Une erreur est survenue lors de l'insertion.");
    }
} catch (PDOException $e) {
    sendResponse(null, "Erreur : " . $e->getMessage());
}
?>