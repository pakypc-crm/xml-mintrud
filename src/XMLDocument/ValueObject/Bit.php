<?php

declare(strict_types=1);

namespace Pakypc\XMLMintrud\XMLDocument\ValueObject;

use Pakypc\XMLMintrud\Exception\ValidationException;

/**
 * Value Object для bit.
 *
 * Допустимые значения согласно XML-схеме:
 *  <xs:simpleType name="bit">
 *    <xs:restriction base="xs:string">
 *      <xs:enumeration value="0"/>
 *      <xs:enumeration value="1"/>
 *      <xs:enumeration value="False"/>
 *      <xs:enumeration value="True"/>
 *      <xs:enumeration value="false"/>
 *      <xs:enumeration value="true"/>
 *      <xs:enumeration value="FALSE"/>
 *      <xs:enumeration value="TRUE"/>
 *    </xs:restriction>
 *  </xs:simpleType>
 *
 */
class Bit implements \Stringable
{
    private readonly bool $value;

    public function __construct(null|string|int $value)
    {
        if ($value === null) {
            throw new ValidationException('Значение bit не задано.');
        }

        $value = \filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

        // Проверка на null
        if ($value === null) {
            throw new ValidationException('Значение bit не валидно.');
        }

        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value ? '1' : '0';
    }
}
