<?php
/**
 * @author roxblnfk roxblnfk@ya.ru
 * @link   https://gitlab.com/roxblnfk/crm
 */
namespace App\Model;

use crm\models\baseRecord;
use crm\models\eduCourse;
use crm\models\eduDirection;
use crm\models\eduDistance;
use crm\models\eduTest;
use crm\models\queryOptions;
use DB;

/**
 * Базовый класс для студентов
 */
abstract class baseStudentIn extends baseRecord
{
    /**
     * ID. Если null, то запись не добавлена в БД
     */
    public ?int $id = null;
    
    /**
     * ID должности в организации
     */
    public ?int $profid = null;
    
    /**
     * ID группы сотрудников
     */
    public ?int $esquadid = null;
    
    /**
     * Системное значение статуса итогового тестирования (0,1,2)
     */
    public int $finalTest;
}
