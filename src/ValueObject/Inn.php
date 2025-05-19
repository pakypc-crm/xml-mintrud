<?php

namespace Pakypc\XMLMintrud\ValueObject;

class Inn implements \Stringable
{
    private readonly string $value;

    public function __construct (string $value)
    {
        # Убираем недопустимые символы
        $this->value = str_replace(['-', ' '], '', $value);
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
