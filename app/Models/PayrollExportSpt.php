<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class PayrollExportSpt extends DefaultValueBinder implements FromView, WithColumnFormatting, WithCustomValueBinder
{
    use Exportable;

    protected $year;
    
    protected $month;

    protected $data;

    protected $column_acc_no;
    
    public function __construct($year, $month, array $data)
    {
        $this->year = $year;
        $this->month = $month;
        $this->data = $data;
        $this->column_acc_no = count($data[0])-4;
    }
    /**
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            $this->column_acc_no => NumberFormat::FORMAT_TEXT,
        ];
    }
    public function view(): View
    {
        return view('administrator.payroll.export-month', [
            'data' => $this->data,
            'year' => $this->year,
            'month' => $this->month
        ]);
    }

    /**
     * Bind value to a cell.
     *
     * @param Cell $cell Cell to bind value to
     * @param mixed $value Value to bind in cell
     *
     * @return bool
     */
    public function bindValue(Cell $cell, $value)
    {
        // TODO: Implement bindValue() method.
        if($cell->getColumn() == \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($this->column_acc_no)){
            $cell->setValueExplicit($value, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            return true;
        }
//        return true;
        return parent::bindValue($cell, $value);
    }
}