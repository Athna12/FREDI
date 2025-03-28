<?php
use PHPUnit\Framework\TestCase;

class EnregDetailsTest extends TestCase
{
    protected $bdd; // Déclaration de la propriété

    protected function setUp(): void
    {
        // 1. Initialise les variables globales
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
        
        // 2. Crée le mock PDO et le stocke dans la propriété
        $this->bdd = $this->createMock(PDO::class);
        $this->bdd->method('exec')->willReturn(1);
    }

    public function testInsertUtilisateur()
    {
        // 3. Remplace la connexion globale
        global $bdd;
        $bdd = $this->bdd;
        
        // 4. Capture la sortie
        ob_start();
        require __DIR__.'/../PHP/EnregDetails.php';
        $output = ob_get_clean();
        
        // 5. Vérifications
        $this->assertEmpty($output, "Aucune erreur ne devrait être affichée");
    }
}