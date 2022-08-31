<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecruitmentRequest extends Model
{
    //
    protected $table = 'recruitment_request';

    /**
     * [user description]
     * @return [type] [description]
     */
    public function requestor()
    {
        return $this->hasOne('App\User', 'id', 'requestor_id');
    }
    public function branch()
    {
        return $this->hasOne('App\Models\Cabang', 'id', 'branch_id');
    }
    public function structure()
    {
        return $this->hasOne('\App\Models\StructureOrganizationCustom', 'id', 'structure_organization_custom_id');
    }
    public function recruiter()
    {
        return $this->hasOne('App\User', 'id', 'recruiter_id');
    }
    public function approver()
    {
        return $this->hasOne('App\User', 'id', 'approval_hr_user_id');
    }

    public function grade()
    {
        return $this->hasOne('App\Models\Grade', 'id', 'grade_id');
    }
    public function subgrade()
    {
        return $this->hasOne('App\Models\SubGrade', 'id', 'subgrade_id');
    }
    public function category()
    {
        return $this->hasOne('App\Models\JobCategory', 'id', 'job_category_id');
    }

    public function details()
    {
        return $this->hasMany('App\Models\RecruitmentRequestDetail', 'recruitment_request_id', 'id');
    }

    public function internal()
    {
        return $this->hasOne('App\Models\RecruitmentRequestDetail', 'recruitment_request_id', 'id')->where('recruitment_type_id',1);
    }
    public function external()
    {
        return $this->hasOne('App\Models\RecruitmentRequestDetail', 'recruitment_request_id', 'id')->where('recruitment_type_id',2);
    }

    public function interviewers()
    {
        return $this->hasMany('App\Models\RecruitmentInterviewer', 'recruitment_request_id', 'id');
    }
    public function approvals()
    {
        return $this->hasMany('App\Models\HistoryApprovalRecruitment', 'recruitment_request_id', 'id');
    }
    public function applications()
    {
        return $this->hasMany('App\Models\RecruitmentApplication', 'recruitment_request_id', 'id');
    }
    public function internals()
    {
        return $this->hasManyThrough('App\Models\InternalApplication', 'App\Models\RecruitmentApplication');
    }
    public function externals()
    {
        return $this->hasManyThrough('App\Models\ExternalApplication', 'App\Models\RecruitmentApplication');
    }

}
