# Фичи-реквест для LLM: улучшение экспорта данных из CRM в XML

## Планы улучшения текущей реализации

1. **Расширение форматов экспорта**
   - Добавление экспорта в JSON (JSONExporter)
   - Добавление экспорта в CSV (CSVExporter)
   - Создание общего интерфейса для всех форматов (ExporterInterface)
   - Фабрика экспортеров (ExporterFactory) для выбора формата в рантайме

2. **Асинхронная обработка экспорта**
   - Реализация асинхронных задач (AsyncExportTask)
   - Очередь задач экспорта (ExportQueue)
   - Обработчики событий для уведомления о статусе (ExportEventSubscriber)
   - Хранение результатов для последующего скачивания (ExportResultStorage)

3. **Расширенные инструменты фильтрации**
   - Критерии фильтрации (FilterCriteria)
   - Конструктор фильтров (FilterBuilder)
   - Предустановленные шаблоны фильтров (FilterTemplate)
   - Преобразование запроса пользователя в фильтры (RequestFilterConverter)

4. **Пакетная обработка и оптимизация производительности**
   - Постраничная выборка данных (PaginatedDataCollector)
   - Настраиваемый размер пакета (BatchSizeConfig)
   - Мониторинг использования памяти (MemoryMonitor)
   - Возможность прерывания и возобновления больших экспортов (ResumableExport)

5. **Улучшенная валидация и мониторинг качества данных**
   - Проверка данных перед экспортом (PreExportValidator)
   - Отчеты о качестве данных (DataQualityReport)
   - Автоматическое исправление распространенных проблем (DataFixer)
   - Логирование проблем с данными (DataIssueLogger)

6. **Внедрение системы отслеживания и мониторинга**
   - Трекинг прогресса экспорта (ProgressTracker)
   - Визуализация прогресса (ProgressBar)
   - Метрики производительности экспорта (ExportMetrics)
   - Панель мониторинга для администраторов (ExportDashboard)

## Маппинг компонентов и зависимостей

| Компонент | Зависимости | Основная функциональность |
|----------|-------------|--------------------------|
| ExporterInterface | - | Общий интерфейс для всех форматов экспорта |
| XmlExporter | XmlDocumentFactory, XmlValidator | Адаптация существующего EducatedPersonExporter |
| JsonExporter | JsonEncoder, SchemaValidator | Преобразование данных студентов в JSON |
| CsvExporter | CsvEncoder, HeaderNormalizer | Экспорт данных в CSV с настраиваемыми заголовками |
| ExporterFactory | DI контейнер | Создание конкретных экспортеров по запросу |
| AsyncExportTask | ExporterInterface, ProgressTracker | Выполнение экспорта в отдельном процессе |
| ExportQueue | MessageBroker, TaskManager | Управление очередью асинхронных экспортов |
| FilterCriteria | - | Определение критериев фильтрации данных |
| FilterBuilder | FilterCriteria | Построение сложных фильтров в цепочке методов |
| PaginatedDataCollector | DataCollectorInterface, FilterCriteria | Постраничная выборка данных с фильтрацией |
| ProgressTracker | NotificationSystem | Отслеживание и оповещение о ходе экспорта |

## Маппинг данных между CRM и форматами экспорта

### Общий универсальный маппинг (для всех форматов)

| Поле в DTO | Источник данных в CRM | Описание |
|------------|------------------------|----------|
| studentId | Student: id | Внутренний идентификатор студента |
| fullName | Student: firstName + lastName + middleName | Полное имя студента |
| snils | Student: snils | СНИЛС студента |
| isForeignSnils | Student: isForeignSnils | Признак иностранного СНИЛС |
| foreignSnils | Student: foreignSnils | Иностранный СНИЛС (если применимо) |
| citizenship | Student: citizenship | Гражданство студента |
| position | Student: position или Group: profession | Должность студента |
| employerInfo | { inn: Organization: inn, name: Organization: name } | Информация о работодателе |
| educationalOrgInfo | { inn: '7610056871', name: 'Частное образовательное учреждение...' } | Информация об образовательной организации |
| examInfo | { date: Group: examDate, protocol: Group: protocolNumber, passed: true } | Информация об экзамене |
| programInfo | { id: Group: programId, name: Group: educationProgram } | Информация о программе обучения |
| outerId | Student: outerId | Внешний идентификатор студента |

### Специфический маппинг для XML (RegistryRecord)

| XML-элемент | Поле в DTO | Формат |
|-------------|------------|--------|
| RegistryRecord@outerId | outerId | Строка |
| Worker/LastName | lastName | Строка |
| Worker/FirstName | firstName | Строка |
| Worker/MiddleName | middleName | Строка |
| Worker/Snils | snils | Строка |
| Worker/IsForeignSnils | isForeignSnils | 0/1 |
| Worker/ForeignSnils | foreignSnils | Строка |
| Worker/Citizenship | citizenship | Строка |
| Worker/Position | position | Строка |
| Worker/EmployerInn | employerInfo.inn | Строка |
| Worker/EmployerTitle | employerInfo.name | Строка |
| Organization/Inn | educationalOrgInfo.inn | Строка |
| Organization/Title | educationalOrgInfo.name | Строка |
| Test@isPassed | examInfo.passed | 0/1 |
| Test@learnProgramId | programInfo.id | Число |
| Test/Date | examInfo.date | YYYY-MM-DD |
| Test/ProtocolNumber | examInfo.protocol | Строка |
| Test/LearnProgramTitle | programInfo.name | Строка |

### Специфический маппинг для JSON

```json
{
  "students": [
    {
      "id": "studentId",
      "personalInfo": {
        "fullName": {
          "last": "lastName",
          "first": "firstName",
          "middle": "middleName"
        },
        "identifiers": {
          "snils": "snils",
          "isForeign": "isForeignSnils",
          "foreignId": "foreignSnils"
        },
        "citizenship": "citizenship",
        "position": "position"
      },
      "employer": {
        "inn": "employerInfo.inn",
        "name": "employerInfo.name"
      },
      "educationalOrg": {
        "inn": "educationalOrgInfo.inn",
        "name": "educationalOrgInfo.name"
      },
      "education": {
        "program": {
          "id": "programInfo.id",
          "name": "programInfo.name"
        },
        "examination": {
          "date": "examInfo.date",
          "protocol": "examInfo.protocol",
          "result": "examInfo.passed"
        }
      },
      "externalId": "outerId"
    }
  ],
  "metadata": {
    "exportDate": "текущая дата/время",
    "totalCount": "количество записей",
    "version": "версия схемы JSON"
  }
}
```

### Специфический маппинг для CSV

| Заголовок CSV | Поле в DTO | Формат |
|---------------|------------|--------|
| ID | studentId | Строка |
| Last Name | lastName | Строка |
| First Name | firstName | Строка |
| Middle Name | middleName | Строка |
| SNILS | snils | Строка |
| Foreign SNILS | foreignSnils | Строка |
| Citizenship | citizenship | Строка |
| Position | position | Строка |
| Employer INN | employerInfo.inn | Строка |
| Employer Name | employerInfo.name | Строка |
| Educational Org. INN | educationalOrgInfo.inn | Строка |
| Educational Org. Name | educationalOrgInfo.name | Строка (сокращенная) |
| Program ID | programInfo.id | Число |
| Program Name | programInfo.name | Строка |
| Exam Date | examInfo.date | DD.MM.YYYY |
| Protocol Number | examInfo.protocol | Строка |
| Exam Result | examInfo.passed | "Passed"/"Failed" |
| External ID | outerId | Строка |

## Важные технические требования

1. **Производительность и масштабирование**
   - Память: оптимизация потребления для больших наборов данных
   - Время: возможность выполнения длительных экспортов без таймаутов
   - Многопоточность: подготовка к параллельной обработке экспортов

2. **Отказоустойчивость и восстановление**
   - Сохранение состояния экспорта для возможности восстановления
   - Обработка частичных результатов при сбоях
   - Автоматические повторные попытки для транзиентных ошибок

3. **Безопасность**
   - Контроль доступа на уровне данных и функций
   - Шифрование экспортированных файлов при необходимости
   - Аудит и логирование действий с экспортами

4. **Расширяемость**
   - Плагинная система для добавления новых форматов экспорта
   - Поддержка версионирования схем данных
   - Инверсия зависимостей для всех компонентов

5. **Удобство использования**
   - API для интеграции с внешними системами
   - Возможность планирования регулярных экспортов
   - Уведомления пользователей о готовности экспорта через различные каналы

## Сценарии использования

1. **Ежемесячный экспорт всех данных в XML**
   - Планирование экспорта через административный интерфейс
   - Асинхронное выполнение в нерабочее время
   - Уведомление администратора о готовности и результатах

2. **Экспорт выбранных групп с фильтрацией по критериям**
   - Выбор групп через интерфейс
   - Настройка фильтров (даты, программы, статусы)
   - Выбор формата экспорта (XML, JSON, CSV)
   - Получение файла или ссылки на скачивание

3. **Непрерывная интеграция с внешними системами**
   - Предоставление API для запроса экспорта
   - Обмен данными через защищенные каналы
   - Автоматическое обновление изменившихся данных

4. **Диагностика и исправление проблем с данными**
   - Предварительная проверка качества данных перед экспортом
   - Отчет о потенциальных проблемах
   - Возможность автоматического исправления типичных ошибок

## Технический план внедрения

1. **Этап 1: Рефакторинг текущего кода**
   - Выделение ExporterInterface и адаптация EducatedPersonExporter
   - Улучшение работы с хранилищем данных для поддержки пагинации
   - Оптимизация потребления памяти при обработке больших объемов данных

2. **Этап 2: Расширение форматов и фильтрация**
   - Реализация JsonExporter и CsvExporter
   - Создание системы фильтров и построителя запросов
   - Разработка ExporterFactory для выбора формата

3. **Этап 3: Асинхронная обработка**
   - Внедрение очереди задач и системы уведомлений
   - Разработка механизмов отслеживания прогресса
   - Реализация возможности прерывания и возобновления экспортов

4. **Этап 4: Управление качеством данных**
   - Создание системы валидации и отчетов о качестве
   - Разработка автоматических исправлений распространенных проблем
   - Внедрение инструментов для мониторинга и анализа данных

5. **Этап 5: Интеграции и масштабирование**
   - Разработка API для внешних систем
   - Создание планировщика регулярных экспортов
   - Подготовка к многопоточной обработке и горизонтальному масштабированию
