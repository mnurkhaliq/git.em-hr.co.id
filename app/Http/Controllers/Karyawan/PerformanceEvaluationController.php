<?php

namespace App\Http\Controllers\Karyawan;

use App\Models\KpiEmployee;
use App\Models\KpiEmployeeScoring;
use App\Models\KpiItem;
use App\Models\KpiPeriod;
use App\Models\KpiSettingScoring;
use App\Models\StructureOrganizationCustom;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use DataTables;
use Illuminate\Support\Facades\Validator;

class PerformanceEvaluationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $user = \Auth::user();
        return view('karyawan.performance-evaluation.index');
    }

    public function table(){
        $user = Auth::user();
        $kpi_employee = getKpiList($user->id);

        return DataTables::of($kpi_employee)
            ->addColumn('action', function ($kpi) {
                return '<a href="'.route('karyawan.performance-evaluation.edit', $kpi->id).'"> <button class="btn btn-info btn-xs m-r-5"><i class="fa fa-edit"></i> detail</button></a>';
            })
            ->make(true);
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

        $user = Auth::user();
        $validator = Validator::make(request()->all(), [
            'status'  => 'required',
            'kpi_employee_id' => 'required|exists:kpi_employee,id'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()]);
        }
        $employee = KpiEmployee::where('id',$request->kpi_employee_id)->first();
        if($request->status == 0 || $request->status == 1){
            if($request->status == 1) {
                $validator = Validator::make(request()->all(), [
                    'self_score.*' => "required|numeric|min:".$employee->period->min_rate."|max:".$employee->period->max_rate,
                ]);

                if ($validator->fails()) {
                    return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()]);
                }
                $employee->employee_input_date = date("Y-m-d");

                // Kirim email

                $params = getEmailConfig();

                $employees = StructureOrganizationCustom::join('users as u','structure_organization_custom.id','=','u.structure_organization_custom_id')
                    ->whereRaw("structure_organization_custom.id = (select parent_id from structure_organization_custom where id = $employee->structure_organization_custom_id)")
                    ->select(['u.*'])->get();
                info($employees);
                Config::set('database.default','mysql');


                $params['view']     = 'email.kpi-submit-score-employee';
                $params['subject']     = $params['mail_name'].' - KPI Score Submission for Managers';
                foreach($employees as $emp) {
                    $params['user']     = $emp;
                    $params['staff']   = $employee->user;
                    $params['period']   = $employee->period;
                    $params['email']    = $employee->user->email;
                    $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                    dispatch($job);
                }
                Config::set('database.default',session('db_name','mysql'));

            }
            foreach ($request->self_score as $item_id => $self_score){
                $score = KpiEmployeeScoring::where(['kpi_item_id'=>$item_id,'kpi_employee_id'=>$request->kpi_employee_id])->first();
                if($score){
                    $score->self_score = $self_score;
                    $score->justification = isset($request->justification[$item_id])?$request->justification[$item_id]:null;
                }
                else{
                    $score = new KpiEmployeeScoring(
                        ['kpi_item_id'=>$item_id,
                        'kpi_employee_id'=>$request->kpi_employee_id,
                        'self_score'=>$self_score,
                        'justification'=>isset($request->justification[$item_id])?$request->justification[$item_id]:null]);
                }
                $score->save();
            }


        }else if($request->status == 3){
            $validator = Validator::make(request()->all(), [
                'feedback' => "required"
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()]);
            }
            $employee->employee_feedback = $request->feedback;
//            $scores = KpiEmployeeScoring::with('kpi_item')->where('kpi_employee_id',$employee->id)->get();
//            $final = 0;
//            foreach ($scores as $score) {
//
//                $final += ($score->supervisor_score*$score->kpi_item->weightage)/100;
//            }
//            $employee->final_score = "$final";

        }

        $employee->status = $request->status;
        $employee->save();
        echo json_encode(['status' => 'success', 'message' => 'KPI Evaluation is saved']);
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
        $user = Auth::user();

        $param = getKpiDetail($user,null,$id);
        if($param)
            return view('karyawan.performance-evaluation.edit')->with($param);
        else
            return redirect()->route('karyawan.performance-evaluation.index')->with('message-error', 'Data is not found');
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
}
