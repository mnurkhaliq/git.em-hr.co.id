<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MedicalResource extends JsonResource
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
            'number' => $this->number,
            'user_id' => $this->user_id,
            'user' => new UserMinResource($this->user),
            'tanggal_pengajuan' => $this->tanggal_pengajuan,
            'status'=> $this->status,
            'history_approval' => MedicalHistoryApprovalResource::collection($this->historyApproval),
            'details' => MedicalFormResource::collection($this->form),
            'is_transfer' => $this->is_transfer,
            'transfer_proof' => $this->transfer_proof != null ? "/storage/medical/transfer-proof/".$this->transfer_proof : NULL,
            'is_transfer_by' => $this->is_transfer_by,
            'disbursement' => $this->disbursement,
            'can_approve' => !cek_medical_id_approval_or_no($this->id) ? 'no' : 'yes',
            'can_transfer' => cek_transfer_setting_user() != null ? 'yes' : 'no',
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
        ];
    }
}
