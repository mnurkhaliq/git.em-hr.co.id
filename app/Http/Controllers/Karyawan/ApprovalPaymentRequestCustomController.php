<?php

namespace App\Http\Controllers\Karyawan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PaymentRequest;
use App\Models\HistoryApprovalPaymentRequest;
use App\Models\PaymentRequestForm;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use App\Models\TransferSetting;
use App\Models\StructureOrganizationCustom; 
use App\Models\OrganisasiDivision;
use App\Models\OrganisasiPosition;

class ApprovalPaymentRequestCustomController extends Controller
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
        //
        $user = auth()->user();
        $params['data'] = cek_payment_request_approval();

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
            \Session::put('apr-employee_status', request()->employee_status);
            \Session::put('apr-position_id', request()->position_id);
            \Session::put('apr-division_id', request()->division_id);
            \Session::put('apr-name', request()->name);
        }

        $employee_status    = \Session::get('apr-employee_status');
        $position_id        = \Session::get('apr-position_id');
        $division_id        = \Session::get('apr-division_id');
        $name               = \Session::get('apr-name');

        $cek_transfer_approve = TransferSetting::where('user_id', auth()->user()->id)->first();

        if($cek_transfer_approve != null){
            $data = HistoryApprovalPaymentRequest::join('payment_request','payment_request.id','=','history_approval_payment_request.payment_request_id')
                            ->orderByRaw('IF(structure_organization_custom_id = '.auth()->user()->structure_organization_custom_id.', 0,1)')
                            ->where(function($qry){
                                $qry->where('structure_organization_custom_id', \Auth::user()->structure_organization_custom_id);
                            });
                            
            if(empty($name) && empty($employee_status) && empty($position_id) && empty($division_id)) {
            $data= $data->orWhere(function($qry){
                            $qry->where('payment_method', 'Bank Transfer');
                            });
            }
                            
        }
        else{
            $data= HistoryApprovalPaymentRequest::join('payment_request','payment_request.id','=','history_approval_payment_request.payment_request_id')
                            ->where('structure_organization_custom_id', \Auth::user()->structure_organization_custom_id)
                            ->groupBy('payment_request_id');
        } 

        if(request())
        {
            if (!empty($name)) {
                $data = $data->whereHas('paymentRequest.user', function($qry) use($name){
                        $qry->where('name', 'LIKE', '%' . $name . '%')
                        ->orWhere('nik', 'LIKE', '%' . $name . '%');
                    })
                    ->orWhere('number', 'LIKE', '%' . $name . '%');
            }
            
            if(!empty($employee_status))
            {
                $data = $data->whereHas('paymentRequest.user', function($qry) use($employee_status){
                    $qry->where('organisasi_status', $employee_status);
                });
            }

            if((!empty($division_id)) and (empty($position_id))) 
            {   
                $data = $data->whereHas('paymentRequest.user', function($qry) use($division_id){
                    $qry->join('structure_organization_custom','users.structure_organization_custom_id','=','structure_organization_custom.id')->where('structure_organization_custom.organisasi_division_id',$division_id);
                });
            }

            if((!empty($position_id)) and (empty($division_id)))
            {   
                $data = $data->whereHas('paymentRequest.user', function($qry) use($position_id){
                    $qry->join('structure_organization_custom','users.structure_organization_custom_id','=','structure_organization_custom.id')->where('structure_organization_custom.organisasi_position_id',$position_id);
                });
            }
            if((!empty($position_id)) and (!empty($division_id)))
            {
                $data = $data->whereHas('paymentRequest.user', function($qry) use($position_id, $division_id){
                    $qry->join('structure_organization_custom','users.structure_organization_custom_id','=','structure_organization_custom.id')->where('structure_organization_custom.organisasi_position_id',$position_id)->where('structure_organization_custom.organisasi_division_id',$division_id);
                });
            }
        } 

        if(request()->reset == 1)
        {
            \Session::forget('apr-employee_status');
            \Session::forget('apr-position_id');
            \Session::forget('apr-division_id');
            \Session::forget('apr-name');

            return redirect()->route('karyawan.approval.payment-request-custom.index');
        }

        $params['data'] = $data->orderBy('payment_request_id', 'DESC')->get()->unique('payment_request_id');

        return view('karyawan.approval-payment-request-custom.index')->with($params);
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
        $params['data'] = cek_payment_request_id_approval($id);

        if (!$params['data']) {
            return redirect()->route('karyawan.approval.payment-request-custom.index')->with('message-error', 'You don\'t have permission to perform this action!');
        }

        $params['data'] = PaymentRequest::where('id', $id)->first();
        $params['history'] = HistoryApprovalPaymentRequest::where('payment_request_id',$id)->where('structure_organization_custom_id', \Auth::user()->structure_organization_custom_id)->first();
        
        $params['cek'] = cek_transfer_setting_user($id);
        if (!$params['cek'] && !$params['history']) {
            return redirect()->route('karyawan.approval.payment-request-custom.index')->with('message-error', 'You don\'t have permission to perform this action!');
        }
        else if ($params['cek'] && !$params['history']){
            $params['history'] = HistoryApprovalPaymentRequest::where('payment_request_id',$id)->orderBy('id', 'DESC')->first();
        }

        return view('karyawan.approval-payment-request-custom.detail')->with($params);
    }

    /**
     * [proses description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function proses(Request $request)
    {
        // dd($request);
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
                $i = PaymentRequestForm::where('id', $k)->first();
                // if($i->sisa_plafond + $i->amount - preg_replace('/[^0-9]/', '', $item) < 0){
                //     redirect()->route('karyawan.approval.payment-request-custom.detail', $request->id)->with('message-error', 'Sum of nominal approved per item can not be bigger than available plafond!');
                // }
                if($i)
                {
                    $i->note                = $request->note[$k];
                    $i->nominal_approved    = preg_replace('/[^0-9]/', '', $item);
                    if(get_setting('period_ca_pr') == 'yes' && $i->sisa_plafond != null && $i->sisa_plafond != $i->plafond){
                        $i->sisa_plafond    = $request->sisa_plafond[$k] != NULL && $request->sisa_plafond[$k] >= 0  ? $request->sisa_plafond[$k] - preg_replace('/[^0-9]/', '', $item) : $i->sisa_plafond + $i->amount - preg_replace('/[^0-9]/', '', $item);
                    }
                    // elseif($request->status==0 && get_setting('period_ca_pr') == 'yes'){
                    //     $i->sisa_plafond        = $i->sisa_plafond + $i->amount;
                    // }
                    $i->save();
                }
            }
        }

        $payment_request    = PaymentRequest::find($request->id);
        $params             = getEmailConfig();
        $params['data']     = $payment_request;
        $params['value']    = $payment_request->historyApproval;
        $params['subject']  = get_setting('mail_name') . ' - Payment Request';
        $params['view']     = 'email.payment-request-approval-custom';


        $approval                = HistoryApprovalPaymentRequest::where(['payment_request_id'=>$payment_request->id,'structure_organization_custom_id'=> $user->structure_organization_custom_id])->first();
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
            $payment_request->status = 3;
            $params['total']    = total_payment_request_nominal($payment_request->id);
            $params['text']     = '<p><strong>Dear Sir/Madam '. $payment_request->user->name .'</strong>,</p> <p>  Submission of your Payment Request <strong style="color: red;">REJECTED</strong>.</p>';
            Config::set('database.default', 'mysql');
            if(!empty($payment_request->user->email)) {
                $params['email'] = $payment_request->user->email;
                $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                dispatch($job);
            }
            Config::set('database.default', $db);

            $notifTitle = "Payment Request";
            $notifType  = "payment_request";
            if($payment_request->user->firebase_token) {
                array_push($userApprovalTokens, $payment_request->user->firebase_token);
                $this->sentNotif($notifTitle, $params['text'], $notifType, $userApprovalTokens, $payment_request->id);
                $userApprovalTokens = [];
            }
            \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $payment_request->user->id, $payment_request, $notifType);
        }else if($approval->is_approved == 1){
            $lastApproval = $payment_request->historyApproval->last();
            if($approval->structure_organization_custom_id == $lastApproval->structure_organization_custom_id){
                $params['total']    = total_payment_request_nominal_approved($payment_request->id);
                $params['text']     = '<p><strong>Dear Sir/Madam '. $payment_request->user->name .'</strong>,</p> <p>  Submission of your Payment Request <strong style="color: green;">APPROVED</strong>.</p>';
                $payment_request->status = 2;
                Config::set('database.default', 'mysql');
                if(!empty($payment_request->user->email)) {
                    $params['email'] = $payment_request->user->email;
                    $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                    dispatch($job);
                }
                Config::set('database.default', $db);

                $notifTitle = "Payment Request";
                $notifType  = "payment_request";
                if($payment_request->user->firebase_token) {
                    array_push($userApprovalTokens, $payment_request->user->firebase_token);
                    $this->sentNotif($notifTitle, $params['text'], $notifType, $userApprovalTokens, $payment_request->id);
                    $userApprovalTokens = [];
                }
                \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $payment_request->user->id, $payment_request, $notifType);
                $userApproval = TransferSetting::get();
                if($userApproval && $payment_request->payment_method=='Bank Transfer') {
                    Config::set('database.default', 'mysql');
                    foreach ($userApproval as $key => $value) {
                        if ($value->user->email == "") continue;
                        $params['text']     = '<p><strong>Dear Sir/Madam '. $value->user->name .'</strong>,</p> <p> '. $payment_request->user->name .'  / '.  $payment_request->user->nik .' applied for Payment Request and currently waiting your payment.</p>';
                        $params['email'] = $value->user->email;
                        $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                        dispatch($job);

                        $notifTitle = "Transfer Payment Request";
                        $notifType  = "transfer_payment_request_approve";
                        if($value->user->firebase_token) {
                            array_push($userApprovalTokens, $value->user->firebase_token);
                            $this->sentNotif($notifTitle, $params['text'], $notifType, $userApprovalTokens, $payment_request->id);
                            $userApprovalTokens = [];
                        }
                        \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $value->user->id, $payment_request, $notifType);
                    }
                    Config::set('database.default', $db);
                }

            }else{
                $payment_request->status = 1;
                $nextApproval = HistoryApprovalPaymentRequest::where(['payment_request_id'=>$payment_request->id,'setting_approval_level_id'=> ($approval->setting_approval_level_id+1)])->first();
                if($nextApproval){
                    $userApproval = user_approval_custom($nextApproval->structure_organization_custom_id);
                    $params['total']    = total_payment_request_nominal($payment_request->id);

                    if($userApproval) {
                        Config::set('database.default', 'mysql');
                        foreach ($userApproval as $key => $value) {
                            if (empty($value->email)) continue;
                            $params['text']     = '<p><strong>Dear Sir/Madam '. $value->name .'</strong>,</p> <p> '. $payment_request->user->name .'  / '.  $payment_request->user->nik .' applied for Payment Request and currently waiting your approval.</p>';
                            $params['email'] = $value->email;
                            $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                            dispatch($job);
                        }
                        Config::set('database.default', $db);

                        $params['text'] = '<p> '. $payment_request->user->name .'  / '.  $payment_request->user->nik .' applied for Payment Request and currently waiting your approval.</p>';
                        $notifTitle = "Payment Request Approval";
                        $notifType  = "approval_payment_request";
                        $userApprovalTokens = user_approval_firebase_tokens($nextApproval->structure_organization_custom_id);
                        $userApprovalIds = user_approval_id($nextApproval->structure_organization_custom_id);
                        $this->sentNotif($notifTitle, $params['text'], $notifType, $userApprovalTokens, $payment_request->id);
                        $userApprovalTokens = [];
                    }
                }
            }
        }
        $payment_request->save();

        foreach ($userApprovalIds as $value) {
            \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $value, $payment_request, $notifType);
        }

        return redirect()->route('karyawan.approval.payment-request-custom.index')->with('message-success', 'Form Payment Request Successfully Processed !');
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

    public function detailTransfer($id)
    {   
        $params['data'] = cek_transfer_setting_user($id);

        if (!$params['data']) {
            return redirect()->route('karyawan.approval.payment-request-custom.index')->with('message-error', 'You don\'t have permission to perform this action!');
        }

        $params['data'] = PaymentRequest::where('id', $id)->first();
        $params['history'] = HistoryApprovalPaymentRequest::where('payment_request_id',$id)->get();
        $params['form'] = PaymentRequestForm::where('payment_request_id', $id)->get();

        return view('karyawan.approval-payment-request-custom.transfer')->with($params);
    }


    public function transfer(Request $request, $id){
        //dd($request);
        $data = PaymentRequest::find($id);
        $data->is_transfer= $request->is_transfer;
        $data->disbursement = $request->disbursement;
        $data->is_transfer_by = auth()->user()->id;
        if($request->hasFile('transfer_proof_by_admin'))
        {
            $image = $request->transfer_proof_by_admin;
            $name = md5($id.'transfer_proof').'.'.$image->getClientOriginalExtension();
            $company_url = session('company_url','umum').'/';
            $destinationPath = public_path('storage/payment-request/transfer-proof/').$company_url;
            $image->move($destinationPath, $name);
            $data->transfer_proof = $company_url.$name;
        }
        $data->save();

        $userApprovalTokens = [];

        $db = Config::get('database.default','mysql');
        $params             = getEmailConfig();
        $params['data']     = $data;
        $params['value']    = $data->historyApproval;
        $params['subject']  = get_setting('mail_name') . ' - Transfer Payment Request';
        $params['view']     = 'email.payment-request-approval-custom';
        $params['total']    = total_payment_request_nominal_approved($data->id);
        if($request->disbursement=='Transfer'){
            $params['text']     = '<p><strong>Dear Sir/Madam '. $data->user->name .'</strong>,</p> <p>  Submission of your payment request has been transfered.</p>';
        }else{
            $params['text']     = '<p><strong>Dear Sir/Madam '. $data->user->name .'</strong>,</p> <p>  Submission of your payment request will be merged with the next payroll.</p>';
        }

        Config::set('database.default', 'mysql');

        if(!empty($data->user->email)) {
            $params['email'] = $data->user->email;
            $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
            dispatch($job);
        }
        Config::set('database.default', $db);
        
        $notifTitle = "Payment Request";
        $notifType  = "payment_request";

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

        return redirect()->route('karyawan.approval.payment-request-custom.index')->with('message-success', 'Transfer Proof Successfully Sent!');
    }

}
