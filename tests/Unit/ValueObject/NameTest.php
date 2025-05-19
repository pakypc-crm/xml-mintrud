<?php

namespace Tests\Unit\ValueObject;

use Pakypc\XMLMintrud\ValueObject\Name;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class NameTest extends TestCase
{
    public static function getName()
    {
        yield ["Иванов  ", "Иванов"];
        yield ["  Иванов", "Иванов"];
    }

    #[DataProvider("getName")]
    public function testName(string $input, string $expected): void
    {
        $name = new Name($input);
        $this->assertEquals($expected, (string) $name);
    }
}
