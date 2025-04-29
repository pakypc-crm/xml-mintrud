<?php

declare(strict_types=1);

namespace Pakypc\XMLMintrud\Model;

/**
 * Модель данных группы студентов.
 * 
 * Представляет информацию о группе студентов в системе.
 */
final class Group
{
    /**
     * @param non-empty-string $id Уникальный идентификатор группы
     * @param non-empty-string $name Название группы
     * @param non-empty-string $educationProgram Название образовательной программы
     * @param int $programId Идентификатор программы обучения
     * @param non-empty-string $profession Название профессии/специальности
     * @param string|\DateTimeImmutable $examDate Дата экзамена
     * @param non-empty-string $protocolNumber Номер протокола
     * @param non-empty-string $organizationId Идентификатор организации-работодателя
     * @param string|null $workplace Рабочее место (если применимо)
     */
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $educationProgram,
        public readonly int $programId,
        public readonly string $profession,
        public readonly string|\DateTimeImmutable $examDate,
        public readonly string $protocolNumber,
        public readonly string $organizationId,
        public readonly ?string $workplace = null,
    ) {
    }

    /**
     * Находит группу по её идентификатору.
     * 
     * @param non-empty-string $id Идентификатор группы
     * @return self|null Найденная группа или null, если группа не найдена
     */
    public function findById(string $id): ?self
    {
        // В реальном приложении здесь был бы запрос к БД или API
        // Это заглушка для примера
        return null;
    }
    
    /**
     * Находит все группы.
     * 
     * @return array<self> Список всех групп
     */
    public function findAll(): array
    {
        // В реальном приложении здесь был бы запрос к БД или API
        // Это заглушка для примера
        return [];
    }
    
    /**
     * Создает новую группу с обновленной датой экзамена и номером протокола.
     * 
     * @param \DateTimeImmutable $examDate Новая дата экзамена
     * @param non-empty-string $protocolNumber Новый номер протокола
     * @return self Новый экземпляр группы с обновленными данными
     */
    public function withExamInfo(\DateTimeImmutable $examDate, string $protocolNumber): self
    {
        return new self(
            $this->id,
            $this->name,
            $this->educationProgram,
            $this->programId,
            $this->profession,
            $examDate,
            $protocolNumber,
            $this->organizationId,
            $this->workplace
        );
    }
}
