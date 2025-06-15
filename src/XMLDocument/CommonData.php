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
        public readonly string $organizationInn,
        public readonly string $organizationTitle,
    ) {}
}
