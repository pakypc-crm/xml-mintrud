<?php

declare(strict_types=1);

namespace App\XMLEntity;

/**
 * Organization entity for XML document.
 */
final class Organization implements EntityInterface
{
    /**
     * @param non-empty-string $id Organization ID
     * @param non-empty-string $name Organization name
     * @param non-empty-string $code Organization code
     * @param array<string, mixed> $additionalData Additional organization data
     */
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $code,
        public readonly array $additionalData = []
    ) {
    }
    
    /**
     * {@inheritdoc}
     */
    public function getEntityType(): string
    {
        return 'organization';
    }
    
    /**
     * {@inheritdoc}
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'additionalData' => $this->additionalData,
        ];
    }
}
