<?php

namespace Tests\Unit\ValueObject;

use Pakypc\XMLMintrud\ValueObject\Citizenship;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class CitizenshipTest extends TestCase
{
    public static function getCitizenship()
    {
        yield ["Российская Федерация  ", "Российская Федерация"];
        yield ["  Российская Федерация", "Российская Федерация"];
    }

    #[DataProvider("getCitizenship")]
    public function testCitizenship(string $input, string $expected): void
    {
        $citizenship = new Citizenship($input);
        $this->assertEquals($expected, (string) $citizenship);
    }
}
