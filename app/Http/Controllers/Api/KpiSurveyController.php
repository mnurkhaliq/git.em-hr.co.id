<?php

namespace App\Http\Controllers\Api;

use App\Http\Middleware\ConnectionMiddleware;
use App\Models\KpiEmployee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class KpiSurveyController extends Controller
{
    public function __construct()
    {
        $this->middleware(ConnectionMiddleware::class);
        $this->middleware('jwt.auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $user = Auth::user();
        $data['surveys'] = KpiEmployee::join('users as u','kpi_employee.user_id','=','u.id')
            ->join('kpi_periods as kp','kpi_employee.kpi_period_id','=','kp.id')
            ->leftJoin('structure_organization_custom as so','kpi_employee.structure_organization_custom_id','=','so.id')
            ->leftJoin('organisasi_position as op','so.organisasi_position_id','=','op.id')
            ->leftJoin('organisasi_division as od','so.organisasi_division_id','=','od.id')
            ->leftJoin('organisasi_title as ot','so.organisasi_title_id','=','ot.id')
            ->where(['so.parent_id'=>$user->structure_organization_custom_id,'kp.status'=>1])
            ->select(['kpi_employee.id',\DB::raw("CONCAT(DATE_FORMAT(kp.start_date, '%d %M %Y'), ' - ',DATE_FORMAT(kp.end_date, '%d %M %Y')) AS period"),'u.nik','u.name',\DB::raw("CONCAT(COALESCE(op.name,''),IF(od.name IS NOT NULL, CONCAT(' - ',COALESCE(od.name,'')), ''),IF(ot.name IS NOT NULL, CONCAT(' - ',COALESCE(ot.name,'')), '')) as position"),'kpi_employee.status','final_score'])
            ->get();
        return response()->json(
            [
                'status' => 'success',
                'message' => 'Successfully',
                'data' => $data
            ], 200);
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

    public function getDetail($id)
    {
        $user = Auth::user();
        $data['details'] = getKpiDetail(null,$user,$id);
        if($data['details']){
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Successfully',
                    'data' => $data
                ], 200);
        }
        else{
            return response()->json(
                [
                    'status' => 'failed',
                    'message' => 'Data is not found or its KPI Items are not set yet',
                    'data' => null
                ], 200);
        }
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
