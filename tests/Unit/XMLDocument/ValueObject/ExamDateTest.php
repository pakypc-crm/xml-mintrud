<?php

declare(strict_types=1);

namespace Pakypc\XMLMintrud\Tests\XMLDocument\ValueObject;

use Pakypc\XMLMintrud\XMLDocument\ValueObject\ExamDate;
use Pakypc\XMLMintrud\Exception\ValidationException;
use PHPUnit\Framework\TestCase;

class ExamDateTest extends TestCase
{
    // Проверяем, что класс корректно создается с правильной датой
    public function testCreatesWithValidDate(): void
    {
        $date = new ExamDate('2023-12-31');
        $this->assertEquals('2023-12-31', (string)$date); // Проверяем формат вывода
    }

    // Проверяем обработку пустого значения
    public function testThrowsExceptionForEmptyDate(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Дата сдачи экзамена не указана.');

        new ExamDate(null); // null не допускается
    }

    // Проверяем обработку пустой строки
    public function testThrowsExceptionForEmptyString(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Дата сдачи экзамена не указана.');

        new ExamDate(''); // Пустая строка не допускается
    }

    // Проверяем обработку неверного формата даты
    public function testThrowsExceptionForInvalidFormat(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Неверный формат даты в поле дата сдачи экзамена.');

        new ExamDate('invalid-date'); // Неправильный формат даты
    }

    // Проверяем преобразование в строку
    public function testConvertsToStringCorrectly(): void
    {
        $date = new ExamDate('2023-01-15');
        $this->assertSame('2023-01-15', (string)$date); // Проверяем формат Y-m-d
    }
}
