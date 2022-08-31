<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OvertimeFormResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $attendance = overtime_absensi($this->tanggal, $this->overtimeSheet->user_id);
        return [
            'id'=> $this->id,
            'overtime_sheet_id'=> $this->overtime_sheet_id,
            'tanggal'=> $this->tanggal,
            'description'=> $this->description,
            'awal'=> $this->awal,
            'akhir'=> $this->akhir,
            'total_lembur'=> $this->total_lembur,
            'created_at'=> (string) $this->created_at,
            'updated_at'=> (string) $this->updated_at,
            'overtime_calculate'=> $this->overtime_calculate,
            'awal_claim'=> $this->awal_claim,
            'akhir_claim'=> $this->akhir_claim,
            'total_lembur_claim'=> $this->total_lembur_claim,
            'awal_approved'=> $this->awal_approved,
            'akhir_approved'=> $this->akhir_approved,
            'total_lembur_approved'=> $this->total_lembur_approved,
            'pre_awal_approved'=> $this->pre_awal_approved,
            'pre_akhir_approved'=> $this->pre_akhir_approved,
            'pre_total_approved'=> $this->pre_total_approved,
            'meal_allowance'=> $this->meal_allowance,
            'clock_in' => isset($attendance) ? $attendance->clock_in :'',
            'clock_out' => isset($attendance) ? $attendance->clock_out :''
        ];
    }
}
