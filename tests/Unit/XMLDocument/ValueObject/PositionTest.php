<?php

namespace Tests\Unit\XMLDocument\ValueObject;

use Pakypc\XMLMintrud\XMLDocument\ValueObject\Position;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use InvalidArgumentException;

class PositionTest extends TestCase
{
    public static function getPosition()
    {
        yield ["полировщик  ", "полировщик"];
        yield ["  полировщик", "полировщик"];
    }

    #[DataProvider("getPosition")]
    public function testPosition(string $input, string $expected): void
    {
        $position = new Position($input);
        $this->assertEquals($expected, (string) $position);
    }

    // Тест с пустым значением - должно быть исключение
    public function testEmptyValueThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Должность сотрудника не указана');

        new Position('');
    }

    // Тест с null - должно быть исключение
    public function testNullValueThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Position(null);
    }
}
