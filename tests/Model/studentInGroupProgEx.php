<?php

namespace crm\models;



/**
 * Общие поля в базовом классе @see baseRecord
 */
class studentInGroupProgEx extends baseRecord
{
    public bool $sPr_examenCustom;
    public ?string $sPr_examenDate = null;

}
