<?php

namespace App\Http\Controllers\Karyawan;

use App\Models\InternalApplication;
use App\Models\RecruitmentPhase;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use DataTables;

class RecruitmentApplicationController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('karyawan.recruitment-application.index');
    }

    public function table(){
        $user = Auth::user();
        $applications = InternalApplication::join('recruitment_applications as ra','internal_applications.recruitment_application_id','=','ra.id')
                                            ->join('recruitment_request as rr','ra.recruitment_request_id','=','rr.id')
                                            ->join('recruitment_phases as rp','ra.current_phase_id','=','rp.id')
                                            ->join('recruitment_application_status as rs','ra.application_status','=','rs.id')
                                            ->join('cabang as c','rr.branch_id','=','c.id')
                                            ->where(['internal_applications.user_id'=>$user->id])
                                            ->select(['internal_applications.id','c.name as branch','ra.application_status as status','rs.status as status_name','rp.name as current_phase',\DB::raw("DATE_FORMAT(ra.created_at, '%d %M %Y') as application_date"),'rr.job_position as job_position'])
                                            ->orderBy('internal_applications.created_at', 'desc');

        return DataTables::of($applications)
            ->make(true);
    }


    public function getApplicationDetail($id){
        return getInternalApplicationDetail($id);
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
