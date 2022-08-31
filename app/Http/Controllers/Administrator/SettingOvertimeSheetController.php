<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\OvertimePayroll;
use App\Models\OvertimePayrollEarning;
use App\Models\OvertimePayrollType;
use App\Models\PayrollEarnings;
use App\User;
use Illuminate\Http\Request;
use Auth;

class SettingOvertimeSheetController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
        $this->middleware('module:7');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $params = [
            'tab' => \Session::has('tab') ? \Session::get('tab') : false,
            'overtimePayrolls' => OvertimePayroll::with(['overtimePayrollType'])->get(),
        ];

        return view('administrator.setting-overtime-sheet.index')->with($params);
    }

    public function userToBeAssigned($type)
    {
        $data = User::whereIn('access_id',[1,2])
            ->leftJoin('structure_organization_custom', 'users.structure_organization_custom_id', '=', 'structure_organization_custom.id')
            ->leftJoin('organisasi_position', 'structure_organization_custom.organisasi_position_id', '=', 'organisasi_position.id')
            ->leftJoin('organisasi_division', 'structure_organization_custom.organisasi_division_id', '=', 'organisasi_division.id')
            ->select(
                'users.id',
                'users.overtime_entitle as overtime_entitle',
                'users.overtime_payroll_id as overtime_payroll_id',
                'users.nik',
                'users.name',
                'organisasi_division.name as division',
                'organisasi_position.name as position'
            );
        $data = $type == 2 ? $data->where('overtime_entitle', 1) : $data;
        $data = $data->get();

        if (count($data) > 0) {
            return \Response::json([
                'message' => 'success',
                'data' => $data,
            ]);
        } else {
            return \Response::json([
                'message' => 'failed',
            ]);
        }
    }

    public function assignEntitle(Request $request)
    {
        if ($request->user_id) {
            User::whereIn('id', $request->user_id)->update([
                'overtime_entitle' => $request->overtime_entitle,
            ]);
        }

        if ($request->user_id_uncheck) {
            User::whereIn('id', $request->user_id_uncheck)->update([
                'overtime_entitle' => $request->overtime_entitle == 1 ? null : 1,
            ]);
        }

        return response()->json(['status' => 'success', 'message' => 'Assign entitlement success']);
    }

    public function assignSetting(Request $request)
    {
        if ($request->user_id) {
            User::whereIn('id', $request->user_id)->update([
                'overtime_payroll_id' => $request->overtime_payroll_id,
            ]);
        }

        if ($request->user_id_uncheck) {
            User::whereIn('id', $request->user_id_uncheck)->where('overtime_payroll_id', $request->overtime_payroll_id)->update([
                'overtime_payroll_id' => null,
            ]);
        }

        return response()->json(['status' => 'success', 'message' => 'Assign setting success']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $payrollEarnings = Auth::user()->project_id ? PayrollEarnings::where('project_id', Auth::user()->project_id)->get()->toArray() : PayrollEarnings::all()->toArray();

        $params = [
            'overtimePayrollTypes' => OvertimePayrollType::all(),
            'earnings' => array_merge([
                ['id' => 'salary', 'title' => 'salary'],
                ['id' => 'bonus', 'title' => 'bonus'],
                ['id' => 'thr', 'title' => 'thr'],
            ], $payrollEarnings),
        ];

        return view('administrator.setting-overtime-sheet.create')->with($params);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $overtimePayrollId = OvertimePayroll::create([
            'overtime_payroll_type_id' => $request->overtime_payroll_type_id,
            'name' => $request->name,
        ])->id;

        if (is_array($request->fix_rate)) {
            foreach ($request->fix_rate as $key => $value) {
                $data = new OvertimePayrollEarning();
                $data->overtime_payroll_id = $overtimePayrollId;
                if (is_numeric($value)) {
                    $data->payroll_earning_id = $value;
                } else {
                    $data->payroll_attribut = $value;
                }
                $data->save();
            }
        } else {
            OvertimePayrollEarning::create([
                'overtime_payroll_id' => $overtimePayrollId,
                'payroll_earning_value' => preg_replace('/[^0-9]/', '', $request->fix_rate),
            ]);
        }

        return redirect()->route('administrator.setting-overtime-sheet.index')->with(['message-success' => 'Save data success', 'tab' => 'payment']);
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
        $payrollEarnings = Auth::user()->project_id ? PayrollEarnings::where('project_id', Auth::user()->project_id)->get()->toArray() : PayrollEarnings::all()->toArray();

        $params = [
            'overtimePayroll' => OvertimePayroll::where('id', $id)->with(['overtimePayrollEarning'])->first(),
            'overtimePayrollTypes' => OvertimePayrollType::all(),
            'earnings' => array_merge([
                ['id' => 'salary', 'title' => 'salary'],
                ['id' => 'bonus', 'title' => 'bonus'],
                ['id' => 'thr', 'title' => 'thr'],
            ], $payrollEarnings),
        ];

        return view('administrator.setting-overtime-sheet.edit')->with($params);
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
        OvertimePayroll::where('id', $id)->update([
            'overtime_payroll_type_id' => $request->overtime_payroll_type_id,
            'name' => $request->name,
        ]);

        OvertimePayrollEarning::where('overtime_payroll_id', $id)->delete();

        if (is_array($request->fix_rate)) {
            foreach ($request->fix_rate as $key => $value) {
                $data = new OvertimePayrollEarning();
                $data->overtime_payroll_id = $id;
                if (is_numeric($value)) {
                    $data->payroll_earning_id = $value;
                } else {
                    $data->payroll_attribut = $value;
                }
                $data->save();
            }
        } else {
            OvertimePayrollEarning::create([
                'overtime_payroll_id' => $id,
                'payroll_earning_value' => preg_replace('/[^0-9]/', '', $request->fix_rate),
            ]);
        }

        return redirect()->route('administrator.setting-overtime-sheet.index')->with(['message-success' => 'Save data success', 'tab' => 'payment']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        OvertimePayroll::destroy($id);

        return redirect()->route('administrator.setting-overtime-sheet.index')->with(['message-success' => 'Delete data success', 'tab' => 'payment']);
    }
}
