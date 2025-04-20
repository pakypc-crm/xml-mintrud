<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exception\XmlValidationException;
use App\Service\EducatedPersonExporter;

/**
 * Контроллер для экспорта данных.
 * 
 * Обрабатывает запросы на экспорт данных и возвращает результат в XML-формате.
 */
final class ExportController
{
    /**
     * Имя файла экспорта по умолчанию.
     */
    private const DEFAULT_FILENAME = 'educated_persons_export.xml';

    /**
     * @param EducatedPersonExporter $exporter Сервис экспорта данных
     */
    public function __construct(
        private readonly EducatedPersonExporter $exporter,
    ) {
    }

    /**
     * Экспортирует данные всех студентов.
     * 
     * @param string|null $filename Имя файла для экспорта (если не указано, используется значение по умолчанию)
     * @return void
     * 
     * @throws \RuntimeException Если произошла ошибка при экспорте
     */
    public function exportAllAction(?string $filename = null): void
    {
        try {
            // Получаем XML-документ со всеми студентами
            $xml = $this->exporter->exportAll();
            
            // Отправляем XML клиенту
            $this->sendXmlResponse($xml, $filename ?? self::DEFAULT_FILENAME);
        } catch (XmlValidationException $e) {
            // В случае ошибок валидации выводим информативное сообщение
            $this->sendErrorResponse('Ошибка валидации XML: ' . $e->getMessage());
        } catch (\Throwable $e) {
            // В случае других ошибок выводим общее сообщение
            $this->sendErrorResponse('Произошла ошибка при экспорте данных: ' . $e->getMessage());
        }
    }
    
    /**
     * Экспортирует данные студентов указанной группы.
     * 
     * @param non-empty-string $groupId Идентификатор группы
     * @param string|null $filename Имя файла для экспорта (если не указано, используется значение по умолчанию)
     * @return void
     * 
     * @throws \InvalidArgumentException Если идентификатор группы некорректен
     * @throws \RuntimeException Если произошла ошибка при экспорте
     */
    public function exportByGroupAction(string $groupId, ?string $filename = null): void
    {
        try {
            // Проверяем идентификатор группы
            if (empty($groupId)) {
                throw new \InvalidArgumentException('Идентификатор группы не может быть пустым');
            }
            
            // Получаем XML-документ для указанной группы
            $xml = $this->exporter->exportByGroup($groupId);
            
            // Формируем имя файла, если не указано
            $exportFilename = $filename ?? $this->generateGroupFilename($groupId);
            
            // Отправляем XML клиенту
            $this->sendXmlResponse($xml, $exportFilename);
        } catch (XmlValidationException $e) {
            // В случае ошибок валидации выводим информативное сообщение
            $this->sendErrorResponse('Ошибка валидации XML: ' . $e->getMessage());
        } catch (\Throwable $e) {
            // В случае других ошибок выводим общее сообщение
            $this->sendErrorResponse('Произошла ошибка при экспорте данных группы: ' . $e->getMessage());
        }
    }
    
    /**
     * Экспортирует данные указанного студента.
     * 
     * @param non-empty-string $studentId Идентификатор студента
     * @param string|null $filename Имя файла для экспорта (если не указано, используется значение по умолчанию)
     * @return void
     * 
     * @throws \InvalidArgumentException Если идентификатор студента некорректен
     * @throws \RuntimeException Если произошла ошибка при экспорте
     */
    public function exportByStudentAction(string $studentId, ?string $filename = null): void
    {
        try {
            // Проверяем идентификатор студента
            if (empty($studentId)) {
                throw new \InvalidArgumentException('Идентификатор студента не может быть пустым');
            }
            
            // Получаем XML-документ для указанного студента
            $xml = $this->exporter->exportByStudent($studentId);
            
            // Формируем имя файла, если не указано
            $exportFilename = $filename ?? $this->generateStudentFilename($studentId);
            
            // Отправляем XML клиенту
            $this->sendXmlResponse($xml, $exportFilename);
        } catch (XmlValidationException $e) {
            // В случае ошибок валидации выводим информативное сообщение
            $this->sendErrorResponse('Ошибка валидации XML: ' . $e->getMessage());
        } catch (\Throwable $e) {
            // В случае других ошибок выводим общее сообщение
            $this->sendErrorResponse('Произошла ошибка при экспорте данных студента: ' . $e->getMessage());
        }
    }
    
    /**
     * Отправляет XML-ответ клиенту.
     * 
     * @param string $xml XML-документ в виде строки
     * @param string $filename Имя файла для скачивания
     * @return void
     */
    private function sendXmlResponse(string $xml, string $filename): void
    {
        // Устанавливаем заголовки для скачивания файла
        header('Content-Type: application/xml; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . strlen($xml));
        
        // Отправляем XML-документ
        echo $xml;
        exit;
    }
    
    /**
     * Отправляет сообщение об ошибке клиенту.
     * 
     * @param string $message Сообщение об ошибке
     * @return void
     */
    private function sendErrorResponse(string $message): void
    {
        // Устанавливаем код ответа
        http_response_code(500);
        
        // Устанавливаем заголовок
        header('Content-Type: text/html; charset=utf-8');
        
        // Отправляем сообщение об ошибке
        echo '<h1>Ошибка экспорта</h1>';
        echo '<p>' . htmlspecialchars($message) . '</p>';
        exit;
    }
    
    /**
     * Генерирует имя файла для экспорта группы.
     * 
     * @param string $groupId Идентификатор группы
     * @return string Сгенерированное имя файла
     */
    private function generateGroupFilename(string $groupId): string
    {
        return 'group_' . preg_replace('/[^a-zA-Z0-9-]/', '_', $groupId) . '_' . date('Y-m-d') . '.xml';
    }
    
    /**
     * Генерирует имя файла для экспорта студента.
     * 
     * @param string $studentId Идентификатор студента
     * @return string Сгенерированное имя файла
     */
    private function generateStudentFilename(string $studentId): string
    {
        return 'student_' . preg_replace('/[^a-zA-Z0-9-]/', '_', $studentId) . '_' . date('Y-m-d') . '.xml';
    }
}
