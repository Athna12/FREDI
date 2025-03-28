<?php
use PHPUnit\Framework\TestCase;

class EnregDetailsTest extends TestCase {
    public function testInsertUtilisateur() {
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

        ob_start(); // Démarre la capture de sortie
        include __DIR__ . '/../EnregDetails.php'; // Inclut le script PHP à tester
        $output = ob_get_clean(); // Récupère et vide la sortie du script

        $this->assertEmpty($output, "Le script ne doit pas renvoyer d'erreur.");
    }
}
?>