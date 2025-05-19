# Фичи-реквест для LLM по экспорту данных из CRM в XML

Необходимо создать выгрузку из CRM. У нас есть данные в 'src/Models', изучи их. На выходе нужно получить XML-документ,
на который есть XML-схема в 'resurces/educated_person_import_v1.0.8.xsd', изучи его.
Открой файлы проекта и просмотри выше перечисленные данные.

Нужно написать Builder, чтобы можно было использовать вот так:

```php

// Создаём сущность документа, куда передаются какие-то общие данные
// Например данные об учебной организации (она всегда одна)
// Также можно передать маппинг учебных программ
$document = XMLDocument::create($commonData);

// Передаём все необходимые сущности в метод add
// Один вызов команды добавляет информацию об одном учащемся (одна запись в таблицу).
$document->add($student1, $organization, $position, /* etc */);
$document->add($student2, $organization, $position, /* etc */);
$document->add($student3, $organization, $position, /* etc */);

// Через Stringable интерфейс можно получить итоговые данные
echo (string) $document;
```

Вся логика работы с XML должна быть изолирована внутри этого класса.

Сигнатура билдера:

```php
final class XMLBuilder {
    /** @var list<XMLBuilder> */
    private array $records = [];

    public static function create(): self
    {
     // todo
    }

    public function add(
        CustomStudent $stud,
        StudentInGroup $org,
        CustomStudProf $position,
        EduGroup $group,
        EduProgram $program,
        Organization $org,
    ) {
        // Создаётся DTO XMLRecord, в которую переносятся все необходимые значения из аргументов
        // Эта DTO сохраняется в XMLBuilder
        $this->records[] = $record;
    }

    public function __toString(): string {
        // Сформировать и вернуть содержимое XML документа
    }
}
```
Все должно быть выполнено просто и лаконично в едином XMLDocument.
Не пиши код. Составь дизайн-проект, построй дерево. Отвечай на русском.