<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TrainingOtherResource extends JsonResource
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
            'description' => $this->description,
            'nominal' => $this->nominal,
            'nominal_approved' => $this->nominal_approved,
            'note' => $this->note,
            'note_approval' => $this->note_approval,
            'file_struk' => $this->file_struk ? "/storage/file-other/".$this->file_struk : null,
            'file_struk_raw' => $this->file_struk,
            'history_ammount' => count($this->report) > 0 ? TrainingOtherReportResource::collection($this->report) : NULL,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
        ];

    }
}
