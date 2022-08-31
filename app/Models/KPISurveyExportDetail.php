<?php

namespace App\Models;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class KPISurveyExportDetail implements FromView
{
    use Exportable;

    protected $data;
    protected $title;
    protected $period;
    protected $position;
    protected $minmax;

    public function __construct($data, $title, $period, $position, $minmax)
    {
        $this->data = $data;
        $this->title = $title;
        $this->period = $period;
        $this->position = $position;
        $this->minmax = $minmax;
    }

    public function view(): View
    {
        return view('administrator.kpi-survey.export-detail', [
            'data' => $this->data,
            'title' => $this->title,
            'period' => $this->period,
            'position' => $this->position,
            'minmax' => $this->minmax,
        ]);

    }
}
