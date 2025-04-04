<?php
// Vérifie si on est en mode CLI (terminal/console)
if (php_sapi_name() === 'cli') {
    // Crée un mock de PDO pour les tests
    $bdd = new class {
        public function exec($sql) { 
            return 1; // Simule une insertion réussie
        }
        public function prepare($sql) { 
            return $this; // Retourne l'objet lui-même
        }
        public function execute($params) { 
            return true; // Simule une exécution réussie
        }
    };
    return; // Sort du fichier sans initialiser les sessions
}

// Code normal pour l'exécution en production
require_once __DIR__.'/SessionManager.php';
SessionManager::startSession();

// ... reste de votre connexion PDO normale


	$server="localhost";
	$db="fredi";
	$user="root";
	$password="";
	
	try
		{
			//	connexion au serveur de données et à la base
				$bdd = new PDO("mysql:host=$server; dbname=$db;charset=utf8", $user,$password,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
		}
	//	gestion d’erreur
	catch (Exception $e)
		{
			die('Erreur : ' . $e->getMessage());
		}
?>