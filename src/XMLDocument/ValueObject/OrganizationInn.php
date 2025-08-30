<?php

declare(strict_types=1);

namespace Pakypc\XMLMintrud\XMLDocument\ValueObject;

class OrganizationInn implements \Stringable
{
    private readonly string $value;

    public function __construct(?string $value)
    {
        # Убираем не цифры
        $this->value = \preg_replace('/\\D/ui', '', (string) $value);

        if ($this->value === '') {
            throw new \InvalidArgumentException('ИНН организации не указан');
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
