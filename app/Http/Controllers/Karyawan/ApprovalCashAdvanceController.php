<?php

namespace App\Http\Controllers\Karyawan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CashAdvance;
use App\Models\HistoryApprovalCashAdvance;
use App\Models\CashAdvanceForm;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use App\Models\TransferSetting;
use App\Models\StructureOrganizationCustom; 
use App\Models\OrganisasiDivision;
use App\Models\OrganisasiPosition;

class ApprovalCashAdvanceController extends Controller
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
    public function index(Request $request)
    {
        $user = auth()->user();
        
        if(auth()->user()->project_id != NULL)
        {
            $params['division'] = OrganisasiDivision::where('project_id', $user->project_id)->select('organisasi_division.*')->get();
            $params['position'] = OrganisasiPosition::where('project_id', $user->project_id)->select('organisasi_position.*')->get();
        } else
        {
            $params['division'] = OrganisasiDivision::all();
            $params['position'] = OrganisasiPosition::all();
        }

        if(count(request()->all())) {
            \Session::put('aca-employee_status', request()->employee_status);
            \Session::put('aca-position_id', request()->position_id);
            \Session::put('aca-division_id', request()->division_id);
            \Session::put('aca-name', request()->name);
        }

        $employee_status    = \Session::get('aca-employee_status');
        $position_id        = \Session::get('aca-position_id');
        $division_id        = \Session::get('aca-division_id');
        $name               = \Session::get('aca-name');

        $cek_transfer_approve = TransferSetting::where('user_id', auth()->user()->id)->first();

        if($cek_transfer_approve != null){
                $data= HistoryApprovalCashAdvance::join('cash_advance','cash_advance.id','=','history_approval_cash_advance.cash_advance_id')->orderBy('cash_advance_id', 'DESC')
                    ->where('structure_organization_custom_id', \Auth::user()->structure_organization_custom_id);
            if(empty($name) && empty($employee_status) && empty($position_id) && empty($division_id)) {
                $data = $data->orWhere(function($qry){
                        $qry->where('payment_method', 'Bank Transfer');
                    })
                    ->orderByRaw('IF(structure_organization_custom_id = '.auth()->user()->structure_organization_custom_id.', 0,1)');
            }
        }
        else{
            $data= HistoryApprovalCashAdvance::join('cash_advance','cash_advance.id','=','history_approval_cash_advance.cash_advance_id')->orderBy('cash_advance_id', 'DESC')
                ->where('structure_organization_custom_id', \Auth::user()->structure_organization_custom_id)
                ->groupBy('cash_advance.id');
        }

        if(request())
        {
            if (!empty($name)) {
                $data = $data->whereHas('cashAdvance.user', function($qry) use($name){
                        $qry->where('name', 'LIKE', '%' . $name . '%')
                        ->orWhere('nik', 'LIKE', '%' . $name . '%');
                    })
                    ->orWhere('number', 'LIKE', '%' . $name . '%');
            }
            // dd($data->where('number', 'LIKE', '%' . $name . '%')->get());
            
            if(!empty($employee_status))
            {
                $data = $data->whereHas('cashAdvance.user', function($qry) use($employee_status){
                    $qry->where('organisasi_status', $employee_status);
                });
            }

            if((!empty($division_id)) and (empty($position_id))) 
            {   
                $data = $data->whereHas('cashAdvance.user', function($qry) use($division_id){
                    $qry->join('structure_organization_custom','users.structure_organization_custom_id','=','structure_organization_custom.id')->where('structure_organization_custom.organisasi_division_id',$division_id);
                });
            }

            if((!empty($position_id)) and (empty($division_id)))
            {   
                $data = $data->whereHas('cashAdvance.user', function($qry) use($position_id){
                    $qry->join('structure_organization_custom','users.structure_organization_custom_id','=','structure_organization_custom.id')->where('structure_organization_custom.organisasi_position_id',$position_id);
                });
            }
            if((!empty($position_id)) and (!empty($division_id)))
            {
                $data = $data->whereHas('cashAdvance.user', function($qry) use($position_id, $division_id){
                    $qry->join('structure_organization_custom','users.structure_organization_custom_id','=','structure_organization_custom.id')->where('structure_organization_custom.organisasi_position_id',$position_id)->where('structure_organization_custom.organisasi_division_id',$division_id);
                });
            }
        }  
        
        if(request()->reset == 1)
        {
            \Session::forget('aca-employee_status');
            \Session::forget('aca-position_id');
            \Session::forget('aca-division_id');
            \Session::forget('aca-name');

            return redirect()->route('karyawan.approval-cash-advance.index');
        }
            
        $params['data'] = $data->orderBy('cash_advance_id', 'DESC')->get()->unique('cash_advance_id');
        
        return view('karyawan.approval-cash-advance.index')->with($params);
    }

    public function detail($id)
    {   
        $params['data'] = cek_cash_advance_id_approval($id);

        if (!$params['data']) {
            return redirect()->route('karyawan.approval.cash-advance.index')->with('message-error', 'You don\'t have permission to perform this action!');
        }

        $params['data'] = CashAdvance::where('id', $id)->first();
        $params['history'] = HistoryApprovalCashAdvance::where('cash_advance_id',$id)->where('structure_organization_custom_id', \Auth::user()->structure_organization_custom_id)->first();

        $params['cek'] = cek_transfer_setting_user($id);
        if (!$params['cek'] && !$params['history']) {
            return redirect()->route('karyawan.approval.cash-advance.index')->with('message-error', 'You don\'t have permission to perform this action!');
        }
        elseif ($params['cek'] && !$params['history']){
            $params['history'] = HistoryApprovalCashAdvance::where('cash_advance_id',$id)->orderBy('id', 'DESC')->first();
        }

        return view('karyawan.approval-cash-advance.detail')->with($params);
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
        if(isset($request->nominal_approve))
        {
            foreach($request->nominal_approve as $k => $item)
            {
                $i = CashAdvanceForm::where('id', $k)->first();
                if($i)
                {
                    $i->note                = $request->note[$k];
                    $i->nominal_approved    = preg_replace('/[^0-9]/', '', $item);
                    if(get_setting('period_ca_pr') == 'yes' && $i->sisa_plafond != null && $i->sisa_plafond != $i->plafond){
                        $i->sisa_plafond    = $request->sisa_plafond[$k] != NULL && $request->sisa_plafond[$k] >= 0  ? $request->sisa_plafond[$k] - preg_replace('/[^0-9]/', '', $item) : $i->sisa_plafond + $i->amount - preg_replace('/[^0-9]/', '', $item);
                    }
                    $i->save();
                }
            }
        }

        $cash_advance    = CashAdvance::find($request->id);
        $params             = getEmailConfig();
        $params['data']     = $cash_advance;
        $params['value']    = $cash_advance->historyApproval;
        $params['subject']  = get_setting('mail_name') . ' - Cash Advance';
        $params['view']     = 'email.cash-advance';


        $approval                = HistoryApprovalCashAdvance::where(['cash_advance_id'=>$cash_advance->id,'structure_organization_custom_id'=> $user->structure_organization_custom_id])->first();
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
            $cash_advance->status = 3;
            $params['total']    = total_cash_advance_nominal($cash_advance->id);
            $params['text']     = '<p><strong>Dear Sir/Madam '. $cash_advance->user->name .'</strong>,</p> <p>  Submission of your Cash Advance <strong style="color: red;">REJECTED</strong>.</p>';
            Config::set('database.default', 'mysql');
            if(!empty($cash_advance->user->email)) {
                $params['email'] = $cash_advance->user->email;
                $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                dispatch($job);
            }
            Config::set('database.default', $db);

            $notifTitle = "Cash Advance";
            $notifType  = "cash_advance";
            if($cash_advance->user->firebase_token) {
                array_push($userApprovalTokens, $cash_advance->user->firebase_token);
                $this->sentNotif($notifTitle, $params['text'], $notifType, $userApprovalTokens, $cash_advance->id);
                $userApprovalTokens = [];
            }
            \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $cash_advance->user->id, $cash_advance, $notifType);
        }else if($approval->is_approved == 1){
            $lastApproval = $cash_advance->historyApproval->last();
            if($approval->structure_organization_custom_id == $lastApproval->structure_organization_custom_id){
                $params['total']    = total_cash_advance_nominal_approved($cash_advance->id);
                $params['text']     = '<p><strong>Dear Sir/Madam '. $cash_advance->user->name .'</strong>,</p> <p>  Submission of your Cash Advance <strong style="color: green;">APPROVED</strong>.</p>';
                $cash_advance->status = 2;
                Config::set('database.default', 'mysql');
                if(!empty($cash_advance->user->email)) {
                    $params['email'] = $cash_advance->user->email;
                    $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                    dispatch($job);
                }
                Config::set('database.default', $db);

                $notifTitle = "Cash Advance";
                $notifType  = "cash_advance";
                if($cash_advance->user->firebase_token) {
                    array_push($userApprovalTokens, $cash_advance->user->firebase_token);
                    $this->sentNotif($notifTitle, $params['text'], $notifType, $userApprovalTokens, $cash_advance->id);
                    $userApprovalTokens = [];
                }
                \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $cash_advance->user->id, $cash_advance, $notifType);
                $userApproval = TransferSetting::get();
                if($userApproval && $cash_advance->payment_method=='Bank Transfer') {
                    Config::set('database.default', 'mysql');
                    foreach ($userApproval as $key => $value) {
                        if ($value->user->email == "") continue;
                        $params['text']     = '<p><strong>Dear Sir/Madam '. $value->user->name .'</strong>,</p> <p> '. $cash_advance->user->name .'  / '.  $cash_advance->user->nik .' applied for Cash Advance and currently waiting your payment.</p>';
                        $params['email'] = $value->user->email;
                        $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                        dispatch($job);

                        $notifTitle = "Transfer Cash Advance";
                        $notifType  = "transfer_cash_advance_approve";
                        if($value->user->firebase_token) {
                            array_push($userApprovalTokens, $value->user->firebase_token);
                            $this->sentNotif($notifTitle, $params['text'], $notifType, $userApprovalTokens, $cash_advance->id);
                            $userApprovalTokens = [];
                        }
                        \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $value->user->id, $cash_advance, $notifType);
                    }
                    Config::set('database.default', $db);

                }

            }else{
                $cash_advance->status = 1;
                $nextApproval = HistoryApprovalCashAdvance::where(['cash_advance_id'=>$cash_advance->id,'setting_approval_level_id'=> ($approval->setting_approval_level_id+1)])->first();
                if($nextApproval){
                    $userApproval = user_approval_custom($nextApproval->structure_organization_custom_id);
                    //$userApproval = getAdminByModule(32);
                    $params['total']    = total_cash_advance_nominal($cash_advance->id);

                    if($userApproval) {
                        Config::set('database.default', 'mysql');
                        foreach ($userApproval as $key => $value) {
                            if (empty($value->email)) continue;
                            $params['text']     = '<p><strong>Dear Sir/Madam '. $value->name .'</strong>,</p> <p> '. $cash_advance->user->name .'  / '.  $cash_advance->user->nik .' applied for Cash Advance and currently waiting your approval.</p>';
                            $params['email'] = $value->email;
                            $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                            dispatch($job);
                        }
                        Config::set('database.default', $db);

                        $params['text'] = '<p> '. $cash_advance->user->name .'  / '.  $cash_advance->user->nik .' applied for Cash Advance and currently waiting your approval.</p>';
                        $notifTitle = "Cash Advance Approval";
                        $notifType  = "cash_advance_approval";
                        $userApprovalTokens = user_approval_firebase_tokens($nextApproval->structure_organization_custom_id);
                        $userApprovalIds = user_approval_id($nextApproval->structure_organization_custom_id);
                        $this->sentNotif($notifTitle, $params['text'], $notifType, $userApprovalTokens, $cash_advance->id);
                        $userApprovalTokens = [];
                    }
                }
            }
        }
        $cash_advance->save();

        foreach ($userApprovalIds as $value) {
            \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $value, $cash_advance, $notifType);
        }

        return redirect()->route('karyawan.approval.cash-advance.index')->with('message-success', 'Form Cash Advance Successfully Processed !');
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

    public function claim($id)
    {   
        $params['data'] = cek_cash_advance_id_approval($id);

        if (!$params['data']) {
            return redirect()->route('karyawan.approval.cash-advance.index')->with('message-error', 'You don\'t have permission to perform this action!');
        }

        $params['data'] = CashAdvance::where('id', $id)->first();
        $params['history'] = HistoryApprovalCashAdvance::where('cash_advance_id',$id)->where('structure_organization_custom_id', \Auth::user()->structure_organization_custom_id)->first();
        
        $params['cek'] = cek_transfer_setting_user($id);
        if (!$params['cek'] && !$params['history']) {
            return redirect()->route('karyawan.approval.cash-advance.index')->with('message-error', 'You don\'t have permission to perform this action!');
        }
        elseif ($params['cek'] && !$params['history']){
            $params['history'] = HistoryApprovalCashAdvance::where('cash_advance_id',$id)->orderBy('id', 'DESC')->first();
        }

        return view('karyawan.approval-cash-advance.claim')->with($params);
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

        if(isset($request->nominal_claimed))
        {
            foreach($request->nominal_claimed as $k => $item)
            {
                $i = CashAdvanceForm::where('id', $k)->first();
                if($i)
                {
                    $i->note_claimed                = $request->note_claimed[$k];
                    $i->nominal_claimed    = preg_replace('/[^0-9]/', '', $item);
                    if(get_setting('period_ca_pr') == 'yes' && $i->sisa_plafond != null && $i->sisa_plafond != $i->plafond){
                        $i->sisa_plafond    = $request->sisa_plafond[$k] != NULL && $request->sisa_plafond[$k] >= 0  ? $request->sisa_plafond[$k] - preg_replace('/[^0-9]/', '', $item) : $i->sisa_plafond + $i->actual_amount - preg_replace('/[^0-9]/', '', $item);
                    }
                    $i->save();
                }
            }
        }

        $cash_advance    = CashAdvance::find($request->id);
        $params             = getEmailConfig();
        $params['data']     = $cash_advance;
        $params['value']    = $cash_advance->historyApproval;
        $params['subject']  = get_setting('mail_name') . ' - Cash Advance';
        $params['view']     = 'email.cash-advance';


        $approval                = HistoryApprovalCashAdvance::where(['cash_advance_id'=>$cash_advance->id,'structure_organization_custom_id'=> $user->structure_organization_custom_id])->first();
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
            $cash_advance->status_claim = 3;
            $params['total']    = total_cash_advance_actual_amount($cash_advance->id);
            $params['text'] = '<p><strong>Dear Sir/Madam ' . $cash_advance->user->name . '</strong>,</p> <p>  Submission of your Claim of Cash Advance <strong style="color: red;">REJECTED</strong>.</p>';
            Config::set('database.default', 'mysql');
            if($cash_advance->user->email && $cash_advance->user->email != "") {
                $params['email'] = $cash_advance->user->email;
                $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                dispatch($job);
            }
            Config::set('database.default', $db);

            $notifTitle = "Claim Cash Advance";
            $notifType  = "claim_cash_advance";
            if($cash_advance->user->firebase_token) {
                array_push($userApprovalTokens, $cash_advance->user->firebase_token);
                $this->sentNotif($notifTitle, $params['text'], $notifType, $userApprovalTokens, $cash_advance->id);
                $userApprovalTokens = [];
            }
            \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $cash_advance->user->id, $cash_advance, $notifType);
        }else if($approval->is_approved_claim == 1){
            $lastApproval = $cash_advance->historyApproval->last();
            if($approval->structure_organization_custom_id == $lastApproval->structure_organization_custom_id){
                $params['total']    = total_cash_advance_nominal_claimed($cash_advance->id);
                $params['text']     = '<p><strong>Dear Sir/Madam ' . $cash_advance->user->name . '</strong>,</p> <p>  Submission of your Claim of Cash Advance <strong style="color: green;">APPROVED</strong>.</p>';
                $cash_advance->status_claim = 2;
                Config::set('database.default', 'mysql');
                if($cash_advance->user->email && $cash_advance->user->email != "") {
                    $params['email'] = $cash_advance->user->email;
                    $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                    dispatch($job);
                }
                Config::set('database.default', $db);

                $notifTitle = "Claim Cash Advance";
                $notifType  = "claim_cash_advance";
                if($cash_advance->user->firebase_token) {
                    array_push($userApprovalTokens, $cash_advance->user->firebase_token);
                    $this->sentNotif($notifTitle, $params['text'], $notifType, $userApprovalTokens, $cash_advance->id);
                    $userApprovalTokens = [];
                }
                \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $cash_advance->user->id, $cash_advance, $notifType);
                if(total_cash_advance_nominal_claimed($cash_advance->id) > total_cash_advance_nominal_approved($cash_advance->id) && $cash_advance->payment_method=='Bank Transfer'){
                    $params['total']    = (total_cash_advance_nominal_claimed($cash_advance->id) - total_cash_advance_nominal_approved($cash_advance->id));
                    $userApproval = TransferSetting::get();

                    if($userApproval) {
                        Config::set('database.default', 'mysql');
                        foreach ($userApproval as $key => $value) {
                            if ($value->user->email == "") continue;
                            $params['text']     = '<p><strong>Dear Sir/Madam '. $value->user->name .'</strong>,</p> <p> '. $cash_advance->user->name .'  / '.  $cash_advance->user->nik .' applied for Claim of Cash Advance and currently waiting your payment lack from cash advance.</p>';
                            $params['email'] = $value->user->email;
                            $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                            dispatch($job);
                            
                            $notifTitle = "Transfer Claim Cash Advance";
                            $notifType  = "transfer_claim_cash_advance";
                            if($value->user->firebase_token) {
                                array_push($userApprovalTokens, $value->user->firebase_token);
                                $this->sentNotif($notifTitle, $params['text'], $notifType, $userApprovalTokens, $cash_advance->id);
                                $userApprovalTokens = [];
                            }
                            \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $value->user->id, $cash_advance, $notifType);
                        }
                        Config::set('database.default', $db);
                    }
                }
                else if(total_cash_advance_nominal_claimed($cash_advance->id) < total_cash_advance_nominal_approved($cash_advance->id) && $cash_advance->payment_method=='Bank Transfer'){
                    $params['total']    = (total_cash_advance_nominal_approved($cash_advance->id) - total_cash_advance_nominal_claimed($cash_advance->id));
                    
                    $userApproval = TransferSetting::get();

                    if($userApproval) {
                        Config::set('database.default', 'mysql');
                        foreach ($userApproval as $key => $value) {
                            if ($value->user->email == "") continue;
                            $params['text']     = '<p><strong>Dear Sir/Madam '. $value->user->name .'</strong>,</p> <p> '. $cash_advance->user->name .'  / '.  $cash_advance->user->nik .' applied for Claim of Cash Advance and total approved was greater than what was claimed, so she/he had to return the excess.</p>';
                            $params['email'] = $value->user->email;
                            $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                            dispatch($job);
                            
                            $notifTitle = "Transfer Claim Cash Advance";
                            $notifType  = "transfer_claim_cash_advance";
                            if($value->user->firebase_token) {
                                array_push($userApprovalTokens, $value->user->firebase_token);
                                $this->sentNotif($notifTitle, $params['text'], $notifType, $userApprovalTokens, $cash_advance->id);
                                $userApprovalTokens = [];
                            }
                            \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $value->user->id, $cash_advance, $notifType);
                        }
                        Config::set('database.default', $db);
                    }
                    
                    Config::set('database.default', 'mysql');
                    if($cash_advance->user->email && $cash_advance->user->email != "") {
                        $params['email'] = $cash_advance->user->email;
                        $params['text']     = '<p><strong>Dear Sir/Madam '. $cash_advance->user->name .'</strong>,</p> <p> Total claimed is less than the total approved, so you must return the excess. which will be followed up by the company.</p>';
                        $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                        dispatch($job);
                    }
                    Config::set('database.default', $db);

                    $notifTitle = "Transfer Claim Cash Advance";
                    $notifType  = "transfer_back_claim_cash_advance_more";
                    $userApprovalTokens = [];
                    if($cash_advance->user->firebase_token) {
                        array_push($userApprovalTokens, $cash_advance->user->firebase_token);
                        $this->sentNotif($notifTitle, $params['text'], $notifType, $userApprovalTokens, $cash_advance->id);
                        $userApprovalTokens = [];
                    }
                    \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $cash_advance->user->id, $cash_advance, $notifType);
                }
            }else{
                $cash_advance->status_claim = 1;
                $nextApproval = HistoryApprovalCashAdvance::where(['cash_advance_id'=>$cash_advance->id,'setting_approval_level_id'=> ($approval->setting_approval_level_id+1)])->first();
                if($nextApproval){
                    $userApproval = user_approval_custom($nextApproval->structure_organization_custom_id);
                    //$userApproval = getAdminByModule(32);
                    if($userApproval) {
                        Config::set('database.default', 'mysql');
                        foreach ($userApproval as $key => $value) {
                            if ($value->email == "") continue;
                            $params['text']     = '<p><strong>Dear Sir/Madam '. $value->name .'</strong>,</p> <p> '. $cash_advance->user->name .'  / '.  $cash_advance->user->nik .' applied for Claim of Cash Advance and currently waiting your approval.</p>';
                            $params['email'] = $value->email;
                            $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                            dispatch($job);
                        }
                        Config::set('database.default', $db);

                        $params['text'] = '<p> '. $cash_advance->user->name .'  / '.  $cash_advance->user->nik .' applied for Claim of Cash Advance and currently waiting your approval.</p>';
                        $notifTitle = "Claim Cash Advance Approval";
                        $notifType  = "claim_cash_advance_approval";
                        $userApprovalTokens = user_approval_firebase_tokens($nextApproval->structure_organization_custom_id);
                        $userApprovalIds = user_approval_id($nextApproval->structure_organization_custom_id);
                        $this->sentNotif($notifTitle, $params['text'], $notifType, $userApprovalTokens, $cash_advance->id);
                        $userApprovalTokens = [];
                    }
                }
            }
        }
        $cash_advance->save();

        foreach ($userApprovalIds as $value) {
            \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $value, $cash_advance, $notifType);
        }

        return redirect()->route('karyawan.approval.cash-advance.index')->with('message-success', 'Form Cash Advance Successfully Processed !');
    }

    public function detailTransfer($id)
    {   
        $params['data'] = cek_transfer_setting_user($id);

        if (!$params['data']) {
            return redirect()->route('karyawan.approval.cash-advance.index')->with('message-error', 'You don\'t have permission to perform this action!');
        }

        $params['data'] = CashAdvance::where('id', $id)->first();
        $params['history'] = HistoryApprovalCashAdvance::where('cash_advance_id',$id)->where('cash_advance_id',$id)->orderBy('id', 'DESC')->first();

        return view('karyawan.approval-cash-advance.transfer')->with($params);
    }

    public function transfer(Request $request, $id){
        //dd($request);
        $data = CashAdvance::find($id);
        $data->is_transfer= $request->is_transfer;
        $data->is_transfer_by = auth()->user()->id;
        $data->disbursement = $request->disbursement;
        if($request->hasFile('transfer_proof_by_admin'))
        {
            $image = $request->transfer_proof_by_admin;
            $name = md5($id.'transfer_proof').'.'.$image->getClientOriginalExtension();
            $company_url = session('company_url','umum').'/';
            $destinationPath = public_path('storage/cash-advance/transfer-proof/').$company_url;
            $image->move($destinationPath, $name);
            $data->transfer_proof = $company_url.$name;
        }
        $data->save();

        $userApprovalTokens = [];

        $db = Config::get('database.default','mysql');
        $params             = getEmailConfig();
        $params['data']     = $data;
        $params['value']    = $data->historyApproval;
        $params['subject']  = get_setting('mail_name') . ' - Transfer Cash Advance';
        $params['view']     = 'email.cash-advance';
        $params['total']    = total_cash_advance_nominal_approved($data->id);
        if($request->disbursement=='Transfer'){
            $params['text']     = '<p><strong>Dear Sir/Madam '. $data->user->name .'</strong>,</p> <p>  Submission of your Cash Advance has been transfered.</p>';
        }else{
            $params['text']     = '<p><strong>Dear Sir/Madam '. $data->user->name .'</strong>,</p> <p>  Submission of your Cash Advance will be merged with the next payroll.</p>';
        }
        Config::set('database.default', 'mysql');

        if(!empty($data->user->email)) {
            $params['email'] = $data->user->email;
            $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
            dispatch($job);
        }
        Config::set('database.default', $db);
        
        $notifTitle = "Transfer Cash Advance";
        $notifType  = "transfer_cash_advance";

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

        return redirect()->route('karyawan.approval.cash-advance.index')->with('message-success', 'Transfer Proof Successfully Sent!');
    }

    public function detailTransferClaim($id)
    {   
        $params['data'] = cek_transfer_setting_user($id);

        if (!$params['data']) {
            return redirect()->route('karyawan.approval.cash-advance.index')->with('message-error', 'You don\'t have permission to perform this action!');
        }

        $params['data'] = CashAdvance::where('id', $id)->first();
        $params['history'] = HistoryApprovalCashAdvance::where('cash_advance_id',$id)->orderBy('id', 'DESC')->first();

        return view('karyawan.approval-cash-advance.transferClaim')->with($params);
    }

    public function transferClaim(Request $request, $id){
        //dd($request);
        $data = CashAdvance::find($id);
        $data->is_transfer_claim = $request->is_transfer_claim;
        $data->is_transfer_claim_by = auth()->user()->id;
        $data->disbursement_claim = $request->disbursement_claim;
        if($request->hasFile('transfer_proof_claim_by_admin'))
        {
            $image = $request->transfer_proof_claim_by_admin;
            $name = md5($id.'transfer_proof_claim').'.'.$image->getClientOriginalExtension();
            $company_url = session('company_url','umum').'/';
            $destinationPath = public_path('storage/cash-advance/transfer-proof/').$company_url;
            $image->move($destinationPath, $name);
            $data->transfer_proof_claim = $company_url.$name;
        }
        $data->save();

        $userApprovalTokens = [];

        $db = Config::get('database.default','mysql');
        $params             = getEmailConfig();
        $params['data']     = $data;
        $params['value']    = $data->historyApproval;
        $params['subject']  = get_setting('mail_name') . ' - Transfer Claim Cash Advance';
        $params['view']     = 'email.cash-advance';
        $params['total']    =  (total_cash_advance_nominal_claimed($data->id) - total_cash_advance_nominal_approved($data->id));
        if($request->disbursement_claim=='Transfer'){
            $params['text']     = '<p><strong>Dear Sir/Madam '. $data->user->name .'</strong>,</p> <p>  Submission of your Cash Advance Claim has been transfered.</p>';
        }else{
            $params['text']     = '<p><strong>Dear Sir/Madam '. $data->user->name .'</strong>,</p> <p>  Submission of your Cash Advance Claim will be merged with the next payroll.</p>';
        }
        Config::set('database.default', 'mysql');

        if(!empty($data->user->email)) {
            $params['email'] = $data->user->email;
            $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
            dispatch($job);
        }
        Config::set('database.default', $db);
        
        $notifTitle = "Transfer Claim Cash Advance";
        $notifType  = "transfer_claim_cash_advance_less";

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

        return redirect()->route('karyawan.approval.cash-advance.index')->with('message-success', 'Transfer Proof Claim Successfully Sent!');
    }

}
