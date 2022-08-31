<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;

class KaryawanExport extends DefaultValueBinder implements WithCustomValueBinder, FromView
{
    use Exportable;

    protected $data;

    protected $title;
    
    public function __construct(array $data, string $title)
    {
        $this->title = $title;
        $this->data = $data;
    }

    public function bindValue(Cell $cell, $value)
    {
        if (is_numeric($value) && strlen($value) > 8) {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);

            return true;
        }

        return parent::bindValue($cell, $value);
    }

    public function view(): View
    {
        return view('administrator.karyawan.export', [
            'data'  => $this->data,
            'title' => $this->title
        ]);
    }
}