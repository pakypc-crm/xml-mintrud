<?php

declare(strict_types=1);

namespace App;

use App\Exception\XMLDocumentException;
use App\XMLEntity\EntityInterface;
use DOMDocument;
use DOMElement;
use DOMException;

/**
 * Builder class for XML documents.
 * 
 * This class encapsulates all XML-related operations and logic,
 * isolating the XML manipulation from the rest of the application.
 */
final class XMLDocumentBuilder
{
    private DOMDocument $document;
    private DOMElement $rootElement;
    private array $entityProcessors = [];
    
    /**
     * Initializes a new XML document with common data.
     *
     * @param array<string, mixed> $commonData Common data to initialize the document
     * @throws XMLDocumentException When the XML document cannot be created
     */
    public function __construct(array $commonData)
    {
        try {
            $this->document = new DOMDocument('1.0', 'UTF-8');
            $this->document->formatOutput = true;
            
            // Create root element
            $this->rootElement = $this->document->createElement('root');
            $this->document->appendChild($this->rootElement);
            
            // Process and add common data to the document
            $this->processCommonData($commonData);
            
            // Register entity processors - can be extended with more entity types
            $this->registerDefaultEntityProcessors();
        } catch (DOMException $e) {
            throw new XMLDocumentException('Failed to initialize XML document: ' . $e->getMessage(), 0, $e);
        }
    }
    
    /**
     * Adds an entity to the XML document.
     *
     * @param EntityInterface $entity Entity to add to the document
     * @throws XMLDocumentException When the entity cannot be added
     */
    public function addEntity(EntityInterface $entity): void
    {
        $entityType = $entity->getEntityType();
        
        if (!isset($this->entityProcessors[$entityType])) {
            throw new XMLDocumentException("No processor registered for entity type: {$entityType}");
        }
        
        try {
            // Call the appropriate processor for this entity type
            $processor = $this->entityProcessors[$entityType];
            $processor($entity, $this->document, $this->rootElement);
        } catch (DOMException $e) {
            throw new XMLDocumentException("Failed to add entity of type {$entityType}: " . $e->getMessage(), 0, $e);
        }
    }
    
    /**
     * Returns the XML document as a string.
     *
     * @return string The XML document as a string
     */
    public function getXmlString(): string
    {
        return $this->document->saveXML() ?? '';
    }
    
    /**
     * Processes common data and adds it to the XML document.
     *
     * @param array<string, mixed> $commonData Common data to add to the document
     */
    private function processCommonData(array $commonData): void
    {
        // Create a commonData element
        $commonDataElement = $this->document->createElement('commonData');
        
        // Process each item in commonData
        foreach ($commonData as $key => $value) {
            $this->addArrayToElement($commonDataElement, [$key => $value]);
        }
        
        // Add commonData element to the root
        $this->rootElement->appendChild($commonDataElement);
    }
    
    /**
     * Registers default entity processors.
     */
    private function registerDefaultEntityProcessors(): void
    {
        // Register processor for each entity type
        // Each processor is a callable that takes (EntityInterface, DOMDocument, DOMElement)
        
        // Example processor for Student entity
        $this->entityProcessors['student'] = function (EntityInterface $entity, DOMDocument $doc, DOMElement $root): void {
            $data = $entity->toArray();
            $studentElement = $doc->createElement('student');
            
            $this->addArrayToElement($studentElement, $data);
            $root->appendChild($studentElement);
        };
        
        // Example processor for Organization entity
        $this->entityProcessors['organization'] = function (EntityInterface $entity, DOMDocument $doc, DOMElement $root): void {
            $data = $entity->toArray();
            $orgElement = $doc->createElement('organization');
            
            $this->addArrayToElement($orgElement, $data);
            $root->appendChild($orgElement);
        };
        
        // Example processor for Position entity
        $this->entityProcessors['position'] = function (EntityInterface $entity, DOMDocument $doc, DOMElement $root): void {
            $data = $entity->toArray();
            $posElement = $doc->createElement('position');
            
            $this->addArrayToElement($posElement, $data);
            $root->appendChild($posElement);
        };
        
        // Additional processors can be registered here or dynamically later
    }
    
    /**
     * Recursively adds an array of data to a DOM element.
     *
     * @param DOMElement $element The element to add data to
     * @param array<string, mixed> $data The data to add
     */
    private function addArrayToElement(DOMElement $element, array $data): void
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                // For arrays, create a new element and process recursively
                $childElement = $this->document->createElement($key);
                $this->addArrayToElement($childElement, $value);
                $element->appendChild($childElement);
            } else {
                // For scalar values, add as element or attribute
                if (is_int($key)) {
                    // Numeric keys become item elements
                    $childElement = $this->document->createElement('item');
                    $childElement->setAttribute('value', (string)$value);
                    $element->appendChild($childElement);
                } else {
                    // String keys become named elements
                    $childElement = $this->document->createElement($key);
                    $childElement->textContent = (string)$value;
                    $element->appendChild($childElement);
                }
            }
        }
    }
    
    /**
     * Registers a custom entity processor.
     *
     * @param string $entityType The entity type to register
     * @param callable $processor The processor function
     */
    public function registerEntityProcessor(string $entityType, callable $processor): void
    {
        $this->entityProcessors[$entityType] = $processor;
    }
}
