<?php

namespace App\Http\Controllers\Api;

use App\Http\Middleware\ConnectionMiddleware;
use App\Http\Resources\MedicalResource;
use App\Models\HistoryApprovalMedical;
use App\Models\MedicalReimbursement;
use App\Models\MedicalReimbursementForm;
use App\Models\MedicalType;
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

class MedicalController extends Controller
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
        $status = $request->input('status','[1,2,3,4,5]');
        if(!isJsonValid($status))
            return response()->json(['status' => 'error','message'=>'Status is invalid'], 404);
        $status = json_decode($status);
        $histories = MedicalReimbursement::where(['user_id'=>$user->id])->whereIn('status',$status)->orderBy('created_at','DESC');
        $totalData = $histories->get()->count();
        $histories = $histories->paginate(10);
        $data = [
            'current_page' => $histories->currentPage(), // get current page number
            'total_page' => $histories->total() ? $histories->lastPage() : $histories->total(), // get last page number
            'total_data' => $totalData,
            'medicals' => MedicalResource::collection($histories)
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
        $user = Auth::user();
        $approval = $user->approval;
        if($approval == null){
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Your position is not defined yet. Please contact your admin!'
                ], 403);
        }else if(count($approval->itemsMedical) == 0){
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Setting approval is not defined yet. Please contact your admin!'
                ], 403);
        }
        $status = $request->status ? $request->status : 1;
        if($status==1){
            $validator = Validator::make($request->all(), [
                "details"                     => "required|array",
                'details.*.tanggal_kwitansi'  => 'required|date',
                'details.*.user_family_id'    => "required|integer",
                'details.*.medical_type_id'   => "required|exists:medical_type,id",
                'details.*.no_kwitansi'       => "required",
                'details.*.jumlah'            => "required|integer",
                // 'files.*'                     => 'required|mimes:jpg,jpeg,bmp,png,gif,svg,pdf',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()],401);
            }
        }

        $data                       = new MedicalReimbursement();
        $data->user_id              = $user->id;
        $data->tanggal_pengajuan    = date('Y-m-d');
        $data->status               = $status;
        $data->is_transfer = 0;
        $data->number = 'MR-' . Carbon::now()->format('dmY') . '/' . \Auth::user()->nik . '-' . (MedicalReimbursement::where('user_id', \Auth::user()->id)->count() + 1);
        $data->save();

        if($request->details){
            foreach ($request->details as $no => $detail){
                $form                           = new MedicalReimbursementForm();
                $form->medical_reimbursement_id = $data->id;
                $form->tanggal_kwitansi         = ($detail['tanggal_kwitansi']);
                $form->user_family_id           = isset($detail['user_family_id']) ? $detail['user_family_id'] : NULL;
                $form->medical_type_id          = isset($detail['medical_type_id']) ? $detail['medical_type_id'] : NULL;
                $form->no_kwitansi              = ($detail['no_kwitansi']);
                $form->jumlah                   = ($detail['jumlah']);

                $files = $request->file('files');
                if ($request->hasFile('files'))
                {
                    foreach($files as $k => $file) {
                        if ($k == $no) {
                            $fname = md5(rand() . $file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                            $company_url = ($request->company?$request->company:"umum") . '/';
                            $destinationPath = public_path('/storage/file-medical/') . $company_url;
                            $file->move($destinationPath, $fname);
                            $form->file_bukti_transaksi = $company_url . $fname;
                        }
                    }
                }
                $form->save();
            }
        }

        if($status==1){
            $historyApproval     = $user->approval->itemsMedical;
            $settingApprovalItem = $historyApproval[0]->structure_organization_custom_id;
            foreach ($historyApproval as $level => $value) {
                # code...
                $history = new HistoryApprovalMedical();
                $history->medical_reimbursement_id         = $data->id;
                $history->setting_approval_level_id        = ($level+1);
                $history->structure_organization_custom_id = $value->structure_organization_custom_id;
                $history->save();
            }
            $historyApprov = HistoryApprovalMedical::where('medical_reimbursement_id',$data->id)->orderBy('setting_approval_level_id','asc')->get();

            $userApproval = user_approval_custom($settingApprovalItem);
            $db = Config::get('database.default','mysql');

            $params = getEmailConfig();
            $params['data']     = $data;
            $params['total']    = total_medical_nominal($data->id);
            $params['value']    = $historyApprov;
            $params['view']     = 'email.medical-approval-custom';
            $params['subject']  = get_setting('mail_name') . ' - Medical Reimbursement';
            if($userApproval) {
                Config::set('database.default', 'mysql');
                foreach ($userApproval as $key => $value) {
                    if (empty($value->email)) continue;
                    $params['email'] = $value->email;
                    $params['text']  = '<p><strong>Dear Sir/Madam '. $value->name .'</strong>,</p> <p> '. $data->user->name .'  / '.  $data->user->nik .' applied for Medical Reimbursement and currently waiting your approval.</p>';
                    $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                    dispatch($job);
                }
                Config::set('database.default', $db);
                $params['text']  = '<p> '. $data->user->name .'  / '.  $data->user->nik .' applied for Medical Reimbursement and currently waiting your approval.</p>';
            }

            $userApprovalTokens = user_approval_firebase_tokens($settingApprovalItem);
            info("structure id ".$settingApprovalItem);
            info($userApprovalTokens);

            foreach (user_approval_id($settingApprovalItem) as $value) {
                \FRDHelper::setNewData(strtolower($request->company), $value, $data, 'medical_approval');
            }
    
            if(count($userApprovalTokens) > 0){
                $config = [
                    'title' => "Medical Reimbursement Approval",
                    'content' => strip_tags($params['text']),
                    'type' => 'medical_approval',
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
                'message' => $status== 1 ? 'Your medical reimbursement request has successfully submitted' : 'Your medical reimbursement request has successfully save draft'
            ], 201);
    }

    public function getParams(Request $request)
    {
        //
        if(!$request->user_id)
            return response()->json(['status' => 'error', 'message' => 'User ID is required!'], 403);
        $user = User::find($request->user_id);
        if(!$user)
            return response()->json(['status' => 'error', 'message' => 'User is not found!'], 404);
        if($request->type == 'create'){
            $approval = $user->approval;
            if($approval == null){
                return response()->json(['status' => 'error','message' => 'Your position is not defined yet. Please contact your admin!'], 403);
            }else if(count($approval->itemsMedical) == 0){
                return response()->json(['status' => 'error', 'message' => 'Setting approval is not defined yet. Please contact your admin!'], 403);
            }
        }
        $data['families']     = $user->userFamily->toArray();
        array_unshift($data['families'],['id'=>'0','hubungan'=>'Myself','nama'=>$user->name]);
        $data['medical_type'] = MedicalType::where('project_id', $user->project_id)->select('*')->get();
        return response()->json(
            [
                'status' => 'success',
                'message' => 'Successfully',
                'data' => $data
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
        $data['medical'] = new MedicalResource(MedicalReimbursement::findOrFail($id));
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
        $user = Auth::user();
        $approval = $user->approval;
        if($approval == null){
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Your position is not defined yet. Please contact your admin!'
                ], 403);
        }else if(count($approval->itemsMedical) == 0){
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Setting approval is not defined yet. Please contact your admin!'
                ], 403);
        }
        $validator = Validator::make($request->all(), [
            'medical.id'                  => 'required|exists:medical_reimbursement,id',
        ]);
        if($request->status==1){
            $validator = Validator::make($request->all(), [
                'medical.id'                  => 'required|exists:medical_reimbursement,id',
                "details"                     => "required|array",
                'details.*.tanggal_kwitansi'  => 'required|date',
                'details.*.user_family_id'    => "required|integer",
                'details.*.medical_type_id'   => "required|exists:medical_type,id",
                'details.*.no_kwitansi'       => "required",
                'details.*.jumlah'            => "required|integer",
                // 'files.*'                     => 'required|mimes:jpg,jpeg,bmp,png,gif,svg,pdf',
            ]);    
        }

        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()],401);
        }

        $data                       = MedicalReimbursement::find($request->medical['id']);
        $data->user_id              = $user->id;
        $data->tanggal_pengajuan    = date('Y-m-d');
        $data->status               = $request->status;
        $data->is_transfer = 0;
        $data->save();

        $temp_form = MedicalReimbursementForm::where('medical_reimbursement_id', $request->medical['id'])->get();
        $other_form = MedicalReimbursementForm::where('medical_reimbursement_id', $request->medical['id'])->delete();

        if($request->details){
            foreach ($request->details as $no => $detail){
                $form                           = new MedicalReimbursementForm();
                $form->medical_reimbursement_id = $data->id;
                $form->tanggal_kwitansi         = ($detail['tanggal_kwitansi']);
                $form->user_family_id           = isset($detail['user_family_id']) ? $detail['user_family_id'] : NULL;
                $form->medical_type_id          = isset($detail['medical_type_id']) ? $detail['medical_type_id'] : NULL;
                $form->no_kwitansi              = ($detail['no_kwitansi']);
                $form->jumlah                   = ($detail['jumlah']);

                if($request->file('files') && isset($request->file('files')[$no]))
                {
                    $file = $request->file('files')[$no];
                    $fname = md5(rand() . $file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                    $company_url = ($request->company?$request->company:"umum") . '/';
                    $destinationPath = public_path('/storage/file-medical/').$company_url;
                    $file->move($destinationPath, $fname);
                    $form->file_bukti_transaksi = $company_url.$fname;
                } else if (isset($request->all()['files'][$no])) {
                    $form->file_bukti_transaksi = $request->all()['files'][$no];
                }

                $form->save();
            }
        }

        if($request->status==1){
            $historyApproval     = $user->approval->itemsMedical;
            $settingApprovalItem = $historyApproval[0]->structure_organization_custom_id;
            foreach ($historyApproval as $level => $value) {
                # code...
                $history = new HistoryApprovalMedical();
                $history->medical_reimbursement_id         = $data->id;
                $history->setting_approval_level_id        = ($level+1);
                $history->structure_organization_custom_id = $value->structure_organization_custom_id;
                $history->save();
            }
            $historyApprov = HistoryApprovalMedical::where('medical_reimbursement_id',$data->id)->orderBy('setting_approval_level_id','asc')->get();

            $userApproval = user_approval_custom($settingApprovalItem);
            $db = Config::get('database.default','mysql');

            $params = getEmailConfig();
            $params['data']     = $data;
            $params['total']    = total_medical_nominal($data->id);
            $params['value']    = $historyApprov;
            $params['view']     = 'email.medical-approval-custom';
            $params['subject']  = get_setting('mail_name') . ' - Medical Reimbursement';
            if($userApproval) {
                Config::set('database.default', 'mysql');
                foreach ($userApproval as $key => $value) {
                    if (empty($value->email)) continue;
                    $params['email'] = $value->email;
                    $params['text']  = '<p><strong>Dear Sir/Madam '. $value->name .'</strong>,</p> <p> '. $data->user->name .'  / '.  $data->user->nik .' applied for Medical Reimbursement and currently waiting your approval.</p>';
                    $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                    dispatch($job);
                }
                Config::set('database.default', $db);
                $params['text']  = '<p> '. $data->user->name .'  / '.  $data->user->nik .' applied for Medical Reimbursement and currently waiting your approval.</p>';
            }

            $userApprovalTokens = user_approval_firebase_tokens($settingApprovalItem);
            info("structure id ".$settingApprovalItem);
            info($userApprovalTokens);

            foreach (user_approval_id($settingApprovalItem) as $value) {
                \FRDHelper::setNewData(strtolower($request->company), $value, $data, 'medical_approval');
            }
    
            if(count($userApprovalTokens) > 0){
                $config = [
                    'title' => "Medical Reimbursement Approval",
                    'content' => strip_tags($params['text']),
                    'type' => 'medical_approval',
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
                'message' => $request->status== 1 ? 'Your medical reimbursement request has successfully submitted' : 'Your medical reimbursement request has successfully save draft'
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
            $approval = MedicalReimbursement::join('history_approval_medical as h', function ($join) use ($user) {
                $join->on('medical_reimbursement.id', '=', 'h.medical_reimbursement_id')
                    ->where('h.setting_approval_level_id', '=', DB::raw('(select min(setting_approval_level_id) from history_approval_medical where medical_reimbursement_id = medical_reimbursement.id and is_approved is null)'))
                    ->where('h.structure_organization_custom_id', '=', $user->structure_organization_custom_id);
            })
                ->where('medical_reimbursement.status', '=', 1)
                ->orderBy('created_at','DESC')
                ->select('medical_reimbursement.*');

                if($approval->count() == 0 && $cek_transfer_approve!=null){
                    $approval = MedicalReimbursement::join('history_approval_medical as h', function ($join) use ($user) {
                        $join->on('medical_reimbursement.id', '=', 'h.medical_reimbursement_id')
                        ->whereNotNull('h.is_approved');
                    })
                        ->where('medical_reimbursement.status', '=',2)
                        ->where('medical_reimbursement.is_transfer', '!=',1)
                        ->orderBy('created_at','DESC')
                        ->groupBy('medical_reimbursement.id')
                        ->select('medical_reimbursement.*');
                }
                else if($approval->count() > 0 && $cek_transfer_approve!=null){
                    $transfer = MedicalReimbursement::join('history_approval_medical as h', function ($join) use ($user) {
                        $join->on('medical_reimbursement.id', '=', 'h.medical_reimbursement_id')
                        ->whereNotNull('h.is_approved');
                    })
                        ->where('medical_reimbursement.status', '=',2)
                        ->where('medical_reimbursement.is_transfer', '!=',1)
                        ->orderBy('created_at','DESC')
                        ->groupBy('medical_reimbursement.id')
                        ->select('medical_reimbursement.*');
                    
                    $approval = $transfer->get()->merge($approval->get());
                    $approvalId = $approval->pluck('id');

                    $approval = MedicalReimbursement::join('history_approval_medical as h', function ($join) use ($user) {
                        $join->on('medical_reimbursement.id', '=', 'h.medical_reimbursement_id');
                    })
                        ->whereIn('medical_reimbursement.id', $approvalId)
                        ->orderBy('created_at','DESC')
                        ->groupBy('medical_reimbursement.id')
                        ->select('medical_reimbursement.*');

                }
        }else if($status == 'history'){
            $approval = MedicalReimbursement::join('history_approval_medical as h', function ($join) use ($user) {
                $join->on('medical_reimbursement.id', '=', 'h.medical_reimbursement_id')
                    ->whereNotNull('h.is_approved')
                    ->where('h.structure_organization_custom_id', '=', $user->structure_organization_custom_id);
            })
                ->orderBy('created_at','DESC')
                ->select('medical_reimbursement.*');

                if($approval->count() == 0 && $cek_transfer_approve!=null){
                    $approval = MedicalReimbursement::join('history_approval_medical as h', function ($join) use ($user) {
                        $join->on('medical_reimbursement.id', '=', 'h.medical_reimbursement_id')
                        ->whereNotNull('h.is_approved');
                    })
                        ->where('medical_reimbursement.is_transfer', '=',1)
                        ->orderBy('created_at','DESC')
                        ->groupBy('medical_reimbursement.id')
                        ->select('medical_reimbursement.*');
                }
                else if($approval->count() > 0 && $cek_transfer_approve!=null){
                    $transfer = MedicalReimbursement::join('history_approval_medical as h', function ($join) use ($user) {
                        $join->on('medical_reimbursement.id', '=', 'h.medical_reimbursement_id')
                        ->whereNotNull('h.is_approved');
                    })
                        ->where('medical_reimbursement.is_transfer', '=',1)
                        ->orderBy('created_at','DESC')
                        ->groupBy('medical_reimbursement.id')
                        ->select('medical_reimbursement.*');
                    
                    $approval = $transfer->get()->merge($approval->get());
                    $approvalId = $approval->pluck('id');

                    $approval = MedicalReimbursement::join('history_approval_medical as h', function ($join) use ($user) {
                        $join->on('medical_reimbursement.id', '=', 'h.medical_reimbursement_id');
                    })
                        ->whereIn('medical_reimbursement.id', $approvalId)
                        ->orderBy('created_at','DESC')
                        ->groupBy('medical_reimbursement.id')
                        ->select('medical_reimbursement.*');

                }
        }
        else if($status == 'all'){
            $approval = MedicalReimbursement::join('history_approval_medical as h', function ($join) use ($user) {
                $join->on('medical_reimbursement.id', '=', 'h.medical_reimbursement_id')
                    ->where('h.structure_organization_custom_id', '=', $user->structure_organization_custom_id);
            })
                ->orderBy('created_at','DESC')
                ->select('medical_reimbursement.*');

                if($approval->count() == 0 && $cek_transfer_approve!=null){
                    $approval = MedicalReimbursement::join('history_approval_medical as h', function ($join) use ($user) {
                        $join->on('medical_reimbursement.id', '=', 'h.medical_reimbursement_id');
                    })
                        ->orderBy('created_at','DESC')
                        ->groupBy('medical_reimbursement.id')
                        ->select('medical_reimbursement.*');
                }
                else if($approval->count() > 0 && $cek_transfer_approve!=null){
                    $transfer = MedicalReimbursement::join('history_approval_medical as h', function ($join) use ($user) {
                        $join->on('medical_reimbursement.id', '=', 'h.medical_reimbursement_id');
                    })
                        ->orderBy('created_at','DESC')
                        ->select('medical_reimbursement.*');
                    
                    $approval = $transfer->get()->merge($approval->get());
                    $approvalId = $approval->pluck('id');
    
                    $approval = MedicalReimbursement::join('history_approval_medical as h', function ($join) use ($user) {
                            $join->on('medical_reimbursement.id', '=', 'h.medical_reimbursement_id');
                        })
                            ->whereIn('medical_reimbursement.id', $approvalId)
                            ->orderBy('created_at','DESC')
                            ->groupBy('medical_reimbursement.id')
                            ->select('medical_reimbursement.*');
                }
        }
        $totalData = $approval->get()->count();
        $approval = $approval->paginate(10);
        $data = [
            'current_page' => $approval->currentPage(), // get current page number
            'total_page' => $approval->total() ? $approval->lastPage() : $approval->total(), // get last page number
            'total_data' => $totalData,
            'medicals' => MedicalResource::collection($approval)
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
            'medical.id'                        => 'required|exists:medical_reimbursement,id',
            'medical.details.*.id'              => 'required|exists:medical_reimbursement_form,id',
            'medical.details.*.nominal_approve' => "required|integer",
            'approval.note'                     => "required",
            'approval.is_approved'              => "required|in:1,0",
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()],401);
        }

        foreach ($request->medical['details'] as $detail){
            $form = MedicalReimbursementForm::where('id', $detail['id'])->first();
            if($form)
            {
                $form->nominal_approve    = $detail['nominal_approve'];
                if (isset($detail['note_approval'])){
                    $form->note_approval      = $detail['note_approval'];
                }
                $form->save();
            }
        }

        $medical            = MedicalReimbursement::find($request->medical['id']);
        $params             = getEmailConfig();
        $params['data']     = $medical;
        $params['value']    = $medical->historyApproval;
        $params['subject']  = get_setting('mail_name') . ' - Medical Reimbursement';
        $params['view']     = 'email.medical-approval-custom';


        $approval                = HistoryApprovalMedical::where(['medical_reimbursement_id'=>$medical->id,'structure_organization_custom_id'=> $user->structure_organization_custom_id])->first();
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
            $medical->status = 3;
            $params['total']    = total_medical_nominal($medical->id);
            $params['text']     = '<p><strong>Dear Sir/Madam '. $medical->user->name .'</strong>,</p> <p>  Submission of your Medical Reimbursement <strong style="color: red;">REJECTED</strong>.</p>';
            Config::set('database.default', 'mysql');
            if(!empty($medical->user->email)) {
                $params['email'] = $medical->user->email;
                $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                dispatch($job);
            }
            Config::set('database.default', $db);

            $notifTitle = "Medical Reimbursement";
            $notifType  = "medical";
            if($medical->user->firebase_token) {
                array_push($userApprovalTokens, $medical->user->firebase_token);
                $this->sentNotif($notifTitle, $params['text'], $notifType, $userApprovalTokens, $medical->id);
                $userApprovalTokens = [];
            }
            \FRDHelper::setNewData(strtolower($request->company), $medical->user->id, $medical, $notifType);
        }else if($approval->is_approved == 1){
            $lastApproval = $medical->historyApproval->last();
            if($approval->structure_organization_custom_id == $lastApproval->structure_organization_custom_id){
                $params['total']    = total_medical_nominal_approved($medical->id);
                $params['text']     = '<p><strong>Dear Sir/Madam '. $medical->user->name .'</strong>,</p> <p>  Submission of your Medical Reimbursement <strong style="color: green;">APPROVED</strong>.</p>';
                $medical->status = 2;
                Config::set('database.default', 'mysql');
                if(!empty($medical->user->email)) {
                    $params['email'] = $medical->user->email;
                    $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                    dispatch($job);
                }
                Config::set('database.default', $db);

                $notifTitle = "Medical Reimbursement";
                $notifType  = "medical";
                if($medical->user->firebase_token) {
                    array_push($userApprovalTokens, $medical->user->firebase_token);
                    $this->sentNotif($notifTitle, $params['text'], $notifType, $userApprovalTokens, $medical->id);
                    $userApprovalTokens = [];
                }
                \FRDHelper::setNewData(strtolower($request->company), $medical->user->id, $medical, $notifType);
                $userApproval = TransferSetting::get();
                if($userApproval) {
                    Config::set('database.default', 'mysql');
                    foreach ($userApproval as $key => $value) {
                        if ($value->user->email == "") continue;
                        $params['text']     = '<p><strong>Dear Sir/Madam '. $value->user->name .'</strong>,</p> <p> '. $medical->user->name .'  / '.  $medical->user->nik .' applied for Medical Reimbursement and currently waiting your payment.</p>';
                        $params['email'] = $value->user->email;
                        $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                        dispatch($job);

                        $notifTitle = "Transfer Medical Reimbursement";
                        $notifType  = "transfer_medical_approve";
                        if($value->user->firebase_token) {
                            array_push($userApprovalTokens, $value->user->firebase_token);
                            $this->sentNotif($notifTitle, $params['text'], $notifType, $userApprovalTokens, $medical->id);
                            $userApprovalTokens = [];
                        }
                        \FRDHelper::setNewData(strtolower($request->company), $value->user->id, $medical, $notifType);
                    }
                    Config::set('database.default', $db);
                }

            }else{
                $medical->status = 1;
                $nextApproval = HistoryApprovalMedical::where(['medical_reimbursement_id'=>$medical->id,'setting_approval_level_id'=> ($approval->setting_approval_level_id+1)])->first();
                if($nextApproval){
                    $userApproval = user_approval_custom($nextApproval->structure_organization_custom_id);
                    $params['total']    = total_medical_nominal($medical->id);

                    if($userApproval) {
                        Config::set('database.default', 'mysql');
                        foreach ($userApproval as $key => $value) {
                            if (empty($value->email)) continue;
                            $params['text']     = '<p><strong>Dear Sir/Madam '. $value->name .'</strong>,</p> <p> '. $medical->user->name .'  / '.  $medical->user->nik .' applied for Medical Reimbursement and currently waiting your approval.</p>';
                            $params['email'] = $value->email;
                            $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                            dispatch($job);
                        }
                        Config::set('database.default', $db);

                        $params['text'] = '<p> '. $medical->user->name .'  / '.  $medical->user->nik .' applied for Medical Reimbursement and currently waiting your approval.</p>';
                        $notifTitle = "Medical Reimbursement Approval";
                        $notifType  = "medical_approval";
                        $userApprovalTokens = user_approval_firebase_tokens($nextApproval->structure_organization_custom_id);
                        $userApprovalIds = user_approval_id($nextApproval->structure_organization_custom_id);
                        $this->sentNotif($notifTitle, $params['text'], $notifType, $userApprovalTokens, $medical->id);
                        $userApprovalTokens = [];
                    }
                }
            }
        }
        $medical->save();

        foreach ($userApprovalIds as $value) {
            \FRDHelper::setNewData(strtolower($request->company), $value, $medical, $notifType);
        }

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Medical Reimbursement Successfully Processed !',
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

        $data['medical'] = new MedicalResource(MedicalReimbursement::findOrFail($id));
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
            'medical.id'                        => 'required|exists:medical_reimbursement,id',
            'medical.disbursement'              => "required|in:Transfer,Next Payroll",
            'medical.is_transfer'               => "required|in:1,0",
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()],401);
        }

        $data = MedicalReimbursement::find($request->medical['id']);
        $data->is_transfer= $request->medical['is_transfer'];
        $data->disbursement = $request->medical['disbursement'];
        $data->is_transfer_by = auth()->user()->id;
        if($request->hasFile('transfer_proof'))
        {
            $image = $request->transfer_proof;
            $name =md5($request->medical['id'].'transfer_proof').'.'.$image->getClientOriginalExtension();
            $company_url = ($request->company ? $request->company : "umum") . '/';
            $destinationPath = public_path('storage/medical/transfer-proof/').$company_url;
            $image->move($destinationPath, $name);
            $data->transfer_proof = $company_url.$name;
        }
        $data->save();

        $userApprovalTokens = [];

        $db = Config::get('database.default','mysql');
        $params             = getEmailConfig();
        $params['data']     = $data;
        $params['value']    = $data->historyApproval;
        $params['subject']  = get_setting('mail_name') . ' - Transfer Medical Reimbursement';
        $params['view']     = 'email.payment-request-approval-custom';
        $params['total']    = total_medical_nominal_approved($data->id);
        if($data->disbursement=='Transfer'){
            $params['text']     = '<p><strong>Dear Sir/Madam '. $data->user->name .'</strong>,</p> <p>  Submission of your Medical Reimbursement has been transfered.</p>';
        }else{
            $params['text']     = '<p><strong>Dear Sir/Madam '. $data->user->name .'</strong>,</p> <p>  Submission of your Medical Reimbursement will be merged with the next payroll.</p>';
        }

        Config::set('database.default', 'mysql');

        if(!empty($data->user->email)) {
            $params['email'] = $data->user->email;
            $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
            dispatch($job);
        }
        Config::set('database.default', $db);
        
        $notifTitle = "Medical Reimbursement";
        $notifType  = "medical_reimbursement";

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
                'message' => 'Medical Reimbursement Transfer Successfully Processed !',
            ], 200);
    }
}
