<?php
// phpFrais.php - Traitement du formulaire de note de frais
include("connexionBDD.php");

// Vérifier si une session est déjà active avant d'en démarrer une nouvelle
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Activer l'affichage des erreurs pour le débogage
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Vérifier que le formulaire a été soumis
if (!isset($_POST) || empty($_POST)) {
    die("Aucune donnée de formulaire reçue. Veuillez remplir le formulaire.");
}

// Afficher toutes les données reçues pour débogage
echo "<h3>Données reçues du formulaire :</h3>";
echo "<pre>";
print_r($_POST);
echo "</pre>";*/

// Récupération des données du formulaire et traitement des éventuels tableaux
$date = is_array($_POST['date']) ? implode(", ", $_POST['date']) : $_POST['date'];
$motif = is_array($_POST['motif']) ? implode(", ", $_POST['motif']) : $_POST['motif'];
$km = is_array($_POST['km']) ? (int)$_POST['km'][0] : (int)$_POST['km'];
$trajet = is_array($_POST['trajet']) ? implode(", ", $_POST['trajet']) : $_POST['trajet'];
$adresse_mail = is_array($_POST['adresse_mail']) ? implode(", ", $_POST['adresse_mail']) : $_POST['adresse_mail'];

// Récupération de l'adresse email de l'utilisateur
/*$$adresse_mail = "";
if (isset($_SESSION['user_email'])) {
    $adresse_mail = $_SESSION['user_email'];
} elseif (isset($_POST['adresse_mail'])) {
    $adresse_mail = $_POST['adresse_mail'];
} else {
    // Si vous êtes en environnement de développement, utilisez une adresse par défaut
    $adresse_mail = "utilisateur@exemple.com";
    // En production, vous pourriez vouloir rediriger l'utilisateur vers une page de connexion
    // header("Location: login.php");
    // exit;
}

// Si aucune adresse email n'est disponible, utilisez une valeur par défaut ou affichez une erreur
if (empty($adresse_mail)) {
    echo "<p style='color: red;'>Erreur : Adresse email requise. Veuillez vous connecter ou fournir une adresse email.</p>";
    echo "<p><a href='javascript:history.back()'>Retour au formulaire</a></p>";
    exit;
}*/

// Traitement des champs optionnels qui peuvent être des tableaux
$peages = 0;
if (isset($_POST['peages'])) {
    $peages = is_array($_POST['peages']) ? array_sum(array_map('floatval', $_POST['peages'])) : (float)$_POST['peages'];
}

$repas = 0;
if (isset($_POST['repas'])) {
    $repas = is_array($_POST['repas']) ? array_sum(array_map('floatval', $_POST['repas'])) : (float)$_POST['repas'];
}

$hebergement = 0;
if (isset($_POST['hebergement'])) {
    $hebergement = is_array($_POST['hebergement']) ? array_sum(array_map('floatval', $_POST['hebergement'])) : (float)$_POST['hebergement'];
}


// Préparation et exécution de la requête avec des paramètres liés (protection contre les injections SQL)
try {
   /* echo "<h3>Valeurs après traitement :</h3>";
    echo "<ul>";
    echo "<li>Date: " . $date . "</li>";
    echo "<li>Motif: " . $motif . "</li>";
    echo "<li>KM: " . $km . "</li>";
    echo "<li>Trajet: " . $trajet . "</li>";
    echo "<li>Péage: " . $peages . "</li>";
    echo "<li>Repas: " . $repas . "</li>";
    echo "<li>Hébergement: " . $hebergement . "</li>";
    echo "<li>Adresse email: " . $adresse_mail . "</li>";
    echo "</ul>";*/
    
    // Construction de la requête SQL avec le champ adresse_mail
    $sql = "INSERT INTO lignes_frais 
        (datee, motif, km, trajet, cout_peage, cout_repas, cout_hebergement, adresse_mail) 
        VALUES 
        (:date, :motif, :km, :trajet, :peages, :repas, :hebergement, :adresse_mail)";
    
    //echo "<p>Requête SQL préparée : " . $sql . "</p>";
    
    $requete = $bdd->prepare($sql);
    
    $requete->bindParam(':date', $date);
    $requete->bindParam(':motif', $motif);
    $requete->bindParam(':km', $km, PDO::PARAM_INT);
    $requete->bindParam(':trajet', $trajet);
    $requete->bindParam(':peages', $peages, PDO::PARAM_STR);
    $requete->bindParam(':repas', $repas, PDO::PARAM_STR);
    $requete->bindParam(':hebergement', $hebergement, PDO::PARAM_STR);
    $requete->bindParam(':adresse_mail', $adresse_mail, PDO::PARAM_STR);
    
    // Exécution de la requête
    $result = $requete->execute();
    
    if ($result) {
        // Redirection vers la page d'accueil
        header('Location: FrediAcceuil.html');
        exit();
    } else {
        // En cas d'erreur, redirection avec message
        $_SESSION['erreur'] = "Erreur lors de l'enregistrement de la note de frais";
        header('Location: FrediAcceuil.html');
        exit();
    }
} catch (PDOException $e) {
    // En cas d'exception, redirection avec message d'erreur
    $_SESSION['erreur'] = "Erreur lors de l'enregistrement de la note de frais: " . $e->getMessage();
    header('Location: FrediAcceuil.html');
    exit();
}
?>