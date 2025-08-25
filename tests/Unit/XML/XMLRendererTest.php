<?php

declare(strict_types=1);

namespace Pakypc\XMLMintrud\Tests\Unit\XML;

use DOMDocument;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Pakypc\XMLMintrud\XML\XMLRenderer;
use Pakypc\XMLMintrud\XML\Exception\XMLRenderException;

#[CoversClass(XMLRenderer::class)]
final class XMLRendererTest extends TestCase
{
    private string $validXml;
    private string $testFilePath;

    /**
     * Подготовка тестовых данных
     */
    protected function setUp(): void
    {
        // Создаем тестовый XML для использования в тестах
        $this->validXml = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<RegistrySet>
    <RegistryRecord outerId="12345">
        <Worker>
            <LastName>Иванов</LastName>
            <FirstName>Иван</FirstName>
            <MiddleName>Иванович</MiddleName>
            <Snils>123-456-789 01</Snils>
            <Position>Инженер</Position>
            <EmployerInn>1234567890</EmployerInn>
            <EmployerTitle>ООО "Рога и Копыта"</EmployerTitle>
        </Worker>
        <Organization>
            <Inn>9876543210</Inn>
            <EmployerTitle>Учебный центр "Знание"</EmployerTitle>
        </Organization>
        <Test isPassed="1" learnProgramId="42">
            <Date>2023-05-15</Date>
            <ProtocolNumber>PROT-2023-001</ProtocolNumber>
            <LearnProgramTitle>Охрана труда</LearnProgramTitle>
        </Test>
    </RegistryRecord>
</RegistrySet>
XML;

        // Создаем временный файл с XML данными
        $this->testFilePath = sys_get_temp_dir() . '/test_xml_' . uniqid() . '.xml';
        file_put_contents($this->testFilePath, $this->validXml);
    }

    /**
     * Очистка тестовых данных
     */
    protected function tearDown(): void
    {
        // Удаляем временный файл
        if (file_exists($this->testFilePath)) {
            unlink($this->testFilePath);
        }
    }

    /**
     * Тест создания рендерера из XML файла
     */
    public function testFromFileCreatesRendererWhenFileExists(): void
    {
        // Arrange
        // Файл уже создан в setUp

        // Act
        $renderer = XMLRenderer::fromFile($this->testFilePath);

        // Assert
        self::assertInstanceOf(XMLRenderer::class, $renderer);
    }

    /**
     * Тест исключения при попытке создания рендерера из несуществующего файла
     */
    public function testFromFileThrowsExceptionWhenFileNotExists(): void
    {
        // Arrange
        $nonExistentFile = 'non_existent_file.xml';

        // Assert
        $this->expectException(XMLRenderException::class);
        $this->expectExceptionMessage('XML file not found');

        // Act
        XMLRenderer::fromFile($nonExistentFile);
    }

    /**
     * Тест создания рендерера из строки XML
     */
    public function testFromStringCreatesRendererWithValidXml(): void
    {
        // Arrange
        // XML строка уже определена в setUp

        // Act
        $renderer = XMLRenderer::fromString($this->validXml);

        // Assert
        self::assertInstanceOf(XMLRenderer::class, $renderer);
    }

    /**
     * Тест исключения при создании рендерера из невалидной XML строки
     */
    public function testFromStringThrowsExceptionWithInvalidXml(): void
    {
        // Arrange
        $invalidXml = '<invalid><xml>';

        // Assert
        $this->expectException(XMLRenderException::class);
        $this->expectExceptionMessage('Failed to parse XML');

        // Act
        XMLRenderer::fromString($invalidXml);
    }

    /**
     * Тест генерации HTML из XML
     */
    public function testToHtmlReturnsFormattedHtml(): void
    {
        // Arrange
        $renderer = XMLRenderer::fromString($this->validXml);

        // Act
        $html = $renderer->toHtml();

        // Assert
        self::assertStringContainsString('<!DOCTYPE html>', $html);
        self::assertStringContainsString('<title>Отчет по учащимся</title>', $html);
        self::assertStringContainsString('Иванов Иван Иванович', $html);
        self::assertStringContainsString('123-456-789 01', $html);
        self::assertStringContainsString('Учебный центр "Знание"', $html);
        self::assertStringContainsString('class="passed">Сдан</span>', $html);
    }

    /**
     * Тест сохранения HTML в файл
     */
    public function testToFileReturnsPathToGeneratedFile(): void
    {
        // Arrange
        $renderer = XMLRenderer::fromString($this->validXml);
        $outputPath = sys_get_temp_dir() . '/output_html_' . uniqid() . '.html';

        // Act
        $resultPath = $renderer->toFile($outputPath);

        // Assert
        self::assertSame($outputPath, $resultPath);
        self::assertFileExists($outputPath);

        // Проверка содержимого файла
        $fileContent = file_get_contents($outputPath);
        self::assertStringContainsString('<!DOCTYPE html>', $fileContent);

        // Удаляем временный файл
        unlink($outputPath);
    }

    /**
     * Тест генерации имени выходного файла, если путь не указан
     */
    public function testToFileGeneratesFilenameWhenPathNotSpecified(): void
    {
        // Arrange
        $renderer = XMLRenderer::fromString($this->validXml);

        // Act
        $resultPath = $renderer->toFile();

        // Assert
        self::assertFileExists($resultPath);
        self::assertStringContainsString('rendered_xml_', $resultPath);

        // Удаляем временный файл
        unlink($resultPath);
    }

    /**
     * Тест настройки стилей HTML
     */
    public function testSetStylesCustomizesHtmlAppearance(): void
    {
        // Arrange
        $renderer = XMLRenderer::fromString($this->validXml);
        $customStyles = [
            'body' => 'font-family: Verdana; background-color: #f0f0f0;',
            'h1' => 'color: red;',
        ];

        // Act
        $renderer->setStyles($customStyles);
        $html = $renderer->toHtml();

        // Assert
        self::assertStringContainsString('font-family: Verdana; background-color: #f0f0f0;', $html);
        self::assertStringContainsString('color: red;', $html);
    }
}
