<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserMinResource extends JsonResource
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
            'name' => $this->name,
            'nik'=> $this->nik,
            'foto' => $this->foto,
            'join_date' => $this->join_date,
            'nama_pemilik_rekening'=> $this->nama_rekening,
            'no_rekening'=> $this->nomor_rekening,
            'nama_bank'=> get_bank_name($this->bank_id),
            'ktp_number' => $this->ktp_number,
            'passport_number' => $this->passport_number,
            'jenis_kelamin' =>  $this->jenis_kelamin,
            'position_full' => get_position_name($this->structure_organization_custom_id),
            'branch' => $this->cabang_id ? $this->cabang->name : '',
        ];
    }
}
