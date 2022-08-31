<?php

namespace App\Http\Controllers\Api;

use App\Http\Middleware\ConnectionMiddleware;
use App\Http\Resources\AssetResource;
use App\Http\Resources\ExitInterviewResource;
use App\Models\Asset;
use App\Models\AssetTracking;
use App\Models\ExitInterview;
use App\Models\ExitInterviewAssets;
use App\Models\ExitInterviewReason;
use App\Models\HistoryApprovalExit;
use App\Models\SettingApprovalClearance;
use App\Models\CareerHistory;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class ExitInterviewController extends Controller
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
        $status = $request->input('status','[1,2,3]');
        if(!isJsonValid($status))
            return response()->json(['status' => 'error','message'=>'Status is invalid'], 404);
        $status = json_decode($status);
        $histories = ExitInterview::where(['user_id'=>$user->id])->whereIn('status',$status)->orderBy('created_at','DESC');
        $totalData = $histories->get()->count();
        $histories = $histories->paginate(10);
        $data = [
            'current_page' => $histories->currentPage(), // get current page number
            'total_page' => $histories->total() ? $histories->lastPage() : $histories->total(), // get last page number
            'total_data' => $totalData,
            'exit_interviews' => ExitInterviewResource::collection($histories)
        ];
        return response()->json(
            [
                'status' => 'success',
                'message' => 'Successfully',
                'data' => $data
            ], 200);
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
        info($request->all());
        //
        $user = Auth::user();
        $approval = $user->approval;
        if($approval == null){
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Your position is not defined yet. Please contact your admin!'
                ], 403);
        }else if(count($approval->itemsExit) == 0){
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Setting approval is not defined yet. Please contact your admin!'
                ], 403);
        }
        $validator = Validator::make($request->all(), [
            'resign_date'                 => "required",
            'last_work_date'              => "required",
            'exit_interview_reason'       => "required|exists:exit_interview_reason,id",
            'other_reason'                => "required_if:exit_interview_reason,12",

            'hal_berkesan'                => "required",
            'masukan'                     => "required",
            'assets.*.id'                 => 'required|exists:asset,id',
            'assets.*.asset_condition'    => "required|in:Good,Malfunction,Lost",
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()],401);
        }

        $data                       = new ExitInterview();
        $data->status               = 1;
        $data->user_id              = $user->id;
        $data->resign_date          = $request['resign_date'];
        $data->last_work_date       = $request['last_work_date'];

        $data->exit_interview_reason = $request['exit_interview_reason'];

        if(isset($request['other_reason']))
            $data->other_reason     = $request['other_reason'];
        if(isset($request['tujuan_perusahaan_baru']))
            $data->tujuan_perusahaan_baru   = $request['tujuan_perusahaan_baru'];
        if(isset($request['jenis_bidang_usaha']))
            $data->jenis_bidang_usaha       = $request['jenis_bidang_usaha'];

        $data->hal_berkesan         = $request['hal_berkesan'];
        $data->masukan              = $request['masukan'];
        $data->save();


        $historyApproval     = $user->approval->itemsExit;
        $settingApprovalItem = $historyApproval[0]->structure_organization_custom_id;
        foreach ($historyApproval as $level => $value) {
            # code...
            $history = new HistoryApprovalExit();
            $history->exit_interview_id                = $data->id;
            $history->setting_approval_level_id        = ($level+1);
            $history->structure_organization_custom_id = $value->structure_organization_custom_id;
            $history->save();
        }
        $historyApprov = HistoryApprovalExit::where('exit_interview_id',$data->id)->orderBy('setting_approval_level_id','asc')->get();

        $userApproval = user_approval_custom($settingApprovalItem);
        $params = getEmailConfig();
        $db = Config::get('database.default','mysql');
        $params['data']     = $data;
        $params['value']    = $historyApprov;
        $params['view']     = 'email.exit-approval-custom';
        $params['subject']  = get_setting('mail_name') . ' - Exit Interview';

        if($userApproval) {
            Config::set('database.default', 'mysql');
            foreach ($userApproval as $key => $value) {
                if ($value->email == "") continue;
                $params['email'] = $value->email;
                $params['text']  = '<p><strong>Dear Sir/Madam '. $value->name .'</strong>,</p> <p> '. $data->user->name .'  / '.  $data->user->nik .' applied for Exit Interview and currently waiting your approval.</p>';
                $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                dispatch($job);
            }
            Config::set('database.default', $db);
            $params['text']  = '<p> '. $data->user->name .'  / '.  $data->user->nik .' applied for Exit Interview and currently waiting your approval.</p>';
        }

        $userApprovalTokens = user_approval_firebase_tokens($settingApprovalItem);
        info("structure id ".$settingApprovalItem);
        info($userApprovalTokens);

        foreach (user_approval_id($settingApprovalItem) as $value) {
            \FRDHelper::setNewData(strtolower($request->company), $value, $data, 'exit_interview_approval');
        }

        if(count($userApprovalTokens) > 0){
            $config = [
                'title' => "Exit Interview Approval",
                'content' => strip_tags($params['text']),
                'type' => 'exit_interview_approval',
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

        // INVENTARIS
        if($request['assets'] != null && count($request['assets'])>0)
        {
            $user = \Auth::user();
            $pics = [];
            foreach($request['assets'] as $no => $item)
            {
                $dataAset                        = new ExitInterviewAssets();
                $dataAset->asset_id              = $item['id'];
                $dataAset->exit_interview_id     = $data->id;
                $dataAset->user_check            = 1;
                $dataAset->catatan_user          = isset($item['catatan_user'])?$item['catatan_user']:null;
                $dataAset->asset_condition       = $item['asset_condition'];
                $dataAset->save();

                $asset = Asset::find($item['id']);
                if($asset && !in_array($asset->asset_type->pic_department, $pics)){
                    array_push($pics,$asset->asset_type->pic_department);
                }
            }
            if($user->project_id != NULL)
            {
                $clearanceApproval = SettingApprovalClearance::join('users', 'users.id','=', 'setting_approval_clearance.user_created')->whereIn('nama_approval',$pics)->where('users.project_id', $user->project_id)->select('setting_approval_clearance.*')->get();
            }else{
                $clearanceApproval = SettingApprovalClearance::whereIn('nama_approval',$pics)->get();
            }

            $userApprovalTokens = [];
            $userApprovalIds = [];
            if(count($clearanceApproval)>0) {
                $params['data']     = $data;
                $params['subject']  = get_setting('mail_name') . ' - Exit Clearance';
                $params['view']     = 'email.clearance-approval-custom';
                Config::set('database.default', 'mysql');
                foreach ($clearanceApproval as $key => $value) {
                    if ($value->user) {
                        if ($value->user->email == "") continue;
                        $params['email'] = $value->user->email;
                        $params['text']  = '<p><strong>Dear Sir/Madam ' . $value->user->name . '</strong>,</p> <p> ' . $data->user->name . '  / ' . $data->user->nik . ' applied for Exit Clearance and currently waiting your approval.</p>';
                        $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                        dispatch($job);

                        if($value->user->firebase_token) {
                            array_push($userApprovalTokens, $value->user->firebase_token);
                        }
                        array_push($userApprovalIds, $value->user->id);
                    }
                }
                Config::set('database.default', $db);
                $params['text']  = '<p> ' . $data->user->name . '  / ' . $data->user->nik . ' applied for Exit Clearance and currently waiting your approval.</p>';
            }

            foreach ($userApprovalIds as $value) {
                \FRDHelper::setNewData(strtolower($request->company), $value, $data, 'exit_clearance_approval');
            }

            if(count($userApprovalTokens) > 0){
                $config = [
                    'title' => "Exit Clearance Approval",
                    'content' => strip_tags($params['text']),
                    'type' => 'exit_clearance_approval',
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
        else{
            $data->status_clearance = 1;
            $data->save();
        }

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Your exit interview request has successfully submitted'
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
            }else if(count($approval->itemsExit) == 0){
                return response()->json(['status' => 'error', 'message' => 'Setting approval is not defined yet. Please contact your admin!'], 403);
            }

            $countExit = ExitInterview::where('user_id',$request->user_id)->whereIn('status',['1,2'])->count();
            if($countExit > 0)
                return response()->json(['status' => 'error', 'message' => 'You have already requested exit interview!'], 403);

            $data['assets']                = AssetResource::collection(Asset::where('user_id',$user->id)->get());
        }else if($request->type == 'approval'){
            $data['pic'] = SettingApprovalClearance::where('user_id',Auth::user()->id)->pluck('nama_approval')->toArray();
        }
        $data['asset_conditions']      = ['Good','Malfunction','Lost'];
        $data['exit_reasons']          = ExitInterviewReason::select(['id','label'])->get();
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
        $data['exit_interview'] = new ExitInterviewResource(ExitInterview::findOrFail($id));
        return response()->json(
            [
                'status' => 'success',
                'message' => 'Successfully',
                'data' => $data
            ], 200);
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

    public function getApproval(Request $request){
        $status = $request->status?$request->status:"all";
        $user = Auth::user();
        $approval = null;
        if($status == 'ongoing') {
            $approval = ExitInterview::join('history_approval_exit as h', function ($join) use ($user) {
                $join->on('exit_interview.id', '=', 'h.exit_interview_id')
                    ->where('h.setting_approval_level_id', '=', DB::raw('(select min(setting_approval_level_id) from history_approval_exit where exit_interview_id = exit_interview.id and is_approved is null)'))
                    ->where('h.structure_organization_custom_id', '=', $user->structure_organization_custom_id);
            })
                ->where('exit_interview.status', '=', 1)
                ->orderBy('created_at','DESC')
                ->select('exit_interview.*');
        }else if($status == 'history'){
            $approval = ExitInterview::join('history_approval_exit as h', function ($join) use ($user) {
                $join->on('exit_interview.id', '=', 'h.exit_interview_id')
                    ->whereNotNull('h.is_approved')
                    ->where('h.structure_organization_custom_id', '=', $user->structure_organization_custom_id);
            })
                ->orderBy('created_at','DESC')
                ->select('exit_interview.*');
        }
        else if($status == 'all'){
            $approval = ExitInterview::join('history_approval_exit as h', function ($join) use ($user) {
                $join->on('exit_interview.id', '=', 'h.exit_interview_id')
                    ->where('h.structure_organization_custom_id', '=', $user->structure_organization_custom_id);
            })
                ->orderBy('created_at','DESC')
                ->select('exit_interview.*');
        }
        $totalData = $approval->get()->count();
        $approval = $approval->paginate(10);
        $data = [
            'current_page' => $approval->currentPage(), // get current page number
            'total_page' => $approval->total() ? $approval->lastPage() : $approval->total(), // get last page number
            'total_data' => $totalData,
            'exit_interviews' => ExitInterviewResource::collection($approval)
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
            'exit.id'                           => 'required|exists:exit_interview,id',
            'approval.note'                     => "required",
            'approval.is_approved'              => "required|in:1,0",
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()],401);
        }

        $exit               = ExitInterview::find($request->exit['id']);
        $params             = getEmailConfig();
        $params['data']     = $exit;
        $params['value']    = $exit->historyApproval;
        $params['subject']  = get_setting('mail_name') . ' - Exit Interview';
        $params['view']     = 'email.exit-approval-custom';


        $approval                = HistoryApprovalExit::where(['exit_interview_id'=>$exit->id,'structure_organization_custom_id'=> $user->structure_organization_custom_id])->first();
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
            // ExitInterviewAssets::where('exit_interview_id', $request->exit['id'])->whereNull('approval_check')->delete();
            // $exit->status_clearance = 1;
            $exit->status = 3;
            $params['text']     = '<p><strong>Dear Sir/Madam '. $exit->user->name .'</strong>,</p> <p>  Submission of your Exit Interview <strong style="color: red;">REJECTED</strong>.</p>';
            Config::set('database.default', 'mysql');
            if($exit->user->email && $exit->user->email != "") {
                $params['email'] = $exit->user->email;
                $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                dispatch($job);
            }
            Config::set('database.default', $db);

            $notifTitle = "Exit Interview";
            $notifType  = "exit_interview";
            if($exit->user->firebase_token) {
                array_push($userApprovalTokens, $exit->user->firebase_token);
            }
            array_push($userApprovalIds, $exit->user->id);
        }else if($approval->is_approved == 1){
            $lastApproval = $exit->historyApproval->last();
            if($approval->structure_organization_custom_id == $lastApproval->structure_organization_custom_id){
                $params['text']     = '<p><strong>Dear Sir/Madam '. $exit->user->name .'</strong>,</p> <p>  Submission of your Exit Interview <strong style="color: green;">APPROVED</strong>.</p>';
                $exit->status = 2;
                Config::set('database.default', 'mysql');
                if($exit->user->email && $exit->user->email != "") {
                    $params['email'] = $exit->user->email;
                    $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                    dispatch($job);
                }

                Config::set('database.default', $db);

                $notifTitle = "Exit Interview";
                $notifType  = "exit_interview";
                if($exit->user->firebase_token) {
                    array_push($userApprovalTokens, $exit->user->firebase_token);
                }
                array_push($userApprovalIds, $exit->user->id);
                $updateUser                = User::where('id', $exit->user_id)->first();
                $updateUser->inactive_date = $exit->last_work_date;
                $updateUser->non_active_date = $exit->resign_date;
                $updateUser->is_exit = 1;
                if ($updateUser->organisasi_status && $updateUser->organisasi_status != 'Permanent') {
                    $updateUser->end_date_contract = $exit->resign_date;
                    $career = CareerHistory::where('user_id', $updateUser->id)
                        ->whereDate('effective_date', '<=', \Carbon\Carbon::now()->format('Y-m-d'))
                        ->orderBy('effective_date', 'DESC')
                        ->orderBy('id', 'DESC')
                        ->first();
                    if (checkModule(26) || $career) {
                        if (!$career) {
                            $career = new CareerHistory();
                            $career->user_id = $updateUser->id;
                            $career->effective_date = $updateUser->join_date ?: \Carbon\Carbon::now()->format('Y-m-d');
                        }
                        $career->end_date = $updateUser->end_date_contract;
                        $career->save();
                    }
                } else {
                    $updateUser->status = 2;
                    $updateUser->resign_date = $exit->resign_date;
                }
                $updateUser->save();
                cleaning_future_career($updateUser);

                if($exit->status_clearance == 1){
                    $this->sendEmailToHRAdmin($exit,$db);
                }
            }else{
                $exit->status = 1;
                $nextApproval = HistoryApprovalExit::where(['exit_interview_id'=>$exit->id,'setting_approval_level_id'=> ($approval->setting_approval_level_id+1)])->first();
                if($nextApproval){
                    $userApproval = user_approval_custom($nextApproval->structure_organization_custom_id);

                    if($userApproval) {
                        Config::set('database.default', 'mysql');
                        foreach ($userApproval as $key => $value) {
                            if ($value->email == "") continue;
                            $params['text']     = '<p><strong>Dear Sir/Madam '. $value->name .'</strong>,</p> <p> '. $exit->user->name .'  / '.  $exit->user->nik .' applied for Exit Interview and currently waiting your approval.</p>';
                            $params['email'] = $value->email;
                            $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                            dispatch($job);
                        }
                        Config::set('database.default', $db);

                        $params['text'] = '<p> '. $exit->user->name .'  / '.  $exit->user->nik .' applied for Exit Interview and currently waiting your approval.</p>';
                        $notifTitle = "Exit Interview Approval";
                        $notifType  = "exit_interview_approval";
                        $userApprovalTokens = user_approval_firebase_tokens($nextApproval->structure_organization_custom_id);
                        $userApprovalIds = user_approval_id($nextApproval->structure_organization_custom_id);
                    }
                }
            }
        }
        $exit->save();

        foreach ($userApprovalIds as $value) {
            \FRDHelper::setNewData(strtolower($request->company), $value, $exit, $notifType);
        }

        if(count($userApprovalTokens) > 0){
            $config = [
                'title' => $notifTitle,
                'content' => strip_tags($params['text']),
                'type' => $notifType,
                'firebase_token' => $userApprovalTokens
            ];
            $notifData = [
                'id' => $exit->id
            ];
            $db = Config::get('database.default', 'mysql');
            Config::set('database.default', 'mysql');
            dispatch((new \App\Jobs\SendPushTokens($notifData,$config))->onQueue('push'));
            Config::set('database.default', $db);
        }

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Exit Interview Successfully Processed !',
            ], 200);
    }

    public function getApprovalClearance(Request $request){
        $status = $request->status?$request->status:"all";
        $user = Auth::user();
        $approval = null;
        if($status == 'ongoing') {
            $approval = ExitInterview::whereRaw("(select count(*) from exit_interview_assets ea
                    join asset a on ea.asset_id = a.id
                    join asset_type as at on a.asset_type_id = at.id
                    where exit_interview.id = ea.exit_interview_id and ea.approval_check is null and exit_interview.status_clearance = 0
                           and at.pic_department in (SELECT sc.nama_approval from setting_approval_clearance sc where sc.user_id = ?)) > ? ORDER BY exit_interview.created_at DESC",[$user->id, 0]);
        }else if($status == 'history'){
            $approval = ExitInterview::whereRaw("(select count(*) from exit_interview_assets ea
                    join asset a on ea.asset_id = a.id
                    join asset_type as at on a.asset_type_id = at.id
                    where exit_interview.id = ea.exit_interview_id and (ea.approval_check is not null or (ea.approval_check is null and exit_interview.status_clearance = 2))
                           and at.pic_department in (SELECT sc.nama_approval from setting_approval_clearance sc where sc.user_id = ?)) > ? ORDER BY exit_interview.created_at DESC",[$user->id, 0]);
        }
        else if($status == 'all'){
            $approval = ExitInterview::whereRaw("(select count(*) from exit_interview_assets ea
                    join asset a on ea.asset_id = a.id
                    join asset_type as at on a.asset_type_id = at.id
                    where exit_interview.id = ea.exit_interview_id 
                    and at.pic_department in (SELECT sc.nama_approval from setting_approval_clearance sc where sc.user_id = ?)) > ? ORDER BY exit_interview.created_at DESC",[$user->id, 0]);
        }
        $totalData = $approval->get()->count();
        $approval = $approval->paginate(10);
        $data = [
            'current_page' => $approval->currentPage(), // get current page number
            'total_page' => $approval->total() ? $approval->lastPage() : $approval->total(), // get last page number
            'total_data' => $totalData,
            'exit_interviews' => ExitInterviewResource::collection($approval)
        ];
        return response()->json(
            [
                'status' => 'success',
                'message' => 'Successfully',
                'data' => $data
            ], 200);
    }

    public function approveClearance(Request $request){
        info($request->all());
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'exit.id'                     => "required|exists:exit_interview,id",
            'assets'                      => "array|required",
            'assets.*.id'                 => 'required|exists:exit_interview_assets,id',
            'assets.*.asset_condition'    => "required|in:Good,Malfunction,Lost",
            'assets.*.approval_check'     => "required|in:1,0",
            'approval.is_approved'        => "required|in:1,0",
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()],401);
        }
        if ($request->approval['is_approved'] == 1) {
            foreach($request['assets'] as $no => $item)
            {
                $dataAset                  = ExitInterviewAssets::where('id', $item['id'])->first();
                $dataAset->approval_check  = isset($item['approval_check']) ? 1 : 0;
                $dataAset->catatan         = $item['catatan'];
                $dataAset->asset_condition = isset($item['asset_condition']) ? $item['asset_condition'] : null;

                if($dataAset->approval_check == 1)
                {
                    $dataAset->approval_id     = $user->id;
                    $dataAset->date_approved   = date('Y-m-d H:i:s');

                    $aset                   = Asset::where('id',$dataAset->asset_id)->first();
                    $aset->user_id          = $user->id;
                    $aset->handover_date    = date('Y-m-d H:i:s');
                    $aset->assign_to        = 'Office Inventory/Idle';
                    $aset->asset_condition = $dataAset->asset_condition;
                    $aset->save();

                    $tracking                   = new AssetTracking();
                    $tracking->asset_number     = $aset->asset_number;
                    $tracking->asset_name       = $aset->asset_name;
                    $tracking->asset_type_id    = $aset->asset_type_id;
                    $tracking->asset_sn         = $aset->asset_sn;
                    $tracking->purchase_date    = date('Y-m-d', strtotime($aset->purchase_date));
                    $tracking->asset_condition  = $aset->asset_condition;
                    $tracking->assign_to        = $aset->assign_to;
                    $tracking->user_id          = $aset->user_id;
                    $tracking->asset_id         = $aset->id;
                    $tracking->status_mobil         = $aset->status_mobil;
                    $tracking->remark               = $aset->remark;
                    $tracking->save();
                }
                $dataAset->save();
            }

            $remaining_asset = ExitInterviewAssets::where('exit_interview_id',$request->exit['id'])->where(function($table){
                $table->where('approval_check','<',1)->orWhereNull('approval_check');
            })->count();
            if($remaining_asset == 0){
                $exit = ExitInterview::find($request->exit['id']);
                $exit->status_clearance = 1;
                $exit->save();


                $db = Config::get('database.default','mysql');
                $data = $exit;

                // send email

                if($data->user->email) {
                    info("Sending email clearance");
                    $params             = getEmailConfig();
                    $params['email']    = $data->user->email;
                    $params['data']     = $data;
                    $params['assets']   = ExitInterviewAssets::where('exit_interview_id',$request->exit['id'])->get();
                    $params['text']     = '<p><strong>Dear Sir/Madam '. $data->user->name .'</strong>,</p> <p>  Submission of your Exit Clearance <strong style="color: green;">APPROVED</strong>.</p>';
                    $params['view']     = 'email.exit-clearance';
                    $params['subject']  = get_setting('mail_name') . ' - Exit Clearance';
                    Config::set('database.default', 'mysql');
                    $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                    dispatch($job);
                    Config::set('database.default', $db);
                }

                \FRDHelper::setNewData(strtolower($request->company), $data->user->id, $data, 'exit_clearance');

                if ($data->user->firebase_token) {
                    $config = [
                        'title' => "Exit Clearance",
                        'content' => strip_tags('<p><strong>Dear Sir/Madam '. $data->user->name .'</strong>,</p> <p>  Submission of your Exit Clearance <strong style="color: green;">APPROVED</strong>.</p>'),
                        'type' => 'exit_clearance',
                        'firebase_token' => [$data->user->firebase_token]
                    ];
                    $notifData = [
                        'id' => $data->id
                    ];
                    info($data->user->firebase_token);
                    $db = Config::get('database.default', 'mysql');
                    Config::set('database.default', 'mysql');
                    dispatch((new \App\Jobs\SendPushTokens($notifData,$config))->onQueue('push'));
                    Config::set('database.default', $db);
                }

                if($exit->status == 2){
                    $this->sendEmailToHRAdmin($exit,$db);
                }
            }
        } else if ($request->approval['is_approved'] == 0) {
            foreach($request['assets'] as $no => $item)
            {
                $dataAset                  = ExitInterviewAssets::where('id', $item['id'])->first();
                $dataAset->approval_check  = 0;
                $dataAset->catatan         = $item['catatan'];
                $dataAset->asset_condition = isset($item['asset_condition']) ? $item['asset_condition'] : null;
                $dataAset->approval_id     = $user->id;
                $dataAset->date_approved   = date('Y-m-d H:i:s');
                $dataAset->save();
            }

            $exit = ExitInterview::find($request->exit['id']);
            $exit->status_clearance = 2;
            $exit->save();

            $db = Config::get('database.default','mysql');
            $data = $exit;

            // send email

            if($data->user->email) {
                info("Sending email clearance");
                $params             = getEmailConfig();
                $params['email']    = $data->user->email;
                $params['data']     = $data;
                $params['assets']   = ExitInterviewAssets::where('exit_interview_id',$request->exit['id'])->get();
                $params['text']     = '<p><strong>Dear Sir/Madam '. $data->user->name .'</strong>,</p> <p>  Submission of your Exit Clearance <strong style="color: red;">REJECTED</strong>.</p>';
                $params['view']     = 'email.exit-clearance';
                $params['subject']  = get_setting('mail_name') . ' - Exit Clearance';
                Config::set('database.default', 'mysql');
                $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                dispatch($job);
                Config::set('database.default', $db);
            }

            \FRDHelper::setNewData(strtolower($request->company), $data->user->id, $data, 'exit_clearance');

            if ($data->user->firebase_token) {
                $config = [
                    'title' => "Exit Clearance",
                    'content' => strip_tags('<p><strong>Dear Sir/Madam '. $data->user->name .'</strong>,</p> <p>  Submission of your Exit Clearance <strong style="color: red;">REJECTED</strong>.</p>'),
                    'type' => 'exit_clearance',
                    'firebase_token' => [$data->user->firebase_token]
                ];
                $notifData = [
                    'id' => $data->id
                ];
                info($data->user->firebase_token);
                $db = Config::get('database.default', 'mysql');
                Config::set('database.default', 'mysql');
                dispatch((new \App\Jobs\SendPushTokens($notifData,$config))->onQueue('push'));
                Config::set('database.default', $db);
            }
        }

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Exit Clearance Successfully Processed !',
            ], 200);
    }

    private function sendEmailToHRAdmin($exit,$db){
        $params             = getEmailConfig();
        $params['data']     = $exit;
        $params['value']    = $exit->historyApproval;
        $params['assets']   = ExitInterviewAssets::where('exit_interview_id',$exit->id)->get();
        $params['subject']  = get_setting('mail_name') . ' - Exit Interview Confirmation';
        $params['view']     = 'email.exit-interview-clearance';
        $users = User::whereHas('modules',function ($q){
            $q->where('product_id',3);
        })->get();
        if($users) {
            Config::set('database.default', 'mysql');
            foreach ($users as $key => $value) {
                if($value->email && $value->email != ""){
                    $params['text'] = '<p><strong>Dear Sir/Madam ' . $value->name . '</strong>,</p> <p>  Submission of '.$exit->user->name.' Exit Interview & Clearance has been <strong style="color: green;">APPROVED</strong>.</p>';
                    $params['email'] = $value->email;
                    $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                    dispatch($job);
                }
            }
            Config::set('database.default', $db);
        }
    }
}
