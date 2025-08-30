<?php

namespace Tests\Unit\XMLDocument\ValueObject;

use Pakypc\XMLMintrud\XMLDocument\ValueObject\LearnProgramTitle;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use InvalidArgumentException;

class LearnProgramTitleTest extends TestCase
{
    public static function getTitle()
    {
        yield ["  Пожарная безопасность", "Пожарная безопасность"];
        yield ["Пожарная безопасность    ", "Пожарная безопасность"];
    }

    #[DataProvider("getTitle")]
    public function testTitle(string $input, string $expected): void
    {
        $title = new LearnProgramTitle($input);
        $this->assertEquals($expected, (string) $title);
    }

    // Тест с пустым значением - должно быть исключение
    public function testEmptyValueThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Название программы обучения не задано');

        new LearnProgramTitle('');
    }

    // Тест с null - должно быть исключение
    public function testNullValueThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new LearnProgramTitle(null);
    }
}

