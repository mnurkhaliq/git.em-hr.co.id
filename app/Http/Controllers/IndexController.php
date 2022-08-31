<?php

namespace App\Http\Controllers;

use App\Models\ConfigDB;
use App\Models\Asset;
use App\Models\AssetTracking;
use App\Models\SettingApprovalClearance;
use Illuminate\Support\Facades\Config;
use File;

class IndexController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth',['except' => ['acceptAsset']]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $params = [];
        return view('home');
    }

    /**
     * [acceptAsset description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function acceptAsset($id,$company='')
    {
        if($company!=''){
            $config = ConfigDB::where('company_code',strtolower($company))->where('due_date', '>=', \Carbon\Carbon::now()->startOfDay())->first();
            if($config){
                Config::set('database.default',$config->db_name);
            }
        }
        $data                   = Asset::where('encrypted_key', $id)->first();
        if($data && $data->status == 0) {
            $data->handover_date = date('Y-m-d H:i:s');
            $data->status = 1;
            $data->save();

            // Update Asset Tracking
            //$tracking = AssetTracking::where('asset_id', $data->id)->first();
            $tracking = AssetTracking::whereNull('handover_date')->where('user_id', $data->user_id)->where('asset_id', $data->id)->first();
            if ($tracking) {
                $tracking->handover_date = $data->handover_date;
                $tracking->save();
            }

            $trackingOther = AssetTracking::whereNull('handover_date')->where('asset_id', $data->id)->delete();
            //if ($tracking) {
              //  $tracking->delete();
            //}

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

            return view('accept-asset');
        }
        else{
            return view('no-asset');
        }
    }

    public function rejectAsset($id,$company='')
    {
        if($company!=''){
            $config = ConfigDB::where('company_code',strtolower($company))->where('due_date', '>=', \Carbon\Carbon::now()->startOfDay())->first();
            if($config){
                Config::set('database.default',$config->db_name);
            }
        }
        $data                   = Asset::where('encrypted_key', $id)->first();
        if($data && $data->status == 0) {
            $data->handover_date = date('Y-m-d H:i:s');
            $data->status = 3;
            $data->save();
            $tracking = AssetTracking::whereNull('handover_date')->where(['user_id'=> $data->user_id,'asset_id'=> $data->id])->first();
            if ($tracking) {
                $tracking->delete();
            }

            return view('reject-asset');
        }
        else{
            return view('no-asset');
        }
    }
}
