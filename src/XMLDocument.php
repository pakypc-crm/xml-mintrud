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
     * Массив программ обучения МинТруда и их ID
     */
    private array $programsTitle = [
        1 => 'Оказание первой помощи пострадавшим',
        2 => 'Использование (применение) средств индивидуальной защиты',
        3 => 'Общие вопросы охраны труда и функционирования системы управления охраной труда',
        4 => 'Безопасные методы и приемы выполнения работ при воздействии вредных и (или) опасных производственных ' .
                'факторов, источников опасности, идентифицированных в рамках специальной оценки условий ' .
                 'труда и оценки профессиональных рисков',
        6 => 'Безопасные методы и приемы выполнения земляных работ',
        7 => 'Безопасные методы и приемы выполнения ремонтных, монтажных и демонтажных работ зданий и сооружений',
        8 => 'Безопасные методы и приемы выполнения работ при размещении, монтаже, техническом обслуживании ' .
                'и ремонте технологического оборудования (включая технологическое оборудование)',
        9 => 'Безопасные методы и приемы выполнения работ на высоте',
        10 => 'Безопасные методы и приемы выполнения пожароопасных работ',
        11 => 'Безопасные методы и приемы выполнения работ в ограниченных и замкнутых пространствах (ОЗП)',
        12 => 'Безопасные методы и приемы выполнения строительных работ, ' .
                'в том числе: - окрасочные работы - электросварочные и газосварочные работы',
        13 => 'Безопасные методы и приемы выполнения работ, связанных с опасностью воздействия сильнодействующих ' .
                'и ядовитых веществ',
        14 => 'Безопасные методы и приемы выполнения газоопасных работ',
        15 => 'Безопасные методы и приемы выполнения огневых работ',
        16 => 'Безопасные методы и приемы выполнения работ, связанные с эксплуатацией подъемных сооружений',
        17 => 'Безопасные методы и приемы выполнения работ, связанные с эксплуатацией тепловых энергоустановок',
        18 => 'Безопасные методы и приемы выполнения работ в электроустановках',
        19 => 'Безопасные методы и приемы выполнения работ, связанные с эксплуатацией сосудов, работающих ' .
                'под избыточным давлением',
        20 => 'Безопасные методы и приемы обращения с животными',
        21 => 'Безопасные методы и приемы при выполнении водолазных работ',
        22 => 'Безопасные методы и приемы работ по поиску, идентификации, обезвреживанию и уничтожению ' .
                'взрывоопасных предметов',
        23 => 'Безопасные методы и приемы работ в непосредственной близости от полотна или проезжей части ' .
                'эксплуатируемых автомобильных и железных дорог',
        24 => 'Безопасные методы и приемы работ, на участках с патогенным заражением почвы',
        25 => 'Безопасные методы и приемы работ по валке леса в особо опасных условиях',
        26 => 'Безопасные методы и приемы работ по перемещению тяжеловесных и крупногабаритных грузов ' .
                'при отсутствии машин соответствующей грузоподъемности и разборке покосившихся ' .
                'и опасных (неправильно уложенных) штабелей круглых лесоматериалов',
        27 => 'Безопасные методы и приемы работ с радиоактивными веществами и источниками ионизирующих излучений',
        28 => 'Безопасные методы и приемы работ с ручным инструментом, в том числе с пиротехническим',
        29 => 'Безопасные методы и приемы работ в театрах',
    ];

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

    public function defineLearnPrograms(array $programs): void
    {
        $this->programsTitle = $programs;
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
            # Итерируем номера программ для МинТруда
            $numbers = \explode(',', (string) $program->mintrudId);
            \count($numbers) === 1 and empty($numbers[0]) and throw new \InvalidArgumentException(
                'Номер учебной программы не указан.',
            );

            foreach ($numbers as $number) {
                $learnProgramTitle = $this->programsTitle[$number];

                $this->records[] = XMLRecord::create(
                    $student,
                    $studentInGroup,
                    $studentInGroupProgEx,
                    $position,
                    $group,
                    $organization,
                    $this->commonData,
                    learnProgramId: $number,
                    learnProgramTitle: $learnProgramTitle,
                );
            }
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
    public function validate(?string $schemaPath = null): array
    {
        $schemaPath = $schemaPath ?? __DIR__ . '/../resources/educated_person_import_v1.0.9.xsd';

        $dom = new \DOMDocument();
        $dom->loadXML($this->__toString());

        $errors = [];
        $handler = \set_error_handler(static function ($errno, $errstr) use (&$errors): void {
            $errors[] = new \ErrorException($errstr, $errno);
        }, E_WARNING | E_NOTICE | E_STRICT);
        try {
            $dom->schemaValidate($schemaPath);
        } finally {
            \set_error_handler($handler);
        }

        return $errors;
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
