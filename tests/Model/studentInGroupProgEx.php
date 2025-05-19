<?php
namespace crm\models;

class studentInGroupProgEx extends baseRecord {
    public int $id;
    public int $programid;
    public int $studid;
    public int $ingroupid;
    public bool $sPr_tolerance; // Допущен до экзамена
    public bool $sPr_examenated; // Экзамен сдан
    public ?string $sPr_examenDate; // Дата экзамена
    public ?string $sPr_examenNext; // След. дата экзамена
    public string $sPr_protoNumber; // Номер протокола
    public string $sPr_certNumber; // Номер удостоверения
    public string $sPr_sheetNumber; // Номер справки
    public bool $sPr_examenCustom; // Своя дата экзамена
    public int $sPr_finalTest; // Системное значение статуса итогового тестирования (0,1,2)
    public int $sPr_finalTestTryCnt; // Кол-во оставшихся попыток для итогового тестирования
    public int $sPr_finalAllowItsTime; // Попытки выданы кроном (по таймауту) или администратором
    public string $sPr_rank; // Разряд
    public string $sPr_docSeries; // Серия документа
    public string $sPr_docNumber; // Номер документа
    public ?bool $sPr_docStatus; // Статус документа (Дубликат/оригинал)
    public int $sPr_confirmLoss; // Подтверждение утраты
    public int $sPr_confirmSwap; // Подтверждение обмена
    public int $sPr_confirmTerminate; // Подтверждение уничтожения
}
