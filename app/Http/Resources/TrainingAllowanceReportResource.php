<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TrainingAllowanceReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
//        return parent::toArray($request);
        return [
            'id' => $this->id,
            'training_id' => $this->training_id,
            'training_allowance_id' => $this->training_allowance_id,
            'level_id' => $this->level_id,
            'approval_id'=> $this->approval_id,
            'approval_nik' => $this->userApproved?$this->userApproved->nik:null,
            'approval_name' => $this->userApproved?$this->userApproved->name:null,
            'morning_approved' => $this->morning_approved,
            'afternoon_approved' => $this->afternoon_approved,
            'evening_approved' => $this->evening_approved,
            'note' => $this->note,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
        ];

    }
}
