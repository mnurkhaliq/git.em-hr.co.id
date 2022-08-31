<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseApiController;
use App\Http\Middleware\ConnectionMiddleware;
use App\Http\Resources\OvertimeResource;
use App\Models\HistoryApprovalOvertime;
use App\Models\OvertimeSheet;
use App\Models\OvertimeSheetForm;
use App\Models\OvertimePayroll;
use App\Models\Payroll;
use App\Models\CutiBersama;
use App\Models\LiburNasional;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class OvertimeController extends BaseApiController
{
    public function __construct()
    {
        $this->middleware(ConnectionMiddleware::class);
        $this->middleware('jwt.auth');
        parent::__construct();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $user = Auth::user();
        $status = $request->input('status','[1,2,3]');
        if(!isJsonValid($status))
            return response()->json(['status' => 'error','message'=>'Status is invalid'], 404);
        $status = json_decode($status);
        $histories = OvertimeSheet::with(['overtime_form','historyApproval'])->where(['user_id'=>$user->id])->where(function ($query) use ($status){
            $query->whereIn('status',$status)->orWhereIn('status_claim',$status);
        })->orderBy('created_at','DESC');
        $totalData = $histories->get()->count();
        $histories = $histories->paginate(10);
        $data = [
            'current_page' => $histories->currentPage(), // get current page number
            'total_page' => $histories->total() ? $histories->lastPage() : $histories->total(), // get last page number
            'total_data' => $totalData,
            'overtimes' => OvertimeResource::collection($histories)
        ];
        return response()->json(
            [
                'status' => 'success',
                'message' => 'Successfully',
                'data' => $data
            ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $approval = $user->approval;
        if($approval == null){
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Your position is not defined yet. Please contact your admin!'
                ], 403);
        }else if(count($approval->itemsOvertime) == 0){
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Setting approval is not defined yet. Please contact your admin!'
                ], 403);
        }
        $validator = Validator::make($request->all(), [
            "details"                     => "required|array",
            'details.*.tanggal'           => 'required|date',
            'details.*.description'       => "required",
            'details.*.awal'              => "required",
            'details.*.akhir'             => "required",
            'details.*.total_lembur'      => "required",
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()],401);
        }

        $data                       = new OvertimeSheet();
        $data->user_id              = $user->id;
        $data->status               = 1;
        $data->save();

        if($request->details){
            foreach ($request->details as $no => $detail){
                $form                    = new OvertimeSheetForm();
                $form->overtime_sheet_id = $data->id;
                $form->tanggal           = $detail['tanggal'];
                $form->description       = $detail['description'];
                $form->awal              = $detail['awal'];
                $form->akhir             = $detail['akhir'];
                $form->total_lembur      = $detail['total_lembur'];
                $form->save();
            }
        }


        $historyApproval     = $user->approval->itemsOvertime;
        $settingApprovalItem = $historyApproval[0]->structure_organization_custom_id;
        foreach ($historyApproval as $level => $value) {
            # code...
            $history = new HistoryApprovalOvertime();
            $history->overtime_sheet_id                = $data->id;
            $history->setting_approval_level_id        = ($level+1);
            $history->structure_organization_custom_id = $value->structure_organization_custom_id;
            $history->save();
        }
        $historyApprov = HistoryApprovalOvertime::where('overtime_sheet_id',$data->id)->orderBy('setting_approval_level_id','asc')->get();

        $userApproval = user_approval_custom($settingApprovalItem);
        $params = getEmailConfig();
        $db = Config::get('database.default','mysql');

        $params['data']     = $data;
        $params['value']    = $historyApprov;
        $params['view']     = 'email.overtime-approval-custom';
        $params['subject']  = get_setting('mail_name') . ' - Overtime Sheet';
        if($userApproval) {
            Config::set('database.default', 'mysql');
            foreach ($userApproval as $key => $value) {
                if (empty($value->email)) continue;
                $params['email'] = $value->email;
                $params['text']  = '<p><strong>Dear Sir/Madam '. $value->name .'</strong>,</p> <p> '. $data->user->name .'  / '.  $data->user->nik .' applied for Overtime and currently waiting your approval.</p>';
                $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                dispatch($job);
            }
            Config::set('database.default', $db);
            $params['text']  = '<p> '. $data->user->name .'  / '.  $data->user->nik .' applied for Overtime and currently waiting your approval.</p>';
        }

        $userApprovalTokens = user_approval_firebase_tokens($settingApprovalItem);
        info("structure id ".$settingApprovalItem);
        info($userApprovalTokens);

        foreach (user_approval_id($settingApprovalItem) as $value) {
            \FRDHelper::setNewData(strtolower($request->company), $value, $data, 'overtime_approval');
        }

        if(count($userApprovalTokens) > 0){
            $config = [
                'title' => "Overtime Sheet Approval",
                'content' => strip_tags($params['text']),
                'type' => 'overtime_approval',
                'firebase_token' => $userApprovalTokens
            ];
            $notifData = [
                'id' => $data->id
            ];
            info($userApprovalTokens);
            $db = Config::get('database.default', 'mysql');
            Config::set('database.default', 'mysql');
            dispatch((new \App\Jobs\SendPushTokens($notifData,$config))->onQueue('push'));
            Config::set('database.default', $db);
        }

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Your overtime request has successfully submitted'
            ], 201);
    }

    public function getParams(Request $request)
    {
        //
        if(!$request->user_id)
            return response()->json(['status' => 'error', 'message' => 'User ID is required!'], 403);
        $user = User::find($request->user_id);
        if(!$user)
            return response()->json(['status' => 'error', 'message' => 'User is not found!'], 404);
        if($request->type == 'create'){
            $approval = $user->approval;
            if($approval == null){
                return response()->json(['status' => 'error','message' => 'Your position is not defined yet. Please contact your admin!'], 403);
            }else if(count($approval->itemsOvertime) == 0){
                return response()->json(['status' => 'error', 'message' => 'Setting approval is not defined yet. Please contact your admin!'], 403);
            }
        }
        return response()->json(
            [
                'status' => 'success',
                'message' => 'Successfully',
            ], 200);
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
        $user = Auth::user();
        $data['overtime'] = new OvertimeResource(OvertimeSheet::findOrFail($id));
        return response()->json(
            [
                'status' => 'success',
                'message' => 'Successfully',
                'data' => $data
            ], 200);
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

    public function getApproval(Request $request){
        $status = $request->status?$request->status:"all";
        $user = Auth::user();
        $approval = null;
        if($status == 'ongoing') {
            $approval = OvertimeSheet::join('history_approval_overtime as h', function ($join) use ($user) {
                $join->on('overtime_sheet.id', '=', 'h.overtime_sheet_id')
                    ->where('h.setting_approval_level_id', '=', DB::raw('(select min(setting_approval_level_id) from history_approval_overtime where overtime_sheet_id = overtime_sheet.id and (is_approved is null or (is_approved_claim is null and overtime_sheet.status = 2)))'))
                    ->where('h.structure_organization_custom_id', '=', $user->structure_organization_custom_id);
            })
                ->where(function ($query){
                    $query->where('overtime_sheet.status',1)->orWhere('overtime_sheet.status_claim',1);
                })
                ->orderBy('created_at','DESC')
                ->select('overtime_sheet.*');
        }else if($status == 'history'){
            $approval = OvertimeSheet::join('history_approval_overtime as h', function ($join) use ($user) {
                $join->on('overtime_sheet.id', '=', 'h.overtime_sheet_id')
                    ->whereNotNull('h.is_approved')
                    ->where(function($query) {
                      $query->where('overtime_sheet.status_claim','!=',1)->orWhereNull('overtime_sheet.status_claim');
                    })
                    ->where('h.structure_organization_custom_id', '=', $user->structure_organization_custom_id);
            })
                ->orderBy('created_at','DESC')
                ->select('overtime_sheet.*');
        }
        else if($status == 'all'){
            $approval = OvertimeSheet::join('history_approval_overtime as h', function ($join) use ($user) {
                $join->on('overtime_sheet.id', '=', 'h.overtime_sheet_id')
                    ->where('h.structure_organization_custom_id', '=', $user->structure_organization_custom_id);
            })
                ->orderBy('created_at','DESC')
                ->select('overtime_sheet.*');
        }
        $totalData = $approval->get()->count();
        $approval = $approval->paginate(10);
        $data = [
            'current_page' => $approval->currentPage(), // get current page number
            'total_page' => $approval->total() ? $approval->lastPage() : $approval->total(), // get last page number
            'total_data' => $totalData,
            'overtimes' => OvertimeResource::collection($approval)
        ];
        return response()->json(
            [
                'status' => 'success',
                'message' => 'Successfully',
                'data' => $data
            ], 200);
    }

    public function approve(Request $request){
        info($request->all());
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'overtime.id'                           => 'required|exists:overtime_sheet,id',
            'overtime.details.*.id'                 => 'required|exists:overtime_sheet_form,id',
            'overtime.details.*.pre_awal_approved'  => "required",
            'overtime.details.*.pre_akhir_approved' => "required",
            'overtime.details.*.pre_total_approved' => "required",
            'approval.note'                         => "required",
            'approval.is_approved'                  => "required|in:1,0",
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()],401);
        }

        $overtime           = OvertimeSheet::find($request->overtime['id']);
        $params             = getEmailConfig();
        $params['data']     = $overtime;
        $params['value']    = $overtime->historyApproval;
        $params['subject']  = get_setting('mail_name') . ' - Overtime Sheet';
        $params['view']     = 'email.overtime-approval-custom';


        $approval                = HistoryApprovalOvertime::where(['overtime_sheet_id'=>$overtime->id,'structure_organization_custom_id'=> $user->structure_organization_custom_id])->first();
        $approval->approval_id   = $user->id;
        $approval->is_approved   = $request->approval['is_approved'];
        $approval->date_approved = date('Y-m-d H:i:s');
        $approval->note          = $request->approval['note'];
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
            foreach ($request->overtime['details'] as $detail){
                $form = OvertimeSheetForm::where('id', $detail['id'])->first();
                if($form)
                {
                    $form->pre_awal_approved     = $detail['pre_awal_approved'];
                    $form->pre_akhir_approved    = $detail['pre_akhir_approved'];
                    $form->pre_total_approved    = $detail['pre_total_approved'];
                    $form->save();
                }
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
            \FRDHelper::setNewData(strtolower($request->company), $value, $overtime, $notifType);
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

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Form Overtime Successfully Processed !',
            ], 200);
    }

    public function claim(Request $request){
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'id'                            => 'required|exists:overtime_sheet,id',
            'details.*.overtime_form_id'    => 'required|exists:overtime_sheet_form,id',
            'details.*.awal_claim'          => 'required',
            'details.*.akhir_claim'         => "required",
            'details.*.total_lembur_claim'  => "required",
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()],401);
        }
        $id = $request->id;
        $overtime = OvertimeSheet::find($id);
        if(!$overtime || $overtime->user_id != $user->id){
            return response()->json(['status' => 'failed', 'message' => 'Overtime is not found'],404);
        }

        $overtime->status_claim               = 1;
        $overtime->date_claim                 = date('Y-m-d H:i:s');

        if($request->details){
            foreach ($request->details as $no => $detail){
                $form                       = OvertimeSheetForm::find($detail['overtime_form_id']);
                $form->awal_claim           = $detail['awal_claim'];
                $form->akhir_claim          = $detail['akhir_claim'];
                $form->total_lembur_claim   = $detail['total_lembur_claim'];
                $form->save();
            }
        }
        $overtime->save();

        $historyApprov        = HistoryApprovalOvertime::where('overtime_sheet_id',$overtime->id)->orderBy('setting_approval_level_id','asc')->get();
        if(count($historyApprov)>0) {
            $settingApprovalItem = $historyApprov[0]->structure_organization_custom_id;
            $userApproval = user_approval_custom($settingApprovalItem);
            $params = getEmailConfig();
            $db = Config::get('database.default', 'mysql');

            $params['data'] = $overtime;
            $params['value'] = $historyApprov;
            $params['view'] = 'email.overtime-approval-custom';
            $params['subject'] = get_setting('mail_name') . ' - Overtime Sheet';
            if ($userApproval) {
                Config::set('database.default', 'mysql');
                foreach ($userApproval as $key => $value) {
                    if (empty($value->email)) continue;
                    $params['email'] = $value->email;
                    $params['text'] = '<p><strong>Dear Sir/Madam ' . $value->name . '</strong>,</p> <p> ' . $overtime->user->name . '  / ' . $overtime->user->nik . ' applied for Claim of Overtime and currently waiting your approval.</p>';
                    $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                    dispatch($job);
                }
                Config::set('database.default', $db);
                $params['text'] = '<p> ' . $overtime->user->name . '  / ' . $overtime->user->nik . ' applied for Claim of Overtime and currently waiting your approval.</p>';
            }
        }

        $userApprovalTokens = user_approval_firebase_tokens($settingApprovalItem);
        info("structure id ".$settingApprovalItem);
        info($userApprovalTokens);

        foreach (user_approval_id($settingApprovalItem) as $value) {
            \FRDHelper::setNewData(strtolower($request->company), $value, $overtime, 'overtime_approval');
        }

        if(count($userApprovalTokens) > 0){
            $config = [
                'title' => "Claim Overtime Sheet Approval",
                'content' => strip_tags($params['text']),
                'type' => 'overtime_approval',
                'firebase_token' => $userApprovalTokens
            ];
            $notifData = [
                'id' => $overtime->id
            ];
            info($userApprovalTokens);
            $db = Config::get('database.default', 'mysql');
            Config::set('database.default', 'mysql');
            dispatch((new \App\Jobs\SendPushTokens($notifData,$config))->onQueue('push'));
            Config::set('database.default', $db);
        }

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Your overtime request has successfully submitted'
            ], 201);

    }

    public function approveClaim(Request $request){
        info($request->all());
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'overtime.id'                               => 'required|exists:overtime_sheet,id',
            'overtime.details.*.id'                     => 'required|exists:overtime_sheet_form,id',
            'overtime.details.*.awal_approved'          => "required",
            'overtime.details.*.akhir_approved'         => "required",
            'overtime.details.*.total_lembur_approved'  => "required",
            'overtime.details.*.overtime_calculate'     => "required",
            'approval.note_claim'                       => "required",
            'approval.is_approved_claim'                => "required|in:1,0",
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()],401);
        }

        $overtime           = OvertimeSheet::find($request->overtime['id']);

        if (!$overtime->user->overtime_payroll_id && $request->approval['is_approved_claim'] == 1) {
            return response()->json(['status' => 'error','message' => 'This user doesn\'t have overtime payment setting!'], 403);
        }

        $params             = getEmailConfig();
        $params['data']     = $overtime;
        $params['value']    = $overtime->historyApproval;
        $params['subject']  = get_setting('mail_name') . ' - Overtime Sheet';
        $params['view']     = 'email.overtime-approval-custom';


        $approval                       = HistoryApprovalOvertime::where(['overtime_sheet_id'=>$overtime->id,'structure_organization_custom_id'=> $user->structure_organization_custom_id])->first();
        $approval->approval_id_claim    = $user->id;
        $approval->is_approved_claim    = $request->approval['is_approved_claim'];
        $approval->date_approved_claim  = date('Y-m-d H:i:s');
        $approval->note_claim           = $request->approval['note_claim'];
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

            foreach ($request->overtime['details'] as $detail){
                $form = OvertimeSheetForm::where('id', $detail['id'])->first();
                if($form)
                {
                    $payroll_calculate = null;
                    if ($payment->overtime_payroll_type_id == 1) {
                        $payroll_calculate = $detail['overtime_calculate'] / 173 * $multipler;
                    } else if ($payment->overtime_payroll_type_id == 2) {
                        $total_lembur_approved = explode(':', $detail['total_lembur_approved']);
                        $total_lembur_approved = $total_lembur_approved[0] + $total_lembur_approved[1] / 60;
                        $payroll_calculate = $total_lembur_approved * $multipler;
                    } else if ($payment->overtime_payroll_type_id == 3) {
                        $payroll_calculate = $multipler;
                    }

                    $form->awal_approved = $detail['awal_approved'];
                    $form->akhir_approved = $detail['akhir_approved'];
                    $form->total_lembur_approved = $detail['total_lembur_approved'];
                    $form->overtime_payroll_type_id = $payment->overtime_payroll_type_id;
                    $form->overtime_calculate = $detail['overtime_calculate'];
                    $form->meal_allowance = preg_replace('/[^0-9]/', '', $detail['meal_allowance']);
                    $form->payroll_calculate = $payroll_calculate;
                    $form->claim_approval = now();
                    $form->save();
                }
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
            \FRDHelper::setNewData(strtolower($request->company), $value, $overtime, $notifType);
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

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Form Overtime Successfully Processed !',
            ], 200);
    }

    public function getCalculation(Request $request){
        $validator = Validator::make($request->all(), [
            'id'            => 'required|exists:overtime_sheet_form,id',
            'total_lembur'  => "required",
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()], 401);
        }

        $overtimeSheetForm  = OvertimeSheetForm::find($request->id);
        $overtimePayroll    = $overtimeSheetForm->overtimeSheet->user->overtimePayroll;
        $tanggal            = $overtimeSheetForm->tanggal;
        $total_hours        = explode(":", $request->total_lembur)[0];
        $total_minutes      = (explode(":", $request->total_lembur)[1]) / 60;

        $overtime_calculate = 0;
        if ($overtimePayroll && $overtimePayroll->overtime_payroll_type_id == 1) {
            $cuti_bersama   = CutiBersama::all();
            $libur_nasional = LiburNasional::all();
            $user_ts        = strtotime($tanggal);

            $result = false;
            foreach ($cuti_bersama as $key => $value_cuti) {
                $start_ts = strtotime($value_cuti->dari_tanggal);
                if (($user_ts >= $start_ts) && ($user_ts <= $start_ts)) {
                    $result = true;
                }
            }

            foreach ($libur_nasional as $key => $value_libur) {
                if ($user_ts == strtotime($value_libur->tanggal)) {
                    $result = true;
                }
            }

            if (getdate(strtotime($tanggal))['wday'] == 6 || getdate(strtotime($tanggal))['wday'] == 0 || $result) {
                if ($total_hours < 8) {
                    $overtime_calculate = ($total_hours * 2) + ($total_minutes * 2);
                } else if ($total_hours == 8) {
                    $overtime_calculate = ($total_hours * 2) + ($total_minutes * 3);
                } else if ($total_hours == 9) {
                    $overtime_calculate = (8 * 2) + (1 * 3) + ($total_minutes * 4);
                } else if ($total_hours >= 10) {
                    $overtime_calculate = (8 * 2) + (1 * 3) + (($total_hours - 9) * 4) + ($total_minutes * 4);
                }
            } else {
                if ($total_hours < 2) {
                    if ($total_hours == 0) {
                        $overtime_calculate = $total_minutes * 1.5;
                    } else {
                        $overtime_calculate = (1 * 1.5) + ($total_minutes * 2);
                    }
                } else if ($total_hours >= 2) {
                    $overtime_calculate = (($total_hours - 1) * 2) + 1.5 + ($total_minutes * 2);
                }
            }
        } else {
            $overtime_calculate = $total_hours + $total_minutes;
        }

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Successfully',
                'data' => round($overtime_calculate, 1),
            ], 200);
    }
}
