<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RecruitmentRequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        switch ($this->employment_type) {
            case '1':
                $employment_type = [
                    'id' => 1,
                    'name' => 'Permanent'
                ];
                break;
            case '2':
                $employment_type = [
                    'id' => 2,
                    'name' => 'Contract'
                ];
                break;
            case '3':
                $employment_type = [
                    'id' => 3,
                    'name' => 'Internship'
                ];
                break;
            case '4':
                $employment_type = [
                    'id' => 4,
                    'name' => 'Outsource'
                ];
                break;
            default:
                $employment_type = [
                    'id' => 5,
                    'name' => 'Freelance'
                ];
        }

        return [
            'id' => $this->id,
            'request_number' => $this->request_number,
            'requestor' => new UserMinResource($this->requestor),
            'branch' => $this->branch,
            'structure_organization_custom' => new StructureOrganizationResource($this->structure),
            'job_position' => $this->job_position,
            'recruiter' => new UserMinResource($this->recruiter),
            'grade' => new GradeResource($this->grade),
            'subgrade' => new SubGradeResource($this->subgrade),
            'job_category' => $this->category,
            'min_salary' => $this->min_salary,
            'max_salary' => $this->max_salary,
            'status' => $this->status,
            'approval_hr' => $this->approval_hr,
            'approver_hr' => new UserMinResource($this->approver),
            'approval_hr_date' => $this->approval_hr_date,
            'approval_user' => $this->approval_user,
            'reason' => $this->reason,
            'headcount' => $this->headcount,
            'job_requirement' => htmlspecialchars_decode($this->job_requirement),
            'job_desc' => htmlspecialchars_decode($this->job_desc),
            'benefit' => htmlspecialchars_decode($this->benefit),
            'expected_date' => $this->expected_date,
            'employment_type' => $employment_type,
            'contract_duration' => $this->contract_duration,
            'additional_information' => $this->additional_information,
            'recruitment_type' => RecruitmentDetailResource::collection($this->details),
            'interviewers' => RecruitmentInterviewerResource::collection($this->interviewers),
            'history_approval' => RecruitmentHistoryApprovalResource::collection($this->approvals),
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at
        ];
    }
}
