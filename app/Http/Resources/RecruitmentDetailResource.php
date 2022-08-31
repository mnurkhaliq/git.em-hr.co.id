<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RecruitmentDetailResource extends JsonResource
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
            'recruitment_type_id' => $this->recruitment_type_id,
            'recruitment_type' => $this->recruitment_type_id == 1 ? 'Internal' : 'External',
            'posting_date' => (string) $this->posting_date,
            'expired_date' => (string) $this->expired_date,
            'last_posted_date' => (string) $this->last_posted_date,
            'status_post' => $this->status_post,
            'show_salary_range' => $this->show_salary_range,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at
        ];
    }
}
