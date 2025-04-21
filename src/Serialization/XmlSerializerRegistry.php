<?php

declare(strict_types=1);

namespace App\Serialization;

use App\Exception\XMLDocumentException;

/**
 * Реестр XML-сериализаторов.
 * 
 * Управляет доступом к сериализаторам для различных типов данных.
 */
final class XmlSerializerRegistry
{
    /**
     * @var array<XmlSerializerInterface>
     */
    private array $serializers = [];
    
    /**
     * @param XmlSerializerInterface ...$serializers
     */
    public function __construct(XmlSerializerInterface ...$serializers)
    {
        foreach ($serializers as $serializer) {
            $this->serializers[] = $serializer;
        }
    }
    
    /**
     * Находит подходящий сериализатор для объекта.
     * 
     * @param object $data Объект для сериализации
     * @return XmlSerializerInterface Найденный сериализатор
     * @throws XMLDocumentException Если не найден подходящий сериализатор
     */
    public function findSerializer(object $data): XmlSerializerInterface
    {
        foreach ($this->serializers as $serializer) {
            if ($serializer->supports($data)) {
                return $serializer;
            }
        }
        
        throw new XMLDocumentException(sprintf(
            'Не найден сериализатор для объекта типа %s',
            get_class($data)
        ));
    }
    
    /**
     * Добавляет сериализатор в реестр.
     * 
     * @param XmlSerializerInterface $serializer
     * @return self
     */
    public function addSerializer(XmlSerializerInterface $serializer): self
    {
        $this->serializers[] = $serializer;
        return $this;
    }
}
