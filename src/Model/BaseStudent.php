<?php
/**
 * @author roxblnfk roxblnfk@ya.ru
 * @link   https://gitlab.com/roxblnfk/crm
 */
namespace Pakypc\XMLMintrud\Model;

use crm\models\baseRecord;
use crm\models\baseRecord\Exception;
use crm\models\dbColumn;
use DB;

/**
 * Class baseStudent
 * @package crm\models
 */
class baseStudent extends baseRecord
{
    /**
     * Уникальный идентификатор студента
     */
    public ?int $id = null;
}
