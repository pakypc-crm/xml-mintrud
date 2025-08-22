<?php

namespace Tests\Unit\XMLDocument\ValueObject;

use Pakypc\XMLMintrud\XMLDocument\ValueObject\EmployerInn;
use Pakypc\XMLMintrud\XMLDocument\ValueObject\Inn;
use Pakypc\XMLMintrud\XMLDocument\ValueObject\OrganizationInn;
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
    public function testOrganizationInn(string $input, string $expected): void
    {
        $inn = new OrganizationInn($input);
        $this->assertEquals($expected, (string) $inn);
    }   #[DataProvider("getInn")]
    public function testEmployerInn(string $input, string $expected): void
    {
        $inn = new EmployerInn($input);
        $this->assertEquals($expected, (string) $inn);
    }
}
