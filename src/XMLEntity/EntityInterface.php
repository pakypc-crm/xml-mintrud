<?php

declare(strict_types=1);

namespace App\XMLEntity;

/**
 * Interface for entities that can be added to an XML document.
 */
interface EntityInterface
{
    /**
     * Returns the entity type identifier.
     * 
     * This identifier is used to determine how the entity should be processed in the XML.
     */
    public function getEntityType(): string;
    
    /**
     * Returns the entity data as an array that can be converted to XML.
     * 
     * @return array<string, mixed> Structured data representing the entity
     */
    public function toArray(): array;
}
