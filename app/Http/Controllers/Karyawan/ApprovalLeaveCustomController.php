<?php

namespace App\Http\Controllers\Karyawan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CutiKaryawan;
use App\Models\UserCuti;
use App\Models\Cuti;
use App\Helper\GeneralHelper;
use App\Models\HistoryApprovalLeave;
use App\Models\SettingApprovalLeave;
use App\Models\SettingApprovalLeaveItem;
use App\Models\SettingApprovalLevel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class ApprovalLeaveCustomController extends Controller
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
        $params['datas'] = cek_leave_approval();
       
        return view('karyawan.approval-leave-custom.index')->with($params);
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
        $params['datas'] = cek_leave_id_approval($id);

        if (!$params['datas']) {
            return redirect()->route('karyawan.approval.leave-custom.index')->with('message-error', 'You don\'t have permission to perform this action!');
        }

        $params['datas'] = CutiKaryawan::where('id', $id)->first();
        $params['histories'] = HistoryApprovalLeave::where('cuti_karyawan_id', $params['datas']->id)->where('structure_organization_custom_id', \Auth::user()->structure_organization_custom_id)->get();

        return view('karyawan.approval-leave-custom.detail')->with($params);
    }

    public function proses(Request $request)
    {
        $request->validate([
            'note' => 'required'
        ],
        [
            'note.required' => 'the note field is required!',
        ]); 
        
        $user = Auth::user();

        $cutiKaryawan = CutiKaryawan::find($request->id);
        $params = getEmailConfig();
        $params['data'] = $cutiKaryawan;
        $params['value'] = $cutiKaryawan->historyApproval;
        $params['subject'] = get_setting('mail_name') . ' - Submission of Leave / Permit';
        $params['view'] = 'email.leave-approval-custom';

        $approval = HistoryApprovalLeave::where(['cuti_karyawan_id' => $cutiKaryawan->id, 'structure_organization_custom_id' => $user->structure_organization_custom_id])->latest('is_withdrawal')->first();
        $approval->approval_id      = $user->id;
        $approval->is_approved      = $request->status;
        $approval->date_approved    = date('Y-m-d H:i:s');
        $approval->note             = $request->note;
        $approval->save();

        $db = Config::get('database.default', 'mysql');
        
        $notifTitle = "";
        $notifType = "";
        $userApprovalTokens = [];
        $userApprovalIds = [];

        if ($approval->is_approved == 0) { // Jika rejected
            if ($cutiKaryawan->status == 1) {
                $cutiKaryawan->status = 3;
                $params['text'] = '<p><strong>Dear Sir/Madam ' . $cutiKaryawan->user->name . '</strong>,</p> <p>  Submission of your Leave / Permit <strong style="color: red;">REJECTED</strong>.</p>';
            } else {
                $cutiKaryawan->status = 8;
                $params['text'] = '<p><strong>Dear Sir/Madam ' . $cutiKaryawan->user->name . '</strong>,</p> <p>  Submission of your Withdrawal Leave / Permit <strong style="color: red;">REJECTED</strong>.</p>';
            }
            Config::set('database.default', 'mysql');
            if (!empty($cutiKaryawan->user->email)) {
                $params['email'] = $cutiKaryawan->user->email;
                $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                dispatch($job);
            }
            Config::set('database.default', $db);

            $notifTitle = "Leave/Permit";
            $notifType  = "leave";
            if($cutiKaryawan->user->firebase_token) {
                array_push($userApprovalTokens, $cutiKaryawan->user->firebase_token);
            }
            array_push($userApprovalIds, $cutiKaryawan->user->id);
        } else if ($approval->is_approved == 1) {
            $lastApproval = $cutiKaryawan->historyApproval->last();
            if ($approval->structure_organization_custom_id == $lastApproval->structure_organization_custom_id) {

                $user_cuti = UserCuti::where('user_id', $cutiKaryawan->user_id)->where('cuti_id', $cutiKaryawan->jenis_cuti)->first();

                if (empty($user_cuti)) {
                    $cuti = Cuti::find($cutiKaryawan->jenis_cuti);
                    if ($cuti) {
                        $user_cuti                  = new UserCuti();
                        $user_cuti->kuota           = $cuti->kuota;
                        $user_cuti->user_id         = $cutiKaryawan->user_id;
                        $user_cuti->cuti_id         = $cutiKaryawan->jenis_cuti;
                        $user_cuti->cuti_terpakai   = $cutiKaryawan->total_cuti;
                        $user_cuti->sisa_cuti       = $cuti->kuota - $cutiKaryawan->total_cuti;
                        $user_cuti->save();
                    }
                } else {
                    if ($cutiKaryawan->status == 1) {
                        $user_cuti->cuti_terpakai       = $user_cuti->cuti_terpakai + $cutiKaryawan->total_cuti;
                        $user_cuti->sisa_cuti           = $user_cuti->kuota - $user_cuti->cuti_terpakai;
                        $user_cuti->save();
                    } else {
                        $user_cuti->cuti_terpakai       = $user_cuti->cuti_terpakai - $cutiKaryawan->total_cuti;
                        $user_cuti->sisa_cuti           = $user_cuti->sisa_cuti + $cutiKaryawan->total_cuti;
                        $user_cuti->save();
                    }

                    // // jika cuti maka kurangi kuota
                    // if(strpos($user_cuti->cuti->jenis_cuti, 'Cuti') !== false) {
                    //     // kurangi cuti tahunan user jika sudah di approved
                    //     $user_cuti->cuti_terpakai   = $user_cuti->cuti_terpakai + $cutiKaryawan->total_cuti;
                    //     $user_cuti->sisa_cuti       = $user_cuti->kuota - $user_cuti->cuti_terpakai;
                    //     $user_cuti->save();
                    // }
                }
                if ($cutiKaryawan->status == 1) {
                    $cutiKaryawan->temp_sisa_cuti       = $cutiKaryawan->temp_sisa_cuti - $cutiKaryawan->total_cuti;
                    $cutiKaryawan->temp_cuti_terpakai   = $cutiKaryawan->total_cuti + $cutiKaryawan->temp_cuti_terpakai;
                } else {
                    $cutiKaryawan->temp_sisa_cuti       = $cutiKaryawan->temp_sisa_cuti + $cutiKaryawan->total_cuti;
                    $cutiKaryawan->temp_cuti_terpakai   = $cutiKaryawan->temp_cuti_terpakai - $cutiKaryawan->total_cuti;
                }

                if ($cutiKaryawan->status == 1) {
                    $params['text'] = '<p><strong>Dear Sir/Madam ' . $cutiKaryawan->user->name . '</strong>,</p> <p>  Submission of your Leave / Permit <strong style="color: green;">APPROVED</strong>.</p>';
                    $cutiKaryawan->status = 2;
                } else {
                    $params['text'] = '<p><strong>Dear Sir/Madam ' . $cutiKaryawan->user->name . '</strong>,</p> <p>  Submission of your Withdrawal Leave / Permit <strong style="color: green;">APPROVED</strong>.</p>';
                    $cutiKaryawan->status = 7;
                }
                Config::set('database.default', 'mysql');
                if (!empty($cutiKaryawan->user->email)) {
                    $params['email'] = $cutiKaryawan->user->email;
                    $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                    dispatch($job);
                }
                Config::set('database.default', $db);

                $notifTitle = "Leave/Permit";
                $notifType  = "leave";
                if($cutiKaryawan->user->firebase_token) {
                    array_push($userApprovalTokens, $cutiKaryawan->user->firebase_token);
                }
                array_push($userApprovalIds, $cutiKaryawan->user->id);
            } else {
                if ($cutiKaryawan->status == 1) {
                    $cutiKaryawan->status = 1;
                } else {
                    $cutiKaryawan->status = 6;
                }
                $nextApproval = HistoryApprovalLeave::where(['cuti_karyawan_id' => $cutiKaryawan->id, 'setting_approval_level_id' => ($approval->setting_approval_level_id + 1)])->first();
                if ($nextApproval) {
                    $userApproval = user_approval_custom($nextApproval->structure_organization_custom_id);
                    if ($userApproval) {
                        Config::set('database.default', 'mysql');
                        foreach ($userApproval as $key => $value) {
                            if ($value->email == "") {
                                continue;
                            }
                            if ($cutiKaryawan->status == 1) {
                                $params['text'] = '<p><strong>Dear Sir/Madam ' . $value->name . '</strong>,</p> <p> ' . $cutiKaryawan->user->name . '  / ' . $cutiKaryawan->user->nik . ' applied for Leave/Permit and currently waiting your approval.</p>';
                            } else {
                                $params['text'] = '<p><strong>Dear Sir/Madam ' . $value->name . '</strong>,</p> <p> ' . $cutiKaryawan->user->name . '  / ' . $cutiKaryawan->user->nik . ' applied for Withdrawal Leave/Permit and currently waiting your approval.</p>';
                            }
                            $params['email'] = $value->email;
                            $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                            dispatch($job);
                        }
                        Config::set('database.default', $db);
                        if ($cutiKaryawan->status == 1) {
                            $params['text'] = '<p> ' . $cutiKaryawan->user->name . '  / ' . $cutiKaryawan->user->nik . ' applied for Leave/Permit and currently waiting your approval.</p>';
                        } else {
                            $params['text'] = '<p> ' . $cutiKaryawan->user->name . '  / ' . $cutiKaryawan->user->nik . ' applied for Withdrawal Leave/Permit and currently waiting your approval.</p>';
                        }
                        $notifTitle = "Leave/Permit Approval";
                        $notifType  = "leave_approval";
                        $userApprovalTokens = user_approval_firebase_tokens($nextApproval->structure_organization_custom_id);
                        $userApprovalIds = user_approval_id($nextApproval->structure_organization_custom_id);
                    }
                }
            }
        }
        $cutiKaryawan->save();

        foreach ($userApprovalIds as $value) {
            \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $value, $cutiKaryawan, $notifType);
        }

        if(count($userApprovalTokens) > 0){
            $config = [
                'title' => $notifTitle,
                'content' => strip_tags($params['text']),
                'type' => $notifType,
                'firebase_token' => $userApprovalTokens
            ];
            $notifData = [
                'id' => $cutiKaryawan->id
            ];
            $db = Config::get('database.default', 'mysql');
            Config::set('database.default', 'mysql');
            dispatch((new \App\Jobs\SendPushTokens($notifData,$config))->onQueue('push'));
            Config::set('database.default', $db);
        }

        return redirect()->route('karyawan.approval.leave-custom.index')->with('messages-success', 'Leave Form Successfully processed !');
    }

}
