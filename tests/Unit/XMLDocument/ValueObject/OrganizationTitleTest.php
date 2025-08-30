<?php

namespace Tests\Unit\XMLDocument\ValueObject;

use Pakypc\XMLMintrud\XMLDocument\ValueObject\OrganizationTitle;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use InvalidArgumentException;

class OrganizationTitleTest extends TestCase
{
    public static function getTitle()
    {
        yield ["  ЧОУ ДПО «УЦ «Ракурс»", "ЧОУ ДПО «УЦ «Ракурс»"];
        yield ["ЧОУ ДПО «УЦ «Ракурс»    ", "ЧОУ ДПО «УЦ «Ракурс»"];
    }

    #[DataProvider("getTitle")]
    public function testTitle(string $input, string $expected): void
    {
        $title = new OrganizationTitle($input);
        $this->assertEquals($expected, (string) $title);
    }

    // Тест с пустым значением - должно быть исключение
    public function testEmptyValueThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Название организации обучения не задано');

        new OrganizationTitle('');
    }

    // Тест с null - должно быть исключение
    public function testNullValueThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new OrganizationTitle(null);
    }
}

