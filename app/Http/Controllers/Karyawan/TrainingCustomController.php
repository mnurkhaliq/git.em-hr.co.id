<?php

namespace App\Http\Controllers\Karyawan;

use App\Models\Kabupaten;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Training;
use App\Models\TrainingType;
use App\User;
use App\Models\HistoryApprovalTraining;
use App\Models\TrainingTransportationType; 
use App\Models\TrainingTransportation; 
use App\Models\TrainingAllowance; 
use App\Models\TrainingDaily; 
use App\Models\TrainingOther; 
use App\Models\TransferSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;

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
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $params['data'] = Training::where('user_id', \Auth::user()->id)->orderBy('id', 'DESC')->get();

        return view('karyawan.training-custom.index')->with($params);
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
            return redirect()->route('karyawan.training-custom.index')->with('message-error', 'Your position is not defined yet. Please contact your admin !');
        }else {
            $checkApproval = $checkApproval->level1Training;
            if ($checkApproval == null) {
                return redirect()->route('karyawan.training-custom.index')->with('message-error', 'Setting approval not define yet. Please contact your admin !');
            }
        }

        $user = \Auth::user();
        if($user->project_id != NULL)
        {
            $params['trainingType'] = TrainingType::join('users', 'users.id','=', 'training_type.user_created')->where('users.project_id', $user->project_id)->select('training_type.*')->get();
        }else{
            $params['trainingType'] = TrainingType::all();
        }

        $params['district'] = Kabupaten::get();

        return view('karyawan.training-custom.create')->with($params);
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
        $checkApproval = \Auth::user()->approval;
        if($checkApproval == null)
        {
            return redirect()->route('karyawan.training-custom.index')->with('message-error', 'Your position is not defined yet. Please contact your admin !');
        }else {
            $checkApproval = $checkApproval->level1Training;
            if($checkApproval == null){
                return redirect()->route('karyawan.training-custom.index')->with('message-error', 'Setting approval not define yet. Please contact your admin !');
            }

            if($request->lokasi_kegiatan == 'Dalam Negeri') {
                $tempat_tujuan = $request->tempat_tujuan != null ? $request->tempat_tujuan : $request->tempat_tujuan_aboard;
                $kab = Kabupaten::where('nama', $tempat_tujuan)->first();
                if (!$kab) {
                    return redirect()->back()->withInput()->withErrors(['Destination not found!']);
                }
            }
            else{
                $tempat_tujuan =  $request->tempat_tujuan_aboard != null ? $request->tempat_tujuan_aboard : $request->tempat_tujuan;
            }

            $data                           = new Training();
            $data->user_id                  = \Auth::user()->id;
            // Form Kegiatan
            //$data->jenis_training           = $request->jenis_training;
            $data->training_type_id           = $request->training_type_id;
            $data->cabang_id                = $request->cabang_id;
            $data->lokasi_kegiatan          = $request->lokasi_kegiatan;
            $data->tempat_tujuan            = $tempat_tujuan;
            $data->topik_kegiatan           = $request->topik_kegiatan;
            $data->tanggal_kegiatan_start   = $request->tanggal_kegiatan_start;
            $data->tanggal_kegiatan_end     = $request->tanggal_kegiatan_end;
            $data->pengambilan_uang_muka    = $request->pengambilan_uang_muka ? preg_replace('/[^0-9]/', '', $request->pengambilan_uang_muka ) : 0;
            if ($data->pengambilan_uang_muka > 0) {
                $data->tanggal_pengajuan = $request->tanggal_pengajuan;
                $data->tanggal_penyelesaian = Carbon::parse($request->tanggal_pengajuan)->startOfDay()->addDays(get_setting('settlement_duration') ?: 10);
            }
            $data->status                   = 1;
            $data->tipe_perjalanan          = $request->tipe_perjalanan;
            $data->is_transfer = $request->pengambilan_uang_muka && $request->pengambilan_uang_muka > 0 != NULL  ? 0 : 1;
            $data->is_transfer_claim =0;
            $data->number = 'BT-' . Carbon::now()->format('dmY') . '/' . \Auth::user()->nik . '-' . (Training::where('user_id', \Auth::user()->id)->count() + 1);

            // Form Perjalanan
            if($request->tipe_perjalanan != 'Tidak Ada'){
                $data->transportasi_berangkat       = $request->transportasi_berangkat;
                $data->tanggal_berangkat            = $request->tanggal_berangkat;
                $data->waktu_berangkat              = $request->waktu_berangkat;
                $data->rute_dari_berangkat          = $request->rute_dari_berangkat;
                $data->rute_tujuan_berangkat        = $request->rute_tujuan_berangkat;
                $data->tipe_kelas_berangkat         = $request->tipe_kelas_berangkat;
                $data->nama_transportasi_berangkat  = $request->nama_transportasi_berangkat;
                if($request->tipe_perjalanan == 'Pulang Pergi'){
                    $data->transportasi_pulang          = $request->transportasi_pulang;
                    $data->tanggal_pulang               = $request->tanggal_pulang;
                    $data->waktu_pulang                 = $request->waktu_pulang;
                    $data->rute_dari_pulang             = $request->rute_dari_pulang;
                    $data->rute_tujuan_pulang           = $request->rute_tujuan_pulang;
                    $data->tipe_kelas_pulang            = $request->tipe_kelas_pulang;
                    $data->nama_transportasi_pulang     = $request->nama_transportasi_pulang;
                }

                $data->pergi_bersama            = $request->pergi_bersama;
                $data->note                     = $request->note;
            }
            $data->save();

            $historyApproval     = $user->approval->itemsTraining;
            $settingApprovalItem = $historyApproval[0]->structure_organization_custom_id;
            foreach ($historyApproval as $level => $value) {
                # code...
                $history = new HistoryApprovalTraining();
                $history->training_id                      = $data->id;
                $history->setting_approval_level_id        = ($level+1);
                $history->structure_organization_custom_id = $value->structure_organization_custom_id;
                $history->save();
            }
            $historyApprov = HistoryApprovalTraining::where('training_id',$data->id)->orderBy('setting_approval_level_id','asc')->get();

            $userApproval = user_approval_custom($settingApprovalItem);
            $params = getEmailConfig();
            $db = Config::get('database.default', 'mysql');

            $params['data'] = $data;
            $params['value'] = $historyApprov;
            $params['view'] = 'email.training-approval-custom';
            $params['subject'] = get_setting('mail_name') . ' - Business Trip';
            if ($userApproval) {
                Config::set('database.default', 'mysql');
                foreach ($userApproval as $key => $value) { 
                    if (empty($value->email)) {
                        continue;
                    }

                    $params['email'] = $value->email;
                    $params['text']     = '<p><strong>Dear Sir/Madam '. $value->name .'</strong>,</p> <p> '. $data->user->name .'  / '.  $data->user->nik .' applied for Business Trip and currently waiting your approval.</p>';
                    $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                    dispatch($job);
                }
                Config::set('database.default', $db);
                $params['text'] = '<p> '. $data->user->name .'  / '.  $data->user->nik .' applied for Business Trip and currently waiting your approval.</p>';
            }

            $userApprovalTokens = user_approval_firebase_tokens($settingApprovalItem);
            info("structure id ".$settingApprovalItem);
            info($userApprovalTokens);
       
            foreach (user_approval_id($settingApprovalItem) as $value) {
                \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $value, $data, 'business_trip_approval');
            }
    
            if(count($userApprovalTokens) > 0){
                $config = [
                    'title' => "Business Trip Approval",
                    'content' => strip_tags($params['text']),
                    'type' => 'business_trip_approval',
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

            return redirect()->route('karyawan.training-custom.index')->with('message-success', 'Business Trip succesfully process');
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
        $params['data']         = Training::where('id', $id)->first();

        if (!IsAccess($params['data'])) {
            return redirect()->route('karyawan.training-custom.index')->with('message-error', 'You don\'t have permission to perform this action!');
        }

        return view('karyawan.training-custom.detail-training')->with($params);
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

     public function claim($id)
    {  
        $params['data'] = Training::where('id', $id)->first();

        if (!IsAccess($params['data'])) {
            return redirect()->route('karyawan.training-custom.index')->with('message-error', 'You don\'t have permission to perform this action!');
        }

        $params['transportationtype'] = TrainingTransportationType::all();
        return view('karyawan.training-custom.biaya')->with($params);

    }
     public function prosesclaim(Request $request)
    {
        $data = Training::find($request->id);
        $data->sub_total_1 = $request->sub_total_1 ?: 0;
        $data->sub_total_2 = $request->sub_total_2 ?: 0;
        $data->sub_total_3 = $request->sub_total_3 ?: 0;
        $data->sub_total_4 = $request->sub_total_4 ?: 0;
        $data->status_actual_bill = $request->status_actual_bill;
        $data->date_submit_actual_bill = date('Y-m-d');

        $defaultAcomodation = TrainingTransportation::where('training_id', $data->id)->get();
        TrainingTransportation::where('training_id', $data->id)->delete();
        if($request->dateAcomodation != null)
        {
            foreach($request->dateAcomodation as $key => $item)
            {
                $acomodation = new TrainingTransportation();
                $acomodation->training_id   = $data->id;
                $acomodation->date = $item;
                $acomodation->training_transportation_type_id  = $request->training_transportation_type_id[$key];
                $acomodation->nominal  = preg_replace('/[^0-9]/', '', $request->nominalAcomodation[$key]);
                $acomodation->note       = $request->noteAcomodation[$key];

                if(isset($request->file_strukAcomodation[$key]))
                {
                    $image = $request->file_strukAcomodation[$key];
                    $name = md5(rand() . $image->getClientOriginalName() . time()) . "." . $image->getClientOriginalExtension();
                    $company_url = session('company_url','umum').'/';
                    $destinationPath = public_path('storage/file-acomodation/').$company_url;
                    $image->move($destinationPath, $name);
                    $acomodation->file_struk = $company_url.$name;
                } else if (isset($request->idAcomodation[$key])) {
                    $acomodation->file_struk = ($temp = $defaultAcomodation->where('id', $request->idAcomodation[$key])->first()) ? $temp->file_struk : $temp;
                }
                $acomodation->save();
            }
        }

        $defaultAllowance = TrainingAllowance::where('training_id', $data->id)->get();
        TrainingAllowance::where('training_id', $data->id)->delete();    
        if($request->dateAllowance != null)
        {
            foreach($request->dateAllowance as $key => $item2)
            {
                $form = new TrainingAllowance();
                $form->training_id   = $data->id;
                $form->date          = $item2;
                $form->meal_plafond  = preg_replace('/[^0-9]/', '', $request->meal_plafond[$key]);
                $form->morning       = preg_replace('/[^0-9]/', '', $request->morning[$key]);
                $form->afternoon     = preg_replace('/[^0-9]/', '', $request->afternoon[$key]);
                $form->evening       = preg_replace('/[^0-9]/', '', $request->evening[$key]);
                $form->note          = $request->noteAllowance[$key];
                
                if(isset($request->file_strukAllowance[$key]))
                {
                    $image = $request->file_strukAllowance[$key];
                    $name = md5(rand() . $image->getClientOriginalName() . time()) . "." . $image->getClientOriginalExtension();
                    $company_url = session('company_url','umum').'/';
                    $destinationPath = public_path('storage/file-allowance/').$company_url;
                    $image->move($destinationPath, $name);
                    $form->file_struk = $company_url.$name;
                } else if (isset($request->idAllowance[$key])) {
                    $form->file_struk = ($temp = $defaultAllowance->where('id', $request->idAllowance[$key])->first()) ? $temp->file_struk : $temp;
                }
                $form->save();
            }
        }

        $defaultDaily = TrainingDaily::where('training_id', $data->id)->get();
        TrainingDaily::where('training_id', $data->id)->delete();    
        if($request->dateDaily != null)
        {
            foreach($request->dateDaily as $key => $item3)
            {
                $daily = new TrainingDaily();
                $daily->training_id   = $data->id;
                $daily->date          = $item3;
                $daily->daily_plafond   = preg_replace('/[^0-9]/', '', $request->daily_plafond[$key]);
                $daily->daily          = preg_replace('/[^0-9]/', '', $request->nominalDaily[$key]);
                $daily->note          = $request->noteDaily[$key];
                
                if(isset($request->file_strukDaily[$key]))
                {
                    $image = $request->file_strukDaily[$key];
                    $name = md5(rand() . $image->getClientOriginalName() . time()) . "." . $image->getClientOriginalExtension();
                    $company_url = session('company_url','umum').'/';
                    $destinationPath = public_path('storage/file-daily/').$company_url;
                    $image->move($destinationPath, $name);
                    $daily->file_struk = $company_url.$name;
                } else if (isset($request->idDaily[$key])) {
                    $daily->file_struk = ($temp = $defaultDaily->where('id', $request->idDaily[$key])->first()) ? $temp->file_struk : $temp;
                }
                $daily->save();
            }
        }

        $defaultOther = TrainingOther::where('training_id', $data->id)->get();
        TrainingOther::where('training_id', $data->id)->delete();    
        if($request->dateOther != null)
        {
            foreach($request->dateOther as $key => $item4)
            {
               // dd($item4);
                $other = new TrainingOther();
                $other->training_id   = $data->id;
                $other->date          = $item4;
                $other->description   = $request->descriptionOther[$key];
                $other->nominal       = preg_replace('/[^0-9]/', '', $request->nominalOther[$key]);
                $other->note          = $request->noteOther[$key];

                if(isset($request->file_strukOther[$key]))
                {
                    $image = $request->file_strukOther[$key];
                    $name = md5(rand() . $image->getClientOriginalName() . time()) . "." . $image->getClientOriginalExtension();
                    $company_url = session('company_url','umum').'/';
                    $destinationPath = public_path('storage/file-other/').$company_url;
                    $image->move($destinationPath, $name);
                    $other->file_struk = $company_url.$name;
                } else if (isset($request->idOther[$key])) {
                    $other->file_struk = ($temp = $defaultOther->where('id', $request->idOther[$key])->first()) ? $temp->file_struk : $temp;
                }
                $other->save();
            }
        }
        
        $data->save();

        if ($request->status_actual_bill == 1 || $request->status_actual_bill == 4) {
            HistoryApprovalTraining::where('training_id',$data->id)->update([
                'approval_id_claim' => null,
                'is_approved_claim' => null,
                'date_approved_claim' => null,
            ]);
        }

        if ($request->status_actual_bill == 1) {
            $historyApprov = HistoryApprovalTraining::where('training_id',$data->id)->orderBy('setting_approval_level_id','asc')->get();
            if(count($historyApprov)>0) {
                $settingApprovalItem = $historyApprov[0]->structure_organization_custom_id;
                $userApproval = user_approval_custom($settingApprovalItem);
                $params = getEmailConfig();
                $db = Config::get('database.default', 'mysql');

                $params['data'] = $data;
                $params['value'] = $historyApprov;
                $params['view'] = 'email.training-approval-custom';
                $params['subject'] = get_setting('mail_name') . ' - Claim Business Trip';
                if ($userApproval) {
                    Config::set('database.default', 'mysql');
                    foreach ($userApproval as $key => $value) { 
                        if (empty($value->email)) {
                            continue;
                        }
        
                        $params['email'] = $value->email;
                        $params['text']     = '<p><strong>Dear Sir/Madam '. $value->name .'</strong>,</p> <p> '. $data->user->name .'  / '.  $data->user->nik .' applied for Claim of Business Trip and currently waiting your approval.</p>';
                        $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                        dispatch($job);
                    }
                    Config::set('database.default', $db);
                    $params['text']  = '<p> '. $data->user->name .'  / '.  $data->user->nik .' applied for Claim of Business Trip and currently waiting your approval.</p>';
                }
            }

            $userApprovalTokens = user_approval_firebase_tokens($settingApprovalItem);
            info("structure id ".$settingApprovalItem);
            info($userApprovalTokens);
        
            foreach (user_approval_id($settingApprovalItem) as $value) {
                \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $value, $data, 'training_approval');
            }
  
            if(count($userApprovalTokens) > 0){
                $config = [
                    'title' => "Claim Business Trip Approval",
                    'content' => strip_tags($params['text']),
                    'type' => 'training_approval',
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

        return redirect()->route('karyawan.training-custom.index')->with('message-success', 'Data successfully saved!');

    }

    public function transfer($id)
    {
        $params['data']         = Training::where('id', $id)->first();

        if (!IsAccess($params['data'])) {
            return redirect()->route('karyawan.training-custom.index')->with('message-error', 'You don\'t have permission to perform this action!');
        }

        $params['karyawan']     = User::whereIn('access_id', [1,2])->get();
        $params['transportationtype'] = TrainingTransportationType::all();
        $params['acomodation']        = TrainingTransportation::where('training_id',$id)->get();
        $params['allowance']        = TrainingAllowance::where('training_id',$id)->get();
        $params['daily']        = TrainingDaily::where('training_id',$id)->get();
        $params['other']        = TrainingOther::where('training_id',$id)->get();
        $params['history'] = HistoryApprovalTraining::where('training_id',$id)->orderBy('id', 'DESC')->first();

        return view('karyawan.training-custom.transfer-claim')->with($params);
    }

    public function prosesTransfer(Request $request, $id){
        //dd($request);
        $data = Training::find($id);
        $data->is_transfer_claim = $request->is_transfer_claim;
        $data->is_transfer_claim_by = auth()->user()->id;
        if($request->hasFile('transfer_proof_claim_by_admin'))
        {
            $image = $request->transfer_proof_claim_by_admin;
            $name = md5($id.'transfer_proof_claim_by_user').'.'.$image->getClientOriginalExtension();
            $company_url = session('company_url','umum').'/';
            $destinationPath = public_path('storage/training-custom/transfer-proof/').$company_url;
            $image->move($destinationPath, $name);
            $data->transfer_proof_claim = $company_url.$name;
        }
        $data->save();

        $userApprovalTokens = [];
        
        $params             = getEmailConfig();
        $params['data']     = $data;
        $params['value']    = $data->historyApproval;
        $params['subject']  = get_setting('mail_name') . ' - Transfer Claim Business Trip';
        $params['view']     = 'email.training-transfer';
        
        $db = Config::get('database.default','mysql');

        $params['total']    = -1 * ($data->sub_total_1_disetujui + $data->sub_total_2_disetujui + $data->sub_total_3_disetujui + $data->sub_total_4_disetujui - $data->pengambilan_uang_muka);
        $userApproval = TransferSetting::get();

        if($userApproval) {
            Config::set('database.default', 'mysql');
            foreach ($userApproval as $key => $value) {
                if ($value->user->email == "") continue;
                $params['text']     = '<p><strong>Dear Sir/Madam '. $value->user->name .'</strong>,</p> <p> '. $data->user->name .'  / '.  $data->user->nik .' has been sent transfer proof business trip.</p>';
                $params['email'] = $value->user->email;
                $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                dispatch($job);
                
                $notifTitle = "Transfer Claim Business Trip";
                $notifType  = "transfer_claim_business_trip_more";
                if($value->user->firebase_token) {
                    array_push($userApprovalTokens, $value->user->firebase_token);
                    $this->sentNotif($notifTitle, $params['text'], $notifType, $userApprovalTokens, $data->id);
                    $userApprovalTokens = [];
                }
                \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $value->user->id, $data, $notifType);
            }
            Config::set('database.default', $db);
        }

        return redirect()->route('karyawan.training-custom.index')->with('message-success', 'Transfer Proof Claim Successfully Sent!');
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
    
}
