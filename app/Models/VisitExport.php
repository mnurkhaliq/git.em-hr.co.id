<?php

namespace App\Models;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class VisitExport implements WithMultipleSheets
{
    use Exportable;

    protected $listData;
    protected $calculatedData;

    public function __construct($listData, $calculatedData)
    {
        $this->listData = $listData;
        $this->calculatedData = $calculatedData;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        return [
            new \App\Models\VisitListExport($this->listData),
            new \App\Models\VisitCalculatedExport($this->calculatedData),
        ];
    }
}
