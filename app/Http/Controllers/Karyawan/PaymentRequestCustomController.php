<?php

namespace App\Http\Controllers\Karyawan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PaymentRequest;
use App\Models\PaymentRequestForm;
use App\User;
use Carbon\Carbon;
use App\Models\PaymentRequestOvertime;
use App\Models\OvertimeSheet;
use App\Models\PaymentRequestBensin;
use App\Models\SettingApprovalPaymentRequestItem;
use App\Models\SettingApproval;
use App\Models\Setting;
use App\Models\PaymentRequestType;
use App\Models\HistoryApprovalPaymentRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class PaymentRequestCustomController extends Controller
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
        $params['data'] = PaymentRequest::where('user_id', \Auth::user()->id)->orderBy('id', 'DESC')->get();
        $params['type'] = PaymentRequestType::get();
        $params['data_waiting'] = PaymentRequest::where('user_id', \Auth::user()->id)
                                ->where(function($query) {
                                    $query->where('status', '1')->orWhere('status', '4');
                                })->count();
        
        return view('karyawan.payment-request-custom.index')->with($params);
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
            return redirect()->route('karyawan.payment-request-custom.index')->with('message-error', 'Your position is not defined yet. Please contact your admin!');
        }else{
            if(count($checkApproval->itemsPaymentRequest) == 0){
                return redirect()->route('karyawan.payment-request-custom.index')->with('message-error', 'Setting approval not define yet. Please contact your admin !');
            }
        }
        $params['karyawan'] = User::whereIn('access_id', [1,2])->get();
        $params['type'] = PaymentRequestType::get();
        \Session::forget('id-pr');
        return view('karyawan.payment-request-custom.create')->with($params);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request);
        $user = Auth::user();
        $checkApproval = \Auth::user()->approval;

        if($checkApproval == null)
        {
            return redirect()->route('karyawan.payment-request-custom.index')->with('message-error', 'Your position is not defined yet. Please contact your admin!');
        }else{
            if(count($checkApproval->itemsPaymentRequest) == 0){
                return redirect()->route('karyawan.payment-request-custom.index')->with('message-error', 'Setting approval not define yet. Please contact your admin !');
            }
            if($checkApproval)
            $data                       = new PaymentRequest();
            $data->user_id              = \Auth::user()->id;
            $data->transaction_type     = $request->transaction_type;
            $data->payment_method       = $request->payment_method;
            $data->tujuan               = $request->tujuan;
            $data->status               = $request->status;
            $data->is_proposal_approved = 0;
            if($request->payment_method=='Bank Transfer'){
                $data->is_transfer = 0;
            }
            $data->is_proposal_verification_approved = 0;
            $data->is_payment_approved  = 0;
            $data->number = 'PR-' . Carbon::now()->format('dmY') . '/' . \Auth::user()->nik . '-' . (PaymentRequest::where('user_id', \Auth::user()->id)->count() + 1);
            $data->save();

            // jika ada overtime
            if(isset($request->overtime))
            {
                foreach($request->overtime as $k => $i)
                {
                    $form                       = new PaymentRequestOvertime();
                    $form->payment_request_id   = $data->id;
                    $form->overtime_sheet_id    = $i;
                    $form->save();

                    $ov                         = OvertimeSheet::where('id', $i)->first();
                    $ov->is_payment_request     = 1;
                    $ov->save();
                }
            }
            $gasolineIds = [];
            foreach($request->description as $key => $item)
            {
                $form = new PaymentRequestForm();
                $form->payment_request_id   = $data->id;
                $form->description          = $item;
                $form->type_form            = $request->type[$key];
                // $form->quantity             = $request->quantity[$key];
                // $form->estimation_cost      = $request->estimation_cost[$key];
                $form->amount               = $request->amount[$key] != null ? preg_replace('/[^0-9]/', '', $request->amount[$key]) : NULL;
                $form->plafond              = $request->plafond[$key] != null ? preg_replace('/[^0-9]/', '', $request->plafond[$key]) : NULL;
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
                            $destinationPath = public_path('storage/file-struk/').$company_url;
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
                    $bensin                          = new PaymentRequestBensin();
                    $bensin->payment_request_id      = $data->id;
                    $bensin->payment_request_form_id = isset($gasolineIds[$k])?$gasolineIds[$k]:null;
                    $bensin->user_id                 = \Auth::user()->id;
                     $bensin->tanggal                = Carbon::parse($request->bensin['tanggal'][$k])->format('d.m.y');
                    //$bensin->tanggal            = $request->bensin['tanggal'][$k];
                    $bensin->odo_start               = $request->bensin['odo_from'][$k];
                    $bensin->odo_end                 = $request->bensin['odo_to'][$k];
                    $bensin->liter                   = $request->bensin['liter'][$k];
                    $bensin->cost                    = $request->bensin['cost'][$k] != null ? preg_replace('/[^0-9]/', '', $request->bensin['cost'][$k]) : NULL;
                    $bensin->save();
                }
            }

            if($request->status == 1){
                $historyApproval     = $user->approval->itemsPaymentRequest;
                $settingApprovalItem = $historyApproval[0]->structure_organization_custom_id;
                foreach ($historyApproval as $level => $value) {
                    # code...
                    $history = new HistoryApprovalPaymentRequest();
                    $history->payment_request_id               = $data->id;
                    $history->setting_approval_level_id        = ($level+1);
                    $history->structure_organization_custom_id = $value->structure_organization_custom_id;
                    $history->save();
                }
                $historyApprov = HistoryApprovalPaymentRequest::where('payment_request_id',$data->id)->orderBy('setting_approval_level_id','asc')->get();

                $userApproval = user_approval_custom($settingApprovalItem);
                $db = Config::get('database.default','mysql');

                $params = getEmailConfig();
                $params['data']     = $data;
                $params['total']    = total_payment_request_nominal($data->id);
                $params['value']    = $historyApprov;
                $params['view']     = 'email.payment-request-approval-custom';
                $params['subject']  = get_setting('mail_name') . ' - Payment Request';
                if($userApproval) {
                    Config::set('database.default', 'mysql');
                    foreach ($userApproval as $key => $value) {
                        if (empty($value->email)) continue;
                        $params['email'] = $value->email;
                        $params['text']  = '<p><strong>Dear Sir/Madam '. $value->name .'</strong>,</p> <p> '. $data->user->name .'  / '.  $data->user->nik .' applied for Payment Request and currently waiting your approval.</p>';
                        $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                        dispatch($job);
                    }
                    Config::set('database.default', $db);
                    $params['text'] = '<p> '. $data->user->name .'  / '.  $data->user->nik .' applied for Payment Request and currently waiting your approval.</p>';
                }

                $userApprovalTokens = user_approval_firebase_tokens($settingApprovalItem);
                info("structure id ".$settingApprovalItem);
                info($userApprovalTokens);

                foreach (user_approval_id($settingApprovalItem) as $value) {
                    \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $value, $data, 'approval_payment_request');
                }
        
                if(count($userApprovalTokens) > 0){
                    $config = [
                        'title' => "Payment Request Approval",
                        'content' => strip_tags($params['text']),
                        'type' => 'approval_payment_request',
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

                return redirect()->route('karyawan.payment-request-custom.index')->with('message-success', 'Payment Request successfully processed');
            }
            else{
                return redirect()->route('karyawan.payment-request-custom.index')->with('message-success', 'Payment Request successfully save to draft');
            }
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
        $params['data']         = PaymentRequest::where('id', $id)->first();

        if (!IsAccess($params['data'])) {
            return redirect()->route('karyawan.payment-request-custom.index')->with('message-error', 'You don\'t have permission to perform this action!');
        }

        $params['karyawan']     = User::whereIn('access_id', [1,2])->get();
        $params['form']         = PaymentRequestForm::where('payment_request_id', $id)->get();
        $params['type'] = PaymentRequestType::get();

        \Session::put('id-pr', $params['data']->id);

        return view('karyawan.payment-request-custom.edit')->with($params);
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
        // dd($request);
        $user = Auth::user();
        $checkApproval = \Auth::user()->approval;

        if($checkApproval == null)
        {
            return redirect()->route('karyawan.payment-request-custom.index')->with('message-error', 'Your position is not defined yet. Please contact your admin!');
        }else{
            if(count($checkApproval->itemsPaymentRequest) == 0){
                return redirect()->route('karyawan.payment-request-custom.index')->with('message-error', 'Setting approval not define yet. Please contact your admin !');
            }
            if($checkApproval)
            $data                       = PaymentRequest::find($id);
            $data->user_id              = \Auth::user()->id;
            $data->transaction_type     = $request->transaction_type;
            $data->payment_method       = $request->payment_method;
            $data->tujuan               = $request->tujuan;
            $data->status               = $request->status;
            $data->is_proposal_approved = 0;
            if($request->payment_method=='Bank Transfer'){
                $data->is_transfer = 0;
            }
            $data->is_proposal_verification_approved = 0;
            $data->is_payment_approved  = 0;
            $data->created_at           = Carbon::today();
            $data->save();

            $temp_form = PaymentRequestForm::where('payment_request_id', $id)->get();
            $former_form = PaymentRequestForm::where('payment_request_id', $id)->delete();
            $bensin_form = PaymentRequestBensin::where('payment_request_id', $id)->delete();
            $overtime_form =  PaymentRequestOvertime::where('payment_request_id', $id)->delete();
            $gasolineIds = [];
            foreach($request->description as $key => $item)
            {
                $form = new PaymentRequestForm();
                $form->payment_request_id   = $data->id;
                $form->description          = $item;
                $form->type_form            = $request->type[$key];
                // $form->quantity             = $request->quantity[$key];
                // $form->estimation_cost      = $request->estimation_cost[$key];
                $form->amount               = $request->amount[$key] != null ? preg_replace('/[^0-9]/', '', $request->amount[$key]) : NULL;;
                $form->plafond              = $request->plafond[$key] != null ? preg_replace('/[^0-9]/', '', $request->plafond[$key]) : NULL;
                if(get_setting('period_ca_pr') == 'yes'){
                    $form->sisa_plafond         = $request->amount[$key] != null && $request->sisa_plafond[$key] > 0 ? (int) preg_replace('/[^0-9]/', '', $request->sisa_plafond[$key]) - (int) preg_replace('/[^0-9]/', '', $request->amount[$key]) : NULL;
                }
                else{
                    $form->sisa_plafond              = $request->plafond[$key] != null ? preg_replace('/[^0-9]/', '', $request->plafond[$key]) : NULL;
                }

                if(isset($request->file_struk[$key]))
                {
                    $file = $request->file_struk[$key];
                    $fname = md5(rand() . $file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                    $company_url = session('company_url','umum').'/';
                    $destinationPath = public_path('/storage/file-struk/').$company_url;
                    $file->move($destinationPath, $fname);
                    $form->file_struk = $company_url.$fname;
                } else if (isset($request->idForm[$key])) {
                    $form->file_struk = ($temp = $temp_form->where('id', $request->idForm[$key])->first()) ? $temp->file_struk : $temp;
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
                    $bensin                          = new PaymentRequestBensin();
                    $bensin->payment_request_id      = $data->id;
                    $bensin->payment_request_form_id = isset($gasolineIds[$k])?$gasolineIds[$k]:null;
                    $bensin->user_id                 = \Auth::user()->id;
                     $bensin->tanggal                = Carbon::parse($request->bensin['tanggal'][$k])->format('d.m.y');
                    //$bensin->tanggal            = $request->bensin['tanggal'][$k];
                    $bensin->odo_start               = $request->bensin['odo_from'][$k];
                    $bensin->odo_end                 = $request->bensin['odo_to'][$k];
                    $bensin->liter                   = $request->bensin['liter'][$k];
                    $bensin->cost                    = $request->bensin['cost'][$k] != null ? preg_replace('/[^0-9]/', '', $request->bensin['cost'][$k]) : NULL;
                    $bensin->save();
                }
            }

            // jika ada overtime
            if(isset($request->overtime))
            {
                foreach($request->overtime as $k => $i)
                {
                    $form                       = new PaymentRequestOvertime();
                    $form->payment_request_id   = $data->id;
                    $form->overtime_sheet_id    = $i;
                    $form->save();

                    $ov                         = OvertimeSheet::where('id', $i)->first();
                    $ov->is_payment_request     = 1;
                    $ov->save();
                }
            }

            if($request->status == 1){
                $historyApproval     = $user->approval->itemsPaymentRequest;
                $settingApprovalItem = $historyApproval[0]->structure_organization_custom_id;
                foreach ($historyApproval as $level => $value) {
                    # code...
                    $history = new HistoryApprovalPaymentRequest();
                    $history->payment_request_id               = $data->id;
                    $history->setting_approval_level_id        = ($level+1);
                    $history->structure_organization_custom_id = $value->structure_organization_custom_id;
                    $history->save();
                }
                $historyApprov = HistoryApprovalPaymentRequest::where('payment_request_id',$data->id)->orderBy('setting_approval_level_id','asc')->get();

                $userApproval = user_approval_custom($settingApprovalItem);
                $db = Config::get('database.default','mysql');

                $params = getEmailConfig();
                $params['data']     = $data;
                $params['total']    = total_payment_request_nominal($data->id);
                $params['value']    = $historyApprov;
                $params['view']     = 'email.payment-request-approval-custom';
                $params['subject']  = get_setting('mail_name') . ' - Payment Request';
                if($userApproval) {
                    Config::set('database.default', 'mysql');
                    foreach ($userApproval as $key => $value) {
                        if (empty($value->email)) continue;
                        $params['email'] = $value->email;
                        $params['text']  = '<p><strong>Dear Sir/Madam '. $value->name .'</strong>,</p> <p> '. $data->user->name .'  / '.  $data->user->nik .' applied for Payment Request and currently waiting your approval.</p>';
                        $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                        dispatch($job);
                    }
                    Config::set('database.default', $db);
                    $params['text'] = '<p> '. $data->user->name .'  / '.  $data->user->nik .' applied for Payment Request and currently waiting your approval.</p>';
                }

                $userApprovalTokens = user_approval_firebase_tokens($settingApprovalItem);
                info("structure id ".$settingApprovalItem);
                info($userApprovalTokens);

                foreach (user_approval_id($settingApprovalItem) as $value) {
                    \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $value, $data, 'approval_payment_request');
                }
  
                if(count($userApprovalTokens) > 0){
                    $config = [
                        'title' => "Payment Request Approval",
                        'content' => strip_tags($params['text']),
                        'type' => 'approval_payment_request',
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

                return redirect()->route('karyawan.payment-request-custom.index')->with('message-success', 'Payment Request successfully processed');
            }
            else{
                return redirect()->route('karyawan.payment-request-custom.index')->with('message-success', 'Payment Request successfully save to draft');
            }
        }
        
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

    public function getPlafond(Request $request){
        $period_ca_pr = Setting::where('key', 'period_ca_pr')->first();
        $type = PaymentRequestType::where('type', $request->type)->first();
        if(isset($period_ca_pr)){
            if($period_ca_pr->value=='yes'){
                $data = PaymentRequestForm::whereHas('paymentRequest', function($qry){
                    $qry->where('user_id', auth()->user()->id)->where('status', '!=', 3);
                })->where('type_form', $request->type)->where('created_at', '>=', $period_ca_pr->updated_at)->orderBy('id', 'DESC');
                
                $id               = \Session::get('id-pr');
                if($id){
                    $data = $data->where('payment_request_id', '!=', $id);
                }
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
        dd($request);
    }
    
}
