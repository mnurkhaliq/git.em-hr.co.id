<?php

namespace App\Http\Controllers\Api;

use App\Http\Middleware\ConnectionMiddleware;
use App\Http\Resources\AssetResource;
use App\Http\Resources\AssetTrackingResource;
use App\Models\Asset;
use App\Models\AssetTracking;
use App\Models\SettingApprovalClearance;
use App\Models\AssetType;
use App\Models\HistoryApprovalAssetTracking;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use File;
use Illuminate\Support\Facades\Config;

class FacilityManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware(ConnectionMiddleware::class);
        $this->middleware('jwt.auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $user = Auth::user();
        $status    = $request->input('status','[0,1,2,3]');
        if(!isJsonValid($status))
            return response()->json(['status' => 'error','message'=>'Status is invalid'], 404);
        $status    = json_decode($status);
        $histories = Asset::where(['user_id'=>$user->id])->whereIn('status',$status)->orderBy('updated_at','DESC');
        $totalData = $histories->get()->count();
        $histories = $histories->paginate(10);
        $data = [
            'current_page' => $histories->currentPage(), // get current page number
            'total_page' => $histories->total() ? $histories->lastPage() : $histories->total(), // get last page number
            'total_data' => $totalData,
            'assets' => AssetResource::collection($histories),
            'term_agreement' => get_setting('term_and_agreement_asset') == '' ? NULL : get_setting('term_and_agreement_asset'),
        ];
        return response()->json(
            [
                'status' => 'success',
                'message' => 'Successfully',
                'data' => $data
            ], 200);
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
        $user = Auth::user();
        $data['asset'] = new AssetResource(Asset::where(['user_id'=>$user->id])->find($id));
        $data['term_agreement'] = get_setting('term_and_agreement_asset') == '' ? NULL : get_setting('term_and_agreement_asset');
        
        return response()->json(
            [
                'status' => 'success',
                'message' => 'Successfully',
                'data' => $data
            ], 200);
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
        $validator = Validator::make($request->all(), [
            'user_note'     => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()],401);
        }

        $user    = Auth::user();
        $data    = Asset::where(['id' => $id, 'user_id' => $user->id])->first();
        if($data) {
            $data->user_note = $request->user_note;
            $data->user_note_by = $user->id;
            $data->save();
            
            return response()->json(['status' => 'success', 'message' => 'Asset note saved successfully!'],200);
        } else {
            return response()->json(['status' => 'failed', 'message' => 'Asset is not found'],404);
        }
    }

    public function userNote(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'    => 'required|exists:asset,id',
            'user_note'     => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()],401);
        }

        $user    = Auth::user();
        $data    = Asset::where(['id'=> $request->id,'user_id'=>$user->id])->first();
        if($data) {
            $data->user_note = $request->user_note;
            $data->user_note_by = $user->id;
            $data->save();
            
            return response()->json(['status' => 'success', 'message' => 'Asset note saved successfully!'],200);
        } else {
            return response()->json(['status' => 'failed', 'message' => 'Asset is not found'],404);
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

    public function confirm(Request $request){

        $validator = Validator::make($request->all(), [
            'id'    => 'required|exists:asset,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()],401);
        }
        $user    = Auth::user();
        $data    = Asset::where(['id'=> $request->id,'user_id'=>$user->id])->first();
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
                    $params = getEmailConfig();
                        
                    $transport = (new \Swift_SmtpTransport($params['mail_host'], $params['mail_port']))
                            ->setEncryption($params['mail_encryption'])
                            ->setUsername($params['mail_username'])
                            ->setPassword($params['mail_password'])
                            ->setStreamOptions(Config::get('mail.stream'));

                    $mailer = app(\Illuminate\Mail\Mailer::class);
                    $mailer->setSwiftMailer(new \Swift_Mailer($transport));

                    $mailer->send('karyawan.inventory.email-term-of-asset', $params, function ($message) use ($params, $file, $tracking) {
                            $message->to($tracking->user->email);
                            $message->subject('Term and Agreement of Asset');
                            $message->attach($file, array(
                                    'as' => 'Term and Agreement of Asset.pdf',
                                    'mime' => 'application/pdf')
                            );
                            $message->setBody('');
                    });

                    // try {
                    //     \Mail::send('karyawan.inventory.email-term-of-asset', $params,
                    //         function ($message) use ($file, $tracking) {
                    //             $message->to($tracking->user->email);
                    //             $message->subject('Term and Agreement of Asset');
                    //             $message->attach($file, array(
                    //                     'as' => 'Term and Agreement of Asset.pdf',
                    //                     'mime' => 'application/pdf')
                    //             );
                    //             $message->setBody('');
                    //         }
                    //     );
    
                    // }catch (\Swift_TransportException $e){
                    //     response()->json(['status' => 'failed', 'message' => 'Email config is invalid'],404);
                    // }
                }
                
                \FRDHelper::setNewData(strtolower($request->company), $tracking->user->id, $tracking, 'facilities_term_agreement');

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
                            $params = getEmailConfig();
                            
                            $transport = (new \Swift_SmtpTransport($params['mail_host'], $params['mail_port']))
                                ->setEncryption($params['mail_encryption'])
                                ->setUsername($params['mail_username'])
                                ->setPassword($params['mail_password'])
                                ->setStreamOptions(Config::get('mail.stream'));

                            $mailer = app(\Illuminate\Mail\Mailer::class);
                            $mailer->setSwiftMailer(new \Swift_Mailer($transport));

                            $mailer->send('karyawan.inventory.email-term-of-asset', $params, function ($message) use ($params, $file, $v) {
                                $message->to($v->user->email);
                                $message->subject('Term and Agreement of Asset');
                                $message->attach($file, array(
                                            'as' => 'Term and Agreement of Asset.pdf',
                                            'mime' => 'application/pdf')
                                    );
                                $message->setBody('');
                            });

                            // try {
                            //     \Mail::send('karyawan.inventory.email-term-of-asset', $params,
                            //         function ($message) use ($file, $v) {
                            //             $message->to($v->user->email);
                            //             $message->subject('Term and Agreement of Asset');
                            //             $message->attach($file, array(
                            //                     'as' => 'Term and Agreement of Asset.pdf',
                            //                     'mime' => 'application/pdf')
                            //             );
                            //             $message->setBody('');
                            //         }
                            //     );
        
                            // }catch (\Swift_TransportException $e){
                            //     response()->json(['status' => 'failed', 'message' => 'Email config is invalid'],404);
                            // }
                        }
                                        
                        \FRDHelper::setNewData(strtolower($request->company), $v->user->id, $tracking, 'facilities_term_agreement_pic');

                        if(isset($v->user->firebase_token)){
                            $config = [
                                'title' => "Term and Agreement of Asset",
                                'content' => strip_tags('<p>Sir/Madam ' . $tracking->user->name . ' send for accepted Term and Agreement of Asset, check your email for term and agreement.</p>'),
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

            return response()->json(['status' => 'success', 'message' => 'Asset has been accepted!'],200);
        }else{
            return response()->json(['status' => 'failed', 'message' => 'Asset not found or already processed!'],404);
        }
    }

    public function reject(Request $request){

        $validator = Validator::make($request->all(), [
            'id'    => 'required|exists:asset,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()],401);
        }
        $user    = Auth::user();
        $data    = Asset::where(['id'=> $request->id,'user_id'=>$user->id])->first();
        if($data && $data->status == 0) {
            $data->handover_date = date('Y-m-d H:i:s');
            $data->status = 3;
            $data->save();
            $tracking = AssetTracking::whereNull('handover_date')->where(['user_id'=> $data->user_id,'asset_id'=> $data->id])->first();
            if ($tracking) {
                $tracking->delete();
            }

            // racking::whereNull('handover_date')->where('asset_id', $data->id)->delete();
            return response()->json(['status' => 'success', 'message' => 'Asset has been rejected!'],200);
        }else{
            return response()->json(['status' => 'failed', 'message' => 'Asset not found or already processed!'],404);
        }
    }

    public function returnAsset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'    => 'required|exists:asset,id',
            'note_return'     => 'required',
            'asset_condition_return' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()],401);
        }
        $id = $request->id;
        $user    = Auth::user();
        $tracking = AssetTracking::where('asset_id', $id)->where('user_id', $user->id)->orderBy('created_at', 'DESC')->first();
        $tracking->note_return = $request->note_return;
        $tracking->asset_condition_return = $request->asset_condition_return;
        $tracking->is_return = 1;
        $tracking->date_return = date('Y-m-d H:i:s');
        $tracking->status_return = 0;
        $tracking->save();

        if($tracking) {
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
                            $params = getEmailConfig();
                            
                            $transport = (new \Swift_SmtpTransport($params['mail_host'], $params['mail_port']))
                                ->setEncryption($params['mail_encryption'])
                                ->setUsername($params['mail_username'])
                                ->setPassword($params['mail_password'])
                                ->setStreamOptions(Config::get('mail.stream'));

                            $mailer = app(\Illuminate\Mail\Mailer::class);
                            $mailer->setSwiftMailer(new \Swift_Mailer($transport));

                            $mailer->send('karyawan.inventory.email-facilities', $params, function ($message) use ($params, $file, $v) {
                                $message->to($v->user->email);
                                $message->subject('Approval Facilities');
                                $message->attach($file, array(
                                            'as' => 'Approval Facilities.pdf',
                                            'mime' => 'application/pdf')
                                    );
                                $message->setBody('');
                            });

                            // try {
                            //     \Mail::send('karyawan.inventory.email-facilities', $params,
                            //         function ($message) use ($file, $v) {
                            //             $message->to($v->user->email);
                            //             $message->subject('Approval Facilities');
                            //             $message->attach($file, array(
                            //                     'as' => 'Approval Facilities.pdf',
                            //                     'mime' => 'application/pdf')
                            //             );
                            //             $message->setBody('');
                            //         }
                            //     );
            
                            // }catch (\Swift_TransportException $e){
                            //     response()->json(['status' => 'failed', 'message' => 'Email config is invalid'],404);
                            // }
                        }
                    
                        \FRDHelper::setNewData(strtolower($request->company), $v->user->id, $tracking, 'facilities_return');                    

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
            
        return response()->json(['status' => 'success', 'message' => 'Asset successfully process for return.'],200);
        } else {
            return response()->json(['status' => 'failed', 'message' => 'Asset is not found'],404);
        }
    }

    public function indexApproval(Request $request){
        $user = Auth::user();
        $pics = SettingApprovalClearance::where('user_id',$user->id)->pluck('nama_approval')->toArray();
        //dd($pics);
        //if(!$approval) return [];
        if(count($pics)>0)
        {
            $type = AssetType::whereIn('pic_department', $pics)->pluck('id')->toArray();

            if($request->status == 'ongoing'){
                $asset = AssetTracking::whereHas('asset', function($qry) use($type){
                    $qry->whereIn('asset_type_id', $type)->where('status', 2);
                })->where('is_return', 1)->where('status_return', 0)->orderBy('created_at', 'desc');
            }
            elseif($request->status == 'history'){
                $asset = AssetTracking::whereHas('asset', function($qry) use($type){
                    $qry->whereIn('asset_type_id', $type);
                })->where('is_return', 1)->where('status_return', 1)->orderBy('created_at', 'desc');
            }
            else{
                $asset = AssetTracking::whereHas('asset', function($qry) use($type){
                    $qry->whereIn('asset_type_id', $type);
                })->where('is_return', 1)->orderBy('created_at', 'desc');
            }

            $totalData = $asset->get()->count();
            $histories = $asset->paginate(10);
            $data = [
                'current_page' => $histories->currentPage(), // get current page number
                'total_page' => $histories->total() ? $histories->lastPage() : $histories->total(), // get last page number
                'total_data' => $totalData,
                'assets' => AssetTrackingResource::collection($histories)
            ];
        }
        else{
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'No data',
                    'data' => []
                ], 200);
        }

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Successfully get data approval asset return',
                'data' => $data
            ], 200);
    }

    public function showApproval($id)
    {
        $user = Auth::user();
        $data['asset_tracking'] = new AssetTrackingResource(AssetTracking::where('id', $id)->first());
        //$data['hasApproved'] = HistoryApprovalAssetTracking::where('asset_tracking_id', $id)->first();
        $data['asset_conditions']      = ['Good','Malfunction','Lost'];

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Successfully get detail data',
                'data' => $data
            ], 200);
    }

    public function historyAsset($asset_id)
    {
        $user = Auth::user();
        $history = AssetTracking::where('asset_id', $asset_id)->get();
        $data['asset']         =  new AssetResource(Asset::where(['user_id'=>$user->id])->find($asset_id));
        $data['history'] = AssetTrackingResource::collection($history);

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Successfully',
                'data' => $data
            ], 200);
    }

    public function prosesApproval(Request $request){

        $validator = Validator::make($request->all(), [
            'asset_tracking_id'    => 'required|exists:asset_tracking,id',
            'approval_check'     => 'required',
            'note' => 'required',
            'asset_condition_return' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()],401);
        }

        $id = $request->asset_tracking_id;

        $user = Auth::user();
        $approval = SettingApprovalClearance::where('user_id', $user->id)->first();

        if($id != null) {

            if(!$request->approval_check){
                response()->json(['status' => 'failed', 'message' => 'Approval should be checked!'],404);
            }

            $cek = HistoryApprovalAssetTracking::where('asset_tracking_id', $id)->first();

            if($cek == NULL){
                $approval                = new HistoryApprovalAssetTracking;
                $approval->asset_tracking_id = $id;
                $approval->setting_approval_level_id        = 1;
                $approval->structure_organization_custom_id = $user->structure_organization_custom_id;
                $approval->approval_id   = \Auth::user()->id;
                $approval->is_approved   = $request->approval_check;
                $approval->date_approved = date('Y-m-d H:i:s');
                $approval->note          = $request->note;
                $approval->save();
            }
            else{
                $approval = $cek;
            }

            $tracking = AssetTracking::find($id);
            $tracking->status_return = $request->approval_check;
            $tracking->date_return = date('Y-m-d H:i:s');
            $tracking->assign_to  = 'Office Inventory/Idle';
            $tracking->asset_condition_return = $request->asset_condition_return;
            $tracking->save();

            $asset = Asset::find($tracking->asset_id);
            $asset->status = 1;
            $asset->handover_date = date('Y-m-d H:i:s');
            $asset->user_id = \Auth::user()->id;
            $asset->assign_to  = 'Office Inventory/Idle';
            $asset->user_note = $tracking->note_return;
            $asset->asset_condition = $tracking->asset_condition_return;
            $asset->user_note_by = $tracking->user_id;
            $asset->admin_note = $request->note;
            $asset->save();

            $tracking_new                   = new AssetTracking();
            $tracking_new->asset_number     = $asset->asset_number; 
            $tracking_new->asset_name       = $asset->asset_name;
            $tracking_new->asset_type_id    = $asset->asset_type_id;
            $tracking_new->asset_sn         = $asset->asset_sn;
            $tracking_new->purchase_date    = date('Y-m-d', strtotime($asset->purchase_date));
            $tracking_new->handover_date    = date('Y-m-d H:i:s');
            $tracking_new->asset_condition  = $asset->asset_condition;
            $tracking_new->assign_to        = $asset->assign_to;
            $tracking_new->user_id          = $asset->user_id;
            $tracking_new->asset_id         = $asset->id;
            $tracking_new->status_mobil     = $asset->status_mobil;
            $tracking_new->remark           = $asset->remark;
            $tracking_new->save();

            $params['tracking'] = $tracking;
            $params['asset'] = $asset;
            $params['approval'] = $approval;

            $view = view('karyawan.approval-facilities.print-facilities')->with($params);

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
            $objDemo->content = view('karyawan.approval-facilities.print-facilities');

            if ($tracking->user->email != "") {
                $params = getEmailConfig();
                        
                $transport = (new \Swift_SmtpTransport($params['mail_host'], $params['mail_port']))
                            ->setEncryption($params['mail_encryption'])
                            ->setUsername($params['mail_username'])
                            ->setPassword($params['mail_password'])
                            ->setStreamOptions(Config::get('mail.stream'));

                $mailer = app(\Illuminate\Mail\Mailer::class);
                $mailer->setSwiftMailer(new \Swift_Mailer($transport));

                $mailer->send('karyawan.approval-facilities.email-facilities', $params, function ($message) use ($params, $file, $tracking) {
                            $message->to($tracking->user->email);
                            $message->subject('Approval Facilities');
                            $message->attach($file, array(
                                        'as' => 'Approval Facilities.pdf',
                                        'mime' => 'application/pdf')
                                );
                            $message->setBody('');
                });

                // try {
                //     \Mail::send('karyawan.approval-facilities.email-facilities', $params,
                //         function ($message) use ($file, $tracking) {
                //             $message->to($tracking->user->email);
                //             $message->subject('Approval Facilities');
                //             $message->attach($file, array(
                //                     'as' => 'Approval Facilities.pdf',
                //                     'mime' => 'application/pdf')
                //             );
                //             $message->setBody('');
                //         }
                //     );
                // }catch (\Swift_TransportException $e){
                //     response()->json(['status' => 'failed', 'message' => 'Email config is invalid'],404);
                // }
            }
                             
            \FRDHelper::setNewData(strtolower($request->company), $tracking->user->id, $tracking, 'facilities_return_approv');                    

            if($tracking->user->firebase_token){
                $config = [
                    'title' => "Facilities Return",
                    'content' => strip_tags('<p><strong>Dear Sir/Madam ' . $tracking->user->name . '</strong>,</p> <p>  Your facilities return has been processed by admin. Please check your email.</p>'),
                    'type' => "facilities_return_approv",
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
            return response()->json(['status' => 'success', 'message' => 'Approval Facilitiess succesfully process.'],200);
        }
        else {
            return response()->json(['status' => 'failed', 'message' => 'Asset tracking is not found'],404);
        }
    }
}
