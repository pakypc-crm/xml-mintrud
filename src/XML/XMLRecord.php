<?php

declare(strict_types=1);

namespace App\XML;

use App\Model\CustomStudent;
use App\Model\CustomStudProf;
use App\Model\EduGroup;
use App\Model\EduProgram;
use App\Model\Organization;
use App\Model\StudentInGroup;
use DateTimeImmutable;

/**
 * DTO для хранения данных о записи учащегося
 *
 * Содержит все необходимые данные для формирования одной записи в XML-документе
 * в соответствии с XSD-схемой.
 */
final class XMLRecord
{
    /**
     * @param string $lastName Фамилия работника
     * @param string $firstName Имя работника
     * @param string $middleName Отчество работника
     * @param string|null $snils СНИЛС работника
     * @param bool|null $isForeignSnils Признак иностранного СНИЛС
     * @param string|null $foreignSnils Иностранный СНИЛС
     * @param string|null $citizenship Гражданство
     * @param string $position Должность
     * @param string $employerInn ИНН работодателя
     * @param string $employerTitle Название работодателя
     * @param string $organizationInn ИНН организации обучения
     * @param string $organizationTitle Название организации обучения
     * @param DateTimeImmutable $testDate Дата экзамена
     * @param string $protocolNumber Номер протокола
     * @param string $learnProgramTitle Название программы обучения
     * @param bool $isPassed Признак успешной сдачи экзамена
     * @param int $learnProgramId ID программы обучения по схеме
     * @param string|null $outerId Внешний идентификатор записи
     */
    private function __construct(
        private readonly string $lastName,
        private readonly string $firstName,
        private readonly string $middleName,
        private readonly ?string $snils,
        private readonly ?bool $isForeignSnils,
        private readonly ?string $foreignSnils,
        private readonly ?string $citizenship,
        private readonly string $position,
        private readonly string $employerInn,
        private readonly string $employerTitle,
        private readonly string $organizationInn,
        private readonly string $organizationTitle,
        private readonly DateTimeImmutable $testDate,
        private readonly string $protocolNumber,
        private readonly string $learnProgramTitle,
        private readonly bool $isPassed,
        private readonly int $learnProgramId,
        private readonly ?string $outerId,
    ) {
    }

    /**
     * Создает экземпляр XMLRecord на основе данных из CRM
     *
     * @param CustomStudent $student Данные студента
     * @param StudentInGroup $studentInGroup Данные студента в группе
     * @param CustomStudProf $position Данные о должности студента
     * @param EduGroup $group Данные о группе обучения
     * @param EduProgram $program Данные о программе обучения
     * @param Organization $organization Данные об организации
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
            $student->name2, // Фамилия
            $student->name1, // Имя
            $student->name3, // Отчество
            $student->snilsNumber, // СНИЛС
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
            $commonData->getProgramIdByLocal($program->id), // ID программы обучения по схеме
            (string)$student->id, // Внешний идентификатор записи
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
            $recordElement->setAttribute('outerId', $this->outerId);
        }
        
        // Создаем элемент Worker
        $workerElement = $document->createElement('Worker');
        
        // Добавляем элементы ФИО
        $workerElement->appendChild($document->createElement('LastName', $this->lastName));
        $workerElement->appendChild($document->createElement('FirstName', $this->firstName));
        $workerElement->appendChild($document->createElement('MiddleName', $this->middleName));
        
        // Добавляем СНИЛС, если он задан
        if ($this->snils !== null) {
            $workerElement->appendChild($document->createElement('Snils', $this->snils));
        }
        
        // Добавляем IsForeignSnils, если он задан
        if ($this->isForeignSnils !== null) {
            $workerElement->appendChild($document->createElement('IsForeignSnils', $this->isForeignSnils ? '1' : '0'));
        }
        
        // Добавляем ForeignSnils, если он задан
        if ($this->foreignSnils !== null) {
            $workerElement->appendChild($document->createElement('ForeignSnils', $this->foreignSnils));
        }
        
        // Добавляем Citizenship, если оно задано
        if ($this->citizenship !== null) {
            $workerElement->appendChild($document->createElement('Citizenship', $this->citizenship));
        }
        
        // Добавляем Position
        $workerElement->appendChild($document->createElement('Position', $this->position));
        
        // Добавляем EmployerInn
        $workerElement->appendChild($document->createElement('EmployerInn', $this->employerInn));
        
        // Добавляем EmployerTitle
        $workerElement->appendChild($document->createElement('EmployerTitle', $this->employerTitle));
        
        // Добавляем Worker в RegistryRecord
        $recordElement->appendChild($workerElement);
        
        // Создаем элемент Organization
        $organizationElement = $document->createElement('Organization');
        
        // Добавляем Inn
        $organizationElement->appendChild($document->createElement('Inn', $this->organizationInn));
        
        // Добавляем Title
        $organizationElement->appendChild($document->createElement('Title', $this->organizationTitle));
        
        // Добавляем Organization в RegistryRecord
        $recordElement->appendChild($organizationElement);
        
        // Создаем элемент Test
        $testElement = $document->createElement('Test');
        
        // Добавляем атрибут isPassed
        $testElement->setAttribute('isPassed', $this->isPassed ? '1' : '0');
        
        // Добавляем атрибут learnProgramId
        $testElement->setAttribute('learnProgramId', (string)$this->learnProgramId);
        
        // Добавляем Date
        $testElement->appendChild($document->createElement('Date', $this->testDate->format('Y-m-d')));
        
        // Добавляем ProtocolNumber
        $testElement->appendChild($document->createElement('ProtocolNumber', $this->protocolNumber));
        
        // Добавляем LearnProgramTitle
        $testElement->appendChild($document->createElement('LearnProgramTitle', $this->learnProgramTitle));
        
        // Добавляем Test в RegistryRecord
        $recordElement->appendChild($testElement);
        
        return $recordElement;
    }
}
