<?php

namespace Tests\Unit\XMLDocument\ValueObject;

use Pakypc\XMLMintrud\XMLDocument\ValueObject\EmployerTitle;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use InvalidArgumentException;

class EmployerTitleTest extends TestCase
{
    public static function getTitle()
    {
        yield ["  ООО Стальфонд", "ООО Стальфонд"];
        yield ["ООО Стальфонд    ", "ООО Стальфонд"];
    }

    #[DataProvider("getTitle")]
    public function testTitle(string $input, string $expected): void
    {
        $title = new EmployerTitle($input);
        $this->assertEquals($expected, (string) $title);
    }

    // Тест с пустым значением - должно быть исключение
    public function testEmptyValueThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Название организации не задано');

        new EmployerTitle('');
    }

    // Тест с null - должно быть исключение
    public function testNullValueThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new EmployerTitle(null);
    }
}
