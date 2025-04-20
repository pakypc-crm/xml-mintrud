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
 */
class organization extends baseRecord
{
    /**
     * Уникальный идентификатор организации
     */
    public ?int $id = null;
    
    /**
     * Данные организации проверены администратором
     */
    public bool $verifed;
    
    /**
     * Данные используются в договоре
     */
    public bool $blocked;
    
    /**
     * ID менеджера, если организация создана им
     */
    public ?int $managerid = null;
    
    /**
     * Название
     */
    public string $orgName;
    
    /**
     * Рабочее название организации (у менеджера)
     */
    public string $orgNameMgr;
    
    /**
     * Фамилия ответственного лица
     */
    public ?string $orgContName1 = null;
    
    /**
     * Имя ответственного лица
     */
    public ?string $orgContName2 = null;
    
    /**
     * Отчество ответственного лица
     */
    public ?string $orgContName3 = null;
    
    /**
     * Контактный телефон
     */
    public ?string $orgContPhone1 = null;
    
    /**
     * Email
     */
    public ?string $orgContEmail = null;
    
    /**
     * Вид деятельности организации
     */
    public ?string $orgActivity = null;
    
    /**
     * Текст в договор
     */
    public ?string $orgOrderText = null;
    
    /**
     * Расчетный счет
     */
    public ?string $orgAccNum = null;
    
    /**
     * Наименование банка
     */
    public ?string $orgBankName = null;
    
    /**
     * Корреспондентский счет
     */
    public ?string $orgCorrAcc = null;
    
    /**
     * БИК
     */
    public ?string $orgBIK = null;
    
    /**
     * ИНН организации
     */
    public ?string $orgINN = null;
    
    /**
     * КПП организации
     */
    public ?string $orgKPP = null;
    
    /**
     * Должность руководителя
     */
    public ?string $orgOwPost = null;
    
    /**
     * Фамилия руководителя
     */
    public ?string $orgOwName1 = null;
    
    /**
     * Имя руководителя
     */
    public ?string $orgOwName2 = null;
    
    /**
     * Отчество руководителя
     */
    public ?string $orgOwName3 = null;
    
    /**
     * Контактный емайл
     */
    public ?string $orgEmail = null;
    
    /**
     * Контактный телефон
     */
    public ?string $orgPhone = null;
    
    /**
     * Адрес
     */
    public ?string $orgAddr = null;
    
    /**
     * Фактический адрес
     */
    public ?string $orgAddrActual = null;
    
    /**
     * Подписка на email-уведомления о должниках
     */
    public bool $orgSubscribeDebt;
    
    /**
     * Подписка на рассылки с приглашениями на переобучение
     */
    public bool $orgSubscribeInvites;
    
    /**
     * Подписка на кастомные массовые подписки
     */
    public bool $orgSubscribeOthers;
    
    /**
     * ID пользователя, создавшего организацию
     */
    public ?int $userid = null;
    
    /**
     * Время изменения
     */
    public string $modifytime;
    
    /**
     * Время создания
     */
    public string $createtime;
    
    /**
     * [RO] Номер договора организации в группе. 
     * Появляется, если организация получена с query-параметром withGroup
     */
    public string $ogOrderNumber;
}
