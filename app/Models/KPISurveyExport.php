<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;


class KPISurveyExport implements FromView,ShouldAutoSize
{
    use Exportable;

    protected $data;
    protected $title;

    public function __construct($data,$title)
    {
        $this->data  = $data;
        $this->title = $title;
    }

    public function view(): View
    {
        return view('administrator.kpi-survey.export', [
            'data'  => $this->data,
            'title' => $this->title,
        ]);

    }
}