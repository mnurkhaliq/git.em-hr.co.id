<?php

namespace App\Http\Controllers;

use App\Models\OrganisasiDivision;
use App\Models\OrganisasiPosition;
use App\Models\TimesheetActivity;
use App\Models\TimesheetCategory;
use App\Models\TimesheetExport;
use App\Models\TimesheetListExport;
use App\Models\TimesheetPeriodTransaction;
use Carbon\Carbon;
use DataTables;
use Illuminate\Http\Request;

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
        $this->middleware('module:29');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = \Auth::user();

        \Session::put('filter_start', request()->filter_start);
        \Session::put('filter_end', request()->filter_end);
        \Session::put('timesheet_name', request()->timesheet_name);
        \Session::put('category', request()->category);
        \Session::put('activity', request()->activity);
        \Session::put('position', request()->position);
        \Session::put('division', request()->division);

        $filter_start = \Session::get('filter_start');
        $filter_end = \Session::get('filter_end');
        $name = \Session::get('timesheet_name');
        $category = \Session::get('category');
        $activity = \Session::get('activity');
        $position = \Session::get('position');
        $division = \Session::get('division');

        if (request()->reset == 1) {
            \Session::forget('filter_start');
            \Session::forget('filter_end');
            \Session::forget('timesheet_name');
            \Session::forget('category');
            \Session::forget('activity');
            \Session::forget('position');
            \Session::forget('division');
            return redirect()->route('timesheet.index');
        }

        if (request()->import == 1 || request()->eksport == 1) {
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
                ->where('timesheet_period_transactions.status', 2);

            if (!empty($name)) {
                $name = explode('-', $name, 2);
                $params['data'] = $params['data']->where(function ($table) use ($name) {
                    if (count($name) > 1) {
                        $table->where('users.name', ltrim(@$name[1]))->where('users.nik', rtrim(@$name[0]));
                    } else {
                        $table->where('users.name', 'LIKE', '%' . $name[0] . '%')->orWhere('users.nik', 'LIKE', '%' . $name[0] . '%');
                    }
                });
            }
            if (!empty($filter_start)) {
                $params['data'] = $params['data']->where('timesheet_period_transactions.date', '>=', Carbon::parse($filter_start)->startOfDay());
            }
            if (!empty($filter_end)) {
                $params['data'] = $params['data']->where('timesheet_period_transactions.date', '<=', Carbon::parse($filter_end)->endOfDay());
            }
            if (!empty($category)) {
                if ($category == 'other') {
                    $params['data'] = $params['data']->whereNull('timesheet_period_transactions.timesheet_category_id');
                } else {
                    $params['data'] = $params['data']->where('timesheet_period_transactions.timesheet_category_id', $category);
                }
            }
            if (!empty($activity)) {
                if ($activity == 'other') {
                    $params['data'] = $params['data']->whereNull('timesheet_period_transactions.timesheet_activity_id');
                } else {
                    $params['data'] = $params['data']->where('timesheet_period_transactions.timesheet_activity_id', $activity);
                }
            }
            if (!empty($position)) {
                $params['data'] = $params['data']->where('structure_organization_custom.organisasi_position_id', $position);
            }
            if (!empty($division)) {
                $params['data'] = $params['data']->where('structure_organization_custom.organisasi_division_id', $division);
            }
        }

        if (request()->import == 1) {
            return (new TimesheetListExport($params['data']->get()))->download('EM-HR.Timesheet-' . date('Y-m-d') . '.xlsx');
        }

        if (request()->eksport == 1) {
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
                        if (isset($val['activity'])) {
                            foreach ($val['activity'] as $val2) {
                                if (isset($val2['timesheet_category_id'])) {
                                    $calculated[$value->user_id]['total'][$val2['id']] = 0;
                                } else {
                                    $calculated[$value->user_id]['total'][$val2['id'] . $val['id']] = 0;
                                }
                            }
                        } else {
                            $calculated[$value->user_id]['total'][$val['id']] = 0;
                        }
                    }
                }

                if ($value->activity_id) {
                    $calculated[$value->user_id]['total'][$value->activity_id] += $value->duration / 8;
                } else {
                    if ($value->category_id) {
                        $calculated[$value->user_id]['total']['OTHER' . $value->category_id] += $value->duration / 8;
                    } else {
                        $calculated[$value->user_id]['total']['OTHER'] += $value->duration / 8;
                    }
                    $calculated[$value->user_id]['description'] .= ($calculated[$value->user_id]['description'] ? ', ' : '') . $value->activity;
                }
            }

            $filter_start = !empty($filter_start) ? $filter_start : $data->min('date');
            $filter_end = !empty($filter_end) ? $filter_end : $data->max('date');

            $date = [
                $filter_start ? Carbon::parse($filter_start)->format('d F Y') : '-',
                $filter_end ? Carbon::parse($filter_end)->format('d F Y') : '-',
            ];

            return (new TimesheetExport($data, $calculated, $date))->download('EM-HR.Timesheet-' . date('Y-m-d') . '.xlsx');
        }

        if (\Auth::user()->project_id != null) {
            $params['division'] = OrganisasiDivision::where('organisasi_division.project_id', \Auth::user()->project_id)->select('organisasi_division.*')->orderBy('organisasi_division.name', 'asc')->get();
            $params['position'] = OrganisasiPosition::where('organisasi_position.project_id', \Auth::user()->project_id)->select('organisasi_position.*')->orderBy('organisasi_position.name', 'asc')->get();
        } else {
            $params['division'] = OrganisasiDivision::orderBy('organisasi_division.name', 'asc')->get();
            $params['position'] = OrganisasiPosition::orderBy('organisasi_position.name', 'asc')->get();
        }

        return view('timesheet.index')->with($params);
    }

    public function table()
    {
        $user = \Auth::user();

        $filter_start = request()->filter_start;
        $filter_end = request()->filter_end;
        $name = request()->timesheet_name;
        $category = request()->category;
        $activity = request()->activity;
        $position = request()->position;
        $division = request()->division;

        $data = TimesheetPeriodTransaction::leftJoin('timesheet_periods', 'timesheet_periods.id', '=', 'timesheet_period_transactions.timesheet_period_id')
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
            ->where('timesheet_periods.status', 2)
            ->where('timesheet_period_transactions.status', 2);

        if (!empty($name)) {
            $name = explode('-', $name, 2);
            $data = $data->where(function ($table) use ($name) {
                if (count($name) > 1) {
                    $table->where('users.name', ltrim(@$name[1]))->where('users.nik', rtrim(@$name[0]));
                } else {
                    $table->where('users.name', 'LIKE', '%' . $name[0] . '%')->orWhere('users.nik', 'LIKE', '%' . $name[0] . '%');
                }
            });
        }
        if (!empty($filter_start)) {
            $data = $data->where('timesheet_period_transactions.date', '>=', Carbon::parse($filter_start)->startOfDay());
        }
        if (!empty($filter_end)) {
            $data = $data->where('timesheet_period_transactions.date', '<=', Carbon::parse($filter_end)->endOfDay());
        }
        if (!empty($category)) {
            if ($category == 'other') {
                $data = $data->whereNull('timesheet_period_transactions.timesheet_category_id');
            } else {
                $data = $data->where('timesheet_period_transactions.timesheet_category_id', $category);
            }
        }
        if (!empty($activity)) {
            if ($activity == 'other') {
                $data = $data->whereNull('timesheet_period_transactions.timesheet_activity_id');
            } else {
                $data = $data->where('timesheet_period_transactions.timesheet_activity_id', $activity);
            }
        }
        if (!empty($position)) {
            $data = $data->where('structure_organization_custom.organisasi_position_id', $position);
        }
        if (!empty($division)) {
            $data = $data->where('structure_organization_custom.organisasi_division_id', $division);
        }

        return DataTables::of($data)
            ->addColumn('column_category', function ($item) {
                return $item->category ?: 'Other Category';
            })
            ->addColumn('column_date', function ($item) {
                return date('l', strtotime($item->date));
            })
            ->addColumn('column_action', function ($item) {
                return '<button onclick="editTimesheet(\'' . $item->transaction_id . '\', \'' . $item->category_id . '\', \'' . $item->activity_id . '\', \'' . $item->activity . '\', \'' . $item->description . '\', \'' . $item->start_time . '\', \'' . $item->end_time . '\', \'' . $item->total_time . '\', \'' . $item->admin_note . '\')" type="button" class="btn btn-info btn-xs"><i class="fa fa-edit"></i> Edit </button>';
            })
            ->rawColumns(['column_category', 'column_date', 'column_action'])
            ->make(true);
    }

    /**
     * [create description]
     * @return [type] [description]
     */
    public function create()
    {
        //
    }

    /**
     * [edit description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function edit($id)
    {
        //
    }

    /**
     * [update description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * [desctroy description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function destroy($id)
    {
        //
    }

    /**
     * [store description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function store(Request $request)
    {
        $form = TimesheetPeriodTransaction::find($request->modal_id);
        $form->timesheet_category_id = $request->modal_category_id[0] != 'other' ? $request->modal_category_id[0] : null;
        $form->timesheet_category_name = $request->modal_category_id[0] != 'other' ? TimesheetCategory::where('id', $request->modal_category_id[0])->withTrashed()->first()->name : null;
        $form->timesheet_activity_id = $request->modal_activity_id[0] != 'other' ? $request->modal_activity_id[0] : null;
        $form->timesheet_activity_name = $request->modal_activity_id[0] != 'other' ? TimesheetActivity::where('id', $request->modal_activity_id[0])->withTrashed()->first()->name : $request->modal_activity_name[0];
        $form->start_time = $request->modal_start_time[0];
        $form->end_time = $request->modal_end_time[0];
        $form->total_time = $request->modal_total_time[0];
        $form->duration = (string) (explode(":", $request->modal_total_time[0])[0] + explode(":", $request->modal_total_time[0])[1] / 60);
        $form->description = $request->modal_description[0];
        $form->admin_note = $request->modal_admin_note[0];
        $form->save();

        foreach ($request->modal_category_id as $key => $value) {
            if ($key == 0) {
                continue;
            }

            $split = new TimesheetPeriodTransaction();
            $split->timesheet_period_id = $form->timesheet_period_id;
            $split->timesheet_category_id = $request->modal_category_id[$key] != 'other' ? $request->modal_category_id[$key] : null;
            $split->timesheet_category_name = $request->modal_category_id[$key] != 'other' ? TimesheetCategory::where('id', $request->modal_category_id[$key])->withTrashed()->first()->name : null;
            $split->timesheet_activity_id = $request->modal_activity_id[$key] != 'other' ? $request->modal_activity_id[$key] : null;
            $split->timesheet_activity_name = $request->modal_activity_id[$key] != 'other' ? TimesheetActivity::where('id', $request->modal_activity_id[$key])->withTrashed()->first()->name : $request->modal_activity_name[$key];
            $split->date = $form->date;
            $split->start_time = $request->modal_start_time[$key];
            $split->end_time = $request->modal_end_time[$key];
            $split->total_time = $request->modal_total_time[$key];
            $split->duration = (string) (explode(":", $request->modal_total_time[$key])[0] + explode(":", $request->modal_total_time[$key])[1] / 60);
            $split->description = $request->modal_description[$key];
            $split->admin_note = $request->modal_admin_note[$key];
            $split->status = $form->status;
            $split->approval_id = \Auth::user()->id;
            $split->date_approved = now();
            $split->save();
        }

        return redirect()->route('timesheet.index')->with('message-success', 'Data saved successfully !');
    }

    public function getActivity(Request $request)
    {
        return \App\Models\TimesheetActivity::where('timesheet_category_id', $request->id)->withTrashed()->get();
    }
}
