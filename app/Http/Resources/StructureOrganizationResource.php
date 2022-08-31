<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StructureOrganizationResource extends JsonResource
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
            'name' => get_position_name($this->id),
            'job_desc' => htmlspecialchars_decode($this->description),
            'job_requirement' => htmlspecialchars_decode($this->requirement),
            'grade' => GradeResource::collection($this->grades),
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
        ];
    }
}
