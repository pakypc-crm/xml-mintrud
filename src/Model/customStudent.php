<?php
/**
 * @author roxblnfk roxblnfk@ya.ru
 * @link   https://gitlab.com/roxblnfk/crm
 */
namespace Pakypc\XMLMintrud\Model;



/**
 * Class customStudent
 * @package crm\models
 */
class customStudent extends baseStudent
{
    /**
     * Уникальный идентификатор студента
     */
    public ?int $id = null;
    
    /**
     * Флаг, что личная карточка создана учащимся, а не админом
     */
    public int $isCustom;
    
    /**
     * Заблокировать ФИО, дату рождения от изменения менеджером
     */
    public bool $lockName;
    
    /**
     * Логин пользователя
     */
    public ?string $login = null;
    
    /**
     * Пароль пользователя
     */
    public ?string $password = null;
    
    /**
     * Флаг отправленного пароля на почту
     */
    public int $passwordMailed;
    
    /**
     * Дата последнего посещения
     */
    public ?string $lastvisit = null;
    
    /**
     * Имя пользователя
     */
    public string $name1;
    
    /**
     * Фамилия пользователя
     */
    public string $name2;
    
    /**
     * Отчество пользователя
     */
    public string $name3;
    
    /**
     * Дата рождения
     */
    public ?string $birthdate = null;
    
    /**
     * Серия документа
     */
    public ?string $docSeries = null;
    
    /**
     * Номер документа
     */
    public ?string $docNumber = null;
    
    /**
     * Дата выдачи документа
     */
    public ?string $docDate = null;
    
    /**
     * Организация, выдавшая документ
     */
    public ?string $docOrg = null;
    
    /**
     * Номер СНИЛС
     */
    public ?string $snilsNumber = null;
    
    /**
     * Адрес
     */
    public ?string $addr = null;
    
    /**
     * Пол (мужской/женский)
     */
    public int $mw;
    
    /**
     * Электронная почта
     */
    public ?string $email = null;
    
    /**
     * Дополнительная электронная почта
     */
    public ?string $email2 = null;
    
    /**
     * Время последнего email-сообщения, характеризующегося как спам
     */
    public ?string $emailSpamLast = null;
    
    /**
     * Основной телефон
     */
    public ?string $phone1 = null;
    
    /**
     * Дополнительный телефон
     */
    public ?string $phone2 = null;
    
    /**
     * Образование
     */
    public int $education;
    
    /**
     * Время создания
     */
    public string $createtime;
    
    /**
     * ID владельца
     */
    public ?int $owner = null;
    
    /**
     * Примечания
     */
    public ?string $notes = null;
}
