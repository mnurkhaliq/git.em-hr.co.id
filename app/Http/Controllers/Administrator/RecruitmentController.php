<?php

namespace App\Http\Controllers\Administrator;

use App\Models\ApplicantInterviewer;
use App\Models\AssetType;
use App\Models\EmployeeFacility;
use App\Models\ExternalApplication;
use App\Models\InternalApplication;
use App\Models\RecruitmentApplication;
use App\Models\RecruitmentApplicationHistory;
use App\Models\RecruitmentPhase;
use App\Models\RecruitmentRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use DataTables;
use Illuminate\Support\Facades\DB;

class RecruitmentController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
        $this->middleware('module:27');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('administrator.recruitment.index');
    }

    public function table(){
        $user = Auth::user();
        $requests = RecruitmentRequest::leftJoin('cabang as c','c.id','=','recruitment_request.branch_id')
            ->join('recruitment_request_detail as rd','recruitment_request.id','=','rd.recruitment_request_id')
            ->groupBy('recruitment_request.id')
            ->where(['recruitment_request.project_id'=>$user->project_id,'recruitment_request.approval_hr'=>1,'recruitment_request.approval_user'=>1,'recruitment_request.recruiter_id'=>$user->id])
            ->select(['recruitment_request.*',\DB::raw('group_concat(rd.recruitment_type_id) as target'),\DB::raw('DATE_FORMAT(recruitment_request.created_at, "%d %M %Y") as request_date'),'c.name as branch']);
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

    public function detail($id)
    {
        //
        $user = Auth::user();
        $param['recruitment'] = RecruitmentRequest::find($id);
        if(!$param['recruitment'] || $param['recruitment']->approval_user != '1' || $param['recruitment']->recruiter_id != $user->id)
            return redirect()->back()->with('message-error', 'Recruitment is not found');
        return view('administrator.recruitment.detail')->with($param);
    }

    public function getMoveDetail($id)
    {
        $data = [];

        $application     = RecruitmentApplication::find($id);
        if($application->internal != null){
            $int_application = $application->internal;
            $name            = $int_application->applicant->name;
            $photo           = !empty($int_application->applicant->foto) ? asset('storage/foto/' . $int_application->applicant->foto) : asset('admin-css/images/user.png');
            $date_request    = date('d F Y', strtotime($int_application->created_at));
            $data['next_boards'] = RecruitmentPhase::where('recruitment_type_id',1)->where('order','>',$application->currentPhase->order)->get();
        }else{
            $ext_application = $application->external;
            $name            = $ext_application->applicant->name;
            $photo           = !empty($ext_application->applicant->photos) ? asset('storage/foto/' . $ext_application->applicant->photos) : asset('admin-css/images/user.png');
            $date_request    = date('d F Y', strtotime($ext_application->created_at));
            $data['next_boards'] = RecruitmentPhase::where('recruitment_type_id',2)->where('order','>',$application->currentPhase->order)->get();
        }

        if (isset($int_application) || isset($ext_application)) {
            $recruitment = $application->recruitmentRequest;
            $data['application'] = [
                'id' => $application->id,
                'name' => $name,
                'photo' => $photo,
                'position' => $recruitment->job_position,
                'branch' => $recruitment->branch ? $recruitment->branch->name : "",
                'date_request' => $date_request,
                'current_phase' => $application->currentPhase->name
            ];

        }
        return $data;
    }

    public function getEditDetail($id)
    {
        $data = [];

        $history             = RecruitmentApplicationHistory::find($id);
        if($history) {
            $application     = $history->application;
            if($application->internal!=null) {
                $type = 'internal';
                $int_application = InternalApplication::where(['id' => $application->internal->id])->with('application')->first();
                $photo           = !empty($int_application->applicant->foto) ? asset('storage/foto/' . $int_application->applicant->foto) : asset('admin-css/images/user.png');
                $date_request    = date('d F Y', strtotime($int_application->created_at));
                $name            = $int_application->applicant->name;
            }
            else {
                $type = 'external';
                $ext_application = ExternalApplication::where(['id' => $application->external->id])->with('application')->first();
                $photo           = !empty($ext_application->applicant->photos) ? asset('storage/foto/' . $ext_application->applicant->photos) : asset('admin-css/images/user.png');
                $date_request    = date('d F Y', strtotime($ext_application->created_at));
                $name            = $ext_application->applicant->name;
            }

            if (isset($int_application) || isset($ext_application)) {
                $recruitment = $application->recruitmentRequest;
                $data['application'] = [
                    'id' => $application->id,
                    'name' => $name,
                    'photo' => $photo,
                    'position' => $recruitment->job_position,
                    'branch' => $recruitment->branch ? $recruitment->branch->name : "",
                    'date_request' => $date_request,
                    'phase' => $application->currentPhase->name,
                    'current_phase_id' => $application->current_phase_id,
                    'status' => $history->application_status,
                    'interviewers' => $application->interviewers,
                    'history_id' => $id
                ];

                $details = [];
                if($type == 'internal') {
                    if ($history->recruitment_phase_id == 2) { // Technical Exam
                        array_push($details, [
                            'title' => 'Test Schedule',
                            'name' => 'technical_test_schedule',
                            'type' => 'datetime',
                            'data' => $int_application->technical_test_schedule ? $int_application->technical_test_schedule : "",
                        ]);
                        array_push($details, [
                            'title' => 'Test Result',
                            'name' => 'technical_test_result',
                            'type' => 'textarea',
                            'data' => $int_application->technical_test_result ? $int_application->technical_test_result : ""
                        ]);
                        array_push($details, [
                            'title' => 'Remark',
                            'name' => 'technical_test_remark',
                            'type' => 'textarea',
                            'data' => $int_application->technical_test_remark ? $int_application->technical_test_remark : ""
                        ]);
                    }
                    if ($history->recruitment_phase_id == 3) { // Interview HR & User
                        array_push($details, [
                            'title' => 'Interview Schedule',
                            'name' => 'interview_test_schedule',
                            'type' => 'datetime',
                            'data' => $int_application->interview_test_schedule ? $int_application->interview_test_schedule : "",
                        ]);
                        array_push($details, [
                            'title' => 'Interview Location',
                            'name' => 'interview_test_location',
                            'type' => 'textarea',
                            'data' => $int_application->interview_test_location ? $int_application->interview_test_location : ""
                        ]);
                        array_push($details, [
                            'title' => 'Interview Result',
                            'name' => 'interview_test_result',
                            'type' => 'textarea',
                            'data' => $int_application->interview_test_result ? $int_application->interview_test_result : ""
                        ]);
                        array_push($details, [
                            'title' => 'Remark',
                            'name' => 'interview_test_remark',
                            'type' => 'textarea',
                            'data' => $int_application->interview_test_remark ? $int_application->interview_test_remark : ""
                        ]);
                    }
                    if ($history->recruitment_phase_id == 4) { // Transfer / Promotion
                        array_push($details, [
                            'title' => 'Memo Number',
                            'name' => 'memo_number',
                            'type' => 'text',
                            'data' => $int_application->memo_number ? $int_application->memo_number : ""
                        ]);
                        array_push($details, [
                            'title' => 'Memo Date',
                            'name' => 'memo_date',
                            'type' => 'date',
                            'data' => $int_application->memo_date ? $int_application->memo_date : ""
                        ]);
                        array_push($details, [
                            'title' => 'Onboard Date',
                            'name' => 'onboard_date',
                            'type' => 'date',
                            'data' => $int_application->onboard_date ? $int_application->onboard_date : ""
                        ]);
                    }
                }
                else if($type=='external'){
                    if ($history->recruitment_phase_id == 6) { // Psychotest
                        array_push($details, [
                            'title' => 'Psychotest Schedule',
                            'name' => 'psychotest_test_schedule',
                            'type' => 'datetime',
                            'data' => $ext_application->psychotest_test_schedule ? $ext_application->psychotest_test_schedule : "",
                        ]);
                        array_push($details, [
                            'title' => 'Psychotest Result',
                            'name' => 'psychotest_test_result',
                            'type' => 'textarea',
                            'data' => $ext_application->psychotest_test_result ? $ext_application->psychotest_test_result : ""
                        ]);
                        array_push($details, [
                            'title' => 'Psychotest Remark',
                            'name' => 'psychotest_test_remark',
                            'type' => 'textarea',
                            'data' => $ext_application->psychotest_test_remark ? $ext_application->psychotest_test_remark : ""
                        ]);
                    }
                    if ($history->recruitment_phase_id == 7) { // Technical Exam
                        array_push($details, [
                            'title' => 'Technical Schedule',
                            'name' => 'technical_test_schedule',
                            'type' => 'datetime',
                            'data' => $ext_application->technical_test_schedule ? $ext_application->technical_test_schedule : "",
                        ]);
                        array_push($details, [
                            'title' => 'Technical Result',
                            'name' => 'technical_test_result',
                            'type' => 'textarea',
                            'data' => $ext_application->technical_test_result ? $ext_application->technical_test_result : ""
                        ]);
                        array_push($details, [
                            'title' => 'Technical Remark',
                            'name' => 'technical_test_remark',
                            'type' => 'textarea',
                            'data' => $ext_application->technical_test_remark ? $ext_application->technical_test_remark : ""
                        ]);

                    }

                    if ($history->recruitment_phase_id == 8) { // Interview
                        array_push($details, [
                            'title' => 'Interview Schedule',
                            'name' => 'interview_test_schedule',
                            'type' => 'datetime',
                            'data' => $ext_application->interview_test_schedule ? $ext_application->interview_test_schedule : "",
                        ]);
                        array_push($details, [
                            'title' => 'Interview Location',
                            'name' => 'interview_test_location',
                            'type' => 'textarea',
                            'data' => $ext_application->interview_test_location ? $ext_application->interview_test_location : ""
                        ]);
                        array_push($details, [
                            'title' => 'Interview Result',
                            'name' => 'interview_test_result',
                            'type' => 'textarea',
                            'data' => $ext_application->interview_test_result ? $ext_application->interview_test_result : ""
                        ]);
                        array_push($details, [
                            'title' => 'Interview Remark',
                            'name' => 'interview_test_remark',
                            'type' => 'textarea',
                            'data' => $ext_application->interview_test_remark ? $ext_application->interview_test_remark : ""
                        ]);
                    }

                    if ($history->recruitment_phase_id == 9) { // Reference Check
                        array_push($details, [
                            'title' => 'Reference User 1',
                            'name' => 'reference_user_1',
                            'type' => 'text',
                            'data' => $ext_application->reference_user_1 ? $ext_application->reference_user_1 : "",
                        ]);
                        array_push($details, [
                            'title' => 'Reference Company 1',
                            'name' => 'reference_company_1',
                            'type' => 'textarea',
                            'data' => $ext_application->reference_company_1 ? $ext_application->reference_company_1 : ""
                        ]);
                        array_push($details, [
                            'title' => 'Reference User 2',
                            'name' => 'reference_user_2',
                            'type' => 'text',
                            'data' => $ext_application->reference_user_2 ? $ext_application->reference_user_2 : "",
                        ]);
                        array_push($details, [
                            'title' => 'Reference Company 2',
                            'name' => 'reference_company_2',
                            'type' => 'textarea',
                            'data' => $ext_application->reference_company_2 ? $ext_application->reference_company_2 : ""
                        ]);
                    }

                    if ($history->recruitment_phase_id == 10) { // Medical
                        array_push($details, [
                            'title' => 'Medical Schedule',
                            'name' => 'medical_test_schedule',
                            'type' => 'datetime',
                            'data' => $ext_application->medical_test_schedule ?  $ext_application->medical_test_schedule : "",
                        ]);
                        array_push($details, [
                            'title' => 'Medical Location',
                            'name' => 'medical_test_location',
                            'type' => 'textarea',
                            'data' => $ext_application->medical_test_location ? $ext_application->medical_test_location : ""
                        ]);
                        array_push($details, [
                            'title' => 'Medical Result',
                            'name' => 'medical_test_result',
                            'type' => 'textarea',
                            'data' => $ext_application->medical_test_result ? $ext_application->medical_test_result : ""
                        ]);
                        array_push($details, [
                            'title' => 'Medical Remark',
                            'name' => 'medical_test_remark',
                            'type' => 'textarea',
                            'data' => $ext_application->medical_test_remark ? $ext_application->medical_test_remark : ""
                        ]);
                    }

                    if ($history->recruitment_phase_id == 11) { // Job Offer
                        array_push($details, [
                            'title' => 'O.L. Number',
                            'name' => 'offering_letter_number',
                            'type' => 'text',
                            'data' => $ext_application->offering_letter_number ? $ext_application->offering_letter_number : ""
                        ]);
                        array_push($details, [
                            'title' => 'O.L. Date',
                            'name' => 'offering_letter_date',
                            'type' => 'date',
                            'data' => $ext_application->offering_letter_date ? $ext_application->offering_letter_date  : ""
                        ]);
                        array_push($details, [
                            'title' => 'O.L. Signing Date',
                            'name' => 'offering_letter_signing_date',
                            'type' => 'date',
                            'data' => $ext_application->offering_letter_signing_date ? $ext_application->offering_letter_signing_date : ""
                        ]);
                    }

                    if ($history->recruitment_phase_id == 12) { // Hiring
                        array_push($details, [
                            'title' => 'E.A. Number',
                            'name' => 'employment_agreement_number',
                            'type' => 'text',
                            'data' => $ext_application->employment_agreement_number ? $ext_application->employment_agreement_number : ""
                        ]);
                        array_push($details, [
                            'title' => 'E.A. Date',
                            'name' => 'employment_agreement_date',
                            'type' => 'date',
                            'data' => $ext_application->employment_agreement_date ? $ext_application->employment_agreement_date : ""
                        ]);
                        array_push($details, [
                            'title' => 'E.A. Signing Date',
                            'name' => 'employment_agreement_signing_date',
                            'type' => 'date',
                            'data' => $ext_application->employment_agreement_signing_date ? $ext_application->employment_agreement_signing_date : ""
                        ]);
                    }

                    if ($history->recruitment_phase_id == 13) { // Onboarding
                        array_push($details, [
                            'title' => 'Onboard Date',
                            'name' => 'onboard_date',
                            'type' => 'date',
                            'data' => $ext_application->onboard_date ? $ext_application->onboard_date : ""
                        ]);
                    }
                }

                $data['application']['details'] = $details;

            }
        }
        return $data;
    }

    function getOnboardDetail($external_id){
        $data = [];
        $ext_application = ExternalApplication::find($external_id);
        $application     = $ext_application->application;
        if ($application) {
            $recruitment = $application->recruitmentRequest;
            $facilities = AssetType::leftJoin('employee_facility_recruitment as ef',function ($join) use($external_id){
                $join->on('asset_type.id','=','ef.asset_type_id');
                $join->where('ef.external_application_id',$external_id);
//                $join->on(DB::raw("asset_type.id = ef.id AND ef.external_application_id = $external_id"),DB::raw(''),DB::raw(''));
            })
            ->select(['asset_type.*', 'ef.id as employee_facility_id'])
            ->get();
            $data['application'] = [
                'id' => $application->id,
                'name' => $ext_application->applicant->name,
                'position' => $recruitment->job_position,
                'branch' => $recruitment->branch ? $recruitment->branch->name : "",
                'onboard_date' => $ext_application->onboard_date,
                'facilities' => $facilities
            ];

        }
        return $data;
    }

    function detailHistoryExternal($id){
        $user = Auth::user();
        $data = [];
        $ext_application = ExternalApplication::where(['id'=>$id])->with('application')->first();
        if($ext_application){
            $application         = $ext_application->application;
            $recruitment         = $application->recruitmentRequest;
            $data['application'] = [
                'name'         => $ext_application->applicant->name,
                'photo'        => !empty($ext_application->applicant->photos)?asset('storage/foto/'. $ext_application->applicant->photos):asset('admin-css/images/user.png'),
                'position'     => $recruitment->job_position,
                'branch'       => $recruitment->branch?$recruitment->branch->name:"",
                'date_request' => date('d F Y', strtotime($ext_application->created_at)),
                'current_phase'=> $application->currentPhase->name
            ];

            $histories          = $application->histories;
            $data['histories']  = [];
            foreach ($histories as $history){
                $newHistory       = [
                    'phase'       => $history->phase->name,
                    'last_edit'   => date('d F Y', strtotime($history->updated_at)),
                    'status'      => $history->application_status,
                    'status_name' => $history->status->status
                ];
                $details = [];
                if($history->recruitment_phase_id == 5){ // Screening
                    if($ext_application->applicant->cv) {
                        array_push($details, [
                            'title' => 'Download CV',
                            'type' => 'url',
                            'data' => asset('storage/file-cv') . "/" . $ext_application->applicant->cv
                        ]);
                    }
                    if($application->cover_letter) {
                        array_push($details, [
                            'title' => 'Show Cover Letter',
                            'type' => 'collapse',
                            'data' => $application->cover_letter
                        ]);
                    }
                }
                if($history->recruitment_phase_id== 6){ // Psychotest
                    if($ext_application->psychotest_test_schedule) {
                        array_push($details, [
                            'title' => 'Psychotest Schedule',
                            'type' => 'text',
                            'data' => $ext_application->psychotest_test_schedule ? date('d F Y H:i', strtotime($ext_application->psychotest_test_schedule)) : "",
                        ]);
                    }
                    if($ext_application->psychotest_test_result) {
                        array_push($details, [
                            'title' => 'Psychotest Result',
                            'type' => 'text',
                            'data' => $ext_application->psychotest_test_result ? $ext_application->psychotest_test_result : ""
                        ]);
                    }
                    if($ext_application->psychotest_test_remark) {
                        array_push($details, [
                            'title' => 'Psychotest Remark',
                            'type' => 'text',
                            'data' => $ext_application->psychotest_test_remark ? $ext_application->psychotest_test_remark : ""
                        ]);
                    }
                }
                if($history->recruitment_phase_id== 7){ // Technical Exam
                    if($ext_application->technical_test_schedule) {
                        array_push($details, [
                            'title' => 'Technical Schedule',
                            'type' => 'text',
                            'data' => $ext_application->technical_test_schedule ? date('d F Y H:i', strtotime($ext_application->technical_test_schedule)) : "",
                        ]);
                    }
                    if($ext_application->technical_test_result) {
                        array_push($details, [
                            'title' => 'Technical Result',
                            'type' => 'text',
                            'data' => $ext_application->technical_test_result ? $ext_application->technical_test_result : ""
                        ]);
                    }
                    if($ext_application->technical_test_remark) {
                        array_push($details, [
                            'title' => 'Technical Remark',
                            'type' => 'text',
                            'data' => $ext_application->technical_test_remark ? $ext_application->technical_test_remark : ""
                        ]);
                    }

                }

                if($history->recruitment_phase_id== 8){ // Interview
                    if($ext_application->interview_test_schedule) {
                        array_push($details, [
                            'title' => 'Interview Schedule',
                            'type' => 'text',
                            'data' => $ext_application->interview_test_schedule ? date('d F Y H:i', strtotime($ext_application->interview_test_schedule)) : "",
                        ]);
                    }
                    if($ext_application->interview_test_location) {
                        array_push($details, [
                            'title' => 'Interview Location',
                            'type' => 'text',
                            'data' => $ext_application->interview_test_location ? $ext_application->interview_test_location : ""
                        ]);
                    }
                    if($ext_application->interview_test_result) {
                        array_push($details, [
                            'title' => 'Interview Result',
                            'type' => 'text',
                            'data' => $ext_application->interview_test_result ? $ext_application->interview_test_result : ""
                        ]);
                    }
                    if($ext_application->interview_test_remark) {
                        array_push($details, [
                            'title' => 'Interview Remark',
                            'type' => 'text',
                            'data' => $ext_application->interview_test_remark ? $ext_application->interview_test_remark : ""
                        ]);
                    }
                }

                if($history->recruitment_phase_id== 9){ // Reference Check
                    if($ext_application->reference_user_1) {
                        array_push($details, [
                            'title' => 'Reference User 1',
                            'type' => 'text',
                            'data' => $ext_application->reference_user_1 ? $ext_application->reference_user_1 : "",
                        ]);
                    }
                    if($ext_application->reference_company_1) {
                        array_push($details, [
                            'title' => 'Reference Company 1',
                            'type' => 'text',
                            'data' => $ext_application->reference_company_1 ? $ext_application->reference_company_1 : ""
                        ]);
                    }
                    if($ext_application->reference_user_2) {
                        array_push($details, [
                            'title' => 'Reference User 2',
                            'type' => 'text',
                            'data' => $ext_application->reference_user_2 ? $ext_application->reference_user_2 : "",
                        ]);
                    }
                    if($ext_application->reference_company_2) {
                        array_push($details, [
                            'title' => 'Reference Company 2',
                            'type' => 'text',
                            'data' => $ext_application->reference_company_2 ? $ext_application->reference_company_2 : ""
                        ]);
                    }
                }

                if($history->recruitment_phase_id== 10){ // Medical
                    if($ext_application->medical_test_schedule) {
                        array_push($details, [
                            'title' => 'Medical Schedule',
                            'type' => 'text',
                            'data' => $ext_application->medical_test_schedule ? date('d F Y H:i', strtotime($ext_application->medical_test_schedule)) : "",
                        ]);
                    }
                    if($ext_application->medical_test_location) {
                        array_push($details, [
                            'title' => 'Medical Location',
                            'type' => 'text',
                            'data' => $ext_application->medical_test_location ? $ext_application->medical_test_location : ""
                        ]);
                    }
                    if($ext_application->medical_test_result) {
                        array_push($details, [
                            'title' => 'Medical Result',
                            'type' => 'text',
                            'data' => $ext_application->medical_test_result ? $ext_application->medical_test_result : ""
                        ]);
                    }
                    if($ext_application->medical_test_remark) {
                        array_push($details, [
                            'title' => 'Medical Remark',
                            'type' => 'text',
                            'data' => $ext_application->medical_test_remark ? $ext_application->medical_test_remark : ""
                        ]);
                    }
                }

                if($history->recruitment_phase_id== 11){ // Job Offer
                    if($ext_application->offering_letter_number) {
                        array_push($details, [
                            'title' => 'O.L. Number',
                            'type' => 'text',
                            'data' => $ext_application->offering_letter_number ? $ext_application->offering_letter_number : ""
                        ]);
                    }
                    if($ext_application->offering_letter_date) {
                        array_push($details, [
                            'title' => 'O.L. Date',
                            'type' => 'text',
                            'data' => $ext_application->offering_letter_date ? date('d F Y', strtotime($ext_application->offering_letter_date)) : ""
                        ]);
                    }
                    if($ext_application->offering_letter_signing_date) {
                        array_push($details, [
                            'title' => 'O.L. Signing Date',
                            'type' => 'text',
                            'data' => $ext_application->offering_letter_signing_date ? date('d F Y', strtotime($ext_application->offering_letter_signing_date)) : ""
                        ]);
                    }
                }

                if($history->recruitment_phase_id== 12){ // Hiring
                    if($ext_application->employment_agreement_number) {
                        array_push($details, [
                            'title' => 'E.A. Number',
                            'type' => 'text',
                            'data' => $ext_application->employment_agreement_number ? $ext_application->employment_agreement_number : ""
                        ]);
                    }
                    if($ext_application->employment_agreement_date) {
                        array_push($details, [
                            'title' => 'E.A. Date',
                            'type' => 'text',
                            'data' => $ext_application->employment_agreement_date ? date('d F Y', strtotime($ext_application->employment_agreement_date)) : ""
                        ]);
                    }
                    if($ext_application->employment_agreement_signing_date) {
                        array_push($details, [
                            'title' => 'E.A. Signing Date',
                            'type' => 'text',
                            'data' => $ext_application->employment_agreement_signing_date ? date('d F Y', strtotime($ext_application->employment_agreement_signing_date)) : ""
                        ]);
                    }
                }

                if($history->recruitment_phase_id== 13){ // Onboarding
                    if($ext_application->onboard_date) {
                        array_push($details, [
                            'title' => 'Onboard Date',
                            'type' => 'text',
                            'data' => $ext_application->onboard_date ? date('d F Y', strtotime($ext_application->onboard_date)) : ""
                        ]);
                    }
                }

                $newHistory['details'] = $details;

                array_push($data['histories'],$newHistory);
            }
        }

        return $data;
    }

    public function move(Request $request){
        $application = RecruitmentApplication::find($request->application_id);
        if($application){
            $nextPhase = RecruitmentPhase::find($request->next_board);
            if($nextPhase){
                $application->current_phase_id          = $nextPhase->id;
                $application->application_status        = 0;
                $application->save();
                $newHistory                             = new RecruitmentApplicationHistory();
                $newHistory->recruitment_phase_id       = $nextPhase->id;
                $newHistory->application_status         = 0;
                $newHistory->recruitment_application_id = $application->id;
                $newHistory->save();
                $type = $application->internal?'1':'2';
                return response()->json(['status' => 'success', 'message' => 'Board has been moved successfully!','data'=>$type]);
            }
            else{
                return response()->json(['status' => 'failed', 'message' => 'Phase is not found!']);
            }
        }
        else{
            return response()->json(['status' => 'failed', 'message' => 'Application is not found!']);
        }
    }

    public function emailInterviewer(Request $request){
        $application = RecruitmentApplication::find($request->id);
        if($application){
            if(count($application->interviewers) == 0){
                return response()->json(['status' => 'failed', 'message' => 'Interviewers have not been set!']);
            }
            else{
                $params = getEmailConfig();
                Config::set('database.default','mysql');
                $params['view']     = 'email.recruitment-interviewers';

                $params['subject']  = $params['mail_name'].' - Recruitment Interview Schedule';
                $params['data']     = $application;
                foreach ($application->interviewers as $interviewer){
                    info($interviewer);
                    $params['user']     = $interviewer->user;
                    $params['email']    = $interviewer->user->email;
                    $params['text']     = '<p><strong>Dear Sir/Madam '. $interviewer->name .'</strong>,</p> <p> You are requested to attend the interview test of : </p>';
                    $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                    dispatch($job);
                }
                Config::set('database.default',session('db_name','mysql'));
                return response()->json(['status' => 'success', 'message' => 'Email has been sent!']);
            }
        }
        else{
            return response()->json(['status' => 'failed', 'message' => 'Application is not found!']);
        }
    }
    public function updateBoard(Request $request){
        $history = RecruitmentApplicationHistory::find($request->history_id);
        $type = '';
        if($history){
            if($request->application_status != '4')
                $history->application_status    = $request->application_status;
            $application                    = $history->application;
            if($application->current_phase_id == $history->recruitment_phase_id){
                $application->application_status = $request->application_status;
                $application->save();
            }
            if($application->internal!=null) {
                $type = '1';
                $internal = $application->internal;
                if ($history->recruitment_phase_id == 2) {
                    $internal->technical_test_schedule = $request->technical_test_schedule;
                    $internal->technical_test_result = $request->technical_test_result;
                    $internal->technical_test_remark = $request->technical_test_remark;
                    $internal->save();
                } else if ($history->recruitment_phase_id == 3) {
                    $this->setInterviewers($request->interviewers,$application->id);

                    $internal->interview_test_schedule = $request->interview_test_schedule;
                    $internal->interview_test_location = $request->interview_test_location;
                    $internal->interview_test_result = $request->interview_test_result;
                    $internal->interview_test_remark = $request->interview_test_remark;
                    $internal->save();
                } else if ($history->recruitment_phase_id == 4) {
                    $internal->memo_number = $request->memo_number;
                    $internal->memo_date = $request->memo_date;
                    $internal->onboard_date = $request->onboard_date;
                    $internal->save();
                }
            }
            else if($application->external!=null) {
                $external = $application->external;
                $type = '2';
                if ($history->recruitment_phase_id == 6) {
                    $external->psychotest_test_schedule = $request->psychotest_test_schedule;
                    $external->psychotest_test_result   = $request->psychotest_test_result;
                    $external->psychotest_test_remark   = $request->psychotest_test_remark;
                    $external->save();
                }else if ($history->recruitment_phase_id == 7) {
                    $external->technical_test_schedule = $request->technical_test_schedule;
                    $external->technical_test_result   = $request->technical_test_result;
                    $external->technical_test_remark   = $request->technical_test_remark;
                    $external->save();
                }else if ($history->recruitment_phase_id == 8) {
                    $this->setInterviewers($request->interviewers,$application->id);
                    $external->interview_test_schedule = $request->interview_test_schedule;
                    $external->interview_test_location = $request->interview_test_location;
                    $external->interview_test_result   = $request->interview_test_result;
                    $external->interview_test_remark   = $request->interview_test_remark;
                    $external->save();
                }else if ($history->recruitment_phase_id == 9) {
                    $external->reference_user_1        = $request->reference_user_1;
                    $external->reference_company_1     = $request->reference_company_1;
                    $external->reference_user_2        = $request->reference_user_2;
                    $external->reference_company_2     = $request->reference_company_2;
                    $external->save();
                }else if ($history->recruitment_phase_id == 10) {
                    $external->medical_test_schedule   = $request->medical_test_schedule;
                    $external->medical_test_location   = $request->medical_test_location;
                    $external->medical_test_result     = $request->medical_test_result;
                    $external->medical_test_remark     = $request->medical_test_remark;
                    $external->save();
                }else if ($history->recruitment_phase_id == 11) {
                    $external->offering_letter_number       = $request->offering_letter_number;
                    $external->offering_letter_date         = $request->offering_letter_date;
                    $external->offering_letter_signing_date = $request->offering_letter_signing_date;
                    $external->save();
                }else if ($history->recruitment_phase_id == 12) {
                    $external->employment_agreement_number       = $request->employment_agreement_number;
                    $external->employment_agreement_date         = $request->employment_agreement_date;
                    $external->employment_agreement_signing_date = $request->employment_agreement_signing_date;
                    $external->save();
                }
                else if ($history->recruitment_phase_id == 13) {
                    $external->onboard_date            = $request->onboard_date;
                    $external->save();
                }
            }
            $history->save();
            return response()->json(['status' => 'success', 'message' => 'Board has been updated successfully!','data'=>$type]);
        }else{
            return response()->json(['status' => 'failed', 'message' => 'Application is not found!']);
        }
    }
    public function updateOnboard(Request $request){
        $facilities_arr = [];
        if ($request->facilities) {
            foreach ($request->facilities as $facility) {
                if ($facility != null) {
                    array_push($facilities_arr, $facility);
                }
            }

            foreach ($facilities_arr as $facility) {
                $param = ['external_application_id' => $request->external_application_id, 'asset_type_id' => $facility];
                $employeeFacility = EmployeeFacility::where($param)->first();
                if (!$employeeFacility) {
                    $employeeFacility = new EmployeeFacility();
                    $employeeFacility->external_application_id = $request->external_application_id;
                    $employeeFacility->asset_type_id = $facility;
                    $employeeFacility->save();
                }
            }
        }
        EmployeeFacility::where('external_application_id', $request->external_application_id)->whereNotIn('asset_type_id', $facilities_arr)->delete();
        return response()->json(['status' => 'success', 'message' => 'Onboarding applicant has been updated successfully!']);
    }

    private function setInterviewers($interviewers,$app_id){
        $interviewers_arr = [];
        if ($interviewers) {
            foreach ($interviewers as $interviewer) {
                if ($interviewer != null) {
                    array_push($interviewers_arr, $interviewer);
                }
            }

            foreach ($interviewers_arr as $interviewer) {
                $param = ['recruitment_application_id' => $app_id, 'user_id' => $interviewer];
                $detail = ApplicantInterviewer::where($param)->first();
                if (!$detail) {
                    $detail = new ApplicantInterviewer();
                    $detail->recruitment_application_id = $app_id;
                    $detail->user_id = $interviewer;
                    $detail->save();
                }
            }
        }
        ApplicantInterviewer::where('recruitment_application_id', $app_id)->whereNotIn('user_id', $interviewers_arr)->delete();
    }

    public function getInternalData($id){
        $phases = RecruitmentPhase::where(['recruitment_type_id'=>1])->orderBy('order','asc')->get();
        foreach ($phases as $phase){
            $applications = RecruitmentApplication::whereHas('internal')
                ->where(['current_phase_id'=>$phase->id,'recruitment_request_id'=>$id])
                ->where('application_status','!=','4')
                ->get();
            $apps = [];
            foreach ($applications as $app){
                $details = [];
                if($app->internal->cv) {
                    array_push($details, [
                        'title' => 'Download CV',
                        'type' => 'url',
                        'data' => asset('storage/file-cv') . "/" . $app->internal->cv
                    ]);
                }
                if($app->current_phase_id == 1){ // Screening
                    if($app->cover_letter) {
                        array_push($details, [
                            'title' => 'Show Cover Letter',
                            'type' => 'collapse',
                            'data' => ($app->cover_letter)
                        ]);
                    }
                }
                if($app->current_phase_id == 2){ // Technical Exam
//                    if($app->internal->technical_test_schedule) {
                        array_push($details, [
                            'title' => 'Test Schedule',
                            'type' => 'text',
                            'data' => $app->internal->technical_test_schedule?date('d F Y H:i', strtotime($app->internal->technical_test_schedule)):"",
                        ]);
//                    }
//                    if($app->internal->technical_test_result) {
                        array_push($details, [
                            'title' => 'Test Result',
                            'type' => 'text',
                            'data' => $app->internal->technical_test_result?$app->internal->technical_test_result:""
                        ]);
//                    }
//                    if($app->internal->technical_test_remark) {
                        array_push($details, [
                            'title' => 'Remark',
                            'type' => 'text',
                            'data' => $app->internal->technical_test_remark?$app->internal->technical_test_remark:""
                        ]);
//                    }
                }
                if($app->current_phase_id == 3){ // Interview HR & User
//                    if($app->internal->interview_test_schedule) {
                        array_push($details, [
                            'title' => 'Interview Schedule',
                            'type' => 'datetime',
                            'data' => $app->internal->interview_test_schedule?date('d F Y H:i', strtotime($app->internal->interview_test_schedule)):"",
                        ]);
//                    }
//                    if($app->internal->interview_test_location) {
                        array_push($details, [
                            'title' => 'Interview Location',
                            'type' => 'text',
                            'data' => $app->internal->interview_test_location?$app->internal->interview_test_location:""
                        ]);
//                    }
//                    if($app->internal->interview_test_result) {
                        array_push($details, [
                            'title' => 'Interview Result',
                            'type' => 'text',
                            'data' => $app->internal->interview_test_result?$app->internal->interview_test_result:""
                        ]);
//                    }
//                    if($app->internal->interview_test_remark) {
                        array_push($details, [
                            'title' => 'Remark',
                            'type' => 'text',
                            'data' => $app->internal->interview_test_remark?$app->internal->interview_test_remark:""
                        ]);
//                    }
                }

                if($app->current_phase_id == 4){ // Transfer / Promotion
//                    if($app->internal->memo_number) {
                        array_push($details, [
                            'title' => 'Memo Number',
                            'type' => 'text',
                            'data' => $app->internal->memo_number?$app->internal->memo_number:""
                        ]);
//                    }
//                    if($app->internal->memo_date) {
                        array_push($details, [
                            'title' => 'Memo Date',
                            'type' => 'text',
                            'data' => $app->internal->memo_date?date('d F Y', strtotime($app->internal->memo_date)):""
                        ]);
//                    }
//                    if($app->internal->onboard_date) {
                        array_push($details, [
                            'title' => 'Onboard Date',
                            'type' => 'text',
                            'data' => $app->internal->onboard_date?date('d F Y', strtotime($app->internal->onboard_date)):""
                        ]);
//                    }

                }
                $newApp['id']               = $app->id;
                $newApp['internal_app_id']  = $app->internal->id;
                $newApp['history']          = RecruitmentApplicationHistory::where(['recruitment_application_id'=>$app->id,'recruitment_phase_id'=>$app->current_phase_id])->first();
                $newApp['applicant']        = $app->internal->applicant->name;
                $newApp['created_at']       = date('d F Y', strtotime($app->created_at));
                $newApp['updated_at']       = date('d F Y', strtotime($app->updated_at));
                $newApp['status']           = $app->application_status;
                $newApp['status_name']      = $app->status->status;
                $newApp['details']          = $details;
                $newApp['next_boards']      = RecruitmentPhase::where('recruitment_type_id',1)->where('order','>',$app->current_phase_id)->get();
                array_push($apps, $newApp);
            }
            $phase['applications'] = $apps;
        }
        return json_encode($phases);
    }
    public function getExternalData($id){
        $phases = RecruitmentPhase::where(['recruitment_type_id'=>2])->orderBy('order','asc')->get();
        foreach ($phases as $phase){
            $applications = RecruitmentApplication::whereHas('external')
                ->where(['current_phase_id'=>$phase->id,'recruitment_request_id'=>$id])
                ->where('application_status','!=','4')
                ->get();
            info($applications);
            $apps = [];
            foreach ($applications as $app){
                $details = [];
                if($app->external->applicant->cv) {
                    array_push($details, [
                        'title' => 'Download CV',
                        'type' => 'url',
                        'data' => asset('storage/file-cv') . "/" . $app->external->applicant->cv
                    ]);
                }
                if($app->current_phase_id == 5){ // Screening
                    if($app->cover_letter) {
                        array_push($details, [
                            'title' => 'Show Cover Letter',
                            'type' => 'collapse',
                            'data' => ($app->cover_letter)
                        ]);
                    }
                }
                if($app->current_phase_id == 6){ // Psychotest
                    array_push($details, [
                        'title' => 'Psychotest Schedule',
                        'type' => 'text',
                        'data' => $app->external->psychotest_test_schedule?date('d F Y H:i', strtotime($app->external->psychotest_test_schedule)):"",
                    ]);
                    array_push($details, [
                        'title' => 'Psychotest Result',
                        'type' => 'text',
                        'data' => $app->external->psychotest_test_result?$app->external->psychotest_test_result:""
                    ]);
                    array_push($details, [
                        'title' => 'Psychotest Remark',
                        'type' => 'text',
                        'data' => $app->external->psychotest_test_remark?$app->external->psychotest_test_remark:""
                    ]);
                }
                if($app->current_phase_id == 7){ // Technical Exam
                    array_push($details, [
                        'title' => 'Technical Schedule',
                        'type' => 'text',
                        'data' => $app->external->technical_test_schedule?date('d F Y H:i', strtotime($app->external->technical_test_schedule)):"",
                    ]);
                    array_push($details, [
                        'title' => 'Technical Result',
                        'type' => 'text',
                        'data' => $app->external->technical_test_result?$app->external->technical_test_result:""
                    ]);
                    array_push($details, [
                        'title' => 'Technical Remark',
                        'type' => 'text',
                        'data' => $app->external->technical_test_remark?$app->external->technical_test_remark:""
                    ]);

                }

                if($app->current_phase_id == 8){ // Interview
                    array_push($details, [
                        'title' => 'Interview Schedule',
                        'type' => 'text',
                        'data' => $app->external->interview_test_schedule?date('d F Y H:i', strtotime($app->external->interview_test_schedule)):"",
                    ]);
                    array_push($details, [
                        'title' => 'Interview Location',
                        'type' => 'text',
                        'data' => $app->external->interview_test_location?$app->external->interview_test_location:""
                    ]);
                    array_push($details, [
                        'title' => 'Interview Result',
                        'type' => 'text',
                        'data' => $app->external->interview_test_result?$app->external->interview_test_result:""
                    ]);
                    array_push($details, [
                        'title' => 'Interview Remark',
                        'type' => 'text',
                        'data' => $app->external->interview_test_remark?$app->external->interview_test_remark:""
                    ]);
                }

                if($app->current_phase_id == 9){ // Reference Check
                    array_push($details, [
                        'title' => 'Reference User 1',
                        'type' => 'text',
                        'data' => $app->external->reference_user_1?$app->external->reference_user_1:"",
                    ]);
                    array_push($details, [
                        'title' => 'Reference Company 1',
                        'type' => 'text',
                        'data' => $app->external->reference_company_1?$app->external->reference_company_1:""
                    ]);
                    array_push($details, [
                        'title' => 'Reference User 2',
                        'type' => 'text',
                        'data' => $app->external->reference_user_2?$app->external->reference_user_2:"",
                    ]);
                    array_push($details, [
                        'title' => 'Reference Company 2',
                        'type' => 'text',
                        'data' => $app->external->reference_company_2?$app->external->reference_company_2:""
                    ]);
                }

                if($app->current_phase_id == 10){ // Medical
                    array_push($details, [
                        'title' => 'Medical Schedule',
                        'type' => 'text',
                        'data' => $app->external->medical_test_schedule?date('d F Y H:i', strtotime($app->external->medical_test_schedule)):"",
                    ]);
                    array_push($details, [
                        'title' => 'Medical Location',
                        'type' => 'text',
                        'data' => $app->external->medical_test_location?$app->external->medical_test_location:""
                    ]);
                    array_push($details, [
                        'title' => 'Medical Result',
                        'type' => 'text',
                        'data' => $app->external->medical_test_result?$app->external->medical_test_result:""
                    ]);
                    array_push($details, [
                        'title' => 'Medical Remark',
                        'type' => 'text',
                        'data' => $app->external->medical_test_remark?$app->external->medical_test_remark:""
                    ]);
                }

                if($app->current_phase_id == 11){ // Job Offer
                    array_push($details, [
                        'title' => 'O.L. Number',
                        'type' => 'text',
                        'data' => $app->external->offering_letter_number?$app->external->offering_letter_number:""
                    ]);
                    array_push($details, [
                        'title' => 'O.L. Date',
                        'type' => 'text',
                        'data' => $app->external->offering_letter_date?date('d F Y', strtotime($app->external->offering_letter_date)):""
                    ]);
                    array_push($details, [
                        'title' => 'O.L. Signing Date',
                        'type' => 'text',
                        'data' => $app->external->offering_letter_signing_date?date('d F Y', strtotime($app->external->offering_letter_signing_date)):""
                    ]);
                }

                if($app->current_phase_id == 12){ // Hiring
                    array_push($details, [
                        'title' => 'E.A. Number',
                        'type' => 'text',
                        'data' => $app->external->employment_agreement_number?$app->external->employment_agreement_number:""
                    ]);
                    array_push($details, [
                        'title' => 'E.A. Date',
                        'type' => 'text',
                        'data' => $app->external->employment_agreement_date?date('d F Y', strtotime($app->external->employment_agreement_date)):""
                    ]);
                    array_push($details, [
                        'title' => 'E.A. Signing Date',
                        'type' => 'text',
                        'data' => $app->external->employment_agreement_signing_date?date('d F Y', strtotime($app->external->employment_agreement_signing_date)):""
                    ]);
                }

                if($app->current_phase_id == 13){ // Onboarding
                    array_push($details, [
                        'title' => 'Onboard Date',
                        'type' => 'text',
                        'data' => $app->external->onboard_date?date('d F Y', strtotime($app->external->onboard_date)):""
                    ]);

                    $facilities = DB::select(DB::raw("SELECT group_concat(a.name SEPARATOR ', ') as facility from employee_facility_recruitment ef join asset_type a on ef.asset_type_id = a.id where ef.external_application_id = ".$app->external->id));
                    array_push($details, [
                        'title' => 'Facilities',
                        'type' => 'text',
                        'data' => $facilities[0]->facility?$facilities[0]->facility:""
                    ]);

                }

                $newApp['id']               = $app->id;
                $newApp['external_app_id']  = $app->external->id;
                $newApp['history']          = RecruitmentApplicationHistory::where(['recruitment_application_id'=>$app->id,'recruitment_phase_id'=>$app->current_phase_id])->first();
                $newApp['applicant']        = $app->external->applicant->name;
                $newApp['created_at']       = date('d F Y', strtotime($app->created_at));
                $newApp['updated_at']       = date('d F Y', strtotime($app->updated_at));
                $newApp['status']           = $app->application_status;
                $newApp['status_name']      = $app->status->status;
                $newApp['details']          = $details;
                $newApp['next_boards']      = RecruitmentPhase::where('recruitment_type_id',2)->where('order','>',$app->currentPhase->order)->get();
                array_push($apps, $newApp);
            }
            $phase['applications'] = $apps;
        }
        return json_encode($phases);
    }
    public function download($id){
        $recruitment = RecruitmentRequest::find($id);
        if($recruitment) {
            $internal = RecruitmentApplication::whereHas('internal')->where('recruitment_request_id', $id)->get();
            $params = [];
            foreach ($internal as $no => $item) {
                $params[$no]['NO'] = $no + 1;
                $params[$no]['NAME'] = $item->internal->applicant->name;
                $params[$no]['DATE APPLY'] = date('Y-m-d', strtotime($item->created_at));
                $params[$no]['PHASE'] = $item->currentPhase->name;
                $params[$no]['STATUS'] = $item->status->status;
                $params[$no]['TYPE'] = 'INTERNAL';
            }
            $no = count($internal);
            $external = RecruitmentApplication::whereHas('external')->where('recruitment_request_id', $id)->get();
            foreach ($external as $item) {
                $params[$no]['NO'] = ++$no;
                $params[$no]['NAME'] = $item->external->applicant->name;
                $params[$no]['DATE APPLY'] = date('Y-m-d', strtotime($item->created_at));
                $params[$no]['PHASE'] = $item->currentPhase->name;
                $params[$no]['STATUS'] = $item->status->status;
                $params[$no]['TYPE'] = 'EXTERNAL';
            }

            $position = $recruitment->job_position;
            return (new \App\Models\KaryawanExport($params, 'Application '.$position))->download("EM-HR.Report-Recruitment-$position.xlsx");
        }
    }
}
