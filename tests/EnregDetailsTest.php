<?php
use PHPUnit\Framework\TestCase;

class EnregDetailsTest extends TestCase
{
    protected function setUp(): void
    {
        // Définit la constante pour le mode test
        define('PHPUNIT_TEST', true);
        
        // Données de test
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
        
        $_SESSION = [];
    }

    public function testInsertUtilisateur()
    {
        ob_start();
        require __DIR__.'/../PHP/EnregDetails.php';
        $output = ob_get_clean();
        
        $this->assertEmpty($output);
    }
}