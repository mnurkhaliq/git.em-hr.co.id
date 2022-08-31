<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseApiController;
use App\Http\Controllers\Controller;
use App\Http\Middleware\ConnectionMiddleware;
use App\Http\Resources\GradeResource;
use App\Http\Resources\RecruitmentRequestResource;
use App\Http\Resources\StructureOrganizationResource;
use App\Models\Cabang;
use App\Models\Grade;
use App\Models\HistoryApprovalRecruitment;
use App\Models\RecruitmentRequest;
use App\Models\RecruitmentRequestDetail;
use App\Models\StructureOrganizationCustom;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class RecruitmentRequestController extends BaseApiController
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
        $status = $request->input('status', '[1,2,3,4]');
        if (!isJsonValid($status)) {
            return response()->json(['status' => 'error', 'message' => 'Status is invalid'], 404);
        }

        $status = json_decode($status);
        $histories = RecruitmentRequest::where(['requestor_id' => $user->id])->whereIn('status', $status)->orderBy('created_at', 'DESC');
        $totalData = $histories->get()->count();
        $histories = $histories->paginate(10);
        $data = [
            'current_page' => $histories->currentPage(), // get current page number
            'total_page' => $histories->total() ? $histories->lastPage() : $histories->total(), // get last page number
            'total_data' => $totalData,
            'recruitments' => RecruitmentRequestResource::collection($histories),
        ];
        return response()->json(
            [
                'status' => 'success',
                'message' => 'Successfully',
                'data' => $data,
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
        if ($approval == null) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Your position is not defined yet. Please contact your admin!',
                ], 403);
        } else if (count($approval->itemsRecruitment) == 0) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Setting approval is not defined yet. Please contact your admin!',
                ], 403);
        }
        $validator = Validator::make($request->all(), [
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
            'contract_duration' => 'bail|required_if:employment_type,2,3,4,5|nullable|integer|min:1',
            'headcount' => 'required|integer|min:1',
            'expected_date' => 'required|date',
            'recruitment_type' => 'required|array',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()], 401);
        }

        $recruitment = new RecruitmentRequest();
        $recruitment->request_number = getRecruitmentId();
        $recruitment->structure_organization_custom_id = $request->position;
        $recruitment->requestor_id = $request->requestor;
        $recruitment->branch_id = $request->branch;
        $recruitment->grade_id = $request->grade;
        $recruitment->subgrade_id = isset($request->subgrade) ? $request->subgrade : null;
        $recruitment->min_salary = $request->min_salary;
        $recruitment->max_salary = $request->max_salary;
        $recruitment->job_position = $request->job_position;
        $recruitment->job_desc = htmlspecialchars($request->job_desc);
        $recruitment->job_requirement = htmlspecialchars($request->job_requirement);
        $recruitment->benefit = htmlspecialchars($request->benefit);
        $recruitment->reason = $request->reason;
        $recruitment->headcount = $request->headcount;
        $recruitment->expected_date = date('Y-m-d', strtotime($request->expected_date));
        $recruitment->employment_type = $request->employment_type;
        $recruitment->contract_duration = ($request->employment_type != 1) ? $request->contract_duration : null;
        $recruitment->additional_information = $request->additional_information;
        $recruitment->project_id = Auth::user()->project_id;
        $recruitment->status = 4;

        $recruitment->save();

        foreach ($request->recruitment_type as $type) {
            $detail = new RecruitmentRequestDetail();
            $detail->recruitment_request_id = $recruitment->id;
            $detail->recruitment_type_id = $type;
            $detail->save();
        }

        $admins = getAdminByModule(27);
        $params = getEmailConfig();
        Config::set('database.default', 'mysql');
        foreach ($admins as $key => $value) {
            if ($value->email == "") {
                continue;
            }

            $params['view'] = 'email.recruitment-request-approval';
            $params['subject'] = get_setting('mail_name') . ' - Recruitment Request';
            $params['email'] = $value->email;
            $params['data'] = $recruitment;
            $params['value'] = [];
            $params['text'] = '<p><strong>Dear Sir/Madam ' . $value->name . '</strong>,</p> <p> ' . $recruitment->requestor->name . '  / ' . $recruitment->requestor->nik . ' applied for Recruitment Request and currently waiting your approval.</p>';
            $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
            dispatch($job);
        }
        Config::set('database.default', session('db_name', 'mysql'));

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Your recruitment request has successfully submitted',
            ], 201);
    }

    public function getParams(Request $request)
    {
        if ($request->type == 'create') {
            $user = Auth::user();
            $approval = $user->approval;
            if ($approval == null) {
                return response()->json(['status' => 'error', 'message' => 'Your position is not defined yet. Please contact your admin!'], 403);
            } else if (count($approval->itemsRecruitment) == 0) {
                return response()->json(['status' => 'error', 'message' => 'Setting approval is not defined yet. Please contact your admin!'], 403);
            }

            $data['position'] = StructureOrganizationResource::collection(StructureOrganizationCustom::orderBy('organisasi_division_id', 'ASC')->get());

            $data['grade'] = GradeResource::collection(Grade::all());

            $data['branch'] = Cabang::orderBy('id', 'ASC')->get();

            $data['employment_type'] = [
                [
                    'id' => 1,
                    'name' => 'Permanent',
                    'contract_duration' => false,
                ],
                [
                    'id' => 2,
                    'name' => 'Contract',
                    'contract_duration' => true,
                ],
                [
                    'id' => 3,
                    'name' => 'Internship',
                    'contract_duration' => true,
                ],
                [
                    'id' => 4,
                    'name' => 'Outsource',
                    'contract_duration' => true,
                ],
                [
                    'id' => 5,
                    'name' => 'Freelance',
                    'contract_duration' => true,
                ],
            ];

            $data['recruitment_type'] = [
                [
                    'id' => 1,
                    'name' => 'Internal',
                ],
                [
                    'id' => 2,
                    'name' => 'External',
                ],
            ];
        } else {
            if (!$request->user_id) {
                return response()->json(['status' => 'error', 'message' => 'User ID is required!'], 403);
            }

            $user = User::find($request->user_id);
            if (!$user) {
                return response()->json(['status' => 'error', 'message' => 'User is not found!'], 404);
            }

            $data = [];
        }

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
        $data['recruitment'] = new RecruitmentRequestResource(RecruitmentRequest::findOrFail($id));
        return response()->json(
            [
                'status' => 'success',
                'message' => 'Successfully',
                'data' => $data,
            ], 200);
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

    public function getApproval(Request $request)
    {
        $status = $request->status ? $request->status : "all";
        $user = Auth::user();
        $approval = null;
        if ($status == 'ongoing') {
            $approval = RecruitmentRequest::join('history_approval_recruitment as h', function ($join) use ($user) {
                $join->on('recruitment_request.id', '=', 'h.recruitment_request_id')
                    ->where('h.setting_approval_level_id', '=', DB::raw('(select min(setting_approval_level_id) from history_approval_recruitment where recruitment_request_id = recruitment_request.id and is_approved is null)'))
                    ->where('h.structure_organization_custom_id', '=', $user->structure_organization_custom_id);
            })
                ->where('recruitment_request.status', '=', 1)
                ->orderBy('created_at', 'DESC')
                ->select('recruitment_request.*');
        } else if ($status == 'history') {
            $approval = RecruitmentRequest::join('history_approval_recruitment as h', function ($join) use ($user) {
                $join->on('recruitment_request.id', '=', 'h.recruitment_request_id')
                    ->whereNotNull('h.is_approved')
                    ->where('h.structure_organization_custom_id', '=', $user->structure_organization_custom_id);
            })
                ->orderBy('created_at', 'DESC')
                ->select('recruitment_request.*');
        } else if ($status == 'all') {
            $approval = RecruitmentRequest::join('history_approval_recruitment as h', function ($join) use ($user) {
                $join->on('recruitment_request.id', '=', 'h.recruitment_request_id')
                    ->where('h.structure_organization_custom_id', '=', $user->structure_organization_custom_id);
            })
                ->orderBy('created_at', 'DESC')
                ->select('recruitment_request.*');
        }
        $totalData = $approval->get()->count();
        $approval = $approval->paginate(10);
        $data = [
            'current_page' => $approval->currentPage(), // get current page number
            'total_page' => $approval->total() ? $approval->lastPage() : $approval->total(), // get last page number
            'total_data' => $totalData,
            'recruitments' => RecruitmentRequestResource::collection($approval),
        ];
        return response()->json(
            [
                'status' => 'success',
                'message' => 'Successfully',
                'data' => $data,
            ], 200);
    }

    public function approve(Request $request)
    {
        info($request->all());
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'recruitment.id' => 'required|exists:cuti_karyawan,id',
            'approval.is_approved' => "required|in:1,0",
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()], 401);
        }

        $recruitment = RecruitmentRequest::find($request->recruitment['id']);
        $params = getEmailConfig();
        $params['data'] = $recruitment;
        $params['value'] = $recruitment->approvals;
        $params['subject'] = get_setting('mail_name') . ' - Recruitment Request';
        $params['view'] = 'email.recruitment-request-approval';

        $approval = HistoryApprovalRecruitment::where(['recruitment_request_id' => $recruitment->id, 'structure_organization_custom_id' => $user->structure_organization_custom_id])->first();
        $approval->approval_id = $user->id;
        $approval->is_approved = $request->approval['is_approved'];
        $approval->date_approved = date('Y-m-d H:i:s');
        $approval->save();

        $db = Config::get('database.default', 'mysql');

        $notifTitle = "";
        $notifType = "";
        $userApprovalTokens = [];
        $userApprovalIds = [];

        if ($approval->is_approved == 0) { // Jika rejected
            $recruitment->status = 3;
            $recruitment->approval_user = $approval->is_approved;
            $params['text'] = '<p><strong>Dear Sir/Madam ' . $recruitment->requestor->name . '</strong>,</p> <p>  Submission of your Recruitment Request <strong style="color: red;">REJECTED</strong>.</p>';
            Config::set('database.default', 'mysql');
            if (!empty($recruitment->requestor->email)) {
                $params['email'] = $recruitment->requestor->email;
                $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                dispatch($job);
            }
            Config::set('database.default', $db);

            $notifTitle = "Recruitment Request";
            $notifType = "recruitment";
            if ($recruitment->requestor->firebase_token) {
                array_push($userApprovalTokens, $recruitment->requestor->firebase_token);
            }
            array_push($userApprovalIds, $recruitment->requestor->id);
        } else if ($approval->is_approved == 1) {
            $lastApproval = $recruitment->approvals->last();
            if ($approval->structure_organization_custom_id == $lastApproval->structure_organization_custom_id) {
                $params['text'] = '<p><strong>Dear Sir/Madam ' . $recruitment->requestor->name . '</strong>,</p> <p>  Submission of your Recruitment Request <strong style="color: green;">APPROVED</strong>.</p>';
                $recruitment->status = 2;
                $recruitment->approval_user = $approval->is_approved;
                Config::set('database.default', 'mysql');
                if (!empty($recruitment->requestor->email)) {
                    $params['email'] = $recruitment->requestor->email;
                    $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                    dispatch($job);
                }
                Config::set('database.default', $db);

                $notifTitle = "Recruitment Request";
                $notifType = "recruitment";
                if ($recruitment->requestor->firebase_token) {
                    array_push($userApprovalTokens, $recruitment->requestor->firebase_token);
                }
                array_push($userApprovalIds, $recruitment->requestor->id);
            } else {
                $recruitment->status = 1;
                $nextApproval = HistoryApprovalRecruitment::where(['recruitment_request_id' => $recruitment->id, 'setting_approval_level_id' => ($approval->setting_approval_level_id + 1)])->first();
                if ($nextApproval) {
                    $userApproval = user_approval_custom($nextApproval->structure_organization_custom_id);
                    if ($userApproval) {
                        Config::set('database.default', 'mysql');
                        foreach ($userApproval as $key => $value) {
                            if (empty($value->email)) {
                                continue;
                            }

                            $params['text'] = '<p><strong>Dear Sir/Madam ' . $value->name . '</strong>,</p> <p> ' . $recruitment->requestor->name . '  / ' . $recruitment->requestor->nik . ' applied for Recruitment Request and currently waiting your approval.</p>';
                            $params['email'] = $value->email;
                            $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                            dispatch($job);
                        }
                        Config::set('database.default', $db);

                        $params['text'] = '<p> ' . $recruitment->requestor->name . '  / ' . $recruitment->requestor->nik . ' applied for Recruitment Request and currently waiting your approval.</p>';
                        $notifTitle = "Recruitment Request Approval";
                        $notifType = "recruitment_approval";
                        $userApprovalTokens = user_approval_firebase_tokens($nextApproval->structure_organization_custom_id);
                        $userApprovalIds = user_approval_id($nextApproval->structure_organization_custom_id);
                    }
                }
            }
        }
        $recruitment->save();

        foreach ($userApprovalIds as $value) {
            \FRDHelper::setNewData(strtolower($request->company), $value, $recruitment, $notifType);
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

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Recruitment Request Successfully Processed !',
            ], 200);
    }
}
