<?php

declare(strict_types=1);

namespace App\XML;

/**
 * DTO для хранения общих данных экспорта
 *
 * Содержит общие данные, используемые при генерации XML-документа, такие как
 * информация об организации обучения и маппинг программ обучения.
 */
final class CommonData
{
    /**
     * @param string $organizationInn ИНН организации обучения
     * @param string $organizationTitle Название организации обучения
     * @param array<int, int> $programMapping Маппинг ID программы обучения в learnProgramId схемы
     */
    public function __construct(
        private readonly string $organizationInn,
        private readonly string $organizationTitle,
        private readonly array $programMapping = [],
    ) {
    }

    /**
     * Возвращает ИНН организации обучения
     */
    public function getOrganizationInn(): string
    {
        return $this->organizationInn;
    }

    /**
     * Возвращает название организации обучения
     */
    public function getOrganizationTitle(): string
    {
        return $this->organizationTitle;
    }

    /**
     * Возвращает ID программы из схемы по ID программы из CRM
     *
     * @param int $programId ID программы из CRM
     * @return int ID программы из схемы
     */
    public function getProgramIdByLocal(int $programId): int
    {
        return $this->programMapping[$programId] ?? 1; // По умолчанию возвращаем 1, если маппинг не задан
    }
}
