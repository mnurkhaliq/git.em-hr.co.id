<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RecruitmentResource extends JsonResource
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
            'job_position' => $this->job_position,
            'posting_date' => (string) $this->posting_date,
            'expired_date' => (string) $this->expired_date,
            'last_posted_date' => (string) $this->last_posted_date,
            'show_salary_range' => $this->show_salary_range,
            'min_salary' => $this->min_salary,
            'max_salary' => $this->max_salary,
            'branch_id' => $this->branch,
            'job_requirement' => htmlspecialchars_decode($this->job_requirement),
            'job_desc' => htmlspecialchars_decode($this->job_desc),
            'benefit' => htmlspecialchars_decode($this->benefit),
            'is_applied' => count($this->internals) ? true : false,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at
        ];
    }
}
