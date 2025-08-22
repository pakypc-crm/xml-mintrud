<?php

declare(strict_types=1);

namespace Pakypc\XMLMintrud\Exception;

final class ValidationException extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);

    }

    public function __toString(): string
    {
        return $this->message;
    }
}
