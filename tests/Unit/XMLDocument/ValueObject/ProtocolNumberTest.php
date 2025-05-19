<?php

namespace Tests\Unit\XMLDocument\ValueObject;

use Pakypc\XMLMintrud\XMLDocument\ValueObject\ProtocolNumber;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

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
}
