<?php
use PHPUnit\Framework\TestCase;

class EnregDetailsTest extends TestCase
{
    protected function setUp(): void
    {
        // Vérifie si la constante PHPUNIT_TEST est déjà définie
        if (!defined('PHPUNIT_TEST')) {
            define('PHPUNIT_TEST', true);
        }
        
        // Données de test par défaut pour les tests
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
        
        // Réinitialise la session pour chaque test
        $_SESSION = [];
    }

    public function testInsertUtilisateur()
    {
        // Teste si l'insertion d'un utilisateur ne produit pas de sortie
        ob_start();
        require __DIR__.'/../PHP/EnregDetails.php';
        $output = ob_get_clean();
        
        // Vérifie que la sortie est vide
        $this->assertEmpty($output);
    }

    public function testValidationDesDonnees()
    {
        // Simule un cas où le champ 'numero_licence' est vide
        $_POST = [
            'numero_licence' => '', // Champ vide
            'ligueSportive' => 'Football',
            'nom' => 'Dupont',
            'prenom' => 'Jean',
            'sexe' => 'M',
            'numTel' => '0606060606',
            'adresse' => '10 rue des tests',
            'ville' => 'Paris',
            'CP' => '75000'
        ];

        // Capture la sortie du script
        ob_start();
        require __DIR__.'/../PHP/EnregDetails.php';
        $output = ob_get_clean();

        // Vérifie qu'un message d'erreur est affiché
        $this->assertStringContainsString('Erreur', $output, "Une erreur aurait dû être affichée pour un champ vide.");
    }

    public function testRedirectionApresInsertion()
    {
        // Simule une insertion réussie avec des données valides
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

        // Exécute le script et vérifie les en-têtes HTTP
        ob_start();
        require __DIR__.'/../PHP/EnregDetails.php';
        ob_end_clean();

        // Vérifie qu'une redirection HTTP a été envoyée
        $this->assertStringContainsString('Location:', implode('', headers_list()), "La redirection après insertion a échoué.");
    }

    public function testReglesMetier()
    {
        // Simule un cas où un utilisateur avec le même numéro de licence existe déjà
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

        // Mock de la base de données pour simuler une insertion échouée
        $mockBdd = $this->createMock(PDO::class);
        $mockBdd->method('exec')->willReturn(false);

        // Injecte le mock dans le script
        $GLOBALS['bdd'] = $mockBdd;

        // Capture la sortie du script
        ob_start();
        require __DIR__.'/../PHP/EnregDetails.php';
        $output = ob_get_clean();

        // Vérifie qu'un message d'erreur est affiché
        $this->assertStringContainsString('Erreur', $output, "Une erreur aurait dû être affichée pour un numéro de licence déjà utilisé.");
    }
}