<?php

namespace App\Http\Controllers\Administrator;

use App\Models\KpiEmployee;
use App\Models\KpiModule;
use App\Models\KpiPeriod;
use App\Models\KpiSettingScoring;
use App\Models\KpiSettingStatus;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SettingPerformanceController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
        $this->middleware('module:25');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $user = \Auth::user();
        return view('administrator.setting-performance.index');
    }

    public function table(){
        $user = Auth::user();
        $periods = KpiPeriod::where('project_id',$user->project_id)->select(DB::raw("CONCAT(DATE_FORMAT(start_date, '%d %M %Y'), ' - ',DATE_FORMAT(end_date, '%d %M %Y')) AS period"),'id','max_rate','min_rate','status','is_lock')->get();
        return DataTables::of($periods)
            ->addColumn('action', function ($period) {
                return '<a href="'.route('administrator.setting-performance.edit', $period->id).'"> <button class="btn btn-info btn-xs m-r-5"><i class="fa fa-edit"></i> edit</button></a>
                                            <button class="btn btn-danger btn-xs m-r-5" onclick="remove('.$period->id.')"><i class="fa fa-trash"></i> delete</button>';
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
        return view('administrator.setting-performance.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $validator = Validator::make(request()->all(), [
            'start_date'  => 'required',
            'end_date'  => 'required',
            //'min_rate'  => 'required|integer',
            'max_rate'  => 'required|integer|max:10|min:2',
            'weightage.*'  => "required|numeric|min:0|max:100"
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator->errors());
        }
        if(array_sum($request->weightage) != 100){
            return redirect()->back()->withInput()->withErrors("Weightage total must be 100%!");
        }
        $start_date = date('Y-m-d' , strtotime($request->start_date));
        $end_date = date('Y-m-d' , strtotime($request->end_date));
        if($start_date > $end_date){
            return redirect()->back()->withInput()->withErrors("Start date and End date are invalid!");
        }

        $period = new KpiPeriod();
        $period->start_date = $start_date;
        $period->end_date = $end_date;
        $period->min_rate = $request->min_rate != null ? $request->min_rate : 1;
        $period->max_rate = $request->max_rate;
        $period->project_id = $user->project_id;
        $period->status = $request->status;
        $period->save();

        foreach ($request->weightage as $module_id => $weight){
            $newSetting = new KpiSettingScoring();
            $newSetting->kpi_period_id = $period->id;
            $newSetting->kpi_module_id = $module_id;
            $newSetting->weightage = $weight;
            $newSetting->save();
        }

        $users = User::where(['project_id'=>$user->project_id])->whereIn('access_id',[1,2])->where(function($query) {
            $query->whereNull('non_active_date')->orWhere('non_active_date', '>', \Carbon\Carbon::now());
        })->where(function($query) {
            $query->whereNull('join_date')->orWhere('join_date', '<=', \Carbon\Carbon::now());
        })->get();
        foreach ($users as $user){
            if(!$user->structure || $user->structure && $user->structure->parent_id == null){
                continue;
            }
            $param = [
                'user_id'=>$user->id,
                'structure_organization_custom_id'=>$user->structure_organization_custom_id,
                'kpi_period_id'=>$period->id
            ];
            $newKPIEmployee = new KpiEmployee($param);
            $newKPIEmployee->save();

        }
        return redirect()->route('administrator.setting-performance.index')->with('message-success', 'Data saved successfully');
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
        $param['period'] = KpiPeriod::where('id',$id)->first();
        $param['modules'] = KpiModule::with(['settings' => function ($query) use($id) {
            $query->where('kpi_period_id', '=', $id);
        }])->get();
//        echo json_encode($param);
        if($param['period'])
            return view('administrator.setting-performance.edit')->with($param);
        else
            return redirect()->route('administrator.setting-performance.index')->with('message-error', 'Data is not found');

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
        $user = Auth::user();
        $validator = Validator::make(request()->all(), [
            'start_date'  => 'required',
            'end_date'  => 'required',
            //'min_rate'  => 'required|integer',
            'max_rate'  => 'required|integer|max:10|min:2',
            'weightage.*'  => "required|numeric|min:0|max:100"
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator->errors());
        }
        if(array_sum($request->weightage) != 100){
            return redirect()->back()->withInput()->withErrors("Weightage total must be 100%!");
        }
        $start_date = date('Y-m-d' , strtotime($request->start_date));
        $end_date = date('Y-m-d' , strtotime($request->end_date));
        if($start_date > $end_date){
            return redirect()->back()->withInput()->withErrors("Start date and End date are invalid!");
        }

        $period = KpiPeriod::find($id);
        if($period->is_lock == 1){
            return redirect()->route('administrator.setting-performance.index')->with('message-error', "Locked item can't be updated!");
        }

//        foreach ($request->weightage as $module_id => $weight){
//            $setting = KpiSettingScoring::where(['kpi_period_id'=>$period->id,'kpi_module_id'=>$module_id])->first();
//            if($setting && count($setting->status)>0 && $setting->status[0]->status == 1) {
//                return redirect()->back()->withInput()->withErrors($setting->module->name."'s weightage can't be changed because its status is published, change its status first!");
//            }
//        }
        if($period->status == 0){
            $statuses = KpiSettingStatus::join('kpi_setting_scoring as ss','kpi_setting_status.kpi_setting_scoring_id','=','ss.id')
                ->where('ss.kpi_period_id',$period->id)->select('kpi_setting_status.*')->get();
            foreach ($statuses as $status){
                $status->status = 0;
                $status->save();
            }
        }


        $period->start_date = $start_date;
        $period->end_date = $end_date;
        $period->min_rate = $request->min_rate != null ? $request->min_rate : 1;;
        $period->max_rate = $request->max_rate;
        $period->status = $request->status;
        $period->save();



        foreach ($request->weightage as $module_id => $weight){
            $setting = KpiSettingScoring::where(['kpi_period_id'=>$period->id,'kpi_module_id'=>$module_id])->first();
            if(!$setting) {
                $newSetting = new KpiSettingScoring();
                $newSetting->kpi_period_id = $period->id;
                $newSetting->kpi_module_id = $module_id;
                $newSetting->weightage = $weight;
                $newSetting->save();
            }
            else{
                $setting->weightage = $weight;
                $setting->save();
            }
        }
        return redirect()->route('administrator.setting-performance.index')->with('message-success', 'Data berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function lock($id)
    {
        //
        $period = KpiPeriod::find($id);
        if($period){
            $period->is_lock = 1;
            $period->save();

            $params = getEmailConfig();
            $employees = KpiEmployee::where(['kpi_period_id'=>$period->id])->get();
            Config::set('database.default','mysql');
            $params['view']     = 'email.kpi-published';
            $params['subject']     = $params['mail_name'].' - KPI Submission for Employees';
            foreach($employees as $employee) {
                $params['user']     = $employee->user;
                $params['period']   = $employee->period;
                $params['email']    = $employee->user->email;
                $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                dispatch($job);
            }
            Config::set('database.default',session('db_name','mysql'));

            return redirect()->back()->with('message-success', 'Period has been locked');
        }
        else{
            return redirect()->back()->with('message-error', 'Cannot lock data!');
        }

    }
    public function destroy($id)
    {
        //
        $deleted = KpiPeriod::destroy($id);
        if($deleted){
            return response()->json(['status' => 'success', 'message' => 'Data has been deleted']);
        }
        else{
            return response()->json(['status' => 'error', 'message' => 'Cannot delete data'],404);
        }

    }
}
