<?php

namespace App\Http\Controllers;

use App\Models\AbsensiItem;
use App\Models\LiburNasional;
use App\Models\SettingActivityVisit;
use App\Models\VisitExport;
use App\Models\VisitList;
use App\Models\VisitListExport;
use App\Models\VisitPict;
use App\User;
use Carbon\Carbon;
use DataTables;
use Illuminate\Http\Request;

class VisitController extends Controller
{
    /**
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
        $this->middleware('module:28');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = \Auth::user();

        \Session::put('filter_start', request()->filter_start);
        \Session::put('filter_end', request()->filter_end);
        \Session::put('visit_name', request()->visit_name);
        \Session::put('branch', request()->branch);
        \Session::put('position', request()->position);

        $filter_start = \Session::get('filter_start');
        $filter_end = \Session::get('filter_end');
        $name = \Session::get('visit_name');
        $branch = \Session::get('branch');
        $position = \Session::get('position');

        $start = str_replace('/', '-', $filter_start);
        $end = str_replace('/', '-', $filter_end);
        if (request()) {
            if (!empty($filter_start) && !empty($filter_end)) {
                $start = str_replace('/', '-', $filter_start);
                $end = str_replace('/', '-', $filter_end);
            }
        }

        if (request()->reset == 1) {
            \Session::forget('filter_start');
            \Session::forget('filter_end');
            \Session::forget('visit_name');
            \Session::forget('branch');
            \Session::forget('position');
            return redirect()->route('visit.index');
        }

        if ($user->project_id != null) {
            $params['data'] = VisitList::join('users', 'users.id', '=', 'visit_list.user_id')
                ->leftJoin('cabang', 'cabang.id', '=', 'visit_list.cabang_id')
                ->leftJoin('master_visit_type', 'visit_list.master_visit_type_id', '=', 'master_visit_type.id')
                ->leftJoin('master_category_visit', 'visit_list.master_category_visit_id', '=', 'master_category_visit.id')
                ->leftJoin('setting_visit_activity', 'visit_list.setting_visit_activity_id', '=', 'setting_visit_activity.id')
                ->select(
                    'users.nik as nik',
                    'users.name as username',
                    'master_visit_type.master_visit_type_name as master_visit_type_name',
                    'cabang.name as cabang_name',
                    'master_category_visit.master_category_name as master_category_name',
                    'visit_list.point as visit_point',
                    'visit_list.*'
                )
                ->whereNotNull('users.nik')
                ->whereNotNull('visit_list.visit_time')
                ->orderBy('visit_list.visit_time', 'DESC');
        } else {
            $params['data'] = VisitList::join('users', 'users.id', '=', 'visit_list.user_id')
                ->leftjoin('structure_organization_custom', 'users.structure_organization_custom_id', '=', 'structure_organization_custom.id')
                ->leftJoin('cabang', 'cabang.id', '=', 'visit_list.cabang_id')
                ->leftJoin('master_visit_type', 'visit_list.master_visit_type_id', '=', 'master_visit_type.id')
                ->leftJoin('master_category_visit', 'visit_list.master_category_visit_id', '=', 'master_category_visit.id')
                ->leftJoin('setting_visit_activity', 'visit_list.setting_visit_activity_id', '=', 'setting_visit_activity.id')
                ->select(
                    'users.nik as nik',
                    'users.name as username',
                    'master_visit_type.master_visit_type_name as master_visit_type_name',
                    'cabang.name as cabang_name',
                    'master_category_visit.master_category_name as master_category_name',
                    'visit_list.point as visit_point',
                    'visit_list.*'
                )
                ->whereNotNull('users.nik')
                ->whereNotNull('visit_list.visit_time')
                ->orderBy('visit_list.visit_time', 'DESC');
        }

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
        if (!empty($filter_start) and !empty($filter_end)) {
            $params['data'] = $params['data']->whereBetween('visit_list.visit_time', [Carbon::parse($start)->startOfDay(), Carbon::parse($end)->endOfDay()]);
        }
        if (!empty($branch)) {
            $params['data'] = $params['data']->where('visit_list.cabang_id', $branch);
        }
        if (!empty($position)) {
            $params['data'] = $params['data']->where('users.structure_organization_custom_id', $position);
        }

        if (request()->import == 1) {
            return (new VisitListExport($params['data']->get()))->download('EM-HR.Visit-' . date('Y-m-d') . '.xlsx');
        }

        if (request()->eksport == 1) {
            $data = $params['data']->get();

            $activity = SettingActivityVisit::select('id', 'activityname')->where('isactive', true)->orderBy('id', 'ASC')->get()->toArray();
            $otherActivity = [[
                'id' => 'Other',
                'activityname' => 'Other',
            ]];
            $calculated['activityname'] = array_merge($activity, $otherActivity);

            foreach ($data as $value) {
                if (!isset($calculated[$value->user_id])) {
                    $calculated[$value->user_id]['nik'] = $value->nik;
                    $calculated[$value->user_id]['name'] = $value->username;
                    $calculated[$value->user_id]['description'] = '';
                    foreach ($calculated['activityname'] as $val) {
                        $calculated[$value->user_id]['total'][$val['id']] = 0;
                    }
                }

                if ($value->setting_visit_activity_id) {
                    $calculated[$value->user_id]['total'][$value->setting_visit_activity_id] += $value->visit_point ?: 1;
                } else {
                    $calculated[$value->user_id]['total']['Other'] += 1;
                    $calculated[$value->user_id]['description'] .= ($calculated[$value->user_id]['description'] ? ', ' : '') . $value->activityname;
                }
            }

            return (new VisitExport($data, $calculated))->download('EM-HR.Visit-' . date('Y-m-d') . '.xlsx');
        }

        return view('visit.index')->with($params);
    }

    public function table()
    {
        $user = \Auth::user();

        $start = str_replace('/', '-', request()->filter_start);
        $end = str_replace('/', '-', request()->filter_end);
        $name = request()->visit_name;
        $branch = request()->branch;
        $position = request()->position;

        if ($user->project_id != null) {
            $data = VisitList::join('users', 'users.id', '=', 'visit_list.user_id')
                ->leftJoin('cabang', 'cabang.id', '=', 'visit_list.cabang_id')
                ->leftJoin('master_visit_type', 'visit_list.master_visit_type_id', '=', 'master_visit_type.id')
                ->leftJoin('master_category_visit', 'visit_list.master_category_visit_id', '=', 'master_category_visit.id')
                ->leftJoin('setting_visit_activity', 'visit_list.setting_visit_activity_id', '=', 'setting_visit_activity.id')
                ->select(
                    'users.nik as nik',
                    'users.name as username',
                    'master_visit_type.master_visit_type_name as master_visit_type_name',
                    'master_category_visit.master_category_name as master_category_name',
                    'visit_list.point as visit_point',
                    'visit_list.*',
                    \DB::raw("IF(master_visit_type.master_visit_type_name = 'Unlock' OR (master_visit_type.master_visit_type_name = 'Lock' AND isoutbranch = 1), placename, cabang.name) as place_name")
                )
                ->whereNotNull('users.nik')
                ->whereNotNull('visit_list.visit_time');
        } else {
            $data = VisitList::join('users', 'users.id', '=', 'visit_list.user_id')
                ->leftjoin('structure_organization_custom', 'users.structure_organization_custom_id', '=', 'structure_organization_custom.id')
                ->leftJoin('cabang', 'cabang.id', '=', 'visit_list.cabang_id')
                ->leftJoin('master_visit_type', 'visit_list.master_visit_type_id', '=', 'master_visit_type.id')
                ->leftJoin('master_category_visit', 'visit_list.master_category_visit_id', '=', 'master_category_visit.id')
                ->leftJoin('setting_visit_activity', 'visit_list.setting_visit_activity_id', '=', 'setting_visit_activity.id')
                ->select(
                    'users.nik as nik',
                    'users.name as username',
                    'master_visit_type.master_visit_type_name as master_visit_type_name',
                    'master_category_visit.master_category_name as master_category_name',
                    'visit_list.point as visit_point',
                    'visit_list.*',
                    \DB::raw("IF(master_visit_type.master_visit_type_name = 'Unlock' OR (master_visit_type.master_visit_type_name = 'Lock' AND isoutbranch = 1), placename, cabang.name) as place_name")
                )
                ->whereNotNull('users.nik')
                ->whereNotNull('visit_list.visit_time');
        }

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
        if (!empty($start) and !empty($end)) {
            $data = $data->whereBetween('visit_list.visit_time', [Carbon::parse($start)->startOfDay(), Carbon::parse($end)->endOfDay()]);
        }
        if (!empty($branch)) {
            $data = $data->where('visit_list.cabang_id', $branch);
        }
        if (!empty($position)) {
            $data = $data->where('users.structure_organization_custom_id', $position);
        }

        return DataTables::of($data)
            ->addColumn('column_date', function ($item) {
                if (!empty($item->longitude) || !empty($item->latitude) || !empty($item->pic)) {
                    return '<a href="javascript:void(0)" data-title="Visit Detail ' . $item->username . ' ' . date('d F Y h:i:s A', strtotime($item->visit_time)) . '" data-longitude="' . $item->longitude . '" data-signature="/' . $item->signature . '" data-description="' . $item->description . '" data-visittype="' . $item->master_visit_type_id . '" data-isoutbranch="' . $item->isoutbranch . '" data-visitid="' . $item->id . '" data-latitude="' . $item->latitude . '" data-picname="' . $item->picname . '" data-time="' . $item->visit_time . '" data-long-branch="' . $item->branchlongitude . '" data-lat-branch="' . $item->branchlatitude . '" data-radius-branch="' . $item->radius_visit . '" data-activity-name="' . $item->activityname . '" data-justification="' . $item->justification . '" data-placename="' . $item->placename . '" data-cabang="' . ($item->cabangDetail ? $item->cabangDetail->name : "") . '" data-location="' . $item->locationname . '" onclick="detail_visit(this)" title="Mobile Visit"> ' . $item->visit_time . '</a><i title="Mobile Visit" class="fa fa-location-arrow right" style="font-size: 20px;"></i>';
                } else {
                    return $item->visit_time;
                }
            })
            ->addColumn('column_day', function ($item) {
                if ($item->timetable == 'Sunday') {
                    return '<td><span style="color: red;">' . $item->timetable . '</span></td>';
                } else {
                    return '<td>' . $item->timetable . '</td>';
                }
            })
            ->rawColumns(['column_date', 'column_day', 'column_branch'])
            ->make(true);
    }

    public function ajaxHoliday()
    {
        $params['holidays'] = LiburNasional::all();
        $params['message'] = 'success';
        return response($params);
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
     * Detail Attandance
     * @param  $SN
     * @return objects
     */

    public function VisitList($SN)
    {
        $absensi_device_id = getVisitList($SN);

        $params['data'] = AbsensiItem::where('absensi_device_id', $absensi_device_id)->get();

        return view('visit.visit-detail')->with($params);
    }

    public function getVisitPhotos($visitid)
    {
        $data = VisitPict::select(
            'visit_list_id',
            \DB::raw("CONCAT('/', photo) AS photo"),
            'photocaption'
        )
            ->where('visit_list_id', $visitid)->get();
        if ($data) {
            if (count($data) > 0) {
                $res['message'] = 'success';
                $res['data'] = $data;
            } else {
                $res['message'] = 'failed';
            }
        } else {
            $res['message'] = 'failed';
        }

        return response($res);
    }

}
