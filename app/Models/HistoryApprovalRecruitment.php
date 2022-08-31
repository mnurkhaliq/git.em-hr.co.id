<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoryApprovalRecruitment extends Model
{
    //
    protected $table = 'history_approval_recruitment';

    public function recruitmentRequest()
    {
        return $this->belongsTo('App\Models\RecruitmentRequest', 'recruitment_request_id', 'id');
    }

    public function level()
    {
        return $this->hasOne('App\Models\SettingApprovalLevel', 'id', 'setting_approval_level_id');
    }

    public function structure()
    {
        return $this->hasOne('\App\Models\StructureOrganizationCustom', 'id', 'structure_organization_custom_id');
    }

    public function userApproved()
    {
        return $this->hasOne('\App\User', 'id', 'approval_id');
    }
}
