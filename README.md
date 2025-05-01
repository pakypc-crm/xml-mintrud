# XML экспорт данных об обученных лицах из CRM

Этот сервис предназначен для создания выгрузки данных об обученных лицах из CRM-системы в формат XML, соответствующий стандартизированной схеме XSD (`educated_person_import_v1.0.8.xsd`).

## Описание проекта

Сервис принимает на вход сущности из CRM (студенты, учебные группы, организации и т.д.) и генерирует XML-документ в соответствии с требованиями XML-схемы. Информация берется из учебной группы и личной карточки слушателя.

## Структура проекта

```
.
├── src/
│   └── XML/
│       ├── CommonData.php         # DTO для общих данных экспорта
│       ├── XMLDocument.php        # Основной класс для генерации XML-документа
│       ├── XMLRecord.php          # DTO для хранения данных о записи учащегося
│       └── Exception/
│           └── XMLValidationException.php  # Исключение при ошибке валидации XML
├── resources/
│   └── educated_person_import_v1.0.8.xsd   # XML-схема
├── examples/                      # Примеры использования
└── tests/                         # Модульные тесты
```

## Использование

```php
// Создаём сущность документа с общими данными
$commonData = new CommonData(
    '7736207543',            // ИНН организации обучения
    'Центр обучения',        // Название организации обучения
    [1 => 1, 2 => 4, 3 => 7] // Маппинг программ (ID в CRM => ID в схеме)
);

$document = XMLDocument::create($commonData);

// Добавляем информацию об учащихся
$document->add($student1, $studentInGroup1, $position1, $group1, $program1, $organization1);
$document->add($student2, $studentInGroup2, $position2, $group2, $program2, $organization2);
$document->add($student3, $studentInGroup3, $position3, $group3, $program3, $organization3);

// Получаем XML-документ в виде строки
$xmlContent = (string) $document;

// Или сохраняем в файл
$document->saveToFile('path/to/output.xml');

// Валидируем XML-документ по XSD-схеме
if ($document->validate()) {
    echo "XML-документ соответствует схеме";
} else {
    echo "XML-документ не соответствует схеме";
}
```

## Описание классов

### CommonData

DTO для хранения общих данных экспорта:
- ИНН организации обучения
- Название организации обучения
- Маппинг ID программ обучения (для преобразования внутренних ID в ID, требуемые схемой)

### XMLRecord

DTO для хранения данных о записи учащегося, соответствующей одному элементу `RegistryRecord` в XML-документе.

### XMLDocument

Основной класс для генерации XML-документа:
- Создание документа с общими данными
- Добавление записей об учащихся
- Генерация XML-документа
- Валидация XML-документа по XSD-схеме
- Сохранение XML-документа в файл

## Структура XML

Сгенерированный XML-документ соответствует следующей структуре:

```xml
<RegistrySet>
    <RegistryRecord>
        <Worker>
            <LastName>...</LastName>
            <FirstName>...</FirstName>
            <MiddleName>...</MiddleName>
            <Snils>...</Snils>
            <Position>...</Position>
            <EmployerInn>...</EmployerInn>
            <EmployerTitle>...</EmployerTitle>
            <!-- Дополнительные поля... -->
        </Worker>
        <Organization>
            <Inn>...</Inn>
            <Title>...</Title>
        </Organization>
        <Test isPassed="true" learnProgramId="1">
            <Date>...</Date>
            <ProtocolNumber>...</ProtocolNumber>
            <LearnProgramTitle>...</LearnProgramTitle>
        </Test>
    </RegistryRecord>
    <!-- Дополнительные записи... -->
</RegistrySet>
```

## Требования

- PHP 8.1 или выше
- Расширение DOM для работы с XML
- Расширение libxml для валидации XML


## Тестирование

Или для запуска всех модульных тестов:

```bash
vendor/bin/phpunit
```
