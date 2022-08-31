<?php

namespace App\Http\Controllers\Administrator;

use App\Models\KpiEmployee;
use App\Models\KpiItem;
use App\Models\KpiPeriod;
use App\Models\StructureOrganizationCustom;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use DataTables;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Models\CareerHistory;
use Excel;
use App\Imports\CareerHistoryImport;

class CareerController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
        $this->middleware('module:26');
    }

    public function index(){
        $user = \Auth::user();
        return view('administrator.career.index');
    }

    public function table(Request $request){
        $user = Auth::user();
        $name = $request->input('name', '');
        $branch_id = $request->input('branch_id', '0');
        $position_id = $request->input('position_id', '0');
        $division_id = $request->input('division_id', '0');
        $status = $request->input('status', '');
        $employee_resign = $request->input('employee_resign', '');

        \Session::put('c_name', $name);
        \Session::put('c_branch_id', $branch_id);
        \Session::put('c_position_id', $position_id);
        \Session::put('c_division_id', $division_id);
        \Session::put('c_status', $status);
        \Session::put('c_employee_resign', $employee_resign);

        $employees = User::whereIn('access_id',[1,2])
            ->leftJoin('cabang as c','users.cabang_id','=','c.id')
            ->leftJoin('structure_organization_custom','users.structure_organization_custom_id','=','structure_organization_custom.id')
            ->leftJoin('organisasi_position as op', 'structure_organization_custom.organisasi_position_id', '=', 'op.id')
            ->leftJoin('organisasi_division as od', 'structure_organization_custom.organisasi_division_id', '=', 'od.id')
            ->leftJoin('organisasi_title as ot', 'structure_organization_custom.organisasi_title_id', '=', 'ot.id')
            ->select(['users.id as id', 'users.nik as nik', 'users.organisasi_status', 'users.name', 'c.name as branch', \DB::raw("CONCAT(COALESCE(op.name,''),IF(od.name IS NOT NULL, CONCAT(' - ',COALESCE(od.name,'')), ''),IF(ot.name IS NOT NULL, CONCAT(' - ',COALESCE(ot.name,'')), '')) as position")]);
        if ($name!=''){
            $employees = $employees->where(function($query) use ($name) {
                $query->where('users.name', 'LIKE', '%' . $name . '%')->orWhere('users.nik', 'LIKE', '%' . $name . '%');
            });
        }
        if($branch_id!='0'){
            $employees = $employees->where('c.id',$branch_id);
        }
        if($position_id!='0'){
            $employees = $employees->where('op.id',$position_id);
        }
        if($division_id!='0'){
            $employees = $employees->where('od.id',$division_id);
        }
        if($status!=''){
            $employees = $employees->where('users.organisasi_status',$status);
        }
        if($employee_resign!=''){
            if ($employee_resign == 'Active')
                $employees = $employees->where(function($query) {
                    $query->whereNull('users.non_active_date')->orWhere('users.non_active_date', '>', \Carbon\Carbon::now());
                })->where(function($query) {
                    $query->whereNull('users.join_date')->orWhere('users.join_date', '<=', \Carbon\Carbon::now());
                });
            else
                $employees = $employees->where(function($query) {
                    $query->whereNotNull('users.non_active_date')->where('users.non_active_date', '<=', \Carbon\Carbon::now());
                })->orWhere(function($query) {
                    $query->whereNotNull('users.join_date')->where('users.join_date', '>', \Carbon\Carbon::now());
                });
        }
        $employees = $employees->whereIn('users.access_id', ['1', '2']);

        return DataTables::of($employees)
            ->addColumn('action', function ($employee) {
                return '<a href="'.route('administrator.career.detail', $employee->id).'"> <button class="btn btn-info btn-xs m-r-5"><i class="fa fa-edit"></i> Detail</button></a>';
            })
            ->make(true);
    }

    public function detail($id){
        $auth = \Auth::user();
        $user = User::where('id', $id)->first(); 
        $data['data'] = CareerHistory::orderBy('effective_date', 'DESC')
                ->orderBy('id', 'DESC')
                ->leftJoin('users as u', 'career_history.user_id', '=', 'u.id')
                ->leftJoin('cabang as c', 'career_history.cabang_id', '=', 'c.id')
                ->leftJoin('structure_organization_custom', 'career_history.structure_organization_custom_id', '=', 'structure_organization_custom.id')
                ->leftJoin('organisasi_position as op', 'structure_organization_custom.organisasi_position_id', '=', 'op.id')
                ->leftJoin('organisasi_division as od', 'structure_organization_custom.organisasi_division_id', '=', 'od.id')
                ->leftJoin('organisasi_title as ot', 'structure_organization_custom.organisasi_title_id', '=', 'ot.id')
                ->where('career_history.user_id', $id)
                ->select([
                    'career_history.id',
                    'career_history.status',
                    'career_history.start_date as start',
                    'career_history.end_date as end',
                    'u.id as user_id',
                    'u.name',
                    'u.nik',
                    'c.name as branch',
                    'c.alamat as branch_address',
                    \DB::raw("CONCAT(COALESCE(op.name,''),IF(od.name IS NOT NULL, CONCAT(' - ',COALESCE(od.name,'')), ''),IF(ot.name IS NOT NULL, CONCAT(' - ',COALESCE(ot.name,'')), '')) as position"),
                    'effective_date',
                    'job_desc'
                ])
                ->get();
        $data['type'] = 'exist';
        $data['join_date'] = '';
        $data['future'] = '';
        $data['current'] = '';
        $data['emp_status'] = $user->organisasi_status;
        $data['end_date'] = '';
        $data['join_date_first'] = $user->join_date;

        $userData = CareerHistory::orderBy('effective_date', 'DESC')
                        ->orderBy('id', 'DESC')
                        ->where('user_id', $id)
                        ->where('structure_organization_custom_id', $user->structure_organization_custom_id)
                        ->where('effective_date', '<=', Carbon::now()->format('Y-m-d'))
                        ->first();

        $dataCon = CareerHistory::where('user_id', $id)
                    ->whereDate('effective_date', '<=', Carbon::now()->format('Y-m-d'))
                    ->orderBy('effective_date', 'DESC')
                    ->orderBy('id', 'DESC')
                    ->whereNotNull('status')
                    ->where('status', '!=', 'Permanent')
                    ->first();

        if($dataCon){
            $data['join_date'] = $dataCon->start_date;
            $data['end_date'] = $dataCon->end_date;
        }

        if($userData){
            $data['current'] = $userData->id;
        }

        $dataForFutureAdmin = CareerHistory::orderBy('effective_date', 'DESC')
                ->orderBy('id', 'DESC')
                ->where('career_history.user_id', $id)
                ->first();
        if($dataForFutureAdmin){
            $future = StructureOrganizationCustom::where('id', $dataForFutureAdmin->structure_organization_custom_id)->first();
            if($future){
                $futureParent = StructureOrganizationCustom::where('structure_organization_custom.id', $future->parent_id)
                ->leftJoin('organisasi_position as op', 'structure_organization_custom.organisasi_position_id', '=', 'op.id')
                ->leftJoin('organisasi_division as od', 'structure_organization_custom.organisasi_division_id', '=', 'od.id')
                ->leftJoin('organisasi_title as ot', 'structure_organization_custom.organisasi_title_id', '=', 'ot.id')
                ->select([\DB::raw("CONCAT(COALESCE(op.name,''),IF(od.name IS NOT NULL, CONCAT(' - ',COALESCE(od.name,'')), ''),IF(ot.name IS NOT NULL, CONCAT(' - ',COALESCE(ot.name,'')), '')) as position")])
                ->first();

                if($futureParent){
                    $data['future'] = $futureParent->position;
                }
            }
        }
        else{
            $data['future'] = '';
        }

        // dd($joinDate);
        if(count($data['data'])==0){
            // return redirect()->route('career.index')->with('message-error', 'Data not found.');
            $data['type'] = 'not exist';
        }
        // dd($data['data']);
        if(isset($_GET['layout_career'])){
            if($auth){
                $setting = \App\Models\Setting::where('key', 'layout_career')->first();
            }

            if(!$setting){
                $setting = new \App\Models\Setting();
                $setting->key = 'layout_career';
                if($auth->project_id != NULL)
                {
                    $setting->project_id = $auth->project_id;
                }
            }
    
            $setting->value = $_GET['layout_career'];
            $setting->save();
        }
        // dd($data);

        return view('administrator.career.detail', compact('data', 'id', 'user'));
    }

    public function download(Request $request){
        // $branch_id = $request->input('branch_id', '0');
        // $position_id = $request->input('position_id', '0');
        // $division_id = $request->input('division_id', '0');
        // $employees = User::whereIn('access_id',[1,2])
        //     ->leftJoin('cabang as c','users.cabang_id','=','c.id')
        //     ->leftJoin('structure_organization_custom','users.structure_organization_custom_id','=','structure_organization_custom.id')
        //     ->leftJoin('organisasi_division as d','structure_organization_custom.organisasi_division_id','=','od.id')
        //     ->leftJoin('organisasi_position as j','structure_organization_custom.organisasi_position_id','=','op.id')
        //     ->select(['users.id as id', 'users.nik as nik', 'users.name as name', 'c.name as branch', 'j.name as position', 'd.name as division']);
        // if($branch_id!='0'){
        //     $employees = $employees->where('c.id',$branch_id);
        // }
        // if($position_id!='0'){
        //     $employees = $employees->where('op.id',$position_id);
        // }
        // if($division_id!='0'){
        //     $employees = $employees->where('od.id',$division_id);
        // }
        // $employees = $employees->whereIn('users.access_id', ['1', '2']);
        // $employees = $employees->get();
        // $params = [];
        // foreach($employees as $no =>  $item)
        // {
        //     $params[$no]['NO']                  = $no+1;
        //     $params[$no]['NIK']                 = $item->nik;
        //     $params[$no]['NAME']                = $item->name;
        //     $params[$no]['BRANCH']              = $item->branch;
        //     $params[$no]['POSITION']            = $item->position;
        // }
        // // dd($params);
        // return (new \App\Models\CareerExport($params, 'User Career History' ))->download('EM-HR.Report-Career'.date('d-m-Y') .'.xlsx');
        // $uid = $request->input('uid');

        $name = $request->input('name', '');
        $branch_id = $request->input('branch_id', '0');
        $position_id = $request->input('position_id', '0');
        $division_id = $request->input('division_id', '0');
        $status = $request->input('status', '');
        $employee_resign = $request->input('employee_resign', '');

        $data = CareerHistory::orderBy('u.nik', 'ASC')->orderBy('effective_date', 'DESC')
            ->orderBy('id', 'DESC')
            ->leftJoin('users as u', 'career_history.user_id', '=', 'u.id')
            ->leftJoin('cabang as c','career_history.cabang_id','=','c.id')
            ->leftJoin('structure_organization_custom','career_history.structure_organization_custom_id','=','structure_organization_custom.id')
            ->leftJoin('organisasi_position as op', 'structure_organization_custom.organisasi_position_id', '=', 'op.id')
            ->leftJoin('organisasi_division as od', 'structure_organization_custom.organisasi_division_id', '=', 'od.id')
            ->leftJoin('organisasi_title as ot', 'structure_organization_custom.organisasi_title_id', '=', 'ot.id')
            ->select(['career_history.id', 'u.id as uid', 'u.name', 'u.nik', 'c.name as branch', \DB::raw("CONCAT(COALESCE(op.name,''),IF(od.name IS NOT NULL, CONCAT(' - ',COALESCE(od.name,'')), ''),IF(ot.name IS NOT NULL, CONCAT(' - ',COALESCE(ot.name,'')), '')) as position"), 'effective_date', 'job_desc'])
            ->where('career_history.user_id', '!=', null)
            ->whereIn('u.access_id', ['1', '2']);
        if ($name!=''){
            $data = $data->where(function($query) use ($name) {
                $query->where('u.name', 'LIKE', '%' . $name . '%')->orWhere('u.nik', 'LIKE', '%' . $name . '%');
            });
        }
        if($branch_id!='0'){
            $data = $data->where('c.id',$branch_id);
        }
        if($position_id!='0'){
            $data = $data->where('op.id',$position_id);
        }
        if($division_id!='0'){
            $data = $data->where('od.id',$division_id);
        }
        if($status!=''){
            $data = $data->where('u.organisasi_status',$status);
        }
        if($employee_resign!=''){
            if ($employee_resign == 'Active')
                $data = $data->where(function($query) {
                    $query->whereNull('u.non_active_date')->orWhere('u.non_active_date', '>', \Carbon\Carbon::now());
                })->where(function($query) {
                    $query->whereNull('u.join_date')->orWhere('u.join_date', '<=', \Carbon\Carbon::now());
                });
            else
                $data = $data->where(function($query) {
                    $query->whereNotNull('u.non_active_date')->where('u.non_active_date', '<=', \Carbon\Carbon::now());
                })->orWhere(function($query) {
                    $query->whereNotNull('u.join_date')->where('u.join_date', '>', \Carbon\Carbon::now());
                });
        }
        $data = $data->get()->toArray();

        for($i = 0; $i < count($data); $i++){
            if (isset($data[$i+1]) && $data[$i]['uid'] == $data[$i+1]['uid']) {
                $data[$i]['prev_branch']             = $data[$i+1]['branch'];
                $data[$i]['prev_position']           = $data[$i+1]['position'];
                $data[$i]['prev_effective_date']     = $data[$i+1]['effective_date'];
                $data[$i]['prev_job_desc']           = $data[$i+1]['job_desc'];
            } else {
                $data[$i]['prev_branch']             = '-';
                $data[$i]['prev_position']           = '-';
                $data[$i]['prev_effective_date']     = '-';
                $data[$i]['prev_job_desc']           = '-';
            }
        }
        
        $params = [];
        foreach($data as $no =>  $item)
        {
            $last = '';
            if($item['prev_effective_date'] == '-'){
                $last = '-';
            }
            else{
                $last = date('F j, Y', strtotime($item['prev_effective_date']));
            }
            $params[$no]['NO']                           = $no+1;
            $params[$no]['NIK']                          = $item['nik'];
            $params[$no]['NAME']                         = $item['name'];
            $params[$no]['BRANCH']                       = $item['branch'];
            $params[$no]['POSITION']                     = $item['position'];
            $params[$no]['EFFECTIVE DATE']               = date('F j, Y', strtotime($item['effective_date']));
            $params[$no]['JOB DESCRIPTION']              = strip_tags(html_entity_decode($item['job_desc']));
            $params[$no]['PREV BRANCH']                  = $item['prev_branch'];
            $params[$no]['PREV POSITION']                = $item['prev_position'];
            $params[$no]['PREV EFFECTIVE DATE']          = $last;
            $params[$no]['PREV JOB DESCRIPTION']         = strip_tags(html_entity_decode($item['prev_job_desc']));
        }
        
        return (new \App\Models\CareerExport($params, 'User Career Detail' ))->download('EM-HR.Report-User-Career'.date('d-m-Y') .'.xlsx');
    }

    public function downloadDetail(Request $request){
        $uid = $request->input('uid');
        $data = CareerHistory::orderBy('effective_date', 'DESC')
                ->orderBy('id', 'DESC')
                ->leftJoin('cabang as c','career_history.cabang_id','=','c.id')
                ->leftJoin('structure_organization_custom','career_history.structure_organization_custom_id','=','structure_organization_custom.id')
                ->leftJoin('organisasi_position as op', 'structure_organization_custom.organisasi_position_id', '=', 'op.id')
                ->leftJoin('organisasi_division as od', 'structure_organization_custom.organisasi_division_id', '=', 'od.id')
                ->leftJoin('organisasi_title as ot', 'structure_organization_custom.organisasi_title_id', '=', 'ot.id')
                ->select(['career_history.id', 'c.name as branch', \DB::raw("CONCAT(COALESCE(op.name,''),IF(od.name IS NOT NULL, CONCAT(' - ',COALESCE(od.name,'')), ''),IF(ot.name IS NOT NULL, CONCAT(' - ',COALESCE(ot.name,'')), '')) as position"), 'effective_date', 'job_desc'])
                ->where('career_history.user_id', $uid)
                ->get()->toArray();

        for($i = 0; $i < count($data); $i++){
            if (isset($data[$i+1])) {
                $data[$i]['prev_branch']             = $data[$i+1]['branch'];
                $data[$i]['prev_position']           = $data[$i+1]['position'];
                $data[$i]['prev_effective_date']     = $data[$i+1]['effective_date'];
                $data[$i]['prev_job_desc']           = $data[$i+1]['job_desc'];
            } else {
                $data[$i]['prev_branch']             = '-';
                $data[$i]['prev_position']           = '-';
                $data[$i]['prev_effective_date']     = '-';
                $data[$i]['prev_job_desc']           = '-';
            }
        }
        
        $params = [];
        foreach($data as $no =>  $item)
        {
            $last = '';
            if($item['prev_effective_date'] == '-'){
                $last = '-';
            }
            else{
                $last = date('F j, Y', strtotime($item['prev_effective_date']));
            }
            $params[$no]['NO']                           = $no+1;
            $params[$no]['BRANCH']                       = $item['branch'];
            $params[$no]['POSITION']                     = $item['position'];
            $params[$no]['EFFECTIVE DATE']               = date('F j, Y', strtotime($item['effective_date']));
            $params[$no]['JOB DESCRIPTION']              = strip_tags(html_entity_decode($item['job_desc']));
            $params[$no]['PREV BRANCH']                  = $item['prev_branch'];
            $params[$no]['PREV POSITION']                = $item['prev_position'];
            $params[$no]['PREV EFFECTIVE DATE']          = $last;
            $params[$no]['PREV JOB DESCRIPTION']         = strip_tags(html_entity_decode($item['prev_job_desc']));
        }
        
        return (new \App\Models\CareerDetailExport($params, 'Career History Detail' ))->download('EM-HR.Report-Career-Detail'.date('d-m-Y') .'.xlsx');
    }

    public function addHistory(Request $r){
        // return response($r->all());
        $history                                    = new CareerHistory();
        $history->user_id                           = $r->user_id;
        $history->cabang_id                         = $r->branch;
        $history->structure_organization_custom_id  = $r->position;
        $history->effective_date                    = $r->eff_date;
        $history->job_desc                          = htmlspecialchars($r->jobd);
        $history->status                            = $r->status;
        $history->sub_grade_id                      = null;
        if($r->subgrade != '0' || $r->subgrade != ''){
            $history->sub_grade_id                  = $r->subgrade;
        }
        if($r->start_date != 'NaN-NaN-NaN'){
            $history->start_date                    = $r->start_date;
        }
        if($r->end_date != 'NaN-NaN-NaN'){
            $history->end_date                    = $r->end_date;
        }
        $history->save();

        synchronize_career($r->user_id);
        // info(synchronize_career($r->user_id));
        
        return response()->json(['status' => 'success', 'message' => 'Added successfully. Please refresh this page.']);
    }

    public function tableDetail(Request $request){
        $user = User::find($request->input('uid'));
        $employees = CareerHistory::orderBy('effective_date', 'DESC')
            ->orderBy('id', 'DESC')
            ->leftJoin('cabang as c','career_history.cabang_id','=','c.id')
            ->leftJoin('structure_organization_custom','career_history.structure_organization_custom_id','=','structure_organization_custom.id')
            ->leftJoin('organisasi_position as op', 'structure_organization_custom.organisasi_position_id', '=', 'op.id')
            ->leftJoin('organisasi_division as od', 'structure_organization_custom.organisasi_division_id', '=', 'od.id')
            ->leftJoin('organisasi_title as ot', 'structure_organization_custom.organisasi_title_id', '=', 'ot.id')
            ->select(['career_history.id', 'c.name as branch', \DB::raw("CONCAT(COALESCE(op.name,''),IF(od.name IS NOT NULL, CONCAT(' - ',COALESCE(od.name,'')), ''),IF(ot.name IS NOT NULL, CONCAT(' - ',COALESCE(ot.name,'')), '')) as position"), 'effective_date', 'job_desc', 'status', 'start_date', 'end_date']);
        $employees = $employees->where('career_history.user_id', $request->input('uid'));
        if ((!$user->non_active_date || $user->non_active_date > \Carbon\Carbon::now()) && !$user->is_exit) {
            return DataTables::of($employees)
                ->addColumn('action', function ($employee) {
                    return '<button onclick="editHistory('.$employee->id.')" class="btn btn-info btn-xs m-r-5"><i class="fa fa-edit"></i></button>
                    <button class="btn btn-danger btn-xs m-r-5" onclick="remove('.$employee->id.')"><i class="fa fa-trash"></i></button>';
                })
                ->make(true);
        } else {
            return DataTables::of($employees)
                ->addColumn('action', function ($employee) {
                    return '';
                })
                ->make(true);
        }
    }

    public function detailHistory($id){
        $user = Auth::user();

        $employees = CareerHistory::find($id);
        $employees->job_desc = htmlspecialchars_decode($employees->job_desc);

        if($employees){
            $str = StructureOrganizationCustom::where('id', $employees->structure_organization_custom_id)->first();
            if($str){
                return response()->json(['status' => 'success', 'message' => 'Data found', 'data' => $employees, 'job_desc_message' => 'found', 'job_desc' => htmlspecialchars_decode($str->description)]);
            }
        }

        return response()->json(['status' => 'success', 'message' => 'Data found', 'data' => $employees, 'job_desc_message' => 'not found', 'job_desc' => '']);
    }

    public function updateHistory(Request $r){
        $data = CareerHistory::where('id', $r->id_edit)->first();
        $data->cabang_id                            = $r->branch_edit;
        $data->structure_organization_custom_id     = $r->position_edit;
        $data->effective_date                       = $r->eff_date_edit;
        $data->job_desc                             = htmlspecialchars($r->job);
        $data->status                               = $r->status_edit;
        $data->sub_grade_id                         = null;
        if($r->subgradeedit != '0' || $r->subgradeedit != ''){
            $data->sub_grade_id                  = $r->subgradeedit;
        }
        if($r->status_edit && $r->status_edit != 'Permanent'){
            $data->start_date                       = $r->start_date_edit;
            $data->end_date                         = $r->end_date_edit;
        }
        else{
            $data->start_date                       = null;
            $data->end_date                         = null;
        }
        $data->save();

        synchronize_career($data->user_id);

        return response()->json(['status' => 'success', 'message' => 'Data updated successfully']);
    }

    public function destroyHistory($id){
        $career = CareerHistory::find($id);
        if($career){
            $user_id = $career->user_id;
            $career->delete();
            synchronize_career($user_id);
            return response()->json(['status' => 'success', 'message' => 'Data deleted']);
        }
        else{
            return response()->json(['status' => 'error', 'message' => 'Cannot delete data'],404);
        }
    }

    public function importData(Request $r){
        if($r->hasFile('file')){
            $path = $r->file('file')->getRealPath();
            // $data = Excel::load($path, function($reader) {})->get();

            // if(!empty($data) && $data->count()){
            //     foreach($data as $key => $val){
            //         $newCareer = CareerHistory::create([
            //             'user_id'                       => $id,
            //             'cabang_id'                     => $val->branch,
            //             'structure_organization_id'     => $val->position,
            //             'status'                        => $val->status,
            //             'start_date'                    => $val->start_date,
            //             'end_date'                      => $val->end_date,
            //             'job_desc'                      => $val->job_description
            //         ]);
            //     }
            // }
            $import = new CareerHistoryImport();
            $data = Excel::import($import, $r->file('file'));

            $failed = '';
            if(count($import->failed) > 0){
                $failed = ' Failed : '.implode( ", ", $import->failed);
            }

            return redirect()->back()->with('message-success', $import->succesfull.' Data imported successfully.'.$failed);
        }
        else{
            return redirect()->back()->with('message-error', 'Failed to import data');
        }
    }
}
