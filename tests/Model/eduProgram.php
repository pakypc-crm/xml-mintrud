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
     * ID программы
     */
    public int $programid;
    
    /**
     * ID направления
     */
    public int $directid;
    
    /**
     * ID группы
     */
    public int $groupid;
    
    /**
     * ID ЛК
     */
    public int $studid;
    
    /**
     * ID учащегося в группе
     */
    public int $ingroupid;
    
    /**
     * RO - если программа получена из направления через курс, то этот флаг
     * присутствует и означает привязку данной программы к направлению и курсу
     */
    public int $eduCourseLinked;
    
    /**
     * RO - если программа получена из направления через группу тестов, то этот флаг
     * присутствует и означает привязку данной программы к направлению и группе тест-вопросов
     */
    public int $eduQuestPackLinked;
    
    /**
     * RO - если программа получена из курса/группы_вопросов через направление, то
     * этот флаг присутствует и означает привязку данной программы к направлению и курсу/группе_вопросов
     */
    public int $eduDirectLinked;
    
    /**
     * RO Флаг привязки (для таблицы eduProgramChecker)
     */
    public bool $linkedProgram;
}
