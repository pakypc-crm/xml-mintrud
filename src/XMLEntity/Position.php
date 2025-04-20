<?php

declare(strict_types=1);

namespace App\XMLEntity;

/**
 * Position entity for XML document.
 */
final class Position implements EntityInterface
{
    /**
     * @param non-empty-string $id Position ID
     * @param non-empty-string $title Position title
     * @param string $department Department name
     * @param array<string, mixed> $additionalData Additional position data
     */
    public function __construct(
        public readonly string $id,
        public readonly string $title,
        public readonly string $department,
        public readonly array $additionalData = []
    ) {
    }
    
    /**
     * {@inheritdoc}
     */
    public function getEntityType(): string
    {
        return 'position';
    }
    
    /**
     * {@inheritdoc}
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'department' => $this->department,
            'additionalData' => $this->additionalData,
        ];
    }
}
