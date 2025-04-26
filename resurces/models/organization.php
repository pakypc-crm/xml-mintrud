<?php
/**
 * @author roxblnfk roxblnfk@ya.ru
 * @link   https://gitlab.com/roxblnfk/crm
 */
namespace crm\resurces\models;

use APP;
use crm\models\baseRecord;
use crm\models\baseRecord\Exception;
use crm\models\dbColumn;
use crm\models\landingStudent;
use crm\models\organizationHistName;
use crm\models\queryOptions;
use DB;
use FORMAT;
use USER;

/**
 * Class organization
 * @package crm\models
 *
 * Все столбцы таблицы описываются здесь
 *
 * @property $id             null|int
 * @property $verifed             bool        Данные организации проверены администратором
 * @property $blocked             bool        Данные используются в договоре
 * @property $managerid      null|int         id менеджера, если организация создана им
 * @property $orgName             string(128) Название
 * @property $orgNameMgr          string(64)  Рабочее название организации (у менеджера)
 * @property $orgContName1   null|string(64)  Фамилия ответственного лица
 * @property $orgContName2   null|string(64)  Имя ответственного лица
 * @property $orgContName3   null|string(64)  Отчество ответственного лица
 * @property $orgContPhone1  null|string(10)  Контактный телефон
 * @property $orgContEmail   null|string(64)  email
 * @property $orgActivity    null|string(256) Вид деятельности организации
 * @property $orgOrderText   null|string(512) Текст в договор
 * @property $orgAccNum      null|string(64)  р/с
 * @property $orgBankName    null|string(128) наименование банка
 * @property $orgCorrAcc     null|string(64)  к/с
 * @property $orgBIK         null|string(64)  БИК
 * @property $orgINN         null|string(32)  ИНН организации
 * @property $orgKPP         null|string(32)  КПП организации
 * @property $orgOwPost      null|string(64)  Должность руководителя
 * @property $orgOwName1     null|string(64)  Фамилия руководителя
 * @property $orgOwName2     null|string(64)  Имя руководителя
 * @property $orgOwName3     null|string(64)  Отчество руководителя
 * @property $orgEmail       null|string(64)  Контактный емайл
 * @property $orgPhone       null|string(64)  контактный телефон
 * @property $orgAddr        null|string(256) адрес
 * @property $orgAddrActual  null|string(256) фактический адрес
 * @property $orgSubscribeDebt    bool        Подписка на email-уведомления о должниках
 * @property $orgSubscribeInvites bool        Подписка на рассылки с приглашениями на переобучение
 * @property $orgSubscribeOthers  bool        Подписка на кастомные массовые подписки
 * @property $userid         null|int         id пользователя, создавшего организацию
 * @property $modifytime          string      Время изменения
 * @property $createtime          string      Время создания
 *
 *
 * @property $ogOrderNumber       string      [RO] Номер договора организации в группе. Появляется, если организация получена с query-параметром withGroup
 */
class organization extends baseRecord
{
}
