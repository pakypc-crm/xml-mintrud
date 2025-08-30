<?php

declare(strict_types=1);

namespace Pakypc\XMLMintrud\XMLDocument\ValueObject;

/**
 *  Value Object для ID программы обучения (1-29)
 *
 * Допустимые значения согласно XML-схеме:
 * <xs:simpleType name="learnProgram">
 *  <xs:restriction base="xs:unsignedLong">
 *      <xs:enumeration value="1"/>
 *      <xs:enumeration value="2"/>
 *      <xs:enumeration value="3"/>
 *      <xs:enumeration value="4"/>
 *      <xs:enumeration value="6"/>
 *      <xs:enumeration value="7"/>
 *      <xs:enumeration value="8"/>
 *      <xs:enumeration value="9"/>
 *      <xs:enumeration value="10"/>
 *      <xs:enumeration value="11"/>
 *      <xs:enumeration value="12"/>
 *      <xs:enumeration value="13"/>
 *      <xs:enumeration value="14"/>
 *      <xs:enumeration value="15"/>
 *      <xs:enumeration value="16"/>
 *      <xs:enumeration value="17"/>
 *      <xs:enumeration value="18"/>
 *      <xs:enumeration value="19"/>
 *      <xs:enumeration value="20"/>
 *      <xs:enumeration value="21"/>
 *      <xs:enumeration value="22"/>
 *      <xs:enumeration value="23"/>
 *      <xs:enumeration value="24"/>
 *      <xs:enumeration value="25"/>
 *      <xs:enumeration value="26"/>
 *      <xs:enumeration value="27"/>
 *      <xs:enumeration value="28"/>
 *      <xs:enumeration value="29"/>
 *     </xs:restriction>
 * </xs:simpleType>
 */
class LearnProgramId implements \Stringable
{
    private readonly int $value;

    public function __construct(string|int $value)
    {
        //Проверка значений
        $value = (int) $value;
        if ($value < 1 or $value > 29) {
            throw new \InvalidArgumentException(
                "ID программы в МинТруда должен быть от 1 до 29. ID: {$value}.",
            );
        }
        $this->value = $value;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}
