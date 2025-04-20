<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\StudentExportData;
use App\Factory\XmlDocumentFactory;
use App\Model\Group;
use App\Service\DataCollector\StudentDataCollector;
use App\Service\Validator\XmlValidator;

/**
 * Сервис экспорта данных об обученных лицах.
 * 
 * Реализует основную логику экспорта данных студентов в XML-формат.
 */
final class EducatedPersonExporter
{
    /**
     * @param StudentDataCollector $studentDataCollector Сборщик данных студентов
     * @param XmlDocumentFactory $xmlFactory Фабрика для создания XML-документов
     * @param XmlValidator $xmlValidator Валидатор XML-документов
     * @param Group $groupRepository Репозиторий для доступа к данным групп
     */
    public function __construct(
        private readonly StudentDataCollector $studentDataCollector,
        private readonly XmlDocumentFactory $xmlFactory,
        private readonly XmlValidator $xmlValidator,
        private readonly Group $groupRepository,
    ) {
    }

    /**
     * Экспортирует данные всех студентов в XML-формат.
     * 
     * @return string Сформированный XML-документ в виде строки
     * 
     * @throws \RuntimeException Если произошла ошибка при экспорте
     */
    public function exportAll(): string
    {
        // Получаем все группы
        $groups = $this->groupRepository->findAll();
        
        if (empty($groups)) {
            throw new \RuntimeException('Не найдено ни одной группы для экспорта');
        }
        
        // Собираем данные студентов из всех групп
        $studentsData = $this->collectAllStudentsData($groups);
        
        if (empty($studentsData)) {
            throw new \RuntimeException('Не найдено ни одного студента для экспорта');
        }
        
        // Создаем и валидируем XML-документ
        return $this->createAndValidateXml($studentsData);
    }
    
    /**
     * Экспортирует данные студентов указанной группы в XML-формат.
     * 
     * @param non-empty-string $groupId Идентификатор группы
     * @return string Сформированный XML-документ в виде строки
     * 
     * @throws \InvalidArgumentException Если идентификатор группы некорректен
     * @throws \RuntimeException Если группа не найдена или произошла ошибка при экспорте
     */
    public function exportByGroup(string $groupId): string
    {
        // Получаем данные студентов в группе
        $studentsData = $this->studentDataCollector->createStudentExportDataByGroup($groupId);
        
        if (empty($studentsData)) {
            throw new \RuntimeException("Не найдено ни одного студента в группе с ID {$groupId}");
        }
        
        // Создаем и валидируем XML-документ
        return $this->createAndValidateXml($studentsData);
    }
    
    /**
     * Экспортирует данные указанного студента в XML-формат.
     * 
     * @param non-empty-string $studentId Идентификатор студента
     * @return string Сформированный XML-документ в виде строки
     * 
     * @throws \InvalidArgumentException Если идентификатор студента некорректен
     * @throws \RuntimeException Если студент не найден или произошла ошибка при экспорте
     */
    public function exportByStudent(string $studentId): string
    {
        // Получаем данные одного студента
        $studentData = $this->studentDataCollector->createStudentExportData($studentId);
        
        // Создаем и валидируем XML-документ
        return $this->createAndValidateXml([$studentData]);
    }
    
    /**
     * Собирает данные всех студентов из указанных групп.
     * 
     * @param array<Group> $groups Список групп
     * @return array<StudentExportData> Список данных студентов
     */
    private function collectAllStudentsData(array $groups): array
    {
        $studentsData = [];
        
        foreach ($groups as $group) {
            try {
                // Получаем данные студентов в текущей группе
                $groupStudentsData = $this->studentDataCollector->createStudentExportDataByGroup($group->id);
                
                // Добавляем данные студентов к общему списку
                $studentsData = [...$studentsData, ...$groupStudentsData];
            } catch (\Exception $e) {
                // Логирование ошибки и продолжение обработки других групп
                // В реальном коде здесь было бы логирование
                continue;
            }
        }
        
        return $studentsData;
    }
    
    /**
     * Создает и валидирует XML-документ на основе данных студентов.
     * 
     * @param array<StudentExportData> $studentsData Список данных студентов
     * @return string Сформированный XML-документ в виде строки
     * 
     * @throws \RuntimeException Если произошла ошибка при создании или валидации XML
     */
    private function createAndValidateXml(array $studentsData): string
    {
        // Создаем XML-документ
        $document = $this->xmlFactory->createFullDocument($studentsData);
        
        // Валидируем документ
        $this->xmlValidator->validate($document);
        
        // Преобразуем документ в строку
        return $document->saveXML();
    }
}
