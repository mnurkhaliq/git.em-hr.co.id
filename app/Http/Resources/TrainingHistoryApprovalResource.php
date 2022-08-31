<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TrainingHistoryApprovalResource extends JsonResource
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
            'training_id'=> $this->training_id,
            'structure_organization_custom_id'=> $this->structure_organization_custom_id,
            'position' => get_position_name($this->structure_organization_custom_id),
            'setting_approval_level_id'=> $this->setting_approval_level_id,
            'approval_id'=> $this->approval_id,
            'approval_name' => $this->userApproved?$this->userApproved->name:null,
            'is_approved'=> $this->is_approved,
            'date_approved'=> (string)$this->date_approved,
            'note' => $this->note,
            'approval_id_claim'=> $this->approval_id_claim,
            'approval_name_claim' => $this->userApprovedClaim?$this->userApprovedClaim->name:null,
            'is_approved_claim'=> $this->is_approved_claim,
            'date_approved_claim'=> $this->date_approved_claim,
            'note_claim' => $this->note_claim,
            'created_at'=> (string)$this->created_at,
            'updated_at'=> (string)$this->updated_at
        ];
    }
}
