<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\KpiItem;
use App\Models\KpiSettingScoring;
use App\Models\PayrollDeductions;
use App\Models\PayrollEarnings;
use App\Models\PayrollEarningsEmployee;
use App\Models\PayrollHistory;
use App\Models\RecruitmentRequest;
use App\Models\RecruitmentRequestDetail;
use App\Models\Seaports;
use App\Models\SettingApprovalRecruitmentItem;
use App\Models\Stations;
use App\Models\StructureOrganizationCustom;
use Illuminate\Http\Request;
use App\Models\ModelUser;
use Auth;
use Illuminate\Support\Facades\Validator;
use Session;
use Illuminate\Support\Facades\Input;
use App\Models\Directorate;
use App\Models\Division;
use App\Models\Department;
use App\Models\Section;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\UserInventarisMobil;
use App\Models\UserInventaris;
use App\Models\UserCuti;
use App\Models\UserEducation;
use App\Models\UserFamily;
use App\Models\UserCertification;
use App\Models\UserContract;
use App\Models\PayrollOthers;
use App\Models\PayrollPtkp;
use App\Models\Payroll;
use App\Models\PayrollNet;
use App\Models\PayrollGross;
use App\Models\Airports;
use App\Models\MedicalReimbursement;
use App\Models\Loan;
use App\Models\TimesheetPeriod;
use App\Models\OvertimeSheet;
use App\Models\PaymentRequest;
use App\Models\StatusApproval;
use App\Models\CutiKaryawan;
use App\Models\ExitInterview;
use App\Models\Training;
use App\Models\SettingApproval;
use App\Models\BranchHead;
use App\Models\BranchStaff;
use App\Models\EmporeOrganisasiDirektur;
use App\Models\EmporeOrganisasiManager;
use App\Models\EmporeOrganisasiStaff;
use App\User;
use App\Models\SettingApprovalLeaveItem;
use App\Models\SettingApprovalPaymentRequestItem;
use App\Models\SettingApprovalTimesheetItem;
use App\Models\SettingApprovalOvertimeItem;
use App\Models\SettingApprovalTrainingItem;
use App\Models\SettingApprovalMedicalItem;
use App\Models\SettingApprovalLoanItem;
use App\Models\SettingApprovalExitItem;
use App\Models\SettingApprovalCashAdvanceItem;
use App\Models\CutiBersama;
use App\Models\LiburNasional;
use App\Models\AbsensiItem;
use App\Models\SettingApprovalClearance;
use App\Models\Note;
use App\Models\Shift;
use App\Models\ShiftScheduleChange;
use App\Models\CutiKaryawanDate;
use App\Models\CashAdvance;
use App\Models\AssetTracking;
use App\Models\Asset;
use App\Models\AssetType;
use App\Helpers\DashboardHelper;
use App\Models\TrainingTransportationReport; 
use App\Models\TrainingAllowanceReport; 
use App\Models\TrainingDailyReport; 
use App\Models\TrainingOtherReport; 
use App\Models\Universitas;

class AjaxController extends Controller
{

    protected $respon;

    /**
     * [__construct description]
     */
    public function __construct()
    {
        /**
         * [$this->respon description]
         * @var [type]
         */
        parent::__construct();
        $this->respon = ['message' => 'error'];
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ;
    }

    /**
     * Edit Inline
     * @param  Request $r
     */
    public function postEditInline(Request $r)
    {
        $params['code']         = 200;
        $params['message']      = 'success';

        if(isset($r->table))
        {
            \DB::table($r->table)
                ->where('id', $r->pk)
                ->update([$r->name => $r->value]);
        }

        return response()->json($params);
    }

    /**
     * Get All Year Payslip
     * @return [type] [description]
     */
    public function getYearPaySlipAll(Request $request)
    {
        if($request->ajax())
        {
            $data = \App\Models\PayrollHistory::select(\DB::raw('year(created_at) as tahun'))->groupBy('tahun')->get();

            return response()->json(['result'=> $data]);
        }
    }

    /**
     * get month
     * @param  Request $request
     * @return json
     */
    public function getBulanPaySlipAll(Request $request)
    {
        $params = [];
        if($request->ajax())
        {
            $params = User::select(\DB::raw('month(join_date) as bulan'))->whereYear('join_date', '=', $request->tahun)->first();

            $bulanArray = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];

            $bulan = [];
            if($params)
            {
                for($b = $params->bulan; $b <= date('m'); $b++)
                {
                    $bulan[$b]['id'] = $b;
                    $bulan[$b]['name'] = $bulanArray[$b];
                }
            }
        }

        return response()->json($bulan);
    }

    /**
     * Get Year Pay Slip
     * @param  Request $request
     * @return json
     */
    public function getYearPaySlip(Request $request)
    {
        if($request->ajax())
        {
            return response()->json(['result'=> pay_slip_tahun_history($request->id)]);
        }
    }

    public function chekDateOVertime(Request $request)
    {
        if($request->ajax())
        {
            $cuti_bersama   = CutiBersama::all();
            $libur_nasional = LiburNasional::all();
            $user_ts = strtotime($request->date);
            $result = false;

            foreach ($cuti_bersama as $key => $value_cuti) {
            # code...
                $start_ts = strtotime($value_cuti->dari_tanggal);
                if(($user_ts >= $start_ts) && ($user_ts <= $start_ts))
                    $result = true;
            }

            foreach ($libur_nasional as $key => $value_libur) {
            # code...
                if($user_ts == strtotime($value_libur->tanggal))
                    $result = true;
            }
        }
        return response()->json(['result'=> $result]);
    }

    public function chekInOutOVertime(Request $request)
    {
       if($request->ajax())
        {
            $absensi = \App\Models\AbsensiItem::where('date', $request->date)->where('user_id',$request->user_id)->first();

            return response()->json(['message' => 'success', 'data' => $absensi]);
        }
        return response()->json($this->respon);

    }

    /**
     * [updatePasswordAdministrator description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function updatePasswordAdministrator(Request $request)
    {
        $params = ['message' => 'success'];

        if($request->ajax())
        {
            $data               = \App\User::where('id', \Auth::user()->id)->first();

            if(!\Hash::check($request->currentpassword, $data->password))
            {
                $params['message']  = 'error';
                $params['data']     = 'Current password wrong';
            }
            else
            {
                $validator = Validator::make(request()->all(), [
                    'password' => 'required|string|min:8|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/',
                    'confirmation'      => 'same:password',
                ],
                [
                    'password.regex' => 'Password should contain : Lowercase(s), Uppercase(s), Number(s), Symbol!'
                ]);

                if ($validator->fails()) {
                    $params['message']  = 'error';
                    $params['data']     = $validator->errors()->first();
                }
                else {
                    $data->password = bcrypt($request->password);
                    $data->last_change_password = date('Y-m-d H:i:s');
                    $data->save();

                    $crmdata = \App\Models\ConfigDB::find(\Auth::user()->project_id);
                    $crmdata->password = $request->password;
                    $crmdata->save();

                    \Session::flash('message-success', 'The password was successfully changed');
                }
            }
        }

        return response()->json($params);
    }

    /**
     * [updateFirstPassword description]
     * @return [type] [description]
     */
    public function updatePassword(Request $request)
    {
        $params = ['message' => 'success'];

        if($request->ajax())
        {
            $validator = Validator::make(request()->all(), [
                'password' => 'required|string|min:8|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/',
            ],
                [
                    'password.regex' => 'Password should contain : Lowercase(s), Uppercase(s), Number(s), Symbol!'
                ]);

            if ($validator->fails()) {
                $params['message']  = 'error';
                $params['data']     = $validator->errors()->first();
            }
            else {
                $data = \App\User::where('id', $request->id)->first();
                $data->password = bcrypt($request->password);
                $data->is_reset_first_password = 1;
                $data->last_change_password = date('Y-m-d H:i:s');
                $data->save();
                \Session::flash('message-success', 'Password has Successfully Change');
            }
        }

        return response()->json($params);
    }

    /**
     * [updateInventarisMobil description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function updateInventarisMobil(Request $request)
    {
        $params = ['message' => 'success'];

        if($request->ajax())
        {
            $data = UserInventarisMobil::where('id', $request->id)->first();
            $data->tipe_mobil           = $request->tipe_mobil;
            $data->tahun                = $request->tahun;
            $data->no_polisi            = $request->no_polisi;
            $data->status_mobil         = $request->status_mobil;
            $data->save();

            \Session::flash('message-success', 'Data Cuti Berhasil di update');
        }

        return response()->json($params);
    }

    /**
     * [updateInventarisLainnya description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function updateInventarisLainnya(Request $request)
    {
        $params = ['message' => 'success'];

        if($request->ajax())
        {
            $data = UserInventaris::where('id', $request->id)->first();
            $data->jenis            = $request->jenis;
            $data->description      = $request->description;
            $data->save();

            \Session::flash('message-success', 'Data Inventaris Berhasil di update');
        }

        return response()->json($params);
    }

    /**
     * [updateCuti description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function updateCuti(Request $request)
    {
        $params = ['message' => 'success'];

        if($request->ajax())
        {
            $data = UserCuti::where('id', $request->id)->first();
            $data->cuti_id          = $request->cuti_id;
            $data->kuota            = $request->kuota;
            $data->cuti_terpakai    = $request->terpakai;
            $data->sisa_cuti        = $request->kuota - $request->terpakai;
            $data->save();

            \Session::flash('message-success', 'Data Cuti Berhasil di update');
        }

        return response()->json($params);
    }

    /**
     * [updateEducation description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function updateEducation(Request $request)
    {
        $params = ['message' => 'success'];

        if($request->ajax())
        {
            $data = UserEducation::where('id', $request->id)->first();
            $data->pendidikan       = $request->pendidikan;
            $data->tahun_awal       = $request->tahun_awal;
            $data->tahun_akhir      = $request->tahun_akhir;
            $data->fakultas         = $request->fakultas;
            $data->jurusan          = $request->jurusan;
            $data->nilai            = $request->nilai;
            $data->kota             = $request->kota;
            $data->save();

            \Session::flash('message-success', 'Data Education Berhasil di update');
        }

        return response()->json($params);
    }

    /**
     * [updateDependent description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function updateDependent(Request $request)
    {
        $params = ['message' => 'success'];

        if($request->ajax())
        {
            $data = UserFamily::where('id', $request->id)->first();
            $data->nama             = $request->nama;
            $data->hubungan         = $request->hubungan;
            $data->contact          = $request->contact;
            $data->tempat_lahir     = $request->tempat_lahir;
            $data->tanggal_lahir    = $request->tanggal_lahir;
            $data->tanggal_meninggal= $request->tanggal_meninggal;
            $data->jenjang_pendidikan=$request->jenjang_pendidikan;
            $data->pekerjaan        = $request->pekerjaan;
            $data->tertanggung      = $request->tertanggung;
            $data->save();

            \Session::flash('message-success', 'Data Dependent Berhasil di update');
        }

        return response()->json($params);
    }

    /**
     * [updateCertification description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function addCertification(Request $request)
    {
        $params = ['message' => 'success'];

        if($request->ajax())
        {
            //dd($request->name);
            $data = new UserCertification;
            $data->user_id              = $request->user_id;
            $data->name                 = $request->name;
            $data->date                 = $request->date;
            $data->organizer            = $request->organizer;
            $data->certificate_number   = $request->certificate_number;
            $data->score                = $request->score;
            $data->description          = $request->description;
            if ($request->hasFile('certificate_photo')) {
                $file = $request->file('certificate_photo');
                $fileName = md5(rand() . $file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                $company_url = session('company_url', 'umum') . '/';
                $destinationPath = public_path('/storage/certificate/') . $company_url;
                $file->move($destinationPath, $fileName);
    
                $data->certificate_photo = $company_url . $fileName;
            }
            $data->save();

            \Session::flash('message-success', 'Success save new Data Training');
        }

        return response()->json($params);
    }

    public function updateCertification(Request $request)
    {
        //dd($request);
        //dd($request->hasFile('certificate_photo'));
        $params = ['message' => 'success'];

        if($request->ajax())
        {
            $data = UserCertification::where('id', $request->id)->first();
            $data->name                 = $request->name;
            $data->date                 = $request->date;
            $data->organizer            = $request->organizer;
            $data->certificate_number   = $request->certificate_number;
            $data->score                = $request->score;
            $data->description          = $request->description;
            if ($request->hasFile('certificate_photo')) {
                $file = $request->file('certificate_photo');
                $fileName = md5(rand() . $file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                $company_url = session('company_url', 'umum') . '/';
                $destinationPath = public_path('/storage/certificate/') . $company_url;
                $file->move($destinationPath, $fileName);
    
                $data->certificate_photo = $company_url . $fileName;
            }
            $data->save();

            \Session::flash('message-success', 'Success update Data Training');
        }

        return response()->json($params);
    }

    public function addContract(Request $request)
    {
        $params = ['message' => 'success'];

        if($request->ajax())
        {
            //dd($request->name);
            $data = new UserContract;
            $data->user_id              = $request->user_id;
            $data->number               = $request->number;
            $data->type                 = $request->type;
            $data->start_date           = $request->start_date;
            $data->end_date             = $request->end_date;
            $data->date                 = $request->date;
            $data->contract_sent        = $request->contract_sent;
            $data->return_contract      = $request->return_contract;
            if ($request->hasFile('file_contract')) {
                $file = $request->file('file_contract');
                $fileName = md5(rand() . $file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                $company_url = session('company_url', 'umum') . '/';
                $destinationPath = public_path('/storage/contract/') . $company_url;
                $file->move($destinationPath, $fileName);
    
                $data->file_contract = $company_url . $fileName;
            }
            $data->save();

            \Session::flash('message-success', 'Success save new Data Contract');
        }

        return response()->json(
            ['url' => url('/administrator/karyawan/'.$data->user_id.'/edit?tab=contract')]
        );
    }

    public function updateContract(Request $request)
    {
        //dd($request);
        // dd($request->file('file_contract'));
        $params = ['message' => 'success'];

        if($request->ajax())
        {
            $data = UserContract::where('id', $request->id)->first();
            $data->user_id              = $request->user_id;
            $data->number               = $request->number;
            $data->type                 = $request->type;
            $data->start_date           = $request->start_date;
            $data->end_date             = $request->end_date;
            $data->date                 = $request->date;
            $data->contract_sent        = $request->contract_sent;
            $data->return_contract      = $request->return_contract;
            if ($request->hasFile('file_contract')) {
                $file = $request->file('file_contract');
                $fileName = md5(rand() . $file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                $company_url = session('company_url', 'umum') . '/';
                $destinationPath = public_path('/storage/contract/') . $company_url;
                $file->move($destinationPath, $fileName);
    
                $data->file_contract = $company_url . $fileName;
            }
            $data->save();

            \Session::flash('message-success', 'Success update Data Contract');
        }

        return response()->json(
            ['url' => url('/administrator/karyawan/'.$data->user_id.'/edit?tab=contract')]
        );
    }

    /**
     * [getBulangPaySlip description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getBulanPaySlip(Request $request)
    {
        $params = [];
        if($request->ajax())
        {
            //$params = User::select(\DB::raw('month(join_date) as bulan'))->whereYear('join_date', '=', $request->tahun)->where('id', $request->user_id)->first();

            /*
            $params = User::select(\DB::raw('month(join_date) as bulan'))->where('id', $request->user_id)->first();

            $bulanArray = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];

            $bulan = [];
            if($params)
            {
                for($b = $params->bulan; $b <= date('m'); $b++)
                {
                    $bulan[$b]['id'] = $b;
                    $bulan[$b]['name'] = $bulanArray[$b];
                }
            }*/
            $bulanArray = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];

            $bulan = [];
            if($request->tahun < date('Y')){
                for($b = 1; $b <= 12; $b++)
                {
                    $bulan[$b]['id'] = $b;
                    $bulan[$b]['name'] = $bulanArray[$b];
                }
            }else if($request->tahun == date('Y')){
                for($b = 1; $b <= date('m'); $b++)
                {
                    $bulan[$b]['id'] = $b;
                    $bulan[$b]['name'] = $bulanArray[$b];
                }
            }

        }

        return response()->json($bulan);
    }

    /**
     * Calcualte Payroll
     * @param  Request $request
     * @return json
     */
    public function getCalculatePayroll(Request $request)
    {
        //dd($request);
        $params = [];

        $biaya_jabatan  = PayrollOthers::where('id', 1)->first()->value;
        $params['umr_value'] = $upah_minimum = PayrollOthers::where('id', 2)->first()->value;
        $params['umr_label'] = 'Default';
        $bpjs_pensiunan_batas = PayrollOthers::where('id', 3)->first()->value;
        $bpjs_kesehatan_batas = PayrollOthers::where('id', 4)->first()->value;

        if($request->ajax())
        {
            if(!empty($request->user_id)) {
                $user = User::find($request->user_id);
                if($user && $user->payroll_umr_id) {
                    $params['umr_value'] = $upah_minimum = $user->payrollUMR->value;
                    $params['umr_label'] = $user->payrollUMR->label;
                }
            }

            $request->salary    = replace_idr($request->salary);

            //Jika salary dibawah UMP, maka salary untuk perhitungan BPJS berdasarkan UMP
            if($request->salary && $request->salary != 0 && $request->salary<$upah_minimum){
                $salary=$upah_minimum;
            }
            else{
                $salary=$request->salary;
            }
            $payroll_type = $request->payroll_type;

            $request->bonus     = replace_idr($request->bonus);
            $request->thr     = replace_idr($request->thr);
            $request->overtime     = replace_idr($request->overtime);

            //JHT EMPLOYEE
            $bpjs_ketenagakerjaan2_persen = get_setting('bpjs_jaminan_jht_employee');
            $bpjs_ketenagakerjaan2 = ($salary * $bpjs_ketenagakerjaan2_persen / 100);
            // start custom
            if($request->edit_bpjs_ketenagakerjaan_employee != 0 )
            {
                if(replace_idr($request->bpjs_ketenagakerjaan_employee) != $bpjs_ketenagakerjaan2)
                {
                    $bpjs_ketenagakerjaan2 = replace_idr($request->bpjs_ketenagakerjaan_employee);
                }
            }
            // end custom
            //JHT COMPANY
            $bpjs_jht_company_persen = get_setting('bpjs_jht_company');
            $bpjs_jht_company = ($salary * $bpjs_jht_company_persen / 100);
            // start custom
            if($request->edit_bpjs_jht_company != 0 )
            {
                if(replace_idr($request->bpjs_jht_company) != $bpjs_jht_company)
                {
                    $bpjs_jht_company = replace_idr($request->bpjs_jht_company);
                }
            }
            // end custom
            //JP EMPLOYEE
            $bpjs_pensiun2        = 0;
            $bpjs_pensiun2_persen = get_setting('bpjs_jaminan_jp_employee');
            if($salary <= $bpjs_pensiunan_batas)
            {
                $bpjs_pensiun2     = ($salary * $bpjs_pensiun2_persen / 100);
            }
            else
            {
                $bpjs_pensiun2     = ($bpjs_pensiunan_batas * $bpjs_pensiun2_persen / 100);
            }
            // start custom
            if($request->edit_edit_bpjs_pensiun_employee != 0)
            {
                if(replace_idr($request->bpjs_pensiun_employee) != $bpjs_pensiun2)
                {
                    $bpjs_pensiun2 = replace_idr($request->bpjs_pensiun_employee);
                }
            }
            // end custom
            //JP COMPANY
            $bpjs_pensiun_company_persen = get_setting('bpjs_pensiun_company');
            $bpjs_pensiun_company = 0;

            if($salary <= $bpjs_pensiunan_batas)
            {
                $bpjs_pensiun_company     = ($salary * $bpjs_pensiun_company_persen / 100);
            }
            else
            {
                $bpjs_pensiun_company     = ($bpjs_pensiunan_batas * $bpjs_pensiun_company_persen / 100);
            }
            // start custom
            if($request->edit_bpjs_pensiun_company != 0 )
            {
                if(replace_idr($request->bpjs_pensiun_company) != $bpjs_pensiun_company)
                {
                    $bpjs_pensiun_company = replace_idr($request->bpjs_pensiun_company);
                }
            }
            // end custom
            //KESEHATAN EMPLOYEE
            $bpjs_kesehatan2        = 0;
            $bpjs_kesehatan2_persen  = get_setting('bpjs_kesehatan_employee');
            if($salary <= $bpjs_kesehatan_batas)
            {
                $bpjs_kesehatan2     = ($salary * $bpjs_kesehatan2_persen / 100);
            }
            else
            {
                $bpjs_kesehatan2     = ($bpjs_kesehatan_batas * $bpjs_kesehatan2_persen / 100);
            }
            // start custom
            if($request->edit_bpjs_kesehatan_employee !=0 )
            {
                if(replace_idr($request->bpjs_kesehatan_employee) != $bpjs_kesehatan2)
                {
                    $bpjs_kesehatan2 = replace_idr($request->bpjs_kesehatan_employee);
                }
            }
            // end custom
            //KESEHATAN COMPANY
            $bpjs_kesehatan_company = 0;
            $bpjs_kesehatan_company_persen = get_setting('bpjs_kesehatan_company');
            if($salary <= $bpjs_kesehatan_batas)
            {
                $bpjs_kesehatan_company     = ($salary * $bpjs_kesehatan_company_persen / 100);
            }
            else
            {
                $bpjs_kesehatan_company     = ($bpjs_kesehatan_batas * $bpjs_kesehatan_company_persen / 100);
            }
            // start custom
            if($request->edit_bpjs_kesehatan_company !=0 )
            {
                if(replace_idr($request->bpjs_kesehatan_company) != $bpjs_kesehatan_company)
                {
                    $bpjs_kesehatan_company = replace_idr($request->bpjs_kesehatan_company);
                }
            }
            // end custom
            //JKK COMPANY
            $bpjs_jkk_company_persen = get_setting('bpjs_jkk_company');
            $bpjs_jkk_company = ($salary * $bpjs_jkk_company_persen / 100);

            // start custom
            if($request->edit_bpjs_jkk_company != 0 )
            {
                if(replace_idr($request->bpjs_jkk_company) != $bpjs_jkk_company)
                {
                    $bpjs_jkk_company = replace_idr($request->bpjs_jkk_company);
                }
            }
            // end custom

            //JKM COMPANY
            $bpjs_jkm_company_persen = get_setting('bpjs_jkm_company');
            $bpjs_jkm_company = ($salary * $bpjs_jkm_company_persen / 100);

            // start custom
            if($request->edit_bpjs_jkm_company != 0 )
            {
                if(replace_idr($request->bpjs_jkm_company) != $bpjs_jkm_company)
                {
                    $bpjs_jkm_company = replace_idr($request->bpjs_jkm_company);
                }
            }
            // end custom
            $bpjstotalearning = $bpjs_jkk_company + $bpjs_jkm_company + $bpjs_jht_company + $bpjs_pensiun_company + $bpjs_kesehatan_company;
            //$bpjspenambahan = $bpjstotalearning;
            //$bpjspengurangan = $bpjs_ketenagakerjaan2 + $bpjs_pensiun2 +$bpjs_kesehatan2 + $bpjstotalearning;

            $bpjspenambahan = $bpjs_jkk_company + $bpjs_jkm_company+$bpjs_kesehatan_company;
            $bpjspengurangan = $bpjs_ketenagakerjaan2 + $bpjs_pensiun2;

            $earnings               = 0;
            $taxable_earning        = 0;
            if(isset($request->earnings))
            {
                foreach($request->earnings as $index => $item)
                {
                    $earnings += replace_idr($item);

                    $earningItem = PayrollEarnings::find($request->earning_items[$index]);
                    if($earningItem && $earningItem->taxable == 1){
                        $taxable_earning += replace_idr($item);
                    }
                }
            }
            info($request->deduction_items);
            info($request->deductions);
            $deductions             = 0;
            $taxable_deduction      = 0;
            if(isset($request->deductions))
            {
                foreach($request->deductions as $index => $item)
                {
                    $deductions += replace_idr($item);

                    $deductionItem = PayrollDeductions::find($request->deduction_items[$index]);
                    if($deductionItem && $deductionItem->taxable == 1){
                        $taxable_deduction += replace_idr($item);
                    }
                }
            }

            if(isset($request->loan_payments))
            {
                foreach($request->loan_payments as $index => $item)
                {
                    $deductions += replace_idr($item);
                }
            }

            if(isset($request->business_trip_payments))
            {
                // dd($request->business_trip_payments);
                foreach($request->business_trip_payments as $index => $item)
                {
                    $earnings += replace_idr($item);
                }
            }
            if(isset($request->business_trip_payments_deduc))
            {
                //dd($request->business_trip_payments_deduc);
                foreach($request->business_trip_payments_deduc as $index => $item)
                {
                    $deductions += replace_idr($item);
                }
            }

            if(isset($request->cash_advance_payments))
            {
                // dd($request->cash_advance_payments);
                foreach($request->cash_advance_payments as $index => $item)
                {
                    $earnings += replace_idr($item);
                }
            }

            if(isset($request->cash_advance_payments_deduc))
            {
                // dd($request->cash_advance_payments_deduc);
                foreach($request->cash_advance_payments_deduc as $index => $item)
                {
                    $deductions += replace_idr($item);
                }
            }

//            $gross_income           = (($request->salary + $request->overtime + $earnings + $bpjspenambahan) * 12);
            $gross_income           = (($request->salary + $request->overtime + $taxable_earning + $bpjspenambahan) * 12);
            // burdern allowance
//            $burden_allow           = 5 *  ($request->salary + $earnings + $bpjspenambahan + $request->bonus) / 100;
            // $burden_allowYear_non_bonus           = 5*($gross_income)/100;
            // $burden_allow_non_bonus = $burden_allowYear_non_bonus/12;

            $gross_income += $request->bonus + $request->thr;
            $burden_allowYear           = 5*($gross_income)/100;
            $burden_allow = $burden_allowYear/12;

            // $burdenAllowTHR = 0;
            // if (!empty(\Session::get('month')) && !empty(\Session::get('year')) && \Session::get('month') == 12 && $request->user_id) {
            //     $payrollHistory = PayrollHistory::select('bonus', 'thr')
            //         ->where('user_id', $request->user_id)
            //         // ->where('is_lock', 1)
            //         ->whereYear('created_at', \Session::get('year'))
            //         ->whereMonth('created_at', '!=', 12)
            //         ->groupBy('created_at')
            //         ->orderBy('created_at', 'ASC')
            //         ->get();
                
            //     if ($payrollHistory->count() == 11) {
            //         $burdenAllowTHR +=  5*($payrollHistory->sum('bonus') + $request->bonus + $payrollHistory->sum('thr') + $request->thr)/100;
            //     }
            // }

            $biaya_jabatan_bulan = $biaya_jabatan / 12;
            // if ($burden_allow_non_bonus + $burdenAllowTHR > $biaya_jabatan_bulan) {
            //     $burden_allow_non_bonus = $biaya_jabatan_bulan;
            // } else {
            //     $burden_allow_non_bonus += $burdenAllowTHR;
            // }

            if($burden_allow > $biaya_jabatan_bulan) {
                $burden_allow = $biaya_jabatan_bulan;
            }

//            $total_deduction = ($bpjspengurangan * 12) + ($burden_allow * 12);
            $total_deduction = ($bpjspengurangan * 12) + ($burden_allow * 12) + ($taxable_deduction * 12);

            //$net_yearly_income          = $gross_income - $total_deduction;
            $net_yearly_val          = $gross_income - $total_deduction;
            $net_yearly_ratusan      = substr($net_yearly_val, -3);
            $net_yearly_income       = $net_yearly_val - $net_yearly_ratusan;

            $untaxable_income = 0;

            $ptkp = \App\Models\PayrollPtkp::where('id', 1)->first();
            if($request->payroll_jenis_kelamin == 'Female' || $request->payroll_jenis_kelamin == "")
            {
                $untaxable_income = $ptkp->bujangan_wanita;
            }elseif ($request->payroll_jenis_kelamin == 'Male') {
                # code...
                if($request->payroll_marital_status == 'Bujangan/Wanita' || $request->payroll_marital_status == "")
                {
                    $untaxable_income = $ptkp->bujangan_wanita;
                }
                if($request->payroll_marital_status == 'Menikah')
                {
                    $untaxable_income = $ptkp->menikah;
                }
                if($request->payroll_marital_status == 'Menikah Anak 1')
                {
                    $untaxable_income = $ptkp->menikah_anak_1;
                }
                if($request->payroll_marital_status == 'Menikah Anak 2')
                {
                    $untaxable_income = $ptkp->menikah_anak_2;
                }
                if($request->payroll_marital_status == 'Menikah Anak 3')
                {
                    $untaxable_income = $ptkp->menikah_anak_3;
                }
            }

            //$taxable_yearly_income = $net_yearly_income - $untaxable_income;
            $taxable_yearly_income_val     = $net_yearly_income - $untaxable_income;
            $taxable_yearly_income_ratusan = substr($taxable_yearly_income_val, -3);
            $taxable_yearly_income         = $taxable_yearly_income_val - $taxable_yearly_income_ratusan;

            $yearly_income_tax = 0;
            foreach (\App\Models\PayrollPPH::all() as $key => $value) {
                if (($taxable_yearly_income <= $value->batas_atas && $taxable_yearly_income >= $value->batas_bawah && $value->batas_atas != null) || ($taxable_yearly_income >= $value->batas_bawah && $value->batas_atas == null)) {
                    $yearly_income_tax += ($value->tarif / 100) * ($taxable_yearly_income - $value->batas_bawah);
                } else if ($taxable_yearly_income >= $value->batas_atas && $value->batas_atas != null) {
                    $yearly_income_tax += ($value->tarif / 100) * ($value->batas_atas - $value->batas_bawah);
                }
            }

            $monthly_income_tax             = $yearly_income_tax / 12;

            $gross_income_per_month         = ($request->salary + $request->overtime + $earnings + $bpjspenambahan)  + $request->bonus + $request->thr;//$gross_income / 12;

            $less                           = $bpjspengurangan + $monthly_income_tax;
//            $gross_thp                      = ($request->salary + $earnings + $request->bonus);




            $params['untaxable_income']     = number_format($untaxable_income);
            $params['gross_income']         = number_format($gross_income);
            $params['burden_allow']         = number_format($burden_allow);
            //$params['bpjs_ketenagakerjaan'] = number_format($bpjs_ketenagakerjaan);
            $params['bpjs_ketenagakerjaan2'] = number_format($bpjs_ketenagakerjaan2);
            //$params['bpjs_kesehatan']         = number_format($bpjs_kesehatan);
            $params['bpjs_kesehatan2']        = number_format($bpjs_kesehatan2);
            //$params['bpjs_pensiun']         = number_format($bpjs_pensiun);
            $params['bpjs_pensiun2']        = number_format($bpjs_pensiun2);

            $params['bpjs_jkk_company']         = number_format($bpjs_jkk_company);
            $params['bpjs_jkm_company']         = number_format($bpjs_jkm_company);
            $params['bpjs_jht_company']         = number_format($bpjs_jht_company);
            $params['bpjs_pensiun_company']     = number_format($bpjs_pensiun_company);
            $params['bpjs_kesehatan_company']   = number_format($bpjs_kesehatan_company);
            $params['bpjstotalearning']         = number_format($bpjstotalearning);

            $params['total_deduction']      = number_format($total_deduction);
            $params['net_yearly_income']    = number_format($net_yearly_income);
            $params['untaxable_income']     = number_format($untaxable_income);
            $params['taxable_yearly_income']        = number_format($taxable_yearly_income);
            $params['yearly_income_tax']            = number_format($yearly_income_tax);
            $params['gross_income_per_month']       = number_format($gross_income_per_month);
            $params['less']                         = number_format($less);
            $params['gender']                       = $request->payroll_jenis_kelamin;
            $params['marital_status']               = $request->payroll_marital_status;

            $non_bonus = $this->getCalculatePayrollNonBonus($request);

            $params['yearly_income_tax_non_bonus']  = $non_bonus['yearly_income_tax'];
            $params['monthly_income_tax']           = $yearly_income_tax - $non_bonus['yearly_income_tax'] + ($non_bonus['yearly_income_tax'] / 12);
            if($payroll_type == 'NET')
                $thp = ($request->salary + $request->bonus + $request->thr + $request->overtime + $earnings + $bpjstotalearning) - ($deductions + $bpjs_ketenagakerjaan2 + $bpjs_kesehatan2 + $bpjs_pensiun2 + $bpjstotalearning);
            else
                $thp = ($request->salary + $request->bonus + $request->thr + $request->overtime + $earnings + $bpjstotalearning) - ($deductions + $bpjs_ketenagakerjaan2 + $bpjs_kesehatan2 + $bpjs_pensiun2 + $params['monthly_income_tax'] + $bpjstotalearning);

            $params['monthly_income_tax']           = number_format($params['monthly_income_tax']);
            // start custom



            $params['thp']                          = number_format($thp);
            // end custom
            $params['bpjs_pengurang']               = number_format($bpjspengurangan);
            $params['bpjs_penambahan']              = number_format($bpjspenambahan);
        }

        return response()->json($params);
    }

     /**
     * Calcualte Payroll Non Bonus
     * @param  Request $request
     * @return json
     */
    public function getCalculatePayrollNonBonus($request)
    {
        $biaya_jabatan  = PayrollOthers::where('id', 1)->first()->value;
        $upah_minimum   = PayrollOthers::where('id', 2)->first()->value;
        $bpjs_pensiunan_batas = PayrollOthers::where('id', 3)->first()->value;
        $bpjs_kesehatan_batas = PayrollOthers::where('id', 4)->first()->value;

        $params = [];
        if($request->ajax())
        {
            if(!empty($request->user_id)) {
                $user = User::find($request->user_id);
                if($user && $user->payroll_umr_id) {
                    $upah_minimum = $user->payrollUMR->value;
                }
            }
            
            $request->salary    = replace_idr($request->salary);

            //Jika salary dibawah UMP, maka salary untuk perhitungan BPJS berdasarkan UMP
            if($request->salary && $request->salary != 0 && $request->salary<$upah_minimum){
                $salary=$upah_minimum;
            }
            else{
                $salary=$request->salary;
            }

            $request->bonus     = replace_idr($request->bonus);
            $request->thr     = replace_idr($request->thr);
            $request->overtime     = replace_idr($request->overtime);

            //JHT EMPLOYEE
            $bpjs_ketenagakerjaan2_persen = get_setting('bpjs_jaminan_jht_employee');
            $bpjs_ketenagakerjaan2 = ($salary * $bpjs_ketenagakerjaan2_persen / 100);
            // start custom
            if($request->edit_bpjs_ketenagakerjaan_employee != 0 )
            {
                if(replace_idr($request->bpjs_ketenagakerjaan_employee) != $bpjs_ketenagakerjaan2)
                {
                    $bpjs_ketenagakerjaan2 = replace_idr($request->bpjs_ketenagakerjaan_employee);
                }
            }
            // end custom

            //JHT COMPANY
            $bpjs_jht_company_persen = get_setting('bpjs_jht_company');
            $bpjs_jht_company = ($salary * $bpjs_jht_company_persen / 100);
            // start custom
            if($request->edit_bpjs_jht_company != 0 )
            {
                if(replace_idr($request->bpjs_jht_company) != $bpjs_jht_company)
                {
                    $bpjs_jht_company = replace_idr($request->bpjs_jht_company);
                }
            }
            // end custom

            //JP EMPLOYEE
            $bpjs_pensiun2        = 0;
            $bpjs_pensiun2_persen = get_setting('bpjs_jaminan_jp_employee');

            if($salary <= $bpjs_pensiunan_batas)
            {
                $bpjs_pensiun2     = ($salary * $bpjs_pensiun2_persen / 100);
            }
            else
            {
                $bpjs_pensiun2     = ($bpjs_pensiunan_batas * $bpjs_pensiun2_persen / 100);
            }

            // start custom
            if($request->edit_edit_bpjs_pensiun_employee != 0)
            {
                if(replace_idr($request->bpjs_pensiun_employee) != $bpjs_pensiun2)
                {
                    $bpjs_pensiun2 = replace_idr($request->bpjs_pensiun_employee);
                }
            }
            // end custom

            //JP COMPANY
            $bpjs_pensiun_company_persen = get_setting('bpjs_pensiun_company');
            $bpjs_pensiun_company = 0;

            if($salary <= $bpjs_pensiunan_batas)
            {
                $bpjs_pensiun_company     = ($salary * $bpjs_pensiun_company_persen / 100);
            }
            else
            {
                $bpjs_pensiun_company     = ($bpjs_pensiunan_batas * $bpjs_pensiun_company_persen / 100);
            }
            // start custom
            if($request->edit_bpjs_pensiun_company != 0 )
            {
                if(replace_idr($request->bpjs_pensiun_company) != $bpjs_pensiun_company)
                {
                    $bpjs_pensiun_company = replace_idr($request->bpjs_pensiun_company);
                }
            }
            // end custom

            //KESEHATAN EMPLOYEE
            $bpjs_kesehatan2        = 0;
            $bpjs_kesehatan2_persen  = get_setting('bpjs_kesehatan_employee');
            if($salary <= $bpjs_kesehatan_batas)
            {
                $bpjs_kesehatan2     = ($salary * $bpjs_kesehatan2_persen / 100);
            }
            else
            {
                $bpjs_kesehatan2     = ($bpjs_kesehatan_batas * $bpjs_kesehatan2_persen / 100);
            }

            // start custom
            if($request->edit_bpjs_kesehatan_employee !=0 )
            {
                if(replace_idr($request->bpjs_kesehatan_employee) != $bpjs_kesehatan2)
                {
                    $bpjs_kesehatan2 = replace_idr($request->bpjs_kesehatan_employee);
                }
            }
            // end custom

            //KESEHATAN COMPANY
            $bpjs_kesehatan_company = 0;
            $bpjs_kesehatan_company_persen = get_setting('bpjs_kesehatan_company');
            if($salary <= $bpjs_kesehatan_batas)
            {
                $bpjs_kesehatan_company     = ($salary * $bpjs_kesehatan_company_persen / 100);
            }
            else
            {
                $bpjs_kesehatan_company     = ($bpjs_kesehatan_batas * $bpjs_kesehatan_company_persen / 100);
            }

            // start custom
            if($request->edit_bpjs_kesehatan_company !=0 )
            {
                if(replace_idr($request->bpjs_kesehatan_company) != $bpjs_kesehatan_company)
                {
                    $bpjs_kesehatan_company = replace_idr($request->bpjs_kesehatan_company);
                }
            }
            // end custom

            //JKK COMPANY
            $bpjs_jkk_company_persen = get_setting('bpjs_jkk_company');
            $bpjs_jkk_company = ($salary * $bpjs_jkk_company_persen / 100);

            // start custom
            if($request->edit_bpjs_jkk_company != 0 )
            {
                if(replace_idr($request->bpjs_jkk_company) != $bpjs_jkk_company)
                {
                    $bpjs_jkk_company = replace_idr($request->bpjs_jkk_company);
                }
            }
            // end custom

            //JKM COMPANY
            $bpjs_jkm_company_persen = get_setting('bpjs_jkm_company');
            $bpjs_jkm_company = ($salary * $bpjs_jkm_company_persen / 100);

            // start custom
            if($request->edit_bpjs_jkm_company != 0 )
            {
                if(replace_idr($request->bpjs_jkm_company) != $bpjs_jkm_company)
                {
                    $bpjs_jkm_company = replace_idr($request->bpjs_jkm_company);
                }
            }
            // end custom
            $bpjstotalearning = $bpjs_jkk_company + $bpjs_jkm_company + $bpjs_jht_company + $bpjs_pensiun_company + $bpjs_kesehatan_company;
            //$bpjspenambahan = $bpjstotalearning;
            //$bpjspengurangan = $bpjs_ketenagakerjaan2 + $bpjs_pensiun2 +$bpjs_kesehatan2 + $bpjstotalearning;

            $bpjspenambahan = $bpjs_jkk_company + $bpjs_jkm_company+$bpjs_kesehatan_company;
            $bpjspengurangan = $bpjs_ketenagakerjaan2 + $bpjs_pensiun2;

            $earnings = 0;
            $taxable_earning = 0;
            if(isset($request->earnings))
            {
                foreach($request->earnings as $index => $item)
                {
                    $earnings += replace_idr($item);

                    $earningItem = PayrollEarnings::find($request->earning_items[$index]);
                    if($earningItem && $earningItem->taxable == 1){
                        $taxable_earning += replace_idr($item);
                    }

                }
            }

            $deductions             = 0;
            $taxable_deduction      = 0;
            if(isset($request->deductions))
            {
                foreach($request->deductions as $index => $item)
                {
                    $deductions += replace_idr($item);

                    $deductionItem = PayrollDeductions::find($request->deduction_items[$index]);
                    if($deductionItem && $deductionItem->taxable == 1){
                        $taxable_deduction += replace_idr($item);
                    }
                }
            }

            if(isset($request->loan_payments))
            {
                foreach($request->loan_payments as $index => $item)
                {
                    $deductions += replace_idr($item);
                }
            }

            if(isset($request->business_trip_payments))
            {
                foreach($request->business_trip_payments as $index => $item)
                {
                    $earnings += replace_idr($item);
                }
            }
            if(isset($request->business_trip_payments_deduc))
            {
                foreach($request->business_trip_payments_deduc as $index => $item)
                {
                    $deductions += replace_idr($item);
                }
            }

            if(isset($request->cash_advance_payments))
            {
                foreach($request->cash_advance_payments as $index => $item)
                {
                    $earnings += replace_idr($item);
                }
            }

            if(isset($request->cash_advance_payments_deduc))
            {
                foreach($request->cash_advance_payments_deduc as $index => $item)
                {
                    $deductions += replace_idr($item);
                }
            }

//            $gross_income           = ($request->salary + $request->overtime + $earnings + $bpjspenambahan) * 12;
            $gross_income           = ($request->salary + $request->overtime + $taxable_earning + $bpjspenambahan) * 12;

            // burdern allowance
//            $burden_allow = 5 * ($request->salary + $earnings + $bpjspenambahan) / 100;
            $burden_allowYear           = 5*($gross_income)/100;
            $burden_allow = $burden_allowYear/12;

            $biaya_jabatan_bulan    = $biaya_jabatan / 12;

            if($burden_allow > $biaya_jabatan_bulan)
            {
                $burden_allow = $biaya_jabatan_bulan;
            }

//            $total_deduction = ($bpjspengurangan * 12) + ($burden_allow * 12);
            $total_deduction = ($bpjspengurangan * 12) + ($burden_allow * 12) + ($taxable_deduction * 12);

            //$net_yearly_income          = $gross_income - $total_deduction;
            $net_yearly_val          = $gross_income - $total_deduction;
            $net_yearly_ratusan      = substr($net_yearly_val, -3);
            $net_yearly_income       = $net_yearly_val - $net_yearly_ratusan;

            $untaxable_income = 0;

            $ptkp = \App\Models\PayrollPtkp::where('id', 1)->first();
            if($request->payroll_jenis_kelamin == 'Female' || $request->payroll_jenis_kelamin == "")
            {
                $untaxable_income = $ptkp->bujangan_wanita;
            }elseif ($request->payroll_jenis_kelamin == 'Male') {
                if($request->payroll_marital_status == 'Bujangan/Wanita' || $request->payroll_marital_status == "")
                {
                    $untaxable_income = $ptkp->bujangan_wanita;
                }
                if($request->payroll_marital_status == 'Menikah')
                {
                    $untaxable_income = $ptkp->menikah;
                }
                if($request->payroll_marital_status == 'Menikah Anak 1')
                {
                    $untaxable_income = $ptkp->menikah_anak_1;
                }
                if($request->payroll_marital_status == 'Menikah Anak 2')
                {
                    $untaxable_income = $ptkp->menikah_anak_2;
                }
                if($request->payroll_marital_status == 'Menikah Anak 3')
                {
                    $untaxable_income = $ptkp->menikah_anak_3;
                }
            }

            //$taxable_yearly_income = $net_yearly_income - $untaxable_income;
            $taxable_yearly_income_val     = $net_yearly_income - $untaxable_income;
            $taxable_yearly_income_ratusan = substr($taxable_yearly_income_val, -3);
            $taxable_yearly_income         = $taxable_yearly_income_val - $taxable_yearly_income_ratusan;

            $yearly_income_tax = 0;
            foreach (\App\Models\PayrollPPH::all() as $key => $value) {
                if (($taxable_yearly_income <= $value->batas_atas && $taxable_yearly_income >= $value->batas_bawah && $value->batas_atas != null) || ($taxable_yearly_income >= $value->batas_bawah && $value->batas_atas == null)) {
                    $yearly_income_tax += ($value->tarif / 100) * ($taxable_yearly_income - $value->batas_bawah);
                } else if ($taxable_yearly_income >= $value->batas_atas && $value->batas_atas != null) {
                    $yearly_income_tax += ($value->tarif / 100) * ($value->batas_atas - $value->batas_bawah);
                }
            }
            
            $params['yearly_income_tax']            = $yearly_income_tax;
        }

        return $params;
    }

    /**
     * [getKaryawan description]
     * @return [type] [description]
     */
    public function getKaryawan(Request $request)
    {
        $params = [];
        if($request->ajax())
        {
            $user = \Auth::user();
            if($user->project_id != NULL)
            {
                $data =  User::where('name', 'LIKE', "%". $request->name . "%")->where('project_id', $user->project_id)
                                ->orWhere('nik', 'LIKE', '%'. $request->name .'%')->where('project_id', $user->project_id)
                                ->get();
            } else{
                $data =  User::where('name', 'LIKE', "%". $request->name . "%")->orWhere('nik', 'LIKE', '%'. $request->name .'%')->get();
            }
            $params = [];
            foreach($data as $k => $item)
            {
                if($k >= 10) continue;

                $params[$k]['id'] = $item->id;
                $params[$k]['value'] = $item->nik .' - '. $item->name;
            }
        }

        return response()->json($params);
    }

    public function getPICAsset(Request $request){
        $type = AssetType::find($request->id);
        $approval = SettingApprovalClearance::where('nama_approval', $type->pic_department)->pluck('user_id')->toArray();
        $user = User::whereIn('id', $approval)->get();

        //dd($approval);
        return response()->json($user);
    }

    public function getKaryawanAsset(Request $request)
    {
        $params = [];
        if($request->ajax())
        {
            $user = \Auth::user();
            if($user->project_id != NULL)
            {
                $data =  User::where(function($query) use ($request) {
                    $query->where('name', 'LIKE', "%". $request->name . "%")->orWhere('nik', 'LIKE', '%'. $request->name .'%');
                })->where(function($query) {
                    $query->whereNull('non_active_date')->orWhere('non_active_date', '>', \Carbon\Carbon::now());
                })->where(function($query) {
                    $query->whereNull('join_date')->orWhere('join_date', '<=', \Carbon\Carbon::now());
                })->where('project_id', $user->project_id)
                ->get();
            } else{
                $data =  User::where(function($query) use ($request) {
                    $query->where('name', 'LIKE', "%". $request->name . "%")->orWhere('nik', 'LIKE', '%'. $request->name .'%');
                })->where(function($query) {
                    $query->whereNull('non_active_date')->orWhere('non_active_date', '>', \Carbon\Carbon::now());
                })->where(function($query) {
                    $query->whereNull('join_date')->orWhere('join_date', '<=', \Carbon\Carbon::now());
                })->get();
            }
            $params = [];
            foreach($data as $k => $item)
            {
                if($k >= 10) continue;

                $params[$k]['id'] = $item->id;
                $params[$k]['value'] = $item->nik .' - '. $item->name;
            }
        }

        return response()->json($params);
    }

   public function getAdministrator(Request $request)
    {
        $params = [];
         if($request->ajax())
        {
            $user = \Auth::user();
            if($user->project_id != NULL)
            {
                $data =  User::where('access_id', 2)->where('project_id', $user->project_id)->where(function($query){
                    $query->where('name', 'LIKE', "%". $request->name . "%")->orWhere('nik', 'LIKE', '%'. $request->name .'%');
                 })->get();

            } else{
                $data =  User::where('access_id', 2)->where(function($query){
                     $query->where('name', 'LIKE', "%". $request->name . "%")->orWhere('nik', 'LIKE', '%'. $request->name .'%');
                 })->get();
            }
            $params = [];
            foreach($data as $k => $item)
            {
                if($k >= 10) continue;

                $params[$k]['id'] = $item->id;
                $params[$k]['value'] =  $item->name;
            }
        }
        return response()->json($params);
    }



    /**
     * [getKaryawan description]
     * @return [type] [description]
     */
    public function getKaryawanPayroll(Request $request)
    {
        $params = [];
        if($request->ajax()) {
            $user = \Auth::user();
            $month = $request->month;
            $year  = $request->year;

            if ($user->project_id != null) {
                $cycle_list = \App\Models\PayrollCycle::where('project_id', $user->project_id)->get();
            } else {
                $cycle_list = \App\Models\PayrollCycle::whereNull('project_id')->get();
            }

            // if(!empty($month) && !empty($year)) {
            //     if ($user->project_id != null) {
            //         $cycle = \App\Models\PayrollCycle::where('project_id', $user->project_id)->where('key_name', 'payroll')->first();
            //     } else {
            //         $cycle = \App\Models\PayrollCycle::whereNull('project_id')->where('key_name', 'payroll')->first();
            //     }
                
            //     if ($cycle) {
            //         $end_date = fix_date($cycle->end_date, $month, $year);

            //         // Start bulan yang sama
            //         if ($cycle->start_date < $cycle->end_date) {
            //             $start_date = fix_date($cycle->start_date, $month, $year);
            //         }
            //         // Start bulan sebelumnya
            //         else {
            //             $prev = get_previous_month($month, $year);
            //             $start_date = fix_date($cycle->start_date, $prev['month'], $prev['year']);
            //         }
            //     }
            // }

            if($user->project_id != NULL) {
                $data =  User::where('project_id', $user->project_id)->where(function($table) use ($request) {
                    $table->where('name', 'LIKE', "%". $request->name . "%")->orWhere('nik', 'LIKE', '%'. $request->name .'%');
                })->get();
            } else {
                $data =  User::where(function($table) use ($request) {
                    $table->where('name', 'LIKE', "%". $request->name . "%")->orWhere('nik', 'LIKE', '%'. $request->name .'%');
                })->get();
            }

            $karyawan = [];
            foreach($data as $k => $i) {
                if(!empty($month) && !empty($year)) {
                    if ($i->payroll_cycle_id != null) {
                        $cycle = $cycle_list->where('id', $i->payroll_cycle_id)->first();
                    } else {
                        $cycle = $cycle_list->where('key_name', 'payroll')->first();
                    }
                    
                    if ($cycle) {
                        $end_date = fix_date($cycle->end_date, $month, $year);
    
                        // Start bulan yang sama
                        if ($cycle->start_date < $cycle->end_date) {
                            $start_date = fix_date($cycle->start_date, $month, $year);
                        }
                        // Start bulan sebelumnya
                        else {
                            $prev = get_previous_month($month, $year);
                            $start_date = fix_date($cycle->start_date, $prev['month'], $prev['year']);
                        }
                    }

                    // check join and resign date
                    if ($cycle) {
                        if(($i->join_date && \Carbon\Carbon::parse($i->join_date)->endOfDay() > \Carbon\Carbon::parse(date_format($end_date, "Y-m-d"))->endOfDay()) || ($i->non_active_date && \Carbon\Carbon::parse($i->non_active_date)->startOfDay() < \Carbon\Carbon::parse(date_format($start_date, "Y-m-d"))->startOfDay())) {
                            continue;
                        }
                    }

                    // check base payroll first,only show those who already has base payroll
                    $base_payroll = Payroll::where('user_id', $i->id)->first();
                    if(!$base_payroll){
                        continue;
                    }

                    $payroll = PayrollHistory::where('user_id', $i->id)->whereMonth('created_at',$month)->whereYear('created_at',$year)->first();
                } else {
                    $payroll = Payroll::where('user_id', $i->id)->first();
                }
                // existing user payroll skip
                if($payroll) continue;

                if($i->access_id == 3) continue; // jika bukan karyawan maka skip

                $karyawan[$k]['id']     = $i->id;
                $karyawan[$k]['value']  = $i->nik .' - '. $i->name;
            }
        }

        return response()->json($karyawan);
    }

 public function getKaryawanPayrollNet(Request $request)
    {
        $params = [];
        if($request->ajax())
        {
            $data =  \User::where('name', 'LIKE', "%". $request->name . "%")->orWhere('nik', 'LIKE', '%'. $request->name .'%')->get();

            $karyawan = [];
            foreach($data as $k => $i)
            {
                $payroll = PayrollNet::where('user_id', $i->id)->first();
                // existing user payroll skip
                if($payroll) continue;

                if($i->access_id == 3) continue; // jika bukan karyawan maka skip

                $karyawan[$k]['id']     = $i->id;
                $karyawan[$k]['value']  = $i->nik .' - '. $i->name;
            }
        }

        return response()->json($karyawan);
    }

    public function getKaryawanPayrollGross(Request $request)
    {
        $params = [];
        if($request->ajax())
        {
            $data =  User::where('name', 'LIKE', "%". $request->name . "%")->orWhere('nik', 'LIKE', '%'. $request->name .'%')->get();

            $karyawan = [];
            foreach($data as $k => $i)
            {
                $payroll = PayrollGross::where('user_id', $i->id)->first();
                // existing user payroll skip
                if($payroll) continue;

                if($i->access_id == 3) continue; // jika bukan karyawan maka skip

                $karyawan[$k]['id']     = $i->id;
                $karyawan[$k]['value']  = $i->nik .' - '. $i->name;
            }
        }

        return response()->json($karyawan);
    }

    /**
     * [getAirports description]
     * @return [type] [description]
     */
    public function getAirports(Request $request)
    {
        $params = [];
        if ($request->ajax()) {
            if (strlen($request->word) >= 2) {
                if ($request->type == 'Pesawat') {
                    $data = Airports::where('name', 'LIKE', "%" . $request->word . "%")->orWhere('code', 'LIKE', '%' . $request->word . '%')->orWhere('cityName', 'LIKE', '%' . $request->word . '%')->orWhere('countryName', 'LIKE', '%' . strtoupper($request->word) . '%')->groupBy('code')->limit(10)->get();
                } else if ($request->type == 'Kapal') {
                    $data = Seaports::where('name', 'LIKE', "%" . $request->word . "%")->orWhere('code', 'LIKE', '%' . $request->word . '%')->orWhere('cityName', 'LIKE', '%' . $request->word . '%')->orWhere('countryName', 'LIKE', '%' . strtoupper($request->word) . '%')->groupBy('code')->limit(10)->get();
                } else if ($request->type == 'Kereta') {
                    $data = Stations::where('name', 'LIKE', "%" . $request->word . "%")->orWhere('code', 'LIKE', '%' . $request->word . '%')->orWhere('cityName', 'LIKE', '%' . $request->word . '%')->orWhere('countryName', 'LIKE', '%' . strtoupper($request->word) . '%')->groupBy('code')->limit(10)->get();
                }
                $params = [];
                foreach ($data as $k => $item) {
                    $params[$k] = $item;
                    $params[$k]['value'] = $item->name . ' - ' . $item->cityName;
                }
            }
        }

        return response()->json($params);
    }

    public function getCity(Request $request)
    {
        $data = [];
        if($request->ajax())
        {
            if(strlen($request->word) >=2 )
            {
                $data =  Kabupaten::where('nama', 'LIKE', "%". $request->word . "%")->limit(10)->get();

                $params = [];
                foreach($data as $k => $item)
                {
                    $params[$k] = $item;
                    $params[$k]['value'] = $item->nama;
                }
            }
        }

        return response()->json($params);
    }

    public function getUniversity(Request $request)
    {
        $data = [];
        if($request->ajax())
        {
            if(strlen($request->word) >=2 )
            {
                $data =  Universitas::where('name', 'LIKE', "%". $request->word . "%")->limit(10)->get();

                $params = [];
                foreach($data as $k => $item)
                {
                    $params[$k] = $item;
                    $params[$k]['value'] = $item->name;
                }
            }
        }
        return response()->json($params);
    }


    /**
     * [getHistoryApprovalOvertime description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getHistoryApprovalMedical(Request $request)
    {
        if($request->ajax())
        {
            $data = MedicalReimbursement::where('id', $request->foreign_id)->first();

            $data->jenis_karyawan = strtolower(jabatan_level_user($data->user_id));

            if(isset($data->atasan->name))
            {
                $data->atasan_name = $data->atasan->nik .' - '. $data->atasan->name;
            }

            if(isset($data->direktur->name))
            {
                $data->direktur_name = $data->direktur->nik .' - '. $data->direktur->name;
            }


            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }

    /**
     * [getHistoryApprovalOvertime description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getHistoryApprovalOvertime(Request $request)
    {
        if($request->ajax())
        {
            $data = OvertimeSheet::where('id', $request->foreign_id)->first();

            $data->jenis_karyawan = strtolower(jabatan_level_user($data->user_id));

            if(isset($data->atasan->name))
            {
                $data->atasan_name = $data->atasan->nik .' - '. $data->atasan->name;
            }

            if(isset($data->direktur->name))
            {
                $data->direktur_name = $data->direktur->nik .' - '. $data->direktur->name;
            }


            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }

    /**
     * [getHistoryApprovalPaymentRequest description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function getHistoryApprovalPaymentRequest(Request $request)
    {
        if($request->ajax())
        {
            $data = PaymentRequest::where('id', $request->foreign_id)->first();
            $data->jenis_karyawan = strtolower(jabatan_level_user($data->user_id));

            if(isset($data->atasan->name))
            {
                $data->atasan_name = $data->atasan->nik .' - '. $data->atasan->name;
            }

            if(isset($data->direktur->name))
            {
                $data->direktur_name = $data->direktur->nik .' - '. $data->direktur->name;
            }

            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }

    /**
     * [getStatusApproval description]
     * @return [type] [description]
     */
    public function getHistoryApproval(Request $request)
    {
        if($request->ajax())
        {
            $data = StatusApproval::where('jenis_form', $request->jenis_form)->where('foreign_id', $request->foreign_id)->get();

            $obj = [];
            foreach($data as $key => $item)
            {
                $obj[$key] = $item;
                $obj[$key]['user_approval'] = $item->user_approval;
            }

            return response()->json(['message' => 'success', 'data' => $obj]);
        }

        return response()->json($this->respon);
    }

    /**
     * [getHistoryApprovalCuti description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getHistoryApprovalCuti(Request $request)
    {
        if($request->ajax())
        {
            $data = CutiKaryawan::where('id', $request->foreign_id)->first();

            $atasan = User::where('id', $data->approved_atasan_id)->first();
            $direktur = User::where('id', $data->approve_direktur_id)->first();

            $data->atasan = "";
            $data->jenis_karyawan = strtolower(jabatan_level_user($data->user_id));

            if(isset($atasan))
            {
                $data->atasan = $atasan->nik .' - '. $atasan->name;
            }

            if(isset($direktur))
            {
                $data->direktur = $direktur->nik .' - '. $direktur->name;
            }

            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }


    public function getHistoryApprovalLeaveCustom(Request $request)
    {
        if($request->ajax())
        {
            $data = CutiKaryawan::where('id', $request->foreign_id)->first();
            $history =[];
            $user = [];
           foreach ($data->historyApproval as $key => $value) {
                # code...
                $history[$key]['level']         = $value->level->name;
                $history[$key]['position']      = (isset($value->structure->position) ? $value->structure->position->name:'').(isset($value->structure->division) ? ' - '.$value->structure->division->name:'').(isset($value->structure->title) ? ' - '.$value->structure->title->name:'');
                $history[$key]['user']          = isset($value->userApproved)?$value->userApproved->name:'';
                $history[$key]['date']          = $value->date_approved;
                $history[$key]['is_approved']   = $value->is_approved;
                $history[$key]['is_withdrawal'] = $value->is_withdrawal;
                $history[$key]['note']          = $value->note;
                $dataUser = user_approval_custom($value->structure_organization_custom_id);
                if($value->userApprovedClaim == null)
                {
                    foreach ($dataUser as $k => $v) {
                        $user[$key]['child'][$k]['name']         = $v->name;
                    }
                }
            }

            return response()->json(['message' => 'success', 'data' => $data, 'history'=> $history, 'user'=>$user]);
        }

        return response()->json($this->respon);
    }

    public function getHistoryApprovalPaymentRequestCustom(Request $request)
    {
        if($request->ajax())
        {
            $data = PaymentRequest::where('id', $request->foreign_id)->first();
            $history =[];
            $user = [];
           foreach ($data->historyApproval as $key => $value) {
                # code...
                $history[$key]['level']         = $value->level->name;
                $history[$key]['position']      = (isset($value->structure->position) ? $value->structure->position->name:'').(isset($value->structure->division) ? ' - '.$value->structure->division->name:'').(isset($value->structure->title) ? ' - '.$value->structure->title->name:'');
                $history[$key]['user']          = isset($value->userApproved)?$value->userApproved->name:'';
                $history[$key]['date']          = $value->date_approved;
                $history[$key]['is_approved']   = $value->is_approved;
                $history[$key]['note']          = $value->note;
                $dataUser = user_approval_custom($value->structure_organization_custom_id);
                if($value->userApprovedClaim == null)
                {
                    foreach ($dataUser as $k => $v) {
                        $user[$key]['child'][$k]['name']         = $v->name;
                    }
                }
            }

            return response()->json(['message' => 'success', 'data' => $data, 'history'=> $history, 'user'=>$user]);
        }

        return response()->json($this->respon);
    }

    public function getHistoryApprovalCashAdvance(Request $request)
    {
        if($request->ajax())
        {
            $data = CashAdvance::where('id', $request->foreign_id)->first();
            $history =[];
            $user = [];
           foreach ($data->historyApproval as $key => $value) {
                # code...
                $history[$key]['level']         = $value->level->name;
                $history[$key]['position']      = (isset($value->structure->position) ? $value->structure->position->name:'').(isset($value->structure->division) ? ' - '.$value->structure->division->name:'').(isset($value->structure->title) ? ' - '.$value->structure->title->name:'');
                $history[$key]['user']          = isset($value->userApproved)?$value->userApproved->name:'';
                $history[$key]['date']          = $value->date_approved;
                $history[$key]['is_approved']   = $value->is_approved;
                $history[$key]['note']          = $value->note;
                $dataUser = user_approval_custom($value->structure_organization_custom_id);
                if($value->userApprovedClaim == null)
                {
                    foreach ($dataUser as $k => $v) {
                        $user[$key]['child'][$k]['name']         = $v->name;
                    }
                }
            }

            return response()->json(['message' => 'success', 'data' => $data, 'history'=> $history, 'user'=>$user]);
        }

        return response()->json($this->respon);
    }

    public function getHistoryApprovalCashAdvanceClaim(Request $request)
    {
        if($request->ajax())
        {
            $data = CashAdvance::where('id', $request->foreign_id)->first();
            $history =[];
            $user=[];
           foreach ($data->historyApproval as $key => $value) {
                # code...
                $history[$key]['level']         = $value->level->name;
                $history[$key]['position']      = (isset($value->structure->position) ? $value->structure->position->name:'').(isset($value->structure->division) ? ' - '.$value->structure->division->name:'').(isset($value->structure->title) ? ' - '.$value->structure->title->name:'');
                $history[$key]['user']          = isset($value->userApprovedClaim)?$value->userApprovedClaim->name:'';
                $history[$key]['date']          = $value->date_approved_claim;
                $history[$key]['is_approved']   = $value->is_approved_claim;
                $history[$key]['note']          = $value->note_claim;
                $dataUser = user_approval_custom($value->structure_organization_custom_id);
                if($value->userApprovedClaim == null)
                {
                    foreach ($dataUser as $k => $v) {
                        $user[$key]['child'][$k]['name']         = $v->name;
                    }
                }
            }
            return response()->json(['message' => 'success', 'data' => $data, 'history'=> $history, 'user'=>$user]);
        }

        return response()->json($this->respon);
    }

    public function getHistoryApprovalTimesheetCustom(Request $request)
    {
        if($request->ajax())
        {
            $data = TimesheetPeriod::where('id', $request->foreign_id)->first();
            $history = [];
            $user = [];
            if ($data->status != 4) {
                foreach ($data->timesheetPeriodTransaction as $key => $value) {
                    $history[$key]['level']         = $value->timesheetCategory->name;
                    $history[$key]['position']      = $value->date.", ".$value->start_time." - ".$value->end_time;
                    $history[$key]['user']          = isset($value->approval_id) ? $value->userApproved->name : '';
                    $history[$key]['date']          = $value->date_approved;
                    $history[$key]['is_approved']   = $value->status == 2 ? 1 : ($value->status == 3 ? 0 : null);
                    $history[$key]['note']          = $value->approval_note;
                    $dataUser = $value->timesheetCategory->settingApproval;
                    if($value->status == 1)
                    {
                        if ($dataUser->count()) {
                            foreach ($dataUser as $k => $v) {
                                $user[$key]['child'][$k]['name']         = $v->user->name;
                            }
                        } else {
                            $user[$key]['child'][0]['name']         = 'Without approver';
                        }
                    }
                }
            }

            return response()->json(['message' => 'success', 'data' => $data, 'history' => $history, 'user' => $user]);
        }

        return response()->json($this->respon);
    }

    public function getHistoryApprovalOvertimeCustom(Request $request)
    {
        if($request->ajax())
        {
            $data = OvertimeSheet::where('id', $request->foreign_id)->first();
            $history =[];
            $user = [];
           foreach ($data->historyApproval as $key => $value) {
                # code...
                $history[$key]['level']         = $value->level->name;
                $history[$key]['position']      = (isset($value->structure->position) ? $value->structure->position->name:'').(isset($value->structure->division) ? ' - '.$value->structure->division->name:'').(isset($value->structure->title) ? ' - '.$value->structure->title->name:'');
                $history[$key]['user']          = isset($value->userApproved)?$value->userApproved->name:'';
                $history[$key]['date']          = $value->date_approved;
                $history[$key]['is_approved']   = $value->is_approved;
                $history[$key]['note']          = $value->note;
                $dataUser = user_approval_custom($value->structure_organization_custom_id);
                if($value->userApprovedClaim == null)
                {
                    foreach ($dataUser as $k => $v) {
                        $user[$key]['child'][$k]['name']         = $v->name;
                    }
                }
            }

            return response()->json(['message' => 'success', 'data' => $data, 'history'=> $history, 'user'=>$user]);
        }

        return response()->json($this->respon);
    }

    public function getHistoryApprovalOvertimeClaimCustom(Request $request)
    {
        if($request->ajax())
        {
            $data = OvertimeSheet::where('id', $request->foreign_id)->first();
            $history =[];
            $user=[];
           foreach ($data->historyApproval as $key => $value) {
                # code...
                $history[$key]['level']         = $value->level->name;
                $history[$key]['position']      = (isset($value->structure->position) ? $value->structure->position->name:'').(isset($value->structure->division) ? ' - '.$value->structure->division->name:'').(isset($value->structure->title) ? ' - '.$value->structure->title->name:'');
                $history[$key]['user']          = isset($value->userApprovedClaim)?$value->userApprovedClaim->name:'';
                $history[$key]['date']          = $value->date_approved_claim;
                $history[$key]['is_approved']   = $value->is_approved_claim;
                $history[$key]['note']          = $value->note_claim;
                $dataUser = user_approval_custom($value->structure_organization_custom_id);
                if($value->userApprovedClaim == null)
                {
                    foreach ($dataUser as $k => $v) {
                        $user[$key]['child'][$k]['name']         = $v->name;
                    }
                }
            }
            return response()->json(['message' => 'success', 'data' => $data, 'history'=> $history, 'user'=>$user]);
        }

        return response()->json($this->respon);
    }

    public function getHistoryApprovalTrainingCustom(Request $request)
    {
        if($request->ajax())
        {
            $data = Training::where('id', $request->foreign_id)->first();
            $history =[];
            $user = [];
           foreach ($data->historyApproval as $key => $value) {
                # code...
                $history[$key]['level']         = $value->level->name;
                $history[$key]['position']      = (isset($value->structure->position) ? $value->structure->position->name:'').(isset($value->structure->division) ? ' - '.$value->structure->division->name:'').(isset($value->structure->title) ? ' - '.$value->structure->title->name:'');
                $history[$key]['user']          = isset($value->userApproved)?$value->userApproved->name:'';
                $history[$key]['date']          = $value->date_approved;
                $history[$key]['is_approved']   = $value->is_approved;
                $history[$key]['note']          = $value->note;
                $dataUser = user_approval_custom($value->structure_organization_custom_id);
                if($value->userApprovedClaim == null)
                {
                    foreach ($dataUser as $k => $v) {
                        $user[$key]['child'][$k]['name']        = $v->name;
                    }
                }
            }
            return response()->json(['message' => 'success', 'data' => $data, 'history'=> $history, 'user'=>$user]);
        }

        return response()->json($this->respon);
    }
    public function getHistoryApprovalTrainingClaimCustom(Request $request)
    {
        if($request->ajax())
        {
            $data = Training::where('id', $request->foreign_id)->first();
            $history =[];
            $user = [];
           foreach ($data->historyApproval as $key => $value) {
                # code...
                $history[$key]['level']         = $value->level->name;
                $history[$key]['position']      = (isset($value->structure->position) ? $value->structure->position->name:'').(isset($value->structure->division) ? ' - '.$value->structure->division->name:'').(isset($value->structure->title) ? ' - '.$value->structure->title->name:'');
                $history[$key]['user']          = isset($value->userApprovedClaim)?$value->userApprovedClaim->name:'';
                $history[$key]['date']          = $value->date_approved_claim;
                $history[$key]['is_approved']   = $value->is_approved_claim;
                $history[$key]['note']          = $value->note_claim;
                $dataUser = user_approval_custom($value->structure_organization_custom_id);
                if($value->userApprovedClaim == null)
                {
                    foreach ($dataUser as $k => $v) {
                        $user[$key]['child'][$k]['name']         = $v->name;
                    }
                }
            }
            return response()->json(['message' => 'success', 'data' => $data, 'history'=> $history, 'user'=>$user]);
        }

        return response()->json($this->respon);
    }
    public function getHistoryApprovalMedicalCustom(Request $request)
    {
        if($request->ajax())
        {
            $data = MedicalReimbursement::where('id', $request->foreign_id)->first();
            $history =[];
            $user =[];
           foreach ($data->historyApproval as $key => $value) {
                # code...
                $history[$key]['level']         = $value->level->name;
                $history[$key]['position']      = (isset($value->structure->position) ? $value->structure->position->name:'').(isset($value->structure->division) ? ' - '.$value->structure->division->name:'').(isset($value->structure->title) ? ' - '.$value->structure->title->name:'');
                $history[$key]['user']          = isset($value->userApproved)?$value->userApproved->name:'';
                $history[$key]['date']          = $value->date_approved;
                $history[$key]['is_approved']   = $value->is_approved;
                $history[$key]['note']          = $value->note;
                $dataUser = user_approval_custom($value->structure_organization_custom_id);
                if($value->userApprovedClaim == null)
                {
                    foreach ($dataUser as $k => $v) {
                        $user[$key]['child'][$k]['name']         = $v->name;
                    }
                }
            }

            return response()->json(['message' => 'success', 'data' => $data, 'history'=> $history,'user'=>$user]);
        }

        return response()->json($this->respon);
    }

    public function getHistoryApprovalLoanCustom(Request $request)
    {
        if($request->ajax())
        {
            $data = Loan::where('id', $request->foreign_id)->with(['asset', 'receiptApprover', 'physicalApprover', 'loanApprover'])->first();
            $history =[];
            $user =[];
            $admin = getAdminByModule(33);
           foreach ($data->historyApproval as $key => $value) {
                # code...
                $history[$key]['level']         = $value->level->name;
                $history[$key]['position']      = (isset($value->structure->position) ? $value->structure->position->name:'').(isset($value->structure->division) ? ' - '.$value->structure->division->name:'').(isset($value->structure->title) ? ' - '.$value->structure->title->name:'');
                $history[$key]['user']          = isset($value->userApproved)?$value->userApproved->name:'';
                $history[$key]['date']          = $value->date_approved;
                $history[$key]['is_approved']   = $value->is_approved;
                $history[$key]['note']          = $value->note;
                $dataUser = user_approval_custom($value->structure_organization_custom_id);
                if($value->userApprovedClaim == null)
                {
                    foreach ($dataUser as $k => $v) {
                        $user[$key]['child'][$k]['name']         = $v->name;
                    }
                }
            }

            return response()->json(['message' => 'success', 'data' => $data, 'history'=> $history,'user'=>$user,'admin'=>$admin]);
        }

        return response()->json($this->respon);
    }

    public function getHistoryApprovalExitCustom(Request $request)
    {
        if($request->ajax())
        {
            $data = ExitInterview::where('id', $request->foreign_id)->first();
            $history =[];
            $user=[];
           foreach ($data->historyApproval as $key => $value) {
                # code...
                $history[$key]['level']         = $value->level->name;
                $history[$key]['position']      = (isset($value->structure->position) ? $value->structure->position->name:'').(isset($value->structure->division) ? ' - '.$value->structure->division->name:'').(isset($value->structure->title) ? ' - '.$value->structure->title->name:'');
                $history[$key]['user']          = isset($value->userApproved)?$value->userApproved->name:'';
                $history[$key]['date']          = $value->date_approved;
                $history[$key]['is_approved']   = $value->is_approved;
                $history[$key]['note']          = $value->note;
                $dataUser = user_approval_custom($value->structure_organization_custom_id);
                if($value->userApprovedClaim == null)
                {
                    foreach ($dataUser as $k => $v) {
                        $user[$key]['child'][$k]['name']         = $v->name;
                    }
                }
            }

            return response()->json(['message' => 'success', 'data' => $data, 'history'=> $history,'user'=>$user]);
        }

        return response()->json($this->respon);
    }

    public function getHistoryApprovalClearanceCustom(Request $request)
    {
        info($request->all());
        if($request->ajax())
        {
            $data = ExitInterview::where('id', $request->foreign_id)->first();

            $history =[];
            $user=[];
            foreach ($data->assets as $key => $value) {

                $history[$key]['level']         = $value->asset->asset_name;
                $history[$key]['position']      = $value->asset->asset_type->pic_department;
                $history[$key]['user']          = isset($value->userApproved)?$value->userApproved->name:'';
                $history[$key]['date']          = $value->date_approved;
                $history[$key]['is_approved']   = $value->approval_check;
                $history[$key]['note']          = $value->catatan;
                $dataSetting = SettingApprovalClearance::where('nama_approval',$value->asset->asset_type->pic_department)->get();
                if($value->approval_check == null)
                {
                    foreach ($dataSetting as $k => $v) {
                        if ($v->user) {
                            $user[$key]['child'][$k]['name']         = $v->user->name;
                        }
                    }
                }
            }

            return response()->json(['message' => 'success', 'data' => $data, 'history'=> $history, 'user'=>$user]);
        }
        return response()->json($this->respon);
    }

    public function getHistoryApprovalFacilities(Request $request)
    {
        info($request->all());
        if($request->ajax())
        {
            $history =[];
            $user=[];
            $data = AssetTracking::with(['asset', 'historyApproval', 'historyApproval.userApproved'])->where('id', $request->foreign_id)->first();
            if($data->historyApproval != null){    
                $history[0]['level']         = $data->asset->asset_name;
                $history[0]['position']      = $data->asset->asset_type->pic_department;
                $history[0]['user']          = $data->historyApproval->userApproved != null ? $data->historyApproval->userApproved->name:'';
                $history[0]['date']          = $data->historyApproval->date_approved;
                $history[0]['is_approved']   = $data->historyApproval->is_approved;
                $history[0]['note']          = $data->historyApproval->note;
                $dataSetting = SettingApprovalClearance::where('nama_approval',$data->asset->asset_type->pic_department)->get();
            }
            else
            {
                $dataSetting = SettingApprovalClearance::where('nama_approval',$data->asset->asset_type->pic_department)->get();
                foreach ($dataSetting as $k => $v) {
                    if ($v->user) {
                        $user[0]['child'][$k]['nik']         = $v->user->nik;
                        $user[0]['child'][$k]['name']         = $v->user->name;
                    }
                }
            }
            
            return response()->json(['message' => 'success', 'data' => $data, 'history'=> $history, 'user'=>$user]);
        }
        return response()->json($this->respon);
    }


    public function getDetailSettingApprovalLeaveItem(Request $request)
    {
        if($request->ajax())
        {
            $all = SettingApprovalLeaveItem::where('setting_approval_id', $request->foreign_id)->orderBy('setting_approval_level_id','asc')->get();
            $data =[];
            foreach ($all as $key => $value) {
                # code...
                $data[$key]['level']         = $value->ApprovalLevel->name;
                $data[$key]['position']      = $value->structureApproval->position->name.($value->structureApproval->division ? ' - '.$value->structureApproval->division->name : '').($value->structureApproval->title ? ' - '.$value->structureApproval->title->name : '');
            }
            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }

    public function getDetailSettingApprovalPaymentRequestItem(Request $request)
    {
        if($request->ajax())
        {
            $all = SettingApprovalPaymentRequestItem::where('setting_approval_id', $request->foreign_id)->orderBy('setting_approval_level_id','asc')->get();
            $data =[];
            foreach ($all as $key => $value) {
                # code...
                $data[$key]['level']         = $value->ApprovalLevel->name;
                $data[$key]['position']      = $value->structureApproval->position->name.($value->structureApproval->division ? ' - '.$value->structureApproval->division->name : '').($value->structureApproval->title ? ' - '.$value->structureApproval->title->name : '');
            }
            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }

    public function getDetailSettingApprovalCashAdvanceItem(Request $request)
    {
        if($request->ajax())
        {
            $all = SettingApprovalCashAdvanceItem::where('setting_approval_id', $request->foreign_id)->orderBy('setting_approval_level_id','asc')->get();
            $data =[];
            foreach ($all as $key => $value) {
                # code...
                $data[$key]['level']         = $value->ApprovalLevel->name;
                $data[$key]['position']      = $value->structureApproval->position->name.($value->structureApproval->division ? ' - '.$value->structureApproval->division->name : '').($value->structureApproval->title ? ' - '.$value->structureApproval->title->name : '');
            }
            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }

    public function getDetailSettingApprovalRecruitmentRequestItem(Request $request)
    {
        if($request->ajax())
        {
            $all = SettingApprovalRecruitmentItem::where('setting_approval_id', $request->foreign_id)->orderBy('setting_approval_level_id','asc')->get();
            $data =[];
            foreach ($all as $key => $value) {
                # code...
                $data[$key]['level']         = $value->ApprovalLevel->name;
                $data[$key]['position']      = $value->structureApproval->position->name.($value->structureApproval->division ? ' - '.$value->structureApproval->division->name : '').($value->structureApproval->title ? ' - '.$value->structureApproval->title->name : '');
            }
            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }

    public function getDetailSettingApprovalTimesheetItem(Request $request)
    {
        if($request->ajax())
        {
            $all = SettingApprovalTimesheetItem::where('setting_approval_id', $request->foreign_id)->orderBy('setting_approval_level_id','asc')->get();
            $data =[];
            foreach ($all as $key => $value) {
                # code...
                $data[$key]['level']         = $value->ApprovalLevel->name;
                $data[$key]['position']      = $value->structureApproval->position->name.($value->structureApproval->division ? ' - '.$value->structureApproval->division->name : '').($value->structureApproval->title ? ' - '.$value->structureApproval->title->name : '');
            }
            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }
    public function getDetailSettingApprovalOvertimeItem(Request $request)
    {
        if($request->ajax())
        {
            $all = SettingApprovalOvertimeItem::where('setting_approval_id', $request->foreign_id)->orderBy('setting_approval_level_id','asc')->get();
            $data =[];
            foreach ($all as $key => $value) {
                # code...
                $data[$key]['level']         = $value->ApprovalLevel->name;
                $data[$key]['position']      = $value->structureApproval->position->name.($value->structureApproval->division ? ' - '.$value->structureApproval->division->name : '').($value->structureApproval->title ? ' - '.$value->structureApproval->title->name : '');
            }
            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }
    public function getDetailSettingApprovalTrainingItem(Request $request)
    {
        if($request->ajax())
        {
            $all = SettingApprovalTrainingItem::where('setting_approval_id', $request->foreign_id)->orderBy('setting_approval_level_id','asc')->get();
            $data =[];
            foreach ($all as $key => $value) {
                # code...
                $data[$key]['level']         = $value->ApprovalLevel->name;
                $data[$key]['position']      = $value->structureApproval->position->name.($value->structureApproval->division ? ' - '.$value->structureApproval->division->name : '').($value->structureApproval->title ? ' - '.$value->structureApproval->title->name : '');
            }
            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }
    public function getDetailSettingApprovalMedicalItem(Request $request)
    {
        if($request->ajax())
        {
            $all = SettingApprovalMedicalItem::where('setting_approval_id', $request->foreign_id)->orderBy('setting_approval_level_id','asc')->get();
            $data =[];
            foreach ($all as $key => $value) {
                # code...
                $data[$key]['level']         = $value->ApprovalLevel->name;
                $data[$key]['position']      = $value->structureApproval->position->name.($value->structureApproval->division ? ' - '.$value->structureApproval->division->name : '').($value->structureApproval->title ? ' - '.$value->structureApproval->title->name : '');
            }
            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }
    public function getDetailSettingApprovalLoanItem(Request $request)
    {
        if($request->ajax())
        {
            $all = SettingApprovalLoanItem::where('setting_approval_id', $request->foreign_id)->orderBy('setting_approval_level_id','asc')->get();
            $data =[];
            foreach ($all as $key => $value) {
                # code...
                $data[$key]['level']         = $value->ApprovalLevel->name;
                $data[$key]['position']      = $value->structureApproval->position->name.($value->structureApproval->division ? ' - '.$value->structureApproval->division->name : '').($value->structureApproval->title ? ' - '.$value->structureApproval->title->name : '');
            }
            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }
    public function getDetailSettingApprovalExitItem(Request $request)
    {
        if($request->ajax())
        {
            $all = SettingApprovalExitItem::where('setting_approval_id', $request->foreign_id)->orderBy('setting_approval_level_id','asc')->get();
            $data =[];
            foreach ($all as $key => $value) {
                # code...
                $data[$key]['level']         = $value->ApprovalLevel->name;
                $data[$key]['position']      = $value->structureApproval->position->name.($value->structureApproval->division ? ' - '.$value->structureApproval->division->name : '').($value->structureApproval->title ? ' - '.$value->structureApproval->title->name : '');
            }
            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }


    /**
     * [getHistoryApprovalCuti description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getHistoryApprovalExit(Request $request)
    {
        if($request->ajax())
        {
            $data = ExitInterview::where('id', $request->foreign_id)->first();
            $data->jenis_karyawan = strtolower(jabatan_level_user($data->user_id));

            if(isset($data->atasan->name))
            {
                $data->atasan_name = $data->atasan->nik .' - '. $data->atasan->name;
            }

            if(isset($data->direktur->name))
            {
                $data->direktur_name = $data->direktur->nik .' - '. $data->direktur->name;
            }


            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }

    /**
     * [getHistoryApprovalCuti description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getHistoryApprovalTraining(Request $request)
    {
        if($request->ajax())
        {
            $data       = Training::where('id', $request->foreign_id)->first();
            $atasan     = User::where('id', $data->approved_atasan_id)->first();
            $direktur   = User::where('id', $data->approve_direktur_id)->first();

            $data->atasan = "";

            $data->jenis_karyawan = strtolower(jabatan_level_user($data->user_id));

            if(isset($atasan))
            {
                $data->atasan = $atasan->nik .' - '. $atasan->name;
            }

            if(isset($direktur))
            {
                $data->direktur = $direktur->nik .' - '. $direktur->name;
            }

            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }

    /**
     * [getHistoryApprovalTrainingBill description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getHistoryApprovalTrainingBill(Request $request)
    {
        if($request->ajax())
        {
            $data       = Training::where('id', $request->foreign_id)->first();
            $atasan     = User::where('id', $data->approved_atasan_id)->first();
            $direktur   = User::where('id', $data->approve_direktur_id)->first();

            $data->atasan = "";

            if(!empty($data->user->empore_organisasi_staff_id))
            {
                $data->jenis_karyawan = 'staff';
            }

            if(empty($data->user->empore_organisasi_staff_id) and !empty($data->user->empore_organisasi_manager_id))
            {
                $data->jenis_karyawan = 'manager';
            }

            if(isset($atasan))
            {
                $data->atasan = $atasan->nik .' - '. $atasan->name;
            }

            if(isset($direktur))
            {
                $data->direktur = $direktur->nik .' - '. $direktur->name;
            }

            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }

    /**
     * [addSettingOvertimeHrOperation description]
     * @param Request $request [description]
     */
    public function addSettingTrainingGaDepartment(Request $request)
    {
        if($request->ajax())
        {
            $data               = new SettingApproval();
            $data->jenis_form   = 'training_mengetahui';
            $data->user_id      = $request->id;
            $data->nama_approval= 'GA Department';
            $data->save();

            Session::flash('message-success', 'User Approval berhasil di tambahkan');

            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }

    /**
     * [addSettingOvertimeManagerDepartment description]
     * @param Request $request [description]
     */
    public function addSettingOvertimeManagerDepartment(Request $request)
    {
        if($request->ajax())
        {
            $data               = new SettingApproval();
            $data->jenis_form   = 'overtime';
            $data->user_id      = $request->id;
            $data->nama_approval= 'Manager Department';
            $data->save();

            Session::flash('message-success', 'User Approval berhasil di tambahkan');

            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }

    /**
     * [addSettingOvertimeManagerDepartment description]
     * @param Request $request [description]
     */
    public function addSettingExitHRD(Request $request)
    {
        if($request->ajax())
        {
            $data               = new SettingApproval();
            $data->jenis_form   = 'exit_clearance';
            $data->user_id      = $request->id;
            $data->nama_approval= 'HRD';
            $data->save();

            Session::flash('message-success', 'User Approval berhasil di tambahkan');

            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }

    /**
     * [addSettingOvertimeManagerDepartment description]
     * @param Request $request [description]
     */
    public function addSettingExitGA(Request $request)
    {
        if($request->ajax())
        {
            $data               = new SettingApproval();
            $data->jenis_form   = 'exit_clearance';
            $data->user_id      = $request->id;
            $data->nama_approval= 'GA';
            $data->save();

            Session::flash('message-success', 'User Approval berhasil di tambahkan');

            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }

    /**
     * [addSettingOvertimeManagerDepartment description]
     * @param Request $request [description]
     */
    public function addSettingExitIT(Request $request)
    {
        if($request->ajax())
        {
            $data               = new SettingApproval();
            $data->jenis_form   = 'exit_clearance';
            $data->user_id      = $request->id;
            $data->nama_approval= 'IT';
            $data->save();

            Session::flash('message-success', 'User Approval berhasil di tambahkan');

            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }

    /**
     * [addSettingOvertimeManagerDepartment description]
     * @param Request $request [description]
     */
    public function addSettingExitAccounting(Request $request)
    {
        if($request->ajax())
        {
            $data               = new SettingApproval();
            $data->jenis_form   = 'exit_clearance';
            $data->user_id      = $request->id;
            $data->nama_approval= 'Accounting';
            $data->save();

            Session::flash('message-success', 'User Approval berhasil di tambahkan');

            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }

    /**
     * [addSettingOvertimeHrOperation description]
     * @param Request $request [description]
     */
    public function addSettingTrainingHRD(Request $request)
    {
        if($request->ajax())
        {
            $data               = new SettingApproval();
            $data->jenis_form   = 'training';
            $data->user_id      = $request->id;
            $data->nama_approval= 'HRD';
            $data->save();

            Session::flash('message-success', 'User Approval berhasil di tambahkan');

            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }

     /**
     * [addSettingOvertimeHrOperation description]
     * @param Request $request [description]
     */
    public function addSettingTrainingFinance(Request $request)
    {
        if($request->ajax())
        {
            $data               = new SettingApproval();
            $data->jenis_form   = 'training';
            $data->user_id      = $request->id;
            $data->nama_approval= 'Finance';
            $data->save();

            Session::flash('message-success', 'User Approval berhasil di tambahkan');

            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }

    /**
     * [addSettingOvertimeHrOperation description]
     * @param Request $request [description]
     */
    public function addSettingOvertimeHrOperation(Request $request)
    {
        if($request->ajax())
        {
            $data               = new SettingApproval();
            $data->jenis_form   = 'overtime';
            $data->user_id      = $request->id;
            $data->nama_approval= 'HR Operation';
            $data->save();

            Session::flash('message-success', 'User Approval berhasil di tambahkan');

            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }

    /**
     * [addSettingOvertimeHrOperation description]
     * @param Request $request [description]
     */
    public function addSettingExitHrDirector(Request $request)
    {
        if($request->ajax())
        {
            $data               = new SettingApproval();
            $data->jenis_form   = 'exit';
            $data->user_id      = $request->id;
            $data->nama_approval= 'HR Director';
            $data->save();

            Session::flash('message-success', 'User Approval berhasil di tambahkan');

            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }

    /**
     * [addSettingExitHRGM description]
     * @param Request $request [description]
     */
    public function addSettingExitHRGM(Request $request)
    {
        if($request->ajax())
        {
            $data               = new SettingApproval();
            $data->jenis_form   = 'exit';
            $data->user_id      = $request->id;
            $data->nama_approval= 'HR GM';
            $data->save();

            Session::flash('message-success', 'User Approval berhasil di tambahkan');

            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }

    /**
     * [addSettingExitHrManager description]
     * @param Request $request [description]
     */
    public function addSettingExitHrManager(Request $request)
    {
        if($request->ajax())
        {
            $data               = new SettingApproval();
            $data->jenis_form   = 'exit';
            $data->user_id      = $request->id;
            $data->nama_approval= 'HR Manager';
            $data->save();

            Session::flash('message-success', 'User Approval berhasil di tambahkan');

            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }

    /**
     * [addSettingOvertimeManagerHR description]
     * @param Request $request [description]
     */
    public function addSettingOvertimeManagerHR(Request $request)
    {
        if($request->ajax())
        {
            $data               = new SettingApproval();
            $data->jenis_form   = 'overtime';
            $data->user_id      = $request->id;
            $data->nama_approval= 'Manager HR';
            $data->save();

            Session::flash('message-success', 'User Approval berhasil di tambahkan');

            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }

    /**
     * [addSettingMedicalGMHR description]
     * @param Request $request [description]
     */
    public function addSettingMedicalGMHR(Request $request)
    {
        if($request->ajax())
        {
            $data               = new SettingApproval();
            $data->jenis_form   = 'medical';
            $data->user_id      = $request->id;
            $data->nama_approval= 'GM HR';
            $data->save();

            Session::flash('message-success', 'User Approval berhasil di tambahkan');

            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }

    /**
     * [addSettingMedicalManagerHR description]
     * @param Request $request [description]
     */
    public function addSettingMedicalManagerHR(Request $request)
    {
        if($request->ajax())
        {
            $data               = new SettingApproval();
            $data->jenis_form   = 'medical';
            $data->user_id      = $request->id;
            $data->nama_approval= 'Manager HR';
            $data->save();

            Session::flash('message-success', 'User Approval berhasil di tambahkan');

            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }

    /**
     * [addSettingMedicalHRBenefit description]
     * @param Request $request [description]
     */
    public function addSettingMedicalHRBenefit(Request $request)
    {
        if($request->ajax())
        {
            $data               = new SettingApproval();
            $data->jenis_form   = 'medical';
            $data->user_id      = $request->id;
            $data->nama_approval= 'HR Benefit';
            $data->save();

            Session::flash('message-success', 'User Approval berhasil di tambahkan');

            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }

    /**
     * [addInvetarisMobil description]
     * @param Request $request [description]
     */
    public function addInvetarisMobil(Request $request)
    {
        if($request->ajax())
        {
            $data               = new UserInvetarisMobil();
            $data->user_id      = $request->user_id;
            $data->tipe_mobil   = $request->tipe_mobil;
            $data->tahun        = $request->tahun;
            $data->no_polisi    = $request->no_polisi;
            $data->status_mobil = $request->status_mobil;
            $data->save();

            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }

    /**
     * [addtSettingPaymentRequestApproval description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function addSettingPaymentRequestApproval(Request $request)
    {
        if($request->ajax())
        {
            $data               = new SettingApproval();
            $data->jenis_form   = 'payment_request';
            $data->user_id      = $request->id;
            $data->nama_approval= 'Approval';
            $data->save();

            Session::flash('message-success', 'User Approval berhasil di tambahkan');

            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }


    /**
     * [addtSettingPaymentRequestApproval description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function addtSettingPaymentRequestVerification(Request $request)
    {
        if($request->ajax())
        {
            $data               = new SettingApproval();
            $data->jenis_form   = 'payment_request';
            $data->user_id      = $request->id;
            $data->nama_approval= 'Verification';
            $data->save();

            Session::flash('message-success', 'User Approval berhasil di tambahkan');

            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }


    /**
     * [addtSettingPaymentRequestApproval description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function addtSettingPaymentRequestPayment(Request $request)
    {
        if($request->ajax())
        {
            $data               = new SettingApproval();
            $data->jenis_form   = 'payment_request';
            $data->user_id      = $request->id;
            $data->nama_approval= 'Payment';
            $data->save();

            Session::flash('message-success', 'User Approval berhasil di tambahkan');

            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }

    /**
     * [addtSettingCutiPersonalia description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function addtSettingCutiPersonalia(Request $request)
    {
        if($request->ajax())
        {
            $data               = new SettingApproval();
            $data->jenis_form   = 'cuti';
            $data->user_id      = $request->id;
            $data->nama_approval= 'Personalia';
            $data->save();

            Session::flash('message-success', 'User Approval berhasil di tambahkan');

            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }

    /**
     * [addtSettingCutiAtasan description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function addtSettingCutiAtasan(Request $request)
    {
        if($request->ajax())
        {
            $data               = new SettingApproval();
            $data->jenis_form   = 'cuti';
            $data->user_id      = $request->id;
            $data->nama_approval= 'Atasan';
            $data->save();

            Session::flash('message-success', 'User Approval berhasil di tambahkan');

            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }

    /**
     * [getKaryawanById description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getKaryawanById(Request $request)
    {
        if($request->ajax())
        {
            $data = User::where('id', $request->id)->with(['payrollCycle', 'attendanceCycle'])->first();

            if(empty($data->foto))
            {
                if($data->jenis_kelamin == 'Male' || $data->jenis_kelamin == "")
                {
                    $data->foto = asset('images/user-man.png');
                }
                else
                {
                    $data->foto = asset('images/user-woman.png');
                }
            }
            else
            {
                if(\File::exists('storage/foto/'.$data->foto))
                {
                    $data->foto = asset('storage/foto/'.$data->foto);
                }
                else
                {
                    if($data->jenis_kelamin == 'Male' || $data->jenis_kelamin == "")
                    {
                        $data->foto = asset('images/user-man.png');
                    }
                    else
                    {
                        $data->foto = asset('images/user-woman.png');
                    }
                }
            }

            $data->department_name  = isset($data->department) ? $data->department->name : '';
            $data->cabang_name      = isset($data->cabang->name) ? $data->cabang->name : '';

            $data->dependent    = UserFamily::where('user_id', $data->id)->get();
            $data->jabatan      = empore_jabatan($request->id);
            $data->position    = (isset($data->structure->position) ? $data->structure->position->name:'').(isset($data->structure->division) ? ' - '.$data->structure->division->name:'').(isset($data->structure->title) ? ' - '.$data->structure->title->name:'');


            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }

    public function getKaryawanCalendar(Request $request)
    {
        $user = Auth::user();
        $start = \Carbon\Carbon::createFromDate($request->year, $request->month)->startOfMonth();
        $end = \Carbon\Carbon::createFromDate($request->year, $request->month)->endOfMonth();

        $publicHoliday = hari_libur($start, $end);

        $ShiftScheduleChange = ShiftScheduleChange::where('change_date', '<=', $end)->where('change_date', '>=', $start)->whereHas('shiftScheduleChangeEmployees', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->orderBy('change_date', 'DESC')->with('shift.details')->get();
        $currentShift = ShiftScheduleChange::where('change_date', '<', $start)->whereHas('shiftScheduleChangeEmployees', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->orderBy('change_date', 'DESC')->with('shift.details')->first();
        $currentShift = $currentShift ? $currentShift->shift : Shift::where('id', $user->shift_id)->with('details')->first();

        $leaveTaken = CutiKaryawanDate::whereBetween('tanggal_cuti', [$start, $end])->where('type', 1)->whereHas('cutiKaryawan', function ($query) use ($user) {
            $query->where('user_id', $user->id)->whereIn('status', [2, 6, 8]);
        })->with('cutiKaryawan.cuti')->get();

        $disabledDates = [];
        $start->subDay();
        while ($start->diff($end)->days) {
            $loopDate = $start->addDay();
            $loopDateName = $loopDate->format('l');
            $loopDate = $loopDate->format('Y-m-d');

            $loopPublicHoliday = $publicHoliday->filter(function ($value) use ($loopDate) {
                return $value->tanggal == $loopDate;
            })->first();

            $loopShiftScheduleChange = $ShiftScheduleChange->filter(function ($value) use ($loopDate) {
                return $value->change_date <= $loopDate;
            })->first();
            $loopShiftScheduleChange = ($loopShiftScheduleChange ? $loopShiftScheduleChange->shift : $currentShift);
            $loopShiftScheduleChangeDay = $loopShiftScheduleChange ? $loopShiftScheduleChange->details->filter(function ($value) use ($loopDateName) {
                return $value->day == $loopDateName;
            })->first() : $loopShiftScheduleChange;

            $loopLeaveTaken = $leaveTaken->filter(function ($value) use ($loopDate) {
                return $value->tanggal_cuti == $loopDate;
            })->first();

            $cutiBersama = !$loopShiftScheduleChange || ($loopShiftScheduleChange && !$loopShiftScheduleChange->is_collective) ? CutiBersama::where('dari_tanggal', $start)->first() : false;

            if ($loopLeaveTaken) {
                $disabledDates[] = [
                    'badge' => false,
                    'date' => $loopDate,
                    'classname' => "type-1",
                    'title' => $loopLeaveTaken->cutiKaryawan->keperluan,
                ];
            } else if ($cutiBersama) {
                $disabledDates[] = [
                    'badge' => false,
                    'date' => $loopDate,
                    'classname' => $cutiBersama->impacttoleave ? "type-1" : "type-3",
                    'title' => $cutiBersama->description,
                ];
            } else if ($loopPublicHoliday && (!$loopShiftScheduleChange || ($loopShiftScheduleChange && !$loopShiftScheduleChange->is_holiday))) {
                $disabledDates[] = [
                    'badge' => false,
                    'date' => $loopDate,
                    'classname' => "type-3",
                    'title' => $loopPublicHoliday->keterangan,
                ];
            } else if ($loopShiftScheduleChange && !$loopShiftScheduleChangeDay) {
                $disabledDates[] = [
                    'badge' => false,
                    'date' => $loopDate,
                    'classname' => "type-2",
                    'title' => "Shift off day",
                ];
            }
        }
        
        return response()->json($disabledDates);
    }

    public function getLeaveCalendar()
    {
        $user = Auth::user();
        $start = \Carbon\Carbon::now()->subMonth(get_setting('min_leave_range') ?: 2)->startOfDay();
        $end = \Carbon\Carbon::now()->addMonths(get_setting('max_leave_range') ?: 2)->endOfDay();

        $data = [
            'start_date' => $start->format('Y-m-d'),
            'end_date' => $end->format('Y-m-d'),
        ];

        $publicHoliday = hari_libur($start, $end);

        $ShiftScheduleChange = ShiftScheduleChange::where('change_date', '<=', $end)->where('change_date', '>=', $start)->whereHas('shiftScheduleChangeEmployees', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->orderBy('change_date', 'DESC')->with('shift.details')->get();
        $currentShift = ShiftScheduleChange::where('change_date', '<', $start)->whereHas('shiftScheduleChangeEmployees', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->orderBy('change_date', 'DESC')->with('shift.details')->first();
        $currentShift = $currentShift ? $currentShift->shift : Shift::where('id', $user->shift_id)->with('details')->first();

        $leaveTaken = CutiKaryawanDate::whereBetween('tanggal_cuti', [$start, $end])->where('type', 1)->whereHas('cutiKaryawan', function ($query) use ($user) {
            $query->where('user_id', $user->id)->whereIn('status', [1, 2, 6, 8]);
        })->with('cutiKaryawan.cuti')->get();

        $disabledDates = [];
        $start->subDay();
        while ($start->diff($end)->days) {
            $loopDate = $start->addDay();
            $loopDateName = $loopDate->format('l');
            $loopDate = $loopDate->format('Y-m-d');

            $loopPublicHoliday = $publicHoliday->filter(function ($value) use ($loopDate) {
                return $value->tanggal == $loopDate;
            })->first();

            $loopShiftScheduleChange = $ShiftScheduleChange->filter(function ($value) use ($loopDate) {
                return $value->change_date <= $loopDate;
            })->first();
            $loopShiftScheduleChange = ($loopShiftScheduleChange ? $loopShiftScheduleChange->shift : $currentShift);
            $loopShiftScheduleChangeDay = $loopShiftScheduleChange ? $loopShiftScheduleChange->details->filter(function ($value) use ($loopDateName) {
                return $value->day == $loopDateName;
            })->first() : $loopShiftScheduleChange;

            $loopLeaveTaken = $leaveTaken->filter(function ($value) use ($loopDate) {
                return $value->tanggal_cuti == $loopDate;
            })->first();

            $cutiBersama = !$loopShiftScheduleChange || ($loopShiftScheduleChange && !$loopShiftScheduleChange->is_collective) ? CutiBersama::where('dari_tanggal', $start)->first() : false;

            if ($loopLeaveTaken) {
                $disabledDates[] = [
                    'date' => $loopDate,
                    'classname' => 4,
                    'title' => $loopLeaveTaken->cutiKaryawan->keperluan,
                ];
            } else if ($cutiBersama) {
                $disabledDates[] = [
                    'date' => $loopDate,
                    'classname' => $cutiBersama->impacttoleave ? 4 : 3,
                    'title' => $cutiBersama->description,
                ];
            } else if ($loopPublicHoliday && (!$loopShiftScheduleChange || ($loopShiftScheduleChange && !$loopShiftScheduleChange->is_holiday))) {
                $disabledDates[] = [
                    'date' => $loopDate,
                    'classname' => 3,
                    'title' => $loopPublicHoliday->keterangan,
                ];
            } else if ($loopShiftScheduleChange && !$loopShiftScheduleChangeDay) {
                $disabledDates[] = [
                    'date' => $loopDate,
                    'classname' => 2,
                    'title' => "Shift off day",
                ];
            }
        }
        
        $data = array_merge($data, [
            'event_dates' => $disabledDates,
        ]);

        return response()->json($data);
    }

    public function getCollectiveCalendar()
    {
        $user = Auth::user();
        if ($user->project_id != NULL) {
            $data['leave'] = CutiBersama::orderBy('cuti_bersama.id', 'DESC')->join('users','users.id','=','cuti_bersama.user_created')->where('users.project_id', $user->project_id)->select('cuti_bersama.*')->get();
        } else {
            $data['leave'] = CutiBersama::orderBy('id', 'DESC')->get();
        }
        
        $data['holiday'] = hari_libur();

        return response()->json($data);
    }

    public function getShiftSchedule() {
        $ShiftScheduleChange = ShiftScheduleChange::whereHas('shiftScheduleChangeEmployees', function($query) {
            $query->where('user_id', \Auth::user()->id);
        })->orderBy('change_date', 'DESC')->with('shift.details')->get();

        $currentShift = Shift::where('id', \Auth::user()->shift_id)->with('details')->first();

        return response()->json([
            'message' => 'success',
            'ShiftScheduleChange' => $ShiftScheduleChange,
            'currentShift' => $currentShift,
        ]);
    }

    /**
     * [getKabupateByProvinsi description]
     * @return [type] [description]
     */
    public function getKabupatenByProvinsi(Request $request)
    {
        if($request->ajax())
        {
            $kabupaten = Kabupaten::where('id_prov', $request->id)->get();

            return response()->json(['message' => 'success', 'data' => $kabupaten]);
        }

        return response()->json($this->respon);
    }

    /**
     * [getKecamatanByKabupaten description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getKecamatanByKabupaten(Request $request)
    {
        if($request->ajax())
        {
            $kabupaten = Kecamatan::where('id_kab', $request->id)->get();

            return response()->json(['message' => 'success', 'data' => $kabupaten]);
        }

        return response()->json($this->respon);
    }

    /**
     * [getKelurahanByKecamatan description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getKelurahanByKecamatan(Request $request)
    {
        if($request->ajax())
        {
            $kabupaten = Kelurahan::where('id_kec', $request->id)->get();

            return response()->json(['message' => 'success', 'data' => $kabupaten]);
        }

        return response()->json($this->respon);
    }

    /**
     * [getDivisionByDirectorate description]
     * @return [type] [description]
     */
    public function getDepartmentByDivision(Request $request)
    {
        if($request->ajax())
        {
            $data = Department::where('division_id', $request->id)->get();

            return response()->json(['message'=> 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }

    /**
     * [getDepartmentByDivision description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getSectionByDepartment(Request $request)
    {
        if($request->ajax())
        {
            $data = Section::where('department_id', $request->id)->get();

            return response()->json(['message'=> 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }

    /**
     * [getDivisionByDirectorate description]
     * @return [type] [description]
     */
    public function getDivisionByDirectorate(Request $request)
    {
        if($request->ajax())
        {
            $data = Division::where('directorate_id', $request->id)->get();

            return response()->json(['message'=> 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }

    /**
     * [getStructureBranch description]
     * @return [type] [description]
     */
    public function getStructureBranch()
    {
        foreach(BranchHead::all() as $k => $item)
        {
            $data[$k]['name'] = 'Head';
            $data[$k]['title'] = $item->name;
            $data[$k]['children'] = [];

            foreach(BranchStaff::where('branch_head_id', $item->id)->get() as $k2 => $i2)
            {
                $data[$k]['children'][$k2]['title'] = $i2->name;
                $data[$k]['children'][$k2]['name'] = 'Staff';
            }
        }

        return response()->json($data);
    }

    /**
     * [getStructure description]
     * @return [type] [description]
     */
    public function getStructure()
    {
        $data = [];

        $directorate = EmporeOrganisasiDirektur::all();
        foreach($directorate as $key_dir => $dir)
        {
            $data[$key_dir]['name'] = 'Director';
            $data[$key_dir]['title'] = $dir->name;
            $data[$key_dir]['children'] = [];

            $num_key_div = 0;
            foreach(EmporeOrganisasiManager::where('empore_organisasi_direktur_id', $dir->id)->get() as $key_div => $div)
            {
                $data[$key_dir]['children'][$key_div]['name'] = 'Manager';
                $data[$key_dir]['children'][$key_div]['title'] = $div->name;

                foreach(EmporeOrganisasiStaff::where('empore_organisasi_manager_id', $div->id)->get() as $key_dept => $dept)
                {
                    $data[$key_dir]['children'][$key_div]['children'][$key_dept]['name'] = 'Staff';
                    $data[$key_dir]['children'][$key_div]['children'][$key_dept]['title'] = $dept->name;
                }

                $num_key_div++;
            }
        }

        return response()->json($data);
    }

    /**
     * Get structure custom
     * @return json
     */
    public function getStructureCustome()
    {
        $user = \Auth::user();
        if($user->project_id != NULL)
        {
            $all = \App\Models\StructureOrganizationCustom::where('project_id', $user->project_id)->select('structure_organization_custom.*')->get();
        } else{
            $all = \App\Models\StructureOrganizationCustom::all();
        }
        $data = [];
        foreach ($all as $key => $item)
        {
            if($item->parent_id == null || \App\Models\StructureOrganizationCustom::find($item->parent_id)) {
                $data[$key]['id'] = $item->id;
                $data[$key]['name'] = isset($item->position) ? $item->position->name : '';
                $data[$key]['name'] = isset($item->division) ? $data[$key]['name'] . ' - ' . $item->division->name : $data[$key]['name'];
                $data[$key]['name'] = isset($item->title) ? $data[$key]['name'] . ' - ' . $item->title->name : $data[$key]['name'];
                $data[$key]['name'] .= " (" . (isset($item->position) ? $item->position->code : "") . (isset($item->division) ? "-" . $item->division->code : "") . (isset($item->title) ? "-" . $item->title->code : "") . ")";
                //$data[$key]['description']= 'this description';
                $data[$key]['parent'] = (int)$item->parent_id;
            }
        }





        return json_encode($data);
    }


    /**
     * Store
     * @param  Request $request
     */
    public function structureCustomeAdd(Request $request)
    {
        $data               = new \App\Models\StructureOrganizationCustom();
        $data->parent_id    = $request->parent_id;
        $data->name         = $request->name;
        $data->save();

        return json_encode(['message' => 'success']);
    }

    /**
     * Delete
     * @param  $id
     */
    public function structureCustomeDelete(Request $request)
    {
        $data = \App\Models\StructureOrganizationCustom::where('id', $request->id)->first();
        if($data) {
            if ($data->parent_id == null) {
                $childCount = \App\Models\StructureOrganizationCustom::where('parent_id', $request->id)->count();
                if ($childCount > 1) {
                    return json_encode(['status' => 'failed', 'message' => 'Can not delete leader with more than 1 predecessor!']);
                } else {
                    \App\Models\StructureOrganizationCustom::where('parent_id', $request->id)->update(['parent_id'=> null]);
                    $data->delete();
                }
            }else {
                \App\Models\StructureOrganizationCustom::where('parent_id', $request->id)->update(['parent_id'=> $data->parent_id]);
                $data->delete();
            }


            $settingApproval = \App\Models\SettingApproval::where('structure_organization_custom_id', $request->id)->first();

            /*$settingApprovalCount = \App\Models\SettingApprovalLeaveItem::where('setting_approval_id', $settingApproval->id)->get();
            if(count($settingApprovalCount)>0)
            {
                $settingApprovalCount->deleteAll();
            }
            */
            $settingApproval->delete();

            \App\User::where('structure_organization_custom_id',$request->id)->update(['structure_organization_custom_id'=>null]);

            /*$settingApprovalItem = \App\Models\SettingApprovalLeaveItem::where('structure_organization_custom_id', $request->id)->first();
            if($settingApprovalItem)
            {
                $settingApprovalItem->delete();
            }
            */
            return json_encode(['status' => 'success', 'message' => 'success delete structure']);
        }
        return json_encode(['status' => 'failed', 'message' => 'failed']);
    }

    /**
     * Delete
     * @param  $id
     */
    public function structureCustomeEdit(Request $request)
    {
        $data = \App\Models\StructureOrganizationCustom::where('id', $request->id)->first();
        if($data)
        {
            $data->name = $request->name;
        }
        $data->save();

        return json_encode(['message' => 'success']);
    }

    /**
     * [getKaryawanApproval description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */

    public function getKaryawanTransfer(Request $request)
    {
        $params = [];
        if($request->ajax())
        {
            $user = \Auth::user();
            if($user->project_id != NULL)
            {
                $approvalCA = SettingApprovalCashAdvanceItem::select('structure_organization_custom_id')->groupBy('structure_organization_custom_id')->get()->toArray();
                $approvalPR = SettingApprovalPaymentRequestItem::select('structure_organization_custom_id')->groupBy('structure_organization_custom_id')->get()->toArray();
                $merge = array_merge($approvalCA, $approvalPR);
                //dd($merge);
                // SKIP SUPERADMIN, ACCESS_ID 1
                $data =  \App\User::whereIn('structure_organization_custom_id', $merge)->where(function($query) {
                    $query->whereNull('non_active_date')->orWhere('non_active_date', '>', \Carbon\Carbon::now());
                })->where(function($query) {
                    $query->whereNull('join_date')->orWhere('join_date', '<=', \Carbon\Carbon::now());
                })->where('project_id',$user->project_id)->where(function($table) use ($request) {
                    $table->where('name', 'LIKE', "%". $request->name . "%")
                    ->orWhere('nik', 'LIKE', '%'. $request->name .'%');
                })->whereIn('access_id', [1,2])->get();

            }else {
                // Skip Exist User
                $approvalCA = SettingApprovalCashAdvanceItem::select('structure_organization_custom_id')->groupBy('structure_organization_custom_id')->get()->toArray();
                $approvalPR = SettingApprovalPaymentRequestItem::select('structure_organization_custom_id')->groupBy('structure_organization_custom_id')->get()->toArray();
                $merge = array_merge($approvalCA, $approvalPR);

                // SKIP SUPERADMIN, ACCESS_ID 1
                $data =  \App\User::whereNotIn('id', $merge)->where(function($query) {
                    $query->whereNull('non_active_date')->orWhere('non_active_date', '>', \Carbon\Carbon::now());
                })->where(function($query) {
                    $query->whereNull('join_date')->orWhere('join_date', '<=', \Carbon\Carbon::now());
                })->where(function($table) use ($request) {

                    $table->where('name', 'LIKE', "%". $request->name . "%")
                    ->orWhere('nik', 'LIKE', '%'. $request->name .'%');
                })->whereIn('access_id', [1,2])->get();
            }

            $params = [];
            foreach($data as $k => $item)
            {
                if($k >= 10) continue;

                $params[$k]['id'] = $item->id;
                $params[$k]['value'] = $item->nik .' - '. $item->name;
            }
        }

        return response()->json($params);
    }

    public function getKaryawanApproval(Request $request)
    {
        $params = [];
        if($request->ajax())
        {
            $user = \Auth::user();
            if($user->project_id != NULL)
            {
                // Skip Exist User
                $approvalExistUser = SettingApprovalClearance::join('users','users.id','=','setting_approval_clearance.user_created')->where('users.project_id', $user->project_id)
                ->select('setting_approval_clearance.user_id')->get()->toArray();

                // SKIP SUPERADMIN, ACCESS_ID 1
                $data =  \App\User::whereNotIn('id', $approvalExistUser)->where(function($query) {
                    $query->whereNull('non_active_date')->orWhere('non_active_date', '>', \Carbon\Carbon::now());
                })->where(function($query) {
                    $query->whereNull('join_date')->orWhere('join_date', '<=', \Carbon\Carbon::now());
                })->where('project_id',$user->project_id)->where(function($table) use ($request) {
                    $table->where('name', 'LIKE', "%". $request->name . "%")
                    ->orWhere('nik', 'LIKE', '%'. $request->name .'%');
                })->whereIn('access_id', [1,2])->get();

            }else {
                // Skip Exist User
                $approvalExistUser = SettingApprovalClearance::select('user_id')->get()->toArray();

                // SKIP SUPERADMIN, ACCESS_ID 1
                $data =  \App\User::whereNotIn('id', $approvalExistUser)->where(function($query) {
                    $query->whereNull('non_active_date')->orWhere('non_active_date', '>', \Carbon\Carbon::now());
                })->where(function($query) {
                    $query->whereNull('join_date')->orWhere('join_date', '<=', \Carbon\Carbon::now());
                })->where(function($table) use ($request) {

                    $table->where('name', 'LIKE', "%". $request->name . "%")
                    ->orWhere('nik', 'LIKE', '%'. $request->name .'%');
                })->whereIn('access_id', [1,2])->get();
            }

            $params = [];
            foreach($data as $k => $item)
            {
                if($k >= 10) continue;

                $params[$k]['id'] = $item->id;
                $params[$k]['value'] = $item->nik .' - '. $item->name;
            }
        }

        return response()->json($params);
    }


    public function addSettingClearanceHrd(Request $request)
    {
        if($request->ajax())
        {
            $data               = new SettingApprovalClearance();
            $data->user_id      = $request->id;
            $data->nama_approval= 'HRD';
            $user = \Auth::user();
            if($user->project_id != NULL)
            {
                $data->user_created = $user->id;
            }

            $data->save();

            Session::flash('message-success', 'User Approval successfully add');

            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }

    public function addSettingClearanceGA(Request $request)
    {
        if($request->ajax())
        {
            $data               = new SettingApprovalClearance();
            $data->user_id      = $request->id;
            $data->nama_approval= 'GA';
            $user = \Auth::user();
            if($user->project_id != NULL)
            {
                $data->user_created = $user->id;
            }
            $data->save();

            Session::flash('message-success', 'User Approval successfully add');

            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }

    public function addSettingClearanceIT(Request $request)
    {
        if($request->ajax())
        {
            $data               = new SettingApprovalClearance();
            $data->user_id      = $request->id;
            $data->nama_approval= 'IT';
            $user = \Auth::user();
            if($user->project_id != NULL)
            {
                $data->user_created = $user->id;
            }
            $data->save();

            Session::flash('message-success', 'User Approval successfully add');

            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }

    public function addSettingClearanceAccounting(Request $request)
    {
        if($request->ajax())
        {
            $data               = new SettingApprovalClearance();
            $data->user_id      = $request->id;
            $data->nama_approval= 'Accounting';
            $user = \Auth::user();
            if($user->project_id != NULL)
            {
                $data->user_created = $user->id;
            }
            $data->save();

            Session::flash('message-success', 'User Approval successfully add');

            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }


    public function deleteKaryawan(Request $request)
    {
        if($request->ajax())
        {
            $user               = \App\User::where('id', $request->id)->count();
            if($user > 0){
                \App\User::where('id', $request->id)->delete();
            }

            $userfamily         =   UserFamily::where('user_id',  $request->id)->count();
            if($userfamily > 0){
                UserFamily::where('user_id',  $request->id)->delete();
            }

            $useredu            =   UserEducation::where('user_id',  $request->id)->count();
            if($useredu > 0){
                UserEducation::where('user_id',  $request->id)->delete();
            }
            $hasil = 'ok';
            return response()->json($hasil);
        }

        return response()->json($this->respon);
    }

    public function getLiburNasional(Request $request)
    {
        if($request->ajax())
        {
            $check               = LiburNasional::count();
            $data               = LiburNasional::all();

            if($check < 1){
                $tanggal = "";
                $keterangan = "";
            }else{
                $params = [];
                foreach($data as $k =>  $item){
                    $tanggal[$k] = $item->tanggal;
                    $keterangan[$k] = $item->keterangan;
                }
            }

            $hasil = json_encode(array("tanggal"=>$tanggal, "keterangan"=>$keterangan));
            return response()->json($hasil);
        }

          return response()->json($this->respon);
    }

    public function getNote(Request $request)
    {
        if($request->ajax())
        {
            if(\Auth::user()->project_id != Null){
                $data               = Note::where('project_id', \Auth::user()->project_id)->get();
                $check              = Note::where('project_id', \Auth::user()->project_id)->count();
            }else{
                $data               = Note::whereNull('project_id')->get();
                $check              = Note::whereNull('project_id')->count();
            }

            if($check < 1){
                $tanggal = "";
                $judul = "";
            }else{
                $params = [];
                foreach($data as $k =>  $item){
                    $tanggal[$k] = $item->tanggal;
                    $judul[$k] = $item->judul;
                }
            }

            $hasil = json_encode(array("tanggal"=>$tanggal, "keterangan"=>$judul));
            return response()->json($hasil);
        }

          return response()->json($this->respon);
    }

    public function getDetailNote(Request $request)
    {
        if($request->ajax())
        {
            $tanggalnote            = $request->tanggal;
            if(\Auth::user()->project_id != Null){
                $data               = Note::where('tanggal', $tanggalnote)->where('project_id', \Auth::user()->project_id)->get();
            }else{
                $data               = Note::where('tanggal', $tanggalnote)->whereNull('project_id')->get();
            }

            if(count($data) < 1){
                $tanggal = $tanggalnote;
                $judul = "";
                $catatan = "";
            }else{
                $params = [];
                foreach($data as $k =>  $item){
                    $tanggal[$k] = $item->tanggal;
                    $judul[$k] = $item->judul;
                    $catatan[$k] = $item->catatan;
                }
            }

            $hasil = json_encode(array("tanggal"=>$tanggalnote, "judul"=>$judul, "catatan"=>$catatan));
            return response()->json($hasil);
        }

          return response()->json($this->respon);
    }


    public function storeNote(Request $request)
    {
        if($request->ajax())
        {
            if(\Auth::user()->project_id != Null){
                $check              = Note::where('tanggal', $request->tanggal)->where('project_id', \Auth::user()->project_id)->count();
                if($check < 1){
                    $data               = new Note();
                    $data->tanggal      = $request->tanggal;
                    $data->judul        = $request->judul;
                    $data->catatan      = $request->catatan;
                    $data->project_id   = \Auth::user()->project_id;
                    $data->save();
                }else{
                    if($request->judul == '' && $request->catatan == ''){
                        $data               = Note::where('tanggal', $request->tanggal)->where('project_id', \Auth::user()->project_id)->first();
                        $data->delete();
                    }else{
                        $data               = Note::where('tanggal', $request->tanggal)->where('project_id', \Auth::user()->project_id)->first();
                        $data->judul        = $request->judul;
                        $data->catatan      = $request->catatan;
                        $data->save();
                    }

                }
            }else{
                $check              = Note::where('tanggal', $request->tanggal)->whereNull('project_id')->count();
                if($check < 1){
                    $data               = new Note();
                    $data->tanggal      = $request->tanggal;
                    $data->judul        = $request->judul;
                    $data->catatan      = $request->catatan;
                    $data->save();
                }else{
                    if($request->judul == '' && $request->catatan == ''){
                        $data               = Note::where('tanggal', $request->tanggal)->whereNull('project_id')->first();
                        $data->delete();
                    }else{
                        $data               = Note::where('tanggal', $request->tanggal)->whereNull('project_id')->first();
                        $data->judul        = $request->judul;
                        $data->catatan      = $request->catatan;
                        $data->save();
                    }

                }
            }


            $hasil = json_encode(array("message"=>"success"));
            return response()->json($hasil);
        }

          return response()->json($this->respon);
    }



    public function getUserActive(Request $request){
        if($request->ajax())
        {
            if(\Auth::user()->project_id != Null){
                $data = User::whereIn('access_id', ['1', '2'])
                        ->where(function($query) {
                            $query->whereNull('non_active_date')->orWhere('non_active_date', '>', \Carbon\Carbon::now());
                        })->where(function($query) {
                            $query->whereNull('join_date')->orWhere('join_date', '<=', \Carbon\Carbon::now());
                        })
                        ->where('project_id', \Auth::user()->project_id)
                    //    ->where('last_logged_in_at', '<=', date('Y-m-d H:i:s'))
                        ->whereRaw('last_logged_in_at >= last_logged_out_at')
                        ->count();
            }else{
                $data = User::whereIn('access_id', ['1', '2'])
                        ->where(function($query) {
                            $query->whereNull('non_active_date')->orWhere('non_active_date', '>', \Carbon\Carbon::now());
                        })->where(function($query) {
                            $query->whereNull('join_date')->orWhere('join_date', '<=', \Carbon\Carbon::now());
                        })
                        ->whereRaw('last_logged_in_at >= last_logged_out_at')
                        ->count();
            }


            return response()->json($data);
        }
        return response()->json($this->respon);
    }

    public function getHeadcountDepartment(Request $request){
        if($request->ajax()) {
            $user = \Auth::user();
            if(\Auth::user()->project_id != Null){
            /*    $jumlahdata = DB::table('organisasi_division')
                        //    ->selectRaw('COUNT(structure_organization_custom.organisasi_division_id) AS jumlah')
                            ->selectRaw('count(organisasi_division.id)')
                            ->join('structure_organization_custom', 'organisasi_division.id', '=', 'structure_organization_custom.organisasi_division_id')
                            ->join('users', 'structure_organization_custom.id', '=', 'users.structure_organization_custom_id')
                            ->where('users.project_id', \Auth::user()->project_id)
                            ->groupby('structure_organization_custom.organisasi_division_id')
                            ->count();  */
                $jumlah = \DB::select('SELECT count(distinct(structure_organization_custom.organisasi_division_id)) as total_divisi
                            FROM organisasi_division 
                            left join structure_organization_custom on organisasi_division.id = structure_organization_custom.organisasi_division_id 
                            left join users on structure_organization_custom.id = users.structure_organization_custom_id 
                            where users.project_id = "'.$user->project_id.'"');   
                                            
                foreach($jumlah as $jumlahdivisi){
                    $jumlahdata = $jumlahdivisi->total_divisi;
                } 
                
                $data = \DB::table('organisasi_division')
                            ->select('organisasi_division.name', 'organisasi_division.id')
                            ->join('structure_organization_custom', 'organisasi_division.id', '=', 'structure_organization_custom.organisasi_division_id')
                            ->join('users', 'structure_organization_custom.id', '=', 'users.structure_organization_custom_id')
                            ->where('users.project_id', \Auth::user()->project_id)
                            ->groupby('structure_organization_custom.organisasi_division_id')
                            ->get();
            } else {
                $jumlahdata = OrganisasiDivision::count();
                $data = OrganisasiDivision::all();
            }

            $name = [];
            $id = [];
            $karyawan_per_divisi = [];
            $y = 0;
            $x = 0;
            $z = 0;
            for ($i=0; $i < $jumlahdata; $i++) {
                if (!$request->filter_division || $request->filter_division == $data[$i]->id) {

                    $name[$y] = $data[$i]->name;
                    $id[$x] = $data[$i]->id;
                    
                    if(\Auth::user()->project_id != Null){
                        $karyawan_per_divisi[$z] = \DB::table('structure_organization_custom')
                                                        ->select('structure_organization_custom.*', 'users.*')
                                                        ->join('users', 'structure_organization_custom.id','=', 'users.structure_organization_custom_id')
                                                        ->where('structure_organization_custom.organisasi_division_id', $id[$x])
                                                        ->where('users.project_id', \Auth::user()->project_id)
                                                        ->whereNull('users.status')
                                                        ->whereIn('users.access_id', ['1', '2'])
                                                        ->where(function($query) {
                                                            $query->whereNull('non_active_date')->orWhere('non_active_date', '>', \Carbon\Carbon::now());
                                                        })->where(function($query) {
                                                            $query->whereNull('join_date')->orWhere('join_date', '<=', \Carbon\Carbon::now());
                                                        });

                        if (!empty($request->filter_position)) {
                            $karyawan_per_divisi[$z] = $karyawan_per_divisi[$z]->where('structure_organization_custom.organisasi_position_id', $request->filter_position);
                        }
                        if (!empty($request->filter_branch)) {
                            $karyawan_per_divisi[$z] = $karyawan_per_divisi[$z]->where('users.cabang_id', $request->filter_branch);
                        }

                        $karyawan_per_divisi[$z] = $karyawan_per_divisi[$z]->count();

                    }else{
                        $karyawan_per_divisi[$z] = \DB::table('structure_organization_custom')
                                                        ->select('structure_organization_custom.*', 'users.*')
                                                        ->join('users', 'structure_organization_custom.id','=', 'users.structure_organization_custom_id')
                                                        ->where('structure_organization_custom.organisasi_division_id', $id[$x])
                                                        ->whereNull('users.status')
                                                        ->whereIn('users.access_id', ['1', '2'])
                                                        ->where(function($query) {
                                                            $query->whereNull('non_active_date')->orWhere('non_active_date', '>', \Carbon\Carbon::now());
                                                        })->where(function($query) {
                                                            $query->whereNull('join_date')->orWhere('join_date', '<=', \Carbon\Carbon::now());
                                                        });

                        if (!empty($request->filter_position)) {
                            $karyawan_per_divisi[$z] = $karyawan_per_divisi[$z]->where('structure_organization_custom.organisasi_position_id', $request->filter_position);
                        }
                        if (!empty($request->filter_branch)) {
                            $karyawan_per_divisi[$z] = $karyawan_per_divisi[$z]->where('users.cabang_id', $request->filter_branch);
                        }

                        $karyawan_per_divisi[$z] = $karyawan_per_divisi[$z]->count();
                    }
                    
                    $name[$y++];
                    $id[$x++];
                    $karyawan_per_divisi[$z++];
                }	
            }
            $namedivision = $name;
            $jumlahperdivisi = $karyawan_per_divisi;

            foreach (array_keys($jumlahperdivisi, 0, false) as $key) {
                unset($namedivision[$key]);
                unset($jumlahperdivisi[$key]);
            }

            return response()->json([
                'namedivision' => count($namedivision) ? array_values($namedivision) : ['No data available'],
                'jumlahperdivisi' => count($jumlahperdivisi) ? array_values($jumlahperdivisi) : [0],
            ]);
        }
        return response()->json($this->respon);
    }

    public function getDataStatus(Request $request){
        $data = \App\User::leftJoin('structure_organization_custom', 'structure_organization_custom.id', '=', 'users.structure_organization_custom_id')
            ->select('users.organisasi_status', \DB::raw('count(*) as total'))
            ->whereIn('users.access_id', ['1', '2'])
            ->where(function($query) {
                $query->whereNull('non_active_date')->orWhere('non_active_date', '>', \Carbon\Carbon::now());
            })->where(function($query) {
                $query->whereNull('join_date')->orWhere('join_date', '<=', \Carbon\Carbon::now());
            })
            ->groupBy('users.organisasi_status');

        if (\Auth::user()->project_id != null) {
            $data = $data->where('users.project_id', \Auth::user()->project_id);
        }

        if (!empty($request->filter_position)) {
            $data = $data->where('structure_organization_custom.organisasi_position_id', $request->filter_position);
        }
        if (!empty($request->filter_division)) {
            $data = $data->where('structure_organization_custom.organisasi_division_id', $request->filter_division);
        }
        if (!empty($request->filter_branch)) {
            $data = $data->where('users.cabang_id', $request->filter_branch);
        }

        $result = [];
        foreach ($data->get() as $value) {
            if ($value->organisasi_status)
                $result[$value->organisasi_status] = $value->total;
            else {
                if (isset($result['No Status']))
                    $result['No Status'] += $value->total;
                else
                    $result['No Status'] = $value->total;
            }
        }

        return response()->json([
            'data' => $result,
        ]);
    }

    public function getDataDashboard(Request $request){
        if($request->ajax())
        {
            $StartDate = strtotime(str_replace('/', '-', $request->filter_start));
            $StopDate = strtotime(str_replace('/', '-', $request->filter_end));
            $filterStart = str_replace('/', '-', $request->filter_start);
            $filterEnd = str_replace('/', '-', $request->filter_end);
            $filterPosition = $request->filter_position;
            $filterDivision =  $request->filter_division;
            $filterBranch = $request->filter_branch;
            $current = $StartDate;
            $ret = array();
            $bulan_val = array();
            $employee_join = array();
            $employee_resign = array();
            $attrition = array();

            while($current<$StopDate){
                $month = date('M y', $current);
                $bulan_val[] = $month;

                $next = date('Y-m', $current) . "+1 month";
                $current = strtotime($next);
                $replacetext = str_replace('+1 month', '', $next);
                $ret[] = $replacetext;

                $nextmonth = date('Y-m', $current)."+1 month";
                $next_month = str_replace('+1 month', '', $nextmonth);

                $employee_active[] = employee_get_actives($filterStart, $filterEnd, $filterPosition, $filterDivision, $filterBranch, $replacetext);
                $employee_join[] = employee_get_joinees($filterStart, $filterEnd, $filterPosition, $filterDivision, $filterBranch, $replacetext);
                $employee_resign[] = employee_get_resigness($filterStart, $filterEnd, $filterPosition, $filterDivision, $filterBranch, $replacetext);
                $employee_end_contract[] = employee_get_end_contracts($filterStart, $filterEnd, $filterPosition, $filterDivision, $filterBranch, $replacetext);
                $attrition[] = employee_attrition($filterStart, $filterEnd, $filterPosition, $filterDivision, $filterBranch, $replacetext, $next_month);

            }

            $hasil = json_encode(array("bulan_val"=>$bulan_val, "employee_active"=>$employee_active, "employee_join"=>$employee_join,
                                        "employee_resign"=>$employee_resign, "employee_end_contract"=>$employee_end_contract, "attrition"=>$attrition));

            return response()->json($hasil);
        }
        return response()->json($this->respon);
    }

    /**
     * Get structure custom
     * @return json
     */
    public function getKaryawanDefaultPayroll(Request $request)
    {
        $data = [
            'payroll' => $payroll = Payroll::with([
                'payrollEarningsEmployee' => function ($query) {
                    $query->has('payrollEarnings');
                },
                'payrollDeductionsEmployee' => function ($query) {
                    $query->has('payrollDeductions');
                }
            ])->where('user_id', $request->user_id)->first(),
            'prorate' => getProrate($request->user_id),
            'loan_payment' => getLoanPayroll($request->user_id),
            'business_trip_payment' => getBusinessTripPayment($request->user_id),
            'cash_advance_payment' => getCashAdvancePayment($request->user_id)
        ];

        return response()->json($data);
    }

    public function getKaryawanPayrollAttendance(Request $request){
        return response()->json(get_payroll_attendance($request->month,$request->year,$request->user_id,$request->id));
    }

    public function getKaryawanPayrollOvertime(Request $request){
        return response()->json(get_payroll_overtime($request->month,$request->year,$request->user_id,$request->id));
    }

    /**
     * Get structure custom
     * @return json
     */
    public function getKpiItems(Request $request)
    {
        $user = \Auth::user();
        $scoring = KpiSettingScoring::with('items')->with(['status' => function($query) use ($request){
            $query->where('structure_organization_custom_id', '=', $request->structure_organization_custom_id);
        }]);
        if($request->period_id){
            $scoring = $scoring->where('kpi_period_id',$request->period_id);
        }
        if($request->module_id){
            $scoring = $scoring->where('kpi_module_id',$request->module_id);
        }


        echo json_encode($scoring->first());
    }

    public function getKpiItemsManager(Request $request)
    {
        $user = \Auth::user();
//        print_r($request->all());
        $scoring = KpiSettingScoring::with(['items' => function($query) use ($request){
            $query->whereRaw(\DB::raw("structure_organization_custom_id is null or structure_organization_custom_id = $request->structure_organization_custom_id"));
        }])->with('period')->with(['status' => function($query) use ($request){
            $query->where('structure_organization_custom_id', '=', $request->structure_organization_custom_id);
        }]);
        if($request->period_id){
            $scoring = $scoring->where('kpi_period_id',$request->period_id);
        }
        $scoring = $scoring->orderBy('kpi_module_id','asc');
//        if($request->module_id){
//            $scoring = $scoring->where('kpi_module_id',$request->module_id);
//        }

        echo json_encode($scoring->get());
    }

    public function getStructureOrganizationDetail(Request $request){
        if ($request->id) {
            $id = $request->id;
            $structure = StructureOrganizationCustom::with('grade.sub_grade')->find($id);
            if($structure){
                $structure->description = htmlspecialchars_decode($structure->description);
                $structure->requirement = htmlspecialchars_decode($structure->requirement);
                if($structure->grade)
                $structure->grade->benefit = htmlspecialchars_decode($structure->grade->benefit);
                return $structure;
            }
        } else {
            $grade = Grade::with('sub_grade')->get();
            foreach ($grade as $key => $value) {
                $grade[$key]->benefit = htmlspecialchars_decode($value->benefit);
            }
            return $grade;
        }
        return null;
    }

    public function getRecruitmentRequestApproval(Request $request)
    {
        $recruitment = RecruitmentRequest::with(['approvals.userApproved','branch','approver'])->where('id',$request->id)->first();
        if($recruitment){
            $recruitment->position = $recruitment->job_position;
            $recruitment->request_date = date('d F Y', strtotime($recruitment->created_at));
            $recruitment->approval_hr_date = date('d F Y', strtotime($recruitment->approval_hr_date));

            foreach ($recruitment->approvals as $approval){
                $approval->position = ($approval->structure->position)?$approval->structure->position->name:"";
                $approval->position .= ($approval->structure->division)?" - ".$approval->structure->division->name:"";
                $approval->position .= ($approval->structure->title)?" - ".$approval->structure->title->name:"";
                $approval->date_approved = date('d F Y', strtotime($approval->date_approved));
            }

            return response()->json($recruitment,200);
        }
        return null;
    }

    public function getRecruitmentRequestDetail(Request $request){
        $id = $request->id;
        $details =  RecruitmentRequestDetail::with('type')->where(['recruitment_request_id'=>$id])->get();
        foreach ($details as $detail){
            if($detail->posting_date==null)
                $detail->posting_date = '';
            else
                $detail->posting_date = date('d M Y', strtotime($detail->posting_date));

            if($detail->expired_date==null)
                $detail->expired_date = '';
            else
                $detail->expired_date = date('d M Y', strtotime($detail->expired_date));
        }
        return response()->json($details,200);
    }


/*    public function importAttendance(Request $request){
        if($request->ajax())
        {
            $user   = \Auth::user();
            if($user->project_id != NULL){
                $params['data']     = AbsensiItem::join('users','users.id','=','absensi_item.user_id')
                                                    ->where('users.project_id', $user->project_id)
                                                    ->select('absensi_item.*')
                                                    ->orderBy('absensi_item.id', 'DESC')->paginate(100);
            }else{
                $params['data']     = AbsensiItem::orderBy('id', 'DESC')->paginate(100);
            }

            $destination = storage_path('app');
            $name_excel = 'Attendance'.date('YmdHis');

            return Excel::store(new AttendanceExport($params), $name_excel.'.xlsx');
        }
    }   */

    public function fileTunnel($path) {
        $path = storage_path($path);

        if (!\File::exists($path)) {
            abort(404);
        }

        $file = \File::get($path);
        $type = \File::mimeType($path);

        $response = \Response::make($file, 200);
        $response->header("Content-Type", $type);

        return $response;
    }

    public function leaveList(Request $request)
    {
        $user = $request->user_id ? \App\User::find($request->user_id) : \Auth::user();
        $cuti = \App\Models\Cuti::orderBy('cuti.jenis_cuti','ASC')
            ->join('users', 'users.id','=', 'cuti.user_created')
            ->select('cuti.*');

        if ($user && $user->project_id != NULL) {
            $cuti = $cuti->where('users.project_id', $user->project_id);
        }

        // if (!$request->shift_id) {
        //     $cuti = $cuti->where('cuti.jenis_cuti', '!=', 'Annual Leave');
        // }

        return response()->json($cuti->get());
    }

    public function reportTraining(Request $request){
        //dd($request);
        if($request->type=='acomodation'){
            $data = TrainingTransportationReport::with('userApproved')->where('training_transportation_id', $request->id)->get();
        }
        else if($request->type=='allowance'){
            $data = TrainingAllowanceReport::with('userApproved')->where('training_allowance_id', $request->id)->get();
        }
        else if($request->type=='daily'){
            $data = TrainingDailyReport::with('userApproved')->where('training_daily_id', $request->id)->get();
        }
        else if($request->type=='other'){
            $data = TrainingOtherReport::with('userApproved')->where('training_other_id', $request->id)->get();
        }
        return $data;
    }

    public function changeNotifReadStatus(Request $request){
        if (session('company_url', 'umum') != 'umum') {
            return \FRDHelper::changeReadStatus($request->id, session('company_url'));
        } else {
            return false;
        }
    }
}
