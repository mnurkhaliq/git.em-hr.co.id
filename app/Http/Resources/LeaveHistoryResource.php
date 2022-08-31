<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LeaveHistoryResource extends JsonResource
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
            'tanggal_cuti_start' => $this->tanggal_cuti_start,
            'tanggal_cuti_end' => $this->tanggal_cuti_end,
//            'jenis_cuti' => $this->cuti->description,
            'jenis_cuti' => $this->jenis_cuti,
            'leave'=> $this->cuti,
            'total_cuti' => $this->total_cuti,
//            'status' => ($this->status == 1 ? 'Waiting Approval' : ($this->status == 2 ? 'Approved' : ($this->status == 3 ? 'Rejected' : ($this->status == 4 ? 'Cancelled' : '')))),
            'status' => $this->status,
            'keperluan' => $this->keperluan,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
        ];
    }
}
