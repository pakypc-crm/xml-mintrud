<?php

declare(strict_types=1);

namespace Pakypc\XMLMintrud\XMLDocument\ValueObject;

class ProtocolNumber implements \Stringable
{
    private readonly string $value;

    public function __construct(?string $value)
    {
        //Удаляем пустые символы в начале и в конце строки
        $this->value = \trim((string) $value);

        //Проверка на незаданное значение
        if ($this->value === '') {
            throw new \InvalidArgumentException('Номер протокола не указан');
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
