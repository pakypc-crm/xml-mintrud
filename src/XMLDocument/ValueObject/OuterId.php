<?php

declare(strict_types=1);

namespace Pakypc\XMLMintrud\XMLDocument\ValueObject;

use Pakypc\XMLMintrud\Exception\ValidationException;

/**
 * Value Object для OuterId.
 *
 * Допустимые символы согласно XML-схеме:
 * <xs:attribute name="outerId">
 *  <xs:simpleType>
 *      <xs:restriction base="xs:string">
 *          <xs:pattern value="[A-Za-z0-9-]+"/>
 *      </xs:restriction>
 *  </xs:simpleType>
 * </xs:attribute>
 */
class OuterId implements \Stringable
{
    private readonly string $value;

    public function __construct(?string $value)
    {
        //Допустимые символы
        $pattern = '/^[A-Za-z0-9-]+$/';

        //Проверка символов
        if ($value === null || $value === '' || \preg_match($pattern, $value) == 1) {
            $this->value = (string) $value;
        } else {
            throw new ValidationException('Строка содержит недопустимые символы');
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
