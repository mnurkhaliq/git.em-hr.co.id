<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LeaveResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'user' => new UserMinResource($this->user),
            'jenis_cuti' => $this->jenis_cuti,
            'leave'=> $this->cuti,
            'leave_dates'=> LeaveDateResource::collection($this->cutiKaryawanDates),
            'tanggal_cuti_start' => $this->tanggal_cuti_start,
            'tanggal_cuti_end' => $this->tanggal_cuti_end,
            'keperluan' => $this->keperluan,
            'backup_user_id' => $this->backup_user_id,
            'backup_user' => new UserMinResource($this->backup_karyawan),
            'status' => $this->status,
            'jam_datang_terlambat' => $this->jam_datang_terlambat?substr($this->jam_datang_terlambat,0,5):"",
            'jam_pulang_cepat' => $this->jam_pulang_cepat?substr($this->jam_pulang_cepat,0,5):"",
            'note_pembatalan' => $this->note_pembatalan,
            'total_cuti' => $this->total_cuti,
            'temp_kuota' => $this->temp_kuota,
            'temp_cuti_terpakai' => $this->temp_cuti_terpakai,
            'temp_sisa_cuti' => $this->temp_sisa_cuti,
            'attachment' => $this->attachment,
            'history_approval' => LeaveHistoryApprovalResource::collection($this->historyApproval),
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
        ];
    }
}
