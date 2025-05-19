<?php

namespace Pakypc\XMLMintrud\XMLDocument\ValueObject;

class Inn implements \Stringable
{
    private readonly string $value;

    public function __construct (?string $value)
    {
        # Убираем не цифры
        $this->value = preg_replace('/\\D/ui', '', (string) $value);
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
