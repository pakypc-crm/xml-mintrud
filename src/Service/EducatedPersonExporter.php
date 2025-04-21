<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\StudentExportData;
use App\Model\Group;
use App\Service\DataCollector\StudentDataCollector;
use App\Service\Validator\XmlValidator;
use App\Serialization\XmlSerializerRegistry;
use App\XMLDocument;

/**
 * Сервис экспорта данных об обученных лицах.
 * 
 * Реализует основную логику экспорта данных студентов в XML-формат.
 */
final class EducatedPersonExporter
{
    /**
     * @param StudentDataCollector $studentDataCollector Сборщик данных студентов
     * @param XmlValidator $xmlValidator Валидатор XML-документов
     * @param Group $groupRepository Репозиторий для доступа к данным групп
     * @param XmlSerializerRegistry $serializerRegistry Реестр сериализаторов XML
     */
    public function __construct(
        private readonly StudentDataCollector $studentDataCollector,
        private readonly XmlValidator $xmlValidator,
        private readonly Group $groupRepository,
        private readonly XmlSerializerRegistry $serializerRegistry,
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
        try {
            // Создаем документ для реестра
            $document = XMLDocument::createRegistry($this->serializerRegistry);
            
            // Добавляем каждого студента
            foreach ($studentsData as $studentData) {
                $document->addStudent($studentData);
            }
            
            // Валидируем документ
            $this->xmlValidator->validate($document->getDOMDocument());
            
            // Возвращаем XML в виде строки
            return $document->saveXML();
        } catch (\Exception $e) {
            throw new \RuntimeException('Ошибка при создании или валидации XML: ' . $e->getMessage(), 0, $e);
        }
    }
}
