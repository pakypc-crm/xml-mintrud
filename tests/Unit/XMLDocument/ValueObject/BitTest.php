<?php

declare(strict_types=1);

namespace Pakypc\XMLMintrud\Tests\XMLDocument\ValueObject;

use Pakypc\XMLMintrud\Exception\ValidationException;
use Pakypc\XMLMintrud\XMLDocument\ValueObject\Bit;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class BitTest extends TestCase
{
    /**
     * Проверяет создание объекта с допустимыми значениями
     */
    #[DataProvider('validValuesProvider')]
    public function testCreateWithValidValues(string $value, string $expectedResult): void
    {
        $bit = new Bit($value);
        $this->assertSame($expectedResult, (string)$bit);
    }

    public static function validValuesProvider(): \Generator
    {
        yield ['0', '0'];
        yield ['1', '1'];
        yield ['False', '0'];
        yield ['True', '1'];
        yield ['false', '0'];
        yield ['true', '1'];
        yield ['FALSE', '0'];
        yield ['TRUE', '1'];
    }

    /**
     * Проверяет исключение при null значении
     */
    public function testNullValueThrowsException(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Значение bit не задано.');

        new Bit(null);
        var_dump(new Bit(null));
    }

    /**
     * Проверяет исключение при недопустимых значениях
     */
    public function testInvalidValuesThrowException(): void
    {
        $this->expectException(ValidationException::class);

        new Bit('invalid123');
    }

}
