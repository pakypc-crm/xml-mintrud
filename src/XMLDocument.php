<?php

declare(strict_types=1);

namespace App;

use App\Exception\XMLDocumentException;
use App\XMLEntity\EntityInterface;

/**
 * Main class for working with XML documents.
 * 
 * This class provides a fluent interface for creating and manipulating XML documents.
 * All XML-specific logic is delegated to the XMLDocumentBuilder.
 */
final class XMLDocument implements \Stringable
{
    private XMLDocumentBuilder $builder;
    
    /**
     * Private constructor. Use the static create() method instead.
     *
     * @param XMLDocumentBuilder $builder The builder instance
     */
    private function __construct(XMLDocumentBuilder $builder)
    {
        $this->builder = $builder;
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
        $builder = new XMLDocumentBuilder($commonData);
        return new self($builder);
    }
    
    /**
     * Adds entities to the XML document.
     *
     * This method accepts variable number of entities that implement EntityInterface.
     * Each entity will be processed and added to the XML document based on its type.
     *
     * ```php
     *  // Add multiple entities
     *  $document->add($student1, $organization, $position);
     * ```
     *
     * @param EntityInterface ...$entities Entities to add to the document
     * @return self For method chaining
     * @throws XMLDocumentException When an entity cannot be added
     */
    public function add(EntityInterface ...$entities): self
    {
        foreach ($entities as $entity) {
            $this->builder->addEntity($entity);
        }
        
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
        return $this->builder->getXmlString();
    }
}
