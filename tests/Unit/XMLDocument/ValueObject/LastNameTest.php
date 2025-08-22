<?php

namespace Tests\Unit\XMLDocument\ValueObject;

use Pakypc\XMLMintrud\XMLDocument\ValueObject\LastName;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class LastNameTest extends TestCase
{
    public static function getName()
    {
        yield ["Иванов  ", "Иванов"];
        yield ["  Иванов", "Иванов"];
    }

    #[DataProvider("getName")]
    public function testName(string $input, string $expected): void
    {
        $name = new LastName($input);
        $this->assertEquals($expected, (string) $name);
    }
}
