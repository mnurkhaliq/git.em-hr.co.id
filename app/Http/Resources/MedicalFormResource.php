<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MedicalFormResource extends JsonResource
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
            'id'=> $this->id,
            'medical_reimbursement_id'=> $this->medical_reimbursement_id,
            'tanggal_kwitansi'=> $this->tanggal_kwitansi,
            'jenis_klaim'=> $this->jenis_klaim,
            'keterangan'=> $this->keterangan,
            'jumlah'=> $this->jumlah,
            'created_at'=> (string)$this->created_at,
            'updated_at'=> (string)$this->updated_at,
            'user_family_id'=> $this->user_family_id,
            'file_bukti_transaksi'=> $this->file_bukti_transaksi ? "/storage/file-medical/".$this->file_bukti_transaksi : NULL,
            'file_struk_raw' => $this->file_bukti_transaksi,
            'nominal_approve'=> $this->nominal_approve,
            'medical_type_id'=> $this->medical_type_id,
            'no_kwitansi'=> $this->no_kwitansi,
            'note_approval'=> $this->note_approval,
        ];
    }
}
