<?php

declare(strict_types=1);

namespace Pakypc\XMLMintrud\XMLDocument\ValueObject;

class Title implements \Stringable
{
    private readonly string $value;

    public function __construct(string $value)
    {
        $this->value = \trim($value);
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
