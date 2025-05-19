<?php

namespace Pakypc\XMLMintrud\XMLDocument\ValueObject;

class Snils implements \Stringable
{
    private readonly string $value;

    public function __construct (?string $value)
    {
        # Убираем недопустимые символы
        $num = preg_replace('/\\D/ui', '', (string) $value);

        # Приводим к формату XXX-XXX-XXX XX
        $this->value = sprintf('%s-%s-%s %s', substr($num, 0, 3), substr($num, 3, 3), substr($num, 6, 3), substr($num, 9));
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
