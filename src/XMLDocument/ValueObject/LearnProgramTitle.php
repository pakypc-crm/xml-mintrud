<?php

declare(strict_types=1);

namespace Pakypc\XMLMintrud\XMLDocument\ValueObject;

class LearnProgramTitle implements \Stringable
{
    private readonly string $value;

    public function __construct(?string $value)
    {
        //Удаляем пустые символы в начале и в конце строки
        $this->value = \trim((string) $value);

        //Проверка на незаданное значение
        if ($this->value === '') {
            throw new \InvalidArgumentException('Название программы обучения не задано');
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
