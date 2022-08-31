<?php

namespace App\Http\Controllers\Administrator;

use App\Models\HistoryApprovalRecruitment;
use App\Models\RecruitmentInterviewer;
use App\Models\RecruitmentRequest;
use App\Models\RecruitmentRequestDetail;
use App\Models\SettingApproval;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use DataTables;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use App\Models\StructureOrganizationCustom; 
use App\Models\OrganisasiDivision;
use App\Models\OrganisasiPosition;
use Carbon\Carbon;

class RecruitmentRequestController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
        $this->middleware('module:3');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $user = \Auth::user();
        if($user->project_id != NULL)
        {
            $params['division'] = OrganisasiDivision::where('project_id', $user->project_id)->select('organisasi_division.*')->get();
            $params['position'] = OrganisasiPosition::where('project_id', $user->project_id)->select('organisasi_position.*')->get();
        } else
        {
            $params['division'] = OrganisasiDivision::all();
            $params['position'] = OrganisasiPosition::all();
        }
        $params['structure'] = getStructureName();
        return view('administrator.recruitment-request.index')->with($params);
    }

    public function table(Request $request){
        $user = Auth::user();
        $status = $request->input('status', '-1');
        $requests = RecruitmentRequest::leftJoin('cabang as c','c.id','=','recruitment_request.branch_id')
            ->leftJoin('structure_organization_custom as so','recruitment_request.structure_organization_custom_id','=','so.id')
            ->leftJoin('organisasi_position as op','so.organisasi_position_id','=','op.id')
            ->leftJoin('organisasi_division as od','so.organisasi_division_id','=','od.id')
            ->leftJoin('organisasi_title as ot', 'so.organisasi_title_id','=','ot.id')
            ->leftJoin('users as ro','recruitment_request.requestor_id','=','ro.id')
            ->leftJoin('users as re','recruitment_request.recruiter_id','=','re.id')
            ->join('recruitment_request_detail as rd','recruitment_request.id','=','rd.recruitment_request_id')
            ->groupBy('recruitment_request.id')
            ->where(['recruitment_request.project_id'=>$user->project_id])
            ->select(['recruitment_request.*','ro.name as requestor','re.name as recruiter',\DB::raw('group_concat(rd.recruitment_type_id) as target'),\DB::raw('group_concat(rd.status_post) as target_post'),\DB::raw('DATE_FORMAT(recruitment_request.created_at, "%d %M %Y") as request_date'),'c.name as branch']);
        if($status == '1'){
            $requests->whereNull('recruitment_request.approval_hr');
        }else if($status == '2'){
            $requests->whereNull('recruitment_request.approval_user')->where('recruitment_request.approval_hr','1');
        }else if($status == '3'){
            $requests->where(['recruitment_request.approval_hr'=>'1','recruitment_request.approval_user'=>'1']);
        }
        else if($status == '4'){
            $requests->where(function($q){
                $q->where('recruitment_request.approval_hr','0')
                ->orWhere('recruitment_request.approval_user','0');
            });
        }

        if(count(request()->all())) {
            \Session::put('rr-status', request()->status);
            \Session::put('rr-position_id', request()->position_id);
            \Session::put('rr-division_id', request()->division_id);
            \Session::put('rr-name', request()->name);
        }

        $position_id        = \Session::get('rr-position_id');
        $division_id        = \Session::get('rr-division_id');
        $name               = \Session::get('rr-name');

        if (!empty($name)) {
            $requests = $requests->where(function ($table) use($name) {
                $table->where('ro.name', 'LIKE', '%' . $name . '%')
                    ->orWhere('ro.nik', 'LIKE', '%' . $name . '%')
                    ->orWhere('re.name', 'LIKE', '%' . $name . '%')
                    ->orWhere('re.nik', 'LIKE', '%' . $name . '%');
            });
        }

        if((!empty($division_id)) and (empty($position_id))) 
        {   
            $requests = $requests->where('so.organisasi_division_id',$division_id);
        }
        if((!empty($position_id)) and (empty($division_id)))
        {   
            $requests = $requests->where('so.organisasi_position_id',$position_id);
        }
        if((!empty($position_id)) and (!empty($division_id)))
        {
            $requests = $requests->where('so.organisasi_position_id',$position_id)->where('so.organisasi_division_id',$division_id);
        }

        if(request()->reset == 1)
        {
            \Session::forget('rr-status');
            \Session::forget('rr-position_id');
            \Session::forget('rr-division_id');
            \Session::forget('rr-name');

            return redirect()->route('administrator.recruitment-request.index');
        }

        return DataTables::of($requests)
//            ->addColumn('action', function ($request) {
//                return '<a href="'.route('administrator.recruitment-request.edit', $request->id).'"> <button class="btn btn-info btn-xs m-r-5"><i class="fa fa-edit"></i> detail</button></a>';
//            })
            ->make(true);
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
        $param['recruitment'] = RecruitmentRequest::find($id);
        if(!$param['recruitment'])
            return redirect()->back()->with('message-error', 'Recruitment Request is not found');
        return view('administrator.recruitment-request.edit')->with($param);
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
        $recruitment = RecruitmentRequest::find($id);
        if(!$recruitment)
            return response()->json(['status' => 'failed', 'message' => 'Recruitment request is not found']);
        
        $validator = Validator::make($request->all(), [
            // 'reason' => 'required',
            // 'grade' => 'required',
            // 'branch' => 'required|exists:cabang,id',
            // 'min_salary' => 'required|integer',
            // 'max_salary' => 'required|integer',
            'job_desc' => 'required',
            'job_requirement' => 'required',
            'benefit' => 'required',
            // 'employment_type' => 'required|integer',
            // 'contract_duration' => 'bail|required_if:employment_type,2,3,4,5|nullable|integer|min:1',
            // 'headcount' => 'required|integer|min:1',
            // 'expected_date' => 'required|date',
            // 'recruitment_type' => 'required|array',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()]);
        }
        if($recruitment->approval_hr == null && $request->employment_type != 1 && (!isset($request->contract_duration) || $request->contract_duration == null || $request->contract_duration <= 0)){
            return response()->json(['status' => 'failed', 'message' => 'field contract duration is incorrect']);
        }

        $interviewers = [];
        foreach ($request->interviewer as $interviewer){
            if($interviewer!=null) {
                array_push($interviewers,$interviewer);
            }
        }
        $msg = 'saved';
        if(isset($request->approve)){
            if($request->approve == 1){
                if($request->recruiter == null)
                    return response()->json(['status' => 'failed', 'message' => 'Recruiter should be choosen!']);
                if(count($interviewers) == 0)
                    return response()->json(['status' => 'failed', 'message' => 'Interviewer(s) should be choosen!']);

                $approval = $recruitment->requestor->approval;
                if($approval == null){
                    return response()->json(['status' => 'failed', 'message' => 'Your position is not defined yet. Please contact your admin!']);
                }else if(count($approval->itemsRecruitment) == 0){
                    return response()->json(['status' => 'failed', 'message' => 'Setting approval is not defined yet. Please contact your admin!']);
                }
                $recruitment->status = 1;
                $msg = 'approved';
            }
            else{
                $recruitment->status = 3;
                $msg = 'rejected';
            }
            $recruitment->approval_hr                  = $request->approve;
            $recruitment->approval_hr_date             = date('Y-m-d H:i:s');
        }
        else if($recruitment->approval_hr == 1){
            if($request->job_category == null)
                return response()->json(['status' => 'failed', 'message' => 'Job category should be choosen!']);
            if($request->recruiter == null)
                return response()->json(['status' => 'failed', 'message' => 'Recruiter should be choosen!']);
            if(count($interviewers) == 0)
                return response()->json(['status' => 'failed', 'message' => 'Interviewer(s) should be choosen!']);
        }

        if($recruitment->approval_hr == 1){
            $recruitment->job_desc = htmlspecialchars($request->job_desc);
            $recruitment->job_requirement = htmlspecialchars($request->job_requirement);
            $recruitment->benefit = htmlspecialchars($request->benefit);
            $recruitment->job_category_id = $request->job_category;
            $recruitment->recruiter_id    = $request->recruiter;
            $recruitment->save();

            foreach ($interviewers as $interviewer){
                $param = ['recruitment_request_id' => $id, 'user_id' => $interviewer];
                $detail = RecruitmentInterviewer::where($param)->first();
                if (!$detail) {
                    $detail = new RecruitmentInterviewer();
                    $detail->recruitment_request_id = $recruitment->id;
                    $detail->user_id = $interviewer;
                    $detail->save();
                }
            }
            RecruitmentInterviewer::where('recruitment_request_id',$id)->whereNotIn('user_id',$interviewers)->delete();
        } else {
            $recruitment->branch_id = $request->branch;
            $recruitment->grade_id = $request->grade;
            $recruitment->subgrade_id = isset($request->subgrade) ? $request->subgrade : null;
            $recruitment->min_salary = $request->min_salary;
            $recruitment->max_salary = $request->max_salary;
            $recruitment->job_desc = htmlspecialchars($request->job_desc);
            $recruitment->job_requirement = htmlspecialchars($request->job_requirement);
            $recruitment->benefit = htmlspecialchars($request->benefit);
            $recruitment->reason = $request->reason;
            $recruitment->headcount = $request->headcount;
            $recruitment->expected_date = date('Y-m-d', strtotime($request->expected_date));
            $recruitment->employment_type = $request->employment_type;
            $recruitment->contract_duration = ($request->employment_type != 1) ? $request->contract_duration : null;
            $recruitment->additional_information = $request->additional_information;
            $recruitment->approval_hr_user_id = Auth::user()->id;
    
            foreach ($request->recruitment_type as $type){
                $param = ['recruitment_request_id'=>$id,'recruitment_type_id'=>$type];
                $detail = RecruitmentRequestDetail::where($param)->first();
                if(!$detail) {
                    $detail = new RecruitmentRequestDetail();
                    $detail->recruitment_request_id = $recruitment->id;
                    $detail->recruitment_type_id    = $type;
                    $detail->save();
                }
            }
    
            RecruitmentRequestDetail::where('recruitment_request_id',$id)->whereNotIn('recruitment_type_id',$request->recruitment_type)->delete();
            
            $recruitment->job_category_id = $request->job_category;
            $recruitment->recruiter_id    = $request->recruiter;
            $recruitment->save();
    
    
    
            foreach ($interviewers as $interviewer){
                $param = ['recruitment_request_id' => $id, 'user_id' => $interviewer];
                $detail = RecruitmentInterviewer::where($param)->first();
                if (!$detail) {
                    $detail = new RecruitmentInterviewer();
                    $detail->recruitment_request_id = $recruitment->id;
                    $detail->user_id = $interviewer;
                    $detail->save();
                }
            }
    
    
            RecruitmentInterviewer::where('recruitment_request_id',$id)->whereNotIn('user_id',$interviewers)->delete();
    
    
            if(isset($request->approve)){
                $params = getEmailConfig();
    
                $notifTitle = "";
                $notifType = "";
                $userApprovalTokens = [];
                $userApprovalIds = [];
    
                if($request->approve == '1') {
                    $checkApproval = $recruitment->requestor->approval;
                    $settingApprovalItem = $checkApproval->level1Recruitment->structure_organization_custom_id;
    
                    $historyApproval = $checkApproval->itemsRecruitment;
                    foreach ($historyApproval as $key => $value) {
                        # code...
                        $history = new HistoryApprovalRecruitment();
                        $history->recruitment_request_id = $recruitment->id;
                        $history->setting_approval_level_id = $value->setting_approval_level_id;
                        $history->structure_organization_custom_id = $value->structure_organization_custom_id;
                        $history->save();
                    }
                    $historyApprov = HistoryApprovalRecruitment::where('recruitment_request_id', $recruitment->id)->get();
    
                    $userApproval = user_approval_custom($settingApprovalItem);
                    Config::set('database.default', 'mysql');
                    foreach ($userApproval as $key => $value) {
                        if ($value->email == "") continue;
                        $params['view'] = 'email.recruitment-request-approval';
                        $params['subject'] = get_setting('mail_name') . ' - Recruitment Request';
                        $params['email'] = $value->email;
                        $params['data'] = $recruitment;
                        $params['value'] = $historyApprov;
                        $params['text'] = '<p><strong>Dear Sir/Madam ' . $value->name . '</strong>,</p> <p> ' . $recruitment->requestor->name . '  / ' . $recruitment->requestor->nik . ' applied for Recruitment Request and currently waiting your approval.</p>';
                        $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                        dispatch($job);
                    }
                    Config::set('database.default', session('db_name', 'mysql'));
                    
                    $params['text'] = '<p> ' . $recruitment->requestor->name . '  / ' . $recruitment->requestor->nik . ' applied for Recruitment Request and currently waiting your approval.</p>';
                    $notifTitle = "Recruitment Request Approval";
                    $notifType = "recruitment_approval";
                    $userApprovalTokens = user_approval_firebase_tokens($settingApprovalItem);
                    $userApprovalIds = user_approval_id($settingApprovalItem);
                }
                else if($request->approve == '0'){
                    Config::set('database.default', 'mysql');
                    $params['view'] = 'email.recruitment-request-approval';
                    $params['subject'] = get_setting('mail_name') . ' - Recruitment Request';
                    $params['email'] = $recruitment->requestor->email;
                    $params['data'] = $recruitment;
                    $params['value'] = [];
                    $params['text'] = '<p><strong>Dear Sir/Madam '. $recruitment->requestor->name .'</strong>,</p> <p>  Submission of your Recruitment Request <strong style="color: red;">REJECTED</strong>.</p>';
                    $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                    dispatch($job);
                    Config::set('database.default', session('db_name', 'mysql'));
    
                    $notifTitle = "Recruitment Request";
                    $notifType = "recruitment";
                    if ($recruitment->requestor->firebase_token) {
                        array_push($userApprovalTokens, $recruitment->requestor->firebase_token);
                    }
                    array_push($userApprovalIds, $recruitment->requestor->id);
                }
                
                foreach ($userApprovalIds as $value) {
                    \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $value, $recruitment, $notifType);
                }
    
                if (count($userApprovalTokens) > 0) {
                    $config = [
                        'title' => $notifTitle,
                        'content' => strip_tags($params['text']),
                        'type' => $notifType,
                        'firebase_token' => $userApprovalTokens,
                    ];
                    $notifData = [
                        'id' => $recruitment->id,
                    ];
                    $db = Config::get('database.default', 'mysql');
                    Config::set('database.default', 'mysql');
                    dispatch((new \App\Jobs\SendPushTokens($notifData, $config))->onQueue('push'));
                    Config::set('database.default', $db);
                }
            }
        }

        return response()->json(['status' => 'success', 'message' => "Recruitment request is $msg",'data'=>$recruitment->id]);
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

    public function updatePost(Request $request, $id){
        $detail = RecruitmentRequestDetail::find($id);
        if(!$detail) {
            return response()->json(['status' => 'failed', 'message' => "Data is not found"]);
        }
        if(isset($request->status)) {
            if ($request->status == 1 && $detail->expired_date != null && Carbon::parse($detail->expired_date)->startOfDay()->lte(Carbon::now()->startOfDay())) {
                return response()->json(['status' => 'failed', 'message' => "Minimum expired date is tomorrow if not empty"]);
            }
            $detail->status_post = $request->status;
            if ($request->status == 1)
                $detail->posting_date = date('Y-m-d H:i:s');
            $detail->save();
            if ($detail->status_post == 1 && $detail->recruitment_type_id == 1) {

                foreach (\App\User::whereNull('inactive_date')->orWhere('inactive_date', '>', \Carbon\Carbon::now())->pluck('id')->toArray() as $value) {
                    \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $value, $detail->recruitment, 'internal');
                }

                $config = [
                    'title' => 'Internal Recruitment',
                    'body' => strip_tags('Position '.$detail->recruitment->job_position.' is now open'),
                    'type' => 'internal',
                    'app_type' => config('constants.apps.emhr_mobile_attendance'),
                    'topic' => session('company_url','umum'),
                    'data' => $detail->recruitment,
                ];
                $db = Config::get('database.default', 'mysql');
                Config::set('database.default', 'mysql');
                dispatch((new \App\Jobs\SendPushAll($config))->onQueue('push'));
                $config['app_type'] = config('constants.apps.emhr_mobile');
                dispatch((new \App\Jobs\SendPushAll($config))->onQueue('push'));
                Config::set('database.default', $db);
            }
            return response()->json(['status' => 'success', 'message' => "Post status is updated successfully!"]);
        }
        else if(isset($request->show_salary_range)){
            $detail->show_salary_range = $request->show_salary_range;
            $detail->save();
            return response()->json(['status' => 'success', 'message' => "Post show salary range is updated successfully!"]);
        }
        else if(isset($request->date) || $request->date == ""){
            $detail->expired_date = $request->date ? Carbon::parse($request->date) : null;
            $detail->last_posted_date = $request->date ? Carbon::parse($request->date)->subDay() : null;
            $detail->save();
            return response()->json(['status' => 'success', 'message' => "Expired date is updated successfully!"]);
        }
    }
}
