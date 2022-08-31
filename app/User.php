<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 
        'email', 
        'password', 
        'last_logged_in_at',
    //    'npwp_number',
    ];

    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
        // sets the model's connection from the one stored in session when it is created
        if(session('user_db',null) != null) {
            $this->setConnection(session('user_db'));
        }
    }

    /**
     * Absensi Setting
     * @return void
     */
    public function absensiSetting()
    {
        return $this->hasOne('App\Models\AbsensiSetting', 'id', 'absensi_setting_id');
    }

    /**
     * [assets description]
     * @return [type] [description]
     */
    public function assets()
    {
        return $this->hasMany('\App\Models\Asset', 'user_id', 'id');
    }

    /**
     * Attendence
     * @return objects
     */
    public function absensiItem()
    {
        return $this->hasMany('App\Models\AbsensiItem', 'user_id', 'id')->orderBy('absensi_item.id', 'DESC');

    }

    /**
     * [empore_staff description]
     * @return [type] [description]
     */
    public function empore_staff()
    {
        return $this->hasOne('App\Models\EmporeOrganisasiStaff', 'id', 'empore_organisasi_staff_id');
    }

    /**
     * [empore_staff description]
     * @return [type] [description]
     */
    public function empore_manager()
    {
        return $this->hasOne('App\Models\EmporeOrganisasiManager', 'id', 'empore_organisasi_manager_id');
    }

    /**
     * [empore_staff description]
     * @return [type] [description]
     */
    public function empore_direktur()
    {
        return $this->hasOne('App\Models\EmporeOrganisasiDirektur', 'id', 'empore_organisasi_direktur');
    }

    /**
     * [inventaris_mobil description]
     * @return [type] [description]
     */
    public function inventaris_mobil()
    {
        return $this->hasMany('App\Models\UserInventarisMobil', 'user_id', 'id');
    }

    /**
     * [inventaris description]
     * @return [type] [description]
     */
    public function inventaris()
    {
        return $this->hasMany('App\Models\UserInventaris', 'user_id', 'id');
    }

    /**
     * [department description]
     * @return [type] [description]
     */
    public function department()
    {
        return $this->hasOne('App\Models\OrganisasiDepartment', 'id', 'department_id');
    }

    /**
     * [section description]
     * @return [type] [description]
     */
    public function section()
    {
        return $this->hasOne('App\Models\OrganisasiSection', 'id', 'section_id');
    }

    /**
     * [position description]
     * @return [type] [description]
     */
    public function position()
    {
        return $this->hasOne('App\Models\OrganisasiPosition', 'id', 'organisasi_position');
    }

    /**
     * [position description]
     * @return [type] [description]
     */
    public function organisasiposition()
    {
        return $this->hasOne('App\Models\OrganisasiPosition', 'id', 'organisasi_position');
    }

    /**
     * [division description]
     * @return [type] [description]
     */
    public function division()
    {
        return $this->hasOne('App\Models\OrganisasiDivision', 'id', 'division_id');
    }

    /**
     * [cabang description]
     * @return [type] [description]
     */
    public function cabang()
    {
        return $this->hasOne('App\Models\Cabang', 'id', 'cabang_id');
    }

    public function branch()
    {
        return $this->hasOne('App\Models\Cabang', 'id', 'cabang_id');
    }



    /**
     * [bank description]
     * @return [type] [description]
     */
    public function bank()
    {
        return $this->hasOne('App\Models\Bank', 'id', 'bank_id');
    }  

    /**
     * [userFamily description]
     * @return [type] [description]
     */
    public function userFamily()
    {
        return $this->hasMany('App\Models\UserFamily', 'user_id', 'id');
    }

    /**
     * [userEducation description]
     * @return [type] [description]
     */
    public function userEducation()
    {
        return $this->hasMany('App\Models\UserEducation', 'user_id', 'id');
    }

     /**
     * [userCertification description]
     * @return [type] [description]
     */
    public function userCertification()
    {
        return $this->hasMany('App\Models\UserCertification', 'user_id', 'id');
    }

    public function userContract()
    {
        return $this->hasMany('App\Models\UserContract', 'user_id', 'id');
    }

    /**
     * [cuti description]
     * @return [type] [description]
     */
    public function cuti()
    {
        return $this->hasMany('App\Models\UserCuti', 'user_id', 'id')->whereHas('cuti');
    }

    /**
     * [provinsi description]
     * @return [type] [description]
     */
    public function provinsi()
    {
        return $this->hasOne('App\Models\Provinsi', 'id_prov', 'provinsi_id');
    }

    /**
     * [kabupaten description]
     * @return [type] [description]
     */
    public function kabupaten()
    {
        return $this->hasOne('App\Models\Kabupaten', 'id_kab', 'kabupaten_id');
    }

    /**
     * [kecamatan description]
     * @return [type] [description]
     */
    public function kecamatan()
    {
        return $this->hasOne('App\Models\Kecamatan', 'id_kec', 'kecamatan_id');
    }

    /**
     * [kelurahan description]
     * @return [type] [description]
     */
    public function kelurahan()
    {
        return $this->hasOne('App\Models\Kelurahan', 'id_kel', 'kelurahan_id');
    }

    public function structure()
    {
        return $this->hasOne('\App\Models\StructureOrganizationCustom', 'id', 'structure_organization_custom_id');
    }

    public function approval()
    {
        return $this->hasOne('\App\Models\SettingApproval', 'structure_organization_custom_id', 'structure_organization_custom_id');
    }

    public function modules(){
        return $this->hasMany('\App\Models\CrmModuleAdmin', 'user_id', 'id');
    }

    public function applications(){
        return $this->hasMany('\App\Models\InternalApplication', 'user_id', 'id');
    }

    public function shift(){
        return $this->belongsTo('\App\Models\Shift', 'shift_id', 'id');
    }

    public function VisitType()
    {
        return $this->belongsTo('\App\Models\MasterVisitType', 'master_visit_type_id', 'id');
    }

    public function CategoryActivityVisit()
    {
        return $this->belongsTo('\App\Models\MasterCategoryVisit', 'master_category_visit_id', 'id');
    }

    public function branchVisit()
    {
    	return $this->hasMany('\App\Models\UsersBranchVisit', 'user_id', 'id');
    }

    public function payroll()
    {
    	return $this->hasOne('\App\Models\Payroll');
    }

    public function overtimePayroll(){
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

    public function shiftScheduleChangeEmployees()
    {
        return $this->hasMany('App\Models\ShiftScheduleChangeEmployee');
    }

    public function settingApprovalTimesheet()
    {
        return $this->hasMany('\App\Models\SettingApprovalTimesheetTransactionItem');
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        // TODO: Implement getJWTIdentifier() method.
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        // TODO: Implement getJWTCustomClaims() method.
        return [];
    }

    public function birthdayLike()
    {
        return $this->hasMany('App\Models\BirthdayLike', 'user_id', 'id');
    }

    public function birthdayComment()
    {
        return $this->hasMany('App\Models\BirthdayComment', 'user_id', 'id');
    }
}
