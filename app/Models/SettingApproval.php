<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SettingApproval extends Model
{
    protected $table = 'setting_approval';

    /**
     * [user description]
     * @return [type] [description]
     */
    public function structure()
    {
        return $this->hasOne('\App\Models\StructureOrganizationCustom', 'id', 'structure_organization_custom_id');
    }

    public function item()
    {
        return $this->hasOne('\App\Models\SettingApprovalLeaveItem', 'setting_approval_id', 'id');
    }

    public function items()
    {
        return $this->hasMany('\App\Models\SettingApprovalLeaveItem', 'setting_approval_id', 'id');
    }

    public function level1()
    {
        return $this->hasOne('\App\Models\SettingApprovalLeaveItem', 'setting_approval_id', 'id')->where('setting_approval_level_id',1);
    }


    public function itemPaymentRequest()
    {
        return $this->hasOne('\App\Models\SettingApprovalPaymentRequestItem', 'setting_approval_id', 'id');
    }

    public function itemsPaymentRequest()
    {
        return $this->hasMany('\App\Models\SettingApprovalPaymentRequestItem', 'setting_approval_id', 'id')->orderBy('setting_approval_level_id','asc');;
    }

    public function level1PaymentRequest()
    {
        return $this->hasOne('\App\Models\SettingApprovalPaymentRequestItem', 'setting_approval_id', 'id')->where('setting_approval_level_id',1);
    }

    public function itemCashAdvance()
    {
        return $this->hasOne('\App\Models\SettingApprovalCashAdvanceItem', 'setting_approval_id', 'id');
    }

    public function itemsCashAdvance()
    {
        return $this->hasMany('\App\Models\SettingApprovalCashAdvanceItem', 'setting_approval_id', 'id')->orderBy('setting_approval_level_id','asc');;
    }

    public function level1CashAdvance()
    {
        return $this->hasOne('\App\Models\SettingApprovalCashAdvanceItem', 'setting_approval_id', 'id')->where('setting_approval_level_id',1);
    }

    public function itemOvertime()
    {
        return $this->hasOne('\App\Models\SettingApprovalOvertimeItem', 'setting_approval_id', 'id');
    }

    public function itemsOvertime()
    {
        return $this->hasMany('\App\Models\SettingApprovalOvertimeItem', 'setting_approval_id', 'id')->orderBy('setting_approval_level_id','asc');;;
    }

    public function level1Overtime()
    {
        return $this->hasOne('\App\Models\SettingApprovalOvertimeItem', 'setting_approval_id', 'id')->where('setting_approval_level_id',1);
    }

    public function itemTimesheet()
    {
        return $this->hasOne('\App\Models\SettingApprovalTimesheetItem', 'setting_approval_id', 'id');
    }

    public function itemsTimesheet()
    {
        return $this->hasMany('\App\Models\SettingApprovalTimesheetItem', 'setting_approval_id', 'id')->orderBy('setting_approval_level_id','asc');;;
    }

    public function level1Timesheet()
    {
        return $this->hasOne('\App\Models\SettingApprovalTimesheetItem', 'setting_approval_id', 'id')->where('setting_approval_level_id',1);
    }

    public function itemTraining()
    {
        return $this->hasOne('\App\Models\SettingApprovalTrainingItem', 'setting_approval_id', 'id');
    }

    public function itemsTraining()
    {
        return $this->hasMany('\App\Models\SettingApprovalTrainingItem', 'setting_approval_id', 'id');
    }

    public function level1Training()
    {
        return $this->hasOne('\App\Models\SettingApprovalTrainingItem', 'setting_approval_id', 'id')->where('setting_approval_level_id',1);
    }
    public function itemRecruitment()
    {
        return $this->hasOne('\App\Models\SettingApprovalRecruitmentItem', 'setting_approval_id', 'id');
    }
    public function itemsRecruitment()
    {
        return $this->hasMany('\App\Models\SettingApprovalRecruitmentItem', 'setting_approval_id', 'id');
    }

    public function level1Recruitment()
    {
        return $this->hasOne('\App\Models\SettingApprovalRecruitmentItem', 'setting_approval_id', 'id')->where('setting_approval_level_id',1);
    }

    public function itemMedical()
    {
        return $this->hasOne('\App\Models\SettingApprovalMedicalItem', 'setting_approval_id', 'id');
    }

    public function itemsMedical()
    {
        return $this->hasMany('\App\Models\SettingApprovalMedicalItem', 'setting_approval_id', 'id')->orderBy('setting_approval_level_id','asc');
    }

    public function level1Medical()
    {
        return $this->hasOne('\App\Models\SettingApprovalMedicalItem', 'setting_approval_id', 'id')->where('setting_approval_level_id',1);
    }

    public function itemLoan()
    {
        return $this->hasOne('\App\Models\SettingApprovalLoanItem', 'setting_approval_id', 'id');
    }

    public function itemsLoan()
    {
        return $this->hasMany('\App\Models\SettingApprovalLoanItem', 'setting_approval_id', 'id')->orderBy('setting_approval_level_id','asc');
    }

    public function level1Loan()
    {
        return $this->hasOne('\App\Models\SettingApprovalLoanItem', 'setting_approval_id', 'id')->where('setting_approval_level_id',1);
    }

    public function itemExit()
    {
        return $this->hasOne('\App\Models\SettingApprovalExitItem', 'setting_approval_id', 'id');
    }

    public function itemsExit()
    {
        return $this->hasMany('\App\Models\SettingApprovalExitItem', 'setting_approval_id', 'id');
    }

    public function level1Exit()
    {
        return $this->hasOne('\App\Models\SettingApprovalExitItem', 'setting_approval_id', 'id')->where('setting_approval_level_id',1);
    }
}
