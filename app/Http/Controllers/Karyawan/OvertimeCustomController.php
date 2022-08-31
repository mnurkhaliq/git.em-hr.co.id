<?php

namespace App\Http\Controllers\Karyawan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\OvertimeSheet;
use App\Models\OvertimeSheetForm;
use App\User;
use App\Models\HistoryApprovalOvertime;
use App\Models\AbsensiItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;


class OvertimeCustomController extends Controller
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
        $params['data'] = OvertimeSheet::where('user_id', \Auth::user()->id)->orderBy('id', 'DESC')->get();

        return view('karyawan.overtime-custom.index')->with($params);
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
            return redirect()->route('karyawan.overtime-custom.index')->with('message-error', 'Your position is not defined yet. Please contact your admin!');
        }else {
            $checkApproval = $checkApproval->level1Overtime;
            if ($checkApproval == null) {
                return redirect()->route('karyawan.overtime-custom.index')->with('message-error', 'Setting approval is not define yet. Please contact your admin !');
            }
        }
        $params['karyawan'] = User::where('access_id', \Auth::user()->id)->get();

        return view('karyawan.overtime-custom.create')->with($params);
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
        $checkApproval = \Auth::user()->approval;
        if($checkApproval == null)
        {
            return redirect()->route('karyawan.overtime-custom.index')->with('message-error', 'Your position is not defined yet. Please contact your admin!');
        }else{
            $checkApproval = $checkApproval->level1Overtime;
            if($checkApproval == null){
                return redirect()->route('karyawan.overtime-custom.index')->with('message-error', 'Setting approval is not define yet. Please contact your admin !');
            }
            $data                       = new OvertimeSheet();
            $data->user_id              = \Auth::user()->id;
            $data->status               = 1;  
            $data->save();

            foreach($request->tanggal as $key => $item)
            {   
                $form               = new OvertimeSheetForm();
                $form->overtime_sheet_id= $data->id;
                $form->description  = $request->description[$key];
                $form->awal         = $request->awal[$key];
                $form->akhir        = $request->akhir[$key];
                $form->total_lembur = $request->total_lembur[$key];
                $form->tanggal      = $request->tanggal[$key];
                $form->save();
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
                \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $value, $data, 'overtime_approval');
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

            return redirect()->route('karyawan.overtime-custom.index')->with('message-success', 'Data successfully saved!');
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
        $params['data'] = OvertimeSheet::where('id', $id)->first();

        if (!IsAccess($params['data'])) {
            return redirect()->route('karyawan.overtime-custom.index')->with('message-error', 'You don\'t have permission to perform this action!');
        }

        return view('karyawan.overtime-custom.edit')->with($params);
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

    public function claim($id)
    {
        $params['data'] = OvertimeSheet::where('id', $id)->first();

        if (!IsAccess($params['data'])) {
            return redirect()->route('karyawan.overtime-custom.index')->with('message-error', 'You don\'t have permission to perform this action!');
        }

        //$over = OvertimeSheet::select('overtime_sheet.*')->join('overtime_sheet_form', 'overtime_sheet.id','=','overtime_sheet_form.overtime_sheet_id')->join('absensi_item', 'absensi_item.date','=','overtime_sheet_form.tanggal')->join('absensi_item','absensi_item.user_id','=','overtime_sheet.user_id')->where('overtime_sheet.id',$id)->get();

       // dd($over);

        return view('karyawan.overtime-custom.claim')->with($params);
    }

    public function prosesclaim(Request $request)
    {
        $user = Auth::user();
        $data = OvertimeSheet::where('id', $request->id)->first();
        $data->status_claim               = 1;
        $data->date_claim                 = date('Y-m-d H:i:s'); 

        foreach($request->id_overtime_form as $key => $item)
        {
            if($item == "" )
            {
                $form = new \App\Models\OvertimeSheetForm;
                $form->overtime_sheet_id= $data->id;
                $form->description  = $request->description[$key];
                $form->tanggal      = $request->tanggal[$key];
            }else{
                $form = \App\Models\OvertimeSheetForm::where('id', $request->id_overtime_form[$key])->first();
            }
            $form->awal_claim                 = $request->awal_claim[$key];
            $form->akhir_claim                = $request->akhir_claim[$key];
            $form->total_lembur_claim         = $request->total_lembur_claim[$key];
            $form->save();
        }

        $historyApprov        = HistoryApprovalOvertime::where('overtime_sheet_id',$data->id)->orderBy('setting_approval_level_id','asc')->get();
        if(count($historyApprov)>0) {
            $settingApprovalItem = $historyApprov[0]->structure_organization_custom_id;
            $userApproval = user_approval_custom($settingApprovalItem);
            $params = getEmailConfig();
            $db = Config::get('database.default', 'mysql');

            $params['data'] = $data;
            $params['value'] = $historyApprov;
            $params['view'] = 'email.overtime-approval-custom';
            $params['subject'] = get_setting('mail_name') . ' - Overtime Sheet';
            if ($userApproval) {
                Config::set('database.default', 'mysql');
                foreach ($userApproval as $key => $value) {
                    if (empty($value->email)) continue;
                    $params['email'] = $value->email;
                    $params['text'] = '<p><strong>Dear Sir/Madam ' . $value->name . '</strong>,</p> <p> ' . $data->user->name . '  / ' . $data->user->nik . ' applied for Claim of Overtime and currently waiting your approval.</p>';
                    $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                    dispatch($job);
                }
                Config::set('database.default', $db);
                $params['text'] = '<p> ' . $data->user->name . '  / ' . $data->user->nik . ' applied for Claim of Overtime and currently waiting your approval.</p>';
            }
        }
        $data->save();

        $userApprovalTokens = user_approval_firebase_tokens($settingApprovalItem);
        info("structure id ".$settingApprovalItem);
        info($userApprovalTokens);
            
        foreach (user_approval_id($settingApprovalItem) as $value) {
            \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $value, $data, 'overtime_approval');
        }

        if(count($userApprovalTokens) > 0){
            $config = [
                'title' => "Claim Overtime Sheet Approval",
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

        return redirect()->route('karyawan.overtime-custom.index')->with('message-success', 'Data successfully saved!');
    }
}
