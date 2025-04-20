<?php

declare(strict_types=1);

namespace App\Service\DataCollector;

/**
 * Интерфейс для сборщиков данных.
 * 
 * Определяет общий интерфейс для сборщиков данных различных типов.
 * Сборщики отвечают за получение и агрегацию данных из различных источников.
 */
interface DataCollectorInterface
{
    /**
     * Собирает данные по указанному идентификатору.
     * 
     * @param non-empty-string $id Идентификатор объекта, для которого собираются данные
     * @return array<string, mixed> Собранные данные в виде ассоциативного массива
     * 
     * @throws \InvalidArgumentException Если идентификатор некорректен
     * @throws \RuntimeException Если произошла ошибка при сборе данных
     */
    public function collect(string $id): array;
}
