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
use Illuminate\Support\Facades\Input;
use DataTables;
use Illuminate\Support\Facades\Validator;

class KpiSurveyManagerController extends Controller
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
        return view('karyawan.kpi-survey.index');
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
        \Session::put('period', $period_id);
        \Session::put('position', $position_id);
        \Session::put('status', $status_id);
        $employees = KpiEmployee::join('users as u','kpi_employee.user_id','=','u.id')
            ->join('kpi_periods as kp','kpi_employee.kpi_period_id','=','kp.id')
            ->leftJoin('structure_organization_custom as so','kpi_employee.structure_organization_custom_id','=','so.id')
            ->leftJoin('organisasi_position as op','so.organisasi_position_id','=','op.id')
            ->leftJoin('organisasi_division as od','so.organisasi_division_id','=','od.id')
            ->leftJoin('organisasi_title as ot', 'so.organisasi_title_id','=','ot.id')
            ->where(['so.parent_id'=>$user->structure_organization_custom_id])
            ->select(['kpi_employee.id',\DB::raw("CONCAT(DATE_FORMAT(kp.start_date, '%d %M %Y'), ' - ',DATE_FORMAT(kp.end_date, '%d %M %Y')) AS period"),'u.nik','u.name',\DB::raw('CONCAT(COALESCE(op.name,"") ,CONCAT(if(od.name is null ,"","-")), COALESCE(od.name,""),CONCAT(if(ot.name is null ,"","-")), COALESCE(ot.name,"")) as position'),'kpi_employee.status','final_score']);
        if($period_id!='0'){
            $employees = $employees->where('kp.id',$period_id);
        }
        if($position_id!='0'){
            $employees = $employees->where('so.id',$position_id);
        }
        if($status_id!='-1'){
            $employees = $employees->where('kpi_employee.status',$status_id);
        }
        $employees = $employees->where('kp.is_lock',1);
        return DataTables::of($employees)
            ->addColumn('action', function ($employee) {
                return '<a href="'.route('karyawan.kpi-survey.edit', $employee->id).'"> <button class="btn btn-info btn-xs m-r-5"><i class="fa fa-edit"></i> edit</button></a>';
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
        $user = Auth::user();
        $validator = Validator::make(request()->all(), [
            'status'  => 'required',
            'kpi_employee_id' => 'required|exists:kpi_employee,id'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()]);
        }
        $employee = KpiEmployee::where('id',$request->kpi_employee_id)->first();
//        print_r($request->spv_score);
//        return;
        if($request->status == 1 || $request->status == 2){
            if($request->status == 2) {

                $validator = Validator::make(request()->all(), [
                    'spv_score.*' => "required|numeric|min:".$employee->period->min_rate."|max:".$employee->period->max_rate,
                ]);

                if ($validator->fails()) {
                    return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()]);
                }
                $employee->supervisor_id = $user->id;
                $employee->supervisor_input_date = date("Y-m-d");



                //Send Email
                $params = getEmailConfig();
                Config::set('database.default','mysql');

                $params['view']     = 'email.kpi-submit-score-manager';
                $params['subject']     = $params['mail_name'].' - KPI Final Scores';
                $params['user']     = $employee->user;
                $params['manager']   = $employee->supervisor;
                $params['period']   = $employee->period;
                $params['email']    = $employee->user->email;
                $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                dispatch($job);
                Config::set('database.default',session('db_name','mysql'));

            }
            $organization = 0;
            $manager      = 0;
            $final        = 0;
            foreach ($request->spv_score as $item_id => $spv_score){
                $score = KpiEmployeeScoring::where(['kpi_item_id'=>$item_id,'kpi_employee_id'=>$request->kpi_employee_id])->first();
                if($score){
                    $score->supervisor_score = $spv_score;
                    $score->comment = isset($request->comment[$item_id])?$request->comment[$item_id]:null;
                }
                else{
                    $score = new KpiEmployeeScoring(
                        ['kpi_item_id'=>$item_id,
                            'kpi_employee_id'=>$request->kpi_employee_id,
                            'supervisor_score'=>$spv_score,
                            'comment'=>isset($request->comment[$item_id])?$request->comment[$item_id]:null]);
                }

                $score->save();

                $this_score = ($score->supervisor_score*$score->kpi_item->weightage)/100;
                $final += $this_score;
                if($score->kpi_item->setting->kpi_module_id == 1)
                    $organization += $this_score;
                if($score->kpi_item->setting->kpi_module_id == 2)
                    $manager += $this_score;
            }
            $employee->final_score          = "$final";
            $employee->manager_score        = "$manager";
            $employee->organization_score   = "$organization";
        }
        $employee->status = $request->status;
        $employee->save();
        return response()->json(['status' => 'success', 'message' => 'KPI Evaluation is saved']);
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
        $param = getKpiDetail(null,$user,$id);
        if($param)
            return view('karyawan.kpi-survey.edit')->with($param);
        else
            return redirect()->route('karyawan.kpi-survey.index')->with('message-error', 'Data is not found or its KPI Items are not set yet');
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

    public function download(Request $request){
        $user = Auth::user();
        $period_id = $request->input('id_period', '0');
        $position_id = $request->input('id_position', '0');
        $status_id = $request->input('id_status', '-1');
        $employees = KpiEmployee::join('users as u','kpi_employee.user_id','=','u.id')
            ->leftJoin('users as s','kpi_employee.supervisor_id','=','s.id')
            ->join('kpi_periods as kp','kpi_employee.kpi_period_id','=','kp.id')
            ->leftJoin('structure_organization_custom as so','kpi_employee.structure_organization_custom_id','=','so.id')
            ->leftJoin('organisasi_position as op','so.organisasi_position_id','=','op.id')
            ->leftJoin('organisasi_division as od','so.organisasi_division_id','=','od.id')
            ->where(['so.parent_id'=>$user->structure_organization_custom_id])
            ->select(['kpi_employee.id','kp.max_rate',\DB::raw("CONCAT(DATE_FORMAT(kp.start_date, '%d %M %Y'), ' - ',DATE_FORMAT(kp.end_date, '%d %M %Y')) AS period"),'u.nik','u.name','s.name as supervisor',\DB::raw('CONCAT(COALESCE(op.name,"") ,"-", COALESCE(od.name,"")) as position'),'kpi_employee.status','organization_score','manager_score','final_score', 'kp.id as period_id']);
        if($period_id!='0'){
            $employees = $employees->where('kp.id',$period_id);
        }
        if($position_id!='0'){
            $employees = $employees->where('so.id',$position_id);
        }
        if($status_id!='-1'){
            $employees = $employees->where('kpi_employee.status',$status_id);
        }
        $employees = $employees->where('kp.is_lock',1);
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
        $user = Auth::user();
        $period_id = $request->input('id_period', '0');
        $position_id = $request->input('id_position', '0');
        $status_id = $request->input('id_status', '-1');
        $employees = KpiEmployee::with(['scorings', 'user'])->whereHas('period', function($query){
            $query->where('is_lock', 1);
        })->whereHas('structure', function($query) use ($user){
            $query->where('parent_id', $user->structure_organization_custom_id);
        });
        if($period_id!='0'){
            $employees = $employees->where('kpi_employee.kpi_period_id', $period_id);
        }
        if($position_id!='0'){
            $employees = $employees->where('kpi_employee.structure_organization_custom_id', $position_id);
        }
        if($status_id!='-1'){
            $employees = $employees->where('kpi_employee.status', $status_id);
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

    public function downloadImport(Request $request){
        $user = Auth::user();
        $period_id = $request->input('id_period', '0');
        $position_id = $request->input('id_position', '0');
        $employees = KpiEmployee::with(['scorings', 'user'])->whereHas('period', function($query){
            $query->where('is_lock', 1);
        })->whereHas('structure', function($query) use ($user){
            $query->where('parent_id', $user->structure_organization_custom_id);
        });
        if($period_id!='0'){
            $employees = $employees->where('kpi_employee.kpi_period_id', $period_id);
        }
        if($position_id!='0'){
            $employees = $employees->where('kpi_employee.structure_organization_custom_id', $position_id);
        }
        $employees = $employees->where('status', 1)->get();

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
        $no = -1;
        foreach ($employees as $key => $employee) {
            if ($employee->user) {
                $data['data'][++$no][] = $employee->user->nik;
                $data['data'][$no][] = $employee->user->name;
                foreach ($data['header'] as $header) {
                    $temp = $employee->scorings->where('kpi_item_id', $header->id)->first();
                    $data['data'][$no][] = $temp ? $temp->self_score : null;
                    $data['data'][$no][] = $temp ? $temp->justification : null;
                    $data['data'][$no][] = $temp ? $temp->supervisor_score : null;
                    $data['data'][$no][] = $temp ? $temp->comment : null;
                }
            }
        }

        return (new \App\Models\KPISurveyImportDownload($data, $period, $position, $minmax))->download('EM-HR.Report-KPI-Detail_'.$period.'_'.$position.'.xlsx');
    }

    public function import(Request $request)
    {
        $user = Auth::user();
        $period = KpiPeriod::find($request->import_id_period);
        $item_ids = $period->items->whereIn('structure_organization_custom_id', [null, $request->import_id_position]);
        
        if ($request->hasFile('file')) {
            //$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($request->file);
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($request->file);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = [];
            $branchsvisitcount=0;
            foreach ($worksheet->getRowIterator() as $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(FALSE); // This loops through all cells,
                $cells = [];
                foreach ($cellIterator as $cell) {
                    $cells[] = $cell->getValue();
                }
                $rows[] = $cells;
            }

            $kpiArray = [];
            $scoreArray = [];
            $errors = [];

            foreach ($rows as $key => $item) {
                if ($key >= 5 && !empty($item[1])) {
                    $employee = KpiEmployee::where('kpi_period_id', $request->import_id_period)->whereHas('user', function($query) use ($item){
                        $query->where('nik', $item[1]);
                    })->first();

                    $organization = 0;
                    $manager      = 0;
                    $final        = 0;
                    $index        = 2;

                    foreach ($item_ids as $item_id){
                        if (!isset($item[$index+=3])) {
                            array_push($errors, 'User '.$item[1].' '.$item_id->name.' score can\'t be blank');
                        } else if ($item[$index] < $period->min_rate) {
                            array_push($errors, 'User '.$item[1].' '.$item_id->name.' score less than min rate');
                        } else if ($item[$index] > $period->max_rate) {
                            array_push($errors, 'User '.$item[1].' '.$item_id->name.' score more than max rate');
                        }

                        $score = KpiEmployeeScoring::where('kpi_item_id', $item_id->id)->where('kpi_employee_id', $employee->id)->first();
                        $score->supervisor_score = $item[$index];
                        $score->comment = $item[++$index];
                        
                        $this_score = ($score->supervisor_score*$score->kpi_item->weightage)/100;
                        $final += $this_score;
                        if($score->kpi_item->setting->kpi_module_id == 1)
                            $organization += $this_score;
                        if($score->kpi_item->setting->kpi_module_id == 2)
                            $manager += $this_score;

                        $score = $score->toArray();
                        unset($score['kpi_item']);
                        array_push($scoreArray, $score);
                    }

                    $employee->final_score              = "$final";
                    $employee->manager_score            = "$manager";
                    $employee->organization_score       = "$organization";
                    $employee->supervisor_id            = $user->id;
                    $employee->supervisor_input_date    = date("Y-m-d");
                    $employee->status = 2;

                    array_push($kpiArray, $employee);
                }
            }
        } else {
            return redirect()->back()->with('message-error', 'File not found');
        }

        if (count($errors)) {
            $error = '';
            foreach ($errors as $key => $value) {
                if ($key > 0) {
                    $error .= ', ';
                }
                $error .= $value;
            }
            return redirect()->back()->with('message-error', $error);
        }        

        //Send Email
        $params = getEmailConfig();
        Config::set('database.default','mysql');

        $params['view']     = 'email.kpi-submit-score-manager';
        $params['subject']     = $params['mail_name'].' - KPI Final Scores';
        foreach ($kpiArray as $key => $employee) {
            $params['user']     = $employee->user;
            $params['manager']   = $employee->supervisor;
            $params['period']   = $employee->period;
            $params['email']    = $employee->user->email;
            $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
            dispatch($job);
            
            $employee = $employee->toArray();
            unset($employee['user']);
            unset($employee['supervisor']);
            unset($employee['period']);
            $kpiArray[$key] = $employee;
        }
        Config::set('database.default',session('db_name','mysql'));

        \Batch::update(new KpiEmployeeScoring, $scoreArray, 'id');
        \Batch::update(new KpiEmployee, $kpiArray, 'id');

        return redirect()->back()->with('message-success', 'KPI survey data successfully imported');
    }
}
