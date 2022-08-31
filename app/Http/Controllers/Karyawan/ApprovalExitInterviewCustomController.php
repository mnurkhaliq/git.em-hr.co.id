<?php

namespace App\Http\Controllers\Karyawan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ExitInterview;
use App\Models\ExitInterviewAssets;
use App\Models\HistoryApprovalExit;
use App\Models\Asset;
use App\Models\CareerHistory;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class ApprovalExitInterviewCustomController extends Controller
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
        //
         $params['data'] = cek_exit_approval();

        return view('karyawan.approval-exit-custom.index')->with($params);
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

    public function detail($id)
    {
        $params['data']    = ExitInterview::where('id', $id)->first();
        $params['history'] = HistoryApprovalExit::where('exit_interview_id',$id)->where('structure_organization_custom_id', \Auth::user()->structure_organization_custom_id)->first();
        if(!$params['data'] || !$params['history'])
            return redirect()->route('karyawan.approval.exit-custom.index')->with('message-error', 'You don\'t have permission to perform this action!');
        return view('karyawan.approval-exit-custom.detail')->with($params);
    }
    public function proses(Request $request)
    {
        $request->validate([
            'noteApproval' => 'required'
        ],
        [
            'noteApproval.required' => 'the note field is required!',
        ]); 

        $user = Auth::user();
        $exit               = ExitInterview::find($request->id);
        $params             = getEmailConfig();
        $params['data']     = $exit;
        $params['value']    = $exit->historyApproval;
        $params['subject']  = get_setting('mail_name') . ' - Exit Interview';
        $params['view']     = 'email.exit-approval-custom';


        $approval                = HistoryApprovalExit::where(['exit_interview_id'=>$exit->id,'structure_organization_custom_id'=> $user->structure_organization_custom_id])->first();
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
            // ExitInterviewAssets::where('exit_interview_id', $request->id)->whereNull('approval_check')->delete();
            // $exit->status_clearance = 1;
            $exit->status = 3;
            $params['text']     = '<p><strong>Dear Sir/Madam '. $exit->user->name .'</strong>,</p> <p>  Submission of your Exit Interview <strong style="color: red;">REJECTED</strong>.</p>';
            Config::set('database.default', 'mysql');
            if($exit->user->email && $exit->user->email != "") {
                $params['email'] = $exit->user->email;
                $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                dispatch($job);
            }
            Config::set('database.default', $db);

            $notifTitle = "Exit Interview";
            $notifType  = "exit_interview";
            if($exit->user->firebase_token) {
                array_push($userApprovalTokens, $exit->user->firebase_token);
            }
            array_push($userApprovalIds, $exit->user->id);
        }else if($approval->is_approved == 1){
            $lastApproval = $exit->historyApproval->last();
            if($approval->structure_organization_custom_id == $lastApproval->structure_organization_custom_id){
                $params['text']     = '<p><strong>Dear Sir/Madam '. $exit->user->name .'</strong>,</p> <p>  Submission of your Exit Interview <strong style="color: green;">APPROVED</strong>.</p>';
                $exit->status = 2;
                Config::set('database.default', 'mysql');
                if($exit->user->email && $exit->user->email != "") {
                    $params['email'] = $exit->user->email;
                    $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                    dispatch($job);
                }
                Config::set('database.default', $db);

                $notifTitle = "Exit Interview";
                $notifType  = "exit_interview";
                if($exit->user->firebase_token) {
                    array_push($userApprovalTokens, $exit->user->firebase_token);
                }
                array_push($userApprovalIds, $exit->user->id);
                $updateUser                = User::where('id', $exit->user_id)->first();
                $updateUser->inactive_date = $exit->last_work_date;
                $updateUser->non_active_date = $exit->resign_date;
                $updateUser->is_exit = 1;
                if ($updateUser->organisasi_status && $updateUser->organisasi_status != 'Permanent') {
                    $updateUser->end_date_contract = $exit->resign_date;
                    $career = CareerHistory::where('user_id', $updateUser->id)
                        ->whereDate('effective_date', '<=', \Carbon\Carbon::now()->format('Y-m-d'))
                        ->orderBy('effective_date', 'DESC')
                        ->orderBy('id', 'DESC')
                        ->first();
                    if (checkModule(26) || $career) {
                        if (!$career) {
                            $career = new CareerHistory();
                            $career->user_id = $updateUser->id;
                            $career->effective_date = $updateUser->join_date ?: \Carbon\Carbon::now()->format('Y-m-d');
                        }
                        $career->end_date = $updateUser->end_date_contract;
                        $career->save();
                    }
                } else {
                    $updateUser->status = 2;
                    $updateUser->resign_date = $exit->resign_date;
                }
                $updateUser->save();
                cleaning_future_career($updateUser);

                if($exit->status_clearance == 1){
                    $this->sendEmailToHRAdmin($exit,$db);
                }
            }else{
                $exit->status = 1;
                $nextApproval = HistoryApprovalExit::where(['exit_interview_id'=>$exit->id,'setting_approval_level_id'=> ($approval->setting_approval_level_id+1)])->first();
                if($nextApproval){
                    $userApproval = user_approval_custom($nextApproval->structure_organization_custom_id);

                    if($userApproval) {
                        Config::set('database.default', 'mysql');
                        foreach ($userApproval as $key => $value) {
                            if ($value->email == "") continue;
                            $params['text']     = '<p><strong>Dear Sir/Madam '. $value->name .'</strong>,</p> <p> '. $exit->user->name .'  / '.  $exit->user->nik .' applied for Exit Interview and currently waiting your approval.</p>';
                            $params['email'] = $value->email;
                            $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                            dispatch($job);
                        }
                        Config::set('database.default', $db);

                        $params['text'] = '<p> '. $exit->user->name .'  / '.  $exit->user->nik .' applied for Exit Interview and currently waiting your approval.</p>';
                        $notifTitle = "Exit Interview Approval";
                        $notifType  = "exit_interview_approval";
                        $userApprovalTokens = user_approval_firebase_tokens($nextApproval->structure_organization_custom_id);
                        $userApprovalIds = user_approval_id($nextApproval->structure_organization_custom_id);
                    }
                }
            }
        }
        $exit->save();

        foreach ($userApprovalIds as $value) {
            \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $value, $exit, $notifType);
        }

        if(count($userApprovalTokens) > 0){
            $config = [
                'title' => $notifTitle,
                'content' => strip_tags($params['text']),
                'type' => $notifType,
                'firebase_token' => $userApprovalTokens
            ];
            $notifData = [
                'id' => $exit->id
            ];
            $db = Config::get('database.default', 'mysql');
            Config::set('database.default', 'mysql');
            dispatch((new \App\Jobs\SendPushTokens($notifData,$config))->onQueue('push'));
            Config::set('database.default', $db);
        }

        return redirect()->route('karyawan.approval.exit-custom.index')->with('message-success', 'Form Exit Interview & Clearance Reimbursement Successfully Processed !');

    }

    private function sendEmailToHRAdmin($exit,$db){
        $params             = getEmailConfig();
        $params['data']     = $exit;
        $params['value']    = $exit->historyApproval;
        $params['assets']   = ExitInterviewAssets::where('exit_interview_id',$exit->id)->get();
        $params['subject']  = get_setting('mail_name') . ' - Exit Interview Confirmation';
        $params['view']     = 'email.exit-interview-clearance';
        $users = User::whereHas('modules',function ($q){
            $q->where('product_id',3);
        })->get();
        if($users) {
            Config::set('database.default', 'mysql');
            foreach ($users as $key => $value) {
                if($value->email && $value->email != ""){
                    $params['text'] = '<p><strong>Dear Sir/Madam ' . $value->name . '</strong>,</p> <p>  Submission of '.$exit->user->name.' Exit Interview & Clearance has been <strong style="color: green;">APPROVED</strong>.</p>';
                    $params['email'] = $value->email;
                    $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                    dispatch($job);
                }
            }
            Config::set('database.default', $db);
        }
    }
}
