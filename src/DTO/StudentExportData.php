<?php

declare(strict_types=1);

namespace App\DTO;

/**
 * DTO для хранения данных студента для экспорта в XML.
 * 
 * Этот класс инкапсулирует все необходимые данные для формирования XML-записи
 * о студенте согласно схеме educated_person_import_v1.0.8.xsd.
 */
final class StudentExportData
{
    /**
     * @param non-empty-string $lastName Фамилия студента
     * @param non-empty-string $firstName Имя студента
     * @param string $middleName Отчество студента (может быть пустым)
     * @param string|null $snils СНИЛС студента (может отсутствовать)
     * @param bool $isForeignSnils Признак иностранного СНИЛС
     * @param string|null $foreignSnils Иностранный СНИЛС (если применимо)
     * @param string|null $citizenship Гражданство студента
     * @param non-empty-string $position Должность студента
     * @param non-empty-string $employerInn ИНН работодателя
     * @param non-empty-string $employerTitle Название работодателя
     * @param non-empty-string $organizationInn ИНН образовательной организации
     * @param non-empty-string $organizationTitle Название образовательной организации
     * @param \DateTimeImmutable $examDate Дата проведения экзамена
     * @param non-empty-string $protocolNumber Номер протокола
     * @param non-empty-string $learnProgramTitle Название программы обучения
     * @param int $learnProgramId Идентификатор программы обучения
     * @param bool $isPassed Признак успешной сдачи экзамена
     * @param string|null $outerId Внешний идентификатор (может отсутствовать)
     */
    public function __construct(
        public readonly string $lastName,
        public readonly string $firstName,
        public readonly string $middleName,
        public readonly ?string $snils,
        public readonly bool $isForeignSnils,
        public readonly ?string $foreignSnils,
        public readonly ?string $citizenship,
        public readonly string $position,
        public readonly string $employerInn,
        public readonly string $employerTitle,
        public readonly string $organizationInn,
        public readonly string $organizationTitle,
        public readonly \DateTimeImmutable $examDate,
        public readonly string $protocolNumber,
        public readonly string $learnProgramTitle,
        public readonly int $learnProgramId,
        public readonly bool $isPassed,
        public readonly ?string $outerId = null,
    ) {
    }

    /**
     * Создает новый экземпляр с измененными данными об экзамене.
     * 
     * @param \DateTimeImmutable $examDate Новая дата экзамена
     * @param non-empty-string $protocolNumber Новый номер протокола
     * @param bool $isPassed Новый признак сдачи экзамена
     * @return self Новый экземпляр с обновленными данными
     */
    public function withExamResult(
        \DateTimeImmutable $examDate,
        string $protocolNumber,
        bool $isPassed
    ): self {
        return new self(
            $this->lastName,
            $this->firstName,
            $this->middleName,
            $this->snils,
            $this->isForeignSnils,
            $this->foreignSnils,
            $this->citizenship,
            $this->position,
            $this->employerInn,
            $this->employerTitle,
            $this->organizationInn,
            $this->organizationTitle,
            $examDate,
            $protocolNumber,
            $this->learnProgramTitle,
            $this->learnProgramId,
            $isPassed,
            $this->outerId
        );
    }

    /**
     * Создает новый экземпляр с измененным внешним идентификатором.
     * 
     * @param string|null $outerId Новый внешний идентификатор
     * @return self Новый экземпляр с обновленным идентификатором
     */
    public function withOuterId(?string $outerId): self
    {
        return new self(
            $this->lastName,
            $this->firstName,
            $this->middleName,
            $this->snils,
            $this->isForeignSnils,
            $this->foreignSnils,
            $this->citizenship,
            $this->position,
            $this->employerInn,
            $this->employerTitle,
            $this->organizationInn,
            $this->organizationTitle,
            $this->examDate,
            $this->protocolNumber,
            $this->learnProgramTitle,
            $this->learnProgramId,
            $this->isPassed,
            $outerId
        );
    }
}
