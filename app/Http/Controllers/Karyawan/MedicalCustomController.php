<?php

namespace App\Http\Controllers\Karyawan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\MedicalReimbursement;
use App\Models\MedicalReimbursementForm;
use App\Models\TransferSetting;
use App\User;
use App\Models\MedicalType;
use App\Models\HistoryApprovalMedical;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;

class MedicalCustomController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
         $params['data'] = MedicalReimbursement::where('user_id', \Auth::user()->id)->orderBy('id', 'DESC')->get();

        return view('karyawan.medical-custom.index')->with($params);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $checkApproval = \Auth::user()->approval;
        if($checkApproval == null)
        {
            return redirect()->route('karyawan.medical-custom.index')->with('message-error', 'Your position is not defined yet. Please contact your admin !');
        }else {
            if (count($checkApproval->itemsMedical) == 0) {
                return redirect()->route('karyawan.medical-custom.index')->with('message-error', 'Setting approval is not defined yet. Please contact your admin !');
            }
        }
        $user = \Auth::user();
        if($user->project_id != NULL)
        {
            $params['type'] = MedicalType::where('project_id', $user->project_id)->select('*')->get();
        }else{
            $params['type'] = MedicalType::all();
        }
        $params['karyawan'] = User::whereIn('access_id', [1,2])->get();

        return view('karyawan.medical-custom.create')->with($params);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) 
    {
        // dd($request);
        $user = Auth::user();
        $checkApproval = $user->approval;
        if($checkApproval == null)
        {
            return redirect()->route('karyawan.medical-custom.index')->with('message-error', 'Your position is not defined yet. Please contact your admin !');
        }else
        {
            if(count($checkApproval->itemsMedical) == 0){
                return redirect()->route('karyawan.medical-custom.index')->with('message-error', 'Setting approval is not defined yet. Please contact your admin !');
            }
            $data                       = new MedicalReimbursement();
            $data->user_id              = \Auth::user()->id;
            $data->tanggal_pengajuan    = $request->tanggal_pengajuan;
            $data->status               = $request->status;  
            $data->is_transfer = 0;
            $data->number = 'MR-' . Carbon::now()->format('dmY') . '/' . \Auth::user()->nik . '-' . (MedicalReimbursement::where('user_id', \Auth::user()->id)->count() + 1);
            $data->save();

            foreach($request->tanggal_kwitansi as $key => $item)
            {   
                $form                           = new MedicalReimbursementForm();
                $form->medical_reimbursement_id = $data->id;
                $form->tanggal_kwitansi         = $request->tanggal_kwitansi[$key] != null ? $request->tanggal_kwitansi[$key] : NULL;
                $form->user_family_id           = $request->user_family_id[$key] != null ? $request->user_family_id[$key] : NULL;
                $form->medical_type_id          = isset($request->medical_type_id[$key]) ? $request->medical_type_id[$key] : NULL;
                $form->no_kwitansi              = $request->no_kwitansi[$key] != null ? $request->no_kwitansi[$key] : NULL;
                $form->jumlah                   = $request->jumlah[$key] != null ? preg_replace('/[^0-9]/', '', $request->jumlah[$key]) : NULL;

                if (request()->hasFile('file_bukti_transaksi'))
                {
                    $file = $request->file('file_bukti_transaksi');

                    foreach($file as $k => $f)
                    {
                        if($k == $key)
                        {
                            $fname = md5(rand() . $f->getClientOriginalName() . time()) . "." . $f->getClientOriginalExtension();
                            $company_url = session('company_url','umum').'/';
                            $destinationPath = public_path('/storage/file-medical/').$company_url;
                            $f->move($destinationPath, $fname);
                            $form->file_bukti_transaksi = $company_url.$fname;
                        }
                    }
                }
                $form->save();
            }

            if($request->status == 1){
                $historyApproval     = $user->approval->itemsMedical;
                $settingApprovalItem = $historyApproval[0]->structure_organization_custom_id;
                foreach ($historyApproval as $level => $value) {
                    # code...
                    $history = new HistoryApprovalMedical();
                    $history->medical_reimbursement_id         = $data->id;
                    $history->setting_approval_level_id        = ($level+1);
                    $history->structure_organization_custom_id = $value->structure_organization_custom_id;
                    $history->save();
                }
                
                $historyApprov = HistoryApprovalMedical::where('medical_reimbursement_id',$data->id)->orderBy('setting_approval_level_id','asc')->get();

                $userApproval = user_approval_custom($settingApprovalItem);
                $db = Config::get('database.default','mysql');

                $params = getEmailConfig();
                $params['data']     = $data;
                $params['total']    = total_medical_nominal($data->id);
                $params['value']    = $historyApprov;
                $params['view']     = 'email.medical-approval-custom';
                $params['subject']  = get_setting('mail_name') . ' - Medical Reimbursement';
                if($userApproval) {
                    Config::set('database.default', 'mysql');
                    foreach ($userApproval as $key => $value) {
                        if (empty($value->email)) continue;
                        $params['email'] = $value->email;
                        $params['text']  = '<p><strong>Dear Sir/Madam '. $value->name .'</strong>,</p> <p> '. $data->user->name .'  / '.  $data->user->nik .' applied for Medical Reimbursement and currently waiting your approval.</p>';
                        $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                        dispatch($job);
                    }
                    Config::set('database.default', $db);
                    $params['text']  = '<p> '. $data->user->name .'  / '.  $data->user->nik .' applied for Medical Reimbursement and currently waiting your approval.</p>';
                }

                $userApprovalTokens = user_approval_firebase_tokens($settingApprovalItem);
                info("structure id ".$settingApprovalItem);
                info($userApprovalTokens);

                foreach (user_approval_id($settingApprovalItem) as $value) {
                    \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $value, $data, 'medical_approval');
                }
    
                if(count($userApprovalTokens) > 0){
                    $config = [
                        'title' => "Medical Reimbursement Approval",
                        'content' => strip_tags($params['text']),
                        'type' => 'medical_approval',
                        'firebase_token' => $userApprovalTokens
                    ];
                    $notifData = [
                        'id' => $data->id
                    ];
                    info($userApprovalTokens);
                    $db = Config::get('database.default', 'mysql');
                    Config::set('database.default', 'mysql');
                    dispatch((new \App\Jobs\SendPushTokens($notifData,$config))->onQueue('push'));
                    Config::set('database.default', $db);
                }
            }

            if($request->status == 1){
                return redirect()->route('karyawan.medical-custom.index')->with('message-success', 'Medical Reimbursement succesfully process');
            }
            else{
                return redirect()->route('karyawan.medical-custom.index')->with('message-success', 'Medical Reimbursement succesfully save to draft');
            }
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
        $params['data'] = MedicalReimbursement::where('id', $id)->first();

        if (!IsAccess($params['data'])) {
            return redirect()->route('karyawan.medical-custom.index')->with('message-error', 'You don\'t have permission to perform this action!');
        }
        $user = \Auth::user();
        if($user->project_id != NULL)
        {
            $params['type'] = MedicalType::where('project_id', $user->project_id)->select('*')->get();
        }else{
            $params['type'] = MedicalType::all();
        }

        $params['form'] = MedicalReimbursementForm::where('medical_reimbursement_id', $id)->get();
        $params['karyawan'] = User::whereIn('access_id', [1,2])->get();

        return view('karyawan.medical-custom.edit')->with($params);
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
        // dd($request);
        $user = Auth::user();
        $checkApproval = $user->approval;
        if($checkApproval == null)
        {
            return redirect()->route('karyawan.medical-custom.index')->with('message-error', 'Your position is not defined yet. Please contact your admin !');
        }else
        {
            if(count($checkApproval->itemsMedical) == 0){
                return redirect()->route('karyawan.medical-custom.index')->with('message-error', 'Setting approval is not defined yet. Please contact your admin !');
            }
            $data                       = MedicalReimbursement::find($id);
            $data->user_id              = \Auth::user()->id;
            $data->tanggal_pengajuan    = $request->tanggal_pengajuan;
            $data->status               = $request->status;  
            $data->is_transfer = 0;
            $data->save();

            $temp_form = MedicalReimbursementForm::where('medical_reimbursement_id', $id)->get();
            $other_form = MedicalReimbursementForm::where('medical_reimbursement_id', $id)->delete();
            
            foreach($request->tanggal_kwitansi as $key => $item)
            {   
                $form                           = new MedicalReimbursementForm();
                $form->medical_reimbursement_id = $data->id;
                $form->tanggal_kwitansi         = $request->tanggal_kwitansi[$key] != null ? $request->tanggal_kwitansi[$key] : NULL;
                $form->user_family_id           = $request->user_family_id[$key] != null ? $request->user_family_id[$key] : NULL;
                $form->medical_type_id          = isset($request->medical_type_id[$key]) ? $request->medical_type_id[$key] : NULL;
                $form->no_kwitansi              = $request->no_kwitansi[$key] != null ? $request->no_kwitansi[$key] : NULL;
                $form->jumlah                   = $request->jumlah[$key] != null ? preg_replace('/[^0-9]/', '', $request->jumlah[$key]) : NULL;

                if(isset($request->file_bukti_transaksi[$key]))
                {
                    $file = $request->file_bukti_transaksi[$key];
                    $fname = md5(rand() . $file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                    $company_url = session('company_url','umum').'/';
                    $destinationPath = public_path('/storage/file-medical/').$company_url;
                    $file->move($destinationPath, $fname);
                    $form->file_bukti_transaksi = $company_url.$fname;
                } else if (isset($request->idForm[$key])) {
                    $form->file_bukti_transaksi = ($temp = $temp_form->where('id', $request->idForm[$key])->first()) ? $temp->file_bukti_transaksi : $temp;
                }

                $form->save();
            }

            if($request->status == 1){
                $historyApproval     = $user->approval->itemsMedical;
                $settingApprovalItem = $historyApproval[0]->structure_organization_custom_id;
                foreach ($historyApproval as $level => $value) {
                    # code...
                    $history = new HistoryApprovalMedical();
                    $history->medical_reimbursement_id         = $data->id;
                    $history->setting_approval_level_id        = ($level+1);
                    $history->structure_organization_custom_id = $value->structure_organization_custom_id;
                    $history->save();
                }

                $historyApprov = HistoryApprovalMedical::where('medical_reimbursement_id',$data->id)->orderBy('setting_approval_level_id','asc')->get();

                $userApproval = user_approval_custom($settingApprovalItem);
                $db = Config::get('database.default','mysql');

                $params = getEmailConfig();
                $params['data']     = $data;
                $params['total']    = total_medical_nominal($data->id);
                $params['value']    = $historyApprov;
                $params['view']     = 'email.medical-approval-custom';
                $params['subject']  = get_setting('mail_name') . ' - Medical Reimbursement';
                if($userApproval) {
                    Config::set('database.default', 'mysql');
                    foreach ($userApproval as $key => $value) {
                        if (empty($value->email)) continue;
                        $params['email'] = $value->email;
                        $params['text']  = '<p><strong>Dear Sir/Madam '. $value->name .'</strong>,</p> <p> '. $data->user->name .'  / '.  $data->user->nik .' applied for Medical Reimbursement and currently waiting your approval.</p>';
                        $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                        dispatch($job);
                    }
                    Config::set('database.default', $db);
                    $params['text']  = '<p> '. $data->user->name .'  / '.  $data->user->nik .' applied for Medical Reimbursement and currently waiting your approval.</p>';
                }

                $userApprovalTokens = user_approval_firebase_tokens($settingApprovalItem);
                info("structure id ".$settingApprovalItem);
                info($userApprovalTokens);

                foreach (user_approval_id($settingApprovalItem) as $value) {
                    \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $value, $data, 'medical_approval');
                }
  
                if(count($userApprovalTokens) > 0){
                    $config = [
                        'title' => "Medical Reimbursement Approval",
                        'content' => strip_tags($params['text']),
                        'type' => 'medical_approval',
                        'firebase_token' => $userApprovalTokens
                    ];
                    $notifData = [
                        'id' => $data->id
                    ];
                    info($userApprovalTokens);
                    $db = Config::get('database.default', 'mysql');
                    Config::set('database.default', 'mysql');
                    dispatch((new \App\Jobs\SendPushTokens($notifData,$config))->onQueue('push'));
                    Config::set('database.default', $db);
                }
            }

            if($request->status == 1){
                return redirect()->route('karyawan.medical-custom.index')->with('message-success', 'Medical Reimbursement succesfully process');
            }
            else{
                return redirect()->route('karyawan.medical-custom.index')->with('message-success', 'Medical Reimbursement succesfully save to draft');
            }
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
        //
    }
}
