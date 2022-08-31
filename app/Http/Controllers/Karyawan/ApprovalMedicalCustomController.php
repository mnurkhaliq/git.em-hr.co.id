<?php

namespace App\Http\Controllers\Karyawan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\MedicalReimbursement;
use App\Models\MedicalReimbursementForm;
use App\Models\HistoryApprovalMedical;
use App\Models\TransferSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use App\Models\StructureOrganizationCustom; 
use App\Models\OrganisasiDivision;
use App\Models\OrganisasiPosition;

class ApprovalMedicalCustomController extends Controller
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
            $params['division'] = OrganisasiDivision::where('project_id', $user->project_id)->select('organisasi_division.*')->get();
            $params['position'] = OrganisasiPosition::where('project_id', $user->project_id)->select('organisasi_position.*')->get();
        } else
        {
            $params['division'] = OrganisasiDivision::all();
            $params['position'] = OrganisasiPosition::all();
        }

        if(count(request()->all())) {
            \Session::put('amr-employee_status', request()->employee_status);
            \Session::put('amr-position_id', request()->position_id);
            \Session::put('amr-division_id', request()->division_id);
            \Session::put('amr-name', request()->name);
        }

        $employee_status    = \Session::get('amr-employee_status');
        $position_id        = \Session::get('amr-position_id');
        $division_id        = \Session::get('amr-division_id');
        $name               = \Session::get('amr-name');

        $cek_transfer_approve = TransferSetting::where('user_id', auth()->user()->id)->first();
        $data = HistoryApprovalMedical::join('medical_reimbursement','medical_reimbursement.id','=','history_approval_medical.medical_reimbursement_id')
            ->where('structure_organization_custom_id', \Auth::user()->structure_organization_custom_id)
            ->where('medical_reimbursement.status', '!=', 5);

        if($cek_transfer_approve != null && empty($name) && empty($employee_status) && empty($position_id) && empty($division_id)){
            $data= $data->orWhere(function($qry){
                $qry->where('status', '2');
            })->orderByRaw('IF(structure_organization_custom_id = '.auth()->user()->structure_organization_custom_id.', 0,1)');
        } 
        
        if(request())
        {
            if (!empty($name)) {
                $data = $data->whereHas('medicalReimbursement.user', function($qry) use($name){
                        $qry->where('name', 'LIKE', '%' . $name . '%')
                        ->orWhere('nik', 'LIKE', '%' . $name . '%');
                    })
                    ->orWhere('number', 'LIKE', '%' . $name . '%');
            }
            
            if(!empty($employee_status))
            {
                $data = $data->whereHas('medicalReimbursement.user', function($qry) use($employee_status){
                    $qry->where('organisasi_status', $employee_status);
                });
            }

            if((!empty($division_id)) and (empty($position_id))) 
            {   
                $data = $data->whereHas('medicalReimbursement.user', function($qry) use($division_id){
                    $qry->join('structure_organization_custom','users.structure_organization_custom_id','=','structure_organization_custom.id')->where('structure_organization_custom.organisasi_division_id',$division_id);
                });
            }

            if((!empty($position_id)) and (empty($division_id)))
            {   
                $data = $data->whereHas('medicalReimbursement.user', function($qry) use($position_id){
                    $qry->join('structure_organization_custom','users.structure_organization_custom_id','=','structure_organization_custom.id')->where('structure_organization_custom.organisasi_position_id',$position_id);
                });
            }
            if((!empty($position_id)) and (!empty($division_id)))
            {
                $data = $data->whereHas('medicalReimbursement.user', function($qry) use($position_id, $division_id){
                    $qry->join('structure_organization_custom','users.structure_organization_custom_id','=','structure_organization_custom.id')->where('structure_organization_custom.organisasi_position_id',$position_id)->where('structure_organization_custom.organisasi_division_id',$division_id);
                });
            }
        }    
        
        if(request()->reset == 1)
        {
            \Session::forget('amr-employee_status');
            \Session::forget('amr-position_id');
            \Session::forget('amr-division_id');
            \Session::forget('amr-name');

            return redirect()->route('karyawan.approval.medical-custom.index');
        }
            
        $params['data'] = $data->orderBy('medical_reimbursement_id', 'DESC')->get()->unique('medical_reimbursement_id');
        
        // dd($params['data']);
        return view('karyawan.approval-medical-custom.index')->with($params);
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

    public function detail($id)
    {   
        $params['data'] = cek_medical_id_approval($id);

        if (!$params['data']) {
            return redirect()->route('karyawan.approval.medical-custom.index')->with('message-error', 'You don\'t have permission to perform this action!');
        }

        $params['data']         = MedicalReimbursement::where('id', $id)->first();
        $params['history'] = HistoryApprovalMedical::where('medical_reimbursement_id',$id)->where('structure_organization_custom_id', \Auth::user()->structure_organization_custom_id)->first();

        return view('karyawan.approval-medical-custom.detail')->with($params);
    }

    public function proses(Request $request)
    {
        $request->validate([
            'noteApproval' => 'required'
        ],
        [
            'noteApproval.required' => 'the note field is required!',
        ]); 

        $user = Auth::user();
        if(isset($request->nominal_approve))
        {
            foreach($request->nominal_approve as $k => $item)
            {
                $i = MedicalReimbursementForm::where('id', $k)->first();
                if($i)
                {
                    $i->nominal_approve    = preg_replace('/[^0-9]/', '', $item);
                    $i->note_approval      = $request->note_approval[$k];
                    $i->save();
                }
            }
        }

        $medical            = MedicalReimbursement::find($request->id);
        $params             = getEmailConfig();
        $params['data']     = $medical;
        $params['value']    = $medical->historyApproval;
        $params['subject']  = get_setting('mail_name') . ' - Medical Reimbursement';
        $params['view']     = 'email.medical-approval-custom';


        $approval                = HistoryApprovalMedical::where(['medical_reimbursement_id'=>$medical->id,'structure_organization_custom_id'=> $user->structure_organization_custom_id])->first();
        $approval->approval_id   = $user->id;
        $approval->is_approved   = $request->status;
        $approval->date_approved = date('Y-m-d H:i:s');
        $approval->note          = $request->noteApproval;
        $approval->save();

        $db = Config::get('database.default','mysql');

        $notifTitle = "";
        $notifType = "";
        $userApprovalTokens = [];
        $userApprovalIds = [];

        if($approval->is_approved == 0){ // Jika rejected
            $medical->status = 3;
            $params['total']    = total_medical_nominal($medical->id);
            $params['text']     = '<p><strong>Dear Sir/Madam '. $medical->user->name .'</strong>,</p> <p>  Submission of your Medical Reimbursement <strong style="color: red;">REJECTED</strong>.</p>';
            Config::set('database.default', 'mysql');
            if(!empty($medical->user->email)) {
                $params['email'] = $medical->user->email;
                $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                dispatch($job);
            }
            Config::set('database.default', $db);

            $notifTitle = "Medical Reimbursement";
            $notifType  = "medical";
            if($medical->user->firebase_token) {
                array_push($userApprovalTokens, $medical->user->firebase_token);
                $this->sentNotif($notifTitle, $params['text'], $notifType, $userApprovalTokens, $medical->id);
                $userApprovalTokens = [];
            }
            \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $medical->user->id, $medical, $notifType);
        }else if($approval->is_approved == 1){
            $lastApproval = $medical->historyApproval->last();
            if($approval->structure_organization_custom_id == $lastApproval->structure_organization_custom_id){
                $params['total']    = total_medical_nominal_approved($medical->id);
                $params['text']     = '<p><strong>Dear Sir/Madam '. $medical->user->name .'</strong>,</p> <p>  Submission of your Medical Reimbursement <strong style="color: green;">APPROVED</strong>.</p>';
                $medical->status = 2;
                Config::set('database.default', 'mysql');
                if(!empty($medical->user->email)) {
                    $params['email'] = $medical->user->email;
                    $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                    dispatch($job);
                }
                Config::set('database.default', $db);

                $notifTitle = "Medical Reimbursement";
                $notifType  = "medical";
                if($medical->user->firebase_token) {
                    array_push($userApprovalTokens, $medical->user->firebase_token);
                    $this->sentNotif($notifTitle, $params['text'], $notifType, $userApprovalTokens, $medical->id);
                    $userApprovalTokens = [];
                }
                \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $medical->user->id, $medical, $notifType);

                $userApproval = TransferSetting::get();
                if($userApproval) {
                    Config::set('database.default', 'mysql');
                    foreach ($userApproval as $key => $value) {
                        if ($value->user->email == "") continue;
                        $params['text']     = '<p><strong>Dear Sir/Madam '. $value->user->name .'</strong>,</p> <p> '. $medical->user->name .'  / '.  $medical->user->nik .' applied for Medical Reimbursement and currently waiting your payment.</p>';
                        $params['email'] = $value->user->email;
                        $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                        dispatch($job);

                        $notifTitle = "Transfer Medical Reimbursement";
                        $notifType  = "transfer_medical_approve";
                        if($value->user->firebase_token) {
                            array_push($userApprovalTokens, $value->user->firebase_token);
                            $this->sentNotif($notifTitle, $params['text'], $notifType, $userApprovalTokens, $medical->id);
                            $userApprovalTokens = [];
                        }
                        \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $value->user->id, $medical, $notifType);
                    }
                    Config::set('database.default', $db);
                }
            }else{
                $medical->status = 1;
                $nextApproval = HistoryApprovalMedical::where(['medical_reimbursement_id'=>$medical->id,'setting_approval_level_id'=> ($approval->setting_approval_level_id+1)])->first();
                if($nextApproval){
                    $userApproval = user_approval_custom($nextApproval->structure_organization_custom_id);
                    $params['total']    = total_medical_nominal($medical->id);

                    if($userApproval) {
                        Config::set('database.default', 'mysql');
                        foreach ($userApproval as $key => $value) {
                            if (empty($value->email)) continue;
                            $params['text']     = '<p><strong>Dear Sir/Madam '. $value->name .'</strong>,</p> <p> '. $medical->user->name .'  / '.  $medical->user->nik .' applied for Medical Reimbursement and currently waiting your approval.</p>';
                            $params['email'] = $value->email;
                            $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                            dispatch($job);
                        }
                        Config::set('database.default', $db);

                        $params['text'] = '<p> '. $medical->user->name .'  / '.  $medical->user->nik .' applied for Medical Reimbursement and currently waiting your approval.</p>';
                        $notifTitle = "Medical Reimbursement Approval";
                        $notifType  = "medical_approval";
                        $userApprovalTokens = user_approval_firebase_tokens($nextApproval->structure_organization_custom_id);
                        $userApprovalIds = user_approval_id($nextApproval->structure_organization_custom_id);
                        $this->sentNotif($notifTitle, $params['text'], $notifType, $userApprovalTokens, $medical->id);
                        $userApprovalTokens = [];
                    }
                }
            }
        }
        $medical->save();

        foreach ($userApprovalIds as $value) {
            \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $value, $medical, $notifType);
        }

        return redirect()->route('karyawan.approval.medical-custom.index')->with('message-success', 'Form Medical Reimbursement Successfully Processed !');
    }

    public function sentNotif($title, $content, $type, $token, $id){
        if(count($token) > 0){
            $config = [
                'title' => $title,
                'content' => strip_tags($content),
                'type' => $type,
                'firebase_token' => $token
            ];
            $notifData = [
                'id' => $id
            ];
            $db = Config::get('database.default', 'mysql');
            Config::set('database.default', 'mysql');
            dispatch((new \App\Jobs\SendPushTokens($notifData,$config))->onQueue('push'));
            Config::set('database.default', $db);
        }
        return 'sent notif success';
    }

    public function detailTransfer($id)
    {   
        $params['data'] = cek_transfer_setting_user($id);

        if (!$params['data']) {
            return redirect()->route('karyawan.approval.medical-custom.index')->with('message-error', 'You don\'t have permission to perform this action!');
        }

        $params['data'] = MedicalReimbursement::where('id', $id)->first();
        $params['history'] = HistoryApprovalMedical::where('medical_reimbursement_id',$id)->get();
        $params['form'] = MedicalReimbursementForm::where('medical_reimbursement_id', $id)->get();

        return view('karyawan.approval-medical-custom.transfer')->with($params);
    }

    public function transfer(Request $request, $id){
        //dd($request);
        $data = MedicalReimbursement::find($id);
        $data->is_transfer= $request->is_transfer;
        $data->disbursement = $request->disbursement;
        $data->is_transfer_by = auth()->user()->id;
        if($request->hasFile('transfer_proof_by_admin'))
        {
            $image = $request->transfer_proof_by_admin;
            $name = md5($id.'transfer_proof').'.'.$image->getClientOriginalExtension();
            $company_url = session('company_url','umum').'/';
            $destinationPath = public_path('storage/medical/transfer-proof/').$company_url;
            $image->move($destinationPath, $name);
            $data->transfer_proof = $company_url.$name;
        }
        $data->save();

        $userApprovalTokens = [];

        $db = Config::get('database.default','mysql');
        $params             = getEmailConfig();
        $params['data']     = $data;
        $params['value']    = $data->historyApproval;
        $params['subject']  = get_setting('mail_name') . ' - Transfer Medical Reimbursement';
        $params['view']     = 'email.payment-request-approval-custom';
        $params['total']    = total_medical_nominal_approved($data->id);
        if($request->disbursement=='Transfer'){
            $params['text']     = '<p><strong>Dear Sir/Madam '. $data->user->name .'</strong>,</p> <p>  Submission of your Medical Reimbursement has been transfered.</p>';
        }else{
            $params['text']     = '<p><strong>Dear Sir/Madam '. $data->user->name .'</strong>,</p> <p>  Submission of your Medical Reimbursement will be merged with the next payroll.</p>';
        }

        Config::set('database.default', 'mysql');

        if(!empty($data->user->email)) {
            $params['email'] = $data->user->email;
            $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
            dispatch($job);
        }
        Config::set('database.default', $db);
        
        $notifTitle = "Medical Reimbursement";
        $notifType  = "medical_reimbursement";

        \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $data->user->id, $data, $notifType);

        if($data->user->firebase_token) {
            array_push($userApprovalTokens, $data->user->firebase_token);
        }

        if(count($userApprovalTokens) > 0){
            $config = [
                'title' => $notifTitle,
                'content' => strip_tags($params['text']),
                'type' => $notifType,
                'firebase_token' => $userApprovalTokens
            ];
            $notifData = [
                'id' => $data->id
            ];
            $db = Config::get('database.default', 'mysql');
            Config::set('database.default', 'mysql');
            dispatch((new \App\Jobs\SendPushTokens($notifData,$config))->onQueue('push'));
            Config::set('database.default', $db);
        }

        return redirect()->route('karyawan.approval.medical-custom.index')->with('message-success', 'Transfer Proof Successfully Sent!');
    }
}
