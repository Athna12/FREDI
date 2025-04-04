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
        
        // Réinitialise la session pour chaque test
        $_SESSION = [];

        // Mock (simule la base de donée) de la base de données pour les tests
        $GLOBALS['bdd'] = $this->createMock(PDO::class);
        $GLOBALS['bdd']->method('prepare')->willReturn($this->createMock(PDOStatement::class));
        $GLOBALS['bdd']->method('exec')->willReturn(true);
    }

    /**
     * Initialise $_POST avec des données par défaut, avec possibilité de les surcharger.
     * @param array $overrides Données à surcharger dans $_POST.
     */
    private function setPostData(array $overrides = []): void
    {
        // Données par défaut pour les tests
        $_POST = array_merge([
            'numero_licence' => '12345',
            'ligueSportive' => 'Football',
            'nom' => 'Dupont',
            'prenom' => 'Jean',
            'sexe' => 'M',
            'numTel' => '0606060606',
            'adresse' => '10 rue des tests',
            'ville' => 'Paris',
            'CP' => '75000'
        ], $overrides);
    }

    public function testInsertUtilisateur()
    {
        // Initialise $_POST avec des données valides
        $this->setPostData();

        // Teste si l'insertion d'un utilisateur ne produit pas de sortie
        ob_start();
        require __DIR__.'/../PHP/EnregDetails.php';
        $output = ob_get_clean();
        
        // Vérifie que la sortie est vide
        $this->assertEmpty($output);
    }

    public function testValidationDesDonnees()
    {
        // Initialise $_POST avec un champ vide
        $this->setPostData(['numero_licence' => '']); // Champ vide

        // Capture la sortie du script
        ob_start();
        require __DIR__.'/../PHP/EnregDetails.php';
        $output = ob_get_clean();

        // Capture les erreurs ou les logs de débogage
        $debugOutput = file_get_contents('php://stderr');

        // Vérifie qu'un message d'erreur est affiché
        $this->assertStringContainsString('Erreur : Tous les champs obligatoires doivent être remplis.', $output, "Une erreur aurait dû être affichée pour un champ vide.");

        // Vérifie que la validation a échoué pour le champ attendu
        $this->assertStringContainsString('Validation échouée pour le champ : numero_licence', $debugOutput, "La validation n'a pas échoué comme prévu pour le champ 'numero_licence'.");
    }

    public function testRedirectionApresInsertion()
    {
        // Initialise $_POST avec des données valides
        $this->setPostData();

        // Exécute le script et vérifie les en-têtes HTTP
        ob_start();
        require __DIR__.'/../PHP/EnregDetails.php';
        ob_end_clean();

        // Vérifie qu'une redirection HTTP a été envoyée
        $this->assertStringContainsString('Location:', implode('', headers_list()), "La redirection après insertion a échoué.");
    }

    public function testReglesMetier()
    {
        // Initialise $_POST avec des données valides
        $this->setPostData();

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