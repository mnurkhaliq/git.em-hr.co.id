<?php

namespace App\Http\Controllers\Administrator;

use App\User;
use App\Models\KpiEmployee;
use App\Models\KpiItem;
use App\Models\KpiPeriod;
use App\Models\KpiSettingScoring;
use App\Models\StructureOrganizationCustom;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use DataTables;
use Illuminate\Support\Facades\Validator;

class KpiSurveyController extends Controller
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
        return view('administrator.kpi-survey.index');
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

    public function table(Request $request){
        $user = Auth::user();
        $period_id = $request->input('id_period', '0');
        $position_id = $request->input('id_position', '0');
        $status_id = $request->input('id_status', '-1');
        $user_id = $request->input('id_user', '0');

        $data_user = User::where('id', $user_id)->first();
        if($data_user){
            \Session::put('ks_nik', $data_user->nik);
            \Session::put('ks_name', $data_user->name);
            \Session::put('ks_user_id',  $user_id);
        }
        \Session::put('ks_period', $period_id);
        \Session::put('ks_position', $position_id);
        \Session::put('ks_status', $status_id);
        
        $employees = KpiEmployee::join('users as u','kpi_employee.user_id','=','u.id')
            ->leftJoin('users as s','kpi_employee.supervisor_id','=','s.id')
            ->join('kpi_periods as kp','kpi_employee.kpi_period_id','=','kp.id')
            ->leftJoin('structure_organization_custom as so','kpi_employee.structure_organization_custom_id','=','so.id')
            ->leftJoin('organisasi_position as op','so.organisasi_position_id','=','op.id')
            ->leftJoin('organisasi_division as od','so.organisasi_division_id','=','od.id')
            ->leftJoin('organisasi_title as ot', 'so.organisasi_title_id','=','ot.id')
            ->select(['kpi_employee.id',\DB::raw("CONCAT(DATE_FORMAT(kp.start_date, '%d %M %Y'), ' - ',DATE_FORMAT(kp.end_date, '%d %M %Y')) AS period"),'u.nik','u.name','s.name as supervisor',\DB::raw('CONCAT(COALESCE(op.name,"") ,CONCAT(if(od.name is null ,"","-")), COALESCE(od.name,""),CONCAT(if(ot.name is null ,"","-")), COALESCE(ot.name,"")) as position'),'kpi_employee.status','final_score']);
        if($period_id!='0'){
            $employees = $employees->where('kp.id',$period_id);
        }
        if($position_id!='0'){
            $employees = $employees->where('so.id',$position_id);
        }
        if($status_id!='-1'){
            $employees = $employees->where('kpi_employee.status',$status_id);
        }
        if($user_id!='0' && $user_id!=null){
            $employees = $employees->where('kpi_employee.user_id',$user_id);
        }
        // $employees = $employees->where('kp.is_lock',1);
        return DataTables::of($employees)
            ->addColumn('action', function ($employee) {
                return '<a href="'.route('administrator.kpi-survey.edit', $employee->id).'"> <button class="btn btn-info btn-xs m-r-5"><i class="fa fa-edit"></i> detail</button></a>
                        <button class="btn btn-danger btn-xs m-r-5" onclick="remove('.$employee->id.')"><i class="fa fa-trash"></i> delete</button>';
            })
            ->make(true);
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
        //
        $user = Auth::user();
        $cek = false;
        $employee = KpiEmployee::with('user')->find($id);

        if($employee){
            $structure = StructureOrganizationCustom::where('id', $employee->structure_organization_custom_id)->first();
            $period = KpiPeriod::with('settings.module')->where(['id' => $employee->kpi_period_id,'is_lock'=>1])->first();
            if($structure && $period) {

                $cek = true;
                $items = KpiItem::with(['scoring' => function ($query) use ($employee) {
                    $query->where('kpi_employee_id', $employee->id);
                }])->join('kpi_setting_scoring as ss', 'kpi_items.kpi_setting_scoring_id', '=', 'ss.id')
                    ->join('kpi_periods as kp', 'ss.kpi_period_id', '=', 'kp.id')
                    ->where('kp.id', $period->id)
                    ->whereRaw(DB::raw("(kpi_items.structure_organization_custom_id is null or kpi_items.structure_organization_custom_id = $employee->structure_organization_custom_id)"))
                    ->select('kpi_items.*')
                    ->get();
                $structure = DB::table('structure_organization_custom as so')
                    ->leftJoin('organisasi_position as op', 'so.organisasi_position_id', '=', 'op.id')
                    ->leftJoin('organisasi_division as od', 'so.organisasi_division_id', '=', 'od.id')
                    ->leftJoin('organisasi_title as ot', 'so.organisasi_title_id', '=', 'ot.id')
                    ->select([DB::raw("CONCAT(COALESCE(op.name,''),IF(od.name IS NOT NULL, CONCAT(' - ',COALESCE(od.name,'')), ''),IF(ot.name IS NOT NULL, CONCAT(' - ',COALESCE(ot.name,'')), '')) as position")])
                    ->where('so.id', $employee->structure_organization_custom_id)
                    ->first();
                $param = ['employee' => $employee, 'period' => $period, 'items' => $items, 'position' => $structure];
            }
        }
        if($cek)
            return view('administrator.kpi-survey.edit')->with($param);
        else
            return redirect()->route('administrator.kpi-survey.index')->with('message-error', 'Data is not found');
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
        $deleted = KpiEmployee::destroy($id);
        if($deleted){
            return response()->json(['status' => 'success', 'message' => 'Data has been deleted']);
        }
        else{
            return response()->json(['status' => 'error', 'message' => 'Cannot delete data'],404);
        }

    }
    /**
     * Add employee to a kpi period
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function addEmployee(Request $request)
    {
        $user = Auth::user();
        $validator = Validator::make(request()->all(), [
            'user_id'  => 'required|exists:users,id',
            'kpi_period_id' => 'required|exists:kpi_periods,id',
            'structure_organization_custom_id'=> 'required|exists:structure_organization_custom,id'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()]);
        }
        $param = [
            'user_id'=>$request->user_id,
            'structure_organization_custom_id'=>$request->structure_organization_custom_id,
            'kpi_period_id'=>$request->kpi_period_id
        ];
        $kpiEmployee = KpiEmployee::where($param)->first();
        if($kpiEmployee){
            return response()->json(['status' => 'failed', 'message' => 'The employee already has this position in this period!']);
        }
        else{
            $newKpiEmployee = new KpiEmployee($param);
            $newKpiEmployee->save();
            return response()->json(['status' => 'success', 'message' => 'The employee has been successfully included into this period!']);
        }
    }
    public function download(Request $request){
        $period_id = $request->input('id_period', '0');
        $position_id = $request->input('id_position', '0');
        $status_id = $request->input('id_status', '-1');
        $user_id = $request->input('id_user', '0');
        $employees = KpiEmployee::join('users as u','kpi_employee.user_id','=','u.id')
            ->leftJoin('users as s','kpi_employee.supervisor_id','=','s.id')
            ->join('kpi_periods as kp','kpi_employee.kpi_period_id','=','kp.id')
            ->leftJoin('structure_organization_custom as so','kpi_employee.structure_organization_custom_id','=','so.id')
            ->leftJoin('organisasi_position as op','so.organisasi_position_id','=','op.id')
            ->leftJoin('organisasi_division as od','so.organisasi_division_id','=','od.id')
            ->leftJoin('organisasi_title as ot','so.organisasi_title_id','=','ot.id')
            ->select(['kpi_employee.id','kp.max_rate',\DB::raw("CONCAT(DATE_FORMAT(kp.start_date, '%d %M %Y'), ' - ',DATE_FORMAT(kp.end_date, '%d %M %Y')) AS period"),'u.nik','u.name','s.name as supervisor',\DB::raw("CONCAT(COALESCE(op.name,''),IF(od.name IS NOT NULL, CONCAT(' - ',COALESCE(od.name,'')), ''),IF(ot.name IS NOT NULL, CONCAT(' - ',COALESCE(ot.name,'')), '')) as position"),'kpi_employee.status','organization_score','manager_score','final_score', 'kp.id as period_id']);
        if($period_id!='0'){
            $employees = $employees->where('kp.id',$period_id);
        }
        if($position_id!='0'){
            $employees = $employees->where('so.id',$position_id);
        }
        if($status_id!='-1'){
            $employees = $employees->where('kpi_employee.status',$status_id);
        }
        if($user_id!='0' && $user_id!=null){
            $employees = $employees->where('kpi_employee.user_id',$user_id);
        }
        // $employees = $employees->where('kp.is_lock',1);
        $employees = $employees->get();
        $params['data'] = [];
        foreach($employees as $no =>  $item)
        {
            $status = "";
            if($item->status == 0)
                $status = "DRAFT";
            else if($item->status == 1)
                $status = "SELF REVIEWED";
            else if($item->status == 2)
                $status = "FINAL REVIEWED";
            else if($item->status == 3)
                $status = "ACKNOWLEDGED";
            $item->status               = $status;

            $organization_kpi = KpiSettingScoring::where(['kpi_period_id'=>$item->period_id,'kpi_module_id'=>1])->first();
            $manager_kpi      = KpiSettingScoring::where(['kpi_period_id'=>$item->period_id,'kpi_module_id'=>2])->first();
            if($organization_kpi && $item->organization_score){
                $item->organization_score .= " / ".$organization_kpi->weightage*$item->max_rate/100;
            }
            if($manager_kpi && $item->manager_score){
                $item->manager_score .= " / ".$manager_kpi->weightage*$item->max_rate/100;
            }

            if($item->final_score!=null)
                $final_score     = $item->final_score." / ".$item->max_rate;
            else
                $final_score     = "Not Finished Yet";
            $item->final_score          = $final_score;
            $params['data'][$no]        = $item;

        }

        $title = 'Report Key Performance Index';
        if($period_id!='0'){
            $period = KpiPeriod::find($period_id);
            if($period){
                $start_date = \Carbon\Carbon::parse($period->start_date)->format('d M Y');
                $end_date   = \Carbon\Carbon::parse($period->end_date)->format('d M Y');
                $title     .= ' Period '.$start_date.' - '.$end_date;
            }
        }


        return (new \App\Models\KPISurveyExport($params, $title))->download('EM-HR.Report-KPI'.date('d-m-Y') .'.xlsx');
    }

    public function downloadDetail(Request $request){
        $period_id = $request->input('id_period', '0');
        $position_id = $request->input('id_position', '0');
        $status_id = $request->input('id_status', '-1');
        $user_id = $request->input('id_user', '0');
        $employees = KpiEmployee::with(['scorings', 'user']);
        if($period_id!='0'){
            $employees = $employees->where('kpi_employee.kpi_period_id', $period_id);
        }
        if($position_id!='0'){
            $employees = $employees->where('kpi_employee.structure_organization_custom_id', $position_id);
        }
        if($status_id!='-1'){
            $employees = $employees->where('kpi_employee.status', $status_id);
        }
        if($user_id!='0' && $user_id!=null){
            $employees = $employees->where('kpi_employee.user_id', $user_id);
        }
        $employees = $employees->get();

        $title = 'Report Detail KPI';
        $temp_period = null;
        $minmax = null;
        $period = null;
        if($period_id!='0'){
            $period = KpiPeriod::find($period_id);
            if($period){
                $temp_period = $period;
                $minmax = $period->min_rate.' - '.$period->max_rate;
                $period = \Carbon\Carbon::parse($period->start_date)->format('d M Y').' - '.\Carbon\Carbon::parse($period->end_date)->format('d M Y');
            }
        }
        $data['header'] = [];
        $position = null;
        if($position_id!='0'){
            $position = StructureOrganizationCustom::find($position_id);
            if($position){
                if($temp_period){
                    $data['header'] = $temp_period->items->whereIn('structure_organization_custom_id', [null, $position_id]);
                }
                $position = $position ? $position->position->name.($position->division ? ' - '.$position->division->name : '').($position->title ? ' - '.$position->title->name : '') : '';
            }
        }
        
        $data['data'] = [];
        $no = 0;
        foreach ($employees as $key => $employee) {
            if ($employee->user) {
                $data['data'][$no][] = $employee->user->nik;
                $data['data'][$no][] = $employee->user->name;
                foreach ($data['header'] as $header) {
                    $temp = $employee->scorings->where('kpi_item_id', $header->id)->first();
                    $data['data'][$no][] = $temp ? $temp->self_score : null;
                    $data['data'][$no][] = $temp ? $temp->justification : null;
                    $data['data'][$no][] = $temp ? $temp->supervisor_score : null;
                    $data['data'][$no][] = $temp ? $temp->comment : null;
                }
                $data['data'][$no][] = $employee->final_score;
                $status = "";
                if($employee->status == 0)
                    $status = "DRAFT";
                else if($employee->status == 1)
                    $status = "SELF REVIEWED";
                else if($employee->status == 2)
                    $status = "FINAL REVIEWED";
                else if($employee->status == 3)
                    $status = "ACKNOWLEDGED";
                $data['data'][$no++][] = $status;
            }
        }

        return (new \App\Models\KPISurveyExportDetail($data, $title, $period, $position, $minmax))->download('EM-HR.Report-KPI-Detail'.date('d-m-Y') .'.xlsx');
    }
}
