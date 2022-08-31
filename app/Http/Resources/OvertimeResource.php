<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OvertimeResource extends JsonResource
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
            'status_claim'=> $this->status_claim,
            'date_claim'=> $this->date_claim,
            'note_pembatalan' => $this->note_pembatalan,
            'history_approval' => OvertimeHistoryApprovalResource::collection($this->historyApproval),
            'details' => OvertimeFormResource::collection($this->overtime_form),
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
        ];
    }
}
