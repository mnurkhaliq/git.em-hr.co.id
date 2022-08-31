<?php

namespace App\Http\Controllers\Karyawan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\MedicalReimbursement;
use App\Models\MedicalReimbursementForm;

class ApprovalMedicalAtasanController extends Controller
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
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $params['data'] = MedicalReimbursement::where('approved_atasan_id', \Auth::user()->id)->orderBy('id', 'DESC')->get();
 
        return view('karyawan.approval-medical-atasan.index')->with($params);
    }

    /**
     * [proses description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function proses(Request $request)
    {
        $data = MedicalReimbursement::where('id', $request->id)->first();
        
        $data->is_approved_atasan = $request->status;
        $params['data'] = $data;
        
        if($request->status == 0)
        {
            $data->status = 3 ;

            $params['text']     = '<p><strong>Dear Sir/Madam '. $data->user->name .'</strong>,</p> <p> Submission of your Medical Reimbursement <strong style="color: red;">REJECTED</strong>.</p>';
            try {
                \Mail::send('email.medical-approval', $params,
                    function ($message) use ($data) {
                        $message->to($data->user->email);
                        $message->subject('Empore - Medical Reimbursement');
                    }
                );
            }catch (\Swift_TransportException $e){
                return redirect()->back()->with('message-error', 'Email config is invalid!');
            }
        }
        else
        {
            $params['text']     = '<p><strong>Dear Sir/Madam '. $data->direktur->name .'</strong>,</p> <p> '. $data->user->name .'  / '.  $data->user->nik .' applied for Medical Reimbursement and currently waiting your approval.</p>';
            try {
                \Mail::send('email.medical-approval', $params,
                    function ($message) use ($data) {
                        $message->to($data->direktur->email);
                        $message->subject('Empore - Medical Reimbursement');
                    }
                );
            }catch (\Swift_TransportException $e){
                return redirect()->back()->with('message-error', 'Email config is invalid!');
            }
        }

        $data->date_approved_atasan = date('Y-m-d H:i:s');
        $data->save();

        foreach($request->nominal_approve as $id => $val)
        {
            $form                       = MedicalReimbursementForm::where('id', $id)->first();
            $form->nominal_approve      = str_replace(',', '', $val);
            $form->save();
        }

        return redirect()->route('karyawan.approval.medical-atasan.index')->with('message-success', 'Form Medical Reimbursement successfully process !');
    }

    /**
     * [detail description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function detail($id)
    {   
        $params['data'] = MedicalReimbursement::where('id', $id)->first();

        return view('karyawan.approval-medical-atasan.detail')->with($params);
    }
}
