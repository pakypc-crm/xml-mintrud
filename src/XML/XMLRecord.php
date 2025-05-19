<?php

declare(strict_types=1);

namespace Pakypc\XMLMintrud\XML;

use crm\models\CustomStudent;
use crm\models\CustomStudProf;
use crm\models\EduGroup;
use crm\models\EduProgram;
use crm\models\Organization;
use crm\models\StudentInGroup;
use DateTimeImmutable;
use Pakypc\XMLMintrud\ValueObject\Snils;
use Pakypc\XMLMintrud\ValueObject\Name;
use Pakypc\XMLMintrud\ValueObject\Title;
use Pakypc\XMLMintrud\ValueObject\Inn;
use Pakypc\XMLMintrud\ValueObject\Position;
use Pakypc\XMLMintrud\ValueObject\LearnProgramId;
use Pakypc\XMLMintrud\ValueObject\ProtocolNumber;
use Pakypc\XMLMintrud\ValueObject\Citizenship;
use Pakypc\XMLMintrud\ValueObject\OuterId;

/**
 * DTO для хранения данных о записи учащегося
 *
 * Содержит все необходимые данные для формирования одной записи в XML-документе
 * в соответствии с XSD-схемой.
 */

final class XMLRecord
{
    /**
     * @param Name $lastName Фамилия работника
     * @param Name $firstName Имя работника
     * @param Name $middleName Отчество работника
     * @param Snils $snils СНИЛС работника
     * @param bool|null $isForeignSnils Признак иностранного СНИЛС
     * @param Snils $foreignSnils Иностранный СНИЛС
     * @param Citizenship $citizenship Гражданство
     * @param Position $position Должность
     * @param Inn $employerInn ИНН работодателя
     * @param Title $employerTitle Название работодателя
     * @param Inn $organizationInn ИНН организации обучения
     * @param Title $organizationTitle Название организации обучения
     * @param DateTimeImmutable $testDate Дата экзамена
     * @param ProtocolNumber $protocolNumber Номер протокола
     * @param Title $learnProgramTitle Название программы обучения
     * @param bool $isPassed Признак успешной сдачи экзамена
     * @param LearnProgramId $learnProgramId ID программы обучения по схеме
     * @param OuterId $outerId Внешний идентификатор записи
     */
    private function __construct(
        private readonly Name $lastName,
        private readonly Name $firstName,
        private readonly Name $middleName,
        private readonly Snils $snils,
        private readonly ?bool $isForeignSnils,
        private readonly Snils $foreignSnils,
        private readonly Citizenship $citizenship,
        private readonly Position $position,
        private readonly Inn $employerInn,
        private readonly Title $employerTitle,
        private readonly Inn $organizationInn,
        private readonly Title $organizationTitle,
        private readonly DateTimeImmutable $testDate,
        private readonly ProtocolNumber $protocolNumber,
        private readonly Title $learnProgramTitle,
        private readonly bool $isPassed,
        private readonly LearnProgramId $learnProgramId,
        private readonly OuterId $outerId,
    ) {
    }

    /**
     * Создает экземпляр XMLRecord на основе данных из CRM
     *
     * @param CustomStudent $student Данные студента
     * @param \crm\models\StudentInGroup $studentInGroup Данные студента в группе
     * @param \crm\models\CustomStudProf $position Данные о должности студента
     * @param \crm\models\EduGroup $group Данные о группе обучения
     * @param EduProgram $program Данные о программе обучения
     * @param \crm\models\Organization $organization Данные об организации
     * @param CommonData $commonData Общие данные
     * @return self Экземпляр XMLRecord
     */
    public static function create(
        CustomStudent $student,
        StudentInGroup $studentInGroup,
        CustomStudProf $position,
        EduGroup $group,
        EduProgram $program,
        Organization $organization,
        CommonData $commonData
    ): self {
        // Получаем дату экзамена
        $testDate = !empty($studentInGroup->examendate)
            ? new DateTimeImmutable($studentInGroup->examendate)
            : (!empty($group->examen)
                ? new DateTimeImmutable($group->examen)
                : new DateTimeImmutable());

        // Получаем номер протокола (используем номер сертификата, если есть)
        $protocolNumber = !empty($studentInGroup->certNumber)
            ? $studentInGroup->certNumber
            : ('PROT-' . $group->id . '-' . $student->id);

        return new self(
            new Name($student->name2), // Фамилия
            new Name($student->name1), // Имя
            new Name($student->name3), // Отчество
            new Snils($student->snilsNumber), // СНИЛС
            null, // IsForeignSnils - по умолчанию null
            null, // ForeignSnils - по умолчанию null
            null, // Citizenship - по умолчанию null
            $position->post ?? 'Специалист', // Должность
            $organization->orgINN ?? '0000000000', // ИНН работодателя
            $organization->orgName, // Название работодателя
            $commonData->getOrganizationInn(), // ИНН организации обучения
            $commonData->getOrganizationTitle(), // Название организации обучения
            $testDate, // Дата экзамена
            $protocolNumber, // Номер протокола
            $program->name, // Название программы обучения
            $studentInGroup->examenated, // Признак успешной сдачи экзамена
            $program->id, // ID программы обучения по схеме
            (string) $student->id, // Внешний идентификатор записи
        );
    }

    /**
     * Преобразует запись в XML-элемент
     *
     * @param \DOMDocument $document DOM-документ
     * @return \DOMElement XML-элемент
     */
    public function toXml(\DOMDocument $document): \DOMElement
    {
        // Создаем элемент RegistryRecord
        $recordElement = $document->createElement('RegistryRecord');

        // Добавляем атрибут outerId, если он задан
        if ($this->outerId !== null) {
            $recordElement->setAttribute('outerId', (string) $this->outerId);
        }

        // Создаем элемент Worker
        $workerElement = $document->createElement('Worker');

        // Добавляем элементы ФИО
        $workerElement->appendChild($document->createElement('LastName', (string) $this->lastName));
        $workerElement->appendChild($document->createElement('FirstName', (string) $this->firstName));
        $workerElement->appendChild($document->createElement('MiddleName', (string) $this->middleName));

        // Добавляем СНИЛС, если он задан
        if ($this->snils !== null) {
            $workerElement->appendChild($document->createElement('Snils', (string) $this->snils));
        }

        // Добавляем IsForeignSnils, если он задан
        if ($this->isForeignSnils !== null) {
            $workerElement->appendChild($document->createElement('IsForeignSnils', $this->isForeignSnils ? '1' : '0'));
        }

        // Добавляем ForeignSnils, если он задан
        if ($this->foreignSnils !== null) {
            $workerElement->appendChild($document->createElement('ForeignSnils', (string) $this->foreignSnils));
        }

        // Добавляем Citizenship, если оно задано
        if ($this->citizenship !== null) {
            $workerElement->appendChild($document->createElement('Citizenship', (string) $this->citizenship));
        }

        // Добавляем Position
        $workerElement->appendChild($document->createElement('Position', (string) $this->position));

        // Добавляем EmployerInn
        $workerElement->appendChild($document->createElement('EmployerInn', (string) $this->employerInn));

        // Добавляем EmployerTitle
        $workerElement->appendChild($document->createElement('EmployerTitle', (string) $this->employerTitle));

        // Добавляем Worker в RegistryRecord
        $recordElement->appendChild($workerElement);

        // Создаем элемент Organization
        $organizationElement = $document->createElement('Organization');

        // Добавляем Inn
        $organizationElement->appendChild($document->createElement('Inn', (string) $this->organizationInn));

        // Добавляем Title
        $organizationElement->appendChild($document->createElement('Title', (string) $this->organizationTitle));

        // Добавляем Organization в RegistryRecord
        $recordElement->appendChild($organizationElement);

        // Создаем элемент Test
        $testElement = $document->createElement('Test');

        // Добавляем атрибут isPassed
        $testElement->setAttribute('isPassed', $this->isPassed ? '1' : '0');

        // Добавляем атрибут learnProgramId
        $testElement->setAttribute('learnProgramId', (string) $this->learnProgramId);

        // Добавляем Date
        $testElement->appendChild($document->createElement('Date', $this->testDate->format('Y-m-d')));

        // Добавляем ProtocolNumber
        $testElement->appendChild($document->createElement('ProtocolNumber', (string) $this->protocolNumber));

        // Добавляем LearnProgramTitle
        $testElement->appendChild($document->createElement('LearnProgramTitle', (string) $this->learnProgramTitle));

        // Добавляем Test в RegistryRecord
        $recordElement->appendChild($testElement);

        return $recordElement;
    }
}
