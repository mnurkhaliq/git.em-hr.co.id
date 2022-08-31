<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RecruitmentHistoryApprovalResource extends JsonResource
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
            'recruitment_request_id'=> $this->recruitment_request_id,
            'structure_organization_custom_id'=> $this->structure_organization_custom_id,
            'position' => get_position_name($this->structure_organization_custom_id),
            'setting_approval_level_id'=> $this->setting_approval_level_id,
            'approval_id'=> $this->approval_id,
            'approval_name' => $this->userApproved?$this->userApproved->name:null,
            'is_approved'=> $this->is_approved,
            'date_approved'=> (string)$this->date_approved,
            'created_at'=> (string)$this->created_at,
            'updated_at'=> (string)$this->updated_at
        ];
    }
}
