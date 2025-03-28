<?php
use PHPUnit\Framework\TestCase;

class EnregDetailsTest extends TestCase
{
    protected function setUp(): void
    {
        // 1. Simule les données POST
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
        
        // 2. Initialise une session vide
        $_SESSION = [];
        
        // 3. Mock de la variable $bdd
        global $bdd;
        $bdd = $this->createMock(PDO::class);
        $bdd->method('exec')->willReturn(1);
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testInsertUtilisateur()
    {
        // 4. Capture la sortie
        ob_start();
        include __DIR__.'/../PHP/EnregDetails.php';
        $output = ob_get_clean();
        
        // 5. Vérifications
        $this->assertEmpty($output, "Le script ne doit pas afficher d'erreur");
    }
}