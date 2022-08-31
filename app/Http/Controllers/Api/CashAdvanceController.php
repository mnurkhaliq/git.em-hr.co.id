<?php

namespace App\Http\Controllers\Api;

use App\Http\Middleware\ConnectionMiddleware;
use App\Http\Resources\CashAdvanceResource;
use App\Http\Resources\CashAdvanceFormResource;
use App\Http\Resources\CashAdvanceHistoryApprovalResource;
use App\Models\HistoryApprovalCashAdvance;
use App\Models\CashAdvance;
use App\Models\CashAdvanceBensin;
use App\Models\CashAdvanceForm;
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

class CashAdvanceController extends Controller
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
        $histories = CashAdvance::select('cash_advance.*', \DB::raw('SUM(cash_advance_form.nominal_approved) as total_amount_approved'), \DB::raw('SUM(cash_advance_form.nominal_claimed) as total_amount_claimed'))
                    ->join('cash_advance_form','cash_advance_form.cash_advance_id', '=', 'cash_advance.id')->groupBy('cash_advance.id')
                    ->where(['user_id'=>$user->id])->whereIn('status',$status)->orderBy('created_at','DESC');
        $totalData = $histories->get()->count();
        $histories = $histories->paginate(10);
        $data = [
            'current_page' => $histories->currentPage(), // get current page number
            'total_page' => $histories->total() ? $histories->lastPage() : $histories->total(), // get last page number
            'total_data' => $totalData,
            'cash_advances' => CashAdvanceResource::collection($histories),
            'is_period_active' => get_setting('period_ca_pr') == 'yes' ? true : false,
        ];
        return response()->json(
            [
                'status' => 'success',
                'message' => 'Successfully',
                'data' => $data
            ], 200);
    }

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
        }else if(count($approval->itemsCashAdvance) == 0){
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Setting approval is not defined yet. Please contact your admin!'
                ], 403);
        }
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

        $data                       = new CashAdvance();
        $data->user_id              = $user->id;
        $data->tujuan               = $request->purpose;
        $data->payment_method       = $request->payment_method;
        if($request->payment_method=='Bank Transfer'){
            $data->is_transfer = 0;
            $data->is_transfer_claim = 0;
        }
        $data->status               = 1;
        $data->number = 'CA-' . Carbon::now()->format('dmY') . '/' . \Auth::user()->nik . '-' . (CashAdvance::where('user_id', \Auth::user()->id)->count() + 1);
        $data->save();

        if($request->details){
            foreach ($request->details as $no => $detail){
                $form                           = new CashAdvanceForm();
                $form->cash_advance_id       = $data->id;
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
                            $destinationPath = public_path('/storage/cash-advance/file-struk/') . $company_url;
                            $file->move($destinationPath, $fname);
                            $form->file_struk = $company_url . $fname;
                        }
                    }
                }
                $form->save();
                if(strtolower($form->type_form) == 'gasoline' && isset($detail['gasoline'])){
                    $bensin                          = new CashAdvanceBensin();
                    $bensin->cash_advance_id      = $data->id;
                    $bensin->cash_advance_form_id = $form->id;
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
                if (empty($value->email)) continue;
                $params['email'] = $value->email;
                $params['text']  = '<p><strong>Dear Sir/Madam '. $value->name .'</strong>,</p> <p> '. $data->user->name .'  / '.  $data->user->nik .' applied for Cash Advance and currently waiting your approval.</p>';
                $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                dispatch($job);
            }
            Config::set('database.default', $db);
            $params['text'] = '<p> '. $data->user->name .'  / '.  $data->user->nik .' applied for Cash Advance and currently waiting your approval.</p>';
        }

        $userApprovalTokens = user_approval_firebase_tokens($settingApprovalItem);
        info("structure id ".$settingApprovalItem);
        info($userApprovalTokens);
        
        foreach (user_approval_id($settingApprovalItem) as $value) {
            \FRDHelper::setNewData(strtolower($request->company), $value, $data, 'cash_advance_approval');
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


        return response()->json(
            [
                'status' => 'success',
                'message' => 'Your cash advance has successfully submitted'
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
            }else if(count($approval->itemsCashAdvance) == 0){
                return response()->json(['status' => 'error', 'message' => 'Setting approval is not defined yet. Please contact your admin!'], 403);
            }
        }
        $data['data_waiting'] = CashAdvance::where('user_id', \Auth::user()->id)->where('status', '!=', 3)
                            ->where(function($query) {
                                $query->where('status', '1')->orWhere('status_claim', NULL)->orWhere('status_claim', '1')->orWhere('status_claim', '4');
                            })->count();
        if($request->type == 'create' && get_setting('period_ca_pr') == 'yes' && $data['data_waiting'] > 0){
            return response()->json(['status' => 'error', 'message' => 'Sorry you can not apply this transaction before the previous transaction has been completely approved.'], 403);
        }

        $data['cash_advance_type'] = [];
        
        // $data['cash_advance_type'] = [
        //     ['id'=>'Parking','name'=>'Parking'],
        //     ['id'=>'Gasoline','name'=>'Gasoline'],
        //     ['id'=>'Toll','name'=>'Toll'],
        //     ['id'=>'Transportation','name'=>'Transportation'],
        //     ['id'=>'Others','name'=>'Others']
        // ];

        $type = PaymentRequestType::get();
        if(count($type) > 0){
            foreach($type as $item){
                array_push($data['cash_advance_type'],  
                    ['id'=>$item->type,'name'=>$item->type,'plafond'=>$item->plafond,'sisa_plafond' => get_available_plafond_ca($item->type), 'period' => $item->period]
                );
            }
        }
        $data['is_period_active'] = get_setting('period_ca_pr') == 'yes' ? true : false;
        return response()->json(
            [
                'status' => 'success',
                'message' => 'Successfully',
                'data' => $data
            ], 200);
    }

    public function show($id)
    {
        //
        $user = Auth::user();
        $data['cash_advance'] = new CashAdvanceResource(CashAdvance::select('cash_advance.*', \DB::raw('SUM(cash_advance_form.nominal_approved) as total_amount_approved'), \DB::raw('SUM(cash_advance_form.nominal_claimed) as total_amount_claimed'))
                                ->join('cash_advance_form','cash_advance_form.cash_advance_id', '=', 'cash_advance.id')->groupBy('cash_advance.id')->find($id));
        $data['is_period_active'] = get_setting('period_ca_pr') == 'yes' ? true : false;

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Successfully',
                'data' => $data
            ], 200);

    }

    public function getApproval(Request $request){
        $status = $request->status?$request->status:"all";
        $user = Auth::user();
        $approval = null;
        $cek_transfer_approve = TransferSetting::where('user_id', auth()->user()->id)->first();
        
        if($status == 'ongoing') {
            $approval = CashAdvance::join('history_approval_cash_advance as h', function ($join) use ($user) {
                $join->on('cash_advance.id', '=', 'h.cash_advance_id')
                    ->where('h.setting_approval_level_id', '=', DB::raw('(select min(setting_approval_level_id) from history_approval_cash_advance where cash_advance_id = cash_advance.id and (is_approved is null or (is_approved_claim is null and cash_advance.status = 2)))'))
                    ->where('h.structure_organization_custom_id', '=', $user->structure_organization_custom_id);
                })
                ->where(function ($query) {
                    $query->where('cash_advance.status', 1)->orWhere('cash_advance.status_claim', 1);
                })
                ->orderBy('cash_advance.created_at','DESC')
                ->select('cash_advance.*', \DB::raw('SUM(cash_advance_form.nominal_approved) as total_amount_approved'), \DB::raw('SUM(cash_advance_form.nominal_claimed) as total_amount_claimed'))
                ->join('cash_advance_form','cash_advance_form.cash_advance_id', '=', 'cash_advance.id')->groupBy('cash_advance.id');

                if($approval->count() == 0 && $cek_transfer_approve!=null){
                    $approval = CashAdvance::join('history_approval_cash_advance as h', function ($join) use ($user) {
                        $join->on('cash_advance.id', '=', 'h.cash_advance_id')
                            ->whereNotNull('h.is_approved')
                            ->where(function($query) {
                                $query->where('cash_advance.status','=',2)->orWhere('cash_advance.status_claim','=',2);
                              })
                            ->where(function($query) {
                                $query->where('cash_advance.is_transfer','!=',1)->orWhere('cash_advance.is_transfer_claim','!=',1)->where('cash_advance.status_claim', '=', 2);
                              });  
                    })
                        ->orderBy('cash_advance.created_at','DESC')
                        ->where('cash_advance.payment_method', 'Bank Transfer')
                        ->select('cash_advance.*', \DB::raw('SUM(cash_advance_form.nominal_approved) as total_amount_approved'), \DB::raw('SUM(cash_advance_form.nominal_claimed) as total_amount_claimed'))
                        ->join('cash_advance_form','cash_advance_form.cash_advance_id', '=', 'cash_advance.id')->groupBy('cash_advance.id')
                        ->having('total_amount_approved', '!=', 'total_amount_claimed');
                }
                else if($approval->count() > 0 && $cek_transfer_approve!=null){
                    $transfer = CashAdvance::join('history_approval_cash_advance as h', function ($join) use ($user) {
                        $join->on('cash_advance.id', '=', 'h.cash_advance_id')
                            ->whereNotNull('h.is_approved')
                            ->where(function($query) {
                                $query->where('cash_advance.status','=',2)->orWhere('cash_advance.status_claim','=',2);
                              })
                            ->where(function($query) {
                                $query->where('cash_advance.is_transfer','!=',1)->orWhere('cash_advance.is_transfer_claim','!=',1)->where('cash_advance.status_claim', '=', 2);
                              });  
                    })
                        ->orderBy('cash_advance.created_at','DESC')
                        ->where('cash_advance.payment_method', 'Bank Transfer')
                        ->select('cash_advance.*', \DB::raw('SUM(cash_advance_form.nominal_approved) as total_amount_approved'), \DB::raw('SUM(cash_advance_form.nominal_claimed) as total_amount_claimed'))
                        ->join('cash_advance_form','cash_advance_form.cash_advance_id', '=', 'cash_advance.id')->groupBy('cash_advance.id');
                        // ->having('total_amount_approved', '!=', 'total_amount_claimed');
                    
                    foreach($transfer->get() as $no => $tf){
                        if($tf->total_amount_claimed != null && $tf->total_amount_approved != null && $tf->total_amount_approved != $tf->total_amount_claimed){
                            $cek[$no] = $tf->id;
                        }
                        if($tf->total_amount_claimed == null && $tf->total_amount_approved != null && $tf->total_amount_approved != $tf->total_amount_claimed){
                            $cek[$no] = $tf->id;
                        }
                        if($tf->total_amount_claimed == null && $tf->total_amount_approved == null && $tf->total_amount_approved == $tf->total_amount_claimed){
                            $cek[$no] = $tf->id;
                        }
                    }
                    //return $cek;
                    if(isset($cek)){
                        $approval = $transfer->whereIn('cash_advance.id', $cek)->get()->merge($approval->get());
                    }
                    else{
                        $approval = $transfer->get()->merge($approval->get());
                    }
                    $approvalId = $approval->pluck('id');
                    $approval = CashAdvance::join('history_approval_cash_advance as h', function ($join) use ($user) {
                        $join->on('cash_advance.id', '=', 'h.cash_advance_id');})
                        ->orderBy('cash_advance.created_at','DESC')
                        ->whereIn('cash_advance.id', $approvalId)
                        ->select('cash_advance.*', \DB::raw('SUM(cash_advance_form.nominal_approved) as total_amount_approved'), \DB::raw('SUM(cash_advance_form.nominal_claimed) as total_amount_claimed'))
                        ->join('cash_advance_form','cash_advance_form.cash_advance_id', '=', 'cash_advance.id')->groupBy('cash_advance.id');
                    
                }
                

        }else if($status == 'history'){
            $approval = CashAdvance::join('history_approval_cash_advance as h', function ($join) use ($user) {
                $join->on('cash_advance.id', '=', 'h.cash_advance_id')
                    ->whereNotNull('h.is_approved')
                    ->where(function($query) {
                        $query->where('cash_advance.status_claim','!=',1)->orWhereNull('cash_advance.status_claim')->orWhereNotNull('h.is_approved_claim');
                      })
                    ->where('h.structure_organization_custom_id', '=', $user->structure_organization_custom_id);
            })
                ->orderBy('cash_advance.created_at','DESC')
                ->select('cash_advance.*', \DB::raw('SUM(cash_advance_form.nominal_approved) as total_amount_approved'), \DB::raw('SUM(cash_advance_form.nominal_claimed) as total_amount_claimed'))
                ->join('cash_advance_form','cash_advance_form.cash_advance_id', '=', 'cash_advance.id')->groupBy('cash_advance.id');

                if($approval->count() == 0 && $cek_transfer_approve!=null){
                    $approval = CashAdvance::join('history_approval_cash_advance as h', function ($join) use ($user) {
                        $join->on('cash_advance.id', '=', 'h.cash_advance_id')
                            ->whereNotNull('h.is_approved')
                            ->where(function($query) {
                                $query->where('cash_advance.status_claim','!=',1)->orWhereNull('cash_advance.status_claim');
                              });
                    })
                        ->where('cash_advance.is_transfer', '=',1)
                        ->orWhere('cash_advance.is_transfer_claim', '=',1)
                        ->orderBy('cash_advance.created_at','DESC')
                        ->where('cash_advance.payment_method', 'Bank Transfer')
                        ->select('cash_advance.*', \DB::raw('SUM(cash_advance_form.nominal_approved) as total_amount_approved'), \DB::raw('SUM(cash_advance_form.nominal_claimed) as total_amount_claimed'))
                        ->join('cash_advance_form','cash_advance_form.cash_advance_id', '=', 'cash_advance.id')->groupBy('cash_advance.id');
                        
                }
                else if($approval->count() > 0 && $cek_transfer_approve!=null){
                    $transfer = CashAdvance::join('history_approval_cash_advance as h', function ($join) use ($user) {
                        $join->on('cash_advance.id', '=', 'h.cash_advance_id')
                            ->whereNotNull('h.is_approved')
                            ->where(function($query) {
                                $query->where('cash_advance.status_claim','!=',1)->orWhereNull('cash_advance.status_claim');
                              });
                    })
                        ->where('cash_advance.is_transfer', '=',1)
                        ->orWhere('cash_advance.is_transfer_claim', '=',1)
                        ->orderBy('cash_advance.created_at','DESC')
                        ->where('cash_advance.payment_method', 'Bank Transfer')
                        ->select('cash_advance.*', \DB::raw('SUM(cash_advance_form.nominal_approved) as total_amount_approved'), \DB::raw('SUM(cash_advance_form.nominal_claimed) as total_amount_claimed'))
                        ->join('cash_advance_form','cash_advance_form.cash_advance_id', '=', 'cash_advance.id')->groupBy('cash_advance.id');
                    
                    $approval = $transfer->get()->merge($approval->get());
                    $approvalId = $approval->pluck('id');
                    $approval = CashAdvance::join('history_approval_cash_advance as h', function ($join) use ($user) {
                        $join->on('cash_advance.id', '=', 'h.cash_advance_id')
                            ->whereNotNull('h.is_approved')
                            ->where(function($query) {
                                $query->where('cash_advance.status_claim','!=',1)->orWhereNull('cash_advance.status_claim');
                              });
                    })
                        ->orderBy('cash_advance.created_at','DESC')
                        ->whereIn('cash_advance.id', $approvalId)
                        ->select('cash_advance.*', \DB::raw('SUM(cash_advance_form.nominal_approved) as total_amount_approved'), \DB::raw('SUM(cash_advance_form.nominal_claimed) as total_amount_claimed'))
                        ->join('cash_advance_form','cash_advance_form.cash_advance_id', '=', 'cash_advance.id')->groupBy('cash_advance.id');
                    
                }
        }
        else if($status == 'all'){
            $approval = CashAdvance::join('history_approval_cash_advance as h', function ($join) use ($user) {
                $join->on('cash_advance.id', '=', 'h.cash_advance_id')
                    ->where('h.structure_organization_custom_id', '=', $user->structure_organization_custom_id);
            })
                ->orderBy('cash_advance.created_at','DESC')
                ->select('cash_advance.*', \DB::raw('SUM(cash_advance_form.nominal_approved) as total_amount_approved'), \DB::raw('SUM(cash_advance_form.nominal_claimed) as total_amount_claimed'))
                ->join('cash_advance_form','cash_advance_form.cash_advance_id', '=', 'cash_advance.id')->groupBy('cash_advance.id');

                if($approval->count() == 0 && $cek_transfer_approve!=null){
                    $approval = CashAdvance::join('history_approval_cash_advance as h', function ($join) use ($user) {
                        $join->on('cash_advance.id', '=', 'h.cash_advance_id');
                    })
                        ->orderBy('cash_advance.created_at','DESC')
                        ->where('cash_advance.payment_method', 'Bank Transfer')
                        ->select('cash_advance.*', \DB::raw('SUM(cash_advance_form.nominal_approved) as total_amount_approved'), \DB::raw('SUM(cash_advance_form.nominal_claimed) as total_amount_claimed'))
                        ->join('cash_advance_form','cash_advance_form.cash_advance_id', '=', 'cash_advance.id')->groupBy('cash_advance.id');
                        
                }
                else if($approval->count() > 0 && $cek_transfer_approve!=null){
                    $transfer = CashAdvance::join('history_approval_cash_advance as h', function ($join) use ($user) {
                        $join->on('cash_advance.id', '=', 'h.cash_advance_id');
                    })
                        ->orderBy('cash_advance.created_at','DESC')
                        ->where('cash_advance.payment_method', 'Bank Transfer')
                        ->select('cash_advance.*', \DB::raw('SUM(cash_advance_form.nominal_approved) as total_amount_approved'), \DB::raw('SUM(cash_advance_form.nominal_claimed) as total_amount_claimed'))
                        ->join('cash_advance_form','cash_advance_form.cash_advance_id', '=', 'cash_advance.id')->groupBy('cash_advance.id');
                    
                    $approval = $transfer->get()->merge($approval->get());
                    $approvalId = $approval->pluck('id');

                    $approval = CashAdvance::join('history_approval_cash_advance as h', function ($join) use ($user) {
                            $join->on('cash_advance.id', '=', 'h.cash_advance_id');
                        })
                        ->orderBy('cash_advance.created_at','DESC')
                        ->whereIn('cash_advance.id', $approvalId)
                        ->select('cash_advance.*', \DB::raw('SUM(cash_advance_form.nominal_approved) as total_amount_approved'), \DB::raw('SUM(cash_advance_form.nominal_claimed) as total_amount_claimed'))
                        ->join('cash_advance_form','cash_advance_form.cash_advance_id', '=', 'cash_advance.id')->groupBy('cash_advance.id');
                    
                }
        }
        $totalData = $approval->get()->count();
        $approval = $approval->paginate(10);
        $data = [
            'current_page' => $approval->currentPage(), // get current page number
            'total_page' => $approval->total() ? $approval->lastPage() : $approval->total(), // get last page number
            'total_data' => $totalData,
            'cash_advances' => CashAdvanceResource::collection($approval)
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
            'cash_advance.id'                        => 'required|exists:cash_advance,id',
            'cash_advance.details.*.id'              => 'required|exists:cash_advance_form,id',

            'cash_advance.details.*.nominal_approved'=> "required|integer",
            'approval.note'                             => "required",
            'approval.is_approved'                      => "required|in:1,0",
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()],401);
        }

        foreach ($request->cash_advance['details'] as $detail){
            $form = CashAdvanceForm::where('id', $detail['id'])->first();
            if($form)
            {
                $form->nominal_approved    = $detail['nominal_approved'];
                if (isset($detail['note'])){
                    $form->note    = $detail['note'];
                }
                if($request->approval['is_approved']==1 && get_setting('period_ca_pr') == 'yes' && $form->sisa_plafond != null && $form->sisa_plafond != $form->plafond){
                    $form->sisa_plafond    = isset($detail['sisa_plafond']) ? $detail['sisa_plafond'] - $detail['nominal_approved'] : $form->sisa_plafond + $form->amount - $detail['nominal_approved'];
                }
                $form->save();
            }
        }

        $cash_advance    = CashAdvance::find($request->cash_advance['id']);
        $params             = getEmailConfig();
        $params['data']     = $cash_advance;
        $params['value']    = $cash_advance->historyApproval;
        $params['subject']  = get_setting('mail_name') . ' - Cash Advance';
        $params['view']     = 'email.cash-advance';


        $approval                = HistoryApprovalCashAdvance::where(['cash_advance_id'=>$cash_advance->id,'structure_organization_custom_id'=> $user->structure_organization_custom_id])->first();
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
            \FRDHelper::setNewData(strtolower($request->company), $cash_advance->user->id, $cash_advance, $notifType);
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
                \FRDHelper::setNewData(strtolower($request->company), $cash_advance->user->id, $cash_advance, $notifType);
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
                        \FRDHelper::setNewData(strtolower($request->company), $value->user->id, $cash_advance, $notifType);
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
            \FRDHelper::setNewData(strtolower($request->company), $value, $cash_advance, $notifType);
        }

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Cash Advance Successfully Processed !',
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

    public function claim(Request $request){
        //dd($request); 
        $user = Auth::user();
        $status_claim = $request->status_claim ? $request->status_claim : 1;
        if($status_claim == 1){
            $validator = Validator::make($request->all(), [
                'cash_advance.id'                        => 'required|exists:cash_advance,id',
                'cash_advance.details.*.id'              => 'required|exists:cash_advance_form,id',
                'cash_advance.details.*.actual_amount'=> "required|integer",
                // 'cash_advance.details.*.file_struk'=> 'required|mimes:jpg,jpeg,bmp,png,gif,svg,pdf'
                // 'approval.note_claim'                             => "required",
                // 'approval.is_approved_claim'                      => "required|in:1,0",
            ]);
        }
        else {
            $validator = Validator::make($request->all(), [
                'cash_advance.id'                        => 'required|exists:cash_advance,id',
                'cash_advance.details.*.id'              => 'required|exists:cash_advance_form,id',
            ]);
        }

        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()],401);
        }
        $id = $request->cash_advance['id'];
        $cash_advance = CashAdvance::find($id);
        $status_claim_prev = $cash_advance->status_claim;
        if(!$cash_advance || $cash_advance->user_id != $user->id){
            return response()->json(['status' => 'failed', 'message' => 'Cash Advance is not found'],404);
        }

        $cash_advance->status_claim               = $status_claim;
        $cash_advance->date_claim                 = date('Y-m-d H:i:s');
        $cash_advance->save();

        foreach ($request->cash_advance['details'] as $detail){
            $form = CashAdvanceForm::where('id', $detail['id'])->first();
            if($form)
            {
                $actual_amount = $form->actual_amount;
                $form->actual_amount            = $detail['actual_amount'];
                if($status_claim_prev == NULL && get_setting('period_ca_pr') == 'yes' && $form->sisa_plafond != null && $form->sisa_plafond != $form->plafond){
                    $form->sisa_plafond    = $detail['sisa_plafond'] != NULL && $detail['sisa_plafond'] >= 0  ? $detail['sisa_plafond'] - $detail['actual_amount'] : $form->sisa_plafond + $form->nominal_approved - $detail['actual_amount'];
                }elseif($status_claim_prev == 4 && get_setting('period_ca_pr') == 'yes' && $form->sisa_plafond != null && $form->sisa_plafond != $form->plafond){
                    $form->sisa_plafond    = $detail['sisa_plafond'] != NULL && $detail['sisa_plafond'] >= 0  ? $detail['sisa_plafond'] - $detail['actual_amount'] : $form->sisa_plafond + $actual_amount - $detail['actual_amount'];
                }
                //$form->nominal_claimed    = $detail['nominal_claimed'];
                $company_url = ($request->company ? $request->company : "umum") . '/';
                
                if(isset($detail['file_struk']) && str_contains($detail['file_struk'],$company_url) == false )
                {
                    $image = $detail['file_struk'];
                    $name = md5(rand() . $image->getClientOriginalName() . time()) . "." . $image->getClientOriginalExtension();
                    $company_url = ($request->company ? $request->company : "umum") . '/';
                    $destinationPath = public_path('storage/cash-advance/file-struk/').$company_url;
                    $image->move($destinationPath, $name);
                    $form->file_struk = $company_url.$name;
                }
                elseif(isset($detail['file_struk'])){
                    $form->file_struk = $detail['file_struk'];
                }
                else{
                    $form->file_struk = NULL;
                }
                $form->save();
            }
        }

        if ($status_claim == 1 || $status_claim == 4) {
            HistoryApprovalCashAdvance::where('cash_advance_id',$cash_advance->id)->update([
                'approval_id_claim' => null,
                'is_approved_claim' => null,
                'date_approved_claim' => null,
            ]);
        }

        if($status_claim == 1){
            $historyApprov        = HistoryApprovalCashAdvance::where('cash_advance_id',$cash_advance->id)->orderBy('setting_approval_level_id','asc')->get();
            if(count($historyApprov)>0) {
                $settingApprovalItem = $historyApprov[0]->structure_organization_custom_id;
                $userApproval = user_approval_custom($settingApprovalItem);
                //$userApproval = getAdminByModule(32);
                $params = getEmailConfig();
                $db = Config::get('database.default', 'mysql');

                $params['data'] = $cash_advance;
                $params['total']    = total_cash_advance_actual_amount($cash_advance->id);
                $params['value'] = $historyApprov;
                $params['view'] = 'email.cash-advance';
                $params['subject'] = get_setting('mail_name') . ' - Cash Advance';
                if ($userApproval) {
                    Config::set('database.default', 'mysql');
                    foreach ($userApproval as $key => $value) {
                        if (empty($value->email)) continue;
                        $params['email'] = $value->email;
                        $params['text'] = '<p><strong>Dear Sir/Madam ' . $value->name . '</strong>,</p> <p> ' . $cash_advance->user->name . '  / ' . $cash_advance->user->nik . ' applied for Claim of Cash Advance and currently waiting your approval.</p>';
                        $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                        dispatch($job);
                    }
                    Config::set('database.default', $db);
                    $params['text'] = '<p> ' . $cash_advance->user->name . '  / ' . $cash_advance->user->nik . ' applied for Claim of Cash Advance and currently waiting your approval.</p>';
                }
            }

            $userApprovalTokens = user_approval_firebase_tokens($settingApprovalItem);
            info("structure id ".$settingApprovalItem);
            info($userApprovalTokens);
        
            foreach (user_approval_id($settingApprovalItem) as $value) {
                \FRDHelper::setNewData(strtolower($request->company), $value, $cash_advance, 'claim_cash_advance_approval');
            }
    
            if(count($userApprovalTokens) > 0){
                $config = [
                    'title' => "Claim Cash Advance Approval",
                    'content' => strip_tags($params['text']),
                    'type' => 'claim_cash_advance_approval',
                    'firebase_token' => $userApprovalTokens
                ];
                $notifData = [
                    'id' => $cash_advance->id
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
                'message' => $status_claim== 1 ? 'Your Cash Advance claim request has successfully submitted' : 'Your Cash Advance claim request has successfully save as draft'
            ], 201);

    }

    public function approveClaim(Request $request){
        info($request->all());
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'cash_advance.id'                        => 'required|exists:cash_advance,id',
            'cash_advance.details.*.id'              => 'required|exists:cash_advance_form,id',
            'cash_advance.details.*.nominal_claimed'=> "required|integer",
            'approval.note_claim'                             => "required",
            'approval.is_approved_claim'                      => "required|in:1,0",
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()],401);
        }

        foreach ($request->cash_advance['details'] as $detail){
            $form = CashAdvanceForm::where('id', $detail['id'])->first();
            if($form)
            {
                $form->nominal_claimed    = $detail['nominal_claimed'];
                if (isset($detail['note_claimed'])){
                    $form->note_claimed    = $detail['note_claimed'];
                }
                if($request->approval['is_approved_claim']==1 && get_setting('period_ca_pr') == 'yes' && $form->sisa_plafond != null && $form->sisa_plafond != $form->plafond){
                    $form->sisa_plafond    = isset($detail['sisa_plafond']) ? $detail['sisa_plafond'] - $detail['nominal_claimed'] : $form->sisa_plafond + $form->actual_amount - $detail['nominal_claimed'];
                }
                $form->save();
            }
        }

        $cash_advance           = CashAdvance::find($request->cash_advance['id']);
 
        $params             = getEmailConfig();
        $params['data']     = $cash_advance;
        $params['value']    = $cash_advance->historyApproval;
        $params['subject']  = get_setting('mail_name') . ' - Cash Advance';
        $params['view']     = 'email.cash-advance';


        $approval                = HistoryApprovalCashAdvance::where(['cash_advance_id'=>$cash_advance->id,'structure_organization_custom_id'=> $user->structure_organization_custom_id])->first();
        $approval->approval_id_claim   = $user->id;
        $approval->is_approved_claim   = $request->approval['is_approved_claim'];
        $approval->date_approved_claim = date('Y-m-d H:i:s');
        $approval->note_claim       = $request->approval['note_claim'];
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
            \FRDHelper::setNewData(strtolower($request->company), $cash_advance->user->id, $cash_advance, $notifType);
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
                \FRDHelper::setNewData(strtolower($request->company), $cash_advance->user->id, $cash_advance, $notifType);
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
                            \FRDHelper::setNewData(strtolower($request->company), $value->user->id, $cash_advance, $notifType);
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
                            \FRDHelper::setNewData(strtolower($request->company), $value->user->id, $cash_advance, $notifType);
                        }
                        Config::set('database.default', $db);
                    }
                    
                    Config::set('database.default', 'mysql');
                    if($cash_advance->user->email && $cash_advance->user->email != "") {
                        $params['email'] = $cash_advance->user->email;
                        $params['text']     = '<p><strong>Dear Sir/Madam '. $cash_advance->user->name .'</strong>,</p> <p>Total claimed is less than the total approved, so you must return the excess. which will be followed up by the company.</p>';
                        $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                        dispatch($job);
                    }
                    Config::set('database.default', $db);

                    $userApprovalTokens = [];
                    $notifTitle = "Transfer Claim Cash Advance";
                    $notifType  = "transfer_back_claim_cash_advance_more";
                    if($cash_advance->user->firebase_token) {
                        array_push($userApprovalTokens, $cash_advance->user->firebase_token);
                        $this->sentNotif($notifTitle, $params['text'], $notifType, $userApprovalTokens, $cash_advance->id);
                        $userApprovalTokens = [];
                    }
                    \FRDHelper::setNewData(strtolower($request->company), $cash_advance->user->id, $cash_advance, $notifType);
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
            \FRDHelper::setNewData(strtolower($request->company), $value, $cash_advance, $notifType);
        }

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Form Cash Advance Successfully Processed !',
            ], 200);
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

        $data['cash_advance'] = new CashAdvanceResource(CashAdvance::find($id));

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Successfully get detail data transfer cash advance',
                'data' => $data
            ], 200);
    }


    public function transfer(Request $request){
        //dd($request);
        info($request->all());
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'cash_advance.id'                        => 'required|exists:cash_advance,id',
            'cash_advance.disbursement'              => "required|in:Transfer,Next Payroll",
            'cash_advance.is_transfer'               => "required|in:1,0",
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()],401);
        }

        $data = CashAdvance::find($request->cash_advance['id']);
        $data->is_transfer= $request->cash_advance['is_transfer'];
        $data->disbursement = $request->cash_advance['disbursement'];
        $data->is_transfer_by = auth()->user()->id;

        if($request->hasFile('transfer_proof'))
        {
            $image = $request->transfer_proof;
            $name = md5($request->cash_advance['id'].'transfer_proof').'.'.$image->getClientOriginalExtension();
            $company_url = ($request->company ? $request->company : "umum") . '/';
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
        if($data->disbursement=='Transfer'){
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
                'message' => 'Cash Advance Transfer Successfully Processed !',
            ], 200);
    }

    public function transferClaim(Request $request){
        //dd($request);
        info($request->all());
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'cash_advance.id'                        => 'required|exists:cash_advance,id',
            'cash_advance.disbursement_claim'              => "required|in:Transfer,Next Payroll",
            'cash_advance.is_transfer_claim'               => "required|in:1,0",
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()],401);
        }

        $data = CashAdvance::find($request->cash_advance['id']);
        $data->is_transfer_claim= $request->cash_advance['is_transfer_claim'];
        $data->disbursement_claim = $request->cash_advance['disbursement_claim'];
        $data->is_transfer_claim_by = auth()->user()->id;

        if($request->hasFile('transfer_proof_claim'))
        {
            $image = $request->transfer_proof_claim;
            $name = md5($request->cash_advance['id'].'transfer_proof_claim').'.'.$image->getClientOriginalExtension();
            $company_url = ($request->company ? $request->company : "umum") . '/';
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
        if($data->disbursement_claim=='Transfer'){
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
                'message' => 'Cash Advance Transfer Claim Successfully Processed !',
            ], 200);
    }

    public function transferUser($id)
    {
        $params['data']         = CashAdvance::where('id', $id)->first();

        if (!IsAccess($params['data'])) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'You don\'t have permission to perform this action!'
                ], 403);
        }

        $data['cash_advance'] = new CashAdvanceResource(CashAdvance::find($id));

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Successfully get detail data transfer cash advance',
                'data' => $data
            ], 200);
    }

    public function prosesTransferUser(Request $request){
        info($request->all());
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'cash_advance.id'                        => 'required|exists:cash_advance,id',
            'cash_advance.is_transfer_claim'               => "required|in:1,0",
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()],401);
        }

        $data = CashAdvance::find($request->cash_advance['id']);
        $data->is_transfer_claim= $request->cash_advance['is_transfer_claim'];
        $data->is_transfer_claim_by = auth()->user()->id;

        if($request->hasFile('transfer_proof_claim'))
        {
            $image = $request->transfer_proof_claim;
            $name = md5($request->cash_advance['id'].'transfer_proof_claim_by_user').'.'.$image->getClientOriginalExtension();
            $company_url = ($request->company ? $request->company : "umum") . '/';
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
                \FRDHelper::setNewData(strtolower($request->company), $value->user->id, $data, $notifType);
            }
            Config::set('database.default', $db);
        }

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Cash Advance Transfer Claim Successfully Processed !',
            ], 200);
    }

}
