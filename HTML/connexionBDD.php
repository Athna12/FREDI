<?php
if (php_sapi_name() === 'cli') {
    // Simulation PDO pour les tests
    $bdd = new class {
        private $shouldThrowError = false;
        private $mockData = 0;
        
        public function prepare($sql) {
            if ($this->shouldThrowError) {
                return false;
            }
            return $this;
        }
        
        public function execute($params = null) {
            if ($this->shouldThrowError) {
                return false;
            }
            return true;
        }
        
        public function fetchColumn() {
            return $this->mockData;
        }
        
        public function setMockData($data) {
            $this->mockData = $data;
        }
        
        public function setShouldThrowError($value) {
            $this->shouldThrowError = $value;
        }
    };
    return;
}

// Code pour l'exécution normale
require_once __DIR__.'/SessionManager.php';
SessionManager::startSession();

    $server="localhost";
    $db="fredi";
    $user="root";
    $password="";
    
    try
        {
            // Connexion au serveur de données et à la base
                $bdd = new PDO("mysql:host=$server; dbname=$db;charset=utf8", $user,$password,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        }
    // Gestion des erreurs
    catch (Exception $e)
        {
            die('Erreur : ' . $e->getMessage());
        }
?>