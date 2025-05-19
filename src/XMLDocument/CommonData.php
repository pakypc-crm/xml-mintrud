<?php

declare(strict_types=1);

namespace Pakypc\XMLMintrud\XMLDocument;

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
     */
    public function __construct(
        private readonly string $organizationInn,
        private readonly string $organizationTitle,
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
}
