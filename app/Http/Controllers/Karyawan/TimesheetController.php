<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\TimesheetActivity;
use App\Models\TimesheetCategory;
use App\Models\TimesheetPeriod;
use App\Models\TimesheetPeriodTransaction;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class TimesheetController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
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
        $params['data'] = TimesheetPeriod::where('user_id', \Auth::user()->id)->orderBy('start_date', 'DESC')->get();

        return view('karyawan.timesheet.index')->with($params);
    }

    public function search()
    {
        $params['data'] = TimesheetPeriod::where('user_id', \Auth::user()->id);

        if (request()) {
            if (!empty(request()->end_date)) {
                $params['data'] = $params['data']->where('start_date', '<=', Carbon::parse(request()->end_date)->startOfDay());
            }

            if (!empty(request()->start_date)) {
                $params['data'] = $params['data']->where('end_date', '>=', Carbon::parse(request()->start_date)->startOfDay());
            }
        }

        $params['data'] = $params['data']->orderBy('start_date', 'DESC')->get();

        return view('karyawan.timesheet.index')->with($params);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('karyawan.timesheet.create');
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
        //     return redirect()->back()->withInput()->withErrors('Your position is not defined yet. Please contact your admin');
        // } else if (count($approval->itemsTimesheet) == 0) {
        //     return redirect()->back()->withInput()->withErrors('Setting approval is not defined yet. Please contact your admin');
        // }

        $validator = Validator::make($request->all(), [
            'start_date' => "required|date",
            'end_date' => "required|date",
            'status' => "required|in:1,4",
            'transactions' => "array",
            'transactions.*.date' => "required_with:transactions.*|date",
            'transactions.*.start_time' => "required_with:transactions.*|date_format:H:i",
            'transactions.*.end_time' => "required_with:transactions.*|date_format:H:i",
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
            return redirect()->back()->withInput()->withErrors($validator->errors()->first());
        }

        if ($request->status == 1) {
            // $checkLastWeek = TimesheetPeriod::where('user_id', $user->id)->where('status', 2)->where('start_date', Carbon::parse($request->start_date)->subDays(7))->where('end_date', Carbon::parse($request->end_date)->subDays(7))->first();
            // $checkFirst = !TimesheetPeriod::where('user_id', $user->id)->where('status', '!=', 4)->where('start_date', '!=', $request->start_date)->where('end_date', '!=', $request->end_date)->first();
            // if (!$checkLastWeek && !$checkFirst) {
            //     return redirect()->back()->withInput()->withErrors('Total approved hours in previous week have not been fulfilled, you can only save as draft');
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
                return redirect()->back()->withInput()->withErrors('Timesheet does not meet 8 hours per day, you can only save as draft');
            } else if ($total != 40) {
                return redirect()->back()->withInput()->withErrors('Timesheet does not meet 40 hours per week, you can only save as draft');
            }
        }

        if ($request->id) {
            $data = TimesheetPeriod::find($request->id);
        } else {
            $data = TimesheetPeriod::where('user_id', $user->id)->where('start_date', $request->start_date)->where('end_date', $request->end_date)->first();
            if ($data) {
                return redirect()->back()->withInput()->withErrors('Period already exist');
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
                \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $value, $data, 'timesheet_approval');
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

        return redirect()->route('karyawan.timesheet.index')->with('message-success', 'Data saved successfully !');
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
        $params['data'] = TimesheetPeriod::find($id);

        if (!IsAccess($params['data'])) {
            return redirect()->route('karyawan.timesheet.index')->with('message-error', 'You don\'t have permission to perform this action!');
        }

        return view('karyawan.timesheet.edit')->with($params);
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

    public function getTimesheetCategory()
    {
        return \App\Models\TimesheetCategory::all();
    }

    public function getTimesheetActivity(Request $request)
    {
        return \App\Models\TimesheetActivity::where('timesheet_category_id', $request->id)->get();
    }

    public function getListWeeks(Request $request)
    {
        $user = Auth::user();

        if ($request->week) {
            $data = [
                'start_date' => Carbon::createFromDate($request->year, $request->month, 1 + ($request->week - 1) * 7)->startOfWeek()->format('Y-m-d'),
                'end_date' => Carbon::createFromDate($request->year, $request->month, 1 + ($request->week - 1) * 7)->endOfWeek()->format('Y-m-d'),
            ];
        } else {
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
        }

        return $data;
    }
}
