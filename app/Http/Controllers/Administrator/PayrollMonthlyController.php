<?php

namespace App\Http\Controllers\Administrator;

use App\Models\RequestPaySlip;
use App\Models\RequestPaySlipItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Carbon\Carbon;
use App\Models\Payroll;
use App\User;
use App\Models\Bank;
use App\Models\PayrollHistory;
use App\Models\PayrollOthers;
use App\Models\PayrollPtkp;
use App\Models\PayrollEarningsEmployee;
use App\Models\PayrollEarningsEmployeeHistory;
use App\Models\PayrollDeductionsEmployee;
use App\Models\PayrollDeductionsEmployeeHistory;
use App\Models\PayrollEarnings;
use App\Models\PayrollDeductions;
use App\Models\OrganisasiDivision;
use App\Models\OrganisasiPosition;
use App\Models\OvertimeSheetForm;
use App\Models\Setting;
use App\Models\PayrollUMR;
use App\Models\LoanPayment;
use App\Models\Training;
use App\Models\CashAdvance;

class PayrollMonthlyController extends Controller
{

    public function __construct(\Maatwebsite\Excel\Excel $excel)
    {
        parent::__construct();
        $this->middleware('module:13');
        $this->excel = $excel;
    }

    /**
     * [index description]
     * @return [type] [description]
     */
    public function index()
    {
        $user = \Auth::user();

        if (!\Session::get('m-month') || request()->month) {
            \Session::put('m-month', request()->month ? request()->month : date('n'));
        }
        if (!\Session::get('m-year') || request()->year) {
            \Session::put('m-year', request()->year ? request()->year : date('Y'));
        }

        if(count(request()->all())) {
            \Session::put('m-is_calculate', request()->is_calculate);
            \Session::put('m-payroll_type', request()->payroll_type);
            \Session::put('m-employee_status', request()->employee_status);
            \Session::put('m-position_id', request()->position_id);
            \Session::put('m-division_id', request()->division_id);
            \Session::put('m-name', request()->name);
            \Session::put('m-employee_resign', request()->employee_resign);
        }

        $is_calculate       = \Session::get('m-is_calculate');
        $payroll_type       = \Session::get('m-payroll_type');
        $employee_status    = \Session::get('m-employee_status');
        $position_id        = \Session::get('m-position_id');
        $division_id        = \Session::get('m-division_id');
        $name               = \Session::get('m-name');
        $month              = \Session::get('m-month');
        $year               = \Session::get('m-year');
        $employee_resign    = \Session::get('m-employee_resign');

        if($user->project_id != NULL)
        {
            if(!empty($year) && !empty($month))
            {
                $result = PayrollHistory::select('payroll_history.*')->join('users', 'users.id', '=', 'payroll_history.user_id')->where('users.project_id', $user->project_id)->whereMonth('payroll_history.created_at', $month)->whereYear('payroll_history.created_at', $year)->orderBy('payroll_history.id', 'DESC');
            }
            else{
                $result = Payroll::select('payroll.*')->join('users', 'users.id','=', 'payroll.user_id')->where('users.project_id', $user->project_id)->orderBy('payroll.id', 'DESC');
            }
            $params['division'] = OrganisasiDivision::where('project_id', $user->project_id)->select('organisasi_division.*')->get();
            $params['position'] = OrganisasiPosition::where('project_id', $user->project_id)->select('organisasi_position.*')->get();
        }
        else{
            if(!empty($year) && !empty($month))
            {
                $result = PayrollHistory::select('payroll_history.*')->join('users', 'users.id', '=', 'payroll_history.user_id')->whereMonth('payroll_history.created_at', $month)->whereYear('payroll_history.created_at', $year)->orderBy('payroll_history.id', 'DESC');
            }
            else{
                $result = Payroll::select('payroll.*')->join('users', 'users.id','=', 'payroll.user_id')->orderBy('payroll.id', 'DESC');
            }
            $params['division'] = OrganisasiDivision::all();
            $params['position'] = OrganisasiPosition::all();
        }

        if(!empty($is_calculate))
        {
            $result = $result->where('is_calculate', $is_calculate);
        }

        if(!empty($payroll_type))
        {
            $result = $result->where('payroll_type', $payroll_type);
        }

        if($employee_status)
        {
            $result = $result->where('users.organisasi_status', $employee_status);
        }

        if((!empty($division_id)) and (empty($position_id)))
        {
            $result = $result->join('structure_organization_custom','users.structure_organization_custom_id','=','structure_organization_custom.id')->where('structure_organization_custom.organisasi_division_id',$division_id);
        }
        if((!empty($position_id)) and (empty($division_id)))
        {
            $result = $result->join('structure_organization_custom','users.structure_organization_custom_id','=','structure_organization_custom.id')->where('structure_organization_custom.organisasi_position_id',$position_id);
        }
        if((!empty($position_id)) and (!empty($division_id)))
        {
            $result = $result->join('structure_organization_custom','users.structure_organization_custom_id','=','structure_organization_custom.id')->where('structure_organization_custom.organisasi_position_id',$position_id)->where('structure_organization_custom.organisasi_division_id',$division_id);
        }
        
        if(!empty($employee_resign))
        {
            if ($employee_resign == 'Active')
                    $result = $result->where(function($query) {
                        $query->whereNull('users.non_active_date')->orWhere('users.non_active_date', '>', \Carbon\Carbon::now());
                    })->where(function($query) {
                        $query->whereNull('users.join_date')->orWhere('users.join_date', '<=', \Carbon\Carbon::now());
                    });
                else
                    $result = $result->where(function($query) {
                        $query->where(function($query) {
                            $query->whereNotNull('users.non_active_date')->where('users.non_active_date', '<=', \Carbon\Carbon::now());
                        })->orWhere(function($query) {
                            $query->whereNotNull('users.join_date')->where('users.join_date', '>', \Carbon\Carbon::now());
                        });
                    });
        }

        if(!empty($name))
        {
            $result = $result->where(function($table) use($name){
                $table->where('users.name', 'LIKE', '%'. $name .'%')
                    ->orWhere('users.nik', 'LIKE', '%'. $name .'%');
            });
        }

        if(request())
        {
            if(request()->action == 'lock')
            {
                if(empty($year) and empty($month))
                {
                    return redirect()->route('administrator.payroll-monthly.index')->with('message-error', 'Year / Month required.');
                }else{
                    $this->lock_payroll($year,$month);
                }
            }
            if(request()->action == 'submitpayslip')
            {
                if(!isset(request()->user_id)) return redirect()->route('administrator.payroll-monthly.index')->with('message-error', 'Payroll item required.');
                if(empty($year) and empty($month))
                {
                    return redirect()->route('administrator.payroll-monthly.index')->with('message-error', 'Year / Month required.');
                }else{
                    return $this->sendsubmitpayslip($year,$month);
                }
            }
            if(request()->action == 'downloadpayslip')
            {
                if(!isset(request()->user_id)) return redirect()->route('administrator.payroll-monthly.index')->with('message-error', 'Payroll item required.');
                if(empty($year) and empty($month))
                {
                    return redirect()->route('administrator.payroll-monthly.index')->with('message-error', 'Year / Month required.');
                }else{
                    return $this->downloadPayslip($year,$month);
                }
            }
            if(request()->action == 'download')
            {

                if(!empty($year) and empty($month))
                {
                    if(!isset(request()->user_id)) return redirect()->route('administrator.payroll-monthly.index')->with('message-error', 'Payroll item required.');
                    return $this->downloadExcelYear();
                }
//                if(empty($year) and empty($month))
//                {
//                    return redirect()->route('administrator.payroll.index')->with('message-error', 'Year / Month required.');
//                }

                else
                {

                    // if(!empty($year) and !empty($month))
                    // {
                    //     $result = cek_payroll_user_id_array($month, $year);
                    // }
//                    return $this->downloadExcel($result->whereIn('user_id', request()->user_id)->get(),$month,$year);

                    if($result->count()==0)
                        return redirect()->route('administrator.payroll-monthly.index')->with('message-error', 'No data!');
                    return $this->downloadExcel($result->get(),$month,$year);
                }
            }
            if(request()->action == 'downloadBank')
            {
                if(empty($year) and empty($month))
                {
                    return redirect()->route('administrator.payroll-monthly.index')->with('message-error', 'Year / Month required.');
                }
                if(!empty($year) and !empty($month))
                {
                    if($year != date('Y') or $month != (int)date('m'))
                    {
                        $result = cek_payroll_user_id_array($month, $year);
                    }
                    return $this->downloadExcelBank($result->get());
                }
            }

            if(request()->action == 'bukti-potong')
            {
                if(!isset(request()->user_id)) return redirect()->route('administrator.payroll-monthly.index')->with('message-error', 'Payroll item required.');

                if(empty($year))
                {
                    return redirect()->route('administrator.payroll-monthly.index')->with('message-error', 'Year required.');
                }else{
                    return $this->buktiPotong($result->get());
                }
            }

            if(request()->action == 'spt')
            {
                if(!isset(request()->user_id))
                    return redirect()->route('administrator.payroll-monthly.index')->with('message-error', 'Payroll item required.');
                return $this->downloadSpt($result->whereIn('user_id', request()->user_id)->get(), $month, $year);
            }

            if(request()->action == 'send-pay-slip')
            {
                return $this->sendPaySlip();
            }
        }


        if(request()->reset == 1)
        {
            \Session::forget('m-is_calculate');
            \Session::forget('m-employee_status');
            \Session::forget('m-position_id');
            \Session::forget('m-division_id');
            \Session::forget('m-name');
            \Session::forget('m-month');
            \Session::forget('m-year');

            return redirect()->route('administrator.payroll-monthly.index');
        }
        if($user->project_id != NULL)
        {
            $result = $result->where('users.project_id', $user->project_id);
        }

        $params['lock'] = Setting::where('description', 'lock_payroll')->where('project_id', $user->project_id)->orderBy('id', 'ASC')->get();

        if(!$params['lock']->count()){
            Setting::create([
                'key' => 'button_lock',
                'value' => 1,
                'description' => 'lock_payroll',
                'user_created' => $user->id,
                'project_id' => $user->project_id, 
            ]);
            Setting::create([
                'key' => 'payslip_lock',
                'value' => 0,
                'description' => 'lock_payroll',
                'user_created' => $user->id,
                'project_id' => $user->project_id, 
            ]);
            Setting::create([
                'key' => 'schedule_lock',
                'value' => 0,
                'description' => 'lock_payroll',
                'user_created' => $user->id,
                'project_id' => $user->project_id, 
            ]);

            $params['lock'] = Setting::where('description', 'lock_payroll')->where('project_id', $user->project_id)->orderBy('id', 'ASC')->get();
        }

        $params['data'] = $result->get();

        return view('administrator.payroll-monthly.index')->with($params);
    }

    /**
     * Lock Payroll
     * @return return void
     */

    public function buktiPotong()
    {

        $dataRequest = request();

        $check = true;
        foreach ($dataRequest->user_id as $user_id) {
            if (!PayrollHistory::where('is_lock', 1)->where('user_id', $user_id)->whereYear('created_at', $dataRequest->year)->first()) {
                $check = false;
            }
        }

        if (!$check) {
            return redirect()->route('administrator.payroll-monthly.index')->with('message-error', 'Payroll is not define/locked yet!');
        } else {
            $params['data']             = PayrollHistory::groupBy('user_id')->whereIn('user_id', $dataRequest->user_id)->get();
            $params['tahun']            = $dataRequest->year;
            $params['nama_perusahaan']  = get_setting_payroll(1);
            $params['npwp_perusahaan']  = get_setting_payroll(2);
            $params['nama_pemotong']    = get_setting_payroll(3);
            $params['npwp_pemotong']    = get_setting_payroll(4);

            foreach ($params['data'] as $key => $value) {
                $temp = \App\Models\PayrollHistory::where('payroll_id', $value->payroll_id)->whereYear('created_at', $dataRequest->year)->whereNotNull('serial_bukti_potong')->first();
                $max = \App\Models\PayrollHistory::whereYear('created_at', $dataRequest->year)->max('serial_bukti_potong');
                if($temp) {
                    \App\Models\PayrollHistory::where('payroll_id', $value->payroll_id)->whereYear('created_at', $dataRequest->year)->update(['serial_bukti_potong' => $temp->serial_bukti_potong]);
                } else {
                    \App\Models\PayrollHistory::where('payroll_id', $value->payroll_id)->whereYear('created_at', $dataRequest->year)->update(['serial_bukti_potong' => $max ? ++$max : 1]);
                }
            }

            $view = view('administrator.payroll.bukti-potong')->with($params);
            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadHTML($view)->setPaper(array(0,0,595,945));
            return $pdf->download('buktiPotong.pdf');
        }
    }

    public function lock_payroll($year,$month)
    {
        //dd(request()->user_id);
        if(!isset(request()->payroll_id))
        {
            return redirect()->route('administrator.payroll-monthly.index')->with('message-error', 'Select Payroll!');
        }else
        {
            foreach(request()->user_id as $item)
            {
                $dataHistory = get_payroll_history($item,$month,$year);

                if(!isset($dataHistory)) continue;
                if(isset($dataHistory)){
                    $payrollhist = PayrollHistory::where('id', $dataHistory->id)->update(['is_lock' => 1]);
                    LoanPayment::where('payroll_history_id', $dataHistory->id)->update([
                        'status' => 5,
                        'approval_user_id' => \Auth::user()->id
                    ]);
                    Training::where('payroll_history_id', $dataHistory->id)->update([
                        'status_payroll' => 1,
                        'payroll_approval_user_id' => \Auth::user()->id
                    ]);
                    CashAdvance::where('payroll_history_id', $dataHistory->id)->update([
                        'status_payroll' => 1,
                        'payroll_approval_user_id' => \Auth::user()->id
                    ]);
//                    $payroll = Payroll::where('id', PayrollHistory::where('id', $dataHistory->id)->first()->payroll_id)->update(['is_lock' => 1]);
                }
            }
        }
        return redirect()->route('administrator.payroll-monthly.index')->with('message-success', 'Payroll Lock.');
    }

    /**
     * Create Payroll History
     * @param  $id
     * @return void
     */
    public function detailHistory($id)
    {
        $params['data'] = PayrollHistory::where('id', $id)->first();
        $params['update_history'] = true;
        
        return view('administrator.payroll-monthly.detail')->with($params);
    }


    /**
     * Create Payroll By ID
     * @param  $id
     * @return void
     */
    public function createByPayrollId($id)
    {
        $params['data'] = Payroll::where('id', $id)->first();
        //$params['data'] = Payroll::where('id', PayrollHistory::where('id', $id)->first()->payroll_id)->first();
        $params['create_by_payroll_id'] = true;
        
        return view('administrator.payroll-monthly.detail')->with($params);
    }

    /**
     *
     * @return [type] [description]
     */

    /**
     * Download excel year
     * @return object
     */
    public function downloadExcelYear()
    {
        $request = request();

        $dataYtd = new \App\Models\PayrollExportYear($request->year, $request->user_id);
        $count = \App\Models\PayrollHistory::groupBy('user_id')->whereIn('user_id', $request->user_id)->count();
        info($count);
        if($count == 0){
            return redirect()->back()->with('message-error','Selected user has no payrolls');
        }

        return ($dataYtd)->download('EM-HR.Payroll-'. $request->year .'.xlsx');
    }


    /**
     * [downloadExlce description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function downloadExcel($data,$month,$year)
    {
        $params = [];
        $request = request();


        foreach($data as $k =>  $item)
        {
            $bank = Bank::where('id', $item->bank_id)->first();
            $structure = get_user_structure_detail($item->user_id);
            // cek data payroll
            $params[$k][]['No']               = $k+1;
            $params[$k][]['Number']           = $item->number;
            $params[$k][]['Employee ID']      = $item->user->nik;
            $params[$k][]['Fullname']         = $item->user->name;
            $params[$k][]['Status']           = $item->user->organisasi_status ;
            $params[$k][]['NPWP']             = $item->user->npwp_number ;
            $params[$k][]['Position']         = $structure?$structure->position:"";
            $params[$k][]['Division']         = $structure?$structure->division:"";
            $params[$k][]['Joint Date']       = $item->user->join_date?Carbon::createFromFormat('Y-m-d H:i:s', $item->user->join_date)->format('Y-m-d'):null;
            $params[$k][]['Resign Date']      = $item->user->non_active_date;
            $params[$k][]['UMR region']       = $item->umr_label;
            $params[$k][]['UMR value']        = $item->umr_value;
            $params[$k][]['Payroll Cycle']    = $item->payroll_cycle_label;
            $params[$k][]['Payroll Cycle Start'] = $item->payroll_cycle_start ? get_cycle_array($item->payroll_cycle_start, $item->payroll_cycle_end, $month, $year)[0]->format('d/m/Y') : "";
            $params[$k][]['Payroll Cycle End'] = $item->payroll_cycle_start ? get_cycle_array($item->payroll_cycle_start, $item->payroll_cycle_end, $month, $year)[1]->format('d/m/Y') : "";
            $params[$k][]['Attendance Cycle'] = $item->attendance_cycle_label;
            $params[$k][]['Attendance Cycle Start'] = $item->attendance_cycle_label ? get_cycle_array($item->attendance_cycle_start, $item->attendance_cycle_end, $month, $year)[0]->format('d/m/Y') : "";
            $params[$k][]['Attendance Cycle End'] = $item->attendance_cycle_label ? get_cycle_array($item->attendance_cycle_start, $item->attendance_cycle_end, $month, $year)[1]->format('d/m/Y') : "";
            $params[$k][]['Salary']           = $item->salary;
            $params[$k][]['Bonus']            = $item->bonus;
            $params[$k][]['THR']              = $item->thr;
            $params[$k][]['Overtime']         = $item->overtime;

            $params[$k][]['BPJS Jaminan Kecelakaan Kerja (JKK) (Company) '. get_setting('bpjs_jkk_company').'%']  = $item->bpjs_jkk_company;
            $params[$k][]['BPJS Jaminan Kematian (JKM) (Company) '. get_setting('bpjs_jkm_company').'%']          = $item->bpjs_jkm_company;
            $params[$k][]['BPJS Jaminan Hari Tua (JHT) (Company) '. get_setting('bpjs_jht_company').'%']          = $item->bpjs_jht_company;
            $params[$k][]['BPJS Pensiun (Company) '. get_setting('bpjs_pensiun_company').'%']                     = $item->bpjs_pensiun_company;
            $params[$k][]['BPJS Kesehatan (Company) '. get_setting('bpjs_kesehatan_company').'%']                 = $item->bpjs_kesehatan_company; //$item->salary *  get_setting('bpjs_kesehatan_company') / 100;


            $payrollearning = get_earnings();

            foreach($payrollearning as $i)
            {
                if(!empty($year) and !empty($month))
                {
                    $earning = PayrollEarningsEmployeeHistory::where('payroll_id', $item->id)->where('payroll_earning_id', $i->id)->first();
                }else
                {
                    $earning = PayrollEarningsEmployee::where('payroll_id', $item->id)->where('payroll_earning_id', $i->id)->first();
                }

                if($earning)
                {
                    $earning = number_format($earning->nominal);
                }
                else{
                    $earning = 0;
                }

                $params[$k][][$i->title] = $earning;
            }
            // bussiness trip earning
            if(isset($item->businessTrips)){
                $business_trip = 0;
                foreach($item->businessTrips as $i)
                {
                    $total = ($i->sub_total_1_disetujui) + ($i->sub_total_2_disetujui) + ($i->sub_total_3_disetujui) + ($i->sub_total_4_disetujui);
                    $sisa = ($i->pengambilan_uang_muka) - ($total);
                    if(!empty($year) and !empty($month) && $sisa < 0) {
                        $business_trip += -1 * $sisa;
                    }
                }
                $params[$k][]['Total Business Trip Earning'] = $business_trip;
            }

            // cash advance earning
            if(isset($item->cashAdvances)){
                $cash_advance = 0;
                foreach($item->cashAdvances as $i)
                {
                    if($i->cash_advance_form){
                        $total_aproved= 0;
                        $total_claimed= 0;
                        foreach($i->cash_advance_form as $j){
                            $total_aproved += $j->nominal_approved;
                            $total_claimed += $j->nominal_claimed;
                        }
                        $sisa = $total_aproved - $total_claimed;
                        if(!empty($year) and !empty($month) && $sisa < 0) {
                            $cash_advance += -1 * $sisa;
                        }
                    }
                }
                $params[$k][]['Total Cash Advance Earning'] = $cash_advance;
            }

            $params[$k][]['Monthly Income Tax (Company)']                                                           = $item->payroll_type=='GROSS'?'0':$item->pph21;
            $params[$k][]['Total Earnings']                                                                       = $item->total_earnings;

            // loan payments
            if(isset($item->loanPayments)){
                $loan_payments = 0;
                foreach($item->loanPayments as $i)
                {
                    if(!empty($year) and !empty($month)) {
                        $loan_payments += $i->amount;
                    }
                }
                $params[$k][]['Total Loan Payments'] = $loan_payments;
            }

            // bussiness trip deduc
            if(isset($item->businessTrips)){
                $business_trip = 0;
                foreach($item->businessTrips as $i)
                {
                    $total = ($i->sub_total_1_disetujui) + ($i->sub_total_2_disetujui) + ($i->sub_total_3_disetujui) + ($i->sub_total_4_disetujui);
                    $sisa = ($i->pengambilan_uang_muka) - ($total);
                    if(!empty($year) and !empty($month) && $sisa > 0) {
                        $business_trip += $sisa;
                    }
                }
                $params[$k][]['Total Business Trip Deductions'] = $business_trip;
            }

            // cash advance deduc
            if(isset($item->cashAdvances)){
                $cash_advance = 0;
                foreach($item->cashAdvances as $i)
                {
                    if($i->cash_advance_form){
                        $total_aproved= 0;
                        $total_claimed= 0;
                        foreach($i->cash_advance_form as $j){
                            $total_aproved += $j->nominal_approved;
                            $total_claimed += $j->nominal_claimed;
                        }
                        $sisa = $total_aproved - $total_claimed;
                        if(!empty($year) and !empty($month) && $sisa > 0) {
                            $cash_advance += $sisa;
                        }
                    }
                }
                $params[$k][]['Total Cash Advance Deductions'] = $cash_advance;
            }

            // deductions
            $payrolldeduction = get_deductions();

            foreach($payrolldeduction as $i)
            {
                if(!empty($year) and !empty($month))
                {
                    $deduction = PayrollDeductionsEmployeeHistory::where('payroll_id', $item->id)->where('payroll_deduction_id', $i->id)->first();
                }else
                {
                    $deduction = PayrollDeductionsEmployee::where('payroll_id', $item->id)->where('payroll_deduction_id', $i->id)->first();
                }
                if($deduction)
                {
                    $deduction = number_format($deduction->nominal);
                }
                else
                {
                    $deduction = 0;
                }

                $params[$k][][$i->title] = $deduction;
            }

            $params[$k][]['BPJS Jaminan Hari Tua (JHT) (Employee) '. get_setting('bpjs_jaminan_jht_employee').'%']= $item->bpjs_ketenagakerjaan_employee;
            $params[$k][]['BPJS Kesehatan (Employee) '. get_setting('bpjs_kesehatan_employee').'%']               = $item->bpjs_kesehatan_employee; //$item->salary *  get_setting('bpjs_kesehatan_employee') / 100;
            $params[$k][]['BPJS Jaminan Pensiun (JP) (Employee) '. get_setting('bpjs_jaminan_jp_employee').'%']   = $item->bpjs_pensiun_employee;
            //    $params[$k][]['Total BPJS (Company) ']   = Payroll::where('id', $item->id)->first()->bpjstotalearning;
            $params[$k][]['Total BPJS (Company) ']   = $item->bpjstotalearning;

            $params[$k][]['Total Deduction (Burden + BPJS)']      = $item->total_deduction;
            $params[$k][]['Monthly Income Tax (Employee)']                    = $item->pph21;



            $params[$k][]['Take Home Pay']                        = $item->thp;
            $params[$k][]['THP Without Incentive']                = $item->thp - ($item->bonus+$item->thr);
            $params[$k][]['Type Payroll']                         = ($item && !is_null($item->payroll_type))?$item->payroll_type:'NET';
            $params[$k][]['Acc No']                               = isset($item->user->nomor_rekening) ? $item->user->nomor_rekening : '';
            $params[$k][]['Acc Name']                             = isset($item->user->nama_rekening) ? $item->user->nama_rekening : '';
            $params[$k][]['Bank Name']                            = isset($item->user->bank->name) ? $item->user->bank->name : '';
        }


        return (new \App\Models\PayrollExportMonth($year, $month, $params))->download('EM-HR.Payroll-'. $request->year .'-'. $request->month.'.xlsx');
    }

    public function downloadSpt($data, $month, $year)
    {
        $params = [];
        
        $check = true;
        foreach ($data as $k => $item) {
            if (!PayrollHistory::where('is_lock', 1)->where('user_id', $item->user_id)->whereYear('created_at', $year)->first()) {
                $check = false;
            }
        }

        if (!$check) {
            return redirect()->route('administrator.payroll-monthly.index')->with('message-error', 'Payroll is not define/locked yet!');
        }

        foreach($data as $k => $item)
        {
            $bank = Bank::where('id', $item->bank_id)->first();
            $structure = get_user_structure_detail($item->user_id);
            // cek data payroll
            $params[$k][]['Masa Pajak']       = $month;
            $params[$k][]['Tahun Pajak']      = $year;
            $params[$k][]['Pembetulan']       = 0;
            $params[$k][]['NPWP']             = $item->user->npwp_number;
            $params[$k][]['Nama']             = $item->user->name;
            $params[$k][]['Kode Pajak']       = '21-100-01';
            $params[$k][]['Jumlah Bruto']     = $item->total_earnings;
            $params[$k][]['Jumlah PPh']       = $item->payroll_type == 'GROSS' ? '0' : $item->pph21;
            $params[$k][]['Kode Negara']      = $item->user->payrollCountry ? $item->user->payrollCountry->code : '';
        }

        return (new \App\Models\PayrollExportSpt($year, $month, $params))->download('EM-HR.E-SPT-'. $year .'-'. $month.'.csv');
    }

    public function downloadExcelBank($data)
    {
        $params = [];
        $request = request();

        foreach($data as $k =>  $item)
        {
            $bank = Bank::where('id', $item->bank_id)->first();

            // cek data payroll
            $params[$k]['REKENING']         = isset($item->user->nomor_rekening) ? $item->user->nomor_rekening : '';
            $params[$k]['PLUS']             = '+';
            $params[$k]['NOMINAL']          = $item->thp;
            $params[$k]['CD']               = 'C';
            $params[$k]['NO']               = $k+1;
            $params[$k]['NAMA']             = $item->user->name;
            $params[$k]['KETERANGAN']       = '';
            $params[$k]['NAMA BANK']        = isset($item->user->bank->name) ? $item->user->bank->name : '';
        }
        return (new \App\Models\PayrollExportMonth(request()->year, request()->month, $params))->download('EM-HR.Payroll-'. $request->year .'-'. $request->month.'.xlsx');
    }

    /**
     * [import description]
     * @return [type] [description]
     */
    public function import()
    {
        return view('administrator.payroll.import');
    }

    /**
     * [create description]
     * @return [type] [description]
     */
    public function create()
    {
        $month              = \Session::get('m-month');
        $year               = \Session::get('m-year');
        if(!empty($year) && empty($month)){
            return redirect()->route('administrator.payroll-monthly.index')->with('message-error','Select year and month first!');
        }
        return view('administrator.payroll-monthly.create');
    }

    /**
     * [store description]
     * @return [type] [description]
     */
    public function store(Request $request)
    {
        // dd($request);
//        if((!isset($request->salary) || empty($request->salary)) && (!isset($request->salary) || empty($request->salary)) || empty($request->user_id)) {
        if(empty($request->user_id)) {
            return redirect()->route('administrator.payroll-monthly.create')->with('message-error', __('payroll.message-employee-cannot-empty'));
        } else {
            $user = User::find($request->user_id);
            if (!$user) {
                return redirect()->route('administrator.payroll-monthly.create')->with('message-error', __('payroll.message-employee-not-found'));
            } else {
                if(empty($request->month) && empty($request->year)) {
                    $temp = new Payroll();
                    $monthly = false;
                    $temp->umr_value = $user->payroll_umr_id ? $user->payrollUMR->value : PayrollOthers::where('id', 2)->first()->value;
                    $temp->umr_label = $user->payroll_umr_id ? $user->payrollUMR->label : 'Default';
                }
                else{
                    $temp = new PayrollHistory();
                    $temp->number = 'P-' . Carbon::now()->format('mY') . '/' . $user->nik . '-' . (PayrollHistory::where('user_id', $request->user_id)->count() + 1);
                    $payroll = Payroll::where('user_id',$request->user_id)->first();
                    $temp->payroll_id = $payroll->id;
                    $temp->created_at = "$request->year-$request->month-01 00:00:00";
                    $monthly = true;
                    $temp->umr_value = $payroll->umr_value;
                    $temp->umr_label = $payroll->umr_label;

                    if ($user->project_id != null) {
                        $cycle_list = \App\Models\PayrollCycle::where('project_id', $user->project_id)->get();
                    } else {
                        $cycle_list = \App\Models\PayrollCycle::whereNull('project_id')->get();
                    }

                    if ($user->payroll_cycle_id != null) {
                        $cycle = $cycle_list->where('id', $user->payroll_cycle_id)->first();
                    } else {
                        $cycle = $cycle_list->where('key_name', 'payroll')->first();
                    }

                    if ($user->attendance_cycle_id != null) {
                        $attendance = $cycle_list->where('id', $user->attendance_cycle_id)->first();
                    } else {
                        $attendance = $cycle_list->where('key_name', 'attendance')->first();
                    }

                    if ($cycle) {
                        $temp->payroll_cycle_start = $cycle->start_date;
                        $temp->payroll_cycle_end = $cycle->end_date;
                        $temp->payroll_cycle_label = $cycle->label ?: 'Default';
                    }
                    if ($attendance) {
                        $temp->attendance_cycle_start = $attendance->start_date;
                        $temp->attendance_cycle_end = $attendance->end_date;
                        $temp->attendance_cycle_label = $attendance->label ?: 'Default';
                    }
                }

                if(!isset($request->salary) || empty($request->salary)) $request->salary = 0;

                if(!isset($request->bpjs_jkk_company) || empty($request->bpjs_jkk_company)) $request->bpjs_jkk_company = 0;
                if(!isset($request->bpjs_jkm_company) || empty($request->bpjs_jkm_company)) $request->bpjs_jkm_company = 0;
                if(!isset($request->bpjs_jht_company) || empty($request->bpjs_jht_company)) $request->bpjs_jht_company = 0;
                if(!isset($request->bpjs_pensiun_company) || empty($request->bpjs_pensiun_company)) $request->bpjs_pensiun_company = 0;
                if(!isset($request->bpjs_kesehatan_company) || empty($request->bpjs_kesehatan_company)) $request->bpjs_kesehatan_company = 0;
                if(!isset($request->bpjstotalearning) || empty($request->bpjstotalearning)) $request->bpjstotalearning = 0;
                if(!isset($request->bpjs_ketenagakerjaan2) || empty($request->bpjs_ketenagakerjaan2)) $request->bpjs_ketenagakerjaan2 = 0;
                if(!isset($request->bpjs_kesehatan2) || empty($request->bpjs_kesehatan2)) $request->bpjs_kesehatan2 = 0;
                if(!isset($request->bpjs_pensiun2) || empty($request->bpjs_pensiun2)) $request->bpjs_pensiun2 = 0;
                if(!isset($request->thp) || empty($request->thp)) $request->thp = 0;
                if(!isset($request->burden_allow) || empty($request->burden_allow)) $request->burden_allow = 0;
                if(!isset($request->yearly_income_tax) || empty($request->yearly_income_tax)) $request->yearly_income_tax = 0;

                $temp->user_id                          = $request->user_id;
                $temp->salary                           = replace_idr($request->salary);
                $temp->thp                              = replace_idr($request->thp);
                if(!$monthly) {
                    $temp->is_calculate = 1;
                    if($request->pdf_password != null){
                        $temp->pdf_password = $request->pdf_password;
                    }
                    else{
                        $temp->pdf_password = "Temp1234$";
                    }
                }
                $temp->bpjs_jkk_company                 = replace_idr($request->bpjs_jkk_company);
                $temp->bpjs_jkm_company                 = replace_idr($request->bpjs_jkm_company);
                $temp->bpjs_jht_company                 = replace_idr($request->bpjs_jht_company);
                $temp->bpjs_pensiun_company             = replace_idr($request->bpjs_pensiun_company);
                $temp->bpjs_kesehatan_company           = replace_idr($request->bpjs_kesehatan_company);
                $temp->bpjstotalearning                 = replace_idr($request->bpjstotalearning);

                $temp->bpjs_ketenagakerjaan2            = replace_idr($request->bpjs_ketenagakerjaan2);
                $temp->bpjs_kesehatan2                  = replace_idr($request->bpjs_kesehatan2);
                $temp->bpjs_pensiun2                    = replace_idr($request->bpjs_pensiun2);
                $temp->total_deduction                  = $request->total_deductions;
                $temp->total_earnings                   = $request->total_earnings;

                $temp->pph21                            = replace_idr($request->pph21);
                $temp->bpjs_ketenagakerjaan_employee             = replace_idr($request->bpjs_ketenagakerjaan_employee);
                $temp->bpjs_kesehatan_employee                   = replace_idr($request->bpjs_kesehatan_employee);
                $temp->bpjs_pensiun_employee                     = replace_idr($request->bpjs_pensiun_employee);
                $temp->bonus                                     = replace_idr($request->bonus);
                $temp->thr                                       = replace_idr($request->thr);
                $temp->overtime                                  = replace_idr($request->overtime);
                $temp->burden_allow                              = replace_idr($request->burden_allow);
                $temp->yearly_income_tax                         = replace_idr($request->yearly_income_tax);

                $temp->payroll_type                              = $request->payroll_type;


                $temp->save();
                $payroll_id = $temp->id;

                // save earnings

                if(isset($request->earning))
                {
                    foreach($request->earning as $key => $value)
                    {
                        if(!$monthly) {
                            $earning = new PayrollEarningsEmployee();
                        }else{
                            $earning = new PayrollEarningsEmployeeHistory();
                        }
                        $earning->payroll_id            = $payroll_id;
                        $earning->payroll_earning_id    = $value;
                        $earning->nominal               = replace_idr($request->earning_nominal[$key]);
                        $earning->save();
                    }
                }
                // save deductions
                if(isset($request->deduction))
                {
                    foreach($request->deduction as $key => $value)
                    {
                        if(!$monthly) {
                            $deduction = new PayrollDeductionsEmployee();
                        }else{
                            $deduction = new PayrollDeductionsEmployeeHistory();
                        }
                        $deduction->payroll_id            = $payroll_id;
                        $deduction->payroll_deduction_id  = $value;
                        $deduction->nominal               = replace_idr($request->deduction_nominal[$key]);
                        $deduction->save();
                    }
                }
                // save loan payments
                if(isset($request->loan_payment) && $monthly)
                {
                    foreach($request->loan_payment as $key => $value)
                    {
                        $loan_payment = LoanPayment::find($key);
                        $loan_payment->status = 4;
                        $loan_payment->payment_type = 1;
                        $loan_payment->payroll_history_id = $payroll_id;
                        $loan_payment->save();
                    }
                }

                //save business trip
                if(isset($request->business_trip) && $monthly)
                {
                    foreach($request->business_trip as $key => $value)
                    {
                        $training = Training::find($key);
                        $training->status_payroll = 0;
                        $training->payroll_approval_user_id = \Auth::user()->id;
                        $training->payroll_history_id = $payroll_id;
                        $training->save();
                    }
                }

                if(isset($request->training_deduc) && $monthly)
                {
                    foreach($request->training_deduc as $key => $value)
                    {
                        $training = Training::find($key);
                        $training->status_payroll = 0;
                        $training->payroll_approval_user_id = \Auth::user()->id;
                        $training->payroll_history_id = $payroll_id;
                        $training->save();
                    }
                }

                //save cash advance
                if(isset($request->cash_advance) && $monthly)
                {
                    foreach($request->cash_advance as $key => $value)
                    {
                        $ca = CashAdvance::find($key);
                        $ca->status_payroll = 0;
                        $ca->payroll_approval_user_id = \Auth::user()->id;
                        $ca->payroll_history_id = $payroll_id;
                        $ca->save();
                    }
                }

                if(isset($request->ca_deduc) && $monthly)
                {
                    foreach($request->ca_deduc as $key => $value)
                    {
                        $ca = CashAdvance::find($key);
                        $ca->status_payroll = 0;
                        $ca->payroll_approval_user_id = \Auth::user()->id;
                        $ca->payroll_history_id = $payroll_id;
                        $ca->save();
                    }
                }

                if(empty($request->month) && empty($request->year)){
                    return redirect()->route('administrator.payroll.index')->with('message-success', 'Data successfully saved !');
                }
                else{
                    return redirect()->route('administrator.payroll-monthly.index')->with('message-success', 'Data Payroll Monthly successfully saved !');
                }
                
            }
        }
    }

    /**
     * [update description]
     * @param  Request $request [description]
     * @param  [type]  $id      [description]
     * @return [type]           [description]
     */
    public function update(Request $request, $id)
    {

        if(empty($request->month) && empty($request->year)) {
            $temp = Payroll::where('id', $id)->first();
            $monthly = false;
            $temp->umr_value = $temp->user->payroll_umr_id ? $temp->user->payrollUMR->value : PayrollOthers::where('id', 2)->first()->value;
            $temp->umr_label = $temp->user->payroll_umr_id ? $temp->user->payrollUMR->label : 'Default';
        }
        if(isset($request->update_history)) {
            $temp = PayrollHistory::where('id', $id)->first();
            $monthly = true;
            // $temp->umr_value = $temp->payroll->umr_value;
            // $temp->umr_label = $temp->payroll->umr_label;
            // if ($temp->user->project_id != null) {
            //     $cycle_list = \App\Models\PayrollCycle::where('project_id', $temp->user->project_id)->get();
            // } else {
            //     $cycle_list = \App\Models\PayrollCycle::whereNull('project_id')->get();
            // }

            // if ($temp->user->payroll_cycle_id != null) {
            //     $cycle = $cycle_list->where('id', $temp->user->payroll_cycle_id)->first();
            // } else {
            //     $cycle = $cycle_list->where('key_name', 'payroll')->first();
            // }

            // if ($temp->user->attendance_cycle_id != null) {
            //     $attendance = $cycle_list->where('id', $temp->user->attendance_cycle_id)->first();
            // } else {
            //     $attendance = $cycle_list->where('key_name', 'attendance')->first();
            // }

            // if ($cycle) {
            //     $temp->payroll_cycle_start = $cycle->start_date;
            //     $temp->payroll_cycle_end = $cycle->end_date;
            //     $temp->payroll_cycle_label = $cycle->label ?: 'Default';
            // }
            // if ($attendance) {
            //     $temp->attendance_cycle_start = $attendance->start_date;
            //     $temp->attendance_cycle_end = $attendance->end_date;
            //     $temp->attendance_cycle_label = $attendance->label ?: 'Default';
            // }
        }
        if(!$monthly){
            if($request->pdf_password != null){
                $temp->pdf_password = $request->pdf_password;
            }
            else{
                $temp->pdf_password = "Temp1234$";
            }

        }
        $temp->salary                           = replace_idr($request->salary);
        $temp->thp                              = replace_idr($request->thp);
        //$temp->thp                              = $request->thp;
        $temp->bpjs_jkk_company                 = replace_idr($request->bpjs_jkk_company);
        $temp->bpjs_jkm_company                 = replace_idr($request->bpjs_jkm_company);
        $temp->bpjs_jht_company                 = replace_idr($request->bpjs_jht_company);
        $temp->bpjs_pensiun_company             = replace_idr($request->bpjs_pensiun_company);
        $temp->bpjs_kesehatan_company           = replace_idr($request->bpjs_kesehatan_company);
        $temp->bpjstotalearning                 = replace_idr($request->bpjstotalearning);

        $temp->bpjs_ketenagakerjaan2            = replace_idr($request->bpjs_ketenagakerjaan2);
        $temp->bpjs_kesehatan2                  = replace_idr($request->bpjs_kesehatan2);
        $temp->bpjs_pensiun2                    = replace_idr($request->bpjs_pensiun2);
        $temp->total_deduction                  = $request->total_deductions;
        $temp->total_earnings                   = $request->total_earnings;
        $temp->pph21                        = replace_idr($request->pph21);
//        $temp->bpjs_ketenagakerjaan_company     = replace_idr($request->bpjs_ketenagakerjaan_company);
        $temp->bpjs_ketenagakerjaan_employee    = replace_idr($request->bpjs_ketenagakerjaan_employee);
        $temp->bpjs_kesehatan_employee          = replace_idr($request->bpjs_kesehatan_employee);
        $temp->bpjs_pensiun_employee            = replace_idr($request->bpjs_pensiun_employee);
        $temp->bpjs_jaminan_jht_employee    = get_setting('bpjs_jaminan_jht_employee');
        $temp->bpjs_jaminan_jp_employee     = get_setting('bpjs_jaminan_jp_employee');
        $temp->bonus                        = replace_idr($request->bonus);
        $temp->thr                          = replace_idr($request->thr);
        $temp->overtime                     = replace_idr($request->overtime);
        $temp->burden_allow                 = replace_idr($request->burden_allow);
        $temp->yearly_income_tax            = replace_idr($request->yearly_income_tax);
        $temp->is_lock                      = $request->is_lock;
        $temp->payroll_type                 = $request->payroll_type;
        $temp->save();

        if ($request->is_lock) {
            $user_id = $temp->user_id;
            OvertimeSheetForm::whereBetween('claim_approval',[Carbon::parse($temp->created_at)->startOfMonth(), Carbon::parse($temp->created_at)->endOfMonth()])->whereHas('overtimeSheet', function($query) use ($user_id) {
                $query->where('user_id',$user_id);
            })->whereNotNull('payroll_calculate')->update([
                'cutoff' => now()
            ]);

            LoanPayment::where('payroll_history_id', $temp->id)->update([
                'status' => 5,
                'approval_user_id' => \Auth::user()->id
            ]);

            Training::where('payroll_history_id', $temp->id)->update([
                'status_payroll' => 1,
                'payroll_approval_user_id' => \Auth::user()->id
            ]);
            CashAdvance::where('payroll_history_id', $temp->id)->update([
                'status_payroll' => 1,
                'payroll_approval_user_id' => \Auth::user()->id
            ]);
        }

        // save earnings
        if(isset($request->earning))
        {

            foreach($request->earning as $key => $value)
            {
                if(!$monthly)
                    $earning = PayrollEarningsEmployee::where('payroll_id', $id)->where('payroll_earning_id', $value)->first();
                else
                    $earning = PayrollEarningsEmployeeHistory::where('payroll_id', $id)->where('payroll_earning_id', $value)->first();
                if(!$earning)
                {
                    if(!$monthly)
                        $earning                    = new PayrollEarningsEmployee();
                    else
                        $earning                    = new PayrollEarningsEmployeeHistory();
                    $earning->payroll_id            = $id;
                    $earning->payroll_earning_id    = $value;
                }

                $earning->nominal               = replace_idr($request->earning_nominal[$key]);
                $earning->save();
            }
        }

        // save deductions
        if(isset($request->deduction))
        {
            foreach($request->deduction as $key => $value)
            {
                if(!$monthly)
                    $deduction                        = PayrollDeductionsEmployee::where('payroll_id', $id)->where('payroll_deduction_id', $value)->first();
                else
                    $deduction                        = PayrollDeductionsEmployeeHistory::where('payroll_id', $id)->where('payroll_deduction_id', $value)->first();
                if(!$deduction)
                {
                    if(!$monthly)
                        $deduction                    = new PayrollDeductionsEmployee();
                    else
                        $deduction                    = new PayrollDeductionsEmployeeHistory();
                    $deduction->payroll_id            = $id;
                    $deduction->payroll_deduction_id  = $value;
                }

                $deduction->nominal               = replace_idr($request->deduction_nominal[$key]);
                $deduction->save();
            }
        }

        if($monthly)
        {
            return redirect()->route('administrator.payroll-monthly.detail-history', $id)->with('message-success', __('general.message-data-saved-success'));
        }
        else
        {
            return redirect()->route('administrator.payroll.detail', $id)->with('message-success', __('general.message-data-saved-success'));
        }
    }

    /**
     * [download description]
     * @return [type] [description]
     */
    public function download()
    {
        $user = \Auth::user();

        if($user->project_id != Null){
            $users = \App\User::whereIn('access_id', [1,2])->where('project_id', $user->project_id)->get();
        }else{
            $users = \App\User::whereIn('access_id', [1,2])->get();
        }

        $month = \Session::get('m-month');
        $year  = \Session::get('m-year');

        if(!empty($month) && !empty($year)) {
            $monthly = true;
        }
        else{
            $monthly = false;
        }

        if ($user->project_id != null) {
            $cycle_list = \App\Models\PayrollCycle::where('project_id', $user->project_id)->get();
        } else {
            $cycle_list = \App\Models\PayrollCycle::whereNull('project_id')->get();
        }

        // if($monthly) {
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

        $bpjs_jkk_company = get_setting('bpjs_jkk_company');
        $bpjs_jkm_company = get_setting('bpjs_jkm_company');
        $bpjs_jht_company = get_setting('bpjs_jht_company');
        $bpjs_pensiun_company = get_setting('bpjs_pensiun_company');
        $bpjs_kesehatan_company = get_setting('bpjs_kesehatan_company');

        $bpjs_jaminan_jht_employee = get_setting('bpjs_jaminan_jht_employee');
        $bpjs_kesehatan_employee   = get_setting('bpjs_kesehatan_employee');
        $bpjs_jaminan_jp_employee   = get_setting('bpjs_jaminan_jp_employee');

        $params = [];
        foreach($users as $k =>  $i)
        {
            if($monthly) {
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

            $params[$k][]['NO']           = count($params) + 1;
            $params[$k][]['NIK']          = $i->nik;
            $params[$k][]['Nama']         = $i->name;

            if($payroll)
            {
                $params[$k][]['Salary']        = $payroll->salary;
                $params[$k][]['Bonus']         = $payroll->bonus;
                $params[$k][]['THR']           = $payroll->thr;
                $params[$k][]['Overtime']      = $payroll->overtime;
                $params[$k][]['BPJS Jaminan Kecelakaan Kerja (JKK) (Company) '.$bpjs_jkk_company .'%']    = $payroll->bpjs_jkk_company;
                $params[$k][]['BPJS Jaminan Kematian (JKM) (Company) '.$bpjs_jkm_company.'%']             = $payroll->bpjs_jkm_company;
                $params[$k][]['BPJS Jaminan Hari Tua (JHT) (Company) '.$bpjs_jht_company.'%']             = $payroll->bpjs_jht_company;
                $params[$k][]['BPJS Pensiun (Company) '.$bpjs_pensiun_company.'%']                        = $payroll->bpjs_pensiun_company;
                $params[$k][]['BPJS Kesehatan (Company) '.$bpjs_kesehatan_company.'%']                    = $payroll->bpjs_kesehatan_company;

                $payrollearning = get_earnings();
                foreach($payrollearning as $item)
                {
                    if(!$monthly)
                        $earning = PayrollEarningsEmployee::where('payroll_id', $payroll->id)->where('payroll_earning_id', $item->id)->first();
                    else
                        $earning = PayrollEarningsEmployeeHistory::where('payroll_id', $payroll->id)->where('payroll_earning_id', $item->id)->first();
                    if($earning)
                    {
                        $earning = number_format($earning->nominal);
                    }
                    else
                        $earning = 0;

                    $params[$k][][$item->title] = $earning;
                }

                $params[$k][]['BPJS Jaminan Hari Tua (JHT) (Employee) '. $bpjs_jaminan_jht_employee .'%']   = $payroll->bpjs_ketenagakerjaan_employee;
                $params[$k][]['BPJS Kesehatan (Employee) '. $bpjs_kesehatan_employee.'%']                   = $payroll->bpjs_kesehatan_employee;
                $params[$k][]['BPJS Jaminan Pensiun (JP) (Employee) '.$bpjs_jaminan_jp_employee .'%']       = $payroll->bpjs_pensiun_employee;

                // earnings
                $payrolldeduction = get_deductions();
                foreach($payrolldeduction as $item)
                {
                    if(!$monthly)
                        $deduction = PayrollDeductionsEmployee::where('payroll_id', $payroll->id)->where('payroll_deduction_id', $item->id)->first();
                    else
                        $deduction = PayrollDeductionsEmployeeHistory::where('payroll_id', $payroll->id)->where('payroll_deduction_id', $item->id)->first();
                    if($deduction)
                    {
                        $deduction = number_format($deduction->nominal);
                    }
                    else
                        $deduction = 0;

                    $params[$k][][$item->title] = $deduction;
                }
            }
            else
            {
                $params[$k][]['Salary']                     = 0;
                $params[$k][]['Bonus']                      = 0;
                $params[$k][]['THR']                        = 0;
                $params[$k][]['Overtime']                   = 0;
                $params[$k][]['BPJS Jaminan Kecelakaan Kerja (JKK) (Company) '.$bpjs_jkk_company .'%']    = null;
                $params[$k][]['BPJS Jaminan Kematian (JKM) (Company) '.$bpjs_jkm_company.'%']             = null;
                $params[$k][]['BPJS Jaminan Hari Tua (JHT) (Company) '.$bpjs_jht_company.'%']             = null;
                $params[$k][]['BPJS Pensiun (Company) '.$bpjs_pensiun_company.'%']                        = null;
                $params[$k][]['BPJS Kesehatan (Company) '.$bpjs_kesehatan_company.'%']                    = null;

                $payrollearning = get_earnings();
                foreach($payrollearning as $item)
                {
                    $params[$k][][$item->title] = 0;
                }

                $params[$k][]['BPJS Jaminan Hari Tua (JHT) (Employee) '. $bpjs_jaminan_jht_employee .'%']   = null;
                $params[$k][]['BPJS Kesehatan (Employee) '. $bpjs_kesehatan_employee.'%']                   = null;
                $params[$k][]['BPJS Jaminan Pensiun (JP) (Employee) '.$bpjs_jaminan_jp_employee .'%']       = null;

                // earnings
                $payrolldeduction = get_deductions();
                foreach($payrolldeduction as $item)
                {
                    $params[$k][][$item->title] = 0;
                }
            }

            $params[$k][]["Type Payroll"] = $payroll && !is_null($payroll->payroll_type) ? $payroll->payroll_type : 'NET';
            
            if (!$monthly) {
                $params[$k][]["UMR region"] = $payroll && !is_null($payroll->umr_label) ? $payroll->umr_label : '';
            }
        }

        return (new \App\Models\PayrollExport($params))->download('EM-HR.Payroll-Template-'. date('Y-m-d') .'.xlsx');
    }

    /**
     * [detail description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function detail($id)
    {
        //$params['data'] = Payroll::where('id', $id)->first();
        $params['data'] = Payroll::where('id', $id)->first();
        //$params['create_by_payroll_id'] = false;
//        $params['update_history'] = true;
        
        return view('administrator.payroll-monthly.detail')->with($params);
    }

    /**
     * [calculate description]
     * @return [type] [description]
     */
    public function calculate()
    {
        $this->init_calculate();

        return redirect()->route('administrator.payroll-monthly.index')->with('message-success', 'Data Payroll successfully calculated !');
    }

    /**
     * Init payroll non bonus
     * @param  item
     * @return object
     */
    public function init_calculate_non_bonus($item)
    {
        $biaya_jabatan = PayrollOthers::where('id', 1)->first()->value;
        $upah_minimum = PayrollOthers::where('id', 2)->first()->value;

        $temp                   = Payroll::where('id', $item->id)->first();
        $ptkp                   = PayrollPtkp::where('id', 1)->first();
        $bpjs_pensiunan_batas   = PayrollOthers::where('id', 3)->first()->value;
        $bpjs_kesehatan_batas   = PayrollOthers::where('id', 4)->first()->value;

        $month = \Session::get('m-month');
        $year  = \Session::get('m-year');

        //Jika salary dibawah UMP, maka salary untuk perhitungan BPJS berdasarkan UMP
        if($item->salary && $item->salary != 0 && $item->salary<$upah_minimum){
            $salary=$upah_minimum;
        }
        else{
            $salary=$item->salary;
        }

        //JHT EMPLOYEE
        $bpjs_ketenagakerjaan2_persen = get_setting('bpjs_jaminan_jht_employee');
        $bpjs_ketenagakerjaan2 = ($salary * $bpjs_ketenagakerjaan2_persen / 100);

        // start custom
        if(replace_idr($item->bpjs_ketenagakerjaan_employee) != $bpjs_ketenagakerjaan2)
        {
            if($item->is_calculate == 1 )
            {
                $bpjs_ketenagakerjaan2 = replace_idr($item->bpjs_ketenagakerjaan_employee);
            }
        }
        // end custom

        //JHT COMPANY
        $bpjs_jht_company_persen = get_setting('bpjs_jht_company');
        $bpjs_jht_company = ($salary * $bpjs_jht_company_persen / 100);

        // start custom
        if(replace_idr($item->bpjs_jht_company) != $bpjs_jht_company)
        {
            if($item->is_calculate == 1 )
            {
                $bpjs_jht_company = replace_idr($item->bpjs_jht_company);
            }
        }

        //KESEHATAN EMPLOYEE
        $bpjs_kesehatan2        = 0;
        $bpjs_kesehatan2_persen = get_setting('bpjs_kesehatan_employee');
        if($salary <= $bpjs_kesehatan_batas)
        {
            $bpjs_kesehatan2     = ($salary * $bpjs_kesehatan2_persen / 100);
        }
        else
        {
            $bpjs_kesehatan2     = ($bpjs_kesehatan_batas * $bpjs_kesehatan2_persen / 100);
        }

        // start custom
        if(replace_idr($item->bpjs_kesehatan_employee) != $bpjs_kesehatan2)
        {
            if($item->is_calculate == 1 )
            {
                $bpjs_kesehatan2 = replace_idr($item->bpjs_kesehatan_employee);
            }
        }
        // end custom

        //KESEHATAN COMPANY
        $bpjs_kesehatan_company        = 0;
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
        if(replace_idr($item->bpjs_kesehatan_company) != $bpjs_kesehatan_company)
        {
            if($item->is_calculate == 1 )
            {
                $bpjs_kesehatan_company = replace_idr($item->bpjs_kesehatan_company);
            }
        }
        // end custom

        //PENSIUN EMPLOYEE
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
        if(replace_idr($item->bpjs_pensiun_employee) != $bpjs_pensiun2)
        {
            if($item->is_calculate == 1 )
            {
                $bpjs_pensiun2 = replace_idr($item->bpjs_pensiun_employee);
            }
        }
        // end custom

        //PENSIUN COMPANY
        $bpjs_pensiun_company        = 0;
        $bpjs_pensiun_company_persen = get_setting('bpjs_pensiun_company');
        if($salary <= $bpjs_pensiunan_batas)
        {
            $bpjs_pensiun_company     = ($salary * $bpjs_pensiun_company_persen / 100);
        }
        else
        {
            $bpjs_pensiun_company     = ($bpjs_pensiunan_batas * $bpjs_pensiun_company_persen / 100);
        }

        // start custom
        if(replace_idr($item->bpjs_pensiun_company) != $bpjs_pensiun_company)
        {
            if($item->is_calculate == 1 )
            {
                $bpjs_pensiun_company = replace_idr($item->bpjs_pensiun_company);
            }
        }
        // end custom

        //JKK COMPANY
        $bpjs_jkk_company_persen = get_setting('bpjs_jkk_company');
        $bpjs_jkk_company = ($salary * $bpjs_jkk_company_persen / 100);

        // start custom
        if(replace_idr($item->bpjs_jkk_company) != $bpjs_jkk_company)
        {
            if($item->is_calculate == 1 )
            {
                $bpjs_jkk_company = replace_idr($item->bpjs_jkk_company);
            }
        }
        // end custom

        //JKM COMPANY
        $bpjs_jkm_company_persen = get_setting('bpjs_jkm_company');
        $bpjs_jkm_company = ($salary * $bpjs_jkm_company_persen / 100);

        // start custom
        if(replace_idr($item->bpjs_jkm_company) != $bpjs_jkm_company)
        {
            if($item->is_calculate == 1 )
            {
                $bpjs_jkm_company = replace_idr($item->bpjs_jkm_company);
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
        if(isset($item->payrollEarningsEmployee))
        {
            foreach($item->payrollEarningsEmployee as $i)
            {
                if(isset($i->payrollEarnings->title))
                {
                    $earnings += $i->nominal;
                    if($i->payrollEarnings->taxable == 1){
                        $taxable_earning += $i->nominal;
                    }
                }
            }
        }
        $deduction = 0;
        $taxable_deduction = 0;
        if(isset($item->payrollDeductionsEmployee))
        {
            foreach($item->payrollDeductionsEmployee as $i)
            {
                if(isset($i->payrollDeductions->title))
                {
                    $deduction += $i->nominal;
                    if($i->payrollDeductions->taxable == 1){
                        $taxable_deduction += $i->nominal;
                    }
                }
            }
        }

//        $gross_income = ($item->salary + $item->overtime + $earnings + $bpjspenambahan) * 12;
        $gross_income = ($item->salary + $item->overtime + $taxable_earning + $bpjspenambahan) * 12;

        // burdern allowance
//        $burden_allow = 5 * ($item->salary + $earnings + $bpjspenambahan) / 100;
        $burden_allowYear           = 5*($gross_income)/100;
        $burden_allow = $burden_allowYear/12;

        $biaya_jabatan_bulan = $biaya_jabatan / 12;

        if($burden_allow > $biaya_jabatan_bulan)
        {
            $burden_allow = $biaya_jabatan_bulan;
        }

//        $total_deduction = ($bpjspengurangan * 12) + ($burden_allow*12);
        $total_deduction = ($bpjspengurangan * 12) + ($burden_allow*12) + ($taxable_deduction*12);

        //$net_yearly_income          = $gross_income - $total_deduction;
        $net_yearly_val          = $gross_income - $total_deduction;
        $net_yearly_ratusan      = substr($net_yearly_val, -3);
        $net_yearly_income       = $net_yearly_val - $net_yearly_ratusan;

        $untaxable_income = 0;

        $ptkp = \App\Models\PayrollPtkp::where('id', 1)->first();

        if (!empty($month) && !empty($year) && $year > \Carbon\Carbon::now()->format('Y')) {
            $payroll_marital_status = $item->user->marital_status;
            $payroll_jenis_kelamin = $item->user->jenis_kelamin;
        } else {
            $payroll_marital_status = $item->user->payroll_marital_status;
            $payroll_jenis_kelamin = $item->user->payroll_jenis_kelamin;
        }

        if ($payroll_jenis_kelamin == 'Female' || $payroll_jenis_kelamin == ""){
            $untaxable_income = $ptkp->bujangan_wanita;
        }elseif ($payroll_jenis_kelamin == 'Male') {
            # code...
            if($payroll_marital_status == 'Bujangan/Wanita' || $payroll_marital_status == "")
            {
                $untaxable_income = $ptkp->bujangan_wanita;
            }
            if($payroll_marital_status == 'Menikah')
            {
                $untaxable_income = $ptkp->menikah;
            }
            if($payroll_marital_status == 'Menikah Anak 1')
            {
                $untaxable_income = $ptkp->menikah_anak_1;
            }
            if($payroll_marital_status == 'Menikah Anak 2')
            {
                $untaxable_income = $ptkp->menikah_anak_2;
            }
            if($payroll_marital_status == 'Menikah Anak 3')
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

        $params['yearly_income_tax']    = $yearly_income_tax;

        return $params;
    }

    /**
     * Init Calculate
     * @return object
     */
    public function init_calculate()
    {
        $data = Payroll::all();

        $biaya_jabatan = PayrollOthers::where('id', 1)->first()->value;
        $upah_minimum = PayrollOthers::where('id', 2)->first()->value;

        foreach($data as $item)
        {
            if(!isset($item->user->id))
            {
                $p = Payroll::where('user_id', $item->user_id)->first();
                if(!$p)
                {
                    $p->delete();
                }
                continue;
            }
            $month = \Session::get('m-month');
            $year  = \Session::get('m-year');
            if(empty($month) && empty($year)) {
                $temp = Payroll::where('id', $item->id)->first();
                $monthly = false;
            }
            else{
                $temp = PayrollHistory::whereRaw("payroll_id = $item->id and (is_lock = 0 or is_lock is null)")->whereMonth('created_at',$month)->whereYear('created_at',$year)->first();
                if(!$temp){
                    continue;
                }
                $monthly = true;
            }

            //Jika salary dibawah UMP, maka salary untuk perhitungan BPJS berdasarkan UMP
            if($temp->salary && $temp->salary != 0 && $temp->salary<$upah_minimum){
                $salary=$upah_minimum;
            }
            else{
                $salary=$temp->salary;
            }


            $ptkp                   = PayrollPtkp::where('id', 1)->first();
            $bpjs_pensiunan_batas   = PayrollOthers::where('id', 3)->first()->value;
            $bpjs_kesehatan_batas   = PayrollOthers::where('id', 4)->first()->value;

            //$bpjs_ketenagakerjaan_persen = get_setting('bpjs_jkk_company') + get_setting('bpjs_jkm_company');
            //$bpjs_ketenagakerjaan = ($item->salary * $bpjs_ketenagakerjaan_persen / 100);

            //JHT EMPLOYEE
            $bpjs_ketenagakerjaan2_persen = get_setting('bpjs_jaminan_jht_employee');
            $bpjs_ketenagakerjaan2 = ($salary * $bpjs_ketenagakerjaan2_persen / 100);

            // start custom
            if(replace_idr($temp->bpjs_ketenagakerjaan_employee) != $bpjs_ketenagakerjaan2)
            {
                if(!$monthly && $temp->is_calculate == 1 )
                {
                    $bpjs_ketenagakerjaan2 = replace_idr($temp->bpjs_ketenagakerjaan_employee);
                }
            }

            //JHT COMPANY
            $bpjs_jht_company_persen = get_setting('bpjs_jht_company');
            $bpjs_jht_company = ($salary * $bpjs_jht_company_persen / 100);
            // start custom
            if(replace_idr($temp->bpjs_jht_company) != $bpjs_jht_company)
            {
                if(!$monthly && $temp->is_calculate == 1 )
                {
                    $bpjs_jht_company = replace_idr($temp->bpjs_jht_company);
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
            if(replace_idr($temp->bpjs_pensiun_employee) != $bpjs_pensiun2)
            {
                if(!$monthly && $temp->is_calculate == 1 )
                {
                    $bpjs_pensiun2 = replace_idr($temp->bpjs_pensiun_employee);
                }
            }
            // end custom

            //JP COMPANY
            $bpjs_pensiun_company        = 0;
            $bpjs_pensiun_company_persen = get_setting('bpjs_pensiun_company');

            if($salary <= $bpjs_pensiunan_batas)
            {
                $bpjs_pensiun_company     = ($salary * $bpjs_pensiun_company_persen / 100);
            }
            else
            {
                $bpjs_pensiun_company     = ($bpjs_pensiunan_batas * $bpjs_pensiun_company_persen / 100);
            }

            // start custom
            if(replace_idr($temp->bpjs_pensiun_company) != $bpjs_pensiun_company)
            {
                if(!$monthly && $temp->is_calculate == 1 )
                {
                    $bpjs_pensiun_company = replace_idr($temp->bpjs_pensiun_company);
                }
            }
            // end custom

            //KESEHATAN EMPLOYEE
            $bpjs_kesehatan2        = 0;
            $bpjs_kesehatan2_persen = get_setting('bpjs_kesehatan_employee');
            if($salary <= $bpjs_kesehatan_batas)
            {
                $bpjs_kesehatan2     = ($salary * $bpjs_kesehatan2_persen / 100);
            }
            else
            {
                $bpjs_kesehatan2     = ($bpjs_kesehatan_batas * $bpjs_kesehatan2_persen / 100);
            }

            // start custom
            if(replace_idr($temp->bpjs_kesehatan_employee) != $bpjs_kesehatan2)
            {
                if(!$monthly && $temp->is_calculate == 1 )
                {
                    $bpjs_kesehatan2 = replace_idr($temp->bpjs_kesehatan_employee);
                }
            }
            // end custom

            //KESEHATAN COMPANY
            $bpjs_kesehatan_company        = 0;
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
            if(replace_idr($temp->bpjs_kesehatan_company) != $bpjs_kesehatan_company)
            {
                if(!$monthly && $temp->is_calculate == 1 )
                {
                    $bpjs_kesehatan_company = replace_idr($temp->bpjs_kesehatan_company);
                }
            }
            // end custom

            //JKK COMPANY
            $bpjs_jkk_company_persen = get_setting('bpjs_jkk_company');
            $bpjs_jkk_company = ($salary * $bpjs_jkk_company_persen / 100);
            // start custom
            if(replace_idr($temp->bpjs_jkk_company) != $bpjs_jkk_company)
            {
                if(!$monthly && $temp->is_calculate == 1 )
                {
                    $bpjs_jkk_company = replace_idr($temp->bpjs_jkk_company);
                }
            }
            // end custom

            //JKM COMPANY
            $bpjs_jkm_company_persen = get_setting('bpjs_jkm_company');
            $bpjs_jkm_company = ($salary * $bpjs_jkm_company_persen / 100);
            // start custom
            if(replace_idr($temp->bpjs_jkm_company) != $bpjs_jkm_company)
            {
                if(!$monthly && $temp->is_calculate == 1 )
                {
                    $bpjs_jkm_company = replace_idr($temp->bpjs_jkm_company);
                }
            }
            // end custom
            $bpjstotalearning = $bpjs_jkk_company + $bpjs_jkm_company + $bpjs_jht_company + $bpjs_pensiun_company + $bpjs_kesehatan_company;
            //$bpjspenambahan = $bpjstotalearning;
            //$bpjspengurangan = $bpjs_ketenagakerjaan2 + $bpjs_pensiun2 +$bpjs_kesehatan2 + $bpjstotalearning;
            $bpjspenambahan = $bpjs_jkk_company + $bpjs_jkm_company + $bpjs_kesehatan_company;
            $bpjspengurangan = $bpjs_ketenagakerjaan2 + $bpjs_pensiun2;

            $earnings = 0;
            $taxable_earning = 0;
            if(isset($temp->payrollEarningsEmployee))
            {
                foreach($temp->payrollEarningsEmployee as $i)
                {
                    if(isset($i->payrollEarnings->title))
                    {
                        $earnings += $i->nominal;
                        if($i->payrollEarnings->taxable == 1){
                            $taxable_earning += $i->nominal;
                        }
                    }
                }
            }

            $deductions = 0;
            $taxable_deduction = 0;
            if(isset($temp->payrollDeductionsEmployee))
            {
                foreach($temp->payrollDeductionsEmployee as $i)
                {
                    if(isset($i->payrollDeductions->title))
                    {
                        $deductions += $i->nominal;
                        if($i->payrollDeductions->taxable == 1){
                            $taxable_deduction += $i->nominal;
                        }
                    }
                }
            }

//            $gross_income = (($temp->salary + $temp->overtime + $earnings + $bpjspenambahan) * 12 );
            $gross_income = (($temp->salary + $temp->overtime + $taxable_earning + $bpjspenambahan) * 12 );

            // burdern allowance
//            $burden_allow = 5 * ($temp->salary + $earnings + $bpjspenambahan + $temp->bonus) / 100;
            // $burden_allowYear_non_bonus           = 5*($gross_income)/100;
            // $burden_allow_non_bonus = $burden_allowYear_non_bonus/12;

            $gross_income += $temp->bonus + $temp->thr;
            $burden_allowYear           = 5*($gross_income)/100;
            $burden_allow = $burden_allowYear/12;

            // $burdenAllowTHR = 0;
            // if (!empty(\Session::get('m-month')) && !empty(\Session::get('m-year')) && \Session::get('m-month') == 12 && $item->user->id) {
            //     $payrollHistory = PayrollHistory::select('bonus', 'thr')
            //         ->where('user_id', $item->user->id)
            //         // ->where('is_lock', 1)
            //         ->whereYear('created_at', \Session::get('m-year'))
            //         ->whereMonth('created_at', '!=', 12)
            //         ->groupBy('created_at')
            //         ->orderBy('created_at', 'ASC')
            //         ->get();
                
            //     if ($payrollHistory->count() == 11) {
            //         $burdenAllowTHR +=  5*($payrollHistory->sum('bonus') + $temp->bonus + $payrollHistory->sum('thr') + $temp->thr)/100;
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

//            $total_deduction = ($bpjspengurangan * 12) + ($burden_allow*12);
            $total_deduction = ($bpjspengurangan * 12) + ($burden_allow*12) + ($taxable_deduction*12);

            //$net_yearly_income          = $gross_income - $total_deduction;
            $net_yearly_val          = $gross_income - $total_deduction;
            $net_yearly_ratusan      = substr($net_yearly_val, -3);
            $net_yearly_income       = $net_yearly_val - $net_yearly_ratusan;


            $untaxable_income = 0;

            $ptkp = \App\Models\PayrollPtkp::where('id', 1)->first();
            
            if (!empty($month) && !empty($year) && $year > \Carbon\Carbon::now()->format('Y')) {
                $payroll_marital_status = $item->user->marital_status;
                $payroll_jenis_kelamin = $item->user->jenis_kelamin;
            } else {
                $payroll_marital_status = $item->user->payroll_marital_status;
                $payroll_jenis_kelamin = $item->user->payroll_jenis_kelamin;
            }

            if($payroll_jenis_kelamin == 'Female' || $payroll_jenis_kelamin == ""){
                $untaxable_income = $ptkp->bujangan_wanita;
            }elseif ($payroll_jenis_kelamin == 'Male') {
                # code...
                if($payroll_marital_status == 'Bujangan/Wanita' || $payroll_marital_status == "")
                {
                    $untaxable_income = $ptkp->bujangan_wanita;
                }
                if($payroll_marital_status == 'Menikah')
                {
                    $untaxable_income = $ptkp->menikah;
                }
                if($payroll_marital_status == 'Menikah Anak 1')
                {
                    $untaxable_income = $ptkp->menikah_anak_1;
                }
                if($payroll_marital_status == 'Menikah Anak 2')
                {
                    $untaxable_income = $ptkp->menikah_anak_2;
                }
                if($payroll_marital_status == 'Menikah Anak 3')
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

            $gross_income_per_month         = $gross_income / 12;

            $less               = $bpjspengurangan + $monthly_income_tax;

//            $gross_thp = ($temp->salary + $earnings + $temp->bonus);


            #$thp                = $gross_thp - $less - $deductions;

//            $thp = ($temp->salary + $temp->bonus + $earnings + $bpjstotalearning) - ($deductions + $bpjs_ketenagakerjaan2 + $bpjs_kesehatan2 + $bpjs_pensiun2 + $monthly_income_tax + $bpjstotalearning);


            if(!isset($temp->salary) || empty($temp->salary)) $temp->salary = 0;
            if(!isset($thp) || empty($thp)) $thp = 0;

            // start custom
//            $thp                         = $thp + $monthly_income_tax;
            $non_bonus = $this->init_calculate_non_bonus($temp);
            $monthly_income_tax = $yearly_income_tax - $non_bonus['yearly_income_tax'] + ($non_bonus['yearly_income_tax'] / 12);

            if($temp->payroll_type == 'NET')
                $thp = ($temp->salary + $temp->bonus + $temp->thr + $temp->overtime + $earnings + $bpjstotalearning) - ($deductions + $bpjs_ketenagakerjaan2 + $bpjs_kesehatan2 + $bpjs_pensiun2 + $bpjstotalearning);
            else
                $thp = ($temp->salary + $temp->bonus + $temp->thr + $temp->overtime + $earnings + $bpjstotalearning) - ($deductions + $bpjs_ketenagakerjaan2 + $bpjs_kesehatan2 + $bpjs_pensiun2 + $monthly_income_tax + $bpjstotalearning);

            $earnings                     = $earnings + $monthly_income_tax;
            $temp->total_deduction              = $deductions + $bpjs_ketenagakerjaan2 + $bpjs_kesehatan2 + $bpjs_pensiun2 + $monthly_income_tax +$bpjstotalearning;
            if($temp->payroll_type == 'NET')
                $temp->total_earnings               = $temp->salary + $temp->bonus + $temp->thr + $temp->overtime + $earnings + $bpjstotalearning;
            else
                $temp->total_earnings               = $temp->salary + $temp->bonus + $temp->thr + $temp->overtime + $earnings + $bpjstotalearning - $monthly_income_tax;
            $temp->thp                          = $thp;
            $temp->pph21                        = $monthly_income_tax;
//            if(!$monthly)
//                $temp->is_calculate                 = 1;
            $temp->bpjs_ketenagakerjaan_employee    = $bpjs_ketenagakerjaan2;
            $temp->bpjs_kesehatan_employee          = $bpjs_kesehatan2;
            $temp->bpjs_pensiun_employee            = $bpjs_pensiun2;
            $temp->bpjs_jkk_company             = $bpjs_jkk_company;
            $temp->bpjs_jkm_company             = $bpjs_jkm_company;
            $temp->bpjs_jht_company             = $bpjs_jht_company;
            $temp->bpjs_pensiun_company         = $bpjs_pensiun_company;
            $temp->bpjs_kesehatan_company       = $bpjs_kesehatan_company;
            $temp->bpjstotalearning             = $bpjstotalearning;
            $temp->bpjs_jaminan_jht_employee    = get_setting('bpjs_jaminan_jht_employee');
            $temp->bpjs_jaminan_jp_employee     = get_setting('bpjs_jaminan_jp_employee');
            //$temp->bpjs_pensiun_company         = $bpjs_pensiun;
            //$temp->bpjs_kesehatan_company       = $bpjs_kesehatan; //get_setting('bpjs_kesehatan_company');
            $temp->yearly_income_tax            = $yearly_income_tax;
            $temp->burden_allow                 = $burden_allow;
            if($temp->save()){
                info("saving");
            }


            $bonus = $temp->bonus;
            $user_id        = $temp->user_id;
            $payroll_id     = $temp->id;
            // save earnings
            if(!$monthly) {
                if (isset($temp->payrollEarningsEmployee)) {
                    foreach ($temp->payrollEarningsEmployee as $key => $value) {
                        $earning = PayrollEarningsEmployee::where(['payroll_id'=>$payroll_id,'payroll_earning_id'=>$value->payroll_earning_id])->first();
                        if(!$earning) {
                            $earning = new PayrollEarningsEmployee();
                            $earning->payroll_id = $payroll_id;
                            $earning->payroll_earning_id = $value->payroll_earning_id;
                        }
                        $earning->nominal = $value->nominal;
                        $earning->save();
                    }
                }
                // save deductions
                if (isset($temp->payrollDeductionsEmployee)) {
                    foreach ($temp->payrollDeductionsEmployee as $key => $value) {
                        $deduction = PayrollDeductionsEmployee::where(['payroll_id'=>$payroll_id,'payroll_deduction_id'=>$value->payroll_deduction_id])->first();
                        if(!$deduction) {
                            $deduction = new PayrollDeductionsEmployee();
                            $deduction->payroll_id = $payroll_id;
                            $deduction->payroll_deduction_id = $value->payroll_deduction_id;
                        }
                        $deduction->nominal = $value->nominal;
                        $deduction->save();
                    }
                }
            }else{
                if (isset($temp->payrollEarningsEmployee)) {
                    foreach ($temp->payrollEarningsEmployee as $key => $value) {
                        $earning = PayrollEarningsEmployeeHistory::where(['payroll_id'=>$payroll_id,'payroll_earning_id'=>$value->payroll_earning_id])->first();
                        if(!$earning) {
                            $earning = new PayrollEarningsEmployeeHistory();
                            $earning->payroll_id = $payroll_id;
                            $earning->payroll_earning_id = $value->payroll_earning_id;
                        }
                        $earning->nominal = $value->nominal;
                        $earning->save();
                    }
                }
                // save deductions
                if (isset($temp->payrollDeductionsEmployee)) {
                    foreach ($temp->payrollDeductionsEmployee as $key => $value) {
                        $deduction = PayrollDeductionsEmployeeHistory::where(['payroll_id'=>$payroll_id,'payroll_deduction_id'=>$value->payroll_deduction_id])->first();
                        if(!$deduction) {
                            $deduction = new PayrollDeductionsEmployeeHistory();
                            $deduction->payroll_id = $payroll_id;
                            $deduction->payroll_deduction_id = $value->payroll_deduction_id;
                        }
                        $deduction->nominal = $value->nominal;
                        $deduction->save();
                    }
                }
            }

        }
    }

    /**
     * Send Pay Slip
     * @return email
     */
    public function sendsubmitpayslip($year,$month) {
        $request = request();

        $bulanArray = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];

        $bulan = $bulanArray[$month];
        $count=0;
        if(isset($request->user_id))
        {

            foreach($request->user_id as $user_id)
            {
                $user = User::where('id', $user_id)->first();
                // $dataArray   = \DB::select(\DB::raw("SELECT payroll_history.*, month(created_at) as bulan FROM payroll_history WHERE MONTH(created_at)=". $month ." and user_id=". $user_id ." and YEAR(created_at) =". $year. ' ORDER BY id DESC'));
                $dataArray = PayrollHistory::where('user_id', $user_id)
                    ->where(\DB::raw('month(created_at)'), $month)
                    ->where(\DB::raw('year(created_at)'), $year)
                    ->select('payroll_history.*', \DB::raw('month(created_at) as bulan'))
                    ->orderBy('id', 'DESC')
                    ->get();

                // dd($dataArray);

                if(!get_setting('payslip_lock')) {
                    if(!$dataArray){
                        continue;
                    }else {
                        if($dataArray)
                        {
                            $skip = 0;
                            foreach ($dataArray as $key => $value) {
                                if($value->is_lock == 0 || empty($value->is_lock) || $value->is_lock == null) {
                                    $skip = 1;
                                }
                            }
                            if($skip == 1){
                                continue;
                            }
                        }
                    }
                }

                if(!$dataArray){
                    continue;
                }else {
                    $count++;
                    $params['dataArray']            = $dataArray;
                    $params['user']                 = $user;
                    $params['bulan']                = $bulan;
                    $params['tahun']                = $year;
                    $payroll                        = Payroll::where('user_id',$user_id)->first();

                    $view =  view('administrator.payroll.print-pay-slip')->with($params);

                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                    $pdf->stream();
                    if($payroll && !empty($payroll->pdf_password))
                        $pdf->setEncryption($payroll->pdf_password);

                    $output = $pdf->output();
                    $destinationPath = public_path('/storage/temp/');

                    file_put_contents( $destinationPath . $user->nik .'.pdf', $output);

                    $file = $destinationPath . $user->nik .'.pdf';

                    // send email
                    $objDemo = new \stdClass();
                    $objDemo->content = view('administrator.request-pay-slip.email-pay-slip');

                    if($user->email != "")
                    {
                        try {
                            \Mail::send('administrator.request-pay-slip.email-pay-slip', $params,
                                function ($message) use ($file, $user, $bulan) {
                                    $message->to($user->email);
                                    $message->subject('Request Pay-Slip Bulan (' . $bulan . ')');
                                    $message->attach($file, array(
                                            'as' => 'Payslip-' . $user->nik . '(' . $bulan . ').pdf',
                                            'mime' => 'application/pdf')
                                    );
                                    $message->setBody('');
                                }
                            );
                            if(get_setting('payslip_lock')) {
                                foreach ($dataArray as $key => $value) {
                                    if($value->is_lock == 0 || empty($value->is_lock) || $value->is_lock == null) {
                                        $value->is_lock = 1;
                                        $value->save();

                                        LoanPayment::where('payroll_history_id', $value->id)->update([
                                            'status' => 5,
                                            'approval_user_id' => \Auth::user()->id
                                        ]);

                                        Training::where('payroll_history_id', $value->id)->update([
                                            'status_payroll' => 1,
                                            'payroll_approval_user_id' => \Auth::user()->id
                                        ]);
                                        CashAdvance::where('payroll_history_id', $value->id)->update([
                                            'status_payroll' => 1,
                                            'payroll_approval_user_id' => \Auth::user()->id
                                        ]);
                                    }
                                }
                            }
                        }catch (\Swift_RfcComplianceException $e){
                            return redirect()->back()->with('message-error', 'Invalid recipient email configuration!');
                        }catch (\Swift_TransportException $e){
                            return redirect()->back()->with('message-error', 'Invalid sender email configuration!');
                        }
                    }
                }
            }
        }
        if($count>0)
            return redirect()->route('administrator.payroll-monthly.index')->with('message-success', $count.' Pay Slip(s) Send successfully');
        else
            return redirect()->route('administrator.payroll-monthly.index')->with('message-error', 'Failed to send checked payslip!');
    }

    public function downloadPayslip($year, $month)
    {
        $bulanArray = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];
        
        $params['bulan'] = $bulanArray[$month];
        $params['tahun'] = $year;
        $params['dataArray'] = PayrollHistory::whereIn('user_id', request()->user_id)
        ->where(\DB::raw('month(created_at)'), $month)
        ->where(\DB::raw('year(created_at)'), $year)
        ->select('payroll_history.*', \DB::raw('month(created_at) as bulan'))
        ->orderBy('id', 'DESC')
        ->get();
        
        $view = view('administrator.payroll.print-pay-slip')->with($params);
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        return $pdf->download('Payslip-' . $params['bulan'] . '.pdf');
    }

    public function sendPaySlip()
    {
        $request = request();

        if(isset($request->user_id))
        {
            foreach($request->user_id as $user_id)
            {
                $data                       = new RequestPaySlip();
                $data->user_id              = $user_id;
                $data->status               = 1;
                $data->save();

                if(!isset($data->user->nik)) continue;

                foreach($request->bulan as $key => $i)
                {
                    $item               = new RequestPaySlipItem();
                    $item->tahun        = $request->tahun;
                    $item->request_pay_slip_id = $data->id;
                    $item->bulan        = $i;
                    $item->status       = 1;
                    $item->user_id      = $user_id;
                    $item->save();
                }

                $bulanItem = RequestPaySlipItem::where('request_pay_slip_id', $data->id)->get();
                $bulan = [];
                $total = 0;
                $dataArray = [];
                $bulanArray = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];
                foreach($bulanItem as $k => $i)
                {
                    $bulan[$k] = $bulanArray[$i->bulan]; $total++;


                    if($i->bulan == (Int)date('m') and $request->tahun == date('Y'))
                    {
                        $items   = \DB::select(\DB::raw("SELECT payroll.*, month(created_at) as bulan FROM payroll WHERE MONTH(created_at)=". $i->bulan ." and user_id=". $data->user_id ." and YEAR(created_at) =". $request->tahun. ' ORDER BY id DESC'));

                        if($items)
                        {
                            if(isset($items->is_lock) and $items->is_lock == 0) continue; // jika payroll belum di lock payroll jangan dikirim
                        }
                    }
                    else
                        $items   = \DB::select(\DB::raw("SELECT payroll_history.*, month(created_at) as bulan FROM payroll_history WHERE MONTH(created_at)=". $i->bulan ." and user_id=". $data->user_id ." and YEAR(created_at) =". $request->tahun. ' ORDER BY id DESC'));

                    if(!$items)
                    {
                        $items   = \DB::select(\DB::raw("SELECT * FROM payroll_history WHERE user_id=". $data->user_id ." and YEAR(created_at) =". $request->tahun ." ORDER BY id DESC"));

                        if(!$items)
                        {
                            continue;
                        }
                        $dataArray[$k] = $items[0];
                    }
                    else
                    {
                        $dataArray[$k] = $items[0];
                    }
                }

                //    $payroll = Payroll::where('user_id', $request->user_id)->first();

                $params['total']                = $total;
                $params['dataArray']            = $dataArray;
                $params['data']                 = $data;
                $params['bulan']                = $bulan;
                $params['tahun']                = $request->tahun;

                $view =  view('administrator.request-pay-slip.print-pay-slip')->with($params);

                $pdf = \App::make('dompdf.wrapper');
                $pdf->loadHTML($view);

                $pdf->stream();

                $output = $pdf->output();
                $destinationPath = public_path('/storage/temp/');

                file_put_contents( $destinationPath . $data->user->nik .'.pdf', $output);

                $file = $destinationPath . $data->user->nik .'.pdf';

                // send email
                $objDemo = new \stdClass();
                $objDemo->content = view('administrator.request-pay-slip.email-pay-slip');

                if($data->user->email != "")
                {
                    try {
                        \Mail::send('administrator.request-pay-slip.email-pay-slip', $params,
                            function ($message) use ($file, $data, $bulan) {
                                $message->to($data->user->email);
                                $message->subject('Request Pay-Slip Bulan (' . implode('/', $bulan) . ')');
                                $message->attach($file, array(
                                        'as' => 'Payslip-' . $data->user->nik . '(' . implode('/', $bulan) . ').pdf',
                                        'mime' => 'application/pdf')
                                );
                                $message->setBody('');
                            }
                        );
                    }catch (\Swift_RfcComplianceException $e){
                        return redirect()->back()->with('message-error', 'Invalid recipient email configuration!');
                    }catch (\Swift_TransportException $e){
                        return redirect()->back()->with('message-error', 'Invalid sender email configuration!');
                    }
                }

                $data->note     = $request->note;
                $data->status   = 2;
                $data->save();
            }
        }

        return redirect()->route('administrator.payroll-monthly.index')->with('message-success', 'Pay Slip Send successfully');
    }

    /**
     * [import description]
     * @return [type] [description]
     */
    public function tempImport(Request $request)
    {
        $this->validate($request, [
            'file' => 'required',
        ]);

        $month = \Session::get('m-month');
        $year = \Session::get('m-year');

        if($request->hasFile('file'))
        {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($request->file);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = [];
            foreach ($worksheet->getRowIterator() AS $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(FALSE); // This loops through all cells,
                $cells = [];
                foreach ($cellIterator as $cell) {
                    $cells[] = $cell->getValue();
                }
                info($cells);
                $rows[] = $cells;
            }

            $umrs = [];
            if(empty($year) || empty($month)) {
                foreach($rows as $key => $row) {
                    if($key == 0 || empty($row[1]) || $row[count($row) - 1] == 'Default' || empty($row[count($row) - 1])) continue;
                    if(!in_array($row[count($row) - 1], $umrs)) {
                        $umrs[] = $row[count($row) - 1];
                    }
                }
            }

            $errors = '';
            foreach($umrs as $key => $value) {
                if(!PayrollUMR::where('label', $value)->first()) {
                    if ($errors != '')
                        $errors .= ', ';
                    $errors .= $value;
                }
            }

            if ($errors != '') {
                return redirect()->route('administrator.payroll-monthly.index')->with('message-error', 'Region '.$errors.' no found!');
            }

            if (\Auth::user()->project_id != null) {
                $cycle_list = \App\Models\PayrollCycle::where('project_id', \Auth::user()->project_id)->get();
            } else {
                $cycle_list = \App\Models\PayrollCycle::whereNull('project_id')->get();
            }

            // delete all table temp
            foreach($rows as $key => $row)
            {
                $count_row = 12;
                if($key == 0 || empty($row[1])) continue;

                $nik                    = $row[1];
                // cek user
                $user = User::where('nik', $nik)->first();
                if($user) {
                    // check month and year
                    // $month = \Session::get('m-month');
                    // $year = \Session::get('m-year');
                    $new = 0;
                    $prorate = 1;
                    if(!empty($year) && !empty($month)) {
                        $monthly = true;

                        if ($user->payroll_cycle_id != null) {
                            $cycle = $cycle_list->where('id', $user->payroll_cycle_id)->first();
                        } else {
                            $cycle = $cycle_list->where('key_name', 'payroll')->first();
                        }
                        
                        if ($cycle) {
                            if ($cycle->start_date && $cycle->end_date) {
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

                                if(($user->join_date && \Carbon\Carbon::parse($user->join_date)->endOfDay() > \Carbon\Carbon::parse(date_format($end_date, "Y-m-d"))->endOfDay()) || ($user->non_active_date && \Carbon\Carbon::parse($user->non_active_date)->startOfDay() < \Carbon\Carbon::parse(date_format($start_date, "Y-m-d"))->startOfDay())) {
                                    continue;
                                }

                                $prorate = getProrate($user->id);
                            }
                        }

                        if ($user->attendance_cycle_id != null) {
                            $attendance = $cycle_list->where('id', $user->attendance_cycle_id)->first();
                        } else {
                            $attendance = $cycle_list->where('key_name', 'attendance')->first();
                        }

                        $payroll_base = Payroll::where('user_id', $user->id)->first();
                        if(!$payroll_base){
                            continue;
                        }
                        $payroll = PayrollHistory::where(['user_id'=> $user->id,'payroll_id'=>$payroll_base->id])->whereMonth('created_at',$month)->whereYear('created_at',$year)->first();
                        if (!$payroll) {
                            $payroll = new PayrollHistory();
                            $payroll->number = 'P-' . Carbon::now()->format('mY') . '/' . $nik . '-' . (PayrollHistory::where('user_id', $user->id)->count() + 1);
                            $payroll->user_id = $user->id;
                            $payroll->payroll_id = $payroll_base->id;
                            $payroll->created_at = "$year-$month-01 00:00:00";
                        }
                        if($payroll->is_lock == 1){
                            continue;
                        }
                        $payroll->umr_value = $payroll_base->umr_value;
                        $payroll->umr_label = $payroll_base->umr_label;
                        if ($cycle) {
                            $payroll->payroll_cycle_start = $cycle->start_date;
                            $payroll->payroll_cycle_end = $cycle->end_date;
                            $payroll->payroll_cycle_label = $cycle->label ?: 'Default';
                        }
                        if ($attendance) {
                            $payroll->attendance_cycle_start = $attendance->start_date;
                            $payroll->attendance_cycle_end = $attendance->end_date;
                            $payroll->attendance_cycle_label = $attendance->label ?: 'Default';
                        }
                    } else {
                        $monthly = false;
                        $payroll = Payroll::where('user_id', $user->id)->first();
                        if (!$payroll) {
                            $payroll = new Payroll();
                            $payroll->user_id = $user->id;
                            $new = 1;
//                            $is_calculate = 1;
//                            if($payroll->salary == 0)
//                            {
//                                $is_calculate   = 0;
//                            }

//                            if($payroll->salary != replace_idr($row[3])) $is_calculate = 0;
//                            $payroll->is_calculate  = $is_calculate;
                        }
                        $payroll->umr_value = $user->payroll_umr_id ? $user->payrollUMR->value : PayrollOthers::where('id', 2)->first()->value;
                        $payroll->umr_label = $user->payroll_umr_id ? $user->payrollUMR->label : 'Default';
                    }


                    $payroll->salary                    = is_null($row[3]) && isset($payroll_base)?$prorate*$payroll_base->salary:$prorate*replace_idr($row[3]);
                    $payroll->bonus                     = is_null($row[4]) && isset($payroll_base)?$payroll_base->bonus:replace_idr($row[4]);
                    $payroll->thr                       = is_null($row[5]) && isset($payroll_base)?$payroll_base->thr:replace_idr($row[5]);
                    $payroll->overtime                  = is_null($row[6]) && isset($payroll_base)?$payroll_base->overtime:replace_idr($row[6]);
                    $payroll->bpjs_jkk_company          = !is_null($row[7]) && $prorate == 1?replace_idr($row[7]):null;
                    $payroll->bpjs_jkm_company          = !is_null($row[8]) && $prorate == 1?replace_idr($row[8]):null;
                    $payroll->bpjs_jht_company          = !is_null($row[9]) && $prorate == 1?replace_idr($row[9]):null;
                    $payroll->bpjs_pensiun_company      = !is_null($row[10]) && $prorate == 1?replace_idr($row[10]):null;
                    $payroll->bpjs_kesehatan_company    = !is_null($row[11]) && $prorate == 1?replace_idr($row[11]):null;
                    info("bpjs pensiun 1 : ".$payroll->bpjs_pensiun_company);
                    $payroll->save();

                        $payrollearning = get_earnings();
                        foreach($payrollearning as $i)
                        {
                            if(!is_null($row[$count_row]))
                            {
                                if(!$monthly) {
                                    $earning = PayrollEarningsEmployee::where('payroll_id', $payroll->id)->where('payroll_earning_id', $i->id)->first();
                                    if (!$earning) {
                                        $earning = new PayrollEarningsEmployee();
                                        $earning->payroll_id = $payroll->id;
                                        $earning->payroll_earning_id = $i->id;
                                    }
                                }else{
                                    $earning = PayrollEarningsEmployeeHistory::where('payroll_id', $payroll->id)->where('payroll_earning_id', $i->id)->first();
                                    if (!$earning) {
                                        $earning = new PayrollEarningsEmployeeHistory();
                                        $earning->payroll_id = $payroll->id;
                                        $earning->payroll_earning_id = $i->id;
                                    }
                                }

                                $earning->nominal = $prorate*replace_idr($row[$count_row]);
                                $earning->save();

                            } else if ($monthly) {
                                $nominal = $payroll_base->payrollEarningsEmployee->where('payroll_earning_id', $i->id)->first();
                                if ($nominal) {
                                    $earning = PayrollEarningsEmployeeHistory::where('payroll_id', $payroll->id)->where('payroll_earning_id', $i->id)->first();
                                    if (!$earning) {
                                        $earning = new PayrollEarningsEmployeeHistory();
                                        $earning->payroll_id = $payroll->id;
                                        $earning->payroll_earning_id = $i->id;
                                    }
                                    $earning->nominal = $prorate*$nominal->nominal;
                                    $earning->save();
                                }
                            }
                            $count_row++;
                        }

                        $payroll->bpjs_ketenagakerjaan_employee = !is_null($row[$count_row]) && $prorate == 1?replace_idr($row[$count_row]):null;$count_row++;
                        $payroll->bpjs_kesehatan_employee       = !is_null($row[$count_row]) && $prorate == 1?replace_idr($row[$count_row]):null;$count_row++;
                        $payroll->bpjs_pensiun_employee         = !is_null($row[$count_row]) && $prorate == 1?replace_idr($row[$count_row]):null;$count_row++;

                        $payrolldeduction = get_deductions();
                        foreach($payrolldeduction as $i)
                        {
                            if(!is_null($row[$count_row]))
                            {
                                if(!$monthly) {
                                    $deduction = PayrollDeductionsEmployee::where('payroll_id', $payroll->id)->where('payroll_deduction_id', $i->id)->first();
                                    if (!$deduction) {
                                        $deduction = new PayrollDeductionsEmployee();
                                        $deduction->payroll_id = $payroll->id;
                                        $deduction->payroll_deduction_id = $i->id;
                                    }
                                }else{
                                    $deduction = PayrollDeductionsEmployeeHistory::where('payroll_id', $payroll->id)->where('payroll_deduction_id', $i->id)->first();
                                    if (!$deduction) {
                                        $deduction = new PayrollDeductionsEmployeeHistory();
                                        $deduction->payroll_id = $payroll->id;
                                        $deduction->payroll_deduction_id = $i->id;
                                    }
                                }
                                
                                $deduction->nominal = replace_idr($row[$count_row]);
                                $deduction->save();

                            } else if ($monthly) {
                                $nominal = $payroll_base->payrollDeductionsEmployee->where('payroll_deduction_id', $i->id)->first();
                                if ($nominal) {
                                    $deduction = PayrollDeductionsEmployeeHistory::where('payroll_id', $payroll->id)->where('payroll_deduction_id', $i->id)->first();
                                    if (!$deduction) {
                                        $deduction = new PayrollDeductionsEmployeeHistory();
                                        $deduction->payroll_id = $payroll->id;
                                        $deduction->payroll_deduction_id = $i->id;
                                    }
                                    $deduction->nominal = $nominal->nominal;
                                    $deduction->save();
                                }
                            }
                            $count_row++;
                        }
//                    }
                    if(isset($row[$count_row])){
                        $nett = array('NET','NETT','BERSIH');
                        $gross = array('GROSS','GROS','KOTOR');

                        $payroll_type = strtoupper($row[$count_row]);
                        if(in_array($payroll_type,$gross)){
                            $payroll_type = 'GROSS';
                        }
                        else{
                            $payroll_type = 'NET';
                        }
                    }
                    else{
                        if($monthly){
                            $payroll_type = isset($payroll_base->payroll_type)?$payroll_base->payroll_type:'NET';
                        }
                        else{
                            $payroll_type = 'NET';
                        }
                    }
                    if(isset($row[++$count_row]) && (empty($year) || empty($month))){
                        $payroll_umr = $row[$count_row] == 'Default' ?: PayrollUMR::where('label', $row[$count_row])->first();
                        $payroll->umr_value = $row[$count_row] == 'Default' ? PayrollOthers::where('id', 2)->first()->value : $payroll_umr->value;
                        $payroll->umr_label = $row[$count_row] == 'Default' ? 'Default' : $payroll_umr->label;
                        $user->payroll_umr_id = $row[$count_row] == 'Default' ? null : $payroll_umr->id;
                        $user->save();
                    }
                    $payroll->payroll_type = $payroll_type;
                    $payroll->save();

                    if($monthly){
                        $loan_payments = getLoanPayroll($user->id);
                        if(isset($loan_payments) && $monthly) {
                            foreach($loan_payments as $key => $value) {
                                $loan_payment = LoanPayment::find($value->id);
                                $loan_payment->status = 4;
                                $loan_payment->payment_type = 1;
                                $loan_payment->payroll_history_id = $payroll->id;
                                $loan_payment->save();
                            }
                        }

                        $business_trip = getBusinessTripPayment($user->id);
                        if(isset($business_trip) && $monthly) {
                            foreach($business_trip as $key => $value) {
                                $training = Training::find($value->id);
                                $training->status_payroll = 0;
                                $training->payroll_approval_user_id = \Auth::user()->id;
                                $training->payroll_history_id = $payroll->id;
                                $training->save();
                            }
                        }

                        //save cash advance
                        $business_trip = getCashAdvancePayment($user->id);
                        if(isset($cash_advance) && $monthly){
                            foreach($cash_advance as $key => $value)
                            {
                                $ca = CashAdvance::find($key);
                                $ca->status_payroll = 0;
                                $ca->payroll_approval_user_id = \Auth::user()->id;
                                $ca->payroll_history_id = $payroll_id;
                                $ca->save();
                            }
                        }
                    }
                    
                    $this->calculate_payroll($payroll);
                }
            }
            if($monthly){
                $monthly = "Monthly";
            }
            else{
                $monthly = "Default";
            }
            return redirect()->route('administrator.payroll-monthly.index')->with('message-success', 'Data Payroll '.$monthly.' successfully imported!');
        }
    }

    /**
     * Delete Payroll Earning
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function deleteEarningPayroll($id)
    {
        $data = PayrollEarningsEmployee::where('id', $id)->first();
        if($data)
        {
            $payroll_id = $data->payroll_id;

            $data->delete();
        }

        //$this->init_calculate();

        return redirect()->route('administrator.payroll-monthly.detail', $payroll_id);
    }


    /**
     * [desctroy description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function deleteHistory($id)
    {
        LoanPayment::where('payroll_history_id', $id)->update([
            'status' => null,
            'payment_type' => null,
            'payroll_history_id' => null,
            'approval_user_id' => null
        ]);
        Training::where('payroll_history_id', $id)->update([
            'status_payroll' => null,
            'payroll_history_id' => null,
            'payroll_approval_user_id' => null
        ]);
        CashAdvance::where('payroll_history_id', $id)->update([
            'status_payroll' => null,
            'payroll_history_id' => null,
            'payroll_approval_user_id' => null
        ]);

        $data = PayrollHistory::where('id', $id)->first();
        $data->delete();

        return redirect()->back()->with('message-success', 'Data deleted successfully');
    }

    /**
     * Delete Payroll Deduction
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function deleteDeductionPayroll($id)
    {
        $data = PayrollDeductionsEmployee::where('id', $id)->first();
        if($data)
        {
            $payroll_id = $data->payroll_id;

            $data->delete();
        }

        //$this->init_calculate();

        return redirect()->route('administrator.payroll-monthly.detail', $payroll_id);
    }

    private function calculate_payroll($item)
    {
        $biaya_jabatan = PayrollOthers::where('id', 1)->first()->value;
        $upah_minimum = PayrollOthers::where('id', 2)->first()->value;
        if (!isset($item->user->id)) {
            $p = Payroll::where('user_id', $item->user_id)->first();
            if (!$p) {
                $p->delete();
            }
            return;
        } else {
            $user = User::find($item->user->id);
            if($user && $user->payroll_umr_id) {
                $upah_minimum = $user->payrollUMR->value;
            }
        }
       $month = \Session::get('m-month');
       $year = \Session::get('m-year');
//        if (empty($month) && empty($year)) {
//            $item = Payroll::where('id', $item->id)->first();
//            $monthly = false;
//        } else {
//            $item = PayrollHistory::whereRaw("payroll_id = $item->id and (is_lock = 0 or is_lock is null)")->whereMonth('created_at', $month)->whereYear('created_at', $year)->first();
//            if (!$item) {
//                return;
//            }
//            $monthly = true;
//        }

        //Jika salary dibawah UMP, maka salary untuk perhitungan BPJS berdasarkan UMP
        if ($item->salary && $item->salary != 0 && $item->salary < $upah_minimum) {
            $salary = $upah_minimum;
        } else {
            $salary = $item->salary;
        }


        $ptkp = PayrollPtkp::where('id', 1)->first();
        $bpjs_pensiunan_batas = PayrollOthers::where('id', 3)->first()->value;
        $bpjs_kesehatan_batas = PayrollOthers::where('id', 4)->first()->value;

        info("bpjs pensiun 2 : ".$item->bpjs_pensiun_company);

        //JHT EMPLOYEE
        if (!is_null($item->bpjs_ketenagakerjaan_employee)){
            $bpjs_ketenagakerjaan2 = $item->bpjs_ketenagakerjaan_employee;
        }
        else {
            $bpjs_ketenagakerjaan2_persen = get_setting('bpjs_jaminan_jht_employee');
            $bpjs_ketenagakerjaan2 = ($salary * $bpjs_ketenagakerjaan2_persen / 100);
        }

        //JHT COMPANY
        if (!is_null($item->bpjs_jht_company)){
            $bpjs_jht_company = $item->bpjs_jht_company;
        }
        else {
            $bpjs_jht_company_persen = get_setting('bpjs_jht_company');
            $bpjs_jht_company = ($salary * $bpjs_jht_company_persen / 100);
        }

        //JP EMPLOYEE
        if (!is_null($item->bpjs_pensiun_employee)){
            $bpjs_pensiun2 = $item->bpjs_pensiun_employee;
        }
        else {
            $bpjs_pensiun2_persen = get_setting('bpjs_jaminan_jp_employee');
            if ($salary <= $bpjs_pensiunan_batas) {
                $bpjs_pensiun2 = ($salary * $bpjs_pensiun2_persen / 100);
            } else {
                $bpjs_pensiun2 = ($bpjs_pensiunan_batas * $bpjs_pensiun2_persen / 100);
            }
        }

        //JP COMPANY
        if (!is_null($item->bpjs_pensiun_company)){
            $bpjs_pensiun_company = $item->bpjs_pensiun_company;
        }
        else {
            $bpjs_pensiun_company_persen = get_setting('bpjs_pensiun_company');
            if ($salary <= $bpjs_pensiunan_batas) {
                $bpjs_pensiun_company = ($salary * $bpjs_pensiun_company_persen / 100);
            } else {
                $bpjs_pensiun_company = ($bpjs_pensiunan_batas * $bpjs_pensiun_company_persen / 100);
            }
        }


        //KESEHATAN EMPLOYEE
        if (!is_null($item->bpjs_kesehatan_employee)){
            $bpjs_kesehatan2 = $item->bpjs_kesehatan_employee;
        }
        else {
            $bpjs_kesehatan2_persen = get_setting('bpjs_kesehatan_employee');
            if ($salary <= $bpjs_kesehatan_batas) {
                $bpjs_kesehatan2 = ($salary * $bpjs_kesehatan2_persen / 100);
            } else {
                $bpjs_kesehatan2 = ($bpjs_kesehatan_batas * $bpjs_kesehatan2_persen / 100);
            }
        }


        //KESEHATAN COMPANY
        if (!is_null($item->bpjs_kesehatan_company)){
            $bpjs_kesehatan_company = $item->bpjs_kesehatan_company;
        }
        else {
            $bpjs_kesehatan_company_persen = get_setting('bpjs_kesehatan_company');
            if ($salary <= $bpjs_kesehatan_batas) {
                $bpjs_kesehatan_company = ($salary * $bpjs_kesehatan_company_persen / 100);
            } else {
                $bpjs_kesehatan_company = ($bpjs_kesehatan_batas * $bpjs_kesehatan_company_persen / 100);
            }
        }

        //JKK COMPANY
        if (!is_null($item->bpjs_jkk_company)){
            $bpjs_jkk_company = $item->bpjs_jkk_company;
        }
        else {
            $bpjs_jkk_company_persen = get_setting('bpjs_jkk_company');
            $bpjs_jkk_company = ($salary * $bpjs_jkk_company_persen / 100);
        }


        //JKM COMPANY
        if (!is_null($item->bpjs_jkm_company)){
            $bpjs_jkm_company = $item->bpjs_jkm_company;
        }
        else {
            $bpjs_jkm_company_persen = get_setting('bpjs_jkm_company');
            $bpjs_jkm_company = ($salary * $bpjs_jkm_company_persen / 100);
        }


        $bpjstotalearning = $bpjs_jkk_company + $bpjs_jkm_company + $bpjs_jht_company + $bpjs_pensiun_company + $bpjs_kesehatan_company;
        $bpjspenambahan = $bpjs_jkk_company + $bpjs_jkm_company + $bpjs_kesehatan_company;
        $bpjspengurangan = $bpjs_ketenagakerjaan2 + $bpjs_pensiun2;

        $item->bpjs_ketenagakerjaan_employee = $bpjs_ketenagakerjaan2;
        $item->bpjs_kesehatan_employee = $bpjs_kesehatan2;
        $item->bpjs_pensiun_employee = $bpjs_pensiun2;
        $item->bpjs_jkk_company = $bpjs_jkk_company;
        $item->bpjs_jkm_company = $bpjs_jkm_company;
        $item->bpjs_jht_company = $bpjs_jht_company;
        $item->bpjs_pensiun_company = $bpjs_pensiun_company;
        $item->bpjs_kesehatan_company = $bpjs_kesehatan_company;
        $item->bpjstotalearning = $bpjstotalearning;

        $earnings = 0;
        $taxable_earning = 0;
        if (isset($item->payrollEarningsEmployee)) {
            foreach ($item->payrollEarningsEmployee as $i) {
                if (isset($i->payrollEarnings->title)) {
                    $earnings += $i->nominal;
                    if ($i->payrollEarnings->taxable == 1) {
                        $taxable_earning += $i->nominal;
                    }
                }
            }
        }

        $deductions = 0;
        $taxable_deduction = 0;
        if (isset($item->payrollDeductionsEmployee)) {
            foreach ($item->payrollDeductionsEmployee as $i) {
                if (isset($i->payrollDeductions->title)) {
                    $deductions += $i->nominal;
                    if ($i->payrollDeductions->taxable == 1) {
                        $taxable_deduction += $i->nominal;
                    }
                }
            }
        }

        if(isset($item->loanPayments) && !empty($month) && !empty($year)) {
            foreach($item->loanPayments as $i)
            {
                $deductions += $i->amount;
            }
        }

//            $gross_income = (($item->salary + $item->overtime + $earnings + $bpjspenambahan) * 12 );
        $gross_income = (($item->salary + $item->overtime + $taxable_earning + $bpjspenambahan) * 12);

        // burdern allowance
//            $burden_allow = 5 * ($item->salary + $earnings + $bpjspenambahan + $item->bonus) / 100;
        // $burden_allowYear_non_bonus           = 5*($gross_income)/100;
        // $burden_allow_non_bonus = $burden_allowYear_non_bonus/12;

        $gross_income += $item->bonus + $item->thr;
        $burden_allowYear = 5 * ($gross_income) / 100;
        $burden_allow = $burden_allowYear / 12;

        // $burdenAllowTHR = 0;
        // if (!empty(\Session::get('m-month')) && !empty(\Session::get('m-year')) && \Session::get('m-month') == 12 && $item->user->id) {
        //     $payrollHistory = PayrollHistory::select('bonus', 'thr')
        //         ->where('user_id', $item->user->id)
        //         // ->where('is_lock', 1)
        //         ->whereYear('created_at', \Session::get('m-year'))
        //         ->whereMonth('created_at', '!=', 12)
        //         ->groupBy('created_at')
        //         ->orderBy('created_at', 'ASC')
        //         ->get();
            
        //     if ($payrollHistory->count() == 11) {
        //         $burdenAllowTHR +=  5*($payrollHistory->sum('bonus') + $item->bonus + $payrollHistory->sum('thr') + $item->thr)/100;
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

//            $total_deduction = ($bpjspengurangan * 12) + ($burden_allow*12);
        $total_deduction = ($bpjspengurangan * 12) + ($burden_allow * 12) + ($taxable_deduction * 12);

        //$net_yearly_income          = $gross_income - $total_deduction;
        $net_yearly_val = $gross_income - $total_deduction;
        $net_yearly_ratusan = substr($net_yearly_val, -3);
        $net_yearly_income = $net_yearly_val - $net_yearly_ratusan;


        $untaxable_income = 0;

        if (!empty($month) && !empty($year) && $year > \Carbon\Carbon::now()->format('Y')) {
            $payroll_marital_status = $item->user->marital_status;
            $payroll_jenis_kelamin = $item->user->jenis_kelamin;
        } else {
            $payroll_marital_status = $item->user->payroll_marital_status;
            $payroll_jenis_kelamin = $item->user->payroll_jenis_kelamin;
        }

        if ($payroll_jenis_kelamin == 'Female' || $payroll_jenis_kelamin == "") {
            $untaxable_income = $ptkp->bujangan_wanita;
        } elseif ($payroll_jenis_kelamin == 'Male') {
            # code...
            if ($payroll_marital_status == 'Bujangan/Wanita' || $payroll_marital_status == "") {
                $untaxable_income = $ptkp->bujangan_wanita;
            }
            if ($payroll_marital_status == 'Menikah') {
                $untaxable_income = $ptkp->menikah;
            }
            if ($payroll_marital_status == 'Menikah Anak 1') {
                $untaxable_income = $ptkp->menikah_anak_1;
            }
            if ($payroll_marital_status == 'Menikah Anak 2') {
                $untaxable_income = $ptkp->menikah_anak_2;
            }
            if ($payroll_marital_status == 'Menikah Anak 3') {
                $untaxable_income = $ptkp->menikah_anak_3;
            }
        }
        //$taxable_yearly_income = $net_yearly_income - $untaxable_income;
        $taxable_yearly_income_val = $net_yearly_income - $untaxable_income;
        $taxable_yearly_income_ratusan = substr($taxable_yearly_income_val, -3);
        $taxable_yearly_income = $taxable_yearly_income_val - $taxable_yearly_income_ratusan;

        $yearly_income_tax = 0;
            foreach (\App\Models\PayrollPPH::all() as $key => $value) {
                if (($taxable_yearly_income <= $value->batas_atas && $taxable_yearly_income >= $value->batas_bawah && $value->batas_atas != null) || ($taxable_yearly_income >= $value->batas_bawah && $value->batas_atas == null)) {
                    $yearly_income_tax += ($value->tarif / 100) * ($taxable_yearly_income - $value->batas_bawah);
                } else if ($taxable_yearly_income >= $value->batas_atas && $value->batas_atas != null) {
                    $yearly_income_tax += ($value->tarif / 100) * ($value->batas_atas - $value->batas_bawah);
                }
            }
            $monthly_income_tax = $yearly_income_tax / 12;

        if (!isset($item->salary) || empty($item->salary)) $item->salary = 0;
        if (!isset($thp) || empty($thp)) $thp = 0;

        // start custom
//            $thp                         = $thp + $monthly_income_tax;
        $non_bonus = $this->calculate_payroll_non_bonus($item);
        $monthly_income_tax = $yearly_income_tax - $non_bonus['yearly_income_tax'] + ($non_bonus['yearly_income_tax'] / 12);

        if ($item->payroll_type == 'NET')
            $thp = ($item->salary + $item->bonus + $item->thr + $item->overtime + $earnings + $bpjstotalearning) - ($deductions + $bpjs_ketenagakerjaan2 + $bpjs_kesehatan2 + $bpjs_pensiun2 + $bpjstotalearning);
        else
            $thp = ($item->salary + $item->bonus + $item->thr + $item->overtime + $earnings + $bpjstotalearning) - ($deductions + $bpjs_ketenagakerjaan2 + $bpjs_kesehatan2 + $bpjs_pensiun2 + $monthly_income_tax + $bpjstotalearning);

        $earnings = $earnings + $monthly_income_tax;
        $item->total_deduction = $deductions + $bpjs_ketenagakerjaan2 + $bpjs_kesehatan2 + $bpjs_pensiun2 + $monthly_income_tax + $bpjstotalearning;
        if ($item->payroll_type == 'NET')
            $item->total_earnings = $item->salary + $item->bonus + $item->thr + $item->overtime + $earnings + $bpjstotalearning;
        else
            $item->total_earnings = $item->salary + $item->bonus + $item->thr + $item->overtime + $earnings + $bpjstotalearning - $monthly_income_tax;
        $item->thp = $thp;
        $item->pph21 = $monthly_income_tax;
//            if(!$monthly)
//                $item->is_calculate                 = 1;

        $item->bpjs_jaminan_jht_employee = get_setting('bpjs_jaminan_jht_employee');
        $item->bpjs_jaminan_jp_employee = get_setting('bpjs_jaminan_jp_employee');
        //$item->bpjs_pensiun_company         = $bpjs_pensiun;
        //$item->bpjs_kesehatan_company       = $bpjs_kesehatan; //get_setting('bpjs_kesehatan_company');
        $item->yearly_income_tax = $yearly_income_tax;
        $item->burden_allow = $burden_allow;
        if ($item->save()) {
            info("saving");
        }
    }

    private function calculate_payroll_non_bonus($item){
        $biaya_jabatan = PayrollOthers::where('id', 1)->first()->value;

        $bpjspenambahan = $item->bpjs_jkk_company + $item->bpjs_jkm_company+ $item->bpjs_kesehatan_company;
        $bpjspengurangan = $item->bpjs_ketenagakerjaan_employee + $item->bpjs_pensiun_employee;

        $month = \Session::get('m-month');
        $year = \Session::get('m-year');

        $earnings = 0;
        $taxable_earning = 0;
        if(isset($item->payrollEarningsEmployee))
        {
            foreach($item->payrollEarningsEmployee as $i)
            {
                if(isset($i->payrollEarnings->title))
                {
                    $earnings += $i->nominal;
                    if($i->payrollEarnings->taxable == 1){
                        $taxable_earning += $i->nominal;
                    }
                }
            }
        }
        $deduction = 0;
        $taxable_deduction = 0;
        if(isset($item->payrollDeductionsEmployee))
        {
            foreach($item->payrollDeductionsEmployee as $i)
            {
                if(isset($i->payrollDeductions->title))
                {
                    $deduction += $i->nominal;
                    if($i->payrollDeductions->taxable == 1){
                        $taxable_deduction += $i->nominal;
                    }
                }
            }
        }
        if(isset($item->loanPayments) && !empty($month) && !empty($year)) {
            foreach($item->loanPayments as $i)
            {
                $deduction += $i->amount;
            }
        }
        $gross_income = ($item->salary + $item->overtime + $taxable_earning + $bpjspenambahan) * 12;

        $burden_allowYear           = 5*($gross_income)/100;
        $burden_allow = $burden_allowYear/12;

        $biaya_jabatan_bulan = $biaya_jabatan / 12;

        if($burden_allow > $biaya_jabatan_bulan)
        {
            $burden_allow = $biaya_jabatan_bulan;
        }

        $total_deduction = ($bpjspengurangan * 12) + ($burden_allow*12) + ($taxable_deduction*12);

        $net_yearly_val          = $gross_income - $total_deduction;
        $net_yearly_ratusan      = substr($net_yearly_val, -3);
        $net_yearly_income       = $net_yearly_val - $net_yearly_ratusan;

        $untaxable_income = 0;

        $ptkp = \App\Models\PayrollPtkp::where('id', 1)->first();

        if (!empty($month) && !empty($year) && $year > \Carbon\Carbon::now()->format('Y')) {
            $payroll_marital_status = $item->user->marital_status;
            $payroll_jenis_kelamin = $item->user->jenis_kelamin;
        } else {
            $payroll_marital_status = $item->user->payroll_marital_status;
            $payroll_jenis_kelamin = $item->user->payroll_jenis_kelamin;
        }

        if ($payroll_jenis_kelamin == 'Female' || $payroll_jenis_kelamin == ""){
            $untaxable_income = $ptkp->bujangan_wanita;
        }elseif ($payroll_jenis_kelamin == 'Male') {
            # code...
            if($payroll_marital_status == 'Bujangan/Wanita' || $payroll_marital_status == "")
            {
                $untaxable_income = $ptkp->bujangan_wanita;
            }
            if($payroll_marital_status == 'Menikah')
            {
                $untaxable_income = $ptkp->menikah;
            }
            if($payroll_marital_status == 'Menikah Anak 1')
            {
                $untaxable_income = $ptkp->menikah_anak_1;
            }
            if($payroll_marital_status == 'Menikah Anak 2')
            {
                $untaxable_income = $ptkp->menikah_anak_2;
            }
            if($payroll_marital_status == 'Menikah Anak 3')
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

        $params['yearly_income_tax']    = $yearly_income_tax;

        return $params;
    }
}
