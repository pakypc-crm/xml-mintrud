<?php

declare(strict_types=1);

namespace Pakypc\XMLMintrud\Exception;

/**
 * Исключение, возникающее при ошибке валидации XML
 *
 * Используется при ошибках валидации XML-документа по XSD-схеме.
 */
final class XMLValidationException extends \Exception
{
    /**
     * @param string $message Сообщение об ошибке
     * @param int $code Код ошибки
     * @param \Throwable|null $previous Предыдущее исключение
     */
    public function __construct(
        string $message = 'XML validation failed',
        int $code = 0,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
