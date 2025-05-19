<?php

declare(strict_types=1);

namespace Tests\Unit\XML;

use crm\models\CustomStudent;
use crm\models\CustomStudProf;
use crm\models\EduGroup;
use crm\models\EduProgram;
use crm\models\Organization;
use crm\models\StudentInGroup;
use DOMDocument;
use Generator;
use Pakypc\XMLMintrud\XML\CommonData;
use Pakypc\XMLMintrud\XML\XMLDocument;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

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
    
    protected function setUp(): void
    {
        // Arrange (common setup)
        $this->commonData = new CommonData(
            '1234567890',
            'Тестовый Учебный Центр',
            [1 => 3]
        );
        
        // Создаем тестового студента
        $this->student = new CustomStudent();
        $this->student->id = 1;
        $this->student->name1 = 'Иван';
        $this->student->name2 = 'Иванов';
        $this->student->name3 = 'Иванович';
        $this->student->snilsNumber = '123-456-789 00';
        
        // Создаем тестовые данные студента в группе
        $this->studentInGroup = new StudentInGroup();
        $this->studentInGroup->id = 1;
        $this->studentInGroup->student_id = 1;
        $this->studentInGroup->group_id = 1;
        $this->studentInGroup->examenated = true;
        $this->studentInGroup->certNumber = 'CERT-2025-001';
        $this->studentInGroup->examendate = '2025-04-15';
        
        // Создаем тестовые данные о должности студента
        $this->position = new CustomStudProf();
        $this->position->id = 1;
        $this->position->student_id = 1;
        $this->position->post = 'Инженер по охране труда';
        
        // Создаем тестовую группу
        $this->group = new EduGroup();
        $this->group->id = 1;
        $this->group->name = 'Группа ОТ-1';
        $this->group->program = 1;
        $this->group->examen = '2025-04-15';
        
        // Создаем тестовую программу обучения
        $this->program = new EduProgram();
        $this->program->id = 1;
        $this->program->name = 'Программа по охране труда';
        
        // Создаем тестовую организацию
        $this->organization = new Organization();
        $this->organization->id = 1;
        $this->organization->orgName = 'ООО "Тестовая Организация"';
        $this->organization->orgINN = '9876543210';
    }
    
    public function testCreateReturnsInstanceOfXMLDocument(): void
    {
        // Act
        $document = XMLDocument::create($this->commonData);
        
        // Assert
        self::assertInstanceOf(XMLDocument::class, $document);
    }
    
    public function testAddReturnsInstanceOfXMLDocument(): void
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
        self::assertInstanceOf(XMLDocument::class, $result);
    }
    
    public function testAddIncreasesRecordCount(): void
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
        
        // Assert
        self::assertSame(1, $document->getRecordCount());
    }
    
    #[DataProvider('provideMultipleStudents')]
    public function testAddMultipleStudentsIncreasesRecordCountCorrectly(int $studentCount): void
    {
        // Arrange
        $document = XMLDocument::create($this->commonData);
        
        // Act
        for ($i = 0; $i < $studentCount; $i++) {
            $document->add(
                $this->student,
                $this->studentInGroup,
                $this->position,
                $this->group,
                $this->program,
                $this->organization
            );
        }
        
        // Assert
        self::assertSame($studentCount, $document->getRecordCount());
    }
    
    public static function provideMultipleStudents(): Generator
    {
        yield 'one student' => [1];
        yield 'three students' => [3];
        yield 'five students' => [5];
    }
    
    public function testToStringReturnsValidXmlString(): void
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
        $xmlString = (string) $document;
        
        // Assert
        $dom = new DOMDocument();
        $loadResult = $dom->loadXML($xmlString);
        self::assertTrue($loadResult, 'XML строка должна быть корректной');
        self::assertEquals('RegistrySet', $dom->documentElement->nodeName);
    }
    
    public function testSaveToFileWritesXmlToSpecifiedPath(): void
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
        
        $tempFilePath = sys_get_temp_dir() . '/test_xml_' . uniqid() . '.xml';
        
        // Act
        $result = $document->saveToFile($tempFilePath);
        
        // Assert
        self::assertTrue($result, 'Метод saveToFile должен вернуть true при успешном сохранении');
        self::assertFileExists($tempFilePath, 'Файл должен быть создан');
        
        $fileContents = file_get_contents($tempFilePath);
        $dom = new DOMDocument();
        $loadResult = $dom->loadXML($fileContents);
        self::assertTrue($loadResult, 'Файл должен содержать корректный XML');
        
        // Cleanup
        unlink($tempFilePath);
    }
    
    public function testValidateWithValidSchemaReturnsTrue(): void
    {
        // Этот тест требует наличия реального XSD файла
        // В реальном тесте мы бы использовали мок или создали тестовый XSD,
        // но для упрощения просто проверим наличие файла
        
        $schemaPath = __DIR__ . '/../../../resources/educated_person_import_v1.0.8.xsd';
        
        // Пропустим тест, если файл схемы не найден
        if (!file_exists($schemaPath)) {
            self::markTestSkipped('Файл схемы не найден: ' . $schemaPath);
        }
        
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
        // Обратите внимание, что в реальном тесте мы бы использовали мок или
        // создали бы тестовую схему, которая гарантировано валидна
        $result = $document->validate($schemaPath);
        
        // Assert
        // Реальное значение зависит от соответствия данных схеме
        self::assertTrue($result, 'XML должен соответствовать схеме');
    }
}
