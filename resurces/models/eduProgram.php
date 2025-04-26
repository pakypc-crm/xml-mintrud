<?php
/**
 * @author roxblnfk roxblnfk@ya.ru
 * @link   https://gitlab.com/roxblnfk/crm
 */
namespace crm\resurces\models;

use crm\models\baseRecord;
use crm\models\baseRecord\Exception;
use crm\models\eduCourse;
use crm\models\eduDirection;
use crm\models\eduDistance;
use crm\models\eduPlan;
use crm\models\queryOptions;
use crm\models\studentInGroupProgEx;
use DB;

/**
 * Class eduProgram
 * @package crm\models
 *
 * @property $id                   null|int
 * @property $name                 string(1024) Название программы
 * @property $progHint             string(1024) Короткое название программы (подсказка)
 * @property $hours                int          Кол-во часов
 * @property $throwDocs            int          id набора документов, выдаваемых учащемуся в конце обучения
 * @property $qualificationName    string(255)  Наименование квалификации
 * @property $activityArea         int          Область профессиональной деятельности
 * @property $progEduType          int          Переподготовка/Повышение квалификации
 *
 *
 * @property $programid            int          id программы
 *
 * @property $directid             int          id направления
 *
 * @property $groupid              int          id группы
 *
 * @property $studid               int          id ЛК
 * @property $ingroupid            int          id учащегося в группе
 *
 * @property $eduCourseLinked      int  RO - если программа получена из направления через курс, то этот флаг
 *           присутствует и означает привязку данной программы к направлению и курсу
 * @property $eduQuestPackLinked   int  RO - если программа получена из направления через группу тестов, то этот флаг
 *           присутствует и означает привязку данной программы к направлению и группе тест-вопросов
 * @property $eduDirectLinked      int  RO - если программа получена из курса/группы_вопросов через направление, то
 *           этот флаг присутствует и означает привязку данной программы к направлению и курсу/группе_вопросов
 * @property $linkedProgram        bool RO Флаг привязки (для таблицы eduProgramChecker)
 *
 */
class eduProgram extends baseRecord
{
}
