<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LeaveTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // dd($this->userCuti[0]->kuota);
        return [
            'id' => $this->id,
            'jenis_cuti' => $this->jenis_cuti,
            'description' => $this->description,
            'is_attachment' => $this->is_attachment == 1,
            'show_early_leave' => $this->jenis_cuti == 'Permit' ? true : false,
            'show_late_coming' => $this->jenis_cuti == 'Permit' ? true : false,
            'show_kuota' => $this->jenis_cuti == 'Special Leave' || $this->jenis_cuti == 'Annual Leave' ? true : false,
            'kuota_cuti' => $this->jenis_cuti == 'Special Leave' || $this->jenis_cuti == 'Annual Leave' ? (count($this->userCuti) ? ($this->userCuti[0]->kuota ?: 0) : ($this->kuota ?: 0)) : 0,
            'kuota_terpakai' => $this->jenis_cuti == 'Special Leave' || $this->jenis_cuti == 'Annual Leave' ? (count($this->userCuti) ? ($this->userCuti[0]->cuti_terpakai ?: 0) : 0) : 0,
            'kuota_sisa' => $this->jenis_cuti == 'Special Leave' || $this->jenis_cuti == 'Annual Leave' ? (count($this->userCuti) ? ($this->userCuti[0]->sisa_cuti ?: 0) : ($this->kuota ?: 0)) : 0,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
        ];
    }
}
