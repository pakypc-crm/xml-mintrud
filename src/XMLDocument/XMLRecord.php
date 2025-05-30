<?php

declare(strict_types=1);

namespace Pakypc\XMLMintrud\XMLDocument;

use crm\models\CustomStudent;
use crm\models\CustomStudProf;
use crm\models\EduGroup;
use crm\models\EduProgram;
use crm\models\Organization;
use crm\models\StudentInGroup;
use crm\models\studentInGroupProgEx;
use DateTimeImmutable;
use Pakypc\XMLMintrud\XMLDocument\ValueObject\Citizenship;
use Pakypc\XMLMintrud\XMLDocument\ValueObject\Inn;
use Pakypc\XMLMintrud\XMLDocument\ValueObject\LearnProgramId;
use Pakypc\XMLMintrud\XMLDocument\ValueObject\Name;
use Pakypc\XMLMintrud\XMLDocument\ValueObject\OuterId;
use Pakypc\XMLMintrud\XMLDocument\ValueObject\Position;
use Pakypc\XMLMintrud\XMLDocument\ValueObject\ProtocolNumber;
use Pakypc\XMLMintrud\XMLDocument\ValueObject\Snils;
use Pakypc\XMLMintrud\XMLDocument\ValueObject\Title;

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
     * @param ?Snils $foreignSnils Иностранный СНИЛС
     * @param ?Citizenship $citizenship Гражданство
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
        private readonly ?Snils $foreignSnils,
        private readonly ?Citizenship $citizenship,
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
     * @param customStudent $student Данные студента
     * @param \crm\models\studentInGroup $studentInGroup Данные студента в группе
     * @param \crm\models\studentInGroupProgEx $studentInGroupProgEx
     * @param \crm\models\customStudProf $position Данные о должности студента
     * @param \crm\models\eduGroup $group Данные о группе обучения
     * @param eduProgram $program Данные о программе обучения
     * @param \crm\models\organization $organization Данные об организации
     * @param CommonData $commonData Общие данные
     * @return self Экземпляр XMLRecord
     */
    public static function create(
        CustomStudent $student,
        StudentInGroup $studentInGroup,
        studentInGroupProgEx $studentInGroupProgEx,
        CustomStudProf $position,
        EduGroup $group,
        EduProgram $program,
        Organization $organization,
        CommonData $commonData
    ): self {
        // если есть флаг "своя дата экзамена", то берём свою дату экзамена
        $dateStr = $studentInGroupProgEx->sPr_examenCustom ? $studentInGroupProgEx->sPr_examenDate : null;

        // Если с предыдущего шага получили null, то берём следующие даты в порядке приоритета
        $dateStr ??= $studentInGroup->examendate ?? $group->examen;

        // Получаем объект даты/времени
        $testDate = new DateTimeImmutable($dateStr);

        return new self(
            new Name($student->name2), // Фамилия
            new Name($student->name1), // Имя
            new Name($student->name3), // Отчество
            new Snils($student->snilsNumber), // СНИЛС
            null, // IsForeignSnils - по умолчанию null
            null, // ForeignSnils - по умолчанию null
            null, // Citizenship - по умолчанию null
            new Position($position->post), // Должность
            new Inn($organization->orgINN), // ИНН работодателя
            new Title($organization->orgName), // Название работодателя
            new Inn($commonData->organizationInn), // ИНН организации обучения
            new Title($commonData->organizationTitle), // Название организации обучения
            $testDate, // Дата экзамена
            new ProtocolNumber($studentInGroupProgEx->sPr_protoNumber), // Номер протокола
            new Title($program->name), // Название программы обучения
            (bool) $studentInGroup->examenated, // Признак успешной сдачи экзамена
            new LearnProgramId($program->mintrudId), // ID программы обучения по схеме
            new OuterId($student->id), // Внешний идентификатор записи
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
