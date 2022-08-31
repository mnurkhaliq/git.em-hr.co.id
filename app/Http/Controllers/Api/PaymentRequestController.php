<?php

namespace App\Http\Controllers\Api;

use App\Http\Middleware\ConnectionMiddleware;
use App\Http\Resources\PaymentRequestResource;
use App\Models\HistoryApprovalPaymentRequest;
use App\Models\PaymentRequest;
use App\Models\PaymentRequestBensin;
use App\Models\PaymentRequestForm;
use App\Models\PaymentRequestOvertime;
use App\Models\TransferSetting;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\PaymentRequestType;

class PaymentRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware(ConnectionMiddleware::class);
        $this->middleware('jwt.auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $user = Auth::user();
        $status = $request->input('status','[1,2,3,4]');
        if(!isJsonValid($status))
            return response()->json(['status' => 'error','message'=>'Status is invalid'], 404);
        $status = json_decode($status);
        $histories = PaymentRequest::where(['user_id'=>$user->id])->whereIn('status',$status)->orderBy('created_at','DESC');
        $totalData = $histories->get()->count();
        $histories = $histories->paginate(10);
        $data = [
            'current_page' => $histories->currentPage(), // get current page number
            'total_page' => $histories->total() ? $histories->lastPage() : $histories->total(), // get last page number
            'total_data' => $totalData,
            'payment_requests' => PaymentRequestResource::collection($histories),
            'is_period_active' => get_setting('period_ca_pr') == 'yes' ? true : false,
        ];

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Successfully',
                'data' => $data
            ], 200);
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
        $approval = $user->approval;
        if($approval == null){
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Your position is not defined yet. Please contact your admin!'
                ], 403);
        }else if(count($approval->itemsPaymentRequest) == 0){
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Setting approval is not defined yet. Please contact your admin!'
                ], 403);
        }
        $status = $request->status ? $request->status : 1;
        if($status==1){
            $validator = Validator::make($request->all(), [
                'purpose'                     => "required",
                'payment_method'              => "required|in:Cash,Bank Transfer",
                "details"                     => "required|array",
                'details.*.description'       => 'required',
                // 'details.*.quantity'          => "required|integer",
                'details.*.type_form'         => "required",
                'details.*.amount'            => "required|integer",
                // 'files.*'                     => 'required|mimes:jpg,jpeg,bmp,png,gif,svg,pdf',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()],401);
            }
        }

        $data                       = new PaymentRequest();
        $data->user_id              = $user->id;
        $data->tujuan               = $request->purpose;
        $data->payment_method       = $request->payment_method;
        $data->status               = $status;
        if($request->payment_method=='Bank Transfer'){
            $data->is_transfer = 0;
        }
        $data->number = 'PR-' . Carbon::now()->format('dmY') . '/' . \Auth::user()->nik . '-' . (PaymentRequest::where('user_id', \Auth::user()->id)->count() + 1);
        $data->save();

        if($request->details){
            foreach ($request->details as $no => $detail){
                $form                           = new PaymentRequestForm();
                $form->payment_request_id       = $data->id;
                $form->description              = $detail['description'];
                // $form->quantity                 = $detail['quantity'];
                $form->amount                   = $detail['amount'];
                $form->plafond                   = isset($detail['plafond']) ? $detail['plafond'] : NULL;
                $form->type_form                = $detail['type_form'];
                if(get_setting('period_ca_pr') == 'yes'){
                    $form->sisa_plafond         = $detail['amount'] && isset($detail['sisa_plafond']) != null  ? (int) $detail['sisa_plafond'] - (int) $detail['amount'] : NULL;
                }
                else{
                    $form->sisa_plafond         = isset($detail['plafond']) ? $detail['plafond']  : NULL;
                }

                $files = $request->file('files');
                if ($request->hasFile('files'))
                {
                    foreach($files as $k => $file) {
                        if ($k == $no) {
                            $fname = md5(rand() . $file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                            $company_url = ($request->company ? $request->company : "umum") . '/';
                            $destinationPath = public_path('/storage/file-struk/') . $company_url;
                            $file->move($destinationPath, $fname);
                            $form->file_struk = $company_url . $fname;
                        }
                    }
                }
                $form->save();
                if(strtolower($form->type_form) == 'gasoline' && isset($detail['gasoline'])){
                    $bensin                          = new PaymentRequestBensin();
                    $bensin->payment_request_id      = $data->id;
                    $bensin->payment_request_form_id = $form->id;
                    $bensin->user_id                 = $user->id;
                    $bensin->tanggal                 = $detail['gasoline']['tanggal'];
                    $bensin->odo_start               = $detail['gasoline']['odo_start'];
                    $bensin->odo_end                 = $detail['gasoline']['odo_end'];
                    $bensin->liter                   = $detail['gasoline']['liter'];
                    $bensin->cost                    = $detail['gasoline']['cost'];
                    $bensin->save();
                }
            }
        }

        if($status==1){

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
                \FRDHelper::setNewData(strtolower($request->company), $value, $data, 'approval_payment_request');
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
        }

        return response()->json(
            [
                'status' => 'success',
                'message' => $status== 1 ? 'Your payment request has successfully submitted' : 'Your payment request has successfully save draft'
            ], 201);
    }

    public function getParams(Request $request)
    {
        //
        $user = Auth::user();
        if(!$user)
            return response()->json(['status' => 'error', 'message' => 'User is not found!'], 404);
        if($request->type == 'create'){
            $approval = $user->approval;
            if($approval == null){
                return response()->json(['status' => 'error','message' => 'Your position is not defined yet. Please contact your admin!'], 403);
            }else if(count($approval->itemsPaymentRequest) == 0){
                return response()->json(['status' => 'error', 'message' => 'Setting approval is not defined yet. Please contact your admin!'], 403);
            }
        }
        $data['data_waiting'] = PaymentRequest::where('user_id', \Auth::user()->id)
                                ->where(function($query) {
                                    $query->where('status', '1')->orWhere('status', '4');
                                })->count();
        if($request->type == 'create' && get_setting('period_ca_pr') == 'yes' && $data['data_waiting'] > 0){
            return response()->json(['status' => 'error', 'message' => 'Sorry you can not apply this transaction before the previous transaction has been completely approved.'], 403);
        }

        $data['payment_request_type'] = [];

        if ($user->os_type == 'ios' && (int) str_replace('.', '', $user->app_version) <= 141) {
            $data['payment_request_type'] = [
                ['id'=>'Parking','name'=>'Parking'],
                ['id'=>'Gasoline','name'=>'Gasoline'],
                ['id'=>'Toll','name'=>'Toll'],
                ['id'=>'Transportation','name'=>'Transportation'],
                ['id'=>'Others','name'=>'Others']
            ];
        } else {
            $type = PaymentRequestType::get();
            if(count($type) > 0){
                foreach($type as $item){
                    array_push($data['payment_request_type'],  
                        ['id'=>$item->type,'name'=>$item->type,'plafond'=>$item->plafond,'sisa_plafond' => get_available_plafond($item->type), 'period' => $item->period]
                    );
                }
            }
        }
        $data['is_period_active'] = get_setting('period_ca_pr') == 'yes' ? true : false;
        return response()->json(
            [
                'status' => 'success',
                'message' => 'Successfully',
                'data' => $data,
            ], 200);
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
        $user = Auth::user();
        $data['payment_request'] = new PaymentRequestResource(PaymentRequest::findOrFail($id));
        $data['is_period_active'] = get_setting('period_ca_pr') == 'yes' ? true : false;
        return response()->json(
            [
                'status' => 'success',
                'message' => 'Successfully',
                'data' => $data
            ], 200);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
        $user = Auth::user();
        $approval = $user->approval;
        if($approval == null){
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Your position is not defined yet. Please contact your admin!'
                ], 403);
        }else if(count($approval->itemsPaymentRequest) == 0){
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Setting approval is not defined yet. Please contact your admin!'
                ], 403);
        }
        $validator = Validator::make($request->all(), [
            'payment_request.id'                  => 'required|exists:payment_request,id',
        ]);

        if($request->status==1){
            
            $validator = Validator::make($request->all(), [
                'purpose'                     => "required",
                'payment_method'              => "required|in:Cash,Bank Transfer",
                "details"                     => "required|array",
                'details.*.description'       => 'required',
                // 'details.*.quantity'          => "required|integer",
                'details.*.type_form'         => "required",
                'details.*.amount'            => "required|integer",
                // 'files.*'                     => 'required|mimes:jpg,jpeg,bmp,png,gif,svg,pdf',
            ]);    
        }

        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()],401);
        }

        $data                       = PaymentRequest::find($request->payment_request['id']);
        $data->user_id              = $user->id;
        $data->tujuan               = $request->purpose;
        $data->payment_method       = $request->payment_method;
        $data->status               = $request->status;
        if($request->payment_method=='Bank Transfer'){
            $data->is_transfer = 0;
        }
        $data->save();

        $temp_form = PaymentRequestForm::where('payment_request_id', $request->payment_request['id'])->get();
        $former_form = PaymentRequestForm::where('payment_request_id', $request->payment_request['id'])->delete();
        $bensin_form = PaymentRequestBensin::where('payment_request_id', $request->payment_request['id'])->delete();
        $overtime_form =  PaymentRequestOvertime::where('payment_request_id', $request->payment_request['id'])->delete();

        if($request->details){
            foreach ($request->details as $no => $detail){
                $form                           = new PaymentRequestForm();
                $form->payment_request_id       = $data->id;
                $form->description              = $detail['description'];
                // $form->quantity                 = $detail['quantity'];
                $form->amount                   = $detail['amount'];
                $form->plafond                   = isset($detail['plafond']) ? $detail['plafond'] : NULL;
                $form->type_form                = $detail['type_form'];
                if(get_setting('period_ca_pr') == 'yes'){
                    $form->sisa_plafond         = $detail['amount'] && isset($detail['sisa_plafond']) != null  ? (int) $detail['sisa_plafond'] - (int) $detail['amount'] : NULL;
                }
                else{
                    $form->sisa_plafond         = isset($detail['plafond']) ? $detail['plafond']  : NULL;
                }

                // $files = $request->file('files');
                // if ($request->hasFile('files'))
                // {
                //     foreach($files as $k => $file) {
                //         if ($k == $no) {
                //             $fname = md5(rand() . $file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                //             $company_url = ($request->company ? $request->company : "umum") . '/';
                //             $destinationPath = public_path('/storage/file-struk/') . $company_url;
                //             $file->move($destinationPath, $fname);
                //             $form->file_struk = $company_url . $fname;
                //         }
                //     }
                // }

                if ($request->file('files') && isset($request->file('files')[$no])) {
                    $image = $request->file('files')[$no];
                    $name = md5(rand() . $image->getClientOriginalName() . time()) . "." . $image->getClientOriginalExtension();
                    $company_url = ($request->company ? $request->company : "umum") . '/';
                    $destinationPath = public_path('storage/file-struk/') . $company_url;
                    $image->move($destinationPath, $name);
                    $form->file_struk = $company_url . $name;
                } else if (isset($request->all()['files'][$no])) {
                    $form->file_struk = $request->all()['files'][$no];
                }

                $form->save();
                if(strtolower($form->type_form) == 'gasoline' && isset($detail['gasoline'])){
                    $bensin                          = new PaymentRequestBensin();
                    $bensin->payment_request_id      = $data->id;
                    $bensin->payment_request_form_id = $form->id;
                    $bensin->user_id                 = $user->id;
                    $bensin->tanggal                 = $detail['gasoline']['tanggal'];
                    $bensin->odo_start               = $detail['gasoline']['odo_start'];
                    $bensin->odo_end                 = $detail['gasoline']['odo_end'];
                    $bensin->liter                   = $detail['gasoline']['liter'];
                    $bensin->cost                    = $detail['gasoline']['cost'];
                    $bensin->save();
                }
            }
        }

        if($request->status==1){

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
                \FRDHelper::setNewData(strtolower($request->company), $value, $data, 'approval_payment_request');
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
        }

        return response()->json(
            [
                'status' => 'success',
                'message' => $request->status== 1 ? 'Your payment request has successfully submitted' : 'Your payment request has successfully save draft'
            ], 201);
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

    public function getApproval(Request $request){
        $status = $request->status?$request->status:"all";
        $user = Auth::user();
        $approval = null;
        $cek_transfer_approve = TransferSetting::where('user_id', auth()->user()->id)->first();

        if($status == 'ongoing') {
            $approval = PaymentRequest::join('history_approval_payment_request as h', function ($join) use ($user) {
                $join->on('payment_request.id', '=', 'h.payment_request_id')
                    ->where('h.setting_approval_level_id', '=', DB::raw('(select min(setting_approval_level_id) from history_approval_payment_request where payment_request_id = payment_request.id and is_approved is null)'))
                    ->where('h.structure_organization_custom_id', '=', $user->structure_organization_custom_id);
            })
                ->where('payment_request.status', '=', 1)
                ->orderBy('created_at','DESC')
                ->select('payment_request.*');

                if($approval->count() == 0 && $cek_transfer_approve!=null){
                    $approval = PaymentRequest::join('history_approval_payment_request as h', function ($join) use ($user) {
                        $join->on('payment_request.id', '=', 'h.payment_request_id')
                        ->whereNotNull('h.is_approved');
                    })
                        ->where('payment_request.status', '=',2)
                        ->where('payment_request.is_transfer', '!=',1)
                        ->where('payment_request.payment_method', 'Bank Transfer')
                        ->orderBy('created_at','DESC')
                        ->groupBy('payment_request.id')
                        ->select('payment_request.*');
                }
                else if($approval->count() > 0 && $cek_transfer_approve!=null){
                    $transfer = PaymentRequest::join('history_approval_payment_request as h', function ($join) use ($user) {
                        $join->on('payment_request.id', '=', 'h.payment_request_id')
                        ->whereNotNull('h.is_approved');
                    })
                        ->where('payment_request.status', '=',2)
                        ->where('payment_request.is_transfer', '!=',1)
                        ->where('payment_request.payment_method', 'Bank Transfer')
                        ->orderBy('created_at','DESC')
                        ->groupBy('payment_request.id')
                        ->select('payment_request.*');
                    
                    $approval = $transfer->get()->merge($approval->get());
                    $approvalId = $approval->pluck('id');

                    $approval = PaymentRequest::join('history_approval_payment_request as h', function ($join) use ($user) {
                        $join->on('payment_request.id', '=', 'h.payment_request_id');
                    })
                        ->whereIn('payment_request.id', $approvalId)
                        ->orderBy('created_at','DESC')
                        ->groupBy('payment_request.id')
                        ->select('payment_request.*');

                }
            
        }else if($status == 'history'){
            $approval = PaymentRequest::join('history_approval_payment_request as h', function ($join) use ($user) {
                $join->on('payment_request.id', '=', 'h.payment_request_id')
                    ->whereNotNull('h.is_approved')
                    ->where('h.structure_organization_custom_id', '=', $user->structure_organization_custom_id);
            })
                ->orderBy('created_at','DESC')
                ->select('payment_request.*');

                if($approval->count() == 0 && $cek_transfer_approve!=null){
                    $approval = PaymentRequest::join('history_approval_payment_request as h', function ($join) use ($user) {
                        $join->on('payment_request.id', '=', 'h.payment_request_id')
                        ->whereNotNull('h.is_approved');
                    })
                        ->where('payment_request.is_transfer', '=',1)
                        ->where('payment_request.payment_method', 'Bank Transfer')
                        ->orderBy('created_at','DESC')
                        ->groupBy('payment_request.id')
                        ->select('payment_request.*');
                }
                else if($approval->count() > 0 && $cek_transfer_approve!=null){
                    $transfer = PaymentRequest::join('history_approval_payment_request as h', function ($join) use ($user) {
                        $join->on('payment_request.id', '=', 'h.payment_request_id')
                        ->whereNotNull('h.is_approved');
                    })
                        ->where('payment_request.is_transfer', '=',1)
                        ->where('payment_request.payment_method', 'Bank Transfer')
                        ->orderBy('created_at','DESC')
                        ->groupBy('payment_request.id')
                        ->select('payment_request.*');
                    
                    $approval = $transfer->get()->merge($approval->get());
                    $approvalId = $approval->pluck('id');

                    $approval = PaymentRequest::join('history_approval_payment_request as h', function ($join) use ($user) {
                        $join->on('payment_request.id', '=', 'h.payment_request_id');
                    })
                        ->whereIn('payment_request.id', $approvalId)
                        ->orderBy('created_at','DESC')
                        ->groupBy('payment_request.id')
                        ->select('payment_request.*');

                }
        }
        else if($status == 'all'){
            $approval = PaymentRequest::join('history_approval_payment_request as h', function ($join) use ($user) {
                $join->on('payment_request.id', '=', 'h.payment_request_id')
                    ->where('h.structure_organization_custom_id', '=', $user->structure_organization_custom_id);
            })
                ->orderBy('created_at','DESC')
                ->select('payment_request.*');
               
                if($approval->count() == 0 && $cek_transfer_approve!=null){
                    $approval = PaymentRequest::join('history_approval_payment_request as h', function ($join) use ($user) {
                        $join->on('payment_request.id', '=', 'h.payment_request_id');
                    })
                        ->where('payment_request.payment_method', 'Bank Transfer')
                        ->orderBy('created_at','DESC')
                        ->groupBy('payment_request.id')
                        ->select('payment_request.*');
                }
                else if($approval->count() > 0 && $cek_transfer_approve!=null){
                    $transfer = PaymentRequest::join('history_approval_payment_request as h', function ($join) use ($user) {
                        $join->on('payment_request.id', '=', 'h.payment_request_id');
                    })
                        ->where('payment_request.payment_method', 'Bank Transfer')
                        ->orderBy('created_at','DESC')
                        ->select('payment_request.*');
                    
                    $approval = $transfer->get()->merge($approval->get());
                    $approvalId = $approval->pluck('id');
    
                    $approval = PaymentRequest::join('history_approval_payment_request as h', function ($join) use ($user) {
                            $join->on('payment_request.id', '=', 'h.payment_request_id');
                        })
                            ->whereIn('payment_request.id', $approvalId)
                            ->orderBy('created_at','DESC')
                            ->groupBy('payment_request.id')
                            ->select('payment_request.*');
                }
        }
        $totalData = $approval->get()->count();
        $approval = $approval->paginate(10);
        $data = [
            'current_page' => $approval->currentPage(), // get current page number
            'total_page' => $approval->total() ? $approval->lastPage() : $approval->total(), // get last page number
            'total_data' => $totalData,
            'payment_requests' => PaymentRequestResource::collection($approval)
        ];
        return response()->json(
            [
                'status' => 'success',
                'message' => 'Successfully',
                'data' => $data
            ], 200);
    }

    public function approve(Request $request){
        info($request->all());
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'payment_request.id'                        => 'required|exists:payment_request,id',
            'payment_request.details.*.id'              => 'required|exists:payment_request_form,id',
            'payment_request.details.*.nominal_approved'=> "required|integer",
            'approval.note'                             => "required",
            'approval.is_approved'                      => "required|in:1,0",
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()],401);
        }

        foreach ($request->payment_request['details'] as $detail){
            $form = PaymentRequestForm::where('id', $detail['id'])->first();
            if($form)
            {
                $form->nominal_approved    = $detail['nominal_approved'];
                if (isset($detail['note'])){
                    $form->note      = $detail['note'];
                }
                if($request->approval['is_approved']==1 && get_setting('period_ca_pr') == 'yes' && $form->sisa_plafond != null && $form->sisa_plafond != $form->plafond){
                    $form->sisa_plafond    = isset($detail['sisa_plafond']) ? $detail['sisa_plafond'] - $detail['nominal_approved'] : $form->sisa_plafond + $form->amount - $detail['nominal_approved'];
                }
                $form->save();
            }
        }

        $payment_request    = PaymentRequest::find($request->payment_request['id']);
        $params             = getEmailConfig();
        $params['data']     = $payment_request;
        $params['value']    = $payment_request->historyApproval;
        $params['subject']  = get_setting('mail_name') . ' - Payment Request';
        $params['view']     = 'email.payment-request-approval-custom';


        $approval                = HistoryApprovalPaymentRequest::where(['payment_request_id'=>$payment_request->id,'structure_organization_custom_id'=> $user->structure_organization_custom_id])->first();
        $approval->approval_id   = $user->id;
        $approval->is_approved   = $request->approval['is_approved'];
        $approval->date_approved = date('Y-m-d H:i:s');
        $approval->note          = $request->approval['note'];
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
            \FRDHelper::setNewData(strtolower($request->company), $payment_request->user->id, $payment_request, $notifType);
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
                \FRDHelper::setNewData(strtolower($request->company), $payment_request->user->id, $payment_request, $notifType);
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
                        \FRDHelper::setNewData(strtolower($request->company), $value->user->id, $payment_request, $notifType);
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
            \FRDHelper::setNewData(strtolower($request->company), $value, $payment_request, $notifType);
        }

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Payment Request Successfully Processed !',
            ], 200);
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
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Your position for transfer is not defined yet. Please contact your admin!'
                ], 403);
        }

        $data['payment_request'] = new PaymentRequestResource(PaymentRequest::findOrFail($id));
        return response()->json(
            [
                'status' => 'success',
                'message' => 'Successfully get detail data transfer',
                'data' => $data
            ], 200);
    }


    public function transfer(Request $request){
        //dd($request);
        info($request->all());
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'payment_request.id'                        => 'required|exists:payment_request,id',
            'payment_request.disbursement'              => "required|in:Transfer,Next Payroll",
            'payment_request.is_transfer'               => "required|in:1,0",
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()],401);
        }

        $data = PaymentRequest::find($request->payment_request['id']);
        $data->is_transfer= $request->payment_request['is_transfer'];
        $data->disbursement = $request->payment_request['disbursement'];
        $data->is_transfer_by = auth()->user()->id;
        if($request->hasFile('transfer_proof'))
        {
            $image = $request->transfer_proof;
            $name =md5($request->payment_request['id'].'transfer_proof').'.'.$image->getClientOriginalExtension();
            $company_url = ($request->company ? $request->company : "umum") . '/';
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
        if($data->disbursement=='Transfer'){
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

        \FRDHelper::setNewData(strtolower($request->company), $data->user->id, $data, $notifType);

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

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Payment Request Transfer Successfully Processed !',
            ], 200);
    }
    
}
