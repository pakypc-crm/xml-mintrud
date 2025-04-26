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
 * @property $id          null|int              id
 * @property $studid           int              id личной карточки
 * @property $orgid            int              id организации
 * @property $post        null|string(128)      Занимаемая должность
 * @property $locked           bool             Заблокировано от изменений менеджером
 *
 * Подгружается из БД
 * @property $orgName     null|string           Название организации
 */
class customStudProf extends baseRecord
{
}
