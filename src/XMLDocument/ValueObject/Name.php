<?php

namespace Pakypc\XMLMintrud\XMLDocument\ValueObject;

class Name implements \Stringable
{
    private readonly string $value;

    public function __construct (string $value)
    {
        #Убираем пробелы в начале и в конце
        $this->value = trim($value);
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
