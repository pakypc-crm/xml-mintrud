<?php

declare(strict_types=1);

namespace Pakypc\XMLMintrud\XMLDocument\ValueObject;

use Pakypc\XMLMintrud\Exception\ValidationException;

/**
 *  Value Object для даты экзамена
 */
class ExamDate
{
    private readonly \DateTimeImmutable|string $examDate;

    public function __construct(?string $examDate)
    {
        if ($examDate === null || $examDate === '') {
            throw new ValidationException('Дата сдачи экзамена не указана.');
        }

        try {
            $this->examDate = new \DateTimeImmutable($examDate);
        } catch (\Throwable) {
            throw new ValidationException('Неверный формат даты в поле дата сдачи экзамена.');
        }
    }

    public function __toString(): string
    {
        return $this->examDate->format('Y-m-d');
    }
}
