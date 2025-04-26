<?php
/**
 * @author roxblnfk roxblnfk@ya.ru
 * @link   https://gitlab.com/roxblnfk/crm
 */
namespace crm\resurces\models;

use APP;
use crm\models\baseRecord\Exception;
use crm\models\cookieSession;
use crm\models\dbColumn;
use crm\models\eduBook;
use crm\models\eduDirection;
use crm\models\landingStudent;
use crm\models\mailTemplate;
use crm\models\queryOptions;
use crm\models\smsCallStack;
use crm\models\studPhoto;
use DB;
use FORMAT;
use STUDS;
use URLify;
use function crm\models\dec2hz;

/**
 * Class customStudent
 * @package crm\models
 *
 * @property $id            null|int
 * @property $isCustom           int          Флаг, что личная карточка создана учащимся, а не админом
 * @property $lockName           bool         Заблокировать ФИО, дату рождения от изменения менеджером
 * @property $login         null|string(32)
 * @property $password      null|string(32)
 * @property $passwordMailed     int
 * @property $lastvisit     null|string
 * @property $name1              string(32)
 * @property $name2              string(32)
 * @property $name3              string(32)
 * @property $birthdate     null|string
 * @property $docSeries     null|string(4)
 * @property $docNumber     null|string(6)
 * @property $docDate       null|string
 * @property $docOrg        null|string(512)
 * @property $snilsNumber   null|string(12)
 * @property $addr          null|string(512)
 * @property $mw                 int
 * @property $email         null|string(64)
 * @property $email2        null|string(64)
 * @property $emailSpamLast null|string       время последнего email-сообщения, характеризующегося как спам
 * @property $phone1        null|string(10)
 * @property $phone2        null|string(10)
 * @property $education          int
 * @property $createtime         string
 * @property $owner         null|int
 * @property $notes         null|string(1024)
 */
class customStudent extends baseStudent
{
}
