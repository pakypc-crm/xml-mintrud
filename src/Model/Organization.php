<?php

declare(strict_types=1);

namespace App\Model;

/**
 * Модель данных организации.
 * 
 * Представляет информацию об организации в системе.
 */
final class Organization
{
    /**
     * @param non-empty-string $id Уникальный идентификатор организации
     * @param non-empty-string $name Название организации
     * @param non-empty-string $inn ИНН организации
     */
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $inn,
    ) {
    }

    /**
     * Находит организацию по её идентификатору.
     * 
     * @param non-empty-string $id Идентификатор организации
     * @return self|null Найденная организация или null, если организация не найдена
     */
    public function findById(string $id): ?self
    {
        // В реальном приложении здесь был бы запрос к БД или API
        // Это заглушка для примера
        return null;
    }
    
    /**
     * Находит организацию по её ИНН.
     * 
     * @param non-empty-string $inn ИНН организации
     * @return self|null Найденная организация или null, если организация не найдена
     */
    public function findByInn(string $inn): ?self
    {
        // В реальном приложении здесь был бы запрос к БД или API
        // Это заглушка для примера
        return null;
    }
}
