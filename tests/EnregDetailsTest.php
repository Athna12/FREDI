<?php
use PHPUnit\Framework\TestCase;

class EnregDetailsTest extends TestCase {
    public function testInsertUtilisateur() {
        // Désactive les sessions pour ce test
        @session_start();
        $_SESSION = [];
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
        // Capture la sortie
        ob_start();
        include __DIR__.'/../PHP/EnregDetails.php';
        $output = ob_get_clean();

        $this->assertEmpty($output);
    }
}
?>