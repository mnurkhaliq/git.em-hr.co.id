<?php

namespace App\Imports;

use App\Models\CareerHistory;
use App\Models\Cabang;
use App\Models\StructureOrganizationCustom;
use App\Models\OrganisasiDivision;
use App\Models\OrganisasiPosition;
use App\Models\OrganisasiTitle;
use App\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CareerHistoryImport implements ToModel, WithHeadingRow{
    private $rowIndex = 7;
    public $succesfull = 0;
    public $failed = array();

    public function __construct()
    {
    }

    public function model(array $row){
        ++$this->rowIndex;
        $user = User::where('nik',$row['nik'])->first();
        if(!$user){
            array_push($this->failed,$this->rowIndex . " (NIK not found)");
            return;
        }
        if ($user->non_active_date && $user->non_active_date <= \Carbon\Carbon::now()) {
            array_push($this->failed,$this->rowIndex . " (Employee already non active)");
            return;
        }
        if ($user->is_exit) {
            array_push($this->failed,$this->rowIndex . " (Employee already approved exit interview)");
            return;
        }
        $userId = $user->id;
        $code = explode("-", $row['position']);
        if(count($code) == 0) {
            array_push($this->failed,$this->rowIndex . " (Position cant be blank)");
            return;
        }
        $posCode = OrganisasiPosition::where('code', trim($code[0]))->orWhere('name', trim($code[0]))->first();
        if (!isset($code[1]))
            $divCode = null;
        else
            $divCode = OrganisasiDivision::where('code', trim($code[1]))->orWhere('name', trim($code[1]))->first();
        if (!isset($code[2]))
            $titCode = null;
        else
            $titCode = OrganisasiTitle::where('code', trim($code[2]))->orWhere('name', trim($code[2]))->first();
        
        $checkPosition = null;
        if (isset($posCode)) {
            $checkPosition = StructureOrganizationCustom::where('organisasi_position_id', $posCode->id);
            if (isset($divCode)) {
                $checkPosition = $checkPosition->where('organisasi_division_id', $divCode->id);
                if (isset($titCode)) {
                    $checkPosition = $checkPosition->where('organisasi_title_id', $titCode->id);
                } else {
                    $checkPosition = $checkPosition->whereNull('organisasi_title_id');
                }
            } else {
                $checkPosition = $checkPosition->whereNull('organisasi_division_id');
            }
            $checkPosition = $checkPosition->first();
        }

        $branch = Cabang::where('name', $row['branch'])->first();
        if(isset($branch)){
            $branchId = $branch->id;
        }
        else{
            array_push($this->failed,$this->rowIndex . " (Branch not found)");
            return;
        }
        
        if(isset($checkPosition)){
            $position = $checkPosition->id;
        }
        else{
            array_push($this->failed,$this->rowIndex . " (Position not found)");
            return;
        }
        $status = ucfirst(strtolower($row['status']));
        if($status != 'Permanent' && $status != 'Contract' && $status != 'Internship' && $status != 'Outsource' && $status != 'Freelance'){
            array_push($this->failed,$this->rowIndex . " (Status not found)");
            return;
        }
        else if(!$status || $status == 'Permanent'){
            $start_date = null;
            $end_date   = null;
        }
        else{
            $start_date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['start_date']);
            $end_date   = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['end_date']);
        }

        $exist = CareerHistory::where('user_id', $userId)->where('effective_date', \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['effective_date']))->first();
        
        if($exist){
            $exist->user_id = $userId;
            $exist->cabang_id = $branchId;
            $exist->structure_organization_custom_id = $position;
            $exist->status = $row['status'];
            $exist->start_date = $start_date;
            $exist->end_date = $end_date;
            $exist->effective_date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['effective_date']);
            $exist->job_desc = $row['job_description'];
            $exist->save();
            ++$this->succesfull;

            synchronize_career($userId);
            return $exist;
        }

        $data = new CareerHistory([
            'user_id'                           => $userId,
            'cabang_id'                         => $branchId,
            'structure_organization_custom_id'  => $position,
            'status'                            => $row['status'],
            'start_date'                        => $start_date,
            'end_date'                          => $end_date,
            'effective_date'                    => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['effective_date']),
            'job_desc'                          => $row['job_description'],
        ]);
        ++$this->succesfull;
        synchronize_career($userId);
        return $data;
    }

    public function headingRow(): int{
        return $this->rowIndex;
    }
}