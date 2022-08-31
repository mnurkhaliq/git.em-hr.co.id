<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BirthdayUserResource extends JsonResource
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
            'count_birthday_like' => count($this->birthdayLike),
            'count_birthday_comment' => count($this->birthdayComment),
            'birthdayComment' => BirthdayCommentResource::collection($this->birthdayComment),
            'birthdayLike' => BirthdayLikeResource::collection($this->birthdayLike),
            'position_full' => get_position_name($this->structure_organization_custom_id),
            'branch' => $this->cabang_id ? $this->cabang->name : null,
            'status_like' => cek_user_like($this->id) ? true : false,
            'can_comment' => cek_user_comment($this->id) ? true : false,
        ];
    }
}
