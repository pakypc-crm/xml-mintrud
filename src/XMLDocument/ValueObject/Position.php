<?php

namespace Pakypc\XMLMintrud\XMLDocument\ValueObject;

class Position implements \Stringable
{
    private readonly string $value;

    public function __construct (?string $value)
    {
        $this->value = trim((string) $value);
    }

    public function __toString(): string
    {
        return $this->value;
    }
}

