<?php

namespace App\Http\Controllers\Karyawan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CashAdvance;
use App\Models\CashAdvanceForm;
use App\Models\TransferSetting;
use App\Models\PaymentRequestType;
use App\User;
use Carbon\Carbon;
use App\Models\CashAdvanceOvertime;
use App\Models\OvertimeSheet;
use App\Models\CashAdvanceBensin;
use App\Models\SettingApprovalCashAdvanceItem;
use App\Models\SettingApproval;
use App\Models\Setting;
use App\Models\HistoryApprovalCashAdvance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class CashAdvanceController extends Controller
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
        $params['data'] = CashAdvance::select('cash_advance.*', \DB::raw('SUM(cash_advance_form.nominal_approved) as total_amount_approved'), \DB::raw('SUM(cash_advance_form.nominal_claimed) as total_amount_claimed'))
                        ->join('cash_advance_form','cash_advance_form.cash_advance_id', '=', 'cash_advance.id')->groupBy('cash_advance.id')
                        ->where('user_id', \Auth::user()->id)->orderBy('cash_advance.id', 'DESC')->get();
        $params['type'] = PaymentRequestType::get();
        $params['data_waiting'] = CashAdvance::where('user_id', \Auth::user()->id)->where('status', '!=', 3)
                        ->where(function($query) {
                            $query->where('status', '1')->orWhere('status_claim', NULL)->orWhere('status_claim', '1')->orWhere('status_claim', '4');
                        })->count();
        // dd($params);
        return view('karyawan.cash-advance.index')->with($params);
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
            return redirect()->route('karyawan.cash-advance.index')->with('message-error', 'Your position is not defined yet. Please contact your admin!');
        }else{
            if(count($checkApproval->itemsCashAdvance) == 0){
                return redirect()->route('karyawan.cash-advance.index')->with('message-error', 'Setting approval not define yet. Please contact your admin !');
            }
        }
        $params['karyawan'] = User::whereIn('access_id', [1,2])->get();
        $params['type'] = PaymentRequestType::get();

        return view('karyawan.cash-advance.create')->with($params);
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
        $checkApproval = \Auth::user()->approval;

        if($checkApproval == null)
        {
            return redirect()->route('karyawan.cash-advance.index')->with('message-error', 'Your position is not defined yet. Please contact your admin!');
        }else{
            if(count($checkApproval->itemsCashAdvance) == 0){
                return redirect()->route('karyawan.cash-advance.index')->with('message-error', 'Setting approval not define yet. Please contact your admin !');
            }
            if($checkApproval)
            $data                       = new CashAdvance();
            $data->user_id              = \Auth::user()->id;
            $data->transaction_type     = $request->transaction_type;
            $data->payment_method       = $request->payment_method;
            if($request->payment_method=='Bank Transfer'){
                $data->is_transfer = 0;
                $data->is_transfer_claim = 0;
            }
            $data->tujuan               = $request->tujuan;
            $data->status               = 1;
            $data->number = 'CA-' . Carbon::now()->format('dmY') . '/' . \Auth::user()->nik . '-' . (CashAdvance::where('user_id', \Auth::user()->id)->count() + 1);
            $data->save();

            // jika ada overtime
            if(isset($request->overtime))
            {
                foreach($request->overtime as $k => $i)
                {
                    $form                       = new CashAdvanceOvertime();
                    $form->cash_advance_id   = $data->id;
                    $form->overtime_sheet_id    = $i;
                    $form->save();

                    $ov                         = OvertimeSheet::where('id', $i)->first();
                    $ov->is_cash_advance     = 1;
                    $ov->save();
                }
            }
            $gasolineIds = [];
            foreach($request->description as $key => $item)
            {
                $form = new CashAdvanceForm();
                $form->cash_advance_id   = $data->id;
                $form->description          = $item;
                $form->type_form            = $request->type[$key];
                // $form->quantity             = $request->quantity[$key];
                // $form->estimation_cost      = $request->estimation_cost[$key];
                $form->amount               = preg_replace('/[^0-9]/', '', $request->amount[$key]);
                $form->plafond               = $request->plafond[$key] != null ? preg_replace('/[^0-9]/', '', $request->plafond[$key]) : NULL;
                if(get_setting('period_ca_pr') == 'yes'){
                    $form->sisa_plafond         = $request->amount[$key] != null && $request->sisa_plafond[$key] != null  ? (int) preg_replace('/[^0-9]/', '', $request->sisa_plafond[$key]) - (int) preg_replace('/[^0-9]/', '', $request->amount[$key]) : NULL;
                }
                else{
                    $form->sisa_plafond         = $request->plafond[$key] != null ? preg_replace('/[^0-9]/', '', $request->plafond[$key]) : NULL;
                }

                if($request->hasFile('file_struk'))
                {
                    foreach($request->file_struk as $k => $file)
                    {
                        if ($file and $key == $k ) {

                            $image = $file;
                            $name = md5(rand() . $image->getClientOriginalName() . time()) . "." . $image->getClientOriginalExtension();
                            $company_url = session('company_url','umum').'/';
                            $destinationPath = public_path('storage/cash-advance/file-struk/').$company_url;
                            $image->move($destinationPath, $name);
                            $form->file_struk = $company_url.$name;
                        }
                    }
                }

                //dd($request->hasFile('file_struk'));

                $form->save();
                if(strtolower($form->type_form) == 'gasoline')
                    array_push($gasolineIds,$form->id);
            }

            if(isset($request->bensin))
            {
                foreach($request->bensin['tanggal'] as $k => $item)
                {
                    $bensin                          = new CashAdvanceBensin();
                    $bensin->cash_advance_id      = $data->id;
                    $bensin->cash_advance_form_id = isset($gasolineIds[$k])?$gasolineIds[$k]:null;
                    $bensin->user_id                 = \Auth::user()->id;
                     $bensin->tanggal                = Carbon::parse($request->bensin['tanggal'][$k])->format('d.m.y');
                    //$bensin->tanggal            = $request->bensin['tanggal'][$k];
                    $bensin->odo_start               = $request->bensin['odo_from'][$k];
                    $bensin->odo_end                 = $request->bensin['odo_to'][$k];
                    $bensin->liter                   = $request->bensin['liter'][$k];
                    $bensin->cost                    = preg_replace('/[^0-9]/', '', $request->bensin['cost'][$k]);
                    $bensin->save();
                }
            }

            $historyApproval     = $user->approval->itemsCashAdvance;
            $settingApprovalItem = $historyApproval[0]->structure_organization_custom_id;
            foreach ($historyApproval as $level => $value) {
                # code...
                $history = new HistoryApprovalCashAdvance();
                $history->cash_advance_id               = $data->id;
                $history->setting_approval_level_id        = ($level+1);
                $history->structure_organization_custom_id = $value->structure_organization_custom_id;
                $history->save();
            }
            $historyApprov = HistoryApprovalCashAdvance::where('cash_advance_id',$data->id)->orderBy('setting_approval_level_id','asc')->get();

            $userApproval = user_approval_custom($settingApprovalItem);
            //$userApproval = getAdminByModule(32);
            $db = Config::get('database.default','mysql');

            $params = getEmailConfig();
            $params['data']     = $data;
            $params['total']    = total_cash_advance_nominal($data->id);
            $params['value']    = $historyApprov;
            $params['view']     = 'email.cash-advance';
            $params['subject']  = get_setting('mail_name') . ' - Cash Advance';
            if($userApproval) {
                Config::set('database.default', 'mysql');
                foreach ($userApproval as $key => $value) {
                    if($value->email != null){
                        $params['email'] = $value->email;
                        $params['text']  = '<p><strong>Dear Sir/Madam '. $value->name .'</strong>,</p> <p> '. $data->user->name .'  / '.  $data->user->nik .' applied for Cash Advance and currently waiting your approval.</p>';
                        $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                        dispatch($job);
                    }
                }
                Config::set('database.default', $db);
                $params['text'] = '<p> '. $data->user->name .'  / '.  $data->user->nik .' applied for Cash Advance and currently waiting your approval.</p>';
            }

            $userApprovalTokens = user_approval_firebase_tokens($settingApprovalItem);
            info("structure id ".$settingApprovalItem);
            info($userApprovalTokens);
        
            foreach (user_approval_id($settingApprovalItem) as $value) {
                \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $value, $data, 'cash_advance_approval');
            }
    
            if(count($userApprovalTokens) > 0){
                $config = [
                    'title' => "Cash Advance Approval", 
                    'content' => strip_tags($params['text']),
                    'type' => 'cash_advance_approval',
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

            //dd($position, $settingApproval,$settingApprovalItem, $historyApproval);

            return redirect()->route('karyawan.cash-advance.index')->with('message-success', 'Cash Advance successfully processed');
            }
        
    }
    public function edit($id)
    {
        $params['data']         = CashAdvance::where('id', $id)->first();

        if (!IsAccess($params['data'])) {
            return redirect()->route('karyawan.cash-advance.index')->with('message-error', 'You don\'t have permission to perform this action!');
        }

        $params['karyawan']     = User::whereIn('access_id', [1,2])->get();
        $params['form']         = CashAdvanceForm::where('cash_advance_id', $id)->get();

        return view('karyawan.cash-advance.edit')->with($params);
    } 
    
    public function claim($id)
    {
        $params['data']         = CashAdvance::where('id', $id)->first();

        if (!IsAccess($params['data'])) {
            return redirect()->route('karyawan.cash-advance.index')->with('message-error', 'You don\'t have permission to perform this action!');
        }

        $params['karyawan']     = User::whereIn('access_id', [1,2])->get();
        $params['form']         = CashAdvanceForm::where('cash_advance_id', $id)->get();

        return view('karyawan.cash-advance.claim')->with($params);
    }

    public function prosesclaim(Request $request, $id)
    {
        // dd($request);
        $user = Auth::user();
        $data = CashAdvance::where('id', $id)->first();
        $status_claim = $data->status_claim;
        $data->status_claim               = $request->status_claim;
        $data->date_claim                 = date('Y-m-d H:i:s'); 

        foreach($request->cash_advance_form_id as $key => $item)
        {
            $form = \App\Models\CashAdvanceForm::where('id', $request->cash_advance_form_id[$key])->first();
            $actual_amount = $form->actual_amount;
            $form->actual_amount            = preg_replace('/[^0-9]/', '', $request->actual_amount[$key]);
            if($status_claim == NULL && get_setting('period_ca_pr') == 'yes' && $form->sisa_plafond != null && $form->sisa_plafond != $form->plafond){
                $form->sisa_plafond    = $request->sisa_plafond[$key] != NULL && $request->sisa_plafond[$key] >= 0  ? $request->sisa_plafond[$key] - preg_replace('/[^0-9]/', '', $request->actual_amount[$key]) : $form->sisa_plafond + $form->nominal_approved - preg_replace('/[^0-9]/', '', $request->actual_amount[$key]);
            }elseif($status_claim == 4 && get_setting('period_ca_pr') == 'yes' && $form->sisa_plafond != null && $form->sisa_plafond != $form->plafond){
                $form->sisa_plafond    = $request->sisa_plafond[$key] != NULL && $request->sisa_plafond[$key] >= 0  ? $request->sisa_plafond[$key] - preg_replace('/[^0-9]/', '', $request->actual_amount[$key]) : $form->sisa_plafond + $actual_amount - preg_replace('/[^0-9]/', '', $request->actual_amount[$key]);
            }
            //$form->nominal_claimed            = $request->amount_claimed[$key];
            if($request->hasFile('file_struk') && isset($request->file_struk[$key]))
            {
                $image = $request->file_struk[$key];
                $name = md5(rand() . $image->getClientOriginalName() . time()) . "." . $image->getClientOriginalExtension();
                $company_url = session('company_url','umum').'/';
                $destinationPath = public_path('storage/cash-advance/file-struk/').$company_url;
                $image->move($destinationPath, $name);
                $form->file_struk = $company_url.$name;
            }
            $form->save();
        }

        $data->save();

        if ($request->status_claim == 1 || $request->status_claim == 4) {
            HistoryApprovalCashAdvance::where('cash_advance_id',$data->id)->update([
                'approval_id_claim' => null,
                'is_approved_claim' => null,
                'date_approved_claim' => null,
            ]);
        }

        if($request->status_claim==1){
            $historyApprov = HistoryApprovalCashAdvance::where('cash_advance_id',$data->id)->orderBy('setting_approval_level_id','asc')->get();
            if(count($historyApprov)>0) {
                $settingApprovalItem = $historyApprov[0]->structure_organization_custom_id;
                $userApproval = user_approval_custom($settingApprovalItem);
                //$userApproval = getAdminByModule(32);
                $params = getEmailConfig();
                $db = Config::get('database.default', 'mysql');

                $params['data'] = $data;
                $params['total']    = total_cash_advance_actual_amount($data->id);
                $params['value'] = $historyApprov;
                $params['view'] = 'email.cash-advance';
                $params['subject'] = get_setting('mail_name') . ' - Cash Advance';
                if ($userApproval) {
                    Config::set('database.default', 'mysql');
                    foreach ($userApproval as $key => $value) {
                        if ($value->email != null) {
                            $params['email'] = $value->email;
                            $params['text'] = '<p><strong>Dear Sir/Madam ' . $value->name . '</strong>,</p> <p> ' . $data->user->name . '  / ' . $data->user->nik . ' applied for Claim of Cash Advance and currently waiting your approval.</p>';
                            $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                            dispatch($job);
                        }
                    }
                    Config::set('database.default', $db);
                    $params['text'] = '<p> ' . $data->user->name . '  / ' . $data->user->nik . ' applied for Claim of Cash Advance and currently waiting your approval.</p>';
                }
            }

            $userApprovalTokens = user_approval_firebase_tokens($settingApprovalItem);
            info("structure id ".$settingApprovalItem);
            info($userApprovalTokens);
                    
            foreach (user_approval_id($settingApprovalItem) as $value) {
                \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $value, $data, 'claim_cash_advance_approval');
            }
   
            if(count($userApprovalTokens) > 0){
                $config = [
                    'title' => "Claim Cash Advance Approval",
                    'content' => strip_tags($params['text']),
                    'type' => 'claim_cash_advance_approval',
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

            return redirect()->route('karyawan.cash-advance.index')->with('message-success', 'Data successfully saved!');
        }
        else{
            return redirect()->route('karyawan.cash-advance.index')->with('message-success', 'Data successfully saved as draft!');
        }
    }

    public function transfer($id)
    {
        $params['data']         = CashAdvance::where('id', $id)->first();

        if (!IsAccess($params['data'])) {
            return redirect()->route('karyawan.cash-advance.index')->with('message-error', 'You don\'t have permission to perform this action!');
        }

        $params['karyawan']     = User::whereIn('access_id', [1,2])->get();
        $params['form']         = CashAdvanceForm::where('cash_advance_id', $id)->get();

        return view('karyawan.cash-advance.transferClaim')->with($params);
    }

    public function prosesTransfer(Request $request, $id){
        //dd($request);
        $data = CashAdvance::find($id);
        $data->is_transfer_claim = $request->is_transfer_claim;
        $data->is_transfer_claim_by = auth()->user()->id;
        if($request->hasFile('transfer_proof_claim_by_admin'))
        {
            $image = $request->transfer_proof_claim_by_admin;
            $name = md5($id.'transfer_proof_claim_by_user').'.'.$image->getClientOriginalExtension();
            $company_url = session('company_url','umum').'/';
            $destinationPath = public_path('storage/cash-advance/transfer-proof/').$company_url;
            $image->move($destinationPath, $name);
            $data->transfer_proof_claim = $company_url.$name;
        }
        $data->save();

        $userApprovalTokens = [];
        
        $params             = getEmailConfig();
        $params['data']     = $data;
        $params['value']    = $data->historyApproval;
        $params['subject']  = get_setting('mail_name') . ' - Transfer Claim Cash Advance';
        $params['view']     = 'email.cash-advance';
        // $params['text']     = '<p><strong>Dear Sir/Madam '. $data->user->name .'</strong>,</p> <p>  Submission of your Cash Advance Claim has been transfered to Admin.</p>';

        $db = Config::get('database.default','mysql');

        $params['total']    = (total_cash_advance_nominal_approved($data->id) - total_cash_advance_nominal_claimed($data->id));
        $userApproval = TransferSetting::get();

        if($userApproval) {
            Config::set('database.default', 'mysql');
            foreach ($userApproval as $key => $value) {
                if ($value->user->email == "") continue;
                $params['text']     = '<p><strong>Dear Sir/Madam '. $value->user->name .'</strong>,</p> <p> '. $data->user->name .'  / '.  $data->user->nik .' has been sent transfer proof cash advance.</p>';
                $params['email'] = $value->user->email;
                $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                dispatch($job);
                
                $notifTitle = "Transfer Claim Cash Advance";
                $notifType  = "transfer_claim_cash_advance_more";
                if($value->user->firebase_token) {
                    array_push($userApprovalTokens, $value->user->firebase_token);
                    $this->sentNotif($notifTitle, $params['text'], $notifType, $userApprovalTokens, $data->id);
                    $userApprovalTokens = [];
                }
                \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $value->user->id, $data, $notifType);

            }
            Config::set('database.default', $db);
        }
        return redirect()->route('karyawan.cash-advance.index')->with('message-success', 'Transfer Proof Claim Successfully Sent!');
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

    public function getPlafond(Request $request){
        $period_ca_pr = Setting::where('key', 'period_ca_pr')->first();
        $type = PaymentRequestType::where('type', $request->type)->first();
        if(isset($period_ca_pr)){
            if($period_ca_pr->value=='yes'){
                $data = CashAdvanceForm::whereHas('cashAdvance', function($qry){
                    $qry->where('user_id', auth()->user()->id)->where('status', '!=', 3);
                })->where('type_form', $request->type)->where('created_at', '>=', $period_ca_pr->updated_at)->orderBy('id', 'DESC');
                
                if($type->period=='Daily'){
                    $data = $data->whereDate('created_at', Carbon::today());
                }
                elseif($type->period=='Weekly'){
                    $data = $data->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                }elseif($type->period=='Monthly'){
                    $data = $data->whereYear('created_at', '=', Carbon::now()->year)
                                ->whereMonth('created_at', '=', Carbon::now()->month);
                }elseif($type->period=='Yearly'){
                    $data = $data->whereYear('created_at', '=', Carbon::now()->year);
                }
                if($data->first()){
                    return response()->json(['data' => $data->first(), 'period_ca_pr' => 'yes']);
                }
                else{
                    return response()->json(['data' => $type, 'period_ca_pr' => 'yes']);
                }
            }
            else{
                return response()->json(['data' => $type, 'period_ca_pr' => 'no']);
            }
        }
        else{
            return response()->json(['data' => $type, 'period_ca_pr' => 'no']);;
        }
    }
}
