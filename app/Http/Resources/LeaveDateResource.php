<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LeaveDateResource extends JsonResource
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
            'cuti_karyawan_id' => $this->cuti_karyawan_id,
            'tanggal_cuti' => $this->tanggal_cuti,
            'type' => $this->type,
            'type_name' => ($this->type == '1' ? 'Leave/permit day' : ($this->type == '2' ? 'Shift off day' : ($this->type == '3' ? 'Holiday' : ($this->type == '4' ? 'Other leave/permit day' : '')))),
            'description' => $this->description,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
        ];
    }
}
