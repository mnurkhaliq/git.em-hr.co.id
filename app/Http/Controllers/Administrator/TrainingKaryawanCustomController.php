<?php

namespace App\Http\Controllers\Administrator;

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

class TrainingKaryawanCustomController extends Controller
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

        return view('administrator.training-custom.index')->with($params);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $user = \Auth::user();
        if($user->project_id != NULL)
        {
            $params['trainingType'] = TrainingType::join('users', 'users.id','=', 'training_type.user_created')->where('users.project_id', $user->project_id)->select('training_type.*')->get();
        }else{
            $params['trainingType'] = TrainingType::all();
        }

        return view('administrator.training-custom.create')->with($params);
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
        $checkApproval = \Auth::user()->approval->level1Training;
        if($checkApproval == null)
        {
            return redirect()->route('administrator.training-custom.index')->with('message-error', 'Setting approval not define yet. Please contact your admin !');
        }else {
            $data                           = new Training();
            $data->user_id                  = \Auth::user()->id;
            // Form Kegiatan
            //$data->jenis_training           = $request->jenis_training;
            $data->training_type_id           = $request->training_type_id;
            $data->cabang_id                = $request->cabang_id;
            $data->lokasi_kegiatan          = $request->lokasi_kegiatan;
            $data->tempat_tujuan            = $request->tempat_tujuan;
            $data->topik_kegiatan           = $request->topik_kegiatan;
            $data->tanggal_kegiatan_start   = $request->tanggal_kegiatan_start;
            $data->tanggal_kegiatan_end     = $request->tanggal_kegiatan_end;
            $data->pengambilan_uang_muka    = $request->pengambilan_uang_muka;
            $data->tanggal_pengajuan        = $request->tanggal_pengajuan;
            $data->tanggal_penyelesaian     = $request->tanggal_penyelesaian;

            // Form Perjalanan Menggunakan Pesawat
            $data->tipe_perjalanan       = $request->tipe_perjalanan;
            $data->tanggal_berangkat        = $request->tanggal_berangkat;
            $data->waktu_berangkat          = $request->waktu_berangkat;
            $data->tanggal_pulang           = $request->tanggal_pulang;
            $data->waktu_pulang             = $request->waktu_pulang;
            $data->rute_dari_berangkat        = $request->rute_dari_berangkat;
            $data->rute_tujuan_berangkat      = $request->rute_tujuan_berangkat;
            $data->tipe_kelas_berangkat            = $request->tipe_kelas_berangkat;
            $data->nama_transportasi_berangkat         = $request->nama_transportasi_berangkat;
            $data->status                   = 1;
            $data->others                   = $request->others;
            $data->pergi_bersama            = $request->pergi_bersama;
            $data->note                     = $request->note;
            $data->save();

            $params['data']     = $data;
            $position = \Auth::user()->structure_organization_custom_id;
            $settingApproval = \Auth::user()->approval->id; //idnya
            $settingApprovalItem = \Auth::user()->approval->level1Training->structure_organization_custom_id;

            $historyApproval    = \Auth::user()->approval->itemsTraining;
            foreach ($historyApproval as $key => $value) {
                # code...
                $history = new HistoryApprovalTraining();
                $history->training_id = $data->id;
                $history->setting_approval_level_id = $value->setting_approval_level_id;
                $history->structure_organization_custom_id = $value->structure_organization_custom_id;
                $history->save();
            }
            $historyApprov = HistoryApprovalTraining::where('training_id',$data->id)->get();

            $userApproval = user_approval_custom($settingApprovalItem);
            foreach ($userApproval as $key => $value) { 
                
                if($value->email == "") continue;
                
                $params['data']     = $data;
                $params['value']    = $historyApprov;
                    $params['text']     = '<p><strong>Dear Sir/Madam '. $value->name .'</strong>,</p> <p> '. $data->user->name .'  / '.  $data->user->nik .' applied for Business Trip/Training and currently waiting your approval.</p>';
               \Mail::send('email.training-approval-custom', $params,
                    function($message) use($data, $value) {
                    $message->to($value->email);
                    $message->subject(get_setting('mail_name').' - Business Trip / Training');
                }); 
            }
            return redirect()->route('administrator.training-custom.index')->with('message-success', 'Training succesfully process');
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
        //
        $params['data']         = Training::where('id', $id)->first();

        return view('administrator.training-custom.detail-training')->with($params);
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
        $params['transportationtype'] = TrainingTransportationType::all();
        return view('administrator.training-custom.biaya')->with($params);

    }
     public function prosesclaim(Request $request)
    {
        $data = Training::where('id', $request->id)->first();
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

                if($request->hasFile('file_strukAcomodation'))
                {
                    foreach($request->file_strukAcomodation as $k => $file)
                    {
                        if ($file and $key == $k ) {
                            $image = $file;
                            $name = md5(rand() . $image->getClientOriginalName() . time()) . "." . $image->getClientOriginalExtension();
                            $company_url = session('company_url','umum').'/';
                            $destinationPath = public_path('storage/file-acomodation/').$company_url;
                            $image->move($destinationPath, $name);
                            $acomodation->file_struk = $company_url.$name;
                        }
                    }
                }
                $acomodation->save();
            }
        }

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
                
                if($request->hasFile('file_strukAllowance'))
                {
                    foreach($request->file_strukAllowance as $k => $file)
                    {
                        if ($file and $key == $k ) {
                            $image = $file;
                            $name = md5(rand() . $image->getClientOriginalName() . time()) . "." . $image->getClientOriginalExtension();
                            $company_url = session('company_url','umum').'/';
                            $destinationPath = public_path('storage/file-allowance/').$company_url;
                            $image->move($destinationPath, $name);
                            $form->file_struk = $company_url.$name;
                        }
                    }
                }
                $form->save();
            }
        }

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
                
                if($request->hasFile('file_strukDaily'))
                {
                    foreach($request->file_strukDaily as $k => $file)
                    {
                        if ($file and $key == $k ) {
                            $image = $file;
                            $name = md5(rand() . $image->getClientOriginalName() . time()) . "." . $image->getClientOriginalExtension();
                            $company_url = session('company_url','umum').'/';
                            $destinationPath = public_path('storage/file-daily/').$company_url;
                            $image->move($destinationPath, $name);
                            $daily->file_struk = $company_url.$name;
                        }
                    }
                }
                $daily->save();
            }
        }

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

                if($request->hasFile('file_strukOther'))
                {
                    foreach($request->file_strukOther as $k => $file)
                    {
                        if ($file and $key == $k ) {
                            $image = $file;
                            $name = md5(rand() . $image->getClientOriginalName() . time()) . "." . $image->getClientOriginalExtension();
                            $company_url = session('company_url','umum').'/';
                            $destinationPath = public_path('storage/file-other/').$company_url;
                            $image->move($destinationPath, $name);
                            $other->file_struk = $company_url.$name;
                        }
                    }
                }
                $other->save();
            }
        }
        
        $data->status_actual_bill = $request->status_actual_bill;
        $data->sub_total_1 = $request->sub_total_1;
        $data->sub_total_2 = $request->sub_total_2;
        $data->sub_total_3 = $request->sub_total_3;
        $data->sub_total_4 = $request->sub_total_4;
        $data->date_submit_actual_bill = date('Y-m-d');

        $params['data']     = $data;
        $historyApprov = HistoryApprovalTraining::where('training_id',$data->id)->get();
        $settingApprovalItem = HistoryApprovalTraining::where('training_id',$data->id)->where('setting_approval_level_id',1)->first();

        $userApproval = user_approval_custom($settingApprovalItem);
        foreach ($userApproval as $key => $value) { 
            if($value->email == "") continue;
            $params['data']     = $data;
            $params['value']    = $historyApprov;
                $params['text']     = '<p><strong>Dear Sir/Madam '. $value->name .'</strong>,</p> <p> '. $data->user->name .'  / '.  $data->user->nik .' applied for Claim of Training & Business Trip and currently waiting your approval.</p>';

           \Mail::send('email.training-approval-custom', $params,
                function($message) use($data, $value) {
                $message->to($value->email);
                $message->subject(get_setting('mail_name').' - Business Trip / Training');
            }); 
        }

        $data->save();
        return redirect()->route('administrator.training-custom.index')->with('message-success', 'Data successfully saved!');

    }
}
