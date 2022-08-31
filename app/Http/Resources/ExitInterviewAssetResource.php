<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ExitInterviewAssetResource extends JsonResource
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
            'exit_interview_id' => $this->exit_interview_id,
            'asset_id'=> $this->asset_id,
            'asset'=> new AssetResource($this->asset),
            'catatan'=> $this->catatan,
            'catatan_user'=> $this->catatan_user,
            'asset_condition'=> $this->asset_condition,
            'employee_check' => $this->employee_check,
            'approval_check' => $this->approval_check,
            'approval_id' => $this->approval_id,
            'approval_name' => $this->userApproved?$this->userApproved->name:null,
            'date_approved' => (string) $this->date_approved,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
        ];
    }
}
