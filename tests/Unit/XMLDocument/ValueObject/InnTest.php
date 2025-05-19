<?php

namespace Tests\Unit\XMLDocument\ValueObject;

use Pakypc\XMLMintrud\XMLDocument\ValueObject\Inn;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class InnTest extends TestCase
{
    public static function getInn()
    {
        yield ["01234  567890   ", "01234567890"];
        yield ["   012 3456 78 --90 ", "01234567890"];
    }

    #[DataProvider("getInn")]
    public function testInn(string $input, string $expected): void
    {
        $inn = new Inn($input);
        $this->assertEquals($expected, (string) $inn);
    }
}
