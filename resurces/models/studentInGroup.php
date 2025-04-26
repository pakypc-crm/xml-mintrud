<?php
/**
 * @author roxblnfk roxblnfk@ya.ru
 * @link   https://gitlab.com/roxblnfk/crm
 */
namespace crm\resurces\models;

use ANKETS;
use APP;
use CONF;
use crm\models\baseRecord\Exception;
use crm\models\baseRecord\statsTrait;
use crm\models\dbColumn;
use crm\models\eduBook;
use crm\models\eduCourse;
use crm\models\eduDirection;
use crm\models\mailTemplate;
use crm\models\queryOptions;
use crm\models\smsCallStack;
use crm\models\studentInGroupProgEx;
use DB;
use TBS;
use function crm\models\dec2hz;
use function crm\models\num2str;

/**
 * Общие поля в базовом классе @see baseStudentIn
 *
 * @property $studid           null|int         id учащегося customStudent
 * @property $groupid          null|int         id учебной группы eduGroup
 * @property $approved              bool        Проверено администратором (Выставляется в true автоматически при перезаписи админом)
 * @property $finalTestTryCnt       int         Кол-во оставшихся попыток для итогового тестирования
 * @property $finalAllowItsTime     int         Попытки выданы кроном (по таймауту) или администратором
 * @property $attestation           int
 * @property $tolerance             bool        Допущен до экзамена
 * @property $examenated            bool        Экзамен сдан
 * @property $examenCustom          bool        Если true, то выставляется собственное значение examendate
 * @property $examendate       null|string      Дата экзамена. Если null, то смотреть на дату экзамена учебной группы
 * @property $examennext       null|string      Дата следующего экзамена
 * @property $certNumber       null|string(128) Номер удостоверения
 * @property $sheetNumber      null|string(128) Номер справки
 * @property $moneyNum              string(128) № счёта
 * @property $moneyDate             string(128) Дата счёта
 * @property $moneyBase             float       Базовая стоимость
 * @property $moneyDisc             int         Скидка в % от 0 до 100
 * @property $moneySum              float       Итоговая стоимость (с учётом скидки) в рублях
 * @property $moneyFinish           int         Оплачено
 * @property $moneyFinishTime       null|string Системная дата проставки установки moneyFinish в 1
 * @property $moneyCurr             float       Текущая сумма выплат (при оплате по частям)
 * @property $moneyCount            int         Количество внесённых частей при оплате по частям
 * @property $moneyNote             string      Комментарий
 * @property $leadContractNum       int         № Договора для удалённого учащегося
 * @property $postalNumber     null|string(96)  Номер почтового отправления
 * @property $custEnrollOrderNum    string(32)  Личный номер приказа о зачислении
 * @property $custEnrollOrderDate   string      Личная дата приказа о зачислении
 * @property $custDismissOrderNum   string(32)  Личный номер приказа об отчислении
 * @property $custDismissOrderDate  string      Личная дата приказа об отчислении
 * @property $sms_invations         int
 * @property $sms_invationLast      string
 * @property $email_invations       int         Кол-во email-приглашений
 * @property $email_invationLast    string      Дата последнего email приглашения
 * @property $lastInactiveSpam      string      спам.. Дата последней рассылки
 * @property $lastInactiveGroup     int         спам.. Группа неактивности
 * @property $lastInactiveStatus    int         спам.. Статус в группе неактивности
 * @property $owner            null|int         id админа, создавшего запись. Если NULL, то запись создана системой в том или ином процессе
 * @property $createtime            string      Системная дата создания записи. С каких то пор редактируемая админами.
 *
 * @property $anket* string    Анкетные данные
 *
 * # С включённой опцией "withProfs" в getThisBaseQuery
 * @property $orgName   string [RO]
 * @property $post      string [RO]
 * @property $fullExp   string [RO]
 * @property $postExp   string [RO]
 */
class studentInGroup extends baseStudentIn
{
}
