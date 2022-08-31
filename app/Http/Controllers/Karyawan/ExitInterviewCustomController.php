<?php

namespace App\Http\Controllers\Karyawan;

use App\Models\Asset;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ExitInterview;
use App\Models\ExitClearanceDocument;
use App\Models\ExitClearanceInventoryHrd;
use App\Models\ExitClearanceInventoryGa;
use App\Models\ExitClearanceInventoryIt;
use App\Models\ExitInterviewAssets;
use App\Models\HistoryApprovalExit;
use App\Models\SettingApprovalClearance;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;


class ExitInterviewCustomController extends Controller
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
        $params['data'] = ExitInterview::where('user_id', \Auth::user()->id)->orderBy('id', 'DESC')->get();

        return view('karyawan.exit-interview-custom.index')->with($params);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $checkApproval = \Auth::user()->approval;
        if($checkApproval == null)
        {
            return redirect()->route('karyawan.exit-custom.index')->with('message-error', 'Setting approval not define yet. Please contact your admin !');
        }else {
            $checkApproval = $checkApproval->level1Exit;
            if ($checkApproval == null) {
                return redirect()->route('karyawan.exit-custom.index')->with('message-error', 'Setting approval is not defined yet. Please contact your admin !');
            }
        }
        $params['assets']                = Asset::where('user_id',\Auth::user()->id)->where('status', 1)->get();
        $params['asset_conditions']      = ['Good','Malfunction','Lost'];
        return view('karyawan.exit-interview-custom.create')->with($params);
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
        $user = Auth::user();
        $checkApproval = $user->approval;
        if($checkApproval == null)
        {
            return redirect()->route('karyawan.exit-custom.index')->with('message-error', 'Setting approval not define yet. Please contact your admin !');
        }else {
            $checkApproval = $checkApproval->level1Exit;
            if($checkApproval == null){
                return redirect()->route('karyawan.exit-custom.index')->with('message-error', 'Setting approval is not defined yet. Please contact your admin !');
            }
        $data       = new ExitInterview();
        $data->status               = 1;
        $data->user_id              = \Auth::user()->id;
        $data->resign_date          = date('Y-m-d', strtotime($request->resign_date));
        $data->last_work_date       = date('Y-m-d', strtotime($request->last_work_date));

        $data->exit_interview_reason = $request->exit_interview_reason;
        $data->other_reason = $request->other_reason;

        $data->hal_berkesan             = $request->hal_berkesan;
        $data->hal_tidak_berkesan       = $request->hal_tidak_berkesan;
        $data->masukan                  = $request->masukan;
        $data->kegiatan_setelah_resign  = $request->kegiatan_setelah_resign;
        $data->tujuan_perusahaan_baru   = $request->tujuan_perusahaan_baru;
        $data->jenis_bidang_usaha       = $request->jenis_bidang_usaha;
        $data->save();



            $historyApproval     = $user->approval->itemsExit;
            $settingApprovalItem = $historyApproval[0]->structure_organization_custom_id;
            foreach ($historyApproval as $level => $value) {
                # code...
                $history = new HistoryApprovalExit();
                $history->exit_interview_id                = $data->id;
                $history->setting_approval_level_id        = ($level+1);
                $history->structure_organization_custom_id = $value->structure_organization_custom_id;
                $history->save();
            }
            $historyApprov = HistoryApprovalExit::where('exit_interview_id',$data->id)->orderBy('setting_approval_level_id','asc')->get();

            $userApproval = user_approval_custom($settingApprovalItem);
            $params = getEmailConfig();
            $db = Config::get('database.default','mysql');
            $params['data']     = $data;
            $params['value']    = $historyApprov;
            $params['view']     = 'email.exit-approval-custom';
            $params['subject']  = get_setting('mail_name') . ' - Exit Interview';

            if($userApproval) {
                Config::set('database.default', 'mysql');
                foreach ($userApproval as $key => $value) {
                    if ($value->email == "") continue;
                    $params['email'] = $value->email;
                    $params['text']  = '<p><strong>Dear Sir/Madam '. $value->name .'</strong>,</p> <p> '. $data->user->name .'  / '.  $data->user->nik .' applied for Exit Interview and currently waiting your approval.</p>';
                    $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                    dispatch($job);
                }
                Config::set('database.default', $db);
                $params['text']  = '<p> '. $data->user->name .'  / '.  $data->user->nik .' applied for Exit Interview and currently waiting your approval.</p>';
            }
    
            $userApprovalTokens = user_approval_firebase_tokens($settingApprovalItem);
            info("structure id ".$settingApprovalItem);
            info($userApprovalTokens);

            foreach (user_approval_id($settingApprovalItem) as $value) {
                \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $value, $data, 'exit_interview_approval');
            }

            if(count($userApprovalTokens) > 0){
                $config = [
                    'title' => "Exit Interview Approval",
                    'content' => strip_tags($params['text']),
                    'type' => 'exit_interview_approval',
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

            // INVENTARIS
            if($request->asset != null && count($request->asset)>0)
            {
                $user = \Auth::user();
                $pics = [];
                foreach($request->asset as $key => $item)
                {
                    $dataAset                        = new ExitInterviewAssets();
                    $dataAset->asset_id              = $request->asset[$key];
                    $dataAset->exit_interview_id     = $data->id;
                    $dataAset->user_check            = 1;
                    $dataAset->catatan_user          = $request->catatan_user[$key];
                    $dataAset->asset_condition       = $request->asset_condition[$key];
                    $dataAset->save();

                    $asset = Asset::find($item);
                    if($asset && !in_array($asset->asset_type->pic_department, $pics)){
                        array_push($pics,$asset->asset_type->pic_department);
                    }
                }
                if($user->project_id != NULL)
                {
                    $clearanceApproval = SettingApprovalClearance::join('users', 'users.id','=', 'setting_approval_clearance.user_created')->whereIn('nama_approval',$pics)->where('users.project_id', $user->project_id)->select('setting_approval_clearance.*')->get();
                }else{
                    $clearanceApproval = SettingApprovalClearance::whereIn('nama_approval',$pics)->get();
                }

                $userApprovalTokens = [];
                $userApprovalIds = [];
                if(count($clearanceApproval)>0) {
                    $params['data']     = $data;
                    $params['subject']  = get_setting('mail_name') . ' - Exit Clearance';
                    $params['view']     = 'email.clearance-approval-custom';
                    Config::set('database.default', 'mysql');
                    foreach ($clearanceApproval as $key => $value) {
                        if ($value->user->email == "") continue;
                        $params['email'] = $value->user->email;
                        $params['text']  = '<p><strong>Dear Sir/Madam ' . $value->user->name . '</strong>,</p> <p> ' . $data->user->name . '  / ' . $data->user->nik . ' applied for Exit Clearance and currently waiting your approval.</p>';
                        $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                        dispatch($job);

                        if($value->user->firebase_token) {
                            array_push($userApprovalTokens, $value->user->firebase_token);
                        }
                        array_push($userApprovalIds, $value->user->id);
                    }
                    Config::set('database.default', $db);
                    $params['text']  = '<p> ' . $data->user->name . '  / ' . $data->user->nik . ' applied for Exit Clearance and currently waiting your approval.</p>';
                }

                foreach ($userApprovalIds as $value) {
                    \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $value, $data, 'exit_clearance_approval');
                }

                if(count($userApprovalTokens) > 0){
                    $config = [
                        'title' => "Exit Clearance Approval",
                        'content' => strip_tags($params['text']),
                        'type' => 'exit_clearance_approval',
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
            }
            else{
                $data->status_clearance = 1;
                $data->save();
            }



            return redirect()->route('karyawan.exit-custom.index')->with('message-success', 'Exit Interview succesfully process');

        }
        
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
        $params['data'] = ExitInterview::where(['id'=> $id,'user_id'=>Auth::user()->id])->first();

        if(!$params['data']){
            return redirect()->route('karyawan.exit-custom.index')->with('message-error', 'You don\'t have permission to perform this action!');
        }

        return view('karyawan.exit-interview-custom.detail')->with($params);
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

    public function clearance($id)
    {
        $params['data'] = ExitInterview::where(['id'=> $id,'user_id'=>Auth::user()->id])->first();
        
        if(!$params['data']){
            return redirect()->route('karyawan.exit-custom.index')->with('message-error', 'You don\'t have permission to perform this action!');
        }

        $user_id = Auth::user()->id;
        $params['data'] = ExitInterviewAssets::whereHas('exitInterview', function ($query) use ($user_id) {
            return $query->where('user_id', '=', $user_id);
        })->where('exit_interview_id', $id)->get();
        $params['asset_conditions']      = ['Good','Malfunction','Lost'];
        return view('karyawan.exit-interview-custom.clearance')->with($params);
    }

    public function prosesclearance(Request $request)
    {
//        if($request->asset != null)
//        {
//            $user = \Auth::user();
//            foreach($request->asset as $key => $item)
//            {
//                $dataAset = ExitInterviewAssets::where('id', $request->asset[$key])->first();
//            //  $dataAset->user_check  = isset($request->user_check[$key]) ? 1 : 0;
//                $dataAset->catatan     = $request->catatan[$key];
//                $dataAset->save();
//            }
//            $data = ExitInterview::where('id',$dataAset->exit_interview_id)->first();
//
//            if($user->project_id != NULL)
//            {
//                $clearanceApproval = SettingApprovalClearance::join('users', 'users.id','=', 'setting_approval_clearance.user_created')->where('users.project_id', $user->project_id)->select('setting_approval_clearance.*')->get();
//            }else{
//                $clearanceApproval = SettingApprovalClearance::all();
//            }
//
//            foreach ($clearanceApproval as $key => $value)
//            {
//
//                if($value->user->email == "") continue;
//                $params['data']     = $data;
//                $params['text']     = '<p><strong>Dear Sir/Madam '. $value->user->name .'</strong>,</p> <p> '. $data->user->name .'  / '.  $data->user->nik .' applied for Exit Clearance and currently waiting your approval.</p>';
//                try {
//                    \Mail::send('email.clearance-approval-custom', $params,
//                        function ($message) use ($data, $value) {
//                            $message->to($value->user->email);
//                            $message->subject(get_setting('mail_name') . ' - Exit Clearance');
//                        });
//                }catch (\Swift_TransportException $e){
//                    return redirect()->back()->with('message-error', 'Email config is invalid!');
//                }
//            }
//        }
        return redirect()->route('karyawan.exit-custom.index')->with('message-success', 'Exit Clearance succesfully process');
    }


}
