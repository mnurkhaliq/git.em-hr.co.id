<?php

namespace App\Models;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class TimesheetListExport implements FromView
{
    use Exportable;

    protected $data;
    protected $date;

    public function __construct($data, $date)
    {
        $this->data = $data;
        $this->date = $date;
    }

    public function view(): View
    {
        return view('timesheet.export-list', [
            'data' => $this->data,
            'date' => $this->date,
        ]);
    }
}
