<?php

namespace Pakypc\XMLMintrud\ValueObject;

class OuterId implements \Stringable
{
    private readonly string $value;

    public function __construct (string|int $value)
    {
        $this->value = (string) $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}

