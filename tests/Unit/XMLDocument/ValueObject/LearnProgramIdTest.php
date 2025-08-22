<?php

declare(strict_types=1);

namespace Pakypc\XMLMintrud\Tests\XMLDocument\ValueObject;

use Pakypc\XMLMintrud\XMLDocument\ValueObject\LearnProgramId;
use PHPUnit\Framework\TestCase;

class LearnProgramIdTest extends TestCase
{
    // Проверяем минимальное, среднее и максимальное допустимые значения
    public function testValidValues(): void
    {
        $this->assertSame('1', (string) new LearnProgramId(1));
        $this->assertSame('15', (string) new LearnProgramId(15));
        $this->assertSame('29', (string) new LearnProgramId(29));
    }

    // Проверяем значения за границами допустимого диапазона
    public function testInvalidValues(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new LearnProgramId(0);

        $this->expectException(\InvalidArgumentException::class);
        new LearnProgramId(30);
    }

    // Проверяем преобразование в строку
    public function testToString(): void
    {
        $id = new LearnProgramId('10');
        $this->assertSame('10', (string) $id);
    }
}
