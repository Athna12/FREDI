<?php
use PHPUnit\Framework\TestCase;

class EnregDetailsTest extends TestCase {
    protected function setUp(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = [];
        
        // Simule la connexion à la BDD (à adapter selon votre architecture)
        require_once __DIR__.'/../PHP/connexionBDD.php';
    }

    protected function tearDown(): void {
        session_unset();
        session_destroy();
    }

    public function testInsertUtilisateur() {
        // 1. Préparation des données de test
        $_POST = [
            'numero_licence' => '12345',
            'ligueSportive' => 'Football',
            'nom' => 'Dupont',
            'prenom' => 'Jean',
            'sexe' => 'M',
            'numTel' => '0606060606',
            'adresse' => '10 rue des tests',
            'ville' => 'Paris',
            'CP' => '75000'
        ];

        // 2. Capture de la sortie
        ob_start();
        require __DIR__ . '/../PHP/EnregDetails.php';
        $output = ob_get_clean();

        // 3. Vérifications
        $this->assertEmpty($output);
        
        // Vérifie que l'utilisateur a bien été enregistré
        $this->assertArrayHasKey('success', $_SESSION);
        $this->assertTrue($_SESSION['success']);
    }
}
?>