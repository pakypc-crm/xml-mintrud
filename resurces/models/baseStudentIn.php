<?php
/**
 * @author roxblnfk roxblnfk@ya.ru
 * @link   https://gitlab.com/roxblnfk/crm
 */
namespace crm\resurces\models;

use crm\models\baseRecord;
use crm\models\eduCourse;
use crm\models\eduDirection;
use crm\models\eduDistance;
use crm\models\eduTest;
use crm\models\queryOptions;
use DB;

/**
 * @property $id               null|int         id Если null, то запись не добавлена в БД
 * @property $profid           null|int         id должности в организации
 * @property $esquadid         null|int         id группы сотрудников
 * @property $finalTest             int         Системное значение статуса итогового тестирования (0,1,2)
 */
abstract class baseStudentIn extends baseRecord
{
}
