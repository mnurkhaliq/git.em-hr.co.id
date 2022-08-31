<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ExitInterviewResource extends JsonResource
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
            'user_id' => $this->user_id,
            'user' => new UserMinResource($this->user),
            'status'=> $this->status,
            'status_clearance'=> $this->status_clearance,
            'resign_date' => $this->resign_date,
            'last_work_date' => $this->last_work_date,
            'exit_interview_reason' => $this->exit_interview_reason,
            'reason' => $this->exitInterviewReason,
            'other_reason' => $this->other_reason,
            'hal_berkesan' => $this->hal_berkesan,
            'hal_tidak_berkesan' => $this->hal_tidak_berkesan,
            'masukan' => $this->masukan,
            'tujuan_perusahaan_baru' => $this->tujuan_perusahaan_baru,
            'jenis_bidang_usaha' => $this->jenis_bidang_usaha,
            'history_approval' => ExitInterviewHistoryApprovalResource::collection($this->historyApproval),
            'exit_assets' => ExitInterviewAssetResource::collection($this->assets),
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
        ];
    }
}
