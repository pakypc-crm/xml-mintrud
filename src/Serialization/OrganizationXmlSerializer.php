<?php

declare(strict_types=1);

namespace App\Serialization;

use App\XMLEntity\Organization;

/**
 * Сериализатор для преобразования данных организации в XML.
 */
final class OrganizationXmlSerializer extends AbstractXmlSerializer
{
    /**
     * {@inheritdoc}
     */
    public function serialize(\DOMDocument $document, object $data): \DOMElement
    {
        if (!$data instanceof Organization) {
            throw new \InvalidArgumentException('Сериализатор поддерживает только объекты Organization');
        }
        
        $organizationElement = $document->createElement('organization');
        
        $this->appendTextElement($document, $organizationElement, 'id', $data->id);
        $this->appendTextElement($document, $organizationElement, 'name', $data->name);
        $this->appendTextElement($document, $organizationElement, 'code', $data->code);
        
        // Добавляем дополнительные данные, если они есть
        if (!empty($data->additionalData)) {
            $additionalDataElement = $document->createElement('additionalData');
            $this->addArrayToElement($document, $additionalDataElement, $data->additionalData);
            $organizationElement->appendChild($additionalDataElement);
        }
        
        return $organizationElement;
    }
    
    /**
     * {@inheritdoc}
     */
    public function supports(object $data): bool
    {
        return $data instanceof Organization;
    }
}
