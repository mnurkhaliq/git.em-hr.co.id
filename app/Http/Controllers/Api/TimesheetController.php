<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseApiController;
use App\Http\Middleware\ConnectionMiddleware;
use App\Http\Resources\TimesheetCategoryResource;
use App\Http\Resources\TimesheetPeriodResource;
use App\Models\TimesheetActivity;
use App\Models\TimesheetCategory;
use App\Models\TimesheetPeriod;
use App\Models\TimesheetPeriodTransaction;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class TimesheetController extends BaseApiController
{
    public function __construct()
    {
        $this->middleware(ConnectionMiddleware::class);
        $this->middleware('jwt.auth');
        parent::__construct();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $status = $request->input('status', '[1,2,3,4]');
        if (!isJsonValid($status)) {
            return response()->json(['status' => 'error', 'message' => 'Status is invalid'], 404);
        }

        $status = json_decode($status);
        $histories = TimesheetPeriod::where(['user_id' => $user->id])->whereIn('status', $status)->orderBy('start_date', 'DESC');
        $totalData = $histories->get()->count();
        $histories = $histories->paginate(10);
        $data = [
            'current_page' => $histories->currentPage(), // get current page number
            'total_page' => $histories->total() ? $histories->lastPage() : $histories->total(), // get last page number
            'total_data' => $totalData,
            'timesheets' => TimesheetPeriodResource::collection($histories),
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
        info($request->all());
        $user = Auth::user();
        // $approval = $user->approval;
        // if ($approval == null) {
        //     return response()->json(
        //         [
        //             'status' => 'error',
        //             'message' => 'Your position is not defined yet. Please contact your admin',
        //         ], 403);
        // } else if (count($approval->itemsTimesheet) == 0) {
        //     return response()->json(
        //         [
        //             'status' => 'error',
        //             'message' => 'Setting approval is not defined yet. Please contact your admin',
        //         ], 403);
        // }

        $validator = Validator::make($request->all(), [
            'id' => "exists:timesheet_periods,id",
            'start_date' => "required|date",
            'end_date' => "required|date",
            'status' => "required|in:1,4",
            'transactions' => "array",
            'transactions.*.id' => "exists:timesheet_period_transactions,id",
            'transactions.*.timesheet_category_id' => "exists:timesheet_categories,id",
            'transactions.*.timesheet_activity_id' => "exists:timesheet_activities,id",
            'transactions.*.date' => "required_with:transactions.*|date",
            'transactions.*.total_time' => "required_with:transactions.*|date_format:H:i",
            // 'transactions.*.description' => "required_if:status,1",
        ], [
            "required_if" => 'Complete the field before submit',
            "required_with" => 'Complete the field before submit',
            "required_without" => 'Complete the field before submit',
        ]);
        $validator->sometimes('transactions.*.timesheet_category_id', 'required_with:transactions.*', function ($request) {
            return $request->status == 1;
        });
        $validator->sometimes('transactions.*.timesheet_activity_id', 'required_without:transactions.*.timesheet_activity_name', function ($request) {
            return $request->status == 1;
        });
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()], 401);
        }

        if ($request->status == 1) {
            // $checkLastWeek = TimesheetPeriod::where('user_id', $user->id)->where('status', 2)->where('start_date', Carbon::parse($request->start_date)->subDays(7))->where('end_date', Carbon::parse($request->end_date)->subDays(7))->first();
            // $checkFirst = !TimesheetPeriod::where('user_id', $user->id)->where('status', '!=', 4)->where('start_date', '!=', $request->start_date)->where('end_date', '!=', $request->end_date)->first();
            // if (!$checkLastWeek && !$checkFirst) {
            //     return response()->json(
            //         [
            //             'status' => 'error',
            //             'message' => 'Total approved hours in previous week have not been fulfilled, you can only save as draft',
            //         ], 403);
            // }

            $array = [];
            $check = true;
            $total = 0;
            if ($request->transactions) {
                foreach ($request->transactions as $no => $transaction) {
                    if (!isset($array[$transaction['date']])) {
                        $array[$transaction['date']] = 0;
                    }
                    $array[$transaction['date']] += explode(":", $transaction['total_time'])[0] + explode(":", $transaction['total_time'])[1] / 60;
                }
            } else {
                $check = false;
            }
            foreach ($array as $key => $value) {
                if ($value != 8) {
                    $check = false;
                }
                $total += $value;
            }
            if (!$check) {
                return response()->json(
                    [
                        'status' => 'error',
                        'message' => 'Timesheet does not meet 8 hours per day, you can only save as draft',
                    ], 403);
            } else if ($total != 40) {
                return response()->json(
                    [
                        'status' => 'error',
                        'message' => 'Timesheet does not meet 40 hours per week, you can only save as draft',
                    ], 403);
            }
        }

        if ($request->id) {
            $data = TimesheetPeriod::find($request->id);
        } else {
            $data = TimesheetPeriod::where('user_id', $user->id)->where('start_date', $request->start_date)->where('end_date', $request->end_date)->first();
            if ($data) {
                return response()->json(
                    [
                        'status' => 'error',
                        'message' => 'Period already exist',
                    ], 403);
            }
            $data = new TimesheetPeriod();
        }
        $data->user_id = $user->id;
        $data->start_date = $request->start_date;
        $data->end_date = $request->end_date;
        $data->status = $request->status;
        $data->save();

        if ($data->status == 1) {
            $listCategoryId = [];
        }
        $array = [];
        if ($request->transactions) {
            foreach ($request->transactions as $no => $transaction) {
                if (isset($transaction['id'])) {
                    $form = TimesheetPeriodTransaction::find($transaction['id']);
                } else {
                    $form = new TimesheetPeriodTransaction();
                }
                $form->timesheet_period_id = $data->id;
                $form->timesheet_category_id = isset($transaction['timesheet_category_id']) ? $transaction['timesheet_category_id'] : null;
                $form->timesheet_category_name = isset($transaction['timesheet_category_id']) ? TimesheetCategory::where('id', $transaction['timesheet_category_id'])->withTrashed()->first()->name : null;
                $form->timesheet_activity_id = isset($transaction['timesheet_activity_id']) ? $transaction['timesheet_activity_id'] : null;
                $form->timesheet_activity_name = isset($transaction['timesheet_activity_id']) ? TimesheetActivity::where('id', $transaction['timesheet_activity_id'])->withTrashed()->first()->name : $transaction['timesheet_activity_name'];
                $form->date = $transaction['date'];
                $form->start_time = isset($transaction['start_time']) ? $transaction['start_time'] : null;
                $form->end_time = isset($transaction['end_time']) ? $transaction['end_time'] : null;
                $form->total_time = $transaction['total_time'];
                $form->duration = (string) (explode(":", $transaction['total_time'])[0] + explode(":", $transaction['total_time'])[1] / 60);
                $form->description = $transaction['description'];
                $form->status = isset($form->status) && $form->status == 2 ? $form->status : $data->status;
                $form->approval_id = isset($form->status) && $form->status == 2 ? $form->approval_id : null;
                $form->date_approved = isset($form->status) && $form->status == 2 ? $form->date_approved : null;
                $form->save();

                array_push($array, $form->id);

                if ($data->status == 1) {
                    if (!in_array($transaction['timesheet_category_id'], $listCategoryId)) {
                        array_push($listCategoryId, $transaction['timesheet_category_id']);
                    }
                }
            }
        }
        TimesheetPeriodTransaction::where('timesheet_period_id', $data->id)->whereNotIn('id', $array)->delete();

        if ($request->transactions && $data->status == 1) {
            $params = getEmailConfig();
            $db = Config::get('database.default', 'mysql');

            $params['data'] = $data;
            $params['value'] = TimesheetCategory::whereIn('id', $listCategoryId)->get();
            $params['view'] = 'email.timesheet';
            $params['subject'] = get_setting('mail_name') . ' - Timesheet';
            $userApprovalTokens = [];
            $userApprovalIds = [];
            $userApproval = [];
            foreach ($params['value'] as $no => $category) {
                Config::set('database.default', 'mysql');
                foreach ($category->settingApproval as $key => $value) {
                    if (!in_array($value->user->id, $userApproval)) {
                        array_push($userApproval, $value->user->id);

                        if($value->user->firebase_token) {
                            array_push($userApprovalTokens, $value->user->firebase_token);
                        }
                        array_push($userApprovalIds, $value->user->id);

                        if (empty($value->user->email)) {
                            continue;
                        }

                        $params['email'] = $value->user->email;
                        $params['text'] = '<p><strong>Dear Sir/Madam ' . $value->user->name . '</strong>,</p> <p> ' . $data->user->name . '  / ' . $data->user->nik . ' applied for Timesheet and currently waiting your approval.</p>';
                        $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                        dispatch($job);
                    }
                }
                Config::set('database.default', $db);
            }

            foreach ($userApprovalIds as $value) {
                \FRDHelper::setNewData(strtolower($request->company), $value, $data, 'timesheet_approval');
            }

            if(count($userApprovalTokens) > 0){
                $config = [
                    'title' => "Timesheet Approval",
                    'content' => strip_tags('<p> ' . $data->user->name . '  / ' . $data->user->nik . ' applied for Timesheet and currently waiting your approval.</p>'),
                    'type' => "timesheet_approval",
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
        }

        // $historyApproval = $user->approval->itemsTimesheet;
        // $settingApprovalItem = $historyApproval[0]->structure_organization_custom_id;
        // $historyApprov = HistoryApprovalTimesheet::where('timesheet_period_id', $data->id)->orderBy('setting_approval_level_id', 'ASC')->get();
        // if (!$historyApprov->count() && $data->status == 1) {
        //     foreach ($historyApproval as $level => $value) {
        //         $history = new HistoryApprovalTimesheet();
        //         $history->timesheet_period_id = $data->id;
        //         $history->setting_approval_level_id = ($level + 1);
        //         $history->structure_organization_custom_id = $value->structure_organization_custom_id;
        //         $history->save();
        //     }
        // } else if ($historyApprov->count()) {
        //     HistoryApprovalTimesheet::where('timesheet_period_id', $data->id)->update([
        //         'approval_id' => null,
        //         'is_approved' => null,
        //         'date_approved' => null,
        //     ]);
        // }

        // if ($data->status == 1) {
        //     $userApproval = user_approval_custom($settingApprovalItem);
        //     $params = getEmailConfig();
        //     $db = Config::get('database.default', 'mysql');

        //     $params['data'] = $data;
        //     $params['value'] = $historyApprov;
        //     $params['view'] = 'email.timesheet-approval-custom';
        //     $params['subject'] = get_setting('mail_name') . ' - Timesheet';
        //     if ($userApproval) {
        //         Config::set('database.default', 'mysql');
        //         foreach ($userApproval as $key => $value) {
        //             if (empty($value->email)) {
        //                 continue;
        //             }

        //             $params['email'] = $value->email;
        //             $params['text'] = '<p><strong>Dear Sir/Madam ' . $value->name . '</strong>,</p> <p> ' . $data->user->name . '  / ' . $data->user->nik . ' applied for Timesheet and currently waiting your approval.</p>';
        //             $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
        //             dispatch($job);
        //         }
        //         Config::set('database.default', $db);
        //     }
        // }

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Your timesheet request has successfully ' . ($data->status == 1 ? 'submitted' : 'drafted'),
            ], 201);
    }

    public function getInfo(Request $request)
    {
        $data['categories'] = TimesheetCategoryResource::collection(TimesheetCategory::get());
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully',
            'data' => $data,
        ], 200);
    }

    public function getParams(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'year' => "required_if:type,create|numeric",
            'month' => "required_if:type,create|numeric",
            'week' => "required_if:type,create|in:1,2,3,4,5,6",
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()], 401);
        }

        if ($request->type == 'create') {
            $user = Auth::user();
            // $approval = $user->approval;
            // if ($approval == null) {
            //     return response()->json(['status' => 'error', 'message' => 'Your position is not defined yet. Please contact your admin'], 403);
            // } else if (count($approval->itemsTimesheet) == 0) {
            //     return response()->json(['status' => 'error', 'message' => 'Setting approval is not defined yet. Please contact your admin'], 403);
            // }

            $data = [
                'start_date' => Carbon::createFromDate($request->year, $request->month, 1 + ($request->week - 1) * 7)->startOfWeek()->format('Y-m-d'),
                'end_date' => Carbon::createFromDate($request->year, $request->month, 1 + ($request->week - 1) * 7)->endOfWeek()->format('Y-m-d'),
            ];

            if (TimesheetPeriod::where('user_id', $user->id)->where('start_date', $data['start_date'])->where('end_date', $data['end_date'])->first()) {
                return response()->json(
                    [
                        'status' => 'error',
                        'message' => 'Period already exist',
                    ], 403);
            }
        } else {
            if (!$request->user_id) {
                return response()->json(['status' => 'error', 'message' => 'User ID is required'], 403);
            }

            $user = User::find($request->user_id);
            if (!$user) {
                return response()->json(['status' => 'error', 'message' => 'User is not found'], 404);
            }
        }

        $data['categories'] = TimesheetCategory::with('timesheetActivity')->get();

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
        $user = Auth::user();
        $data['timesheet'] = new TimesheetPeriodResource(TimesheetPeriod::findOrFail($id));
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
        $status = $request->status ?: "all";
        $user = Auth::user();
        $approval = null;
        if ($status == 'ongoing') {
            $approval = TimesheetPeriod::whereHas('timesheetPeriodTransaction', function ($query) use ($user) {
                $query->join('timesheet_categories as tc', function ($join) {
                    $join->on('tc.id', '=', 'timesheet_period_transactions.timesheet_category_id');
                })->join('setting_approval_timesheet_transaction_item as satti', function ($join) {
                    $join->on('tc.id', '=', 'satti.timesheet_category_id');
                })->where('status', '=', 1)->where('satti.user_id', '=', $user->id);
            })
                ->orderBy('start_date', 'DESC')
                ->select('timesheet_periods.*');
        } else if ($status == 'history') {
            $approval = TimesheetPeriod::whereHas('timesheetPeriodTransaction', function ($query) use ($user) {
                $query->join('timesheet_categories as tc', function ($join) {
                    $join->on('tc.id', '=', 'timesheet_period_transactions.timesheet_category_id');
                })->join('setting_approval_timesheet_transaction_item as satti', function ($join) {
                    $join->on('tc.id', '=', 'satti.timesheet_category_id');
                })->where(function ($query) {
                    $query->where('status', '=', 2)->orWhere('status', '=', 3);
                })->where('satti.user_id', '=', $user->id);
            })->whereDoesntHave('timesheetPeriodTransaction', function ($query) use ($user) {
                $query->join('timesheet_categories as tc', function ($join) {
                    $join->on('tc.id', '=', 'timesheet_period_transactions.timesheet_category_id');
                })->join('setting_approval_timesheet_transaction_item as satti', function ($join) {
                    $join->on('tc.id', '=', 'satti.timesheet_category_id');
                })->where(function ($query) use ($user) {
                    $query->where('status', '=', 1)->where('satti.user_id', '=', $user->id);
                })->orWhere('status', '=', 4);
            })
                ->orderBy('start_date', 'DESC')
                ->select('timesheet_periods.*');
        } else if ($status == 'all') {
            $approval = TimesheetPeriod::whereHas('timesheetPeriodTransaction', function ($query) use ($user) {
                $query->join('timesheet_categories as tc', function ($join) {
                    $join->on('tc.id', '=', 'timesheet_period_transactions.timesheet_category_id');
                })->join('setting_approval_timesheet_transaction_item as satti', function ($join) {
                    $join->on('tc.id', '=', 'satti.timesheet_category_id');
                })->where('status', '!=', 4)->where('satti.user_id', '=', $user->id);
            })->whereDoesntHave('timesheetPeriodTransaction', function ($query) {
                $query->where('status', '=', 4);
            })
                ->orderBy('start_date', 'DESC')
                ->select('timesheet_periods.*');
        }
        $totalData = $approval->get()->count();
        $approval = $approval->paginate(10);
        $data = [
            'current_page' => $approval->currentPage(), // get current page number
            'total_page' => $approval->total() ? $approval->lastPage() : $approval->total(), // get last page number
            'total_data' => $totalData,
            'timesheets' => TimesheetPeriodResource::collection($approval),
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
            'id' => "required|exists:timesheet_periods,id",
            'transactions' => "required|array",
            'transactions.*.id' => "required|exists:timesheet_period_transactions,id",
            'transactions.*.is_approved' => "required|in:1,0",
            'transactions.*.note' => "required_if:transactions.*.is_approved,0",
        ], [
            "required_if" => 'Reject must include note',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()], 401);
        }

        $categoryName = "";
        $listCategoryId = [];
        foreach ($request->transactions as $no => $transaction) {
            $form = TimesheetPeriodTransaction::find($transaction['id']);
            if (!in_array($form->timesheetCategory->id, $listCategoryId)) {
                array_push($listCategoryId, $form->timesheetCategory->id);

                if (!$form->timesheetCategory->settingApproval->where('user_id', $user->id)->first()) {
                    if (!$categoryName) {
                        $categoryName .= 'category ' . $form->timesheetCategory->name;
                    } else {
                        $categoryName .= ', ' . $form->timesheetCategory->name;
                    }
                }
            }
        }
        if ($categoryName) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'You are not entitled to approve ' . $categoryName,
                ], 403);
        }

        foreach ($request->transactions as $no => $transaction) {
            $form = TimesheetPeriodTransaction::find($transaction['id']);
            $form->status = $transaction['is_approved'] ? 2 : 3;
            $form->approval_id = $user->id;
            $form->approval_note = isset($transaction['note']) ? $transaction['note'] : null;
            $form->date_approved = Carbon::now();
            $form->save();
        }

        $timesheet = TimesheetPeriod::find($request->id);
        if (!$timesheet->timesheetPeriodTransaction->where('status', 1)->first()) {
            $params = getEmailConfig();
            $params['data'] = $timesheet;
            // $params['value'] = $timesheet->historyApproval;
            $params['subject'] = get_setting('mail_name') . ' - Timesheet';
            $params['view'] = 'email.timesheet-approval';

            // $approval = HistoryApprovalTimesheet::where(['timesheet_period_id' => $timesheet->id, 'structure_organization_custom_id' => $user->structure_organization_custom_id])->first();
            // $approval->approval_id = $user->id;
            // $approval->is_approved = (int) !count(array_filter($request->transactions, function ($transaction) {
            //     return ($transaction['is_approved'] == 0);
            // }));
            // $approval->date_approved = date('Y-m-d H:i:s');
            // $approval->save();

            $db = Config::get('database.default', 'mysql');

            $notifTitle = "";
            $notifType = "";
            $userApprovalTokens = [];
            $userApprovalIds = [];

            if ($timesheet->timesheetPeriodTransaction->where('status', 3)->first()) { // Jika rejected
                $timesheet->status = 3;
                $params['text'] = '<p><strong>Dear Sir/Madam ' . $timesheet->user->name . '</strong>,</p> <p>  Submission of your Timesheet <strong style="color: red;">REVISION</strong>.</p>';
                Config::set('database.default', 'mysql');
                if (!empty($timesheet->user->email)) {
                    $params['email'] = $timesheet->user->email;
                    $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                    dispatch($job);
                }
                Config::set('database.default', $db);

                $notifTitle = "Timesheet";
                $notifType  = "timesheet";
                if($timesheet->user->firebase_token) {
                    array_push($userApprovalTokens, $timesheet->user->firebase_token);
                }
                array_push($userApprovalIds, $timesheet->user->id);
            } else {
                $timesheet->status = 2;
                $params['text'] = '<p><strong>Dear Sir/Madam ' . $timesheet->user->name . '</strong>,</p> <p>  Submission of your Timesheet <strong style="color: green;">APPROVED</strong>.</p>';
                Config::set('database.default', 'mysql');
                if (!empty($timesheet->user->email)) {
                    $params['email'] = $timesheet->user->email;
                    $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                    dispatch($job);
                }
                Config::set('database.default', $db);

                $notifTitle = "Timesheet";
                $notifType  = "timesheet";
                if($timesheet->user->firebase_token) {
                    array_push($userApprovalTokens, $timesheet->user->firebase_token);
                }
                array_push($userApprovalIds, $timesheet->user->id);
            }

            foreach ($userApprovalIds as $value) {
                \FRDHelper::setNewData(strtolower($request->company), $value, $timesheet, $notifType);
            }

            if(count($userApprovalTokens) > 0){
                $config = [
                    'title' => $notifTitle,
                    'content' => strip_tags($params['text']),
                    'type' => $notifType,
                    'firebase_token' => $userApprovalTokens
                ];
                $notifData = [
                    'id' => $timesheet->id
                ];
                $db = Config::get('database.default', 'mysql');
                Config::set('database.default', 'mysql');
                dispatch((new \App\Jobs\SendPushTokens($notifData,$config))->onQueue('push'));
                Config::set('database.default', $db);
            }
        }

        // foreach ($request->transactions as $no => $transaction) {
        //     $form = TimesheetPeriodTransaction::find($transaction['id']);
        //     if ($timesheet->status == 1) {
        //         $nextNote = HistoryApprovalTimesheetNote::where('history_approval_timesheet_id', $nextApproval->id)->where('timesheet_transaction_id', $form->id)->first();
        //     }
        //     $form->status = $timesheet->status == 1 ? ($nextNote ? ($nextNote->is_approved ? $form->status : $timesheet->status) : $timesheet->status) : ($transaction['is_approved'] ? 2 : 3);
        //     $form->save();

        //     $note = HistoryApprovalTimesheetNote::firstOrNew(['history_approval_timesheet_id' => $approval->id, 'timesheet_transaction_id' => $form->id]);
        //     $note->note = isset($transaction['note']) ? $transaction['note'] : null;
        //     $note->is_approved = $transaction['is_approved'];
        //     $note->save();
        // }
        $timesheet->save();

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Form Timesheet Successfully Processed',
            ], 200);
    }

    public function getListWeeks(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'year' => "required|numeric",
            'month' => "required|numeric",
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()], 401);
        }

        $periods = TimesheetPeriod::where('user_id', $user->id)->get();
        $date = Carbon::createFromDate($request->year, $request->month, 1);

        $index = 0;
        while ($date->startOfWeek()->format('m') == $request->month || $date->endOfWeek()->format('m') == $request->month) {
            if (!TimesheetPeriod::where('user_id', $user->id)->where('start_date', $date->startOfWeek()->format('Y-m-d'))->where('end_date', $date->endOfWeek()->format('Y-m-d'))->first()) {
                $data['week'][] = $index + 1;
                $data['date'][] = $date->startOfWeek()->format('d M') . ' - ' . $date->endOfWeek()->format('d M');
            }

            $index++;
            $date->addWeeks(1);
        }

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Successfully',
                'data' => $data,
            ], 200);
    }
}
