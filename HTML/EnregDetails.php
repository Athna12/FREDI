<?php
include __DIR__ . "/connexionBDD.php";

// Débogage : Affiche le contenu de $_POST pour vérifier les données reçues
if (defined('PHPUNIT_TEST')) {
    file_put_contents('php://stderr', print_r($_POST, true));
}

// Modification de la fonction sendResponse pour gérer correctement les tests
if (!function_exists('sendResponse')) {
    function sendResponse($location = null, $message = null) {
        if (defined('PHPUNIT_TEST')) {
            if ($message) {
                echo $message;
                return false;
            }
            if ($location) {
                // Ne renvoie pas de redirection en cas d'erreur pendant les tests
                if (isset($GLOBALS['hasError']) && $GLOBALS['hasError']) {
                    echo $GLOBALS['errorMessage'];
                    return false;
                }
                echo "REDIRECT:" . $location;
                return true;
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
        $GLOBALS['hasError'] = true;
        $GLOBALS['errorMessage'] = "Erreur : Tous les champs obligatoires doivent être remplis.";
        if (defined('PHPUNIT_TEST')) {
            throw new Exception($GLOBALS['errorMessage']);
        }
        sendResponse(null, $GLOBALS['errorMessage']);
        return;
    }
}

// Validation du format du numéro de licence
if (!preg_match('/^[A-Za-z0-9]{6,12}$/', $_POST['numero_licence'])) {
    $GLOBALS['hasError'] = true;
    $GLOBALS['errorMessage'] = "Erreur : Le numéro de licence doit contenir entre 6 et 12 caractères";
    sendResponse(null, $GLOBALS['errorMessage']);
    return;
}

// Validation du code postal
if (!preg_match('/^\d{5}$/', $_POST['CP'])) {
    $GLOBALS['hasError'] = true;
    $GLOBALS['errorMessage'] = "Erreur : Le code postal doit contenir exactement 5 chiffres";
    sendResponse(null, $GLOBALS['errorMessage']);
    return;
}

// Validation du numéro de téléphone
if (!preg_match('/^0[1-9][0-9]{8}$/', $_POST['numTel'])) {
    $GLOBALS['hasError'] = true;
    $GLOBALS['errorMessage'] = "Erreur : Le numéro de téléphone doit être au format français valide";
    sendResponse(null, $GLOBALS['errorMessage']);
    return;
}

try {
    $requete = $bdd->prepare("SELECT COUNT(*) FROM utilisateur WHERE numero_licence = :numero_licence");
    $requete->execute(['numero_licence' => $numero_licence]);
    // Vérification de l'existence du numéro de licence
    if ($requete->fetchColumn() > 0) {
        $GLOBALS['hasError'] = true;
        $GLOBALS['errorMessage'] = "Erreur : Un utilisateur avec ce numéro de licence existe déjà";
        sendResponse(null, $GLOBALS['errorMessage']);
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
    $GLOBALS['hasError'] = true;
    $GLOBALS['errorMessage'] = "Erreur : " . $e->getMessage();
    sendResponse(null, $GLOBALS['errorMessage']);
}
?>