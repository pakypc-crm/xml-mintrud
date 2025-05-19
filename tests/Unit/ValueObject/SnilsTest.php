<?php

namespace Tests\Unit\ValueObject;

use Pakypc\XMLMintrud\ValueObject\Snils;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class SnilsTest extends TestCase
{
    public static function getSnils()
    {
        yield ["01234567890", "012-345-678-90"];
        yield ["0   -1234 --5--6-7890 ", "012-345-678-90"];
    }

    #[DataProvider("getSnils")]
    public function testSnils(string $input, string $expected): void
    {
        $snils = new Snils($input);
        $this->assertEquals($expected, (string) $snils);
    }
}
