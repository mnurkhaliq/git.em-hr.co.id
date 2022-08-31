<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TrainingDailyResource extends JsonResource
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
            'date' => $this->date,
            'daily_plafond' => $this->daily_plafond,
            'daily' => $this->daily,
            'daily_approved' => $this->daily_approved,
            'note' => $this->note,
            'note_approval' => $this->note_approval,
            'file_struk' => $this->file_struk ? "/storage/file-daily/".$this->file_struk : null,
            'file_struk_raw' => $this->file_struk,
            'history_ammount' => count($this->report) > 0 ? TrainingDailyReportResource::collection($this->report) : NULL,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
        ];

    }
}
