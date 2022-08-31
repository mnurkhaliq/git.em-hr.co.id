<?php

namespace App\Http\Controllers;

use App\Imports\AttendanceImport;
use App\Models\Absensi;
use App\Models\AbsensiItem;
use App\Models\AbsensiItemTemp;
use App\Models\AbsensiSetting;
use App\Models\AttendanceExport;
use App\Models\AttendanceExportList;
use App\Models\LiburNasional;
use App\Models\OrganisasiDivision;
use App\Models\OrganisasiPosition;
use App\Models\Setting;
use App\Models\Shift;
use App\Models\ShiftDetail;
use App\Models\ShiftScheduleChange;
use App\Models\ShiftScheduleChangeEmployee;
use App\Models\StructureOrganizationCustom;
use App\User;
use Carbon\Carbon;
use DataTables;
use DB;
use Illuminate\Http\Request;

class AttendanceController extends Controller
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
        $this->middleware('module:15');
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
        \Session::put('attendance_name', request()->attendance_name);
        \Session::put('branch', request()->branch);
        \Session::put('position', request()->position);
        \Session::put('division', request()->division);

        $filter_start = \Session::get('filter_start');
        $filter_end = \Session::get('filter_end');
        $name = \Session::get('attendance_name');
        $branch = \Session::get('branch');
        $position = \Session::get('position');
        $division = \Session::get('division');

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
            \Session::forget('attendance_name');
            \Session::forget('branch');
            \Session::forget('position');
            \Session::forget('division');
            return redirect()->route('attendance.index');
        }

        if ($user->project_id != null) {
            $params['data'] = AbsensiItem::join('users', 'users.id', '=', 'absensi_item.user_id')
                ->leftJoin('structure_organization_custom', 'structure_organization_custom.id', '=', 'users.structure_organization_custom_id')
                ->leftJoin('shift', 'shift.id', '=', 'absensi_item.shift_id')
                ->leftJoin('shift_detail', 'shift.id', '=', 'shift_detail.shift_id')
                ->whereIn('users.access_id', ['1', '2'])
                ->whereNotNull('users.nik')
                ->whereNotNull('absensi_item.date')
                ->whereNotIn('absensi_item.date', ['1970-01-01'])
                ->where(function ($query) {
                    $query->whereColumn('shift_detail.day', 'absensi_item.timetable')
                        ->orWhereNull('absensi_item.shift_id')
                        ->orWhere('absensi_item.shift_id', 0);
                })
                ->where(function ($query) use ($start) {
                    $query->whereNull('users.non_active_date')->orWhere('users.non_active_date', '>', ($start ?: \Carbon\Carbon::now()));
                })
                ->where(function ($query) use ($end) {
                    $query->whereNull('users.join_date')->orWhere('users.join_date', '<=', ($end ?: \Carbon\Carbon::now()));
                })
                ->where('users.project_id', $user->project_id)
                ->select(
                    'users.nik',
                    'users.name as username',
                    'shift.name as shift',
                    'absensi_item.*',
                    'shift_detail.clock_in as shift_in',
                    'shift_detail.clock_out as shift_out'
                )
                ->orderBy('absensi_item.date', 'DESC')
                ->orderBy('absensi_item.clock_in', 'DESC');
        } else {
            $params['data'] = AbsensiItem::join('users', 'users.id', '=', 'absensi_item.user_id')
                ->leftJoin('structure_organization_custom', 'structure_organization_custom.id', '=', 'users.structure_organization_custom_id')
                ->leftJoin('shift', 'shift.id', '=', 'absensi_item.shift_id')
                ->leftJoin('shift_detail', 'shift.id', '=', 'shift_detail.shift_id')
                ->whereIn('users.access_id', ['1', '2'])
                ->whereNotNull('users.nik')
                ->whereNotNull('absensi_item.date')
                ->whereNotIn('absensi_item.date', ['1970-01-01'])
                ->where(function ($query) {
                    $query->whereColumn('shift_detail.day', 'absensi_item.timetable')
                        ->orWhereNull('absensi_item.shift_id')
                        ->orWhere('absensi_item.shift_id', 0);
                })
                ->where(function ($query) use ($start) {
                    $query->whereNull('users.non_active_date')->orWhere('users.non_active_date', '>', ($start ?: \Carbon\Carbon::now()));
                })
                ->where(function ($query) use ($end) {
                    $query->whereNull('users.join_date')->orWhere('users.join_date', '<=', ($end ?: \Carbon\Carbon::now()));
                })
                ->select(
                    'users.nik',
                    'users.name as username',
                    'shift.name as shift',
                    'absensi_item.*',
                    'shift_detail.clock_in as shift_in',
                    'shift_detail.clock_out as shift_out'
                )
                ->orderBy('absensi_item.date', 'DESC')
                ->orderBy('absensi_item.clock_in', 'DESC');
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
            $params['data'] = $params['data']->whereBetween('absensi_item.date', [$start, $end]);
        }

        if (!empty($branch)) {
            $params['data'] = $params['data']->where('users.cabang_id', $branch);
        }

        if (!empty($position)) {
            $params['data'] = $params['data']->where('structure_organization_custom.organisasi_position_id', $position);
        }
        if (!empty($division)) {
            $params['data'] = $params['data']->where('structure_organization_custom.organisasi_division_id', $division);
        }

        if (request()->import == 1) {
            return (new AttendanceExport($params['data']))->download('EM-HR.Attendance-' . date('Y-m-d') . '.xlsx');
        }

        if (request()->eksport == 1) {
            return (new AttendanceExport($params['data']))->download('EM-HR.Attendance-' . date('Y-m-d') . '.xlsx');
        }

        if (\Auth::user()->project_id != null) {
            $params['division'] = OrganisasiDivision::where('organisasi_division.project_id', \Auth::user()->project_id)->select('organisasi_division.*')->orderBy('organisasi_division.name', 'asc')->get();
            $params['position'] = OrganisasiPosition::where('organisasi_position.project_id', \Auth::user()->project_id)->select('organisasi_position.*')->orderBy('organisasi_position.name', 'asc')->get();
        } else {
            $params['division'] = OrganisasiDivision::orderBy('organisasi_division.name', 'asc')->get();
            $params['position'] = OrganisasiPosition::orderBy('organisasi_position.name', 'asc')->get();
        }

        return view('attendance.index')->with($params);
    }

    public function table()
    {
        $user = \Auth::user();

        $start = str_replace('/', '-', request()->filter_start);
        $end = str_replace('/', '-', request()->filter_end);
        $name = request()->attendance_name;
        $branch = request()->branch;
        $position = request()->position;
        $division = request()->division;

        if ($user->project_id != null) {
            $data = AbsensiItem::join('users', 'users.id', '=', 'absensi_item.user_id')
                ->leftJoin('structure_organization_custom', 'structure_organization_custom.id', '=', 'users.structure_organization_custom_id')
                ->leftJoin('shift', 'shift.id', '=', 'absensi_item.shift_id')
                ->leftJoin('shift_detail', 'shift.id', '=', 'shift_detail.shift_id')
                ->leftJoin('cabang as ci', 'absensi_item.cabang_id_in', '=', 'ci.id')
                ->leftJoin('cabang as co', 'absensi_item.cabang_id_out', '=', 'co.id')
                ->whereIn('users.access_id', ['1', '2'])
                ->whereNotNull('users.nik')
                ->whereNotNull('absensi_item.date')
                ->whereNotIn('absensi_item.date', ['1970-01-01'])
                ->where(function ($query) {
                    $query->whereColumn('shift_detail.day', 'absensi_item.timetable')
                        ->orWhereNull('absensi_item.shift_id')
                        ->orWhere('absensi_item.shift_id', 0);
                })
                ->where(function ($query) use ($start) {
                    $query->whereNull('users.non_active_date')->orWhere('users.non_active_date', '>', ($start ?: \Carbon\Carbon::now()));
                })
                ->where(function ($query) use ($end) {
                    $query->whereNull('users.join_date')->orWhere('users.join_date', '<=', ($end ?: \Carbon\Carbon::now()));
                })
                ->where('users.project_id', $user->project_id)
                ->select(
                    'users.nik',
                    'users.name as username',
                    'shift.name as shift',
                    'absensi_item.*',
                    'shift_detail.clock_in as shift_in',
                    'shift_detail.clock_out as shift_out'
                );
        } else {
            $data = AbsensiItem::join('users', 'users.id', '=', 'absensi_item.user_id')
                ->leftJoin('structure_organization_custom', 'structure_organization_custom.id', '=', 'users.structure_organization_custom_id')
                ->leftJoin('shift', 'shift.id', '=', 'absensi_item.shift_id')
                ->leftJoin('shift_detail', 'shift.id', '=', 'shift_detail.shift_id')
                ->leftJoin('cabang as ci', 'absensi_item.cabang_id_in', '=', 'ci.id')
                ->leftJoin('cabang as co', 'absensi_item.cabang_id_out', '=', 'co.id')
                ->whereIn('users.access_id', ['1', '2'])
                ->whereNotNull('users.nik')
                ->whereNotNull('absensi_item.date')
                ->whereNotIn('absensi_item.date', ['1970-01-01'])
                ->where(function ($query) {
                    $query->whereColumn('shift_detail.day', 'absensi_item.timetable')
                        ->orWhereNull('absensi_item.shift_id')
                        ->orWhere('absensi_item.shift_id', 0);
                })
                ->where(function ($query) use ($start) {
                    $query->whereNull('users.non_active_date')->orWhere('users.non_active_date', '>', ($start ?: \Carbon\Carbon::now()));
                })
                ->where(function ($query) use ($end) {
                    $query->whereNull('users.join_date')->orWhere('users.join_date', '<=', ($end ?: \Carbon\Carbon::now()));
                })
                ->select(
                    'users.nik',
                    'users.name as username',
                    'shift.name as shift',
                    'absensi_item.*',
                    'shift_detail.clock_in as shift_in',
                    'shift_detail.clock_out as shift_out'
                );
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
            $data = $data->whereBetween('absensi_item.date', [$start, $end]);
        }

        if (!empty($branch)) {
            $data = $data->where('users.cabang_id', $branch);
        }

        if (!empty($position)) {
            $data = $data->where('structure_organization_custom.organisasi_position_id', $position);
        }
        if (!empty($division)) {
            $data = $data->where('structure_organization_custom.organisasi_division_id', $division);
        }

        return DataTables::of($data)
            ->addColumn('column_date', function ($item) {
                if ($item->timetable == 'Sunday' || ($item->is_holiday == 0 && hari_libur($item->date, $item->date)->count())) {
                    return '<td><span style="color: red;">' . $item->date . '</span></td>';
                } else {
                    return '<td>' . $item->date . '</td>';
                }
            })
            ->addColumn('column_shift', function ($item) {
                if ($item->shift_id == 0) {
                    return '<td>No Shift</td>';
                } else {
                    return '<td>' . $item->shift . '</td>';
                }
            })
            ->addColumn('column_clock_in', function ($item) {
                if (!empty($item->long) || !empty($item->lat) || !empty($item->pic)) {
                    if (str_contains($item->pic, 'upload/attendance')) {
                        return '<a href="javascript:void(0)" data-title="Clock In ' . date('d F Y', strtotime($item->date)) . ' ' . $item->clock_in . '" data-long="' . $item->long . '" data-lat="' . $item->lat . '" data-pic="' . asset('/' . $item->pic) . '" data-time="' . $item->clock_in . '" data-long-office="' . $item->long_office_in . '" data-lat-office="' . $item->lat_office_in . '" data-radius-office="' . $item->radius_office_in . '" data-attendance-type="' . $item->attendance_type_in . '" data-justification="' . $item->justification_in . '" data-cabang="' . ($item->cabangIn ? $item->cabangIn->name : "") . '" data-location="' . $item->location_name_in . '" onclick="detail_attendance(this)" title="Web Attendance"> ' . $item->clock_in . ' ' . ($item->attendance_type_in == "remote" ? "(R)" : ($item->attendance_type_in == "out_of_office" ? "(O)" : "")) . '</a><i title="Web Attendance" class="fa fa-desktop pull-right"></i>';
                    } else {
                        return '<a href="javascript:void(0)" data-title="Clock In ' . date('d F Y', strtotime($item->date)) . ' ' . $item->clock_in . '" data-long="' . $item->long . '" data-lat="' . $item->lat . '" data-pic="' . asset('upload/attendance/' . $item->pic) . '" data-time="' . $item->clock_in . '" data-long-office="' . $item->long_office_in . '" data-lat-office="' . $item->lat_office_in . '" data-radius-office="' . $item->radius_office_in . '" data-attendance-type="' . $item->attendance_type_in . '" data-justification="' . $item->justification_in . '" data-cabang="' . ($item->cabangIn ? $item->cabangIn->name : "") . '" data-location="' . $item->location_name_in . '" onclick="detail_attendance(this)" title="Mobile Attendance"> ' . $item->clock_in . ' ' . ($item->attendance_type_in == "remote" ? "(R)" : ($item->attendance_type_in == "out_of_office" ? "(O)" : "")) . '</a><i title="Mobile Attendance" class="fa fa-mobile pull-right" style="font-size: 20px;"></i>';
                    }
                } else {
                    return $item->clock_in;
                }
            })
            ->addColumn('column_clock_out', function ($item) {
                if (!empty($item->long_out) || !empty($item->lat_out) || !empty($item->pic_out)) {
                    if (str_contains($item->pic_out, 'upload/attendance')) {
                        return '<a href="javascript:void(0)" data-title="Clock Out ' . date('d F Y', strtotime($item->date_out)) . ' ' . $item->clock_out . '" data-long="' . $item->long_out . '" data-lat="' . $item->lat_out . '" data-pic="' . asset('/' . $item->pic_out) . '" data-time="' . $item->clock_out . '" data-long-office="' . $item->long_office_out . '" data-lat-office="' . $item->lat_office_out . '" data-radius-office="' . $item->radius_office_out . '" data-attendance-type="' . $item->attendance_type_out . '" data-justification="' . $item->justification_out . '" data-cabang="' . ($item->cabangOut ? $item->cabangOut->name : "") . '" data-location="' . $item->location_name_out . '" onclick="detail_attendance(this)" title="Web Attendance"> ' . $item->clock_out . ' ' . ($item->attendance_type_out == "remote" ? "(R)" : ($item->attendance_type_out == "out_of_office" ? "(O)" : "")) . '' . ($item->date != $item->date_out ? " (ND)" : "") . '</a><i title="Web Attendance" class="fa fa-desktop pull-right"></i>';
                    } else {
                        return '<a href="javascript:void(0)" data-title="Clock Out ' . date('d F Y', strtotime($item->date_out)) . ' ' . $item->clock_out . '" data-long="' . $item->long_out . '" data-lat="' . $item->lat_out . '" data-pic="' . asset('upload/attendance/' . $item->pic_out) . '" data-time="' . $item->clock_out . '" data-long-office="' . $item->long_office_out . '" data-lat-office="' . $item->lat_office_out . '" data-radius-office="' . $item->radius_office_out . '" data-attendance-type="' . $item->attendance_type_out . '" data-justification="' . $item->justification_out . '" data-cabang="' . ($item->cabangOut ? $item->cabangOut->name : "") . '" data-location="' . $item->location_name_out . '" onclick="detail_attendance(this)" title="Mobile Attendance"> ' . $item->clock_out . ' ' . ($item->attendance_type_out == "remote" ? "(R)" : ($item->attendance_type_out == "out_of_office" ? "(O)" : "")) . '' . ($item->date != $item->date_out ? " (ND)" : "") . '</a><i title="Mobile Attendance" class="fa fa-mobile pull-right" style="font-size: 20px;"></i>';
                    }
                } else {
                    $item->clock_out;
                }
            })
            ->addColumn('column_branch_in', function ($item) {
                return $item->cabangIn ? $item->cabangIn->name : '';
            })
            ->addColumn('column_branch_out', function ($item) {
                return $item->cabangOut ? $item->cabangOut->name : '';
            })
            ->rawColumns(['column_date', 'column_shift', 'column_clock_in', 'column_clock_out', 'column_branch_in', 'column_branch_out'])
            ->make(true);
    }

    function list() {
        \Session::put('start', request()->start ?: Carbon::now()->subMonth()->startOfDay()->format('Y/m/d'));
        \Session::put('end', request()->end ?: Carbon::now()->startOfDay()->format('Y/m/d'));
        \Session::put('min', request()->min);
        \Session::put('name', request()->name);
        \Session::put('branch', request()->branch);
        \Session::put('position', request()->position);

        $start = \Session::get('start');
        $end = \Session::get('end');
        $min = \Session::get('min');
        $name = \Session::get('name');
        $branch = \Session::get('branch');
        $position = \Session::get('position');

        if (request()->reset == 1) {
            \Session::forget('start');
            \Session::forget('end');
            \Session::forget('min');
            \Session::forget('name');
            \Session::forget('branch');
            \Session::forget('position');
            return redirect()->route('attendance.list');
        }

        $params['data'] = User::withCount(['absensiItem' => function ($query) use ($start, $end) {
            $query->whereBetween('date', [$start, $end])->select(DB::raw('count(distinct(date))'));
        }])->where(function ($query) use ($start) {
            $query->whereNull('non_active_date')->orWhere('non_active_date', '>', $start);
        })->where(function ($query) use ($end) {
            $query->whereNull('join_date')->orWhere('join_date', '<=', $end);
        });

        if (!empty($min)) {
            $params['data'] = $params['data']->having('absensi_item_count', '<=', $min);
        }
        if (!empty($name)) {
            $name = explode('-', $name, 2);
            $params['data'] = $params['data']->where(function ($table) use ($name) {
                if (count($name) > 1) {
                    $table->where('name', ltrim(@$name[1]))->where('nik', rtrim(@$name[0]));
                } else {
                    $table->where('name', 'LIKE', '%' . $name[0] . '%')->orWhere('nik', 'LIKE', '%' . $name[0] . '%');
                }
            });
        }
        if (!empty($branch)) {
            $params['data'] = $params['data']->where('cabang_id', $branch);
        }
        if (!empty($position)) {
            $params['data'] = $params['data']->where('structure_organization_custom_id', $position);
        }

        $params['data'] = $params['data']->get();

        if (request()->eksport == 1) {
            $params['min'] = $min ?: false;
            $params['date'] = Carbon::parse($start)->format('d F Y') . ' - ' . Carbon::parse($end)->format('d F Y');
            return (new AttendanceExportList($params))->download('EM-HR.Attendance_Summary-' . date('Y-m-d') . '.xlsx');
        }

        return view('attendance.list')->with($params);
    }

    public function ajaxHoliday()
    {
        $params['holidays'] = LiburNasional::all();
        $params['message'] = 'success';

        return response($params);
    }

    /**
     * Save Setting
     * @param  Request $request
     * @return void
     */
    public function settingSave(Request $request)
    {
        $user = \Auth::user();

        if ($request->setting_mobile) {
            foreach ($request->setting_mobile as $key => $value) {
                if ($user->project_id != null) {
                    $setting = Setting::where('key', $key)->where('project_id', $user->project_id)->first();
                } else {
                    $setting = Setting::where('key', $key)->first();
                }
                if (!$setting) {
                    $setting = new Setting();
                    $setting->key = $key;
                }
                $setting->user_created = $user->id;
                $setting->project_id = $user->project_id;
                $setting->value = $value;
                $setting->save();
            }
        }

        if ($request->hasFile('attendance_logo')) {
            $file = $request->file('attendance_logo');
            $fileName = md5(rand() . $file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();

            $destinationPath = public_path('/upload/setting');
            $file->move($destinationPath, $fileName);

            if ($user->project_id != null) {
                $setting = Setting::where('key', 'attendance_logo')->where('project_id', $user->project_id)->first();
            } else {
                $setting = Setting::where('key', 'attendance_logo')->first();
            }
            if (!$setting) {
                $setting = new Setting();
                $setting->key = 'attendance_logo';
            }
            $setting->user_created = $user->id;
            $setting->project_id = $user->project_id;
            $setting->value = '/upload/setting/' . $fileName;
            $setting->save();
        }

        return redirect()->back()->with('message-success', 'Setting saved');
    }

    public function settingRemoteAttendance(Request $request)
    {
        $remote_attendance = $request->remote_attendance ? $request->remote_attendance : [];
        StructureOrganizationCustom::whereIn('id', $remote_attendance)->update(['remote_attendance' => '1']);
        StructureOrganizationCustom::whereNotIn('id', $remote_attendance)->update(['remote_attendance' => '0']);

        return redirect()->back()->with('message-success', 'Setting saved');
    }

    public function shiftSave(Request $r)
    {
        $shift = new Shift();
        $shift->name = $r->shift_name;
        $shift->workdays = $r->workdays;
        $shift->is_holiday = $r->holiday;
        $shift->is_collective = $r->collective;
        $shift->branch_id = $r->branch_id;
        $shift->save();

        for ($i = 0; $i < count($r->day); $i++) {
            $detail = new ShiftDetail();
            $detail->shift_id = $shift->id;
            $detail->day = $r->day[$i];
            $detail->clock_in = $r->clock_in[$i];
            $detail->clock_out = $r->clock_out[$i];
            $detail->save();
        }

        return response()->json(['status' => 'success', 'message' => 'Data saved successfully']);
    }

    public function shiftList(Request $r)
    {
        $data = Shift::where('branch_id', $r->branch_id)->get();

        if (count($data) > 0) {
            $res['message'] = 'success';
            $res['data'] = $data;
        } else {
            $res['message'] = 'failed';
        }

        return response($res);
    }

    public function shiftEdit($id)
    {
        $data = Shift::where('id', $id)->first();
        $detail = ShiftDetail::where('shift_id', $data->id)->get();

        $res['message'] = 'success';
        $res['data'] = $data;
        $res['detail'] = $detail;

        return response($res);
    }

    public function shiftUpdate(Request $r)
    {
        $shift = Shift::where('id', $r->id)->first();
        $shift->name = $r->shift_name;
        $shift->workdays = $r->workdays;
        $shift->is_holiday = $r->holiday;
        $shift->is_collective = $r->collective;
        $shift->branch_id = $r->branch_id;
        $shift->save();

        $dtl = ShiftDetail::where('shift_id', $r->id)->get();
        if ($dtl) {
            for ($i = 0; $i < count($dtl); $i++) {
                $delDtl = ShiftDetail::where('id', $dtl[$i]->id)->first();
                $delDtl->delete();
            }
        }

        for ($j = 0; $j < count($r->day); $j++) {
            $detail = new ShiftDetail();
            $detail->shift_id = $shift->id;
            $detail->day = $r->day[$j];
            $detail->clock_in = $r->clock_in[$j];
            $detail->clock_out = $r->clock_out[$j];
            $detail->save();
        }

        return response()->json(['status' => 'success', 'message' => 'Data updated successfully']);
    }

    public function shiftDelete($id)
    {
        $shift = Shift::find($id);
        if ($shift) {
            $shift->delete();
            return response()->json(['status' => 'success', 'message' => 'Data deleted']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Cannot delete data'], 404);
        }
    }

    public function userToBeAssigned($shift_id)
    {
        $shift = Shift::where('id', $shift_id)->first();

        if ($shift) {
            $data = User::where('cabang_id', $shift->branch_id)
                ->leftJoin('structure_organization_custom', 'users.structure_organization_custom_id', '=', 'structure_organization_custom.id')
                ->leftJoin('organisasi_position', 'structure_organization_custom.organisasi_position_id', '=', 'organisasi_position.id')
                ->leftJoin('organisasi_division', 'structure_organization_custom.organisasi_division_id', '=', 'organisasi_division.id')
                ->select(
                    'users.id',
                    'users.shift_id',
                    'users.nik',
                    'users.name',
                    'organisasi_division.name as division',
                    'organisasi_position.name as position'
                )
                ->get();

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

    public function assignShift(Request $r)
    {
        if ($r->user_id) {
            for ($i = 0; $i < count($r->user_id); $i++) {
                $user = User::where('id', $r->user_id[$i])->first();
                $user->shift_id = $r->shift_id;
                $user->save();
            }
        }

        if ($r->user_id_uncheck) {
            for ($x = 0; $x < count($r->user_id_uncheck); $x++) {
                $edit = User::where('id', $r->user_id_uncheck[$x])->first();
                if ((int) $edit->shift_id == (int) $r->shift_id) {
                    $edit->shift_id = null;
                    $edit->save();
                }
            }
        }

        $item = ShiftScheduleChange::where('change_date', \Carbon\Carbon::today())->where('shift_id', $r->shift_id)->first();

        if ($r->user_id) {
            if (!$item) {
                $item = new ShiftScheduleChange();
                $item->change_date = \Carbon\Carbon::today();
                $item->shift_id = $r->shift_id;
                $item->save();
            }

            ShiftScheduleChangeEmployee::where(function ($query) use ($item, $r) {
                $query->whereIn('user_id', $r->user_id)->whereHas('shiftScheduleChange', function ($query) use ($item) {
                    $query->where('change_date', '=', $item->change_date);
                });
            })->orWhere(function ($query) use ($item, $r) {
                $query->whereNotIn('user_id', $r->user_id)->where('shift_schedule_change_id', $item->id);
            })->delete();

            $temp = [];
            foreach ($r->user_id as $value) {
                array_push($temp, [
                    'user_id' => $value,
                    'shift_schedule_change_id' => $item->id,
                ]);
            }

            ShiftScheduleChangeEmployee::insert($temp);
        } else if ($item) {
            ShiftScheduleChangeEmployee::where('shift_schedule_change_id', $item->id)->delete();
        }

        return response()->json(['status' => 'success', 'message' => 'Shift assigned successfully']);
    }

    /**
     * Detail Attandance
     * @param  $SN
     * @return objects
     */
    public function AttendanceList($SN)
    {
        $absensi_device_id = getAttendanceList($SN);

        $params['data'] = AbsensiItem::where('absensi_device_id', $absensi_device_id)->get();

        return view('attendance.attendance-detail')->with($params);
    }
    /**
     * Absensi Setting
     * @return view
     */
    public function setting(Request $request)
    {
        $params['tab'] = $request->tab ?: false;
        $params['data'] = AbsensiSetting::where('project_id', \Auth::user()->project_id)->get();
        $params['list'] = Shift::leftJoin('cabang', 'shift.branch_id', '=', 'cabang.id')
            ->select(
                'shift.id',
                'shift.name',
                'cabang.name as branch',
                'shift.workdays',
                'shift.is_holiday',
                'shift.is_collective'
            )->get();
        $arr_emp = [];
        for ($i = 0; $i < count($params['list']); $i++) {
            $dataUser = User::where('shift_id', $params['list'][$i]->id)->get();
            array_push($arr_emp, count($dataUser));
        }

        for ($j = 0; $j < count($arr_emp); $j++) {
            $params['list'][$j]['total_employees'] = $arr_emp[$j];
        }

        return view('shift.setting')->with($params);
    }

    /**
     * Set Position
     * @param Request $request
     */
    public function setPosition(Request $request)
    {
        User::where('structure_organization_custom_id', $request->structure_organization_custom_id)
            ->update(['absensi_setting_id' => $request->shift_id]);

        return redirect()->back()->with('message-success', 'Setting saved');
    }

    /**
     * Absensi Setting Store
     * @return view
     */
    public function settingStore(Request $r)
    {
        $data = new AbsensiSetting();
        $data->project_id = \Auth::user()->project_id;
        $data->shift = $r->shift;
        $data->clock_in = $r->clock_in;
        $data->clock_out = $r->clock_out;
        $data->save();

        return redirect()->back()->with('message-success', 'Setting saved');
    }

    /**
     * Delete Setting
     */
    public function settingDelete($id)
    {
        AbsensiSetting::where('id', $id)->delete();

        User::where('absensi_setting_id', $id)->update(['absensi_setting_id' => null]);

        return redirect()->route('attendance-setting.index')->with('message-success', 'Setting Deleted.');
    }

    /**
     * Import Attendance
     * @param  Request $request
     * @return void
     */
    public function attendanceImport(Request $request)
    {
        if ($request->hasFile('file')) {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($request->file);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = [];
            foreach ($worksheet->getRowIterator() as $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false); // This loops through all cells,
                $cells = [];
                foreach ($cellIterator as $cell) {
                    $cells[] = $cell->getValue();
                }
                $rows[] = $cells;
            }

            AbsensiItemTemp::truncate();
            // delete all table temp
            foreach ($rows as $key => $item) {
                if (empty($item[1])) {
                    continue;
                }

                if ($key == 0) {
                    continue;
                }

                // check nik
                $user = User::where('nik', $item[0])->first();
                if ($user) {
                    $date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($item[2]);
                    $clock_in = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($item[3]);
                    $clock_out = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($item[4]);

                    $data = new AbsensiItemTemp();
                    $data->user_id = $user->id;
                    $data->date = $date->format('Y-m-d');
                    $data->date_out = $date->format('Y-m-d');
                    $data->date_shift = $date->format('Y-m-d');
                    $data->timetable = date('l', strtotime($data->date));
                    $data->clock_in = $clock_in->format('H:i');
                    $data->clock_out = $clock_out->format('H:i');

                    // Clock In
                    if (isset($data->user->absensiSetting->clock_in)) {
                        $awal = strtotime($data->date . ' ' . $data->user->absensiSetting->clock_in . ':00');
                        $akhir = strtotime($data->date . ' ' . $data->clock_in . ":00");
                        $diff = $akhir - $awal;
                        $jam = floor($diff / (60 * 60));
                        $menit = ($diff - $jam * (60 * 60)) / 60;

                        if ($diff > 0) {
                            $jam = abs($jam);
                            $menit = abs($menit);
                            $jam = $jam <= 9 ? "0" . $jam : $jam;
                            $menit = $menit <= 9 ? "0" . $menit : $menit;

                            $data->late = $jam . ':' . $menit;
                        }
                    }

                    if (isset($data->user->absensiSetting->clock_out)) {
                        $akhir = strtotime($data->date . ' ' . $data->user->absensiSetting->clock_out . ':00');
                        $awal = strtotime($data->date . ' ' . $data->clock_out . ":00");
                        $diff = $akhir - $awal;
                        $jam = floor($diff / (60 * 60));
                        $menit = ($diff - $jam * (60 * 60)) / 60;
                        if ($diff > 0) {
                            $awal = date_create($data->date . ' ' . $data->user->absensiSetting->clock_out . ':00');
                            $akhir = date_create($data->date . ' ' . $data->clock_out . ":00"); // waktu sekarang, pukul 06:13
                            $diff = @date_diff($akhir, $awal);

                            $jam = @$diff->h <= 9 ? "0" . $diff->h : $diff->h;
                            $menit = @$diff->i <= 9 ? "0" . $diff->i : $diff->i;

                            $data->early = $jam . ':' . $menit;
                        }
                    }

                    if (!empty($data->clock_out) and !empty($data->clock_in)) {
                        $awal = strtotime($data->date . ' ' . $data->clock_in . ":00");
                        $akhir = strtotime($data->date . ' ' . $data->clock_out . ":00");
                        $diff = $akhir - $awal;
                        $jam = floor($diff / (60 * 60));
                        $menit = ($diff - $jam * (60 * 60)) / 60;

                        $jam = $jam <= 9 ? "0" . $jam : $jam;
                        $menit = $menit <= 9 ? "0" . $menit : $menit;

                        $data->work_time = $jam . ':' . $menit;
                    }

                    $data->save();
                }
            }
        }

        return redirect()->route('attendance.preview')->with('message-success', 'Import success');
    }

    /**
     * Import Attendance
     * @param  Request $request
     * @return void
     */
    public function attendancePreview()
    {
        $params['data'] = AbsensiItemTemp::all();

        return view('attendance.preview')->with($params);
    }

    /**
     * Import All
     * @return void
     */
    public function importAll()
    {
        $data = AbsensiItemTemp::all();
        foreach ($data as $i) {
            $item = AbsensiItem::where('user_id', $i->user_id)->whereDate('date', $i->date)->first();
            if (!$item) {
                $item = new AbsensiItem();
                $item->user_id = $i->user_id;
                $item->date = $i->date;
                $item->timetable = $i->timetable;
                $item->clock_in = $i->clock_in;
                $item->clock_out = $i->clock_out;
                $item->early = $i->early;
                $item->late = $i->late;
                $item->work_time = $i->work_time;
            } else {
                if (empty($item->clock_in)) {
                    $item->clock_in = $i->clock_in;
                    $item->early = $i->early;
                }

                if (empty($item->clock_out)) {
                    $item->clock_out = $i->clock_out;
                    $item->late = $i->late;
                }

                if (empty($item->work_time)) {
                    $item->work_time = $i->work_time;
                }
            }

            $item->save();
        }

        AbsensiItemTemp::truncate();

        return redirect()->route('attendance.index')->with('message-success', 'Import success');
    }
}
