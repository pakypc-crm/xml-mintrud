<?php

declare(strict_types=1);

namespace App\Service\Validator;

use App\Exception\XmlValidationException;

/**
 * Валидатор XML-документов.
 * 
 * Выполняет проверку XML-документов на соответствие XSD-схеме.
 */
final class XmlValidator
{
    /**
     * Путь к XSD-схеме.
     */
    private const DEFAULT_XSD_PATH = __DIR__ . '/../../../resources/educated_person_import_v1.0.8.xsd';

    /**
     * @param string $xsdPath Путь к XSD-схеме для валидации
     */
    public function __construct(
        private readonly string $xsdPath = self::DEFAULT_XSD_PATH,
    ) {
    }

    /**
     * Проверяет XML-документ на соответствие XSD-схеме.
     * 
     * @param \DOMDocument $document XML-документ для проверки
     * @return bool Результат валидации (true, если документ соответствует схеме)
     * 
     * @throws XmlValidationException Если произошла ошибка при валидации
     */
    public function validate(\DOMDocument $document): bool
    {
        // Проверяем существование файла схемы
        if (!file_exists($this->xsdPath)) {
            throw new XmlValidationException(sprintf('XSD схема не найдена по пути: %s', $this->xsdPath));
        }
        
        // Сохраняем текущее состояние обработки ошибок
        $previousLibXmlUseErrors = libxml_use_internal_errors(true);
        
        // Пытаемся выполнить валидацию
        $isValid = $document->schemaValidate($this->xsdPath);
        
        // Если валидация не прошла, собираем и форматируем ошибки
        if (!$isValid) {
            $errors = libxml_get_errors();
            $errorMessages = [];
            
            foreach ($errors as $error) {
                $errorMessages[] = sprintf(
                    'Строка %d, колонка %d: %s', 
                    $error->line, 
                    $error->column,
                    trim($error->message)
                );
            }
            
            // Очищаем ошибки libxml
            libxml_clear_errors();
            
            // Восстанавливаем предыдущее состояние обработки ошибок
            libxml_use_internal_errors($previousLibXmlUseErrors);
            
            // Выбрасываем исключение с детальным описанием ошибок
            throw new XmlValidationException(
                sprintf("XML не соответствует XSD-схеме:\n%s", implode("\n", $errorMessages))
            );
        }
        
        // Восстанавливаем предыдущее состояние обработки ошибок
        libxml_use_internal_errors($previousLibXmlUseErrors);
        
        return true;
    }
    
    /**
     * Проверяет строку XML на соответствие XSD-схеме.
     * 
     * @param string $xmlString Строка XML для проверки
     * @return bool Результат валидации (true, если XML соответствует схеме)
     * 
     * @throws XmlValidationException Если произошла ошибка при валидации
     */
    public function validateString(string $xmlString): bool
    {
        $document = new \DOMDocument('1.0', 'utf-8');
        
        // Сохраняем текущее состояние обработки ошибок
        $previousLibXmlUseErrors = libxml_use_internal_errors(true);
        
        // Пытаемся загрузить XML-строку
        $loadResult = $document->loadXML($xmlString);
        
        // Если не удалось загрузить XML, собираем ошибки
        if (!$loadResult) {
            $errors = libxml_get_errors();
            $errorMessages = [];
            
            foreach ($errors as $error) {
                $errorMessages[] = sprintf(
                    'Строка %d, колонка %d: %s', 
                    $error->line, 
                    $error->column,
                    trim($error->message)
                );
            }
            
            // Очищаем ошибки libxml
            libxml_clear_errors();
            
            // Восстанавливаем предыдущее состояние обработки ошибок
            libxml_use_internal_errors($previousLibXmlUseErrors);
            
            // Выбрасываем исключение
            throw new XmlValidationException(
                sprintf("Некорректный XML:\n%s", implode("\n", $errorMessages))
            );
        }
        
        // Восстанавливаем предыдущее состояние обработки ошибок для загрузки XML
        libxml_use_internal_errors($previousLibXmlUseErrors);
        
        // Валидируем загруженный документ
        return $this->validate($document);
    }
}
