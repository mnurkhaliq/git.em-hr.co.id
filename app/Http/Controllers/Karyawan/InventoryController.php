<?php

namespace App\Http\Controllers\Karyawan;

use App\Models\Asset;
use App\Models\AssetTracking;
use App\Models\SettingApprovalClearance;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use ZipArchive;
use File;
use Illuminate\Support\Facades\Config;

class InventoryController extends Controller
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


    public function index(Request $request)
    {
        $params['data']             = User::where('id', \Auth::user()->id)->with(['assets' => function($qry) {
            $qry->orderBy('updated_at','DESC');
        }])->first();
        return view('karyawan.inventory.index')->with($params);
    }
    
    public function confirmAsset($id){
        $data    = Asset::where(['id'=> $id])->first();
        if($data && $data->status == 0) {
            $data->handover_date = date('Y-m-d H:i:s');
            $data->status = 1;
            $data->save();
            $tracking = AssetTracking::whereNull('handover_date')->where(['user_id'=> $data->user_id,'asset_id'=> $data->id])->first();
            if ($tracking) {
                $tracking->handover_date = $data->handover_date;
                $tracking->save();
            }

            AssetTracking::whereNull('handover_date')->where('asset_id', $data->id)->delete();

            $dataSetting = SettingApprovalClearance::where('nama_approval',$data->asset_type->pic_department)->get();

            if($dataSetting != null){

                $params['tracking'] = $tracking;
                $params['asset'] = $data;

                $view = view('karyawan.inventory.term-of-asset')->with($params);

                $pdf = \App::make('dompdf.wrapper');
                $pdf->loadHTML($view);
                info("Loading View to PDF : ");
                $pdf->stream();
                info("PDF Streamed : ");
                $output = $pdf->output();
                info("PDF CREATED : ");

                $destinationPath = public_path('/storage/temp/facilities/termAsset/');

                if (!File::exists($destinationPath)) {
                    $path = public_path() . '/storage/temp/facilities/termAsset/';
                    File::makeDirectory($destinationPath, $mode = 0777, true, true);
                }

                file_put_contents($destinationPath . $tracking->user->nik . '.pdf', $output);

                $file = $destinationPath . $tracking->user->nik . '.pdf';
                info("Facilities PDF WRITTEN : ".$tracking->user->name);
                // send email
                $objDemo = new \stdClass();
                $objDemo->content = view('karyawan.inventory.term-of-asset');
                //dd($file);
                if ($tracking->user->email != "") {
                    try {
                        \Mail::send('karyawan.inventory.email-term-of-asset', $params,
                            function ($message) use ($file, $tracking) {
                                $message->to($tracking->user->email);
                                $message->subject('Term and Agreement of Asset');
                                $message->attach($file, array(
                                        'as' => 'Term and Agreement of Asset.pdf',
                                        'mime' => 'application/pdf')
                                );
                                $message->setBody('');
                            }
                        );
                    }catch (\Swift_TransportException $e){
                        return redirect()->back()->with('message-error', 'Email config is invalid!');
                    }
                }
                
                \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $tracking->user->id, $tracking, 'facilities_term_agreement');

                if($tracking->user->firebase_token){
                    $config = [
                        'title' => "Term and Agreement of Asset",
                        'content' => strip_tags('<p><strong>Dear Sir/Madam ' . $tracking->user->name . '</strong>,</p> <p> Asset has been accepted, check your email for term and agreement.</p>'),
                        'type' => "facilities_term_agreement",
                        'firebase_token' => $tracking->user->firebase_token
                    ];
                    $notifData = [
                        'id' => $tracking->asset_id
                    ];
                    $db = Config::get('database.default', 'mysql');
                    Config::set('database.default', 'mysql');
                    dispatch((new \App\Jobs\SendPushTokens($notifData,$config))->onQueue('push'));
                    Config::set('database.default', $db);
                }

                foreach ($dataSetting as $k => $v) {
                    if ($v->user) {
                        if (isset($v->user->email)) {
                            try {
                                \Mail::send('karyawan.inventory.email-term-of-asset', $params,
                                    function ($message) use ($file, $v) {
                                        $message->to($v->user->email);
                                        $message->subject('Term and Agreement of Asset');
                                        $message->attach($file, array(
                                                'as' => 'Term and Agreement of Asset.pdf',
                                                'mime' => 'application/pdf')
                                        );
                                        $message->setBody('');
                                    }
                                );
                            }catch (\Swift_TransportException $e){
                                return redirect()->back()->with('message-error', 'Email config is invalid!');
                            }
                        }
                                        
                        \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $v->user->id, $tracking, 'facilities_term_agreement_pic');

                        if(isset($v->user->firebase_token)){
                            $config = [
                                'title' => "Term and Agreement of Asset",
                                'content' => strip_tags('<p>Sir/Madam ' . $tracking->user->name . ' send for accepted Term and Agreement of Asset.</p>'),
                                'type' => "facilities_term_agreement_pic",
                                'firebase_token' => $v->user->firebase_token
                            ];
                            $notifData = [
                                'id' => $tracking->asset_id
                            ];
                            $db = Config::get('database.default', 'mysql');
                            Config::set('database.default', 'mysql');
                            dispatch((new \App\Jobs\SendPushTokens($notifData,$config))->onQueue('push'));
                            Config::set('database.default', $db);
                        }
                    }
                }
            }

            return \Redirect::route('karyawan.facilities.index')->with('message-success', 'Asset has been accepted!');
        }else{
            return redirect()->back()->with('message-success', 'Asset not found or already processed!');
        }
    }

    public function rejectAsset($id){
        $data    = Asset::where(['id'=> $id])->first();
        if($data && $data->status == 0) {
            $data->handover_date = date('Y-m-d H:i:s');
            $data->status = 3;
            $data->save();
            $tracking = AssetTracking::whereNull('handover_date')->where(['user_id'=> $data->user_id,'asset_id'=> $data->id])->first();
            if ($tracking) {
                $tracking->delete();
            }

            //AssetTracking::whereNull('handover_date')->where('asset_id', $data->id)->delete();
            return \Redirect::route('karyawan.facilities.index')->with('message-success', 'Asset has been rejected!');
        }else{
            return redirect()->back()->with('message-success', 'Asset not found or already processed!');
        }
    }

    public function updateNote(Request $request) {
        $data = Asset::find($request->id_note);
        if($data) {
            $data->user_note = $request->user_note;
            $data->user_note_by = \Auth::user()->id;
            $data->save();
            
            return \Redirect::route('karyawan.facilities.index')->with('message-success', 'Asset note saved successfully');
        } else {
            return redirect()->back()->with('message-error', 'Asset is not found!');
        }
    }

    public function show($id)
    { 
        //$data    = Asset::where(['id'=> $id])->first();
        //dd($data);
        $params['data']             = Asset::where(['id'=> $id])->first();
        $params['asset_conditions']      = ['Good','Malfunction','Lost'];
        $params['tracking']  = AssetTracking::where('asset_id', $id)->where('user_id', auth()->user()->id)->orderBy('created_at', 'DESC')->first();
        //dd($params);
        return view('karyawan.inventory.detail')->with($params);
    }


    public function edit($id)
    { 
        //$data    = Asset::where(['id'=> $id])->first();
        //dd($data);
        $params['data']             = Asset::where(['id'=> $id])->first();
        $params['asset_conditions']      = ['Good','Malfunction','Lost'];
        $params['tracking']  = AssetTracking::where('asset_id', $id)->where('user_id', auth()->user()->id)->orderBy('created_at', 'DESC')->first();
        //dd($params);
        return view('karyawan.inventory.edit')->with($params);
    }

    public function update(Request $request, $id)
    {
        $tracking = AssetTracking::where('asset_id', $id)->where('user_id', auth()->user()->id)->orderBy('created_at', 'DESC')->first();
        $tracking->note_return = $request->note_return;
        $tracking->asset_condition_return = $request->asset_condition_return;
        $tracking->is_return = 1;
        $tracking->date_return = date('Y-m-d H:i:s');
        $tracking->status_return = 0;
        $tracking->save();

        $asset = Asset::find($id);
        $asset->status = 2;
        $asset->save();

        $dataSetting = SettingApprovalClearance::where('nama_approval',$asset->asset_type->pic_department)->get();

        if($dataSetting != null){

            $params['tracking'] = $tracking;
            $params['asset'] = $asset;

            $view = view('karyawan.inventory.print-facilities')->with($params);

            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadHTML($view)->setPaper('a4', 'landscape');
            info("Loading View to PDF : ");
            $pdf->stream();
            info("PDF Streamed : ");
            $output = $pdf->output();
            info("PDF CREATED : ");

            $destinationPath = public_path('/storage/temp/facilities/');

            if (!File::exists($destinationPath)) {
                $path = public_path() . '/storage/temp/facilities/';
                File::makeDirectory($destinationPath, $mode = 0777, true, true);
            }

            file_put_contents($destinationPath . $tracking->user->nik . '.pdf', $output);

            $file = $destinationPath . $tracking->user->nik . '.pdf';
            info("Facilities PDF WRITTEN : ".$tracking->user->name);
            // send email
            $objDemo = new \stdClass();
            $objDemo->content = view('karyawan.inventory.print-facilities');

            foreach ($dataSetting as $k => $v) {
                if ($v->user) {
                    if (isset($v->user->email)) {
                        try {
                            \Mail::send('karyawan.inventory.email-facilities', $params,
                                function ($message) use ($file, $v) {
                                    $message->to($v->user->email);
                                    $message->subject('Approval Facilities');
                                    $message->attach($file, array(
                                            'as' => 'Approval Facilities.pdf',
                                            'mime' => 'application/pdf')
                                    );
                                    $message->setBody('');
                                }
                            );
                        }catch (\Swift_TransportException $e){
                            return redirect()->back()->with('message-error', 'Email config is invalid!');
                        }
                    }
                    
                    \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $v->user->id, $tracking, 'facilities_return');                    

                    if(isset($v->user->firebase_token)){
                        $config = [
                            'title' => "Facilities Return",
                            'content' => strip_tags('<p>Sir/Madam ' . $tracking->user->name . ' applied for facilities return and currently waiting your approval.</p>'),
                            'type' => "facilities_return",
                            'firebase_token' => $v->user->firebase_token
                        ];
                        $notifData = [
                            'id' => $tracking->id
                        ];
                        $db = Config::get('database.default', 'mysql');
                        Config::set('database.default', 'mysql');
                        dispatch((new \App\Jobs\SendPushTokens($notifData,$config))->onQueue('push'));
                        Config::set('database.default', $db);
                    }
                }
            }
        }
        return \Redirect::route('karyawan.facilities.index')->with('message-success', 'Asset successfully process for return.');
        //dd($tracking); 
    }
}
