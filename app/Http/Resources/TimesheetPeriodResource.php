<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TimesheetPeriodResource extends JsonResource
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
            'status' => $this->status,
            'is_editable'=> $this->status == 4 || $this->status == 3,
            'is_approvable'=> $this->status == 1,
            'start_date' => (string) $this->start_date,
            'end_date' => (string) $this->end_date,
            'transactions' => TimesheetPeriodTransactionResource::collection($this->timesheetPeriodTransaction),
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
        ];
    }
}
