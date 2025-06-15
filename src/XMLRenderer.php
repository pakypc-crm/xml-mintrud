<?php

declare(strict_types=1);

namespace Pakypc\XMLMintrud;

use DOMDocument;
use Pakypc\XMLMintrud\Exception\XMLRenderException;

/**
 * Класс для преобразования XML в HTML с использованием Bootstrap 3
 *
 * Этот класс предоставляет интерфейс для загрузки XML-данных из файла или строки
 * и преобразования их в HTML-таблицу с использованием разметки Bootstrap 3.
 *
 * ```php
 *  // Создание из файла
 *  $renderer = XMLRenderer::fromFile('data.xml');
 *
 *  // Вывод HTML
 *  echo $renderer->toHtml();
 *
 *  // Сохранение в файл
 *  $renderer->toFile('output.html');
 * ```
 */
final class XMLRenderer
{
    /** @var \DOMDocument XML документ */
    private \DOMDocument $document;

    /**
     * Создает экземпляр XMLRenderer из объекта DOMDocument
     *
     * @param \DOMDocument $document DOM документ
     */
    private function __construct(\DOMDocument $document)
    {
        $this->document = $document;
    }

    /**
     * Создает экземпляр XMLRenderer из файла
     *
     * @param string $xmlFile Путь к XML файлу
     * @return self Экземпляр XMLRenderer
     * @throws XMLRenderException Если файл не существует или не удается загрузить XML
     */
    public static function fromFile(string $xmlFile): self
    {
        if (!\file_exists($xmlFile)) {
            throw new XMLRenderException("Файл XML не найден: {$xmlFile}");
        }

        $document = new \DOMDocument();

        // Подавляем ошибки и затем проверяем успешность загрузки
        $result = @$document->load($xmlFile);

        if (!$result) {
            throw new XMLRenderException("Не удалось загрузить XML из файла: {$xmlFile}");
        }

        return new self($document);
    }

    /**
     * Создает экземпляр XMLRenderer из строки XML
     *
     * @param string $xml Строка, содержащая XML
     * @return self Экземпляр XMLRenderer
     * @throws XMLRenderException Если не удается загрузить XML
     */
    public static function fromString(string $xml): self
    {
        $document = new \DOMDocument();

        // Подавляем ошибки и затем проверяем успешность загрузки
        $result = @$document->loadXML($xml);

        if (!$result) {
            throw new XMLRenderException("Не удалось загрузить XML из строки");
        }

        return new self($document);
    }

    /**
     * Конвертирует XML в HTML с таблицей Bootstrap
     *
     * @return string HTML-строка с таблицей
     */
    public function toHtml(): string
    {
        // Получаем корневой элемент (RegistrySet)
        $root = $this->document->documentElement;

        if (!$root || $root->nodeName !== 'RegistrySet') {
            return '<div class="alert alert-danger">Неверный формат XML: ожидается корневой элемент RegistrySet</div>';
        }

        // Получаем все записи (RegistryRecord)
        $records = $root->getElementsByTagName('RegistryRecord');

        if ($records->length === 0) {
            return '<div class="alert alert-warning">Нет данных для отображения</div>';
        }

        // Начало HTML-контента
        $html = '';

        // Получаем первую запись для определения структуры XML
        $firstRecord = $records->item(0);
        $columnNames = []; // Массив для хранения названий колонок из XML

        // Получаем ID как первую колонку
        $columnNames[] = 'outerId';

        // Получаем названия колонок из Worker
        $firstWorker = $firstRecord->getElementsByTagName('Worker')->item(0);
        if ($firstWorker) {
            foreach ($firstWorker->childNodes as $node) {
                if ($node->nodeType === XML_ELEMENT_NODE) {
                    $columnNames[] = $node->nodeName;
                }
            }
        }

        // Получаем названия колонок из Organization
        $firstOrg = $firstRecord->getElementsByTagName('Organization')->item(0);
        if ($firstOrg) {
            foreach ($firstOrg->childNodes as $node) {
                if ($node->nodeType === XML_ELEMENT_NODE) {
                    $columnNames[] = 'Org' . $node->nodeName; // Префикс для избежания дублирования
                }
            }
        }

        // Получаем названия колонок из Test
        $firstTest = $firstRecord->getElementsByTagName('Test')->item(0);
        if ($firstTest) {
            // Добавляем атрибуты теста
            if ($firstTest->hasAttribute('isPassed')) {
                $columnNames[] = 'isPassed';
            }
            if ($firstTest->hasAttribute('learnProgramId')) {
                $columnNames[] = 'learnProgramId';
            }

            // Добавляем элементы теста
            foreach ($firstTest->childNodes as $node) {
                if ($node->nodeType === XML_ELEMENT_NODE) {
                    $columnNames[] = $node->nodeName;
                }
            }
        }

        // Начало таблицы с компактным стилем и без прокрутки
        $html .= '<div class="table-responsive">
            <table class="table table-striped table-bordered table-hover table-compact">
                <thead>
                    <tr class="bg-primary">';

        // Добавляем заголовки колонок с классами для регулировки ширины
        foreach ($columnNames as $columnName) {
            $sizeClass = ''; // По умолчанию нет дополнительного класса

            // Определяем размер колонки в зависимости от типа данных
            if (\in_array($columnName, ['outerId', 'learnProgramId', 'isPassed'])) {
                $sizeClass = 'narrow-col';
            } elseif (\in_array($columnName, ['FirstName', 'LastName', 'MiddleName', 'Snils', 'Position'])) {
                $sizeClass = 'medium-col';
            } elseif (\in_array($columnName, ['OrgTitle', 'EmployerTitle', 'LearnProgramTitle'])) {
                $sizeClass = 'wide-col';
            }

            $html .= '<th class="' . $sizeClass . '">' . $columnName . '</th>';
        }

        $html .= '</tr>
                </thead>
                <tbody>';

        // Заполняем таблицу данными
        foreach ($records as $record) {
            $html .= '<tr>';

            // Для каждой колонки получаем значение
            foreach ($columnNames as $columnName) {
                $value = '';

                if ($columnName === 'outerId') {
                    $value = $record->getAttribute('outerId') ?: '-';
                } elseif ($columnName === 'isPassed') {
                    $test = $record->getElementsByTagName('Test')->item(0);
                    if ($test && $test->hasAttribute('isPassed')) {
                        $value = $test->getAttribute('isPassed') === '1' ? 'Сдано' : 'Не сдано';
                    }
                } elseif ($columnName === 'learnProgramId') {
                    $test = $record->getElementsByTagName('Test')->item(0);
                    if ($test && $test->hasAttribute('learnProgramId')) {
                        $value = $test->getAttribute('learnProgramId');
                    }
                } elseif (\strpos($columnName, 'Org') === 0) {
                    // Для полей Organization
                    $orgColumnName = \substr($columnName, 3); // Удаляем префикс 'Org'
                    $organization = $record->getElementsByTagName('Organization')->item(0);
                    $value = $this->getElementValue($organization, $orgColumnName);
                } else {
                    // Проверяем в Worker
                    $worker = $record->getElementsByTagName('Worker')->item(0);
                    $workerValue = $this->getElementValue($worker, $columnName);

                    if ($workerValue !== '') {
                        $value = $workerValue;
                    } else {
                        // Проверяем в Test
                        $test = $record->getElementsByTagName('Test')->item(0);
                        $value = $this->getElementValue($test, $columnName);
                    }
                }

                $html .= '<td>' . $value . '</td>';
            }

            $html .= '</tr>';
        }

        // Закрываем таблицу
        $html .= '</tbody>
            </table>
        </div>';


        return $html;
    }

    /**
     * Сохраняет HTML-представление в файл
     *
     * @param string $outputFile Путь к файлу для сохранения
     * @return bool Результат сохранения
     */
    public function toFile(string $outputFile): bool
    {
        return \file_put_contents($outputFile, $this->toHtml()) !== false;
    }

    /**
     * Получает значение элемента по имени тега
     *
     * @param \DOMNode|null $parentNode Родительский узел
     * @param string $tagName Имя тега
     * @return string Значение элемента или пустая строка, если элемент не найден
     */
    private function getElementValue(?\DOMNode $parentNode, string $tagName): string
    {
        if (!$parentNode) {
            return '';
        }

        $elements = $parentNode->getElementsByTagName($tagName);

        if ($elements->length > 0) {
            return $elements->item(0)->textContent;
        }

        return '';
    }
}
