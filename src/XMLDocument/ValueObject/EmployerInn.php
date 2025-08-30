<?php

declare(strict_types=1);

namespace Pakypc\XMLMintrud\XMLDocument\ValueObject;

class EmployerInn implements \Stringable
{
    private readonly string $value;

    public function __construct(?string $value)
    {
        // Убираем не цифры
        $this->value = \preg_replace('/\\D/ui', '', (string) $value);

        //Проверка на незаданное значение
        if ($this->value === '') {
            throw new \InvalidArgumentException('ИНН работодателя не указан.');
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
