<?php

namespace Pakypc\XMLMintrud\Exception;


final class ValidationException extends \Exception
{

    //private readonly string $mssg;

    public function __construct (string $message)
    {
        parent::__construct($message);

        //$this->message = $message;
    }

    public function __toString(): string
    {
        return $this->message;
    }
}
