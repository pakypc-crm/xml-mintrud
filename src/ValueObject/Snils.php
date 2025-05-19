<?php

namespace Pakypc\XMLMintrud\ValueObject;

class Snils implements \Stringable
{
    private readonly string $value;

    public function __construct (string $value)
    {
        # Убираем недопустимые символы
        $value = str_replace(['-', ' '], '', $value);

        # Вставляем дефис после каждых 3-х символов
        $this->value = wordwrap($value, 3, '-', true);
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
