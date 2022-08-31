<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VacancyResource extends JsonResource
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
            'id'=> $this->id,
            'recruitment_type_id' => $this->recruitment_type_id,
            'recruitment_request_id'=> $this->recruitment_request_id,
            'posting_date'=> (string)$this->posting_date,
            'status_post'=> (string)$this->status_post,
            'show_salary_range'=> $this->show_salary_range,
            'created_at'=> (string)$this->created_at,
            'updated_at'=> (string)$this->updated_at,
            'recruitment_id'=> $this->recruitment_id,
            'min_salary'=> $this->min_salary,
            'max_salary'=> $this->max_salary,
            'branch'=> $this->branch,
            'job_desc'=> htmlspecialchars_decode($this->job_desc),
            'job_requirement'=> htmlspecialchars_decode($this->job_requirement),
            'job_position'=> $this->job_position
        ];
    }
}
