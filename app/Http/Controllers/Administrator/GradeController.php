<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\SubGrade;
use App\Models\StructureOrganizationCustom;
use App\Models\CareerHistory;
use DB;

class GradeController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = \Auth::user();
        if($user->project_id != NULL)
        {
            // $params['data'] = Grade::join('users', 'users.id', '=', 'grade.user_created')->where('users.project_id', $user->project_id)->leftJoin('sub_grade', 'grade.id', '=', 'sub_grade.grade_id')->select('grade.id', 'sub_grade.grade_id', 'grade.name as grade', 'grade.salary_range as grade_salary_range', 'sub_grade.name as sub_grade', 'sub_grade.salary_range as sub_grade_salary_range')->get();
            $params['data'] = Grade::all();
            // $params['dataSub'] = SubGrade::orderBy('sub_grade.id', 'DESC')->join('users','users.id','=','sub_grade.user_created')->where('users.project_id', $user->project_id)->select('sub_grade.*')->get();
        }else{
            // $params['data'] = Grade::join('users', 'users.id', '=', 'grade.user_created')->where('users.project_id', $user->project_id)->leftJoin('sub_grade', 'grade.id', '=', 'sub_grade.grade_id')->select('grade.id', 'sub_grade.grade_id', 'grade.name as grade', 'grade.salary_range as grade_salary_range', 'sub_grade.name as sub_grade', 'sub_grade.salary_range as sub_grade_salary_range')->get();
            $params['data'] = Grade::all();
            // $params['dataSub'] = SubGrade::all();
        }
        // dd($params);
        return view('administrator.grade.index')->with($params);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('administrator.grade.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = \Auth::user();
        if($request->salaryLow){
            $check = Grade::where('name', $request->gradeName)->first();
            if($check){
                return redirect()->route('administrator.grade.create')->with('message-error', 'Grade Name is already existed!');
            }

            if($request->gradeName == '' || $request->gradeName == null || $request->gradeName == ' '){
                return redirect()->route('administrator.grade.create')->with('message-error', 'Grade Name must be filled!');
            }
            $data = new Grade();
            $data->name = $request->gradeName;
            $data->salary_range = $request->salaryLow.' - '.$request->salaryHigh;
            if($user->project_id != NULL){
                $data->user_created = $user->id;
            }
            $data->benefit = htmlspecialchars($request->benefit);
            $data->save();

            return redirect()->route('administrator.grade.index')->with('message-success', 'Data successfully saved!');
        }
        else{
            $check = Grade::where('name', $request->gradeName)->first();
            if($check){
                return redirect()->route('administrator.grade.create')->with('message-error', 'Grade Name is already existed!');
            }

            if($request->gradeName == '' || $request->gradeName == null || $request->gradeName == ' '){
                return redirect()->route('administrator.grade.create')->with('message-error', 'Grade Name must be filled!');
            }
            $array_sub_low = [];
            $array_sub_high = [];
            $array_sub_name = [];
            for($i = 0; $i < count($request->subSalaryLow); $i++){
                array_push($array_sub_low, $request->subSalaryLow[$i]);
                array_push($array_sub_high, $request->subSalaryHigh[$i]);
                array_push($array_sub_name, $request->subGradeName[$i]);
            }

            for($y = 0; $y < count($array_sub_name); $y++){
                if($array_sub_name[$y] == '' || $array_sub_name == null || $array_sub_name == ' '){
                    return redirect()->route('administrator.grade.create')->with('message-error', 'Sub Grade Name must be filled!');
                }
            }

            if(count($array_sub_name) !=  count(array_unique($array_sub_name))){
                return redirect()->route('administrator.grade.create')->with('message-error', 'Duplicate Sub Grade Name!');
            }

            $data = new Grade();
            $data->name = $request->gradeName;
            $data->salary_range = $array_sub_low[0].' - '.$array_sub_high[count($array_sub_high)-1];
            if($user->project_id != NULL){
                $data->user_created = $user->id;
            }
            $data->benefit = htmlspecialchars($request->benefit);
            $data->save();

            for($j = 0; $j < count($request->subSalaryLow); $j++){
                $dataSub = new SubGrade();
                $dataSub->grade_id = $data->id;
                $dataSub->name = $array_sub_name[$j];
                $dataSub->salary_range = $array_sub_low[$j].' - '.$array_sub_high[$j];
                if($user->project_id != NULL){
                    $dataSub->user_created = $user->id;
                }
                $dataSub->save();
            }

            return redirect()->route('administrator.grade.index')->with('message-success', 'Data successfully saved!');
        }
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
        $params['data']         = Grade::where('id', $id)->first();
        $params['benefit']      = htmlspecialchars_decode($params['data']->benefit);
        $sal                    = explode(' - ', $params['data']->salary_range);
        $params['low']          = $sal[0];
        $params['high']         = $sal[1];
        $subGrade               = SubGrade::where('grade_id', $params['data']->id)->get();
        $params['is_sub']       = 0;
        if(count($subGrade) > 0){
            $params['is_sub']   = 1;
            $first = $subGrade[0];
            $params['first']    = $first;
            $after = $subGrade->toArray();
            reset($after);
            unset($after[0]);
            if(count($after) > 0){
                $params['after']    = $after;
            }
        }
        // dd($params);
        return view('administrator.grade.edit')->with($params);
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
        $user = \Auth::user();
        if($request->salaryLow){
            $curGrade = Grade::where('id', $id)->first();
            $check = Grade::where('name', $request->gradeName)->whereNotIn('id', [$curGrade->id])->first();
            if($check){
                return redirect()->route('administrator.grade.create')->with('message-error', 'Grade Name is already existed!');
            }
            $curGrade->name = $request->gradeName;
            $curGrade->salary_range = $request->salaryLow.' - '.$request->salaryHigh;
            if($user->project_id != NULL){
                $curGrade->user_created = $user->id;
            }
            $curGrade->benefit = htmlspecialchars($request->benefit);

            $curSub = SubGrade::where('grade_id', $curGrade->id)->get();
            if(count($curSub) > 0){
                for($i = 0; $i < count($curSub); $i++){
                    $delSub = SubGrade::where('id', $curSub[$i]->id)->first();
                    $delSub->delete();
                }
            }
            $curGrade->save();

            return redirect()->route('administrator.grade.index')->with('message-success', 'Data successfully saved!');
        }
        else{
            $array_sub_low = [];
            $array_sub_high = [];
            $array_sub_name = [];
            for($i = 0; $i < count($request->subSalaryLow); $i++){
                array_push($array_sub_low, $request->subSalaryLow[$i]);
                array_push($array_sub_high, $request->subSalaryHigh[$i]);
                array_push($array_sub_name, $request->subGradeName[$i]);
            }

            if(count($array_sub_name) !=  count(array_unique($array_sub_name))){
                return redirect()->route('administrator.grade.create')->with('message-error', 'Duplicate Sub Grade Name!');
            }

            $data = Grade::where('id', $id)->first();
            $data->name = $request->gradeName;
            $data->salary_range = $array_sub_low[0].' - '.$array_sub_high[count($array_sub_high)-1];
            if($user->project_id != NULL){
                $data->user_created = $user->id;
            }
            $data->benefit = htmlspecialchars($request->benefit);
            $data->save();

            $curSub = SubGrade::where('grade_id', $data->id)->get();
            if(count($curSub) > 0){
                for($i = 0; $i < count($curSub); $i++){
                    $delSub = SubGrade::where('id', $curSub[$i]->id)->first();
                    $delSub->delete();
                }
            }

            for($j = 0; $j < count($request->subSalaryLow); $j++){
                $dataSub = new SubGrade();
                $dataSub->grade_id = $data->id;
                $dataSub->name = $array_sub_name[$j];
                $dataSub->salary_range = $array_sub_low[$j].' - '.$array_sub_high[$j];
                if($user->project_id != NULL){
                    $dataSub->user_created = $user->id;
                }
                $dataSub->save();
            }

            return redirect()->route('administrator.grade.index')->with('message-success', 'Data successfully saved!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Grade::where('id', $id)->first();
        $dataSub = SubGrade::where('grade_id', $data->id)->get();
        if(count($dataSub) > 0){
            $idSub = [];
            for($i = 0; $i < count($dataSub); $i++){
                array_push($idSub, $dataSub[$i]->id);
                $delSub = SubGrade::where('id', $dataSub[$i]->id)->first();
                $delSub->delete();
            }
            $dataCar = CareerHistory::whereIn('sub_grade_id', $idSub)->get();
            for($y = 0; $y < count($dataCar); $y++){
                $delCar = CareerHistory::where('id', $dataCar[$y]->id)->first();
                $delCar->delete();
            }
        }
        $dataStr = StructureOrganizationCustom::where('grade_id', $data->id)->get();
        if(count($dataStr) > 0){
            for($j = 0; $j < count($dataStr); $j++){
                $delStr = StructureOrganizationCustom::where('id', $dataStr[$j]->id)->first();
                $delStr->grade_id = null;
                $delStr->save();
            }
        }
        $data->delete();
//        synchronize_all_career();
        return redirect()->route('administrator.grade.index')->with('message-success', 'Data successfully deleted');
    }

    public function checkName(Request $r){
        if($r->type == 'store'){
            $checkExistName = Grade::where('name', $r->name)->first();
            if(isset($checkExistName)){
                $status = 'false';
            }
            else{
                $status = 'true';
            }
        }
        else{
            $data           = Grade::where('id', $r->id)->first();
            $checkExistName = Grade::where('name', $r->name)->whereNotIn('id', [$data->id])->first();
            if(isset($checkExistName)){
                $status = 'false';
            }
            else{
                $status = 'true';
            }
        }

        return response($status);
    }

    public function checkSubGrade(Request $r){
        $str = StructureOrganizationCustom::where('id', $r->id)->first();
        if($str && $str->grade_id != NULL){
            $gr = Grade::where('id', $str->grade_id)->first();
            if($gr){
                $sgr = SubGrade::where('grade_id', $gr->id)->get();
                if(count($sgr) > 0){
                    $res['message'] = 'sub grade found';
                    $res['grade_name'] = $gr->name;
                    $res['data']    = $sgr;
                    $res['job_desc'] = htmlspecialchars_decode($str->description);
                }
                else{
                    $res['message'] = 'only grade found';
                    $res['grade_name'] = $gr->name;
                    $res['job_desc'] = htmlspecialchars_decode($str->description);
                }
            }
            else{
                $res['message'] = 'data not found';
                $res['job_desc'] = htmlspecialchars_decode($str->description);
            }
        }
        else if($str){
            $res['message'] = 'data not found';
            $res['job_desc'] = htmlspecialchars_decode($str->description);
        }
        else{
            $res['message'] = 'data not found';
            $res['job_desc'] = '';
        }

        return response($res);
    }
}
