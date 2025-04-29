<?php

declare(strict_types=1);

namespace Pakypc\XMLMintrud\Model;

/**
 * Модель данных студента.
 * 
 * Представляет информацию о студенте в системе.
 */
final class Student
{
    /**
     * @param non-empty-string $id Уникальный идентификатор студента
     * @param non-empty-string $firstName Имя студента
     * @param non-empty-string $lastName Фамилия студента
     * @param string $middleName Отчество студента (может быть пустым)
     * @param non-empty-string $groupId Идентификатор группы, к которой относится студент
     * @param string|null $snils СНИЛС студента (если есть)
     * @param bool $isForeignSnils Признак иностранного СНИЛС
     * @param string|null $foreignSnils Иностранный СНИЛС (если применимо)
     * @param string|null $citizenship Гражданство студента
     * @param string|null $position Должность студента (если известна)
     * @param string|null $outerId Внешний идентификатор (если есть)
     */
    public function __construct(
        public readonly string $id,
        public readonly string $firstName,
        public readonly string $lastName,
        public readonly string $middleName,
        public readonly string $groupId,
        public readonly ?string $snils = null,
        public readonly bool $isForeignSnils = false,
        public readonly ?string $foreignSnils = null,
        public readonly ?string $citizenship = null,
        public readonly ?string $position = null,
        public readonly ?string $outerId = null,
    ) {
    }

    /**
     * Находит студента по его идентификатору.
     * 
     * @param non-empty-string $id Идентификатор студента
     * @return self|null Найденный студент или null, если студент не найден
     */
    public function findById(string $id): ?self
    {
        // В реальном приложении здесь был бы запрос к БД или API
        // Это заглушка для примера
        return null;
    }

    /**
     * Находит студентов по идентификатору группы.
     * 
     * @param non-empty-string $groupId Идентификатор группы
     * @return array<self> Список студентов в группе
     */
    public function findByGroupId(string $groupId): array
    {
        // В реальном приложении здесь был бы запрос к БД или API
        // Это заглушка для примера
        return [];
    }

    /**
     * Создает нового студента с обновленной должностью.
     * 
     * @param string $position Новая должность студента
     * @return self Новый экземпляр студента с обновленной должностью
     */
    public function withPosition(string $position): self
    {
        return new self(
            $this->id,
            $this->firstName,
            $this->lastName,
            $this->middleName,
            $this->groupId,
            $this->snils,
            $this->isForeignSnils,
            $this->foreignSnils,
            $this->citizenship,
            $position,
            $this->outerId
        );
    }
}
