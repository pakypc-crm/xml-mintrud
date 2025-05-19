<?php

namespace Pakypc\XMLMintrud\ValueObject;

class Citizenship implements \Stringable
{
    private readonly string $value;

    public function __construct (string $value)
    {
        $this->value = rtrim(trim($value));
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
