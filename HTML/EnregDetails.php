<?php
include __DIR__ . "/connexionBDD.php";

// Initialisation des variables globales de gestion d'erreur
$GLOBALS['hasError'] = false;
$GLOBALS['errorMessage'] = '';

// Débogage : Affiche le contenu de $_POST pour vérifier les données reçues
if (defined('PHPUNIT_TEST')) {
    file_put_contents('php://stderr', print_r($_POST, true));
}

// Modifier la fonction sendResponse
if (!function_exists('sendResponse')) {
    function sendResponse($location = null, $message = null) {
        if (defined('PHPUNIT_TEST')) {
            if ($message) {
                echo $message;  // Affiche d'abord le message
                if ($location) {
                    echo "\nREDIRECT:" . $location;  // Puis la redirection sur une nouvelle ligne
                }
            } else if ($location) {
                echo "REDIRECT:" . $location;
            }
            return;
        } else {
            if ($message) {
                echo $message;
            }
            if ($location) {
                header("Location: " . $location);
                exit();
            }
        }
    }
}

// Validation des données d'entrée
// Vérifie que tous les champs obligatoires sont remplis
$requiredFields = ['numero_licence', 'ligueSportive', 'nom', 'prenom', 'sexe', 'numTel', 'adresse', 'ville', 'CP'];

// Initialise les variables avant utilisation pour accéder directement à $_POST
$numero_licence = isset($_POST['numero_licence']) ? $_POST['numero_licence'] : '';
$ligueSportive = isset($_POST['ligueSportive']) ? $_POST['ligueSportive'] : '';
$nom = isset($_POST['nom']) ? $_POST['nom'] : '';
$prenom = isset($_POST['prenom']) ? $_POST['prenom'] : '';
$sexe = isset($_POST['sexe']) ? $_POST['sexe'] : '';
$numTel = isset($_POST['numTel']) ? $_POST['numTel'] : '';
$adresse = isset($_POST['adresse']) ? $_POST['adresse'] : '';
$ville = isset($_POST['ville']) ? $_POST['ville'] : '';
$CP = isset($_POST['CP']) ? $_POST['CP'] : '';

// Vérifie les champs obligatoires
foreach ($requiredFields as $field) {
    if (!isset($_POST[$field]) || trim($_POST[$field]) === '') {
        sendResponse(null, "Erreur : Tous les champs obligatoires doivent être remplis.");
        return;
    }
}

// Validation du format du numéro de licence
if (!preg_match('/^[A-Za-z0-9]{6,12}$/', $_POST['numero_licence'])) {
    sendResponse(null, "Erreur : Le numéro de licence doit contenir entre 6 et 12 caractères");
    return;
}

// Validation du code postal
if (!preg_match('/^\d{5}$/', $_POST['CP'])) {
    sendResponse(null, "Erreur : Le code postal doit contenir exactement 5 chiffres");
    return;
}

// Validation du numéro de téléphone
if (!preg_match('/^0[1-9][0-9]{8}$/', $_POST['numTel'])) {
    sendResponse(null, "Erreur : Le numéro de téléphone doit être au format français valide");
    return;
}

try {
    // Vérifie si le numéro de licence existe déjà
    $requete = $bdd->prepare("SELECT COUNT(*) FROM utilisateur WHERE numero_licence = :numero_licence");
    if (!$requete) {
        sendResponse("confirmation_creation_de_compte.html", "Erreur : Erreur de préparation de la requête");
        return;
    }
    
    $success = $requete->execute(['numero_licence' => $numero_licence]);
    if (!$success) {
        sendResponse("confirmation_creation_de_compte.html", "Erreur : Erreur d'exécution de la requête");
        return;
    }
    
    if ($requete->fetchColumn() > 0) {
        sendResponse("confirmation_creation_de_compte.html", "Ce numéro de licence existe déjà");
        return;
    }

    // Insertion des données
    $requete = $bdd->prepare("INSERT INTO utilisateur (numero_licence, ligueSportive, nom, prenom, sexe, numTel, adresse, codePostal, ville) 
        VALUES (:numero_licence, :ligueSportive, :nom, :prenom, :sexe, :numTel, :adresse, :CP, :ville)");
    
    if (!$requete) {
        sendResponse("confirmation_creation_de_compte.html", "Erreur : Erreur lors de la préparation de l'insertion");
        return;
    }

    $success = $requete->execute([
        'numero_licence' => $numero_licence,
        'ligueSportive' => $ligueSportive,
        'nom' => $nom,
        'prenom' => $prenom,
        'sexe' => $sexe,
        'numTel' => $numTel,
        'adresse' => $adresse,
        'CP' => $CP,
        'ville' => $ville
    ]);

    if (!$success) {
        sendResponse("confirmation_creation_de_compte.html", "Erreur : Erreur lors de l'insertion des données");
        return;
    }

    sendResponse("confirmation_creation_de_compte.html");

} catch (PDOException $e) {
    sendResponse("confirmation_creation_de_compte.html", "Erreur : " . $e->getMessage());
    return;
}
?>