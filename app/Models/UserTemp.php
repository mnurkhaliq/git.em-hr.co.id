<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTemp extends Model
{
    protected $table = 'users_temp';

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('\App\User', 'nik', 'nik');
    }

    public function structure()
    {
        return $this->hasOne('\App\Models\StructureOrganizationCustom', 'id', 'structure_id');
    }

    public function VisitType()
    {
        return $this->belongsTo('\App\Models\MasterVisitType', 'master_visit_type_id', 'id');
    }

    public function CategoryActivityVisit()
    {
        return $this->belongsTo('\App\Models\MasterCategoryVisit', 'master_category_visit_id', 'id');
    }

    public function shift(){
        return $this->hasOne('\App\Models\Shift', 'id','shift_id');
    }

    public function cabang(){
        return $this->hasOne('\App\Models\Cabang', 'id', 'branch');
    }

    public function overtimePayroll()
    {
        return $this->belongsTo('\App\Models\OvertimePayroll');
    }

    public function payrollCountry(){
        return $this->belongsTo('\App\Models\PayrollCountry', 'payroll_country_id');
    }

    public function payrollUMR(){
        return $this->belongsTo('\App\Models\PayrollUMR', 'payroll_umr_id');
    }

    public function payrollCycle(){
        return $this->belongsTo('\App\Models\PayrollCycle', 'payroll_cycle_id');
    }

    public function attendanceCycle(){
        return $this->belongsTo('\App\Models\PayrollCycle', 'attendance_cycle_id');
    }

    public function project(){
        return $this->belongsTo('\App\Models\Project', 'custom_project_id');
    }

    /**
     * [education description]
     * @return [type] [description]
     */
    public function education()
    {
    	return $this->hasMany('\App\Models\UserEducationTemp', 'user_temp_id', 'id');
    }

    /**
     * [dependent description]
     * @return [type] [description]
     */
    public function family()
    {
    	return $this->hasMany('\App\Models\UserFamilyTemp', 'user_temp_id', 'id');
    }

    /**
     * [certification description]
     * @return [type] [description]
     */
    public function certification()
    {
    	return $this->hasMany('\App\Models\UserCertificationTemp', 'user_temp_id', 'id');
    }

    /**
     * [branchVisit description]
     * @return [type] [description]
     */
    public function branchVisit()
    {
    	return $this->hasMany('\App\Models\UsersBranchVisitTemp', 'user_id_temp', 'id');
    }

    /**
     * [direktur description]
     * @return [type] [description]
     */
    public function direktur()
    {
        return $this->hasOne('\App\Models\EmporeOrganisasiDirektur', 'id', 'empore_organisasi_direktur');
    }

    /**
     * [direktur description]
     * @return [type] [description]
     */
    public function manager()
    {
        return $this->hasOne('\App\Models\EmporeOrganisasiManager', 'id', 'empore_organisasi_manager_id');
    }

    /**
     * [direktur description]
     * @return [type] [description]
     */
    public function staff()
    {
        return $this->hasOne('\App\Models\EmporeOrganisasiStaff', 'id', 'empore_organisasi_staff_id');
    }

    /**
     * [bank description]
     * @return [type] [description]
     */
    public function bank()
    {
        return $this->hasOne('App\Models\Bank', 'id', 'bank_id');
    }  
}
