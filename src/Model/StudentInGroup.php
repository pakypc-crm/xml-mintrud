<?php
/**
 * @author roxblnfk roxblnfk@ya.ru
 * @link   https://gitlab.com/roxblnfk/crm
 */
namespace App\Model;

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
 */
class studentInGroup extends baseStudentIn
{
    /**
     * ID учащегося customStudent
     */
    public ?int $studid = null;
    
    /**
     * ID учебной группы eduGroup
     */
    public ?int $groupid = null;
    
    /**
     * Проверено администратором (Выставляется в true автоматически при перезаписи админом)
     */
    public bool $approved;
    
    /**
     * Кол-во оставшихся попыток для итогового тестирования
     */
    public int $finalTestTryCnt;
    
    /**
     * Попытки выданы кроном (по таймауту) или администратором
     */
    public int $finalAllowItsTime;
    
    /**
     * Аттестация
     */
    public int $attestation;
    
    /**
     * Допущен до экзамена
     */
    public bool $tolerance;
    
    /**
     * Экзамен сдан
     */
    public bool $examenated;
    
    /**
     * Если true, то выставляется собственное значение examendate
     */
    public bool $examenCustom;
    
    /**
     * Дата экзамена. Если null, то смотреть на дату экзамена учебной группы
     */
    public ?string $examendate = null;
    
    /**
     * Дата следующего экзамена
     */
    public ?string $examennext = null;
    
    /**
     * Номер удостоверения
     */
    public ?string $certNumber = null;
    
    /**
     * Номер справки
     */
    public ?string $sheetNumber = null;
    
    /**
     * № счёта
     */
    public string $moneyNum;
    
    /**
     * Дата счёта
     */
    public string $moneyDate;
    
    /**
     * Базовая стоимость
     */
    public float $moneyBase;
    
    /**
     * Скидка в % от 0 до 100
     */
    public int $moneyDisc;
    
    /**
     * Итоговая стоимость (с учётом скидки) в рублях
     */
    public float $moneySum;
    
    /**
     * Оплачено
     */
    public int $moneyFinish;
    
    /**
     * Системная дата проставки установки moneyFinish в 1
     */
    public ?string $moneyFinishTime = null;
    
    /**
     * Текущая сумма выплат (при оплате по частям)
     */
    public float $moneyCurr;
    
    /**
     * Количество внесённых частей при оплате по частям
     */
    public int $moneyCount;
    
    /**
     * Комментарий
     */
    public string $moneyNote;
    
    /**
     * № Договора для удалённого учащегося
     */
    public int $leadContractNum;
    
    /**
     * Номер почтового отправления
     */
    public ?string $postalNumber = null;
    
    /**
     * Личный номер приказа о зачислении
     */
    public string $custEnrollOrderNum;
    
    /**
     * Личная дата приказа о зачислении
     */
    public string $custEnrollOrderDate;
    
    /**
     * Личный номер приказа об отчислении
     */
    public string $custDismissOrderNum;
    
    /**
     * Личная дата приказа об отчислении
     */
    public string $custDismissOrderDate;
    
    /**
     * Количество SMS-приглашений
     */
    public int $sms_invations;
    
    /**
     * Дата последнего SMS-приглашения
     */
    public string $sms_invationLast;
    
    /**
     * Кол-во email-приглашений
     */
    public int $email_invations;
    
    /**
     * Дата последнего email приглашения
     */
    public string $email_invationLast;
    
    /**
     * Спам: Дата последней рассылки
     */
    public string $lastInactiveSpam;
    
    /**
     * Спам: Группа неактивности
     */
    public int $lastInactiveGroup;
    
    /**
     * Спам: Статус в группе неактивности
     */
    public int $lastInactiveStatus;
    
    /**
     * ID админа, создавшего запись. Если NULL, то запись создана системой в том или ином процессе
     */
    public ?int $owner = null;
    
    /**
     * Системная дата создания записи. С каких то пор редактируемая админами.
     */
    public string $createtime;
    
    /**
     * [RO] Название организации
     */
    public string $orgName;
    
    /**
     * [RO] Должность
     */
    public string $post;
    
    /**
     * [RO] Полный опыт
     */
    public string $fullExp;
    
    /**
     * [RO] Опыт работы на должности
     */
    public string $postExp;
}
