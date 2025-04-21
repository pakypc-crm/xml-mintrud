<?php

declare(strict_types=1);

namespace App;

use App\DTO\StudentExportData;
use App\Exception\XMLDocumentException;
use App\Serialization\XmlSerializerRegistry;
use App\XMLEntity\EntityInterface;
use DOMDocument;
use DOMElement;
use DOMException;

/**
 * XML document class for creating and manipulating XML documents.
 * 
 * This class provides a fluent interface for creating and manipulating XML documents
 * with a focus on creating structured XML with multiple related entities.
 */
final class XMLDocument implements \Stringable
{
    private DOMDocument $document;
    private DOMElement $rootElement;
    private array $entityProcessors = [];
    private ?XmlSerializerRegistry $serializerRegistry = null;
    
    /**
     * Private constructor. Use the static create() methods instead.
     *
     * @param array<string, mixed> $commonData Common data to initialize the document
     * @throws XMLDocumentException When the XML document cannot be created
     */
    private function __construct(array $commonData = [])
    {
        try {
            $this->document = new DOMDocument('1.0', 'UTF-8');
            $this->document->formatOutput = true;
            
            // Create root element
            $this->rootElement = $this->document->createElement('root');
            $this->document->appendChild($this->rootElement);
            
            // Process and add common data to the document if any
            if (!empty($commonData)) {
                $this->processCommonData($commonData);
            }
            
            // Register entity processors - can be extended with more entity types
            $this->registerDefaultEntityProcessors();
        } catch (DOMException $e) {
            throw new XMLDocumentException('Failed to initialize XML document: ' . $e->getMessage(), 0, $e);
        }
    }
    
    /**
     * Creates a new XML document with common data.
     *
     * ```php
     *  // Create document with common organization data
     *  $document = XMLDocument::create([
     *      'organization' => [
     *          'name' => 'University XYZ',
     *          'code' => '12345'
     *      ],
     *      'programMapping' => [
     *          'program1' => 'Bachelor of Science',
     *          'program2' => 'Master of Arts'
     *      ]
     *  ]);
     * ```
     *
     * @param array<string, mixed> $commonData Common data to initialize the document
     * @return self New instance of XMLDocument
     * @throws XMLDocumentException When the XML document cannot be created
     */
    public static function create(array $commonData): self
    {
        return new self($commonData);
    }
    
    /**
     * Creates a new document for registry entries.
     * 
     * @param XmlSerializerRegistry $serializerRegistry Serializer registry
     * @return self New XMLDocument instance
     * @throws XMLDocumentException When the XML document cannot be created
     */
    public static function createRegistry(XmlSerializerRegistry $serializerRegistry): self
    {
        $document = new self();
        $document->setSerializerRegistry($serializerRegistry);
        $document->createRegistrySetElement();
        
        return $document;
    }
    
    /**
     * Adds multiple entities as a single entry to the XML document.
     *
     * This method accepts variable number of entities that implement EntityInterface.
     * All entities are processed together to create a single cohesive XML entry.
     *
     * ```php
     *  // Add related entities as a single entry
     *  $document->add($student, $organization, $position);
     * ```
     *
     * @param EntityInterface ...$entities Entities to add to the document
     * @return self For method chaining
     * @throws XMLDocumentException When an entity cannot be added
     */
    public function add(EntityInterface ...$entities): self
    {
        if (empty($entities)) {
            return $this;
        }
        
        try {
            // Create an entry element for this group of entities
            $entryElement = $this->document->createElement('entry');
            
            // Process each entity and add it to the entry element
            foreach ($entities as $entity) {
                $entityType = $entity->getEntityType();
                
                if (!isset($this->entityProcessors[$entityType])) {
                    throw new XMLDocumentException("No processor registered for entity type: {$entityType}");
                }
                
                // Call the appropriate processor for this entity type
                // But modify to add to the entry element instead of directly to root
                $processor = $this->entityProcessors[$entityType];
                $processor($entity, $this->document, $entryElement);
            }
            
            // Add the completed entry to the root element
            $this->rootElement->appendChild($entryElement);
        } catch (DOMException $e) {
            throw new XMLDocumentException("Failed to add entities: " . $e->getMessage(), 0, $e);
        }
        
        return $this;
    }
    
    /**
     * Adds a student to the document.
     * 
     * @param StudentExportData $student Student data for export
     * @return self For method chaining
     * @throws XMLDocumentException When the student cannot be added
     */
    public function addStudent(StudentExportData $student): self
    {
        try {
            if ($this->serializerRegistry === null) {
                throw new XMLDocumentException('Serializer registry is not configured');
            }
            
            $serializer = $this->serializerRegistry->findSerializer($student);
            $element = $serializer->serialize($this->document, $student);
            $this->rootElement->appendChild($element);
        } catch (\Exception $e) {
            throw new XMLDocumentException('Error adding student record: ' . $e->getMessage(), 0, $e);
        }
        
        return $this;
    }
    
    /**
     * Sets the serializer registry for the document.
     * 
     * @param XmlSerializerRegistry $serializerRegistry The serializer registry
     * @return self For method chaining
     */
    public function setSerializerRegistry(XmlSerializerRegistry $serializerRegistry): self
    {
        $this->serializerRegistry = $serializerRegistry;
        return $this;
    }
    
    /**
     * Creates a registry set element as the root element.
     * 
     * @return self For method chaining
     */
    public function createRegistrySetElement(): self
    {
        // Remove existing root element
        if ($this->rootElement->parentNode) {
            $this->document->removeChild($this->rootElement);
        }
        
        // Create a new root element for registry
        $this->rootElement = $this->document->createElement('RegistrySet');
        $this->document->appendChild($this->rootElement);
        
        return $this;
    }
    
    /**
     * Registers a custom entity processor.
     *
     * @param string $entityType The entity type to register
     * @param callable $processor The processor function
     * @return self For method chaining
     */
    public function registerEntityProcessor(string $entityType, callable $processor): self
    {
        $this->entityProcessors[$entityType] = $processor;
        return $this;
    }
    
    /**
     * Returns the XML document as a string.
     *
     * This method is automatically called when the object is used in a string context:
     * ```php
     *  echo $document; // Outputs the XML string
     * ```
     *
     * @return string The XML document as a string
     */
    public function __toString(): string
    {
        return $this->document->saveXML() ?? '';
    }
    
    /**
     * Returns the DOMDocument.
     * 
     * @return DOMDocument The XML document
     */
    public function getDOMDocument(): DOMDocument
    {
        return $this->document;
    }
    
    /**
     * Returns the root element.
     * 
     * @return DOMElement The root element
     */
    public function getRootElement(): DOMElement
    {
        return $this->rootElement;
    }
    
    /**
     * Saves the XML document to a string.
     * 
     * @return string The XML document as a string
     */
    public function saveXML(): string
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
        $this->entityProcessors['student'] = function (EntityInterface $entity, DOMDocument $doc, DOMElement $parent): void {
            $data = $entity->toArray();
            $studentElement = $doc->createElement('student');
            
            $this->addArrayToElement($studentElement, $data);
            $parent->appendChild($studentElement);
        };
        
        // Example processor for Organization entity
        $this->entityProcessors['organization'] = function (EntityInterface $entity, DOMDocument $doc, DOMElement $parent): void {
            $data = $entity->toArray();
            $orgElement = $doc->createElement('organization');
            
            $this->addArrayToElement($orgElement, $data);
            $parent->appendChild($orgElement);
        };
        
        // Example processor for Position entity
        $this->entityProcessors['position'] = function (EntityInterface $entity, DOMDocument $doc, DOMElement $parent): void {
            $data = $entity->toArray();
            $posElement = $doc->createElement('position');
            
            $this->addArrayToElement($posElement, $data);
            $parent->appendChild($posElement);
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
}
