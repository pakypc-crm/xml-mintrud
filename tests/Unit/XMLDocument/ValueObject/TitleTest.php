<?php

namespace Tests\Unit\XMLDocument\ValueObject;

use Pakypc\XMLMintrud\XMLDocument\ValueObject\Title;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class TitleTest extends TestCase
{
    public static function getTitle()
    {
        yield ["  ООО Стальфонд", "ООО Стальфонд"];
        yield ["ООО Стальфонд    ", "ООО Стальфонд"];
    }

    #[DataProvider("getTitle")]
    public function testTitle(string $input, string $expected): void
    {
        $title = new Title($input);
        $this->assertEquals($expected, (string) $title);
    }
}
