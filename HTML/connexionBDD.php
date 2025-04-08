<?php
// Vérifie si on est en mode CLI (terminal/console)
if (php_sapi_name() === 'cli') {
    // Crée un mock de PDO plus complet pour les tests
    $bdd = new class {
        private $shouldReturnData = true;
        private $mockData = 0;

        public function exec($sql) { 
            return 1; // Simule une insertion réussie
        }
        
        public function prepare($sql) { 
            return $this; // Retourne l'objet lui-même
        }
        
        public function execute($params = null) { 
            return true; // Simule une exécution réussie
        }
        
        public function fetchColumn() {
            return $this->mockData;
        }
        
        public function setMockData($data) {
            $this->mockData = $data;
        }
        
        public function fetch() {
            return $this->shouldReturnData ? ['data' => 'mock'] : false;
        }
        
        public function setShouldReturnData($value) {
            $this->shouldReturnData = $value;
        }
    };
    return;
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