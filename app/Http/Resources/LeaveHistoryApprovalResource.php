<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LeaveHistoryApprovalResource extends JsonResource
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
            'cuti_karyawan_id'=> $this->cuti_karyawan_id,
            'structure_organization_custom_id'=> $this->structure_organization_custom_id,
            'position' => get_position_name($this->structure_organization_custom_id),
            'setting_approval_level_id'=> $this->setting_approval_level_id,
            'approval_id'=> $this->approval_id,
            'approval_name' => $this->userApproved?$this->userApproved->name:null,
            'is_approved'=> $this->is_approved,
            'date_approved'=> (string)$this->date_approved,
            'note'=> $this->note,
            'is_withdrawal'=> $this->is_withdrawal,
            'is_approvable'=> in_array($this->cutiKaryawan->status, [1, 6]) && $this->is_approved == null && ($this->setting_approval_level_id == 1 || $this->cutiKaryawan->historyApproval->where('is_withdrawal', $this->is_withdrawal)->where('setting_approval_level_id', $this->setting_approval_level_id - 1)->where('is_approved', '!=', null)->first()),
            'created_at'=> (string)$this->created_at,
            'updated_at'=> (string)$this->updated_at
        ];
    }
}
