<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GradeResource extends JsonResource
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
            'name' => $this->name,
            'benefit' => htmlspecialchars_decode($this->benefit),
            'min_salary' => explode(" - ", $this->salary_range)[0],
            'max_salary'=> explode(" - ", $this->salary_range)[1],
            'sub_grade' => SubGradeResource::collection($this->sub_grade),
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
        ];
    }
}
