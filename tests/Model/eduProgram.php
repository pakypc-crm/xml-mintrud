<?php
/**
 * @author roxblnfk roxblnfk@ya.ru
 * @link   https://gitlab.com/roxblnfk/crm
 */
namespace crm\models;



/**
 * Class eduProgram
 * @package crm\models
 */
class eduProgram extends baseRecord
{
    /**
     * ID программы
     */
    public ?int $id = null;

    /**
     * Название программы
     */
    public string $name;

    /**
     * Короткое название программы (подсказка)
     */
    public string $progHint;

    /**
     * Кол-во часов
     */
    public int $hours;

    /**
     * ID набора документов, выдаваемых учащемуся в конце обучения
     */
    public int $throwDocs;

    /**
     * Наименование квалификации
     */
    public string $qualificationName;

    /**
     * Область профессиональной деятельности
     */
    public int $activityArea;

    /**
     * Переподготовка/Повышение квалификации
     */
    public int $progEduType;

    /**
     * ID программ для МинТруда через запятую
     */
    public string $mintrudId;
}
