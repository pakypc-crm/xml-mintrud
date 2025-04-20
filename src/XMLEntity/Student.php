<?php

declare(strict_types=1);

namespace App\XMLEntity;

/**
 * Student entity for XML document.
 */
final class Student implements EntityInterface
{
    /**
     * @param non-empty-string $id Student ID
     * @param non-empty-string $firstName First name
     * @param non-empty-string $lastName Last name
     * @param \DateTimeImmutable $birthDate Birth date
     * @param array<string, mixed> $additionalData Additional student data
     */
    public function __construct(
        public readonly string $id,
        public readonly string $firstName,
        public readonly string $lastName,
        public readonly \DateTimeImmutable $birthDate,
        public readonly array $additionalData = []
    ) {
    }
    
    /**
     * {@inheritdoc}
     */
    public function getEntityType(): string
    {
        return 'student';
    }
    
    /**
     * {@inheritdoc}
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'birthDate' => $this->birthDate->format('Y-m-d'),
            'additionalData' => $this->additionalData,
        ];
    }
}
