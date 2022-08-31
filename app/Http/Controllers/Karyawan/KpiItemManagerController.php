<?php

namespace App\Http\Controllers\Karyawan;

use App\Models\KpiEmployee;
use App\Models\KpiItem;
use App\Models\KpiPeriod;
use App\Models\KpiSettingScoring;
use App\Models\KpiSettingStatus;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use DataTables;

class KpiItemManagerController extends Controller
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
        $this->middleware(function ($request, $next) {
            if(checkModule(25) && !checkManager()){
                return redirect()->back()->with('message-error', 'You are not manager');
            }
            return $next($request);
        });

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
        return view('karyawan.kpi-item.index');
    }

    public function table(){
        $user = Auth::user();
        $module_id = 2;
        $periods = DB::select(DB::raw("SELECT kp.*, ss.id as setting_scoring_id, ss.weightage as weight_setting, 
                                      CONCAT(DATE_FORMAT(start_date, '%d %M %Y'), ' - ',DATE_FORMAT(end_date, '%d %M %Y')) AS period 
                                      FROM kpi_periods kp JOIN kpi_setting_scoring ss on kp.id = ss.kpi_period_id 
                                      WHERE kp.project_id = $user->project_id AND kp.status = 1 AND ss.kpi_module_id = $module_id 
                                      AND (select status from kpi_setting_status st join kpi_setting_scoring ss2 on st.kpi_setting_scoring_id = ss2.id where ss2.kpi_period_id=kp.id and ss2.kpi_module_id = 1) = 1"));
        // and select (count(st.status) = 1) from kpi_setting_status st join kpi_setting_scoring ss2 on st.kpi_setting_scoring_id = ss2.id where ss2.kpi_period_id=18 and ss2.kpi_module_id < 2 and status=1
        return DataTables::of($periods)
            ->addColumn('action', function ($period) {
                return '<a href="'.route('karyawan.kpi-item.edit', $period->id).'"> <button class="btn btn-info btn-xs m-r-5"><i class="fa fa-edit"></i> edit</button></a>';
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
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()]);
        }

        // Cari setting scoring yang bersangkutan
        $setting = KpiSettingScoring::with(['status'=>function($query) use ($request){
            if($request->structure_organization_custom_id) {
                $query->where('structure_organization_custom_id',$request->structure_organization_custom_id);
            }
            else{
                $query->whereNull('structure_organization_custom_id');
            }
        }])->find($request->setting_id);

        // Cek status, apabila publish pastikan weightage 100%
        if($request->status == 1 && $setting->weightage!=0 && (!isset($request->weightage) || array_sum($request->weightage) != $setting->weightage)){
            return response()->json(['status' => 'failed', 'message' => "Weightage total must be 100%!"]);
        } else if($request->status == 0 && $setting->weightage!=0 && (!isset($request->weightage) || array_sum($request->weightage) > $setting->weightage)){
            return response()->json(['status' => 'failed', 'message' => "Weightage total cannot exceed 100%!"]);
        }

        // apabila period telah dilock tidak bisa mengubah apapun
        if($setting->period->is_lock == 1){
            return response()->json(['status' => 'failed', 'message' => "Unlocked item can't be updated!"]);
        }

        // Cek status, apabila sudah ada update, kalau belum create
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

            $users = User::whereHas('modules',function ($q){
                $q->where('product_id',25);
            })->get();
            $params['position']    = get_position_name($request->structure_organization_custom_id);
            Config::set('database.default','mysql');


            $params['view']     = 'email.kpi-publish-item-manager';
            $params['subject']     = $params['mail_name'].' - KPI Items Manager';
            foreach($users as $user) {
                $params['user']        = $user;
                $params['period']      = $setting->period;
                $params['email']       = $user->email;
                $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                dispatch($job);
            }

            Config::set('database.default',session('db_name','mysql'));

        }

        return response()->json(['status' => 'success', 'message' => 'KPI Items are saved']);
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
        $cek = false;
        $param['period'] = KpiPeriod::with('settings')->where(['id'=>$id,'status'=>1])->first();
//        echo json_encode($param);
        if($param['period']){
            $setting = KpiSettingScoring::where(['kpi_period_id'=>$param['period']->id,'kpi_module_id'=>1])
                ->with(['status'=>function($query){
                $query->whereNull('structure_organization_custom_id');
            }])->first();
            if($setting && count($setting->status)>0 && $setting->status[0]->status == 1){
                $cek = true;
            }
        }
        if($cek)
            return view('karyawan.kpi-item.edit')->with($param);
        else
            return redirect()->route('karyawan.kpi-item.index')->with('message-error', 'Data is not found');
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
