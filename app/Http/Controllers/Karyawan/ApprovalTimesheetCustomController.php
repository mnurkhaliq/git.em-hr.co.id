<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\OrganisasiDivision;
use App\Models\OrganisasiPosition;
use App\Models\TimesheetPeriod;
use App\Models\TimesheetPeriodTransaction;
use App\Models\TimesheetCategory;
use App\Models\TimesheetExport;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class ApprovalTimesheetCustomController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()) {

            if (request()->eksport == 1) {
                return $this->download();
            }

            $params['data'] = TimesheetPeriod::join('users', 'timesheet_periods.user_id', '=', 'users.id')->whereHas('timesheetPeriodTransaction', function ($query) {
                $query->join('timesheet_categories as tc', function ($join) {
                    $join->on('tc.id', '=', 'timesheet_period_transactions.timesheet_category_id');
                })->join('setting_approval_timesheet_transaction_item as satti', function ($join) {
                    $join->on('tc.id', '=', 'satti.timesheet_category_id');
                })->where('status', '!=', 4)->where('satti.user_id', '=', \Auth::user()->id);
            })->whereDoesntHave('timesheetPeriodTransaction', function ($query) {
                $query->where('status', '=', 4);
            });

            if (!empty(request()->name)) {
                $params['data'] = $params['data']->where(function ($table) {
                    $table->where('users.name', 'LIKE', '%' . request()->name . '%')->orWhere('users.nik', 'LIKE', '%' . request()->name . '%');
                });
            }

            if ((!empty(request()->division_id)) && (empty(request()->position_id))) {
                $params['data'] = $params['data']->join('structure_organization_custom', 'users.structure_organization_custom_id', '=', 'structure_organization_custom.id')->where('structure_organization_custom.organisasi_division_id', request()->division_id);
            }
            if ((!empty(request()->position_id)) && (empty(request()->division_id))) {
                $params['data'] = $params['data']->join('structure_organization_custom', 'users.structure_organization_custom_id', '=', 'structure_organization_custom.id')->where('structure_organization_custom.organisasi_position_id', request()->position_id);
            }
            if ((!empty(request()->position_id)) && (!empty(request()->division_id))) {
                $params['data'] = $params['data']->join('structure_organization_custom', 'users.structure_organization_custom_id', '=', 'structure_organization_custom.id')->where('structure_organization_custom.organisasi_position_id', request()->position_id)->where('structure_organization_custom.organisasi_division_id', request()->division_id);
            }

            if (!empty(request()->end_date)) {
                $params['data'] = $params['data']->where('timesheet_periods.start_date', '<=', Carbon::parse(request()->end_date)->startOfDay());
            }

            if (!empty(request()->start_date)) {
                $params['data'] = $params['data']->where('timesheet_periods.end_date', '>=', Carbon::parse(request()->start_date)->startOfDay());
            }

            $params['data'] = $params['data']->select('timesheet_periods.*')->orderBy('start_date', 'DESC')->get();
        } else {
            $params['data'] = cek_timesheet_approval();
        }

        if (\Auth::user()->project_id != null) {
            $params['division'] = OrganisasiDivision::where('organisasi_division.project_id', \Auth::user()->project_id)->select('organisasi_division.*')->orderBy('organisasi_division.name', 'asc')->get();
            $params['position'] = OrganisasiPosition::where('organisasi_position.project_id', \Auth::user()->project_id)->select('organisasi_position.*')->orderBy('organisasi_position.name', 'asc')->get();
        } else {
            $params['division'] = OrganisasiDivision::orderBy('organisasi_division.name', 'asc')->get();
            $params['position'] = OrganisasiPosition::orderBy('organisasi_position.name', 'asc')->get();
        }

        return view('karyawan.approval-timesheet-custom.index')->with($params);
    }

    public function download()
    {
        $user = \Auth::user();

        $params['data'] = TimesheetPeriodTransaction::leftJoin('timesheet_periods', 'timesheet_periods.id', '=', 'timesheet_period_transactions.timesheet_period_id')
            ->leftJoin('users', 'users.id', '=', 'timesheet_periods.user_id')
            ->leftJoin('structure_organization_custom', 'structure_organization_custom.id', '=', 'users.structure_organization_custom_id')
            ->leftJoin('organisasi_position', 'organisasi_position.id', '=', 'structure_organization_custom.organisasi_position_id')
            ->leftJoin('organisasi_division', 'organisasi_division.id', '=', 'structure_organization_custom.organisasi_division_id')
            ->leftJoin('timesheet_activities', 'timesheet_activities.id', '=', 'timesheet_period_transactions.timesheet_activity_id')
            ->leftJoin('timesheet_categories', 'timesheet_categories.id', '=', 'timesheet_period_transactions.timesheet_category_id')
            ->select(
                'timesheet_period_transactions.id as transaction_id',
                'users.id as user_id',
                'users.nik as nik',
                'users.name as username',
                'organisasi_position.name as position',
                'organisasi_division.name as division',
                'timesheet_period_transactions.timesheet_category_id as category_id',
                \DB::Raw('IFNULL(timesheet_categories.name, timesheet_period_transactions.timesheet_category_name) as category'),
                'timesheet_period_transactions.timesheet_activity_id as activity_id',
                \DB::Raw('IFNULL(timesheet_activities.name, timesheet_period_transactions.timesheet_activity_name) as activity'),
                'timesheet_period_transactions.description as description',
                'timesheet_period_transactions.date as date',
                'timesheet_period_transactions.duration as duration',
                'timesheet_period_transactions.start_time as start_time',
                'timesheet_period_transactions.end_time as end_time',
                'timesheet_period_transactions.total_time as total_time',
                'timesheet_period_transactions.admin_note as admin_note'
            )
            ->orderBy('timesheet_period_transactions.date', 'DESC')->orderBy('timesheet_period_transactions.start_time', 'DESC')
            ->where('timesheet_periods.status', 2)
            ->where('timesheet_period_transactions.approval_id', $user->id);

        if (!empty(request()->name)) {
            $params['data'] = $params['data']->where(function ($table) {
                $table->where('users.name', 'LIKE', '%' . request()->name . '%')->orWhere('users.nik', 'LIKE', '%' . request()->name . '%');
            });
        }

        if (!empty(request()->start_date)) {
            $params['data'] = $params['data']->where('timesheet_period_transactions.date', '>=', Carbon::parse(request()->start_date)->startOfDay());
        }
        if (!empty(request()->end_date)) {
            $params['data'] = $params['data']->where('timesheet_period_transactions.date', '<=', Carbon::parse(request()->end_date)->endOfDay());
        }

        if (!empty(request()->position)) {
            $params['data'] = $params['data']->where('structure_organization_custom.organisasi_position_id', request()->position);
        }
        if (!empty(request()->division)) {
            $params['data'] = $params['data']->where('structure_organization_custom.organisasi_division_id', request()->division);
        }

        $data = $params['data']->get();

        $other = [
            'id' => 'OTHER',
            'name' => 'Other Activity',
        ];
        $calculated['name'] = [];

        $activity = TimesheetCategory::withTrashed()->get();
        foreach ($activity as $key => $value) {
            array_unshift($calculated['name'], ['id' => $value->id, 'name' => $value->name, 'activity' => array_merge($value->timesheetActivity()->withTrashed()->get()->toArray(), [$other])]);
        }

        foreach ($data as $value) {
            if (!isset($calculated[$value->user_id])) {
                $calculated[$value->user_id]['nik'] = $value->nik;
                $calculated[$value->user_id]['name'] = $value->username;
                $calculated[$value->user_id]['description'] = '';
                foreach ($calculated['name'] as $val) {
                    if(isset($val['activity']))
                        foreach ($val['activity'] as $val2) {
                            if(isset($val2['timesheet_category_id']))
                                $calculated[$value->user_id]['total'][$val2['id']] = 0;
                            else
                                $calculated[$value->user_id]['total'][$val2['id'].$val['id']] = 0;
                        }
                    else
                        $calculated[$value->user_id]['total'][$val['id']] = 0;
                }
            }
            
            if ($value->activity_id) {
                $calculated[$value->user_id]['total'][$value->activity_id] += $value->duration;
            } else {
                if ($value->category_id) {
                    $calculated[$value->user_id]['total']['OTHER'.$value->category_id] += $value->duration;
                } else {
                    $calculated[$value->user_id]['total']['OTHER'] += $value->duration;
                }
                $calculated[$value->user_id]['description'] .= ($calculated[$value->user_id]['description'] ? ', ' : '') . $value->activity;
            }
        }

        request()->start_date = !empty(request()->start_date) ? request()->start_date : $data->min('date');
        request()->end_date = !empty(request()->end_date) ? request()->end_date : $data->max('date');

        $date = [
            request()->start_date ? Carbon::parse(request()->start_date)->format('d F Y') : '-',
            request()->end_date ? Carbon::parse(request()->end_date)->format('d F Y') : '-'
        ];

        return (new TimesheetExport($data, $calculated, $date))->download('EM-HR.Timesheet-' . date('Y-m-d') . '.xlsx');
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

    /**
     * [detail description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function detail($id)
    {
        $params['data'] = cek_timesheet_id_approval($id);

        if (!$params['data']) {
            return redirect()->route('karyawan.approval.timesheet-custom.index')->with('message-error', 'You don\'t have permission to perform this action!');
        }

        return view('karyawan.approval-timesheet-custom.detail')->with($params);
    }

    public function proses(Request $request)
    {
        info($request->all());
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'id' => "required|exists:timesheet_periods,id",
            'transactions' => "required|array",
            'transactions.*.id' => "required|exists:timesheet_period_transactions,id",
            'transactions.*.is_approved' => "required_if:transactions.*.is_approving,1|in:1,0",
            'transactions.*.note' => "required_if:transactions.*.is_approved,0",
        ], [
            "required_if" => 'Reject must include note',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator->errors()->first());
        }

        $categoryName = "";
        $listCategoryId = [];
        foreach ($request->transactions as $no => $transaction) {
            $form = TimesheetPeriodTransaction::find($transaction['id']);
            if (!in_array($form->timesheetCategory->id, $listCategoryId)) {
                array_push($listCategoryId, $form->timesheetCategory->id);
                if ($transaction['is_approving'] && !$form->timesheetCategory->settingApproval->where('user_id', $user->id)->first()) {
                    if (!$categoryName) {
                        $categoryName .= 'category ' . $form->timesheetCategory->name;
                    } else {
                        $categoryName .= ', ' . $form->timesheetCategory->name;
                    }
                }
            }
        }
        if ($categoryName) {
            return redirect()->back()->withInput()->withErrors('You are not entitled to approve ' . $categoryName);
        }

        foreach ($request->transactions as $no => $transaction) {
            if ($transaction['is_approving']) {
                $form = TimesheetPeriodTransaction::find($transaction['id']);
                $form->status = $transaction['is_approved'] ? 2 : 3;
                $form->approval_id = $user->id;
                $form->approval_note = isset($transaction['note']) ? $transaction['note'] : null;
                $form->date_approved = Carbon::now();
                $form->save();
            }
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
                \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $value, $timesheet, $notifType);
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

        return redirect()->route('karyawan.approval.timesheet-custom.index')->with('message-success', 'Form Timesheet Successfully Processed !');
    }
}
