<?php

namespace App\Http\Controllers\Karyawan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ExitInterview;
use App\Models\ExitInterviewAssets;
use App\Models\Asset;
use App\Models\AssetTracking;
use App\User;
use Illuminate\Support\Facades\Config;

class ApprovalExitClearanceCustomController extends Controller
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
        $user = \Auth::user();
        $pics = \App\Models\SettingApprovalClearance::where('user_id',$user->id)->pluck('nama_approval')->toArray();
        //if(!$approval) return [];
        if(count($pics)>0)
        {
            $user = \Auth::user();
//            if($user->project_id != NULL)
//            {
//                $params['data'] = ExitInterview::where('exit_interview.status','<',3)->orderBy('exit_interview.id', 'DESC')->join('users','users.id','=','exit_interview.user_id')->where('users.project_id', $user->project_id)->select('exit_interview.*')->get();
//            }else {
//                $params['data'] = ExitInterview::where('status','<',3)->orderBy('id', 'DESC')->get();
//            }
            $params['data'] = ExitInterview::whereRaw("(select count(*) from exit_interview_assets ea
                    join asset a on ea.asset_id = a.id
                    join asset_type as at on a.asset_type_id = at.id
                    where exit_interview.id = ea.exit_interview_id 
                    and at.pic_department in (SELECT sc.nama_approval from setting_approval_clearance sc where sc.user_id = ?)) > ?",[$user->id, 0])->orderBy('exit_interview.id','desc')->get();

        } else
        {
            $params['data'] = [];
        }
        return view('karyawan.approval-clearance-custom.index')->with($params);
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
        $data = ExitInterview::whereRaw("(select count(*) from exit_interview_assets ea
            join asset a on ea.asset_id = a.id
            join asset_type as at on a.asset_type_id = at.id
            where exit_interview.id = ea.exit_interview_id
            and exit_interview.id = ?
            and at.pic_department in (SELECT sc.nama_approval from setting_approval_clearance sc where sc.user_id = ?)) > ?",[$id, \Auth::user()->id, 0])->orderBy('exit_interview.id','desc')->first();
        if(!$data)
            return redirect()->route('karyawan.approval.clearance-custom.index')->with('message-error', 'You don\'t have permission to perform this action!');
        
//        $count = ExitInterviewAssets::where('exit_interview_id', $id)->where(function($table){
//          $table->where('approval_check','<',1)
//          ->orWhereNull('approval_check');
//        })->get();
        $params['type']     = \App\Models\SettingApprovalClearance::where('user_id', \Auth::user()->id)->first();
        $params['data']     = ExitInterviewAssets::where('exit_interview_id', $id)->get();
//        $params['check']    = count($count);
        $params['id']       = $id;
        $params['exit']     = ExitInterview::find($id);
        if(!$params['type']){
            return redirect()->route('karyawan.approval.clearance-custom.index')->with('message-error', 'You are not allowed to access this');
        }
        $params['asset_conditions']      = ['Good','Malfunction','Lost'];
        return view('karyawan.approval-clearance-custom.detail')->with($params);
    }
    public function proses(Request $request)
    {
        // dd($request);
        if($request->asset != null && $request->status=='accept') {
            if(!$request->approval_check || count($request->asset) != count($request->approval_check)){
                return redirect()->back()->withErrors(['Approval should be checked!']);
            }
            foreach($request->asset as $key => $item)
            {
                $dataAset = ExitInterviewAssets::where('id', $request->asset[$key])->first();
                $dataAset->approval_check  = isset($request->approval_check[$key]) ? 1 : 0;
                $dataAset->catatan         = $request->catatan[$key];
                $dataAset->asset_condition = isset($request->asset_condition[$key]) ? $request->asset_condition[$key] : null;

                if($dataAset->approval_check == 1)
                {
                    $dataAset->approval_id     = \Auth::user()->id;
                    $dataAset->date_approved   = date('Y-m-d H:i:s');

                    $aset = Asset::where('id',$dataAset->asset_id)->first();
                    $aset->user_id = \Auth::user()->id;
                    $aset->handover_date = date('Y-m-d H:i:s');
                    $aset->assign_to  = 'Office Inventory/Idle';
                    $aset->asset_condition = $dataAset->asset_condition;
                    $aset->save();

                    $tracking                   = new AssetTracking();
                    $tracking->asset_number     = $aset->asset_number; 
                    $tracking->asset_name       = $aset->asset_name;
                    $tracking->asset_type_id    = $aset->asset_type_id;
                    $tracking->asset_sn         = $aset->asset_sn;
                    $tracking->purchase_date    = date('Y-m-d', strtotime($aset->purchase_date));
                    $tracking->asset_condition  = $aset->asset_condition;
                    $tracking->assign_to        = $aset->assign_to;
                    $tracking->user_id          = $aset->user_id;
                    $tracking->asset_id         = $aset->id;
                    $tracking->status_mobil         = $aset->status_mobil;
                    $tracking->remark               = $aset->remark;
                    $tracking->save();
                }
                $dataAset->save();
            }

            $remaining_asset = ExitInterviewAssets::where('exit_interview_id',$request->id)->where(function($table){
                $table->where('approval_check','<',1)->orWhereNull('approval_check');
            })->count();
            
            if($remaining_asset == 0){
                $exit = ExitInterview::find($request->id);
                $exit->status_clearance = 1;
                $exit->save();


                $db = Config::get('database.default','mysql');
                $data = $exit;

               // send email

                if($data->user->email) {
                    info("Sending email clearance");
                    $params             = getEmailConfig();
                    $params['email']    = $data->user->email;
                    $params['data']     = $data;
                    $params['assets']   = ExitInterviewAssets::where('exit_interview_id',$request->id)->get();
                    $params['text']     = '<p><strong>Dear Sir/Madam '. $data->user->name .'</strong>,</p> <p>  Submission of your Exit Clearance <strong style="color: green;">APPROVED</strong>.</p>';
                    $params['view']     = 'email.exit-clearance';
                    $params['subject']  = get_setting('mail_name') . ' - Exit Clearance';
                    Config::set('database.default', 'mysql');
                    $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                    dispatch($job);
                    Config::set('database.default', $db);
                }

               \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $data->user->id, $data, 'exit_clearance');

                if ($data->user->firebase_token) {
                    $config = [
                        'title' => "Exit Clearance",
                        'content' => strip_tags('<p><strong>Dear Sir/Madam '. $data->user->name .'</strong>,</p> <p>  Submission of your Exit Clearance <strong style="color: green;">APPROVED</strong>.</p>'),
                        'type' => 'exit_clearance',
                        'firebase_token' => [$data->user->firebase_token]
                    ];
                    $notifData = [
                        'id' => $data->id
                    ];
                    info($data->user->firebase_token);
                    $db = Config::get('database.default', 'mysql');
                    Config::set('database.default', 'mysql');
                    dispatch((new \App\Jobs\SendPushTokens($notifData,$config))->onQueue('push'));
                    Config::set('database.default', $db);
                }

                if($exit->status == 2){
                    $this->sendEmailToHRAdmin($exit,$db);
                }
           }
        }
        elseif($request->asset != null && $request->status=='reject'){
            foreach($request->asset as $key => $item)
            {
                $dataAset = ExitInterviewAssets::where('id', $request->asset[$key])->first();
                $dataAset->approval_check  = 0;
                $dataAset->catatan         = $request->catatan[$key];
                $dataAset->asset_condition = isset($request->asset_condition[$key]) ? $request->asset_condition[$key] : null;
                $dataAset->approval_id     = \Auth::user()->id;
                $dataAset->date_approved   = date('Y-m-d H:i:s');
                $dataAset->save();
            }

            $exit = ExitInterview::find($request->id);
            $exit->status_clearance = 2;
            $exit->save();

            $db = Config::get('database.default','mysql');
            $data = $exit;

            // send email

            if($data->user->email) {
                info("Sending email clearance");
                $params             = getEmailConfig();
                $params['email']    = $data->user->email;
                $params['data']     = $data;
                $params['assets']   = ExitInterviewAssets::where('exit_interview_id',$request->id)->get();
                $params['text']     = '<p><strong>Dear Sir/Madam '. $data->user->name .'</strong>,</p> <p>  Submission of your Exit Clearance <strong style="color: red;">REJECTED</strong>.</p>';
                $params['view']     = 'email.exit-clearance';
                $params['subject']  = get_setting('mail_name') . ' - Exit Clearance';
                Config::set('database.default', 'mysql');
                $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                dispatch($job);
                Config::set('database.default', $db);
            }

            \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $data->user->id, $data, 'exit_clearance');

            if ($data->user->firebase_token) {
                $config = [
                    'title' => "Exit Clearance",
                    'content' => strip_tags('<p><strong>Dear Sir/Madam '. $data->user->name .'</strong>,</p> <p>  Submission of your Exit Clearance <strong style="color: red;">REJECTED</strong>.</p>'),
                    'type' => 'exit_clearance',
                    'firebase_token' => [$data->user->firebase_token]
                ];
                $notifData = [
                    'id' => $data->id
                ];
                info($data->user->firebase_token);
                $db = Config::get('database.default', 'mysql');
                Config::set('database.default', 'mysql');
                dispatch((new \App\Jobs\SendPushTokens($notifData,$config))->onQueue('push'));
                Config::set('database.default', $db);
            }

        }

        $approval = \App\Models\SettingApprovalClearance::where('user_id', \Auth::user()->id)->first();
        if($approval)
        {
            $params['data'] = ExitInterview::where('status','<',3)->orderBy('id', 'DESC')->get();
          
        } else
        {
            $params['data'] = [];
        }
        return redirect()->route('karyawan.approval.clearance-custom.index')->with('message-success', 'Exit Clearance succesfully process')->with($params);
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
