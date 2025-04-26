<?php
/**
 * @author roxblnfk roxblnfk@ya.ru
 * @link   https://gitlab.com/roxblnfk/crm
 */
namespace crm\resurces\models;

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
 * @property $id                   null|int
 * @property int         $direction             id направления
 * @property string(32)  $absNum                Название группы по направлению (например "ПЖ-132")
 * @property string(32)  $directNum             Название группы авсолютное (например 123/2016)
 * @property null|string $startDate             Дата начала обучения
 * @property null|string $stopDate              Дата окончания обучения
 * @property null|string $examen                Дата экзамена
 * @property null|int    $period                период обучение - Срок действия удостоверения
 * @property int         $finished              Группа завершена
 * @property null|int    $comissionLeader       Председатель комиссии
 * @property int         $formaObu4             Форма обучения
 * @property int         $formaRealiz           Форма реализации
 * @property null|string $changeFormRealizDate  Дата запланированной автоматической смены $formaRealiz с 1 на 0
 * @property bool        $allowLandingStuds     Разрешить лэндинговым учащимся добавляться в эту группу
 * @property null|int    $pageAfterFinalTest    Страница, показываемая после прохождения итогового теста
 * @property null|int    $pageAfterSampleTest   Страница, показываемая после прохождения пробного теста
 * @property string(32)  $groupEnrollOrderNum   Номер приказа о зачислении
 * @property string      $groupEnrollOrderDate  Дата приказа о зачислении
 * @property string(32)  $groupDismissOrderNum  Номер приказа об отчислении
 * @property string      $groupDismissOrderDate Дата приказа об отчислении
 * @property bool        $isOutloadedFRDO       Отметка о том, что данные группы выгружены в ФРДО
 * @property null|int    $owner                 id пользователя, создавшего группу
 * @property string      $createtime            Дата создания группы
 */
class eduGroup extends baseRecord
{
}
