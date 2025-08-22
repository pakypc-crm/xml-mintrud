<?php


/*
namespace Tests\Unit\XMLDocument\ValueObject;

use Pakypc\XMLMintrud\XMLDocument\ValueObject\OuterId;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class OuterIdTest extends TestCase
{
    public static function getOuterId(): \Generator
    {
        yield ["ALKdss-01234567890", "ALKdss-01234567890"];
        yield ["", ""];
    }

    #[DataProvider("getOuterId")]
    public function testOuterId(string $input, string $expected): void
    {
        $outerId = new OuterId($input);
        $this->assertEquals($expected, (string) $outerId);
    }
}
*/


declare(strict_types=1);

namespace Pakypc\XMLMintrud\Tests\XMLDocument\ValueObject;

use Pakypc\XMLMintrud\XMLDocument\ValueObject\OuterId;
use Pakypc\XMLMintrud\Exception\ValidationException;
use PHPUnit\Framework\TestCase;

class OuterIdTest extends TestCase
{
    // Тест на создание объекта с допустимыми значениями
    public function testCreatesWithValidValues(): void
    {
        $this->assertSame('', (string)new OuterId(null)); // null → пустая строка
        $this->assertSame('', (string)new OuterId(''));   // пустая строка
        $this->assertSame('abc123', (string)new OuterId('abc123')); // буквы и цифры
        $this->assertSame('abc-123', (string)new OuterId('abc-123')); // с дефисом
    }

    // Тест на исключение при недопустимых значениях
    public function testThrowsExceptionForInvalidValues(): void
    {
        $this->expectException(ValidationException::class);
        new OuterId('abc 123'); // пробел

        $this->expectException(ValidationException::class);
        new OuterId('abc@123'); // спецсимвол
    }

    // Тест на преобразование в строку
    public function testConvertsToString(): void
    {
        $id = new OuterId('test-123');
        $this->assertSame('test-123', (string)$id); // проверка __toString
    }
}
