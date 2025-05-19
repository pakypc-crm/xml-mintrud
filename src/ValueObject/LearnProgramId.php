<?php

namespace Pakypc\XMLMintrud\ValueObject;

class LearnProgramId implements \Stringable
{
    private readonly string $value;

    public function __construct (string|int $value)
    {
        $this->value = preg_replace('/\\D/ui', '', (string)$value);
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
