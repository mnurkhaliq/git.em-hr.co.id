<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\CutiBersama;
use App\Models\HistoryApprovalOvertime;
use App\Models\LiburNasional;
use App\Models\OvertimePayroll;
use App\Models\OvertimeSheet;
use App\Models\OvertimeSheetForm;
use App\Models\Payroll;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class ApprovalOvertimeCustomController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $params['data'] = cek_overtime_approval();

        return view('karyawan.approval-overtime-custom.index')->with($params);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * [detail description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function detail($id)
    {
        $params['data'] = cek_overtime_id_approval($id);

        if (!$params['data']) {
            return redirect()->route('karyawan.approval.overtime-custom.index')->with('message-error', 'You don\'t have permission to perform this action!');
        }

        $params['data'] = OvertimeSheet::where('id', $id)->first();
        $params['history'] = HistoryApprovalOvertime::where('overtime_sheet_id', $id)->where('structure_organization_custom_id', \Auth::user()->structure_organization_custom_id)->first();

        return view('karyawan.approval-overtime-custom.detail')->with($params);
    }

    public function proses(Request $request)
    {
        $request->validate([
            'noteApproval' => 'required'
        ],
        [
            'noteApproval.required' => 'the note field is required!',
        ]); 

        $user               = Auth::user();
        $overtime           = OvertimeSheet::find($request->id);
        $params             = getEmailConfig();
        $params['data']     = $overtime;
        $params['value']    = $overtime->historyApproval;
        $params['subject']  = get_setting('mail_name') . ' - Overtime Sheet';
        $params['view']     = 'email.overtime-approval-custom';


        $approval                = HistoryApprovalOvertime::where(['overtime_sheet_id'=>$overtime->id,'structure_organization_custom_id'=> $user->structure_organization_custom_id])->first();
        $approval->approval_id   = $user->id;
        $approval->is_approved   = $request->status;
        $approval->date_approved = date('Y-m-d H:i:s');
        $approval->note          = $request->noteApproval;
        $approval->save();

        $db = Config::get('database.default','mysql');

        $notifTitle = "";
        $notifType = "";
        $userApprovalTokens = [];
        $userApprovalIds = [];

        if($approval->is_approved == 0){ // Jika rejected
            $overtime->status = 3;
            $params['text']     = '<p><strong>Dear Sir/Madam '. $overtime->user->name .'</strong>,</p> <p>  Submission of your Overtime <strong style="color: red;">REJECTED</strong>.</p>';
            Config::set('database.default', 'mysql');
            if(!empty($overtime->user->email)) {
                $params['email'] = $overtime->user->email;
                $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                dispatch($job);
            }
            Config::set('database.default', $db);

            $notifTitle = "Overtime Sheet";
            $notifType  = "overtime";
            if($overtime->user->firebase_token) {
                array_push($userApprovalTokens, $overtime->user->firebase_token);
            }
            array_push($userApprovalIds, $overtime->user->id);
        }else if($approval->is_approved == 1){
            foreach ($request->id_overtime_form as $key => $item) {
                $form = \App\Models\OvertimeSheetForm::where('id', $request->id_overtime_form[$key])->first();
                $form->pre_awal_approved = $request->pre_awal_approved[$key];
                $form->pre_akhir_approved = $request->pre_akhir_approved[$key];
                $form->pre_total_approved = $request->pre_total_approved[$key];
                $form->save();
            }

            $lastApproval = $overtime->historyApproval->last();
            if($approval->structure_organization_custom_id == $lastApproval->structure_organization_custom_id){
                $params['text']     = '<p><strong>Dear Sir/Madam '. $overtime->user->name .'</strong>,</p> <p>  Submission of your Overtime <strong style="color: green;">APPROVED</strong>.</p>';
                $overtime->status = 2;
                Config::set('database.default', 'mysql');
                if(!empty($overtime->user->email)) {
                    $params['email'] = $overtime->user->email;
                    $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                    dispatch($job);
                }
                Config::set('database.default', $db);

                $notifTitle = "Overtime Sheet";
                $notifType  = "overtime";
                if($overtime->user->firebase_token) {
                    array_push($userApprovalTokens, $overtime->user->firebase_token);
                }
                array_push($userApprovalIds, $overtime->user->id);
            }else{
                $overtime->status = 1;
                $nextApproval = HistoryApprovalOvertime::where(['overtime_sheet_id'=>$overtime->id,'setting_approval_level_id'=> ($approval->setting_approval_level_id+1)])->first();
                if($nextApproval){
                    $userApproval = user_approval_custom($nextApproval->structure_organization_custom_id);
                    if($userApproval) {
                        Config::set('database.default', 'mysql');
                        foreach ($userApproval as $key => $value) {
                            if ($value->email == "") continue;
                            $params['text']     = '<p><strong>Dear Sir/Madam '. $value->name .'</strong>,</p> <p> '. $overtime->user->name .'  / '.  $overtime->user->nik .' applied for Overtime and currently waiting your approval.</p>';
                            $params['email'] = $value->email;
                            $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                            dispatch($job);
                        }
                        Config::set('database.default', $db);

                        $params['text'] = '<p> '. $overtime->user->name .'  / '.  $overtime->user->nik .' applied for Overtime and currently waiting your approval.</p>';
                        $notifTitle = "Overtime Sheet Approval";
                        $notifType  = "overtime_approval";
                        $userApprovalTokens = user_approval_firebase_tokens($nextApproval->structure_organization_custom_id);
                        $userApprovalIds = user_approval_id($nextApproval->structure_organization_custom_id);
                    }
                }
            }
        }
        $overtime->save();

        foreach ($userApprovalIds as $value) {
            \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $value, $overtime, $notifType);
        }

        if(count($userApprovalTokens) > 0){
            $config = [
                'title' => $notifTitle,
                'content' => strip_tags($params['text']),
                'type' => $notifType,
                'firebase_token' => $userApprovalTokens
            ];
            $notifData = [
                'id' => $overtime->id
            ];
            $db = Config::get('database.default', 'mysql');
            Config::set('database.default', 'mysql');
            dispatch((new \App\Jobs\SendPushTokens($notifData,$config))->onQueue('push'));
            Config::set('database.default', $db);
        }

        return redirect()->route('karyawan.approval.overtime-custom.index')->with('message-success', 'Form Overtime Successfully Processed !');
    }

    public function claim($id)
    {
        $params['data'] = cek_overtime_id_approval($id);

        if (!$params['data']) {
            return redirect()->route('karyawan.approval.overtime-custom.index')->with('message-error', 'You don\'t have permission to perform this action!');
        }
        
        $params['data'] = OvertimeSheet::where('id', $id)->first();
        $params['history'] = HistoryApprovalOvertime::where('overtime_sheet_id', $id)->where('structure_organization_custom_id', \Auth::user()->structure_organization_custom_id)->first();
        $params['paymentType'] = $params['data']->user->overtimePayroll ? $params['data']->user->overtimePayroll->overtime_payroll_type_id : null;

        return view('karyawan.approval-overtime-custom.claim')->with($params);
    }

    public function chekDateOVertime(Request $date)
    {
        $cuti_bersama = CutiBersama::all();
        $libur_nasional = LiburNasional::all();
        $user_ts = strtotime($date);
        $result;

        foreach ($cuti_bersama as $key => $value_cuti) {
            # code...
            $start_ts = strtotime($value_cuti->dari_tanggal);
            if (($user_ts >= $start_ts) && ($user_ts <= $start_ts)) {
                $result = true;
            } else {
                $result = false;
            }
        }
        foreach ($libur_nasional as $key => $value_libur) {
            # code...
            if ($user_ts == $value_libur->tanggal) {
                $result = true;
            } else {
                $result = false;
            }
        }

        return $result;
    }

    public function prosesClaim(Request $request)
    {
        $request->validate([
            'note_claim' => 'required'
        ],
        [
            'note_claim.required' => 'the note field is required!',
        ]); 

        $user               = Auth::user();
        $overtime           = OvertimeSheet::find($request->id);

        if (!$overtime->user->overtime_payroll_id && $request->status == 1) {
            return redirect()->back()->with('message-error', 'This user doesn\'t have overtime payment setting!');
        }

        $params             = getEmailConfig();
        $params['data']     = $overtime;
        $params['value']    = $overtime->historyApproval;
        $params['subject']  = get_setting('mail_name') . ' - Overtime Sheet';
        $params['view']     = 'email.overtime-approval-custom';


        $approval                       = HistoryApprovalOvertime::where(['overtime_sheet_id'=>$overtime->id,'structure_organization_custom_id'=> $user->structure_organization_custom_id])->first();
        $approval->approval_id_claim    = $user->id;
        $approval->is_approved_claim    = $request->status;
        $approval->date_approved_claim  = date('Y-m-d H:i:s');
        $approval->note_claim           = $request->note_claim;
        $approval->save();

        $db = Config::get('database.default','mysql');

        $notifTitle = "";
        $notifType = "";
        $userApprovalTokens = [];
        $userApprovalIds = [];

        if($approval->is_approved_claim == 0){ // Jika rejected
            $overtime->status_claim = 3;
            $params['text'] = '<p><strong>Dear Sir/Madam ' . $overtime->user->name . '</strong>,</p> <p>  Submission of your Claim of Overtime <strong style="color: red;">REJECTED</strong>.</p>';
            Config::set('database.default', 'mysql');
            if($overtime->user->email && $overtime->user->email != "") {
                $params['email'] = $overtime->user->email;
                $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                dispatch($job);
            }
            Config::set('database.default', $db);

            $notifTitle = "Claim Overtime Sheet";
            $notifType  = "overtime";
            if($overtime->user->firebase_token) {
                array_push($userApprovalTokens, $overtime->user->firebase_token);
            }
            array_push($userApprovalIds, $overtime->user->id);
        }else if($approval->is_approved_claim == 1){
            $payment = OvertimePayroll::where('id', $overtime->user->overtime_payroll_id)->with(['overtimePayrollEarning'])->first();

            if ($payment->overtimePayrollEarning[0]->payroll_earning_value) {
                $multipler = $payment->overtimePayrollEarning[0]->payroll_earning_value;
            } else {
                $payroll = Payroll::where('user_id', $overtime->user->id)->with(['payrollEarningsEmployee'])->first();

                $multipler = 0;
                foreach ($payment->overtimePayrollEarning as $value) {
                    if ($value->payroll_attribut) {
                        $multipler += $payroll[$value->payroll_attribut];
                    } else if ($value->payroll_earning_id) {
                        $multipler += $payroll->payrollEarningsEmployee()->where('payroll_earning_id', $value->payroll_earning_id)->first()->nominal;
                    }
                }
            }

            foreach ($request->id_overtime_form as $key => $item) {
                $payroll_calculate = null;
                if ($payment->overtime_payroll_type_id == 1) {
                    $payroll_calculate = $request->overtime_calculate[$key] / 173 * $multipler;
                } else if ($payment->overtime_payroll_type_id == 2) {
                    $total_lembur_approved = explode(':', $request->total_lembur_approved[$key]);
                    $total_lembur_approved = $total_lembur_approved[0] + $total_lembur_approved[1] / 60;
                    $payroll_calculate = $total_lembur_approved * $multipler;
                } else if ($payment->overtime_payroll_type_id == 3) {
                    $payroll_calculate = $multipler;
                }

                $form = \App\Models\OvertimeSheetForm::where('id', $request->id_overtime_form[$key])->first();
                $form->awal_approved = $request->awal_approved[$key];
                $form->akhir_approved = $request->akhir_approved[$key];
                $form->total_lembur_approved = $request->total_lembur_approved[$key];
                $form->overtime_payroll_type_id = $payment->overtime_payroll_type_id;
                $form->overtime_calculate = $request->overtime_calculate[$key];
                $form->meal_allowance = preg_replace('/[^0-9]/', '', $request->meal_allowance[$key]);
                $form->payroll_calculate = $payroll_calculate;
                $form->claim_approval = now();
                $form->save();
            }

            $lastApproval = $overtime->historyApproval->last();
            if($approval->structure_organization_custom_id == $lastApproval->structure_organization_custom_id){
                $params['text']     = '<p><strong>Dear Sir/Madam ' . $overtime->user->name . '</strong>,</p> <p>  Submission of your Claim of Overtime <strong style="color: green;">APPROVED</strong>.</p>';
                $overtime->status_claim = 2;
                Config::set('database.default', 'mysql');
                if($overtime->user->email && $overtime->user->email != "") {
                    $params['email'] = $overtime->user->email;
                    $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                    dispatch($job);
                }
                Config::set('database.default', $db);

                $notifTitle = "Claim Overtime Sheet";
                $notifType  = "overtime";
                if($overtime->user->firebase_token) {
                    array_push($userApprovalTokens, $overtime->user->firebase_token);
                }
                array_push($userApprovalIds, $overtime->user->id);
            }else{
                $overtime->status_claim = 1;
                $nextApproval = HistoryApprovalOvertime::where(['overtime_sheet_id'=>$overtime->id,'setting_approval_level_id'=> ($approval->setting_approval_level_id+1)])->first();
                if($nextApproval){
                    $userApproval = user_approval_custom($nextApproval->structure_organization_custom_id);
                    if($userApproval) {
                        Config::set('database.default', 'mysql');
                        foreach ($userApproval as $key => $value) {
                            if ($value->email == "") continue;
                            $params['text']     = '<p><strong>Dear Sir/Madam '. $value->name .'</strong>,</p> <p> '. $overtime->user->name .'  / '.  $overtime->user->nik .' applied for Claim of Overtime and currently waiting your approval.</p>';
                            $params['email'] = $value->email;
                            $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                            dispatch($job);
                        }
                        Config::set('database.default', $db);

                        $params['text'] = '<p> '. $overtime->user->name .'  / '.  $overtime->user->nik .' applied for Claim of Overtime and currently waiting your approval.</p>';
                        $notifTitle = "Claim Overtime Sheet Approval";
                        $notifType  = "overtime_approval";
                        $userApprovalTokens = user_approval_firebase_tokens($nextApproval->structure_organization_custom_id);
                        $userApprovalIds = user_approval_id($nextApproval->structure_organization_custom_id);
                    }
                }
            }
        }
        $overtime->save();

        foreach ($userApprovalIds as $value) {
            \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $value, $overtime, $notifType);
        }

        if(count($userApprovalTokens) > 0){
            $config = [
                'title' => $notifTitle,
                'content' => strip_tags($params['text']),
                'type' => $notifType,
                'firebase_token' => $userApprovalTokens
            ];
            $notifData = [
                'id' => $overtime->id
            ];
            $db = Config::get('database.default', 'mysql');
            Config::set('database.default', 'mysql');
            dispatch((new \App\Jobs\SendPushTokens($notifData,$config))->onQueue('push'));
            Config::set('database.default', $db);
        }

        return redirect()->route('karyawan.approval.overtime-custom.index')->with('message-success', 'Form Overtime Successfully Processed !');
    }

}
