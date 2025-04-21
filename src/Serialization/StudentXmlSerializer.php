<?php

declare(strict_types=1);

namespace App\Serialization;

use App\DTO\StudentExportData;

/**
 * Сериализатор для преобразования данных студента в XML.
 */
final class StudentXmlSerializer extends AbstractXmlSerializer
{
    /**
     * Пространство имен XML-схемы.
     */
    private const XML_NAMESPACE = 'http://www.w3.org/2001/XMLSchema';
    
    /**
     * Версия XML-схемы.
     */
    private const XML_VERSION = '1.0.8';
    
    /**
     * {@inheritdoc}
     */
    public function serialize(\DOMDocument $document, object $data): \DOMElement
    {
        if (!$data instanceof StudentExportData) {
            throw new \InvalidArgumentException('Сериализатор поддерживает только объекты StudentExportData');
        }
        
        // Создаем элемент записи реестра
        $registryRecord = $document->createElement('RegistryRecord');
        
        // Добавляем внешний идентификатор, если он есть
        if ($data->outerId !== null) {
            $registryRecord->setAttribute('outerId', $data->outerId);
        }
        
        // Создаем и добавляем элемент работника
        $workerElement = $this->createWorkerElement($document, $data);
        $registryRecord->appendChild($workerElement);
        
        // Создаем и добавляем элемент организации
        $organizationElement = $this->createOrganizationElement($document, $data);
        $registryRecord->appendChild($organizationElement);
        
        // Создаем и добавляем элемент теста
        $testElement = $this->createTestElement($document, $data);
        $registryRecord->appendChild($testElement);
        
        return $registryRecord;
    }
    
    /**
     * {@inheritdoc}
     */
    public function supports(object $data): bool
    {
        return $data instanceof StudentExportData;
    }
    
    /**
     * Создает элемент работника на основе данных студента.
     * 
     * @param \DOMDocument $doc XML-документ
     * @param StudentExportData $data Данные студента для экспорта
     * @return \DOMElement Элемент Worker
     */
    private function createWorkerElement(\DOMDocument $doc, StudentExportData $data): \DOMElement
    {
        $worker = $doc->createElement('Worker');
        
        // Добавляем обязательные элементы
        $this->appendTextElement($doc, $worker, 'LastName', $data->lastName);
        $this->appendTextElement($doc, $worker, 'FirstName', $data->firstName);
        $this->appendTextElement($doc, $worker, 'MiddleName', $data->middleName);
        
        // Добавляем СНИЛС, если он есть
        if ($data->snils !== null) {
            $this->appendTextElement($doc, $worker, 'Snils', $data->snils);
        }
        
        // Добавляем признак иностранного СНИЛС
        if ($data->isForeignSnils) {
            $this->appendTextElement($doc, $worker, 'IsForeignSnils', $data->isForeignSnils ? '1' : '0');
            
            if ($data->foreignSnils !== null) {
                $this->appendTextElement($doc, $worker, 'ForeignSnils', $data->foreignSnils);
            }
        }
        
        // Добавляем гражданство, если оно указано
        if ($data->citizenship !== null) {
            $this->appendTextElement($doc, $worker, 'Citizenship', $data->citizenship);
        }
        
        // Добавляем должность
        $this->appendTextElement($doc, $worker, 'Position', $data->position);
        
        // Добавляем данные работодателя
        $this->appendTextElement($doc, $worker, 'EmployerInn', $data->employerInn);
        $this->appendTextElement($doc, $worker, 'EmployerTitle', $data->employerTitle);
        
        return $worker;
    }
    
    /**
     * Создает элемент организации на основе данных студента.
     * 
     * @param \DOMDocument $doc XML-документ
     * @param StudentExportData $data Данные студента для экспорта
     * @return \DOMElement Элемент Organization
     */
    private function createOrganizationElement(\DOMDocument $doc, StudentExportData $data): \DOMElement
    {
        $organization = $doc->createElement('Organization');
        
        $this->appendTextElement($doc, $organization, 'Inn', $data->organizationInn);
        $this->appendTextElement($doc, $organization, 'Title', $data->organizationTitle);
        
        return $organization;
    }
    
    /**
     * Создает элемент теста на основе данных студента.
     * 
     * @param \DOMDocument $doc XML-документ
     * @param StudentExportData $data Данные студента для экспорта
     * @return \DOMElement Элемент Test
     */
    private function createTestElement(\DOMDocument $doc, StudentExportData $data): \DOMElement
    {
        $test = $doc->createElement('Test');
        
        // Установка атрибутов
        $test->setAttribute('isPassed', $data->isPassed ? '1' : '0');
        $test->setAttribute('learnProgramId', (string) $data->learnProgramId);
        
        // Добавление дочерних элементов
        $this->appendTextElement($doc, $test, 'Date', $data->examDate->format('Y-m-d'));
        $this->appendTextElement($doc, $test, 'ProtocolNumber', $data->protocolNumber);
        $this->appendTextElement($doc, $test, 'LearnProgramTitle', $data->learnProgramTitle);
        
        return $test;
    }
}
