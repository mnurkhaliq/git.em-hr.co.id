<?php

namespace App\Models;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class TimesheetExport implements WithMultipleSheets
{
    use Exportable;

    protected $listData;
    protected $calculatedData;
    protected $date;

    public function __construct($listData, $calculatedData, $date)
    {
        $this->listData = $listData;
        $this->calculatedData = $calculatedData;
        $this->date = $date;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        return [
            new \App\Models\TimesheetListExport($this->listData, $this->date),
            new \App\Models\TimesheetCalculatedExport($this->calculatedData, $this->date),
        ];
    }
}
