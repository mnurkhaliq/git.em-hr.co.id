<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseApiController;
use App\Http\Middleware\ConnectionMiddleware;
use App\Http\Resources\LeaveHistoryResource;
use App\Http\Resources\LeaveResource;
use App\Http\Resources\LeaveTypeResource;
use App\Http\Resources\UserMinResource;
use App\Models\Cuti;
use App\Models\CutiBersama;
use App\Models\CutiKaryawan;
use App\Models\CutiKaryawanDate;
use App\Models\HistoryApprovalLeave;
use App\Models\Shift;
use App\Models\ShiftScheduleChange;
use App\Models\UserCuti;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class LeaveController extends BaseApiController
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
        $user = Auth::user();
        $status = $request->input('status', '[1,2,3,5,6,7,8]');
        if (!isJsonValid($status)) {
            return response()->json(['status' => 'error', 'message' => 'Status is invalid'], 404);
        }

        $status = json_decode($status);
        $histories = CutiKaryawan::where(['user_id' => $user->id])->whereIn('status', $status)->orderBy('created_at', 'DESC');
        $totalData = $histories->get()->count();
        $histories = $histories->paginate(10);
        $data = [
            'current_page' => $histories->currentPage(), // get current page number
            'total_page' => $histories->total() ? $histories->lastPage() : $histories->total(), // get last page number
            'total_data' => $totalData,
            'employee_leaves' => LeaveResource::collection($histories),
            'leaves' => LeaveResource::collection($histories),
        ];
        return response()->json(
            [
                'status' => 'success',
                'message' => 'Successfully',
                'data' => $data,
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
        $user = Auth::user();
        $approval = $user->approval;
        if ($approval == null) {
            return response()->json(['status' => 'error', 'message' => 'Your position is not defined yet. Please contact your admin!'], 403);
        } else if (count($approval->items) == 0) {
            return response()->json(['status' => 'error', 'message' => 'Setting approval is not defined yet. Please contact your admin!'], 403);
        }

        $validator = Validator::make($request->all(), [
            'jenis_cuti'            => "required",
            'tanggal_cuti_start'    => "required|date",
            'tanggal_cuti_end'      => "required|date",
            'keperluan'             => "required",
            'backup_user_id'        => "required|integer",
            'jam_pulang_cepat'      => "date_format:H:i",
            'jam_datang_terlambat'  => "date_format:H:i",
            'total_cuti'            => "required|integer",
            'temp_kuota'            => "integer",
            'temp_cuti_terpakai'    => "integer",
            'temp_sisa_cuti'        => "integer",
            'details'               => "required|array",
            'details.*.date'        => "required|date",
            'details.*.type'        => "required|integer",
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()], 401);
        }

        $data = new CutiKaryawan();
        $data->user_id              = $user->id;
        $data->jenis_cuti           = $request->jenis_cuti;
        $data->tanggal_cuti_start   = date('Y-m-d', strtotime($request->tanggal_cuti_start));
        $data->tanggal_cuti_end     = date('Y-m-d', strtotime($request->tanggal_cuti_end));
        $data->keperluan            = $request->keperluan;
        $data->backup_user_id       = $request->backup_user_id;
        $data->status               = 1;

        $data->jam_pulang_cepat     = $request->jam_pulang_cepat;
        $data->jam_datang_terlambat = $request->jam_datang_terlambat;
        $data->total_cuti           = $request->total_cuti;
        $data->temp_kuota           = $request->temp_kuota;
        $data->temp_cuti_terpakai   = $request->temp_cuti_terpakai;
        $data->temp_sisa_cuti       = $request->temp_sisa_cuti;

        if (isset($request->attachment) && $request->hasFile('attachment')) {
            $fileName = date('H.i.s') . '.' . $request->file('attachment')->getClientOriginalExtension();
            $path = env('PATH_LEAVE_UPLOAD') . '/attachment/' . ($request->company ? strtolower($request->company) : 'umum') . '/' . date('Y-m-d') . '/' . $user->id;
            if (!is_dir(env('PATH_STORAGE_UPLOAD_SAAS') . $path)) {
                mkdir(env('PATH_STORAGE_UPLOAD_SAAS') . $path, 0755, true);
            }
            $request->file('attachment')->move(env('PATH_STORAGE_UPLOAD_SAAS') . $path, $fileName);
            $data->attachment = env('PATH_STORAGE_TUNNEL_SAAS') . $path . '/' . $fileName;
        }

        $data->save();

        foreach ($request->details as $no => $detail) {
            CutiKaryawanDate::create([
                'cuti_karyawan_id'  => $data->id,
                'tanggal_cuti'      => $detail['date'],
                'type'              => $detail['type'],
                'description'       => isset($detail['description']) && $detail['description'] ? $detail['description'] : 'Leave/permit day',
            ]);
        }

        $historyApproval = $user->approval->items;
        $settingApprovalItem = $historyApproval[0]->structure_organization_custom_id;
        foreach ($historyApproval as $level => $value) {
            # code...
            $history = new HistoryApprovalLeave();
            $history->cuti_karyawan_id                  = $data->id;
            $history->setting_approval_level_id         = ($level + 1);
            $history->structure_organization_custom_id  = $value->structure_organization_custom_id;
            $history->save();
        }
        $historyApprov = HistoryApprovalLeave::where('cuti_karyawan_id', $data->id)->orderBy('setting_approval_level_id', 'asc')->get();

        $userApproval = user_approval_custom($settingApprovalItem);
        $params = getEmailConfig();
        $db = Config::get('database.default', 'mysql');

        $params['data'] = $data;
        $params['value'] = $historyApprov;
        $params['view'] = 'email.leave-approval-custom';
        $params['subject'] = get_setting('mail_name') . ' - Submission of Leave / Permit';
        if ($userApproval) {
            Config::set('database.default', 'mysql');
            foreach ($userApproval as $key => $value) {
                if (empty($value->email)) {
                    continue;
                }

                $params['email'] = $value->email;
                $params['text'] = '<p><strong>Dear Sir/Madam ' . $value->name . '</strong>,</p> <p> ' . $data->user->name . '  / ' . $data->user->nik . ' applied for Leave/Permit and currently waiting your approval.</p>';
                $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                dispatch($job);
            }
            Config::set('database.default', $db);
            $params['text'] = '<p> ' . $data->user->name . '  / ' . $data->user->nik . ' applied for Leave/Permit and currently waiting your approval.</p>';
        }

        $userApprovalTokens = user_approval_firebase_tokens($settingApprovalItem);
        info("structure id ".$settingApprovalItem);
        info($userApprovalTokens);

        foreach (user_approval_id($settingApprovalItem) as $value) {
            \FRDHelper::setNewData(strtolower($request->company), $value, $data, 'leave_approval');
        }

        if(count($userApprovalTokens) > 0){
            $config = [
                'title' => "Leave/Permit Approval",
                'content' => strip_tags($params['text']),
                'type' => 'leave_approval',
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
                'message' => 'Your leave request has successfully submitted',
            ], 201);
    }

    public function getParams(Request $request)
    {
        if ($request->type == 'create') {
            $user = Auth::user();
            $approval = $user->approval;
            if ($approval == null) {
                return response()->json(['status' => 'error', 'message' => 'Your position is not defined yet. Please contact your admin!'], 403);
            } else if (count($approval->items) == 0) {
                return response()->json(['status' => 'error', 'message' => 'Setting approval is not defined yet. Please contact your admin!'], 403);
            }
        } else {
            if (!$request->user_id) {
                return response()->json(['status' => 'error', 'message' => 'User ID is required!'], 403);
            }

            $user = User::find($request->user_id);
            if (!$user) {
                return response()->json(['status' => 'error', 'message' => 'User is not found!'], 404);
            }
        }

        $start = Carbon::now()->subMonth(get_setting('min_leave_range') ?: 2)->startOfDay();
        $end = Carbon::now()->addMonths(get_setting('max_leave_range') ?: 2)->endOfDay();

        $data = [
            'is_pending' => CutiKaryawan::where('user_id', $user->id)->where('status', 1)->first() ? true : false,
            'start_date' => $start->format('Y-m-d'),
            'end_date' => $end->format('Y-m-d'),
        ];

        $publicHoliday = hari_libur($start, $end);

        $ShiftScheduleChange = ShiftScheduleChange::where('change_date', '<=', $end)->where('change_date', '>=', $start)->whereHas('shiftScheduleChangeEmployees', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->orderBy('change_date', 'DESC')->with('shift.details')->get();
        $currentShift = ShiftScheduleChange::where('change_date', '<', $start)->whereHas('shiftScheduleChangeEmployees', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->orderBy('change_date', 'DESC')->with('shift.details')->first();
        $currentShift = $currentShift ? $currentShift->shift : Shift::where('id', $user->shift_id)->with('details')->first();

        $leaveTaken = CutiKaryawanDate::whereBetween('tanggal_cuti', [$start, $end])->where('type', 1)->whereHas('cutiKaryawan', function ($query) use ($user) {
            $query->where('user_id', $user->id)->whereIn('status', [1, 2, 6, 8]);
        })->with('cutiKaryawan.cuti')->get();

        $disabledDates = [];
        $start->subDay();
        while ($start->diff($end)->days) {
            $loopDate = $start->addDay();
            $loopDateName = $loopDate->format('l');
            $loopDate = $loopDate->format('Y-m-d');

            $loopPublicHoliday = $publicHoliday->filter(function ($value) use ($loopDate) {
                return $value->tanggal == $loopDate;
            })->first();

            $loopShiftScheduleChange = $ShiftScheduleChange->filter(function ($value) use ($loopDate) {
                return $value->change_date <= $loopDate;
            })->first();
            $loopShiftScheduleChange = ($loopShiftScheduleChange ? $loopShiftScheduleChange->shift : $currentShift);
            $loopShiftScheduleChangeDay = $loopShiftScheduleChange ? $loopShiftScheduleChange->details->filter(function ($value) use ($loopDateName) {
                return $value->day == $loopDateName;
            })->first() : $loopShiftScheduleChange;

            $loopLeaveTaken = $leaveTaken->filter(function ($value) use ($loopDate) {
                return $value->tanggal_cuti == $loopDate;
            })->first();

            $cutiBersama = CutiBersama::where('dari_tanggal', $start)->first();

            if ($loopLeaveTaken) {
                $disabledDates[] = [
                    'date' => $loopDate,
                    'type' => 4,
                    'is_disabled' => true,
                    'description' => $loopLeaveTaken->cutiKaryawan->keperluan,
                ];
            } else if ($cutiBersama && (!$loopShiftScheduleChange || ($loopShiftScheduleChange && (!$cutiBersama->impacttoleave || !$loopShiftScheduleChange->is_collective)))) {
                $disabledDates[] = [
                    'date' => $loopDate,
                    'type' => $cutiBersama->impacttoleave ? 4 : 3,
                    'is_disabled' => !$loopShiftScheduleChange || ($loopShiftScheduleChange && !$loopShiftScheduleChange->is_collective) ? true : false,
                    'description' => $cutiBersama->description,
                ];
            } else if ($loopPublicHoliday) {
                $disabledDates[] = [
                    'date' => $loopDate,
                    'type' => 3,
                    'is_disabled' => !$loopShiftScheduleChange || ($loopShiftScheduleChange && (!$loopShiftScheduleChange->is_holiday || !$loopShiftScheduleChangeDay)) ? true : false,
                    'description' => $loopPublicHoliday->keterangan,
                ];
            } else if ($loopShiftScheduleChange && !$loopShiftScheduleChangeDay) {
                $disabledDates[] = [
                    'date' => $loopDate,
                    'type' => 2,
                    'is_disabled' => true,
                    'description' => "Shift off day",
                ];
            }
        }

        $leaveType = list_user_cuti($user, true)->with(['userCuti' => function ($query) use ($user) {
            $query->where('user_id', $user->id);
        }])->get();

        $data = array_merge($data, [
            'event_dates' => $disabledDates,
            'leave_type' => LeaveTypeResource::collection($leaveType),
        ]);

        return response()->json([
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
        $data['employee_leave'] = new LeaveResource(CutiKaryawan::findOrFail($id));
        $data['leave'] = new LeaveResource(CutiKaryawan::findOrFail($id));
        return response()->json(
            [
                'status' => 'success',
                'message' => 'Successfully',
                'data' => $data,
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

    public function getApproval(Request $request)
    {
        $status = $request->status ?: "all";
        $user = Auth::user();
        $approval = null;
        if ($status == 'ongoing') {
            $approval = CutiKaryawan::join('history_approval_leave as h', function ($join) use ($user) {
                $join->on('cuti_karyawan.id', '=', 'h.cuti_karyawan_id')
                    ->where('h.setting_approval_level_id', '=', DB::raw('(select min(setting_approval_level_id) from history_approval_leave where cuti_karyawan_id = cuti_karyawan.id and is_approved is null)'))
                    ->where('h.structure_organization_custom_id', '=', $user->structure_organization_custom_id);
            })
                ->whereIn('cuti_karyawan.status', [1, 6])
                ->orderBy('created_at', 'DESC')
                ->select('cuti_karyawan.*')
                ->groupBy('cuti_karyawan.id');
        } else if ($status == 'history') {
            $approval = CutiKaryawan::join('history_approval_leave as h', function ($join) use ($user) {
                $join->on('cuti_karyawan.id', '=', 'h.cuti_karyawan_id')
                    ->whereNotNull('h.is_approved')
                    ->where('h.structure_organization_custom_id', '=', $user->structure_organization_custom_id);
            })
                ->orderBy('created_at', 'DESC')
                ->select('cuti_karyawan.*')
                ->groupBy('cuti_karyawan.id');
        } else if ($status == 'all') {
            $approval = CutiKaryawan::join('history_approval_leave as h', function ($join) use ($user) {
                $join->on('cuti_karyawan.id', '=', 'h.cuti_karyawan_id')
                    ->where('h.structure_organization_custom_id', '=', $user->structure_organization_custom_id);
            })
                ->orderBy('created_at', 'DESC')
                ->select('cuti_karyawan.*')
                ->groupBy('cuti_karyawan.id');
        }
        $totalData = $approval->get()->count();
        $approval = $approval->paginate(10);
        $data = [
            'current_page' => $approval->currentPage(), // get current page number
            'total_page' => $approval->total() ? $approval->lastPage() : $approval->total(), // get last page number
            'total_data' => $totalData,
            'employee_leaves' => LeaveResource::collection($approval),
            'leaves' => LeaveResource::collection($approval),
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
            'leave.id'              => 'required|exists:cuti_karyawan,id',
            'approval.note'         => "required",
            'approval.is_approved'  => "required|in:1,0",
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()], 401);
        }

        $cutiKaryawan = CutiKaryawan::find($request->leave['id']);
        $params = getEmailConfig();
        $params['data'] = $cutiKaryawan;
        $params['value'] = $cutiKaryawan->historyApproval;
        $params['subject'] = get_setting('mail_name') . ' - Submission of Leave / Permit';
        $params['view'] = 'email.leave-approval-custom';

        $approval = HistoryApprovalLeave::where(['cuti_karyawan_id' => $cutiKaryawan->id, 'structure_organization_custom_id' => $user->structure_organization_custom_id])->latest('is_withdrawal')->first();
        $approval->approval_id      = $user->id;
        $approval->is_approved      = $request->approval['is_approved'];
        $approval->date_approved    = date('Y-m-d H:i:s');
        $approval->note             = $request->approval['note'];
        $approval->save();

        $db = Config::get('database.default', 'mysql');

        $notifTitle = "";
        $notifType = "";
        $userApprovalTokens = [];
        $userApprovalIds = [];

        if ($approval->is_approved == 0) { // Jika rejected
            if ($cutiKaryawan->status == 1) {
                $cutiKaryawan->status = 3;
                $params['text'] = '<p><strong>Dear Sir/Madam ' . $cutiKaryawan->user->name . '</strong>,</p> <p>  Submission of your Leave / Permit <strong style="color: red;">REJECTED</strong>.</p>';
            } else {
                $cutiKaryawan->status = 8;
                $params['text'] = '<p><strong>Dear Sir/Madam ' . $cutiKaryawan->user->name . '</strong>,</p> <p>  Submission of your Withdrawal Leave / Permit <strong style="color: red;">REJECTED</strong>.</p>';
            }
            Config::set('database.default', 'mysql');
            if (!empty($cutiKaryawan->user->email)) {
                $params['email'] = $cutiKaryawan->user->email;
                $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                dispatch($job);
            }
            Config::set('database.default', $db);

            $notifTitle = "Leave/Permit";
            $notifType  = "leave";
            if($cutiKaryawan->user->firebase_token) {
                array_push($userApprovalTokens, $cutiKaryawan->user->firebase_token);
            }
            array_push($userApprovalIds, $cutiKaryawan->user->id);
        } else if ($approval->is_approved == 1) {
            $lastApproval = $cutiKaryawan->historyApproval->last();
            if ($approval->structure_organization_custom_id == $lastApproval->structure_organization_custom_id) {

                $user_cuti = UserCuti::where('user_id', $cutiKaryawan->user_id)->where('cuti_id', $cutiKaryawan->jenis_cuti)->first();

                if (empty($user_cuti)) {
                    $cuti = Cuti::find($cutiKaryawan->jenis_cuti);
                    if ($cuti) {
                        $user_cuti                  = new UserCuti();
                        $user_cuti->kuota           = $cuti->kuota;
                        $user_cuti->user_id         = $cutiKaryawan->user_id;
                        $user_cuti->cuti_id         = $cutiKaryawan->jenis_cuti;
                        $user_cuti->cuti_terpakai   = $cutiKaryawan->total_cuti;
                        $user_cuti->sisa_cuti       = $cuti->kuota - $cutiKaryawan->total_cuti;
                        $user_cuti->save();
                    }
                } else {
                    if ($cutiKaryawan->status == 1) {
                        $user_cuti->cuti_terpakai       = $user_cuti->cuti_terpakai + $cutiKaryawan->total_cuti;
                        $user_cuti->sisa_cuti           = $user_cuti->kuota - $user_cuti->cuti_terpakai;
                        $user_cuti->save();
                    } else {
                        $user_cuti->cuti_terpakai       = $user_cuti->cuti_terpakai - $cutiKaryawan->total_cuti;
                        $user_cuti->sisa_cuti           = $user_cuti->sisa_cuti + $cutiKaryawan->total_cuti;
                        $user_cuti->save();
                    }

                    // // jika cuti maka kurangi kuota
                    // if(strpos($user_cuti->cuti->jenis_cuti, 'Cuti') !== false) {
                    //     // kurangi cuti tahunan user jika sudah di approved
                    //     $user_cuti->cuti_terpakai   = $user_cuti->cuti_terpakai + $cutiKaryawan->total_cuti;
                    //     $user_cuti->sisa_cuti       = $user_cuti->kuota - $user_cuti->cuti_terpakai;
                    //     $user_cuti->save();
                    // }
                }
                if ($cutiKaryawan->status == 1) {
                    $cutiKaryawan->temp_sisa_cuti       = $cutiKaryawan->temp_sisa_cuti - $cutiKaryawan->total_cuti;
                    $cutiKaryawan->temp_cuti_terpakai   = $cutiKaryawan->total_cuti + $cutiKaryawan->temp_cuti_terpakai;
                } else {
                    $cutiKaryawan->temp_sisa_cuti       = $cutiKaryawan->temp_sisa_cuti + $cutiKaryawan->total_cuti;
                    $cutiKaryawan->temp_cuti_terpakai   = $cutiKaryawan->temp_cuti_terpakai - $cutiKaryawan->total_cuti;
                }

                if ($cutiKaryawan->status == 1) {
                    $params['text'] = '<p><strong>Dear Sir/Madam ' . $cutiKaryawan->user->name . '</strong>,</p> <p>  Submission of your Leave / Permit <strong style="color: green;">APPROVED</strong>.</p>';
                    $cutiKaryawan->status = 2;
                } else {
                    $params['text'] = '<p><strong>Dear Sir/Madam ' . $cutiKaryawan->user->name . '</strong>,</p> <p>  Submission of your Withdrawal Leave / Permit <strong style="color: green;">APPROVED</strong>.</p>';
                    $cutiKaryawan->status = 7;
                }
                Config::set('database.default', 'mysql');
                if (!empty($cutiKaryawan->user->email)) {
                    $params['email'] = $cutiKaryawan->user->email;
                    $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                    dispatch($job);
                }
                Config::set('database.default', $db);

                $notifTitle = "Leave/Permit";
                $notifType  = "leave";
                if($cutiKaryawan->user->firebase_token) {
                    array_push($userApprovalTokens, $cutiKaryawan->user->firebase_token);
                }
                array_push($userApprovalIds, $cutiKaryawan->user->id);
            } else {
                if ($cutiKaryawan->status == 1) {
                    $cutiKaryawan->status = 1;
                } else {
                    $cutiKaryawan->status = 6;
                }
                $nextApproval = HistoryApprovalLeave::where(['cuti_karyawan_id' => $cutiKaryawan->id, 'setting_approval_level_id' => ($approval->setting_approval_level_id + 1)])->first();
                if ($nextApproval) {
                    $userApproval = user_approval_custom($nextApproval->structure_organization_custom_id);
                    if ($userApproval) {
                        Config::set('database.default', 'mysql');
                        foreach ($userApproval as $key => $value) {
                            if ($value->email == "") {
                                continue;
                            }

                            if ($cutiKaryawan->status == 1) {
                                $params['text'] = '<p><strong>Dear Sir/Madam ' . $value->name . '</strong>,</p> <p> ' . $cutiKaryawan->user->name . '  / ' . $cutiKaryawan->user->nik . ' applied for Leave/Permit and currently waiting your approval.</p>';
                            } else {
                                $params['text'] = '<p><strong>Dear Sir/Madam ' . $value->name . '</strong>,</p> <p> ' . $cutiKaryawan->user->name . '  / ' . $cutiKaryawan->user->nik . ' applied for Withdrawal Leave/Permit and currently waiting your approval.</p>';
                            }
                            $params['email'] = $value->email;
                            $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                            dispatch($job);
                        }
                        Config::set('database.default', $db);

                        if ($cutiKaryawan->status == 1) {
                            $params['text'] = '<p> ' . $cutiKaryawan->user->name . '  / ' . $cutiKaryawan->user->nik . ' applied for Leave/Permit and currently waiting your approval.</p>';
                        } else {
                            $params['text'] = '<p> ' . $cutiKaryawan->user->name . '  / ' . $cutiKaryawan->user->nik . ' applied for Withdrawal Leave/Permit and currently waiting your approval.</p>';
                        }
                        $notifTitle = "Leave Approval";
                        $notifType  = "leave_approval";
                        $userApprovalTokens = user_approval_firebase_tokens($nextApproval->structure_organization_custom_id);
                        $userApprovalIds = user_approval_id($nextApproval->structure_organization_custom_id);
                    }
                }
            }
        }
        $cutiKaryawan->save();

        foreach ($userApprovalIds as $value) {
            \FRDHelper::setNewData(strtolower($request->company), $value, $cutiKaryawan, $notifType);
        }

        if(count($userApprovalTokens) > 0){
            $config = [
                'title' => $notifTitle,
                'content' => strip_tags($params['text']),
                'type' => $notifType,
                'firebase_token' => $userApprovalTokens
            ];
            $notifData = [
                'id' => $cutiKaryawan->id
            ];
            $db = Config::get('database.default', 'mysql');
            Config::set('database.default', 'mysql');
            dispatch((new \App\Jobs\SendPushTokens($notifData,$config))->onQueue('push'));
            Config::set('database.default', $db);
        }

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Form Leave Successfully Processed !',
            ], 200);
    }

    public function cancel(Request $request) {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'leave.id'              => 'required|exists:cuti_karyawan,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()], 401);
        }

        $data = CutiKaryawan::find($request->leave['id']);
        if ($data->status == 1) {
            $data->status = 5;
            $historyApprov = HistoryApprovalLeave::where('cuti_karyawan_id', $data->id)->where('setting_approval_level_id', '<=', DB::raw('(select min(setting_approval_level_id) from history_approval_leave where cuti_karyawan_id = '.$request->leave['id'].' and is_approved is null)'))->get();
            foreach ($historyApprov as $level => $settingApprovalItem) {
                $settingApprovalItem = $settingApprovalItem->structure_organization_custom_id;
                $userApproval = user_approval_custom($settingApprovalItem);
                $params = getEmailConfig();
                $db = Config::get('database.default', 'mysql');
    
                $params['data'] = $data;
                $params['value'] = $historyApprov;
                $params['view'] = 'email.leave-approval-custom';
                $params['subject'] = get_setting('mail_name') . ' - Submission of Leave / Permit';
                if ($userApproval) {
                    Config::set('database.default', 'mysql');
                    foreach ($userApproval as $key => $value) {
                        if (empty($value->email)) {
                            continue;
                        }
    
                        $params['email'] = $value->email;
                        $params['text'] = '<p><strong>Dear Sir/Madam ' . $value->name . '</strong>,</p> <p> ' . $data->user->name . '  / ' . $data->user->nik . ' applied for Cancel Leave/Permit.</p>';
                        $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                        dispatch($job);
                    }
                    Config::set('database.default', $db);
                    $params['text'] = '<p> ' . $data->user->name . '  / ' . $data->user->nik . ' applied for Cancel Leave/Permit.</p>';
                }
    
                $userApprovalTokens = user_approval_firebase_tokens($settingApprovalItem);
                info("structure id ".$settingApprovalItem);
                info($userApprovalTokens);
                
                foreach (user_approval_id($settingApprovalItem) as $value) {
                    \FRDHelper::setNewData(strtolower($request->company), $value, $data, 'leave_approval');
                }

                if(count($userApprovalTokens) > 0){
                    $config = [
                        'title' => "Leave/Permit Approval",
                        'content' => strip_tags($params['text']),
                        'type' => 'leave_approval',
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
                // $data->historyApproval()->delete();
            }
        } else if ($data->status == 2) {
            $data->status = 6;
            // $data->historyApproval()->delete();
            $historyApproval = $user->approval->items;
            $settingApprovalItem = $historyApproval[0]->structure_organization_custom_id;
            foreach ($historyApproval as $level => $value) {
                # code...
                $history = new HistoryApprovalLeave();
                $history->cuti_karyawan_id                  = $data->id;
                $history->setting_approval_level_id         = ($level + 1);
                $history->structure_organization_custom_id  = $value->structure_organization_custom_id;
                $history->is_withdrawal                     = 1;
                $history->save();
            }
            $historyApprov = HistoryApprovalLeave::where('cuti_karyawan_id', $data->id)->orderBy('setting_approval_level_id', 'asc')->get();

            $userApproval = user_approval_custom($settingApprovalItem);
            $params = getEmailConfig();
            $db = Config::get('database.default', 'mysql');

            $params['data'] = $data;
            $params['value'] = $historyApprov;
            $params['view'] = 'email.leave-approval-custom';
            $params['subject'] = get_setting('mail_name') . ' - Submission of Leave / Permit';
            if ($userApproval) {
                Config::set('database.default', 'mysql');
                foreach ($userApproval as $key => $value) {
                    if (empty($value->email)) {
                        continue;
                    }

                    $params['email'] = $value->email;
                    $params['text'] = '<p><strong>Dear Sir/Madam ' . $value->name . '</strong>,</p> <p> ' . $data->user->name . '  / ' . $data->user->nik . ' applied for Withdrawal Leave/Permit and currently waiting your approval.</p>';
                    $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                    dispatch($job);
                }
                Config::set('database.default', $db);
                $params['text'] = '<p> ' . $data->user->name . '  / ' . $data->user->nik . ' applied for Withdrawal Leave/Permit and currently waiting your approval.</p>';
            }

            $userApprovalTokens = user_approval_firebase_tokens($settingApprovalItem);
            info("structure id ".$settingApprovalItem);
            info($userApprovalTokens);
                            
            foreach (user_approval_id($settingApprovalItem) as $value) {
                \FRDHelper::setNewData(strtolower($request->company), $value, $data, 'leave_approval');
            }

            if(count($userApprovalTokens) > 0){
                $config = [
                    'title' => "Leave/Permit Approval",
                    'content' => strip_tags($params['text']),
                    'type' => 'leave_approval',
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
        } else {
            return response()->json(['status' => 'error', 'message' => 'Submit request with this status is not allowed!'], 403);
        }
        $data->save();

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Your leave request has successfully submitted',
            ], 201);
    }

    public function getBackupPerson(Request $request)
    {
        $user = Auth::user();
        $param = $request->param;
        $users = User::where('id', '!=', $user->id)->where(function ($q) use ($param) {
            $q->where('nik', 'like', '%' . $param . '%')->orWhere('name', 'like', '%' . $param . '%');
        })->paginate(10);
        $data['users'] = UserMinResource::collection($users);

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Successfully',
                'data' => $data,
            ], 200);
    }

    public function getHistory(Request $request)
    {
        $user_id = $request->user_id ?: Auth::user()->id;

        $histories = CutiKaryawan::where('user_id', $user_id);
        if ($request->jenis_cuti) {
            $histories = $histories->where('jenis_cuti', $request->jenis_cuti);
        }
        $histories = $histories->whereIn('status', [2, 6, 8])->orderBy('created_at', 'DESC');
        $totalData = $histories->get()->count();
        $histories = $histories->paginate(10);
        $data = [
            'current_page' => $histories->currentPage(), // get current page number
            'total_page' => $histories->total() ? $histories->lastPage() : $histories->total(), // get last page number
            'total_data' => $totalData,
            'employee_leaves' => LeaveHistoryResource::collection($histories),
            'leaves' => LeaveHistoryResource::collection($histories),
        ];

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Successfully',
                'data' => $data,
            ], 200);
    }
}
