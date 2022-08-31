<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PaymentRequest;
use App\Models\PaymentRequestForm;
use App\User;
use App\Models\StatusApproval;
use App\Models\StructureOrganizationCustom;
use App\Models\OrganisasiDivision;
use App\Models\OrganisasiPosition;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class PaymentRequestCustomController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
        $this->middleware('module:6');
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
            $data = PaymentRequest::select('payment_request.*')->orderBy('id', 'DESC')->join('users', 'users.id', '=', 'payment_request.user_id')->where('payment_request.status', '!=', 4)->where('users.project_id', $user->project_id);
            $params['division'] = OrganisasiDivision::where('project_id', $user->project_id)->select('organisasi_division.*')->get();
            $params['position'] = OrganisasiPosition::where('project_id', $user->project_id)->select('organisasi_position.*')->get();
        } else
        {
            $data = PaymentRequest::select('payment_request.*')->orderBy('id', 'DESC')->join('users', 'users.id', '=', 'payment_request.user_id')->where('payment_request.status', '!=', 4);
            $params['division'] = OrganisasiDivision::all();
            $params['position'] = OrganisasiPosition::all();
        }
        $params['structure'] = getStructureName();

        if(count(request()->all())) {
            \Session::put('pr-employee_status', request()->employee_status);
            \Session::put('pr-position_id', request()->position_id);
            \Session::put('pr-division_id', request()->division_id);
            \Session::put('pr-name', request()->name);
        }

        $employee_status    = \Session::get('pr-employee_status');
        $position_id        = \Session::get('pr-position_id');
        $division_id        = \Session::get('pr-division_id');
        $name               = \Session::get('pr-name');

        if(request())
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
            \Session::forget('pr-employee_status');
            \Session::forget('pr-position_id');
            \Session::forget('pr-division_id');
            \Session::forget('pr-name');

            return redirect()->route('administrator.paymentrequestcustom.index');
        }

        $params['data'] = $data->get();

        return view('administrator.paymentrequestcustom.index')->with($params);
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

        $params['data'] = PaymentRequest::where('id', $id)->first();
        $params['karyawan']     = User::whereIn('access_id', [1,2])->get();
        $params['form']         = PaymentRequestForm::where('payment_request_id', $id)->get();

        return view('administrator.paymentrequestcustom.proses')->with($params);
    }

    /**
     * [downloadExlce description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function downloadExcel($data)
    {
        $params = [];

        $total_loop_header = [];
        foreach($data as $no =>  $item)
        {
            $total = 0;
            foreach($item->payment_request_form as $type => $form)
            {
                $total++;
            }
            $total_loop_header[] = $total;
        }

        foreach($data as $no =>  $item)
        {
            $params[$no]['NO']               = $no+1;
            $params[$no]['NIK']              = $item->user->nik;
            $params[$no]['NAME']    = $item->user->name;
            $params[$no]['POSITION']         = (isset($item->structure->position) ? $item->structure->position->name:'').(isset($item->structure->division) ? ' - '.$item->structure->division->name:'').(isset($item->structure->title) ? ' - '.$item->structure->title->name:'');
            $params[$no]['DATE REQUEST']    = date('d F Y', strtotime($item->created_at));
            $params[$no]['PURPOSE']           = $item->tujuan;
            $params[$no]['PAYMENT METHOD']  = $item->payment_method;


            $total=0;
            $total_amount = 0;
            $total_amount_approved = 0;
            foreach($item->payment_request_form as $type => $form)
            {
                $type = $type+1;
                $params[$no]['TYPE '.$type]             = $form->type_form;
                $params[$no]['DESCRIPTION '.$type]      = $form->description;
                $params[$no]['QUANTITY '.$type]         = $form->quantity;
                $params[$no]['AMOUNT '.$type]           = $form->amount;
                $params[$no]['AMOUNT APPROVED '.$type]  = $form->nominal_approved;
                $params[$no]['NOTE '.$type]  = $form->note;
                $total++;

                $total_amount +=$form->amount;
                $total_amount_approved +=$form->nominal_approved;
            }
            if($total ==0 ) $total++;
            for($v=$total; $v < max($total_loop_header); $v++)
            {
                $params[$no]['TYPE '. ($v+1)]             = "-";
                $params[$no]['DESCRIPTION '.($v+1)]      = "-";
                $params[$no]['QUANTITY '.($v+1)]         = "-";
                $params[$no]['AMOUNT '.($v+1)]           = "-";
                $params[$no]['AMOUNT APPROVED '.($v+1)]  = "-";
                $params[$no]['NOTE '.($v+1)]  = "-";
            }
            $params[$no]['TOTAL AMOUNT']  = $total_amount;
            $params[$no]['TOTAL AMOUNT APPROVED']  = $total_amount_approved;

            // SET HEADER LEVEL APPROVAL
            $level_header = get_payment_header();
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

        return (new \App\Models\KaryawanExport($params, 'Report Payment Request Employee ' ))->download('EM-HR.Report-Payment-Request-'.date('d-m-Y') .'.xlsx');
    }

    public function transfer(Request $request, $id){
        //dd($request);
        $data = PaymentRequest::find($id);
        $data->is_transfer= $request->is_transfer;
        $data->is_transfer_by = auth()->user()->id;
        if($request->hasFile('transfer_proof_by_admin'))
        {
            $image = $request->transfer_proof_by_admin;
            $name = $id.'transfer_proof.'.$image->getClientOriginalExtension();
            $company_url = session('company_url','umum').'/';
            $destinationPath = public_path('storage/payment-request/transfer-proof/').$company_url;
            $image->move($destinationPath, $name);
            $data->transfer_proof = $company_url.$name;
        }
        $data->save();

        $userApprovalTokens = [];

        $db = Config::get('database.default','mysql');
        $params             = getEmailConfig();
        $params['data']     = $data;
        $params['value']    = $data->historyApproval;
        $params['subject']  = get_setting('mail_name') . ' - Transfer Payment Request';
        $params['view']     = 'email.cash-advance';
        $params['total']    = total_payment_request_nominal_approved($data->id);
        $params['text']     = '<p><strong>Dear Sir/Madam '. $data->user->name .'</strong>,</p> <p>  Submission of your Payment Request has been transfered.</p>';

        Config::set('database.default', 'mysql');

        if(!empty($data->user->email)) {
            $params['email'] = $data->user->email;
            $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
            dispatch($job);
        }
        Config::set('database.default', $db);
        
        $notifTitle = "Payment Request";
        $notifType  = "payment_request";
        if($data->user->firebase_token) {
            array_push($userApprovalTokens, $data->user->firebase_token);
        }

        return redirect()->route('administrator.paymentRequestCustom.index')->with('message-success', 'Transfer Proof Successfully Sent!');
    }

}
