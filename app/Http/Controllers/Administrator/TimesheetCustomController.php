<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\OrganisasiDivision;
use App\Models\OrganisasiPosition;
use App\Models\TimesheetPeriod;
use App\User;
use Illuminate\Http\Request;

class TimesheetCustomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
        $this->middleware('module:29');
    }

    public function index()
    {
        $user = \Auth::user();
        if ($user->project_id != null) {
            $data = TimesheetPeriod::select('timesheet_periods.*')->join('users', 'users.id', '=', 'timesheet_periods.user_id')->where('users.project_id', $user->project_id)->orderBy('start_date', 'DESC');
            $params['division'] = OrganisasiDivision::where('project_id', $user->project_id)->select('organisasi_division.*')->get();
            $params['position'] = OrganisasiPosition::where('project_id', $user->project_id)->select('organisasi_position.*')->get();
        } else {
            $data = TimesheetPeriod::select('timesheet_periods.*')->join('users', 'users.id', '=', 'timesheet_periods.user_id')->orderBy('start_date', 'DESC');
            $params['division'] = OrganisasiDivision::all();
            $params['position'] = OrganisasiPosition::all();
        }
        $params['structure'] = getStructureName();

        if(count(request()->all())) {
            \Session::put('ts-employee_status', request()->employee_status);
            \Session::put('ts-position_id', request()->position_id);
            \Session::put('ts-division_id', request()->division_id);
            \Session::put('ts-name', request()->name);
        }

        $employee_status    = \Session::get('ts-employee_status');
        $position_id        = \Session::get('ts-position_id');
        $division_id        = \Session::get('ts-division_id');
        $name               = \Session::get('ts-name');
        
        if (request()) {
            if (!empty($name)) {
                $data = $data->where(function ($table) use($name) {
                    $table->where('users.name', 'LIKE', '%' . $name . '%')
                        ->orWhere('users.nik', 'LIKE', '%' . $name . '%');
                });
            }
            
            if (!empty($employee_status)) {
                $data = $data->where('users.organisasi_status', $employee_status);
            }
            if ((!empty($division_id)) and (empty($position_id))) {
                $data = $data->join('structure_organization_custom', 'users.structure_organization_custom_id', '=', 'structure_organization_custom.id')->where('structure_organization_custom.organisasi_division_id', $division_id);
            }
            if ((!empty($position_id)) and (empty($division_id))) {
                $data = $data->join('structure_organization_custom', 'users.structure_organization_custom_id', '=', 'structure_organization_custom.id')->where('structure_organization_custom.organisasi_position_id', $position_id);
            }
            if ((!empty($position_id)) and (!empty($division_id))) {
                $data = $data->join('structure_organization_custom', 'users.structure_organization_custom_id', '=', 'structure_organization_custom.id')->where('structure_organization_custom.organisasi_position_id', $position_id)->where('structure_organization_custom.organisasi_division_id', $division_id);
            }
            if (request()->action == 'download') {
                return $this->downloadExcel($data);
            }
        }

        if(request()->reset == 1)
        {
            \Session::forget('ts-employee_status');
            \Session::forget('ts-position_id');
            \Session::forget('ts-division_id');
            \Session::forget('ts-name');

            return redirect()->route('administrator.timesheetcustom.index');
        }
        
        $params['data'] = $data->get();

        return view('administrator.timesheetcustom.index')->with($params);
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
    public function proses($id)
    {
        $params['data'] = TimesheetPeriod::where('id', $id)->first();

        return view('administrator.timesheetcustom.proses')->with($params);
    }
    public function downloadExcel($data)
    {
        $max = $data->with(['timesheetPeriodTransaction' => function($query) { $query->count(); }])->get()->sortByDesc('timesheetPeriodTransaction')->first()->timesheetPeriodTransaction->count();
        $params = [];
        foreach ($data->get() as $no => $item) {
            $params[$no]['NO'] = $no + 1;
            $params[$no]['EMPLOYEE ID(NIK)'] = $item->user->nik;
            $params[$no]['EMPLOYEE NAME'] = $item->user->name;
            $params[$no]['POSITION'] = (isset($item->user->structure->position) ? $item->user->structure->position->name : '').(isset($item->user->structure->division) ?  ' - '.$item->user->structure->division->name : '').(isset($item->user->structure->title) ?  ' - '.$item->user->structure->title->name : '');
            $params[$no]['START DATE'] = date('d F Y', strtotime($item->start_date));
            $params[$no]['END DATE'] = date('d F Y', strtotime($item->end_date));

            // SET HEADER LEVEL APPROVAL
            for ($a = 0; $a < $max; $a++) {
                $params[$no]['APPROVAL STATUS ' . ($a + 1)] = '-';
                $params[$no]['APPROVAL NAME ' . ($a + 1)] = '-';
                $params[$no]['APPROVAL DATE ' . ($a + 1)] = '-';
            }

            foreach ($item->timesheetPeriodTransaction as $key => $value) {
                //$params[$no]['Approval '. ($key+1)] = $value->id;

                if ($value->status == 2) {
                    $params[$no]['APPROVAL STATUS ' . ($key + 1)] = 'Approved';
                } elseif ($value->status == 3) {
                    $params[$no]['APPROVAL STATUS ' . ($key + 1)] = 'Revision';
                } else {
                    $params[$no]['APPROVAL STATUS ' . ($key + 1)] = '-';
                }

                $params[$no]['APPROVAL NAME ' . ($key + 1)] = isset($value->userApproved) ? $value->userApproved->name : '';

                $params[$no]['APPROVAL DATE ' . ($key + 1)] = $value->date_approved != null ? date('d F Y', strtotime($value->date_approved)) : '';
            }
        }

        return (new \App\Models\KaryawanExport($params, 'Report Timesheet Employee '))->download('EM-HR.Report-Timesheet-' . date('d-m-Y') . '.xlsx');
    }
}
