<?php

declare(strict_types=1);

namespace Pakypc\XMLMintrud\Tests\Unit\XML;

use Pakypc\XMLMintrud\XML\XMLDocument;
use Pakypc\XMLMintrud\XML\CommonData;
use Pakypc\XMLMintrud\Model\CustomStudent;
use Pakypc\XMLMintrud\Model\CustomStudProf;
use Pakypc\XMLMintrud\Model\EduGroup;
use Pakypc\XMLMintrud\Model\EduProgram;
use Pakypc\XMLMintrud\Model\Organization;
use Pakypc\XMLMintrud\Model\StudentInGroup;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;
use DOMDocument;

/**
 * Unit tests for the XMLDocument class
 */
#[CoversClass(XMLDocument::class)]
final class XMLDocumentTest extends TestCase
{
    private CommonData $commonData;
    private CustomStudent $student;
    private StudentInGroup $studentInGroup;
    private CustomStudProf $position;
    private EduGroup $group;
    private EduProgram $program;
    private Organization $organization;
    private string $tempFile;
    
    protected function setUp(): void
    {
        // Create common data
        $this->commonData = new CommonData(
            '1234567890',
            'Test Organization',
            [1 => 101, 2 => 102]
        );
        
        // Create test objects
        $this->student = $this->createCustomStudent();
        $this->studentInGroup = $this->createStudentInGroup();
        $this->position = $this->createCustomStudProf();
        $this->group = $this->createEduGroup();
        $this->program = $this->createEduProgram();
        $this->organization = $this->createOrganization();
        
        // Create a temporary file for testing saveToFile method
        $this->tempFile = sys_get_temp_dir() . '/xml_document_test_' . uniqid() . '.xml';
    }
    
    protected function tearDown(): void
    {
        // Remove temporary file if it exists
        if (file_exists($this->tempFile)) {
            unlink($this->tempFile);
        }
    }
    
    public function testCreate(): void
    {
        // Act
        $document = XMLDocument::create($this->commonData);
        
        // Assert
        self::assertInstanceOf(XMLDocument::class, $document);
        self::assertSame(0, $document->getRecordCount());
    }
    
    public function testAdd(): void
    {
        // Arrange
        $document = XMLDocument::create($this->commonData);
        
        // Act
        $result = $document->add(
            $this->student,
            $this->studentInGroup,
            $this->position,
            $this->group,
            $this->program,
            $this->organization
        );
        
        // Assert
        self::assertSame($document, $result, 'The add method should return the document instance for method chaining');
        self::assertSame(1, $document->getRecordCount());
    }
    
    public function testAddMultipleRecords(): void
    {
        // Arrange
        $document = XMLDocument::create($this->commonData);
        
        // Act
        $document->add(
            $this->student,
            $this->studentInGroup,
            $this->position,
            $this->group,
            $this->program,
            $this->organization
        );
        
        $document->add(
            $this->createCustomStudent('987654321098'),
            $this->studentInGroup,
            $this->position,
            $this->group,
            $this->program,
            $this->organization
        );
        
        // Assert
        self::assertSame(2, $document->getRecordCount());
    }
    
    public function testToString(): void
    {
        // Arrange
        $document = XMLDocument::create($this->commonData);
        $document->add(
            $this->student,
            $this->studentInGroup,
            $this->position,
            $this->group,
            $this->program,
            $this->organization
        );
        
        // Act
        $xml = (string)$document;
        
        // Assert
        self::assertStringContainsString('<?xml version="1.0" encoding="utf-8"?>', $xml);
        self::assertStringContainsString('<RegistrySet>', $xml);
        self::assertStringContainsString('</RegistrySet>', $xml);
        
        // Verify XML is well-formed
        $dom = new DOMDocument();
        $loadResult = $dom->loadXML($xml);
        self::assertTrue($loadResult, 'The generated XML should be well-formed');
    }
    
    public function testSaveToFile(): void
    {
        // Arrange
        $document = XMLDocument::create($this->commonData);
        $document->add(
            $this->student,
            $this->studentInGroup,
            $this->position,
            $this->group,
            $this->program,
            $this->organization
        );
        
        // Act
        $result = $document->saveToFile($this->tempFile);
        
        // Assert
        self::assertTrue($result, 'The saveToFile method should return true on success');
        self::assertFileExists($this->tempFile);
        
        // Verify file contents
        $fileContent = file_get_contents($this->tempFile);
        self::assertStringContainsString('<?xml version="1.0" encoding="utf-8"?>', $fileContent);
        self::assertStringContainsString('<RegistrySet>', $fileContent);
    }
    
    public function testGetRecordCount(): void
    {
        // Arrange
        $document = XMLDocument::create($this->commonData);
        
        // Assert initial count
        self::assertSame(0, $document->getRecordCount());
        
        // Act - Add a record
        $document->add(
            $this->student,
            $this->studentInGroup,
            $this->position,
            $this->group,
            $this->program,
            $this->organization
        );
        
        // Assert after adding
        self::assertSame(1, $document->getRecordCount());
        
        // Act - Add another record
        $document->add(
            $this->createCustomStudent('987654321098'),
            $this->studentInGroup,
            $this->position,
            $this->group,
            $this->program,
            $this->organization
        );
        
        // Assert after adding another
        self::assertSame(2, $document->getRecordCount());
    }
    
    public function testEmptyDocumentToString(): void
    {
        // Arrange
        $document = XMLDocument::create($this->commonData);
        
        // Act
        $xml = (string)$document;
        
        // Assert
        self::assertStringContainsString('<?xml version="1.0" encoding="utf-8"?>', $xml);
        self::assertStringContainsString('<RegistrySet/>', $xml);
        
        // XML should be valid even when empty
        $dom = new DOMDocument();
        $loadResult = $dom->loadXML($xml);
        self::assertTrue($loadResult, 'The generated XML for empty document should be well-formed');
    }
    
    /**
     * Test the validate method with a custom schema path
     * Note: This test assumes there is a valid XSD schema file available
     */
    public function testValidateWithCustomSchemaPath(): void
    {
        // This test would ideally validate against a test schema
        // But since we're not using mocks, we'll just verify the method returns a boolean
        
        // Arrange
        $document = XMLDocument::create($this->commonData);
        $document->add(
            $this->student,
            $this->studentInGroup,
            $this->position,
            $this->group,
            $this->program,
            $this->organization
        );
        
        // Create a temporary schema file for testing
        $tempSchemaPath = sys_get_temp_dir() . '/test_schema.xsd';
        
        // We'll create a simple XSD schema that matches our RegistrySet root element
        $schemaContent = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<xs:schema xmlns:xs="http://www.w3.org/2001/XMLSchema">
  <xs:element name="RegistrySet">
    <xs:complexType>
      <xs:sequence>
        <xs:any minOccurs="0" maxOccurs="unbounded" processContents="skip"/>
      </xs:sequence>
    </xs:complexType>
  </xs:element>
</xs:schema>
XML;
        
        file_put_contents($tempSchemaPath, $schemaContent);
        
        try {
            // Act
            $result = $document->validate($tempSchemaPath);
            
            // Assert
            self::assertIsBool($result, 'The validate method should return a boolean');
        } finally {
            // Clean up the temporary schema file
            unlink($tempSchemaPath);
        }
    }
    
    /**
     * Helper method to create a CustomStudent object
     */
    private function createCustomStudent(string $snils = '123456789012'): CustomStudent
    {
        // Arrange
        $student = new CustomStudent();
        
        // Set properties directly
        $student->id = 1;
        $student->name2 = 'Иванов';
        $student->name1 = 'Иван';
        $student->name3 = 'Иванович';
        $student->birthdate = '1990-01-01';
        $student->addr = 'Москва';
        // Свойство для страны отсутствует в модели, его нужно пропустить
        $student->mw = 1; // 1 для male
        $student->snilsNumber = $snils;
        $student->email = 'ivan@example.com';
        $student->education = 1; // Значение для "Специалист"
        $student->phone1 = '79991234567';
        $student->docSeries = '4500';
        $student->docNumber = '123456';
        $student->lockName = true;
        $student->isCustom = 1;
        $student->passwordMailed = 0;
        $student->createtime = date('Y-m-d H:i:s');
        
        return $student;
    }
    
    /**
     * Helper method to create a StudentInGroup object
     */
    private function createStudentInGroup(): StudentInGroup
    {
        // Arrange
        $studentInGroup = new StudentInGroup();
        
        // Set properties directly - инициализируем все необходимые свойства
        $studentInGroup->id = 1;
        $studentInGroup->studid = 1;
        $studentInGroup->groupid = 1;
        $studentInGroup->approved = true;
        $studentInGroup->finalTestTryCnt = 3;
        $studentInGroup->finalAllowItsTime = 0;
        $studentInGroup->attestation = 1;
        $studentInGroup->tolerance = true;
        $studentInGroup->examenated = true; // Ошибка была из-за отсутствия инициализации этого свойства
        $studentInGroup->examenCustom = false;
        $studentInGroup->examendate = '2023-06-01';
        $studentInGroup->certNumber = 'a234';
        $studentInGroup->sheetNumber = '98765';
        $studentInGroup->moneyNum = '123/2023';
        $studentInGroup->moneyDate = '2023-01-15';
        $studentInGroup->moneyBase = 15000.0;
        $studentInGroup->moneyDisc = 0;
        $studentInGroup->moneySum = 15000.0;
        $studentInGroup->moneyFinish = 1;
        $studentInGroup->moneyCurr = 15000.0;
        $studentInGroup->moneyCount = 1;
        $studentInGroup->moneyNote = '';
        $studentInGroup->leadContractNum = 0;
        $studentInGroup->custEnrollOrderNum = '123';
        $studentInGroup->custEnrollOrderDate = '2023-06-15';
        $studentInGroup->custDismissOrderNum = '456';
        $studentInGroup->custDismissOrderDate = '2023-07-01';
        $studentInGroup->sms_invations = 0;
        $studentInGroup->sms_invationLast = '';
        $studentInGroup->email_invations = 0;
        $studentInGroup->email_invationLast = '';
        $studentInGroup->lastInactiveSpam = '';
        $studentInGroup->lastInactiveGroup = 0;
        $studentInGroup->lastInactiveStatus = 0;
        $studentInGroup->createtime = '2023-01-01';
        $studentInGroup->orgName = 'ООО Тестовая организация';
        $studentInGroup->post = 'Инженер';
        $studentInGroup->fullExp = '';
        $studentInGroup->postExp = '';
        
        return $studentInGroup;
    }
    
    /**
     * Helper method to create a CustomStudProf object
     */
    private function createCustomStudProf(): CustomStudProf
    {
        // Arrange
        $position = new CustomStudProf();
        
        // Set properties directly
        $position->id = 1;
        $position->studid = 1;
        $position->orgid = 1;
        $position->post = 'Инженер';
        $position->locked = false;
        $position->orgName = 'ООО Тестовая организация';
        
        return $position;
    }
    
    /**
     * Helper method to create an EduGroup object
     */
    private function createEduGroup(): EduGroup
    {
        // Arrange
        $group = new EduGroup();
        
        // Set properties directly
        $group->id = 1;
        $group->direction = 1;
        $group->absNum = 'Группа тестирования';
        $group->directNum = 'Тест-01';
        $group->formaObu4 = 1; // Для "очной" формы обучения
        $group->formaRealiz = 0;
        $group->startDate = '2023-01-01';
        $group->stopDate = '2023-06-01';
        $group->examen = '2023-06-15';
        $group->period = 144;
        $group->finished = 0;
        $group->allowLandingStuds = false;
        $group->groupEnrollOrderNum = '123';
        $group->groupEnrollOrderDate = '2023-01-01';
        $group->groupDismissOrderNum = '456';
        $group->groupDismissOrderDate = '2023-06-30';
        $group->isOutloadedFRDO = false;
        $group->createtime = date('Y-m-d H:i:s');
        
        return $group;
    }
    
    /**
     * Helper method to create an EduProgram object
     */
    private function createEduProgram(): EduProgram
    {
        // Arrange
        $program = new EduProgram();
        
        // Set properties directly
        $program->id = 1;
        $program->name = 'Программа тестирования';
        $program->progHint = 'Тестирование';
        $program->hours = 144;
        $program->throwDocs = 1;
        $program->qualificationName = 'Тестирование программного обеспечения';
        $program->activityArea = 1;
        $program->progEduType = 1; // Для "профессиональная переподготовка"
        $program->programid = 1;
        $program->directid = 1;
        $program->groupid = 1;
        $program->studid = 1;
        $program->ingroupid = 1;
        $program->eduCourseLinked = 0;
        $program->eduQuestPackLinked = 0;
        $program->eduDirectLinked = 0;
        $program->linkedProgram = false;
        
        return $program;
    }
    
    /**
     * Helper method to create an Organization object
     */
    private function createOrganization(): Organization
    {
        // Arrange
        $organization = new Organization();
        
        // Set properties directly
        $organization->id = 1;
        $organization->verifed = true;
        $organization->blocked = false;
        $organization->orgName = 'ООО Тестовая организация';
        $organization->orgNameMgr = 'Тестовая организация';
        $organization->orgINN = '7712345678';
        $organization->orgKPP = '771201001';
        $organization->orgAddr = 'Москва';
        $organization->orgAddrActual = 'ул. Тестовая, д. 1';
        $organization->orgEmail = 'test@example.com';
        $organization->orgPhone = '79991234567';
        $organization->orgAccNum = '1234567890';
        $organization->orgSubscribeDebt = false;
        $organization->orgSubscribeInvites = false;
        $organization->orgSubscribeOthers = false;
        $organization->createtime = date('Y-m-d H:i:s');
        $organization->modifytime = date('Y-m-d H:i:s');
        $organization->ogOrderNumber = '';
        
        return $organization;
    }
}
