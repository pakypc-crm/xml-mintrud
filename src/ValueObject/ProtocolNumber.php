<?php

namespace Pakypc\XMLMintrud\ValueObject;

class ProtocolNumber implements \Stringable
{
    private readonly string $value;

    public function __construct (string $value)
    {
        $this->value = trim($value);
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
