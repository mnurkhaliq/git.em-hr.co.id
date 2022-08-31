<?php

namespace App\Http\Controllers\Karyawan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CutiKaryawan;
use App\Models\CutiKaryawanDate;
use App\User;
use App\Models\SettingApprovalLeaveItem;
use App\Models\SettingApprovalLeave;
use App\Models\HistoryApprovalLeave;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Image;
use Illuminate\Support\Facades\DB;

class LeaveController extends Controller
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
        $params['data'] = CutiKaryawan::where('user_id', \Auth::user()->id)->orderBy('id', 'DESC')->get();

        return view('karyawan.leave.index')->with($params);
    }

    public function getListDay($id)
    {
        $cutiKaryawanDate = CutiKaryawanDate::where('cuti_karyawan_id', $id)->get();

        $data = [];
        foreach ($cutiKaryawanDate as $key => $value) {
            $data[$key]['badge'] = false;
            $data[$key]['classname'] = "type-".$value->type;
            $data[$key]['date'] = $value->tanggal_cuti;
            $data[$key]['title'] = $value->description;
        }

        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $checkApproval = \Auth::user()->approval;
        if($checkApproval == null)
        {
            return redirect()->route('karyawan.leave.index')->with('message-error', 'Your position is not defined yet. Please contact your admin!');
        }else {
            $checkApproval = $checkApproval->level1;
            if ($checkApproval == null) {
                return redirect()->route('karyawan.leave.index')->with('message-error', 'Setting approval not define yet. Please contact your admin !');
            }
        }
        $params['karyawan'] = User::whereIn('access_id', [1,2])->get();
        $params['karyawan_backup'] = User::whereIn('access_id', [1,2])->get();

        return view('karyawan.leave.create')->with($params);
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
        if ($approval == null) {
            return redirect()->route('karyawan.leave.index')->with('message-error', 'Your position is not defined yet. Please contact your admin!');
        }  else if (count($approval->items) == 0) {
            return redirect()->route('karyawan.leave.index')->with('message-error', 'Setting approval not define yet. Please contact your admin !');            
        }

        $data = new CutiKaryawan();
        $data->user_id              = $user->id;
        $data->jenis_cuti           = $request->jenis_cuti;
        $data->tanggal_cuti_start   = date('Y-m-d', strtotime($request->tanggal_cuti_start));
        $data->tanggal_cuti_end     = date('Y-m-d', strtotime($request->tanggal_cuti_end));
        $data->keperluan            = $request->keperluan;
        $data->backup_user_id       = $request->backup_user_id;
        $data->status               = 1;

        $data->jam_pulang_cepat     = $request->jam_pulang_cepat;
        $data->jam_datang_terlambat = $request->jam_datang_terlambat;
        $data->total_cuti           = $request->total_cuti;
        $data->temp_kuota           = $request->temp_kuota;
        $data->temp_cuti_terpakai   = $request->temp_cuti_terpakai;
        $data->temp_sisa_cuti       = $request->temp_sisa_cuti;

        if (isset($request->attachment) && $request->hasFile('attachment')) {
            $fileName = date('H.i.s') . '.' . $request->file('attachment')->getClientOriginalExtension();
            $path = env('PATH_LEAVE_UPLOAD') . '/attachment/' . strtolower(session('company_url', 'umum')) . '/' . date('Y-m-d') . '/' . $user->id;
            if (!is_dir(env('PATH_STORAGE_UPLOAD_SAAS') . $path)) {
                mkdir(env('PATH_STORAGE_UPLOAD_SAAS') . $path, 0755, true);
            }

            if ($request->file('attachment')->getClientOriginalExtension() == 'pdf') {
                $request->file('attachment')->move(env('PATH_STORAGE_UPLOAD_SAAS') . $path, $fileName);
            } else {
                $width = Image::make($request->file('attachment'))->width();
                $height = Image::make($request->file('attachment'))->height();

                $ratio = 1;
                while($width/($ratio += 0.5) > 600 || $height/$ratio > 400);
                $width = $width/$ratio;
                $height = $height/$ratio;

                $canvas = Image::canvas($width, $height);
                $resizeImage  = Image::make($request->file('attachment'))->resize($width, $height, function($constraint) {
                    $constraint->aspectRatio();
                });
    
                $canvas->insert($resizeImage, 'center');
                $canvas->save(env('PATH_STORAGE_UPLOAD_SAAS') . $path . '/' . $fileName);
            }

            $data->attachment = env('PATH_STORAGE_TUNNEL_SAAS') . $path . '/' . $fileName;
        }

        $data->save();

        foreach ($request->day_list as $day) {
            CutiKaryawanDate::create([
                'cuti_karyawan_id' => $data->id,
                'tanggal_cuti' => $day['date'],
                'type' => $day['type'],
                'description' => $day['desc']
            ]);
        }
        
        $historyApproval = $user->approval->items;
        $settingApprovalItem = $historyApproval[0]->structure_organization_custom_id;
        foreach ($historyApproval as $level => $value) {
            # code...
            $history = new HistoryApprovalLeave();
            $history->cuti_karyawan_id                  = $data->id;
            $history->setting_approval_level_id         = ($level + 1);
            $history->structure_organization_custom_id  = $value->structure_organization_custom_id;
            $history->save();
        }
        $historyApprov = HistoryApprovalLeave::where('cuti_karyawan_id', $data->id)->orderBy('setting_approval_level_id', 'asc')->get();

        $userApproval = user_approval_custom($settingApprovalItem);
        $params = getEmailConfig();
        $db = Config::get('database.default', 'mysql');

        $params['data'] = $data;
        $params['value'] = $historyApprov;
        $params['view'] = 'email.leave-approval-custom';
        $params['subject'] = get_setting('mail_name') . ' - Submission of Leave / Permit';
        if ($userApproval) {
            Config::set('database.default', 'mysql');
            foreach ($userApproval as $key => $value) {
                if (empty($value->email)) {
                    continue;
                }

                $params['email'] = $value->email;
                $params['text'] = '<p><strong>Dear Sir/Madam ' . $value->name . '</strong>,</p> <p> ' . $data->user->name . '  / ' . $data->user->nik . ' applied for Leave/Permit and currently waiting your approval.</p>';
                $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                dispatch($job);
            }
            Config::set('database.default', $db);
            $params['text'] = '<p> ' . $data->user->name . '  / ' . $data->user->nik . ' applied for Leave/Permit and currently waiting your approval.</p>';
        }

        $userApprovalTokens = user_approval_firebase_tokens($settingApprovalItem);
        info("structure id ".$settingApprovalItem);
        info($userApprovalTokens);

        foreach (user_approval_id($settingApprovalItem) as $value) {
            \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $value, $data, 'leave_approval');
        }

        if(count($userApprovalTokens) > 0){
            $config = [
                'title' => "Leave/Permit Approval",
                'content' => strip_tags($params['text']),
                'type' => 'leave_approval',
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

        return redirect()->route('karyawan.leave.index')->with('message-success', 'Data saved successfully !');
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
        $params['data']     = CutiKaryawan::where('id', $id)->first();

        if (!IsAccess($params['data'])) {
            return redirect()->route('karyawan.leave.index')->with('message-error', 'You don\'t have permission to perform this action!');
        }

        $params['karyawan'] = User::whereIn('access_id', [1,2])->get();
        $params['karyawan_backup'] = User::whereIn('access_id', [1,2])->get();

        return view('karyawan.leave.edit')->with($params);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $user = Auth::user();

        $data = CutiKaryawan::find($id);
        if ($data->status == 1) {
            $data->status = 5;
            $historyApprov = HistoryApprovalLeave::where('cuti_karyawan_id', $data->id)->where('setting_approval_level_id', '<=', DB::raw('(select min(setting_approval_level_id) from history_approval_leave where cuti_karyawan_id = '.$id.' and is_approved is null)'))->get();
            foreach ($historyApprov as $level => $settingApprovalItem) {
                $settingApprovalItem = $settingApprovalItem->structure_organization_custom_id;
                $userApproval = user_approval_custom($settingApprovalItem);
                $params = getEmailConfig();
                $db = Config::get('database.default', 'mysql');
    
                $params['data'] = $data;
                $params['value'] = $historyApprov;
                $params['view'] = 'email.leave-approval-custom';
                $params['subject'] = get_setting('mail_name') . ' - Submission of Leave / Permit';
                if ($userApproval) {
                    Config::set('database.default', 'mysql');
                    foreach ($userApproval as $key => $value) {
                        if (empty($value->email)) {
                            continue;
                        }
    
                        $params['email'] = $value->email;
                        $params['text'] = '<p><strong>Dear Sir/Madam ' . $value->name . '</strong>,</p> <p> ' . $data->user->name . '  / ' . $data->user->nik . ' applied for Cancel Leave/Permit.</p>';
                        $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                        dispatch($job);
                    }
                    Config::set('database.default', $db);
                    $params['text'] = '<p> ' . $data->user->name . '  / ' . $data->user->nik . ' applied for Cancel Leave/Permit.</p>';
                }
    
                $userApprovalTokens = user_approval_firebase_tokens($settingApprovalItem);
                info("structure id ".$settingApprovalItem);
                info($userApprovalTokens);

                foreach (user_approval_id($settingApprovalItem) as $value) {
                    \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $value, $data, 'leave_approval');
                }
        
                if(count($userApprovalTokens) > 0){
                    $config = [
                        'title' => "Leave/Permit Approval",
                        'content' => strip_tags($params['text']),
                        'type' => 'leave_approval',
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
                // $data->historyApproval()->delete();
            }
        } else if ($data->status == 2) {
            $data->status = 6;
            // $data->historyApproval()->delete();
            $historyApproval = $user->approval->items;
            $settingApprovalItem = $historyApproval[0]->structure_organization_custom_id;
            foreach ($historyApproval as $level => $value) {
                # code...
                $history = new HistoryApprovalLeave();
                $history->cuti_karyawan_id                  = $data->id;
                $history->setting_approval_level_id         = ($level + 1);
                $history->structure_organization_custom_id  = $value->structure_organization_custom_id;
                $history->is_withdrawal                     = 1;
                $history->save();
            }
            $historyApprov = HistoryApprovalLeave::where('cuti_karyawan_id', $data->id)->orderBy('setting_approval_level_id', 'asc')->get();

            $userApproval = user_approval_custom($settingApprovalItem);
            $params = getEmailConfig();
            $db = Config::get('database.default', 'mysql');

            $params['data'] = $data;
            $params['value'] = $historyApprov;
            $params['view'] = 'email.leave-approval-custom';
            $params['subject'] = get_setting('mail_name') . ' - Submission of Leave / Permit';
            if ($userApproval) {
                Config::set('database.default', 'mysql');
                foreach ($userApproval as $key => $value) {
                    if (empty($value->email)) {
                        continue;
                    }

                    $params['email'] = $value->email;
                    $params['text'] = '<p><strong>Dear Sir/Madam ' . $value->name . '</strong>,</p> <p> ' . $data->user->name . '  / ' . $data->user->nik . ' applied for Withdrawal Leave/Permit and currently waiting your approval.</p>';
                    $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                    dispatch($job);
                }
                Config::set('database.default', $db);
                $params['text'] = '<p> ' . $data->user->name . '  / ' . $data->user->nik . ' applied for Withdrawal Leave/Permit and currently waiting your approval.</p>';
            }

            $userApprovalTokens = user_approval_firebase_tokens($settingApprovalItem);
            info("structure id ".$settingApprovalItem);
            info($userApprovalTokens);

            foreach (user_approval_id($settingApprovalItem) as $value) {
                \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $value, $data, 'leave_approval');
            }
    
            if(count($userApprovalTokens) > 0){
                $config = [
                    'title' => "Leave/Permit Approval",
                    'content' => strip_tags($params['text']),
                    'type' => 'leave_approval',
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
        } else {
            return redirect()->route('karyawan.leave.index')->with('message-error', 'Submit request with this status is not allowed!');
        }
        $data->save();

        return redirect()->route('karyawan.leave.index')->with('message-success', 'Data saved successfully !');
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
}
