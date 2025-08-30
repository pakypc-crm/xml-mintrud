<?php

declare(strict_types=1);

namespace Pakypc\XMLMintrud;

use crm\models\CustomStudent;
use crm\models\CustomStudProf;
use crm\models\EduGroup;
use crm\models\EduProgram;
use crm\models\Organization;
use crm\models\StudentInGroup;
use crm\models\studentInGroupProgEx;
use Pakypc\XMLMintrud\Exception\DocumentError;
use Pakypc\XMLMintrud\XMLDocument\CommonData;
use Pakypc\XMLMintrud\XMLDocument\XMLRecord;

/**
 * Класс для создания XML-документа с данными об обученных лицах
 *
 * Этот класс предоставляет простой интерфейс для формирования XML-документа
 * на основе данных из CRM в соответствии с XSD-схемой educated_person_import_v1.0.8.xsd.
 *
 * ```php
 *  // Создаём сущность документа с общими данными
 *  $document = XMLDocument::create($commonData);
 *
 *  // Добавляем информацию об учащихся
 *  $document->add($student1, $studentInGroup1, $position1, $group1, $program1, $organization1);
 *  $document->add($student2, $studentInGroup2, $position2, $group2, $program2, $organization2);
 *
 *  // Получаем XML-документ
 *  echo (string) $document;
 * ```
 */
final class XMLDocument implements \Stringable
{
    /** @var list<XMLRecord> Список записей учащихся */
    private array $records = [];

    /** @var list<DocumentError> Список ошибок документа */
    private array $exceptions = [];

    /**
     * @param CommonData $commonData Общие данные для XML-документа
     */
    private function __construct(
        private readonly CommonData $commonData,
    ) {}

    /**
     * Создает новый экземпляр XMLDocument
     *
     * @param CommonData $commonData Общие данные для XML-документа
     * @return self Экземпляр XMLDocument
     */
    public static function create(CommonData $commonData): self
    {
        return new self($commonData);
    }

    /**
     * Добавляет информацию об одном учащемся
     *
     * @param \crm\models\CustomStudent $student Данные студента
     * @param StudentInGroup $studentInGroup Данные студента в группе
     * @param studentInGroupProgEx $studentInGroupProgEx Дополнительные данные о программе обучения
     * @param CustomStudProf $position Данные о должности студента
     * @param EduGroup $group Данные о группе обучения
     * @param \crm\models\EduProgram $program Данные о программе обучения
     * @param Organization $organization Данные об организации
     * @return self Текущий экземпляр XMLDocument для цепочки вызовов
     */
    public function add(
        CustomStudent $student,
        StudentInGroup $studentInGroup,
        studentInGroupProgEx $studentInGroupProgEx,
        CustomStudProf $position,
        EduGroup $group,
        EduProgram $program,
        Organization $organization,
    ): self {
        try {
            $this->records[] = XMLRecord::create(
                $student,
                $studentInGroup,
                $studentInGroupProgEx,
                $position,
                $group,
                $program,
                $organization,
                $this->commonData,
            );
        } catch (\Throwable $exception) {
            $this->exceptions[] = new DocumentError(
                $exception,
                $student,
                $studentInGroup,
                $studentInGroupProgEx,
                $position,
                $group,
                $program,
                $organization,
            );
        }
        return $this;
    }

    /**
     * @return  list<DocumentError> Список ошибок документа
     */
    public function getExceptions(): array
    {
        return $this->exceptions;
    }

    /**
     * Валидирует созданный XML-документ по XSD-схеме
     *
     * @param string|null $schemaPath Путь к файлу XSD-схемы (если не указан, используется стандартный путь)
     * @return bool Результат валидации
     */
    public function validate(?string $schemaPath = null): bool
    {
        $schemaPath = $schemaPath ?? __DIR__ . '/../../resources/educated_person_import_v1.0.8.xsd';

        $dom = new \DOMDocument();
        $dom->loadXML($this->__toString());

        return $dom->schemaValidate($schemaPath);
    }

    /**
     * Сохраняет XML-документ в файл
     *
     * @param string $filePath Путь к файлу для сохранения
     * @return bool Результат сохранения
     */
    public function saveToFile(string $filePath): bool
    {
        return \file_put_contents($filePath, $this->__toString()) !== false;
    }

    /**
     * Возвращает количество записей в документе
     *
     * @return int Количество записей
     */
    public function getRecordCount(): int
    {
        return \count($this->records);
    }

    /**
     * Генерирует и возвращает XML-документ
     *
     * @return string XML-документ в виде строки
     */
    public function __toString(): string
    {
        $dom = new \DOMDocument('1.0', 'utf-8');
        $dom->formatOutput = true;

        // Создаем корневой элемент
        $rootElement = $dom->createElement('RegistrySet');
        $dom->appendChild($rootElement);

        // Добавляем все записи
        foreach ($this->records as $record) {
            $recordElement = $record->toXml($dom);
            $rootElement->appendChild($recordElement);
        }

        return $dom->saveXML();
    }
}
