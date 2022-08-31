<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;


class AttendanceExportList implements FromView,ShouldAutoSize
{
    use Exportable;

    protected $data;
    
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('attendance.export-list', [
            'data'  => $this->data['data'],
            'min'   => $this->data['min'],
            'date'  => $this->data['date'],
        ]);
        
    }
}