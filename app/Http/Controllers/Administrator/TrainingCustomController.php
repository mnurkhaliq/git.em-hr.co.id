<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Training;
use App\User;
use App\Models\OrganisasiDivision;
use App\Models\OrganisasiPosition;

class TrainingCustomController extends Controller
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
        $this->middleware('module:8');
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
            $data = Training::orderBy('id', 'DESC')->select('training.*')->join('users', 'users.id', '=', 'training.user_id')->where('users.project_id', $user->project_id);
            $params['division'] = OrganisasiDivision::where('project_id', $user->project_id)->select('organisasi_division.*')->get();
            $params['position'] = OrganisasiPosition::where('project_id', $user->project_id)->select('organisasi_position.*')->get();
        } else
        {
            $data = Training::orderBy('id', 'DESC')->select('training.*')->join('users', 'users.id', '=', 'training.user_id');
            $params['division'] = OrganisasiDivision::all();
            $params['position'] = OrganisasiPosition::all();
        }

        if(count(request()->all())) {
            \Session::put('bt-employee_status', request()->employee_status);
            \Session::put('bt-position_id', request()->position_id);
            \Session::put('bt-division_id', request()->division_id);
            \Session::put('bt-name', request()->name);
            \Session::put('bt-bt_approval', request()->bt_approval);
            \Session::put('bt-bt_claim', request()->bt_claim);
            \Session::put('bt-employee_resign', request()->employee_resign);
        }

        $employee_status    = \Session::get('bt-employee_status');
        $position_id        = \Session::get('bt-position_id');
        $division_id        = \Session::get('bt-division_id');
        $name               = \Session::get('bt-name');
        $bt_approval        = \Session::get('bt-bt_approval');
        $bt_claim           = \Session::get('bt-bt_claim');

        if($user->project_id != NULL)
        {
            if (!empty($name)) {
                $data = $data->where(function ($table) use($name) {
                    $table->where('users.name', 'LIKE', '%' . $name . '%')
                        ->orWhere('number', 'LIKE', '%' . $name . '%')
                        ->orWhere('users.nik', 'LIKE', '%' . $name . '%');
                });
            }
            
            if(!empty($employee_status))
            {
                $data = $data->where('users.organisasi_status', $employee_status);
            }
            if(!empty($bt_approval))
            {
                $data = $data->where('training.status', $bt_approval);
            }
            if(!empty($bt_claim))
            {
                $data = $data->where('status_actual_bill', $bt_claim);
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
                $data = $data->join('structure_organization_custom','users.structure_organization_custom_id','=','structure_organization_custom.id')->where('structure_organization_custom.organisasi_position_id',request()->position_id)->where('structure_organization_custom.organisasi_division_id',request()->division_id);
            }
            if(request()->action == 'download')
            {
                return $this->downloadExcel($data->get());
            }
        }

        if(request()->reset == 1)
        {
            \Session::forget('bt-employee_status');
            \Session::forget('bt-position_id');
            \Session::forget('bt-division_id');
            \Session::forget('bt-name');
            \Session::forget('bt-bt_approval');
            \Session::forget('bt-bt_claim');

            return redirect()->route('administrator.trainingcustom.index');
        }

        $params['data'] = $data->get();
        return view('administrator.trainingcustom.index')->with($params);
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
        $params['data'] = Training::where('id', $id)->first();
        return view('administrator.trainingcustom.detail')->with($params);
    }

    public function claim($id)
    {   
        $params['data'] = Training::where('id', $id)->first();
        return view('administrator.trainingcustom.biaya')->with($params);
    }

    /**
     * [downloadExlce description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function downloadExcel($data)
    {
        $params = [];

        $total_acomodation_header = [];
        $total_meal_header = [];
        $total_daily_header = [];
        $total_other_header = [];

        foreach($data as $no =>  $item) {
            $total1 = 0;
            $total2 = 0;
            $total3 = 0;
            $total4 = 0;

            foreach($item->training_acomodation as $type1 => $form1)
            {
                $total1++;
            }
            $total_acomodation_header[] = $total1;
            
            foreach($item->training_allowance as $type2 => $form2)
            {
                $total2++;
            }
            $total_meal_header[] = $total2;

            foreach($item->training_daily as $type3 => $form3)
            {
                $total3++;
            }
            $total_daily_header[] = $total3;

            foreach($item->training_other as $type4 => $form4)
            {
                $total4++;
            }
            $total_other_header[] = $total4;
            
        }
        //dd(max($total_acomodation_header));


        foreach($data as $no =>  $item)
        {
            $params[$no]['NO']               = $no+1;
            $params[$no]['BT NUMBER']      = isset($item->number)? $item->number:'';
            $params[$no]['NIK']                     = $item->user->nik;
            $params[$no]['NAME']                    = $item->user->name;
            $params[$no]['POSITION']                = (isset($item->structure->position) ? $item->structure->position->name:'').(isset($item->structure->division) ? ' - '.$item->structure->division->name:'').(isset($item->structure->title) ? ' - '.$item->structure->title->name:'');
            $params[$no]['ACTIVITY START DATE']   = date('d F Y', strtotime($item->tanggal_kegiatan_start));
            $params[$no]['ACTIVITY END DATE']     = date('d F Y', strtotime($item->tanggal_kegiatan_end));
            $params[$no]['ACTIVITY TYPE']      = isset($item->training_type)? $item->training_type->name:'';
            $params[$no]['ACTIVITY TOPIC']      = $item->topik_kegiatan;
            $params[$no]['SUBMISSION']     = date('d F Y', strtotime($item->created_at));
            $params[$no]['CLAIM']        = $item->date_submit_actual_bill;

            // SET HEADER ACOMODATION
            $header_acomodation = max($total_acomodation_header);
            for($a=0; $a < $header_acomodation  ; $a++)
            {
                $params[$no]['Acommodation & Transportation Date '. ($a+1)]           = '-';
                $params[$no]['Acommodation & Transportation Description '. ($a+1)]    = '-';
                $params[$no]['Acommodation & Transportation Claimed '. ($a+1)]        = '-';
                $params[$no]['Acommodation & Transportation Approved '. ($a+1)]        = '-';
                $params[$no]['Acommodation & Transportation Note '. ($a+1)]        = '-';
            }
            foreach ($item->training_acomodation as $key => $value) {
                $params[$no]['Acommodation & Transportation Date '. ($key+1)]           = $value->date;
                $params[$no]['Acommodation & Transportation Description '. ($key+1)]    = isset($value->transportation_type)? $value->transportation_type->name:'';
                $params[$no]['Acommodation & Transportation Claimed '. ($key+1)]        = $value->nominal;
                $params[$no]['Acommodation & Transportation Approved '. ($key+1)]        =  $value->nominal_approved;
                $params[$no]['Acommodation & Transportation Note '. ($key+1)]        = $value->note;
            }
            // SET HEADER MEAL ALLOWANCE
            $header_meal = max($total_meal_header);
            for($a=0; $a < $header_meal  ; $a++)
            {
                $params[$no]['Meal Allowance Date '.($a+1)]    = '-';
                $params[$no]['Plafond Meal Allowance '.($a+1)] = '-';
                $params[$no]['Morning Claimed '.($a+1)]     = '-';
                $params[$no]['Morning Approved '.($a+1)]    = '-';
                $params[$no]['Afternoon Claimed '.($a+1)]     = '-';
                $params[$no]['Afternoon Approved '.($a+1)]    = '-';
                $params[$no]['Evening Claimed '.($a+1)]     = '-';
                $params[$no]['Evening Approved '.($a+1)]    = '-';
                $params[$no]['Meal Allowance Note '.($a+1)]    = '-';
            }
            foreach ($item->training_allowance  as $key => $value) {
                $params[$no]['Meal Allowance Date '.($key+1)]    = $value->date;
                $params[$no]['Plafond Meal Allowance '.($key+1)] = $value->meal_plafond;
                $params[$no]['Morning Claimed '.($key+1)]     = $value->morning;
                $params[$no]['Morning Approved '.($key+1)]    = $value->morning_approved;
                $params[$no]['Afternoon Claimed '.($key+1)]     = $value->afternoon;
                $params[$no]['Afternoon Approved '.($key+1)]    = $value->afternoon_approved;
                $params[$no]['Evening Claimed '.($key+1)]     = $value->evening;
                $params[$no]['Evening Approved '.($key+1)]    = $value->evening_approved;
                $params[$no]['Meal Allowance Note '.($key+1)]    = $value->note;
            }
             // SET HEADER DAILY ALLOWANCE
            $header_daily = max($total_daily_header);
            for($a=0; $a < $header_daily  ; $a++)
            {
                $params[$no]['Daily Allowance Date '.($a+1)]       = '-';
                $params[$no]['Plafond Daily Allowance '.($a+1)]    = '-';
                $params[$no]['Daily Claimed '.($a+1)]           = '-';
                $params[$no]['Daily Approved '.($a+1)]          = '-';
                $params[$no]['Daily Allowance Note '.($a+1)]       = '-';
            }
            foreach ($item->training_daily   as $key => $value) {
                $params[$no]['Daily Allowance Date '.($key+1)]    = $value->date;
                $params[$no]['Plafond Daily Allowance '.($key+1)] = $value->daily_plafond;
                $params[$no]['Daily Claimed '.($key+1)]     = $value->daily;
                $params[$no]['Daily Approved '.($key+1)]    = $value->daily_approved;
                $params[$no]['Daily Allowance Note '.($key+1)]    = $value->note;
            }
            // SET HEADER OTHER 
            $header_other = max($total_other_header);
            for($a=0; $a < $header_other  ; $a++)
            {
               $params[$no]['Other Date '.($a+1)]        = '-';
                $params[$no]['Other Description '.($a+1)] = '-';
                $params[$no]['Other Claimed '.($a+1)]     = '-';
                $params[$no]['Other Approved '.($a+1)]    = '-';
                $params[$no]['Other Note '.($a+1)]        = '-';

            }
            foreach ($item->training_other    as $key => $value) {
                $params[$no]['Other Date '.($key+1)]    = $value->date;
                $params[$no]['Other Description '.($key+1)] = $value->daily_plafond;
                $params[$no]['Other Claimed '.($key+1)]     = $value->daily;
                $params[$no]['Other Approved '.($key+1)]    = $value->daily_approved;
                $params[$no]['Other Note '.($key+1)]    = $value->note;
            }

            // SET HEADER LEVEL APPROVAL
            $level_header = get_training_header();
            for($a=0; $a < $level_header  ; $a++)
            {
                $params[$no]['BT APPROVAL STATUS '. ($a+1)]           = '-';
                $params[$no]['BT APPROVAL NAME '. ($a+1)]           = '-';
                $params[$no]['BT APPROVAL DATE '. ($a+1)]           = '-';

            }

            foreach ($item->historyApproval as $key => $value) {
                //$params[$no]['Approval '. ($key+1)]           = $value->id;

                if($value->is_approved == 1)
                {
                    $params[$no]['BT APPROVAL STATUS '. ($key+1)]           = 'Approved';
                }elseif($value->is_approved == 0)
                {
                    $params[$no]['BT APPROVAL STATUS '. ($key+1)]           = 'Rejected';
                }elseif($value->is_approved == NULL || empty($value->is_approved) || $value->is_approved =="")
                {
                    $params[$no]['BT APPROVAL STATUS '. ($key+1)]           = '-';
                }

                $params[$no]['BT APPROVAL NAME '. ($key+1)]           = isset($value->userApproved) ? $value->userApproved->name:'';

                $params[$no]['BT APPROVAL DATE '. ($key+1)]           = $value->date_approved != NULL ? date('d F Y', strtotime($value->date_approved)) : ''; 
            }

            for($a=0; $a < $level_header  ; $a++)
            {
                $params[$no]['BT CLAIM STATUS '. ($a+1)]           = '-';
                $params[$no]['BT CLAIM NAME '. ($a+1)]             = '-';
                $params[$no]['BT CLAIM DATE '. ($a+1)]             = '-';
            }

            foreach ($item->historyApproval as $key => $value) {
                //$params[$no]['Approval '. ($key+1)]           = $value->id;

                if($value->is_approved_claim == 1)
                {
                    $params[$no]['BT CLAIM STATUS '. ($key+1)]           = 'Approved';
                }elseif($value->is_approved_claim == 0)
                {
                    $params[$no]['BT CLAIM STATUS '. ($key+1)]           = 'Rejected';
                }elseif($value->is_approved_claim == NULL || empty($value->is_approved_claim) || $value->is_approved_claim =="")
                {
                    $params[$no]['BT CLAIM STATUS '. ($key+1)]           = '-';
                }

                $params[$no]['BT CLAIM NAME '. ($key+1)]           = isset($value->userApprovedClaim) ? $value->userApprovedClaim->name:'';

                $params[$no]['BT CLAIM DATE '. ($key+1)]           = $value->date_approved_claim != NULL ? date('d F Y', strtotime($value->date_approved_claim)) : ''; 
            }
        }

        return (new \App\Models\KaryawanExport($params, 'Report Business Trip ' ))->download('EM-HR.Report-Training-'.date('d-m-Y') .'.xlsx');
    }
    
}
