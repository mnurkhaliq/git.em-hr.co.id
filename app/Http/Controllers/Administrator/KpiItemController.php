<?php

namespace App\Http\Controllers\Administrator;

use App\Models\KpiItem;
use App\Models\KpiPeriod;
use App\Models\KpiSettingScoring;
use App\Models\KpiSettingStatus;
use App\Models\StructureOrganizationCustom;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use DataTables;

class KpiItemController extends Controller
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
        return view('administrator.kpi-item.index');
    }

    public function table(){
        $user = Auth::user();
        $periods = KpiPeriod::join('kpi_setting_scoring as ss','kpi_periods.id','=','ss.kpi_period_id')
            ->leftJoin('kpi_setting_status as st','ss.id','=','st.kpi_setting_scoring_id')
            ->where(['project_id'=>$user->project_id,'kpi_periods.status'=>1,'ss.kpi_module_id'=>1])
            ->select(['kpi_periods.*',\DB::raw("CONCAT(DATE_FORMAT(start_date, '%d %M %Y'), ' - ',DATE_FORMAT(end_date, '%d %M %Y')) AS period"), 'ss.id as setting_scoring_id','ss.weightage as weight_setting','st.status as status_setting',\DB::raw('(select count(*) from kpi_items where kpi_setting_scoring_id = ss.id) as count')]);
        return DataTables::of($periods)
            ->addColumn('action', function ($period) {
                return '<a href="'.route('administrator.kpi-item.edit', $period->id).'"> <button class="btn btn-info btn-xs m-r-5"><i class="fa fa-edit"></i> edit</button></a>';
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
        $user = Auth::user();
        $validator = Validator::make(request()->all(), [
            'setting_id'  => 'required|exists:kpi_setting_scoring,id',
            'weightage.*'  => "required|numeric|min:1|max:100",
            'name.*'  => "required",
            'status' => "required"
        ]);
        info($request->all());
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()]);
        }
        $setting = KpiSettingScoring::with(['status'=>function($query) use ($request){
            if($request->structure_organization_custom_id) {
                $query->where('structure_organization_custom_id',$request->structure_organization_custom_id);
            }
            else{
                $query->whereNull('structure_organization_custom_id');
            }
        }])->find($request->setting_id);
        if($request->status == 1 && $setting->weightage!=0 && (!isset($request->weightage) || array_sum($request->weightage) != $setting->weightage)){
            return response()->json(['status' => 'failed', 'message' => "Weightage total must be $setting->weightage%!"]);
        } else if($request->status == 0 && $setting->weightage!=0 && (!isset($request->weightage) || array_sum($request->weightage) > $setting->weightage)){
            return response()->json(['status' => 'failed', 'message' => "Weightage total cannot exceed $setting->weightage%!"]);
        }
        if($setting->period->is_lock == 1){
            return response()->json(['status' => 'failed', 'message' => "Unlocked item can't be updated!"]);
        }

        if(count($setting->status)>0){
            $setting->status[0]->update(['status'=>$request->status]);
        }
        else{
            $settingStatus = new KpiSettingStatus(['status'=>$request->status,'structure_organization_custom_id'=>$request->structure_organization_custom_id]);
            $setting->status()->save($settingStatus);
        }
        if($request->ids){
            $ids = $request->ids;
        }
        if($request->name) {
            for ($i = 0; $i < count($request->name); $i++) {
                if(isset($ids[$i])){
                    $item = KpiItem::find($ids[$i]);
                }
                else{
                    $item = new KpiItem();
                }
                $item->kpi_setting_scoring_id = $request->setting_id;
                $item->name = $request->name[$i];
                $item->weightage = $request->weightage[$i];
                if($request->structure_organization_custom_id){
                    $item->structure_organization_custom_id = $request->structure_organization_custom_id;
                }
                $item->save();
            }
        }
        if($request->dels){
            for ($i = 0; $i < count($request->dels); $i++) {
                $item = KpiItem::find($request->dels[$i]);
                if($item)
                    $item->delete();
            }
        }

        // Kirim email
        if($request->status == 1){
            $params = getEmailConfig();

            $employees = StructureOrganizationCustom::join('users as u','structure_organization_custom.id','=','u.structure_organization_custom_id')
                ->whereRaw('structure_organization_custom.id in (SELECT parent_id from structure_organization_custom)')
                ->select(['u.*'])->get();
            info($employees);
            Config::set('database.default','mysql');


            $params['view']     = 'email.kpi-publish-item-admin';
            $params['subject']     = $params['mail_name'].' - KPI Item Submission for Managers';
            foreach($employees as $employee) {
                $params['user']     = $employee;
                $params['period']   = $setting->period;
                $params['email']    = $employee->email;
                $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                dispatch($job);
            }
            Config::set('database.default',session('db_name','mysql'));
        }

        return response()->json(['status' => 'success', 'message' => 'KPI Items is saved']);
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
        $param['period'] = KpiPeriod::with(['settings'=>function($query){
            $query->where('kpi_module_id',1);
        }])->where(['id'=>$id,'status'=>1])->first();
//        echo json_encode($param);
        if($param['period'])
            return view('administrator.kpi-item.edit')->with($param);
        else
            return redirect()->route('administrator.kpi-item.index')->with('message-error', 'Data is not found');
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
