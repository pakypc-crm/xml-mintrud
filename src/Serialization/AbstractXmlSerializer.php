<?php

declare(strict_types=1);

namespace App\Serialization;

/**
 * Базовый класс для XML-сериализаторов.
 * 
 * Предоставляет общие методы для сериализации объектов в XML.
 */
abstract class AbstractXmlSerializer implements XmlSerializerInterface
{
    /**
     * Добавляет текстовый элемент в родительский элемент.
     * 
     * @param \DOMDocument $doc XML-документ
     * @param \DOMElement $parent Родительский элемент
     * @param string $name Имя нового элемента
     * @param string $value Текстовое значение элемента
     * @return \DOMElement Созданный элемент
     */
    protected function appendTextElement(\DOMDocument $doc, \DOMElement $parent, string $name, string $value): \DOMElement
    {
        $element = $doc->createElement($name);
        $element->appendChild($doc->createTextNode($value));
        $parent->appendChild($element);
        
        return $element;
    }
    
    /**
     * Добавляет элемент со значением атрибута в родительский элемент.
     * 
     * @param \DOMDocument $doc XML-документ
     * @param \DOMElement $parent Родительский элемент
     * @param string $name Имя нового элемента
     * @param string $attrName Имя атрибута
     * @param string $attrValue Значение атрибута
     * @return \DOMElement Созданный элемент
     */
    protected function appendElementWithAttribute(\DOMDocument $doc, \DOMElement $parent, string $name, string $attrName, string $attrValue): \DOMElement
    {
        $element = $doc->createElement($name);
        $element->setAttribute($attrName, $attrValue);
        $parent->appendChild($element);
        
        return $element;
    }
    
    /**
     * Добавляет массив элементов в родительский элемент.
     *
     * @param \DOMDocument $doc XML-документ
     * @param \DOMElement $parent Родительский элемент
     * @param array<string, mixed> $data Данные для добавления
     */
    protected function addArrayToElement(\DOMDocument $doc, \DOMElement $parent, array $data): void
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                // Для массивов создаем новый элемент и обрабатываем рекурсивно
                $childElement = $doc->createElement($key);
                $this->addArrayToElement($doc, $childElement, $value);
                $parent->appendChild($childElement);
            } else {
                // Для скалярных значений добавляем как элемент или атрибут
                if (is_int($key)) {
                    // Числовые ключи становятся элементами item
                    $childElement = $doc->createElement('item');
                    $childElement->setAttribute('value', (string)$value);
                    $parent->appendChild($childElement);
                } else {
                    // Строковые ключи становятся именованными элементами
                    $childElement = $doc->createElement($key);
                    $childElement->textContent = (string)$value;
                    $parent->appendChild($childElement);
                }
            }
        }
    }
}
