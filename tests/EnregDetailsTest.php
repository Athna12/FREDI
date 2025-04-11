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

    /**
     * Initialise $_POST avec des données par défaut, avec possibilité de les surcharger.
     */
    private function setPostData(array $overrides = []): void
    {
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

    public function testFormatNumeroLicenceInvalide()
    {
        $this->setPostData([
            'numero_licence' => 'AB1', // Trop court (moins de 6 caractères)
        ]);
        
        ob_start();
        require __DIR__.'/../HTML/EnregDetails.php';
        $output = ob_get_clean();
        
        $this->assertStringContainsString('Erreur : Le numéro de licence', $output);
    }

    public function testFormatCodePostalInvalide() 
    {
        $this->setPostData([
            'CP' => '123' // Code postal invalide (3 chiffres)
        ]);
        
        ob_start();
        require __DIR__.'/../HTML/EnregDetails.php';
        $output = ob_get_clean();
        
        $this->assertStringContainsString('Erreur : Le code postal', $output);
    }

    public function testNumeroTelephoneInvalide()
    {
        $this->setPostData([
            'numTel' => '1234567890' // Format invalide (ne commence pas par 0)
        ]);
        
        ob_start();
        require __DIR__.'/../HTML/EnregDetails.php'; 
        $output = ob_get_clean();
        
        $this->assertStringContainsString('Erreur : Le numéro de téléphone', $output);
    }

    public function testErreurPDOPrepare()
    {
        $this->setPostData();
        
        // Configure mock to throw exception
        $this->mockPDO->method('prepare')
            ->willThrowException(new PDOException('Erreur de préparation'));
        
        ob_start();
        require __DIR__.'/../HTML/EnregDetails.php';
        $output = ob_get_clean();
        
        $this->assertStringContainsString('Erreur', $output);
    }

    public function testDoubleInsertionMemeNumeroLicence()
    {
        $this->setPostData();
        
        // Configure mock to return 1 (indicating license exists)
        $this->mockStatement->method('fetchColumn')
            ->willReturn(1);
        
        ob_start();
        require __DIR__.'/../HTML/EnregDetails.php';
        $output = ob_get_clean();
        
        $this->assertStringContainsString('existe déjà', $output);
    }

    public function testValidationDesDonnees()
    {
        $this->setPostData(['numero_licence' => '']);
        
        ob_start();
        require __DIR__.'/../HTML/EnregDetails.php';
        $output = ob_get_clean();
        
        $this->assertStringContainsString('Erreur : Tous les champs obligatoires', $output);
    }

    public function testRedirectionApresInsertion()
    {
        $this->setPostData();
        
        ob_start();
        require __DIR__.'/../HTML/EnregDetails.php';
        $output = ob_get_clean();
        
        $this->assertStringContainsString('REDIRECT:', $output);
    }

    public function testReglesMetier()
    {
        $this->setPostData();
        
        // Simuler une insertion échouée
        $this->mockPDO->method('exec')->willReturn(false);
        
        ob_start();
        require __DIR__.'/../HTML/EnregDetails.php';
        $output = ob_get_clean();
        
        $this->assertStringContainsString('Erreur', $output);
    }
}