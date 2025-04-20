<?php
/**
 * @author roxblnfk roxblnfk@ya.ru
 * @date   2020.5.21
 * @link   https://gitlab.com/roxblnfk/crm
 */
namespace crm\resurces\models;

use crm\models\baseRecord;
use crm\models\baseRecord\Exception;
use crm\models\queryOptions;

/**
 * Класс для хранения информации о профессии студента
 */
class customStudProf extends baseRecord
{
    /**
     * ID записи
     */
    public ?int $id = null;
    
    /**
     * ID личной карточки
     */
    public int $studid;
    
    /**
     * ID организации
     */
    public int $orgid;
    
    /**
     * Занимаемая должность
     */
    public ?string $post = null;
    
    /**
     * Заблокировано от изменений менеджером
     */
    public bool $locked;
    
    /**
     * Название организации (подгружается из БД)
     */
    public ?string $orgName = null;
}
