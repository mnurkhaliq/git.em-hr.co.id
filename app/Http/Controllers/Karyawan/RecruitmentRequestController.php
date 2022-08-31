<?php

namespace App\Http\Controllers\Karyawan;

use App\Models\RecruitmentRequest;
use App\Models\RecruitmentRequestDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use DataTables;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class RecruitmentRequestController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware(function ($request, $next) {
            // if(checkModule(27) && !checkManager()){
            //     return redirect()->back()->with('message-error', 'You are not manager');
            // }
            return $next($request);
        });
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('karyawan.recruitment-request.index');
    }

    public function table(){
        $user = Auth::user();
        $requests = RecruitmentRequest::leftJoin('cabang as c','c.id','=','recruitment_request.branch_id')
            ->leftJoin('structure_organization_custom as so','recruitment_request.structure_organization_custom_id','=','so.id')
            ->leftJoin('organisasi_position as op','so.organisasi_position_id','=','op.id')
            ->leftJoin('organisasi_division as od','so.organisasi_division_id','=','od.id')
            ->join('recruitment_request_detail as rd','recruitment_request.id','=','rd.recruitment_request_id')
            ->groupBy('recruitment_request.id')
            ->where(['recruitment_request.project_id'=>$user->project_id, 'recruitment_request.requestor_id'=>$user->id])
            ->select(['recruitment_request.*',\DB::raw('group_concat(rd.recruitment_type_id) as target'),\DB::raw('group_concat(rd.status_post) as target_post'),\DB::raw('DATE_FORMAT(recruitment_request.created_at, "%d %M %Y") as request_date'),'c.name as branch']);
        return DataTables::of($requests)
            // ->addColumn('action', function ($request) {
            //     return '<a href="'.route('karyawan.recruitment-request.edit', $request->id).'"> <button class="btn btn-info btn-xs m-r-5"><i class="fa fa-search-plus"></i> detail</button></a>';
            // })
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
        return view('karyawan.recruitment-request.create');
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
        $validator = Validator::make($request->all(),[
            'requestor' => 'required|exists:users,id',
            'reason' => 'required',
            'position' => 'nullable|exists:structure_organization_custom,id',
            'grade' => 'nullable|exists:grade,id',
            'subgrade' => 'nullable|exists:sub_grade,id',
            'branch' => 'required|exists:cabang,id',
            'min_salary' => 'nullable|integer',
            'max_salary' => 'nullable|integer',
            'job_position' => 'required',
            'job_desc' => 'required',
            'job_requirement' => 'required',
            'benefit' => 'required',
            'employment_type' => 'required|integer',
            'contract_duration' => 'bail|required_if:employment_type,2,3,4,5,6|nullable|integer|min:1',
            'headcount' => 'required|integer|min:1',
            'expected_date' => 'required|date',
            'recruitment_type' => 'required|array',
        ]);
//        print_r($request->all());
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()]);
        }
        if($request->employment_type != 1 && (!isset($request->contract_duration) || $request->contract_duration == null || $request->contract_duration <= 0)){
            return response()->json(['status' => 'failed', 'message' => 'field contract duration is incorrect']);
        }

        $recruitment = new RecruitmentRequest();
        $recruitment->request_number                   = getRecruitmentId();
        $recruitment->structure_organization_custom_id = $request->position;
        $recruitment->requestor_id                     = $request->requestor;
        $recruitment->branch_id                        = $request->branch;
        $recruitment->grade_id                         = $request->grade;
        $recruitment->subgrade_id                      = isset($request->subgrade)?$request->subgrade:null;
        $recruitment->min_salary                       = $request->min_salary;
        $recruitment->max_salary                       = $request->max_salary;
        $recruitment->job_position                     = $request->job_position;
        $recruitment->job_desc                         = htmlspecialchars($request->job_desc);
        $recruitment->job_requirement                  = htmlspecialchars($request->job_requirement);
        $recruitment->benefit                          = htmlspecialchars($request->benefit);
        $recruitment->reason                           = $request->reason;
        $recruitment->headcount                        = $request->headcount;
        $recruitment->expected_date                    = date('Y-m-d' , strtotime($request->expected_date));
        $recruitment->employment_type                  = $request->employment_type;
        $recruitment->contract_duration                = ($request->employment_type != 1)?$request->contract_duration:null;
        $recruitment->additional_information           = $request->additional_information;
        $recruitment->project_id                       = Auth::user()->project_id;
        $recruitment->status                           = 4;

        $recruitment->save();

        foreach ($request->recruitment_type as $type){
            $detail = new RecruitmentRequestDetail();
            $detail->recruitment_request_id = $recruitment->id;
            $detail->recruitment_type_id    = $type;
            $detail->save();
        }

        $admins = getAdminByModule(27);
        $params = getEmailConfig();
        Config::set('database.default', 'mysql');
        foreach ($admins as $key => $value) {
            if ($value->email == "") continue;
            $params['view']     = 'email.recruitment-request-approval';
            $params['subject']  = get_setting('mail_name') . ' - Recruitment Request';
            $params['email']    = $value->email;
            $params['data']     = $recruitment;
            $params['value']    = [];
            $params['text']     = '<p><strong>Dear Sir/Madam ' . $value->name . '</strong>,</p> <p> ' . $recruitment->requestor->name . '  / ' . $recruitment->requestor->nik . ' applied for Recruitment Request and currently waiting your approval.</p>';
            $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
            dispatch($job);
        }
        Config::set('database.default', session('db_name', 'mysql'));

        return response()->json(['status' => 'success', 'message' => 'Recruitment request is saved','data'=>$recruitment->id]);

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
        $param['recruitment'] = RecruitmentRequest::find($id);
        if(!$param['recruitment'] || $param['recruitment']->requestor->structure_organization_custom_id != Auth::user()->structure_organization_custom_id)
            return redirect()->route('karyawan.recruitment-request.index')->with('message-error', 'You don\'t have permission to perform this action!');
        return view('karyawan.recruitment-request.edit')->with($param);
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
}
