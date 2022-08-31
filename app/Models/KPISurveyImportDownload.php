<?php

namespace App\Models;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class KPISurveyImportDownload implements FromView
{
    use Exportable;

    protected $data;
    protected $period;
    protected $position;
    protected $minmax;

    public function __construct($data, $period, $position, $minmax)
    {
        $this->data = $data;
        $this->period = $period;
        $this->position = $position;
        $this->minmax = $minmax;
    }

    public function view(): View
    {
        return view('karyawan.kpi-survey.import-download', [
            'data' => $this->data,
            'period' => $this->period,
            'position' => $this->position,
            'minmax' => $this->minmax,
        ]);

    }
}
