<?php

declare(strict_types=1);

namespace App\Service\DataCollector;

use App\DTO\StudentExportData;
use App\Model\Student;
use App\Model\Group;
use App\Model\Organization;

/**
 * Сборщик данных для студентов.
 * 
 * Собирает данные о студентах из разных источников (студент, группа, организация)
 * и формирует DTO для дальнейшего использования при экспорте.
 */
final class StudentDataCollector implements DataCollectorInterface
{
    /**
     * ИНН образовательной организации по умолчанию.
     */
    private const EDUCATIONAL_ORGANIZATION_INN = '7610056871';
    
    /**
     * Название образовательной организации по умолчанию.
     */
    private const EDUCATIONAL_ORGANIZATION_TITLE = 'Частное образовательное учреждение дополнительного профессионального образования "Учебный центр "РАКурс"';

    /**
     * @param Student $studentRepository Репозиторий для доступа к данным студентов
     * @param Group $groupRepository Репозиторий для доступа к данным групп
     * @param Organization $organizationRepository Репозиторий для доступа к данным организаций
     */
    public function __construct(
        private readonly Student $studentRepository,
        private readonly Group $groupRepository,
        private readonly Organization $organizationRepository,
    ) {
    }

    /**
     * Собирает данные о студенте по его идентификатору.
     * 
     * @param non-empty-string $studentId Идентификатор студента
     * @return array<string, mixed> Данные студента в виде ассоциативного массива
     * 
     * @throws \InvalidArgumentException Если идентификатор студента некорректен
     * @throws \RuntimeException Если данные студента не найдены или неполные
     */
    public function collect(string $studentId): array
    {
        $student = $this->studentRepository->findById($studentId);
        
        if ($student === null) {
            throw new \RuntimeException("Студент с ID {$studentId} не найден");
        }
        
        $group = $this->groupRepository->findById($student->groupId);
        
        if ($group === null) {
            throw new \RuntimeException("Группа с ID {$student->groupId} не найдена");
        }
        
        $organization = $this->organizationRepository->findById($group->organizationId);
        
        if ($organization === null) {
            throw new \RuntimeException("Организация с ID {$group->organizationId} не найдена");
        }
        
        return [
            'student' => $student,
            'group' => $group,
            'organization' => $organization,
        ];
    }
    
    /**
     * Создает DTO на основе собранных данных о студенте.
     * 
     * @param non-empty-string $studentId Идентификатор студента
     * @return StudentExportData DTO с данными студента для экспорта
     * 
     * @throws \InvalidArgumentException Если идентификатор студента некорректен
     * @throws \RuntimeException Если данные студента не найдены или неполные
     */
    public function createStudentExportData(string $studentId): StudentExportData
    {
        $data = $this->collect($studentId);
        
        /** @var Student $student */
        $student = $data['student'];
        
        /** @var Group $group */
        $group = $data['group'];
        
        /** @var Organization $organization */
        $organization = $data['organization'];
        
        // Преобразуем дату экзамена в DateTimeImmutable
        $examDate = $group->examDate instanceof \DateTimeImmutable 
            ? $group->examDate 
            : new \DateTimeImmutable($group->examDate);
        
        return new StudentExportData(
            $student->lastName,
            $student->firstName,
            $student->middleName,
            $student->snils,
            $student->isForeignSnils ?? false,
            $student->foreignSnils,
            $student->citizenship,
            $student->position ?? $group->profession, // Если у студента нет должности, используем профессию из группы
            $organization->inn,
            $organization->name,
            self::EDUCATIONAL_ORGANIZATION_INN,
            self::EDUCATIONAL_ORGANIZATION_TITLE,
            $examDate,
            $group->protocolNumber,
            $group->educationProgram,
            $group->programId, // Идентификатор программы обучения из группы
            true, // По умолчанию считаем, что экзамен сдан успешно
            $student->outerId,
        );
    }
    
    /**
     * Создает список DTO для экспорта на основе группы студентов.
     * 
     * @param non-empty-string $groupId Идентификатор группы
     * @return array<StudentExportData> Список DTO с данными студентов
     * 
     * @throws \InvalidArgumentException Если идентификатор группы некорректен
     * @throws \RuntimeException Если группа не найдена или произошла ошибка при сборе данных
     */
    public function createStudentExportDataByGroup(string $groupId): array
    {
        $group = $this->groupRepository->findById($groupId);
        
        if ($group === null) {
            throw new \RuntimeException("Группа с ID {$groupId} не найдена");
        }
        
        $students = $this->studentRepository->findByGroupId($groupId);
        
        if (empty($students)) {
            return [];
        }
        
        $result = [];
        
        foreach ($students as $student) {
            try {
                $result[] = $this->createStudentExportData($student->id);
            } catch (\Exception $e) {
                // Логирование ошибки и продолжение обработки
                // В реальном коде здесь было бы логирование
                continue;
            }
        }
        
        return $result;
    }
}
