<?php

namespace App\Http\Controllers\Karyawan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Training;
use App\Models\HistoryApprovalTraining;
use App\Models\TrainingTransportationType; 
use App\Models\TrainingTransportation; 
use App\Models\TrainingAllowance; 
use App\Models\TrainingDaily; 
use App\Models\TrainingOther; 
use App\Models\TrainingTransportationReport; 
use App\Models\TrainingAllowanceReport; 
use App\Models\TrainingDailyReport; 
use App\Models\TrainingOtherReport; 
use App\Models\TransferSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use App\Models\StructureOrganizationCustom; 
use App\Models\OrganisasiDivision;
use App\Models\OrganisasiPosition;

class ApprovalTrainingCustomController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
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
        $user = auth()->user();
        if($user->project_id != NULL)
        {
            $params['division'] = OrganisasiDivision::where('project_id', $user->project_id)->select('organisasi_division.*')->get();
            $params['position'] = OrganisasiPosition::where('project_id', $user->project_id)->select('organisasi_position.*')->get();
        } else
        {
            $params['division'] = OrganisasiDivision::all();
            $params['position'] = OrganisasiPosition::all();
        }
        
        if(count(request()->all())) {
            \Session::put('abt-employee_status', request()->employee_status);
            \Session::put('abt-position_id', request()->position_id);
            \Session::put('abt-division_id', request()->division_id);
            \Session::put('abt-name', request()->name);
        }

        $employee_status    = \Session::get('abt-employee_status');
        $position_id        = \Session::get('abt-position_id');
        $division_id        = \Session::get('abt-division_id');
        $name               = \Session::get('abt-name');

        $data = $params['data'] = HistoryApprovalTraining::join('training','training.id','=','history_approval_training.training_id')->orderBy('training.id', 'DESC')->where('structure_organization_custom_id', \Auth::user()->structure_organization_custom_id);
        $cek_transfer_approve = TransferSetting::where('user_id', auth()->user()->id)->first();

        if($cek_transfer_approve != null && empty($name) && empty($employee_status) && empty($position_id) && empty($division_id)){
            $data = $data->orWhere(function($qry){
                $qry->where('status', '2')->orWhere('status_actual_bill', '2');
            })->orderByRaw('IF(structure_organization_custom_id = '.auth()->user()->structure_organization_custom_id.', 0,1)');
        } 

        if(request())
        {
            if (!empty($name)) {
                $data = $data->whereHas('training.user', function($qry) use($name){
                        $qry->where('name', 'LIKE', '%' . $name . '%')
                        ->orWhere('nik', 'LIKE', '%' . $name . '%');
                    })
                    ->orWhere('number', 'LIKE', '%' . $name . '%');
            }
            
            if(!empty($employee_status))
            {
                $data = $data->whereHas('training.user', function($qry) use($employee_status){
                    $qry->where('organisasi_status', $employee_status);
                });
            }

            if((!empty($division_id)) and (empty($position_id))) 
            {   
                $data = $data->whereHas('training.user', function($qry) use($division_id){
                    $qry->join('structure_organization_custom','users.structure_organization_custom_id','=','structure_organization_custom.id')->where('structure_organization_custom.organisasi_division_id',$division_id);
                });
            }

            if((!empty($position_id)) and (empty($division_id)))
            {   
                $data = $data->whereHas('training.user', function($qry) use($position_id){
                    $qry->join('structure_organization_custom','users.structure_organization_custom_id','=','structure_organization_custom.id')->where('structure_organization_custom.organisasi_position_id',$position_id);
                });
            }
            if((!empty($position_id)) and (!empty($division_id)))
            {
                $data = $data->whereHas('training.user', function($qry) use($position_id, $division_id){
                    $qry->join('structure_organization_custom','users.structure_organization_custom_id','=','structure_organization_custom.id')->where('structure_organization_custom.organisasi_position_id',$position_id)->where('structure_organization_custom.organisasi_division_id',$division_id);
                });
            }
        } 

        if(request()->reset == 1)
        {
            \Session::forget('abt-employee_status');
            \Session::forget('abt-position_id');
            \Session::forget('abt-division_id');
            \Session::forget('abt-name');

            return redirect()->route('karyawan.approval.training-custom.index');
        }

        $params['data'] = $data->orderBy('training_id', 'DESC')->get()->unique('training_id');
        
        return view('karyawan.approval-training-custom.index')->with($params);
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
        $params['data'] = cek_training_id_approval($id);

        if (!$params['data']) {
            return redirect()->route('karyawan.approval.training-custom.index')->with('message-error', 'You don\'t have permission to perform this action!');
        }

        $params['data']         = Training::where('id', $id)->first();
        $params['history'] = HistoryApprovalTraining::where('training_id',$id)->where('structure_organization_custom_id', \Auth::user()->structure_organization_custom_id)->first();

        return view('karyawan.approval-training-custom.detail')->with($params);
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
        $data               = Training::find($request->id);
        $params             = getEmailConfig();
        $params['data']     = $data;
        $params['value']    = $data->historyApproval;
        $params['subject']  = get_setting('mail_name') . ' - Business Trip';
        $params['view']     = 'email.training-approval-custom';

        $approval                = HistoryApprovalTraining::where(['training_id'=>$data->id,'structure_organization_custom_id'=> $user->structure_organization_custom_id])->first();
        $approval->approval_id   = $user->id;
        $approval->is_approved   = $request->status;
        $approval->date_approved = date('Y-m-d H:i:s');
        $approval->note          = $request->noteApproval;
        $approval->save();

        $db = Config::get('database.default', 'mysql');
        
        $notifTitle = "";
        $notifType = "";
        $userApprovalTokens = [];
        $userApprovalIds = [];

        if($approval->is_approved == 0){
            $data->status = 3;
            $params['text']     = '<p><strong>Dear Sir/Madam '. $data->user->name .'</strong>,</p> <p>  Submission of your Business Trip <strong style="color: red;">REJECTED</strong>.</p>';
            Config::set('database.default', 'mysql');
            if (!empty($data->user->email)) {
                $params['email'] = $data->user->email;
                $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                dispatch($job);
            }
            Config::set('database.default', $db);

            $notifTitle = "Business Trip";
            $notifType  = "business_trip";
            if($data->user->firebase_token) {
                array_push($userApprovalTokens, $data->user->firebase_token);
                $this->sentNotif($notifTitle, $params['text'], $notifType, $userApprovalTokens, $data->id);
                $userApprovalTokens = [];
            }
            \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $data->user->id, $data, $notifType);
        }else if($approval->is_approved == 1){
            $lastApproval = $data->historyApproval->last();
            if($approval->structure_organization_custom_id == $lastApproval->structure_organization_custom_id){
                $params['text']     = '<p><strong>Dear Sir/Madam '. $data->user->name .'</strong>,</p> <p>  Submission of your Business Trip <strong style="color: green;">APPROVED</strong>.</p>';
                $data->status = 2;
                Config::set('database.default', 'mysql');
                if (!empty($data->user->email)) {
                    $params['email'] = $data->user->email;
                    $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                    dispatch($job);
                }
                Config::set('database.default', $db);

                $notifTitle = "Business Trip";
                $notifType  = "business_trip";
                if($data->user->firebase_token) {
                    array_push($userApprovalTokens, $data->user->firebase_token);
                    $this->sentNotif($notifTitle, $params['text'], $notifType, $userApprovalTokens, $data->id);
                    $userApprovalTokens = [];
                }
                \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $data->user->id, $data, $notifType);
                $userApproval = TransferSetting::get();
                if($userApproval && $data->pengambilan_uang_muka > 0) {
                    Config::set('database.default', 'mysql');
                    foreach ($userApproval as $key => $value) {
                        if ($value->user->email == "") continue;
                        $params['total']    = $data->pengambilan_uang_muka;
                        $params['text']     = '<p><strong>Dear Sir/Madam '. $value->user->name .'</strong>,</p> <p> '. $data->user->name .'  / '.  $data->user->nik .' applied for Business Trip and currently waiting your payment.</p>';
                        $params['email'] = $value->user->email;
                        $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                        dispatch($job);

                        $notifTitle = "Transfer Business Trip";
                        $notifType  = "transfer_business_trip_approve";
                        if($value->user->firebase_token) {
                            array_push($userApprovalTokens, $value->user->firebase_token);
                            $this->sentNotif($notifTitle, $params['text'], $notifType, $userApprovalTokens, $data->id);
                            $userApprovalTokens = [];
                        }
                        \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $value->user->id, $data, $notifType);
                    }
                    Config::set('database.default', $db);
                }

            } else{
                $data->status = 1;
                $nextApproval = HistoryApprovalTraining::where(['training_id'=>$data->id,'setting_approval_level_id'=> ($approval->setting_approval_level_id+1)])->first();
                if($nextApproval){
                    $userApproval = user_approval_custom($nextApproval->structure_organization_custom_id);
                    if($userApproval) {
                        Config::set('database.default', 'mysql');
                        foreach ($userApproval as $key => $value) { 
                            if ($value->email == "") {
                                continue;
                            }

                            $params['text']     = '<p><strong>Dear Sir/Madam '. $value->name .'</strong>,</p> <p> '. $data->user->name .'  / '.  $data->user->nik .' applied for Business Trip and currently waiting your approval.</p>';
                            $params['email'] = $data->user->email;
                            $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                            dispatch($job);
                        }
                        Config::set('database.default', $db);

                        $params['text'] = '<p> '. $data->user->name .'  / '.  $data->user->nik .' applied for Business Trip and currently waiting your approval.</p>';
                        $notifTitle = "Business Trip Approval";
                        $notifType  = "business_trip_approval";
                        $userApprovalTokens = user_approval_firebase_tokens($nextApproval->structure_organization_custom_id);
                        $userApprovalIds = user_approval_id($nextApproval->structure_organization_custom_id);
                        $this->sentNotif($notifTitle, $params['text'], $notifType, $userApprovalTokens, $data->id);
                        $userApprovalTokens = [];
                    }
                }
            }
        }
        $data->save();

        foreach ($userApprovalIds as $value) {
            \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $value, $data, $notifType);
        }

        return redirect()->route('karyawan.approval.training-custom.index')->with('message-success', 'Form Business Trip Successfully Processed !');
    }

    public function sentNotif($title, $content, $type, $token, $id){
        if(count($token) > 0){
            $config = [
                'title' => $title,
                'content' => strip_tags($content),
                'type' => $type,
                'firebase_token' => $token
            ];
            $notifData = [
                'id' => $id
            ];
            $db = Config::get('database.default', 'mysql');
            Config::set('database.default', 'mysql');
            dispatch((new \App\Jobs\SendPushTokens($notifData,$config))->onQueue('push'));
            Config::set('database.default', $db);
        }
        return 'sent notif success';
    }

    //claim
    public function claim($id)
    {   
        $params['data'] = cek_training_id_approval($id);

        if (!$params['data']) {
            return redirect()->route('karyawan.approval.training-custom.index')->with('message-error', 'You don\'t have permission to perform this action!');
        }
        
        $params['data']         = Training::where('id', $id)->first();
        $params['history'] = HistoryApprovalTraining::where('training_id',$id)->where('structure_organization_custom_id', \Auth::user()->structure_organization_custom_id)->first();
        // dd($params['data']->training_acomodation);
        return view('karyawan.approval-training-custom.biaya')->with($params);
    }
    public function prosesClaim(Request $request)
    {
        // dd($request);
        $request->validate([
            'note_claim' => 'required'
        ],
        [
            'note_claim.required' => 'the note field is required!',
        ]); 
        
        $user = Auth::user();
        $data = Training::find($request->id);
        $history =  HistoryApprovalTraining::where('training_id',$request->id)->where('structure_organization_custom_id', \Auth::user()->structure_organization_custom_id)->first();

        if($request->id_acomodation != null)
        {
            foreach($request->id_acomodation as $key => $item)
            {
                $acomodation = \App\Models\TrainingTransportation::find($request->id_acomodation[$key]);
                $acomodation->nominal_approved              = preg_replace('/[^0-9]/', '', $request->nominalAcomodation_approved[$key]);
                $acomodation->note_approval = $request->noteAcomodation_approved[$key];
                $acomodation->save();

                $report = new TrainingTransportationReport;
                $report->training_id = $request->id;
                $report->training_transportation_id = $request->id_acomodation[$key];
                $report->level_id = $history->setting_approval_level_id;
                $report->approval_id = \Auth::user()->id;
                $report->approved = preg_replace('/[^0-9]/', '', $request->nominalAcomodation_approved[$key]);
                $report->note = $request->noteAcomodation_approved[$key];
                $report->save();
            }
        }
        if($request->id_allowance != null)
        {
            foreach($request->id_allowance as $key => $item)
            {
                $allowance = \App\Models\TrainingAllowance::find($request->id_allowance[$key]);
                $allowance->morning_approved              = preg_replace('/[^0-9]/', '', $request->morning_approved[$key]);
                $allowance->afternoon_approved            = preg_replace('/[^0-9]/', '', $request->afternoon_approved[$key]);
                $allowance->evening_approved              = preg_replace('/[^0-9]/', '', $request->evening_approved[$key]);
                $allowance->note_approval = $request->noteAllowance_approved[$key];
                $allowance->save();

                $report = new TrainingAllowanceReport;
                $report->training_id = $request->id;
                $report->training_allowance_id = $request->id_allowance[$key];
                $report->level_id = $history->setting_approval_level_id;
                $report->approval_id = \Auth::user()->id;
                $report->morning_approved              = preg_replace('/[^0-9]/', '', $request->morning_approved[$key]);
                $report->afternoon_approved            = preg_replace('/[^0-9]/', '', $request->afternoon_approved[$key]);
                $report->evening_approved              = preg_replace('/[^0-9]/', '', $request->evening_approved[$key]);
                $report->note = $request->noteAllowance_approved[$key];
                $report->save();
            }
        }
        if($request->id_daily != null)
        {
            foreach($request->id_daily as $key => $item)
            {
                $daily = \App\Models\TrainingDaily::find($request->id_daily[$key]);
                $daily->daily_approved              = preg_replace('/[^0-9]/', '', $request->nominalDaily_approved[$key]);
                $daily->note_approval = $request->noteDaily_approved[$key];
                $daily->save();

                $report = new TrainingDailyReport;
                $report->training_id = $request->id;
                $report->training_daily_id = $request->id_daily[$key];
                $report->level_id = $history->setting_approval_level_id;
                $report->approval_id = \Auth::user()->id;
                $report->approved = preg_replace('/[^0-9]/', '', $request->nominalDaily_approved[$key]);
                $report->note = $request->noteDaily_approved[$key];
                $report->save();
            }
        }
        if($request->id_other != null)
        {
            foreach($request->id_other as $key => $item)
            {
                $other = \App\Models\TrainingOther::find($request->id_other[$key]);
                $other->nominal_approved              = preg_replace('/[^0-9]/', '', $request->nominalOther_approved[$key]);
                $other->note_approval = $request->noteOther_approved[$key];
                $other->save();

                $report = new TrainingOtherReport;
                $report->training_id = $request->id;
                $report->training_other_id = $request->id_other[$key];
                $report->level_id = $history->setting_approval_level_id;
                $report->approval_id = \Auth::user()->id;
                $report->approved = preg_replace('/[^0-9]/', '', $request->nominalOther_approved[$key]);
                $report->note = $request->noteOther_approved[$key];
                $report->save();
            }
        }

        $data->sub_total_1_disetujui = $request->sub_total_1_disetujui ?: 0;
        $data->sub_total_2_disetujui = $request->sub_total_2_disetujui ?: 0;
        $data->sub_total_3_disetujui = $request->sub_total_3_disetujui ?: 0;
        $data->sub_total_4_disetujui = $request->sub_total_4_disetujui ?: 0;
        $total_reimbursement_disetujui    = $request->sub_total_1_disetujui + $request->sub_total_2_disetujui + $request->sub_total_3_disetujui + $request->sub_total_4_disetujui - $data->pengambilan_uang_muka;
        
        $params = getEmailConfig();
        $params['data'] = $data;
        $params['value'] = $data->historyApproval;
        $params['subject'] = get_setting('mail_name') . ' - Claim Business Trip';
        $params['view'] = 'email.training-approval-custom';

        $approval = HistoryApprovalTraining::where(['training_id' => $data->id, 'structure_organization_custom_id' => $user->structure_organization_custom_id])->first();
        $approval->approval_id_claim = $user->id;
        $approval->is_approved_claim = $request->status_actual_bill;
        $approval->date_approved_claim = date('Y-m-d H:i:s');
        $approval->note_claim = $request->note_claim;
        $approval->save();

        $db = Config::get('database.default', 'mysql');
        
        $notifTitle = "";
        $notifType = "";
        $userApprovalTokens = [];
        $userApprovalIds = [];

        if ($approval->is_approved_claim == 0) { // Jika rejected
            $data->status_actual_bill = 3;
            $params['text']     = '<p><strong>Dear Sir/Madam '. $data->user->name .'</strong>,</p> <p>  Submission of your Claim of Business Trip <strong style="color: red;">REJECTED</strong>.</p>';
            Config::set('database.default', 'mysql');
            if (!empty($data->user->email)) {
                $params['email'] = $data->user->email;
                $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                dispatch($job);
            }
            Config::set('database.default', $db);

            $notifTitle = "Claim Business Trip";
            $notifType  = "training_reject";
            if($data->user->firebase_token) {
                array_push($userApprovalTokens, $data->user->firebase_token);
                $this->sentNotif($notifTitle, $params['text'], $notifType, $userApprovalTokens, $data->id);
                $userApprovalTokens = [];
            }
            \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $data->user->id, $data, $notifType);
            $other = TrainingOtherReport::where('training_id', $request->id)->delete();
            $allowance = TrainingAllowanceReport::where('training_id', $request->id)->delete();
            $transport = TrainingTransportationReport::where('training_id', $request->id)->delete();
            $daily = TrainingDailyReport::where('training_id', $request->id)->delete();

        } else if ($approval->is_approved_claim == 1) {
            $lastApproval = $data->historyApproval->last();
            if($approval->structure_organization_custom_id == $lastApproval->structure_organization_custom_id){
                $params['text'] = '<p><strong>Dear Sir/Madam '. $data->user->name .'</strong>,</p> <p>  Submission of your Business Trip <strong style="color: green;">APPROVED</strong>.</p>';
                $data->status_actual_bill = 2;
                Config::set('database.default', 'mysql');
                if (!empty($data->user->email)) {
                    $params['email'] = $data->user->email;
                    $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                    dispatch($job);
                }
                Config::set('database.default', $db);

                $notifTitle = "Claim Business Trip";
                $notifType  = "training";
                if($data->user->firebase_token) {
                    array_push($userApprovalTokens, $data->user->firebase_token);
                    $this->sentNotif($notifTitle, $params['text'], $notifType, $userApprovalTokens, $data->id);
                    $userApprovalTokens = [];
                }
                \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $data->user->id, $data, $notifType);
                if($total_reimbursement_disetujui > 0){
                    $userApproval = TransferSetting::get();
                    if($userApproval) {
                        Config::set('database.default', 'mysql');
                        foreach ($userApproval as $key => $value) {
                            if ($value->user->email == "") continue;
                            $params['total']    = $total_reimbursement_disetujui;
                            $params['text']     = '<p><strong>Dear Sir/Madam '. $value->user->name .'</strong>,</p> <p> '. $data->user->name .'  / '.  $data->user->nik .' applied for Claim of Business Trip and currently waiting your payment lack from business trip.</p>';
                            $params['email'] = $value->user->email;
                            $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                            dispatch($job);
                            
                            $notifTitle = "Transfer Claim Business Trip";
                            $notifType  = "transfer_claim_business_trip";
                            if($value->user->firebase_token) {
                                array_push($userApprovalTokens, $value->user->firebase_token);
                                $this->sentNotif($notifTitle, $params['text'], $notifType, $userApprovalTokens, $data->id);
                                $userApprovalTokens = [];
                            }
                            \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $value->user->id, $data, $notifType);
                        }
                        Config::set('database.default', $db);
                    }
                }
                elseif($total_reimbursement_disetujui < 0){
                    $userApproval = TransferSetting::get();
                    if($userApproval) {
                        Config::set('database.default', 'mysql');
                        foreach ($userApproval as $key => $value) {
                            if ($value->user->email == "") continue;
                            $params['total']    = -1 * $total_reimbursement_disetujui;
                            $params['text']     = '<p><strong>Dear Sir/Madam '. $value->user->name .'</strong>,</p> <p> '. $data->user->name .'  / '.  $data->user->nik .' applied for Claim of Business Trip and total approved was greater than what was claimed, so she/he had to return the excess.</p>';
                            $params['email'] = $value->user->email;
                            $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                            dispatch($job);
                            
                            $notifTitle = "Transfer Claim Business Trip";
                            $notifType  = "transfer_claim_business_trip";
                            if($value->user->firebase_token) {
                                array_push($userApprovalTokens, $value->user->firebase_token);
                                $this->sentNotif($notifTitle, $params['text'], $notifType, $userApprovalTokens, $data->id);
                                $userApprovalTokens = [];
                            }
                            \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $value->user->id, $data, $notifType);
                        }
                        Config::set('database.default', $db);
                    }

                    Config::set('database.default', 'mysql');
                    if($data->user->email && $data->user->email != "") {
                        $params['email'] = $data->user->email;
                        $params['total']    = -1 * $total_reimbursement_disetujui;
                        $params['text']     = '<p><strong>Dear Sir/Madam '. $data->user->name .'</strong>,</p> <p>Total claimed is less than the total approved, so you must return the excess. which will be followed up by the company.</p>';
                        $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                        dispatch($job);
                    }
                    Config::set('database.default', $db);

                    $userApprovalTokens = [];
                    $notifTitle = "Transfer Claim Business Trip";
                    $notifType  = "transfer_back_claim_business_trip_more";
                    if($data->user->firebase_token) {
                        array_push($userApprovalTokens, $data->user->firebase_token);
                        $this->sentNotif($notifTitle, $params['text'], $notifType, $userApprovalTokens, $data->id);
                        $userApprovalTokens = [];
                    }
                    \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $data->user->id, $data, $notifType);
                }
                elseif($total_reimbursement_disetujui == 0){
                    $data->is_transfer_claim = 1;
                }
            } else{
                $data->status_actual_bill = 1;
                $nextApproval = HistoryApprovalTraining::where(['training_id' => $data->id, 'setting_approval_level_id' => ($approval->setting_approval_level_id + 1)])->first();
                if ($nextApproval) {
                    $userApproval = user_approval_custom($nextApproval->structure_organization_custom_id);
                    if ($userApproval) {
                        Config::set('database.default', 'mysql');
                        foreach ($userApproval as $key => $value) { 
                            if($value->email == "") {
                                continue;
                            }

                            $params['text']     = '<p><strong>Dear Sir/Madam '. $value->name .'</strong>,</p> <p> '. $data->user->name .'  / '.  $data->user->nik .' applied for Claim of Business Trip and currently waiting your approval.</p>';
                            $params['email'] = $data->user->email;
                            $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                            dispatch($job);
                        }
                        Config::set('database.default', $db);

                        $params['text'] = '<p> '. $data->user->name .'  / '.  $data->user->nik .' applied for Claim of Business Trip and currently waiting your approval.</p>';
                        $notifTitle = "Claim Business Trip Approval";
                        $notifType  = "training_approval";
                        $userApprovalTokens = user_approval_firebase_tokens($nextApproval->structure_organization_custom_id);
                        $userApprovalIds = user_approval_id($nextApproval->structure_organization_custom_id);
                        $this->sentNotif($notifTitle, $params['text'], $notifType, $userApprovalTokens, $data->id);
                        $userApprovalTokens = [];
                    }
                }
            }
        }
        $data->save();

        foreach ($userApprovalIds as $value) {
            \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $value, $data, $notifType);
        }

        return redirect()->route('karyawan.approval.training-custom.index')->with('message-success', 'Form Business Trip Successfully Processed !');

    }

    public function detailTransfer($id)
    {   
        $params['data'] = cek_transfer_setting_user($id);

        if (!$params['data']) {
            return redirect()->route('karyawan.approval.training-custom.index')->with('message-error', 'You don\'t have permission to perform this action!');
        }

        $params['data'] = Training::where('id', $id)->first();
        $params['history'] = HistoryApprovalTraining::where('training_id',$id)->where('training_id',$id)->orderBy('id', 'DESC')->first();

        return view('karyawan.approval-training-custom.transfer')->with($params);
    }


    public function transfer(Request $request, $id){
        //dd($request);
        $data = Training::find($id);
        $data->is_transfer= $request->is_transfer;
        $data->is_transfer_by = auth()->user()->id;
        $data->disbursement = $request->disbursement;
        if($request->hasFile('transfer_proof_by_admin'))
        {
            $image = $request->transfer_proof_by_admin;
            $name = md5($id.'transfer_proof').'.'.$image->getClientOriginalExtension();
            $company_url = session('company_url','umum').'/';
            $destinationPath = public_path('storage/training-custom/transfer-proof/').$company_url;
            $image->move($destinationPath, $name);
            $data->transfer_proof = $company_url.$name;
        }
        $data->save();

        $userApprovalTokens = [];

        $db = Config::get('database.default','mysql');
        $params             = getEmailConfig();
        $params['data']     = $data;
        $params['value']    = $data->historyApproval;
        $params['subject']  = get_setting('mail_name') . ' - Transfer Business Trip';
        $params['view']     = 'email.training-transfer';
        $params['total']    = $data->pengambilan_uang_muka;
        if($request->disbursement=='Transfer'){
            $params['text']     = '<p><strong>Dear Sir/Madam '. $data->user->name .'</strong>,</p> <p>  Submission of your Business Trip has been transfered.</p>';
        }else{
            $params['text']     = '<p><strong>Dear Sir/Madam '. $data->user->name .'</strong>,</p> <p>  Submission of your Business Trip will be merged with the next payroll.</p>';
        }
        Config::set('database.default', 'mysql');

        if(!empty($data->user->email)) {
            $params['email'] = $data->user->email;
            $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
            dispatch($job);
        }
        Config::set('database.default', $db);
        
        $notifTitle = "Transfer Business Trip";
        $notifType  = "transfer_business_trip";

        \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $data->user->id, $data, $notifType);

        if($data->user->firebase_token) {
            array_push($userApprovalTokens, $data->user->firebase_token);
        }

        if(count($userApprovalTokens) > 0){
            $config = [
                'title' => $notifTitle,
                'content' => strip_tags($params['text']),
                'type' => $notifType,
                'firebase_token' => $userApprovalTokens
            ];
            $notifData = [
                'id' => $data->id
            ];
            $db = Config::get('database.default', 'mysql');
            Config::set('database.default', 'mysql');
            dispatch((new \App\Jobs\SendPushTokens($notifData,$config))->onQueue('push'));
            Config::set('database.default', $db);
        }

        return redirect()->route('karyawan.approval.training-custom.index')->with('message-success', 'Transfer Proof Successfully Sent!');
    }

    public function detailTransferClaim($id)
    {   
        $params['data'] = cek_transfer_setting_user($id);

        if (!$params['data']) {
            return redirect()->route('karyawan.approval.training-custom.index')->with('message-error', 'You don\'t have permission to perform this action!');
        }

        $params['data'] = Training::where('id', $id)->first();
        $params['history'] = HistoryApprovalTraining::where('training_id',$id)->orderBy('id', 'DESC')->first();

        return view('karyawan.approval-training-custom.transfer-claim')->with($params);
    }

    public function transferClaim(Request $request, $id){
        //dd($request);
        $data = Training::find($id);
        $data->is_transfer_claim = $request->is_transfer_claim;
        $data->is_transfer_claim_by = auth()->user()->id;
        $data->disbursement_claim = $request->disbursement_claim;
        if($request->hasFile('transfer_proof_claim_by_admin'))
        {
            $image = $request->transfer_proof_claim_by_admin;
            $name = md5($id.'transfer_proof_claim').'.'.$image->getClientOriginalExtension();
            $company_url = session('company_url','umum').'/';
            $destinationPath = public_path('storage/training-custom/transfer-proof/').$company_url;
            $image->move($destinationPath, $name);
            $data->transfer_proof_claim = $company_url.$name;
        }
        $data->save();

        $userApprovalTokens = [];

        $db = Config::get('database.default','mysql');
        $params             = getEmailConfig();
        $params['data']     = $data;
        $params['value']    = $data->historyApproval;
        $params['subject']  = get_setting('mail_name') . ' - Transfer Claim Business Trip';
        $params['view']     = 'email.training-transfer';
        $params['total']    = $data->sub_total_1_disetujui + $data->sub_total_2_disetujui + $data->sub_total_3_disetujui + $data->sub_total_4_disetujui - $data->pengambilan_uang_muka;
        if($request->disbursement_claim=='Transfer'){
            $params['text']     = '<p><strong>Dear Sir/Madam '. $data->user->name .'</strong>,</p> <p>  Submission of your Business Trip Claim has been transfered.</p>';
        }else{
            $params['text']     = '<p><strong>Dear Sir/Madam '. $data->user->name .'</strong>,</p> <p>  Submission of your Business Trip Claim will be merged with the next payroll.</p>';
        }
        Config::set('database.default', 'mysql');

        if(!empty($data->user->email)) {
            $params['email'] = $data->user->email;
            $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
            dispatch($job);
        }
        Config::set('database.default', $db);
        
        $notifTitle = "Transfer Claim Business Trip";
        $notifType  = "transfer_claim_business_trip_less";

        \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $data->user->id, $data, $notifType);

        if($data->user->firebase_token) {
            array_push($userApprovalTokens, $data->user->firebase_token);
        }

        if(count($userApprovalTokens) > 0){
            $config = [
                'title' => $notifTitle,
                'content' => strip_tags($params['text']),
                'type' => $notifType,
                'firebase_token' => $userApprovalTokens
            ];
            $notifData = [
                'id' => $data->id
            ];
            $db = Config::get('database.default', 'mysql');
            Config::set('database.default', 'mysql');
            dispatch((new \App\Jobs\SendPushTokens($notifData,$config))->onQueue('push'));
            Config::set('database.default', $db);
        }

        return redirect()->route('karyawan.approval.training-custom.index')->with('message-success', 'Transfer Proof Claim Successfully Sent!');
    }

}
