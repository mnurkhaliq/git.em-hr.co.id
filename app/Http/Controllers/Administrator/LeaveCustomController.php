<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\UserCuti;
use App\Models\Cuti;
use App\Models\CutiKaryawan;
use App\Models\StatusApproval;
use App\Models\StructureOrganizationCustom; 
use App\Models\OrganisasiDivision;
use App\Models\OrganisasiPosition;
class LeaveCustomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
        $this->middleware('module:4');
    }

    public function index()
    {
        //
        $user = \Auth::user();
        if($user->project_id != NULL)
        {
            $data = CutiKaryawan::select('cuti_karyawan.*')->join('users', 'users.id','=','cuti_karyawan.user_id')->where('users.project_id', $user->project_id)->orderBy('id', 'DESC');
            $params['division'] = OrganisasiDivision::where('project_id', $user->project_id)->select('organisasi_division.*')->get();
            $params['position'] = OrganisasiPosition::where('project_id', $user->project_id)->select('organisasi_position.*')->get();
        } else
        {
            $data = CutiKaryawan::select('cuti_karyawan.*')->join('users', 'users.id','=','cuti_karyawan.user_id')->orderBy('id', 'DESC');
            $params['division'] = OrganisasiDivision::all();
            $params['position'] = OrganisasiPosition::all();
        }
        $params['structure'] = getStructureName();
        
        if(count(request()->all())) {
            \Session::put('l-employee_status', request()->employee_status);
            \Session::put('l-position_id', request()->position_id);
            \Session::put('l-division_id', request()->division_id);
            \Session::put('l-name', request()->name);
        }

        $employee_status    = \Session::get('l-employee_status');
        $position_id        = \Session::get('l-position_id');
        $division_id        = \Session::get('l-division_id');
        $name               = \Session::get('l-name');
        
        if(request())
        {
            if (!empty($name)) {
                $data = $data->where(function ($table) use($name) {
                    $table->where('users.name', 'LIKE', '%' . $name . '%')
                        ->orWhere('users.nik', 'LIKE', '%' . $name . '%');
                });
            }

            if(!empty($employee_status))
            {
                $data = $data->where('users.organisasi_status', $employee_status);
            }

            if((!empty($division_id)) and (empty($position_id))) 
            {   
                $data = $data->join('structure_organization_custom','users.structure_organization_custom_id','=','structure_organization_custom.id')->where('structure_organization_custom.organisasi_division_id',$division_id);
            }
            if((!empty($position_id)) and (empty($division_id)))
            {   
                $data = $data->join('structure_organization_custom','users.structure_organization_custom_id','=','structure_organization_custom.id')->where('structure_organization_custom.organisasi_position_id',$position_id);
            }
            if((!empty($position_id)) and (!empty($division_id)))
            {
                $data = $data->join('structure_organization_custom','users.structure_organization_custom_id','=','structure_organization_custom.id')->where('structure_organization_custom.organisasi_position_id',$position_id)->where('structure_organization_custom.organisasi_division_id',$division_id);
            }
            if(request()->action == 'download')
            {
                return $this->downloadExcel($data->get());
            }
        }

        if(request()->reset == 1)
        {
            \Session::forget('l-employee_status');
            \Session::forget('l-position_id');
            \Session::forget('l-division_id');
            \Session::forget('l-name');

            return redirect()->route('administrator.leaveCustom.index');
        }

        $params['data'] = $data->get();
        return view('administrator.leavecustom.index')->with($params);
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
    public function proses($id)
    {   
        $params['data'] = CutiKaryawan::where('id', $id)->first();

        return view('administrator.leavecustom.proses')->with($params);
    }
    public function downloadExcel($data)
    {
        $params = [];

        foreach($data as $no =>  $item)
        {
            $params[$no]['NO']                  = $no+1;
            $params[$no]['EMPLOYEE ID(NIK)']    = $item->user->nik;
            $params[$no]['EMPLOYEE NAME']    = $item->user->name;
            $params[$no]['POSITION']         = (isset($item->structure->position) ? $item->structure->position->name:'').(isset($item->structure->division) ? ' - '.$item->structure->division->name:'').(isset($item->structure->title) ? ' - '.$item->structure->title->name:'');
            $params[$no]['START DATE']      = date('d F Y', strtotime($item->tanggal_cuti_start));
            $params[$no]['END DATE']        =date('d F Y', strtotime($item->tanggal_cuti_end));
            $params[$no]['LEAVE TYPE']= isset($item->cuti->description) ? $item->cuti->description : '';
            $params[$no]['LEAVE DURATION'] = $item->total_cuti;
            $params[$no]['PURPOSE']        = $item->keperluan;
            $params[$no]['LEAVE BALANCE']   = $item->temp_sisa_cuti;
            $params[$no]['DATE OF SUBMITTED']       = date('d F Y', strtotime($item->created_at));

            // SET HEADER LEVEL APPROVAL
            $level_header = get_level_header();
            for($a=0; $a < $level_header  ; $a++)
            {
                $params[$no]['APPROVAL STATUS '. ($a+1)]           = '-';
                $params[$no]['APPROVAL NAME '. ($a+1)]           = '-';
                $params[$no]['APPROVAL DATE '. ($a+1)]           = '-';

            }

            foreach ($item->historyApproval as $key => $value) {
                //$params[$no]['Approval '. ($key+1)]           = $value->id;

                if($value->is_approved == 1)
                {
                    $params[$no]['APPROVAL STATUS '. ($key+1)]           = 'Approved';
                }elseif($value->is_approved == 0)
                {
                    $params[$no]['APPROVAL STATUS '. ($key+1)]           = 'Rejected';
                }else
                {
                    $params[$no]['APPROVAL STATUS '. ($key+1)]           = '-';
                }

                $params[$no]['APPROVAL NAME '. ($key+1)]           = isset($value->userApproved) ? $value->userApproved->name:'';

                $params[$no]['APPROVAL DATE '. ($key+1)]           = $value->date_approved != NULL ? date('d F Y', strtotime($value->date_approved)) : ''; 
            }
        }

        return (new \App\Models\KaryawanExport($params, 'Report Leave Permit Employee ' ))->download('EM-HR.Report-Leave-Permit-'.date('d-m-Y') .'.xlsx');
    }
}
