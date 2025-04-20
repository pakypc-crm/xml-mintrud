<?php

declare(strict_types=1);

namespace App\Factory;

use App\DTO\StudentExportData;

/**
 * Фабрика для создания XML-документов.
 * 
 * Отвечает за создание и формирование XML-документов на основе DTO.
 */
final class XmlDocumentFactory
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
     * Создает новый XML-документ.
     * 
     * @return \DOMDocument Новый XML-документ с настроенными параметрами
     */
    public function createDocument(): \DOMDocument
    {
        $doc = new \DOMDocument('1.0', 'utf-8');
        $doc->formatOutput = true;
        
        return $doc;
    }
    
    /**
     * Создает корневой элемент для XML-документа.
     * 
     * @param \DOMDocument $doc XML-документ
     * @return \DOMElement Корневой элемент RegistrySet
     */
    public function createRegistrySetElement(\DOMDocument $doc): \DOMElement
    {
        $registrySet = $doc->createElement('RegistrySet');
        $doc->appendChild($registrySet);
        
        return $registrySet;
    }
    
    /**
     * Создает элемент записи реестра на основе данных студента.
     * 
     * @param \DOMDocument $doc XML-документ
     * @param StudentExportData $data Данные студента для экспорта
     * @return \DOMElement Элемент RegistryRecord
     */
    public function createRegistryRecordElement(\DOMDocument $doc, StudentExportData $data): \DOMElement
    {
        // Создаем элемент записи реестра
        $registryRecord = $doc->createElement('RegistryRecord');
        
        // Добавляем внешний идентификатор, если он есть
        if ($data->outerId !== null) {
            $registryRecord->setAttribute('outerId', $data->outerId);
        }
        
        // Создаем и добавляем элемент работника
        $workerElement = $this->createWorkerElement($doc, $data);
        $registryRecord->appendChild($workerElement);
        
        // Создаем и добавляем элемент организации
        $organizationElement = $this->createOrganizationElement($doc, $data);
        $registryRecord->appendChild($organizationElement);
        
        // Создаем и добавляем элемент теста
        $testElement = $this->createTestElement($doc, $data);
        $registryRecord->appendChild($testElement);
        
        return $registryRecord;
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

    /**
     * Добавляет текстовый элемент в родительский элемент.
     * 
     * @param \DOMDocument $doc XML-документ
     * @param \DOMElement $parent Родительский элемент
     * @param string $name Имя нового элемента
     * @param string $value Текстовое значение элемента
     * @return \DOMElement Созданный элемент
     */
    private function appendTextElement(\DOMDocument $doc, \DOMElement $parent, string $name, string $value): \DOMElement
    {
        $element = $doc->createElement($name);
        $element->appendChild($doc->createTextNode($value));
        $parent->appendChild($element);
        
        return $element;
    }
    
    /**
     * Создает полный XML-документ на основе списка данных студентов.
     * 
     * @param array<StudentExportData> $dataList Список данных студентов для экспорта
     * @return \DOMDocument Сформированный XML-документ
     */
    public function createFullDocument(array $dataList): \DOMDocument
    {
        $doc = $this->createDocument();
        $registrySet = $this->createRegistrySetElement($doc);
        
        foreach ($dataList as $data) {
            $recordElement = $this->createRegistryRecordElement($doc, $data);
            $registrySet->appendChild($recordElement);
        }
        
        return $doc;
    }
}
