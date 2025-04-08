<?php
use PHPUnit\Framework\TestCase;

class EnregDetailsTest extends TestCase 
{
    private $mockPDO;
    private $mockStatement;

    protected function setUp(): void
    {
        if (!defined('PHPUNIT_TEST')) {
            define('PHPUNIT_TEST', true);
        }
        
        $_SESSION = [];

        // Create PDOStatement mock with common behaviors
        $this->mockStatement = $this->createMock(PDOStatement::class);
        $this->mockStatement->method('execute')->willReturn(true);
        $this->mockStatement->method('fetchColumn')->willReturn(0);
        $this->mockStatement->method('errorInfo')->willReturn([0, null, null]);
        
        // Create PDO mock with necessary methods
        $this->mockPDO = $this->getMockBuilder(PDO::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['prepare', 'exec', 'errorInfo'])
            ->getMock();
            
        $this->mockPDO->method('prepare')->willReturn($this->mockStatement);
        $this->mockPDO->method('exec')->willReturn(true);
        $this->mockPDO->method('errorInfo')->willReturn([0, null, null]);

        $GLOBALS['bdd'] = $this->mockPDO;
    }

    // Existing tests...

    public function testFormatNumeroLicenceInvalide()
    {
        $this->setPostData([
            'numero_licence' => 'AB12657', // Trop court (moins de 6 caractères)
            'ligueSportive' => 'Football',
            'nom' => 'Dupont',
            'prenom' => 'Math',
            'sexe' => 'M',
            'numTel' => '0606060606',
            'adresse' => '10 rue des tests',
            'ville' => 'Paris',
            'CP' => '75000'
        ]);
        
        ob_start();
        require __DIR__.'/../HTML/EnregDetails.php';
        $output = ob_get_clean();
        
        $this->assertStringContainsString('Erreur : Le numéro de licence doit contenir entre 6 et 12 caractères', $output);
    }

    public function testFormatCodePostalInvalide()
    {
        $this->setPostData(['CP' => '12345']); // CP trop court
        
        ob_start();
        require __DIR__.'/../HTML/EnregDetails.php';
        $output = ob_get_clean();
        
        $this->assertStringContainsString('Erreur : Le code postal doit contenir exactement 5 chiffres', $output);
    }

    public function testNumeroTelephoneInvalide()
    {
        $this->setPostData(['numTel' => '0123456749']); // Numéro invalide
        
        ob_start();
        require __DIR__.'/../HTML/EnregDetails.php';
        $output = ob_get_clean();
        
        $this->assertStringContainsString('Erreur', $output);
    }

    public function testErreurPDOPrepare()
    {
        $this->setPostData();
        
        // Simulate PDO prepare error
        $this->mockPDO->method('prepare')
            ->will($this->throwException(new PDOException('Erreur de préparation')));
            
        ob_start();
        require __DIR__.'/../HTML/EnregDetails.php';
        $output = ob_get_clean();
        
        $this->assertStringContainsString('Erreur', $output);
    }

    public function testDoubleInsertionMemeNumeroLicence()
    {
        $this->setPostData();
        
        // First insertion succeeds
        ob_start();
        require __DIR__.'/../HTML/EnregDetails.php';
        ob_clean();
        
        // Second insertion with same license number fails
        $this->mockStatement->method('fetchColumn')->willReturn(1);
        require __DIR__.'/../HTML/EnregDetails.php';
        $output = ob_get_clean();
        
        $this->assertStringContainsString('existe déjà', $output);
    }

    // Removed duplicate method to resolve the syntax error.

    /**
     * Initialise $_POST avec des données par défaut, avec possibilité de les surcharger.
     * @param array $overrides Données à surcharger dans $_POST.
     */
    function setPostData(array $overrides = []): void
    {
        // Données par défaut pour les tests
        $_POST = array_merge([
            'numero_licence' => '1234567',
            'ligueSportive' => 'Football',
            'nom' => 'Dupont',
            'prenom' => 'Jean',
            'sexe' => 'M',
            'numTel' => '0123456789',
            'adresse' => '10 rue des tests',
            'ville' => 'Paris',
            'CP' => '75875'
        ], $overrides);
    }


    public function testValidationDesDonnees()
    {
        // Initialise $_POST avec un champ vide
        $this->setPostData(['numero_licence' => '']); // Champ vide

        // Capture les erreurs ou les logs de débogage
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Erreur : Tous les champs obligatoires doivent être remplis.");

        // Exécute le script
        require __DIR__.'/../HTML/EnregDetails.php';
    }

    public function testRedirectionApresInsertion()
    {
        // Initialise $_POST avec des données valides
        $this->setPostData();

        // Exécute le script et vérifie les en-têtes HTTP
        ob_start();
        require __DIR__.'/../HTML/EnregDetails.php';
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
        require __DIR__.'/../HTML/EnregDetails.php';
        $output = ob_get_clean();

        // Vérifie qu'un message d'erreur est affiché
        $this->assertStringContainsString('Erreur', $output, "Une erreur aurait dû être affichée pour un numéro de licence déjà utilisé.");
    }
}