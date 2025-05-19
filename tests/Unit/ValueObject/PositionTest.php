<?php

namespace Tests\Unit\ValueObject;

use Pakypc\XMLMintrud\ValueObject\Position;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class PositionTest extends TestCase
{
    public static function getPosition()
    {
        yield ["полировщик  ", "полировщик"];
        yield ["  полировщик", "полировщик"];
    }

    #[DataProvider("getPosition")]
    public function testPosition(string $input, string $expected): void
    {
        $position = new Position($input);
        $this->assertEquals($expected, (string) $position);
    }
}
