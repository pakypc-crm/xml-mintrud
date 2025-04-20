<?php

declare(strict_types=1);

namespace App\Exception;

/**
 * Исключение, возникающее при ошибках валидации XML.
 * 
 * Используется для обработки ошибок, связанных с несоответствием
 * XML-документа XSD-схеме или другими проблемами валидации.
 */
final class XmlValidationException extends \RuntimeException
{
    /**
     * @param string $message Сообщение об ошибке
     * @param int $code Код ошибки
     * @param \Throwable|null $previous Предыдущее исключение (если есть)
     */
    public function __construct(
        string $message = 'Ошибка валидации XML',
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
