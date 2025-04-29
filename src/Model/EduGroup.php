<?php
/**
 * @author roxblnfk roxblnfk@ya.ru
 * @link   https://gitlab.com/roxblnfk/crm
 */
namespace Pakypc\XMLMintrud\Model;

use crm\models\baseRecord;
use crm\models\baseRecord\Exception;
use crm\models\baseRecord\statsTrait;
use crm\models\dbColumn;
use crm\models\eduBook;
use crm\models\eduDirection;
use crm\models\mrPrepod;
use crm\models\queryOptions;
use crm\models\studentInGroupProgEx;
use DB;

/**
 * Class eduGroup
 * @package crm\models
 */
class eduGroup extends baseRecord
{
    /**
     * Уникальный идентификатор группы
     */
    public ?int $id = null;
    
    /**
     * ID направления
     */
    public int $direction;
    
    /**
     * Название группы по направлению (например "ПЖ-132")
     */
    public string $absNum;
    
    /**
     * Название группы авсолютное (например 123/2016)
     */
    public string $directNum;
    
    /**
     * Дата начала обучения
     */
    public ?string $startDate = null;
    
    /**
     * Дата окончания обучения
     */
    public ?string $stopDate = null;
    
    /**
     * Дата экзамена
     */
    public ?string $examen = null;
    
    /**
     * Период обучение - Срок действия удостоверения
     */
    public ?int $period = null;
    
    /**
     * Группа завершена
     */
    public int $finished;
    
    /**
     * Председатель комиссии
     */
    public ?int $comissionLeader = null;
    
    /**
     * Форма обучения
     */
    public int $formaObu4;
    
    /**
     * Форма реализации
     */
    public int $formaRealiz;
    
    /**
     * Дата запланированной автоматической смены $formaRealiz с 1 на 0
     */
    public ?string $changeFormRealizDate = null;
    
    /**
     * Разрешить лэндинговым учащимся добавляться в эту группу
     */
    public bool $allowLandingStuds;
    
    /**
     * Страница, показываемая после прохождения итогового теста
     */
    public ?int $pageAfterFinalTest = null;
    
    /**
     * Страница, показываемая после прохождения пробного теста
     */
    public ?int $pageAfterSampleTest = null;
    
    /**
     * Номер приказа о зачислении
     */
    public string $groupEnrollOrderNum;
    
    /**
     * Дата приказа о зачислении
     */
    public string $groupEnrollOrderDate;
    
    /**
     * Номер приказа об отчислении
     */
    public string $groupDismissOrderNum;
    
    /**
     * Дата приказа об отчислении
     */
    public string $groupDismissOrderDate;
    
    /**
     * Отметка о том, что данные группы выгружены в ФРДО
     */
    public bool $isOutloadedFRDO;
    
    /**
     * ID пользователя, создавшего группу
     */
    public ?int $owner = null;
    
    /**
     * Дата создания группы
     */
    public string $createtime;
}
