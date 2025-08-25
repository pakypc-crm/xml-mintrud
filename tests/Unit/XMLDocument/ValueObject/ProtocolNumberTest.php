<?php

namespace Tests\Unit\XMLDocument\ValueObject;

use Pakypc\XMLMintrud\XMLDocument\ValueObject\ProtocolNumber;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use InvalidArgumentException;

class ProtocolNumberTest extends TestCase
{
    public static function getProtocolNumber()
    {
        yield ["01234567890   ", "01234567890"];
        yield ["   01234567890", "01234567890"];
    }

    #[DataProvider("getProtocolNumber")]
    public function testProtocolNumber(string $input, string $expected): void
    {
        $protocolNumber = new ProtocolNumber($input);
        $this->assertEquals($expected, (string) $protocolNumber);
    }

    // Тест с пустым значением - должно быть исключение
    public function testEmptyValueThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Номер протокола не указан');

        new ProtocolNumber('');
    }

    // Тест с null - должно быть исключение
    public function testNullValueThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new ProtocolNumber(null);
    }
}
