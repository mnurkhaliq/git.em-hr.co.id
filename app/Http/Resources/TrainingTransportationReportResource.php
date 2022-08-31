<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TrainingTransportationReportResource extends JsonResource
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
            'training_transportation_id' => $this->training_transportation_id,
            'level_id' => $this->level_id,
            'approval_id'=> $this->approval_id,
            'approval_nik' => $this->userApproved?$this->userApproved->nik:null,
            'approval_name' => $this->userApproved?$this->userApproved->name:null,
            'approved' => $this->approved,
            'note' => $this->note,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
        ];

    }
}
