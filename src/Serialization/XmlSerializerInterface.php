<?php

declare(strict_types=1);

namespace App\Serialization;

/**
 * Интерфейс для сериализаторов XML.
 * 
 * Определяет контракт для классов, которые преобразуют объекты в XML-элементы.
 */
interface XmlSerializerInterface
{
    /**
     * Преобразует объект в XML-элемент.
     * 
     * @param \DOMDocument $document XML-документ
     * @param object $data Объект для сериализации
     * @return \DOMElement Созданный XML-элемент
     */
    public function serialize(\DOMDocument $document, object $data): \DOMElement;
    
    /**
     * Проверяет, может ли сериализатор обработать данный объект.
     * 
     * @param object $data Объект для проверки
     * @return bool Результат проверки
     */
    public function supports(object $data): bool;
}
