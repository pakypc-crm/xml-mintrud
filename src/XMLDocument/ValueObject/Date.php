<?php

namespace Pakypc\XMLMintrud\XMLDocument\ValueObject;

use DateTimeImmutable;
use Pakypc\XMLMintrud\Exception\ValidationException;

class Date
{
    private readonly DateTimeImmutable|string $date;

    public function __construct(?string $date)
    {
        if ($date === null || $date === '') {
            throw new ValidationException('Дата сдачи экзамена не указана.');
        }

        try
        {
            $this->date = new DateTimeImmutable($date);
        }
        catch (\Throwable)
        {
            throw new ValidationException('Неверный формат даты в поле дата сдачи экзамена.');
        }
    }
    public function __toString(): string
    {
        return $this->date->format('Y-m-d');
    }
}
