<?php

namespace App\Http\Controllers\Karyawan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ExitInterview;
use App\Models\ExitInterviewAssets;
use App\Models\Asset;
use App\Models\AssetTracking;
use App\Models\HistoryApprovalAssetTracking;
use App\User;
use Illuminate\Support\Facades\Config;
use Auth;
use File;

class ApprovalFacilitiesController extends Controller
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
        $user = \Auth::user();
        $pics = \App\Models\SettingApprovalClearance::where('user_id',$user->id)->pluck('nama_approval')->toArray();
        //dd($pics);
        //if(!$approval) return [];
        if(count($pics)>0)
        {
            $type = \App\Models\AssetType::whereIn('pic_department', $pics)->pluck('id')->toArray();
            $params['data'] = AssetTracking::whereHas('asset', function($qry) use($type){
                $qry->whereIn('asset_type_id', $type);
            })->where('is_return', 1)->orderBy('created_at', 'desc')->get();
        }
        else{
            $params['data'] = NULL;
        }
        return view('karyawan.approval-facilities.index')->with($params);
    }

    public function detail($id)
    {
        $params['data'] = AssetTracking::where('id', $id)->first();
        $params['hasApproved'] = HistoryApprovalAssetTracking::where('asset_tracking_id', $id)->first();
        $params['asset_conditions']      = ['Good','Malfunction','Lost'];
        return view('karyawan.approval-facilities.detail')->with($params);
    }

    public function history($id)
    {
        $user = \Auth::user();
        $params['data']         = Asset::where('id', $id)->first();
        $params['history'] = AssetTracking::where('asset_id', $id)->get();
        
        return view('karyawan.approval-facilities.history')->with($params);
    }

    public function proses(Request $request)
    {
        $approval = \App\Models\SettingApprovalClearance::where('user_id', \Auth::user()->id)->first();
        $user = Auth::user();
        if($request->asset_tracking != null) {
            $id = $request->asset_tracking;
            if(!$request->approval_check){
                return redirect()->back()->withErrors(['Approval should be checked!']);
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
            $tracking_new->status_mobil         = $asset->status_mobil;
            $tracking_new->remark               = $asset->remark;
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
                try {
                    \Mail::send('karyawan.approval-facilities.email-facilities', $params,
                        function ($message) use ($file, $tracking) {
                            $message->to($tracking->user->email);
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
                             
            \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $tracking->user->id, $tracking, 'facilities_return_approv');                    

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

        }
        return redirect()->route('karyawan.approval.facilities.index')->with('message-success', 'Approval Facilities succesfully process');
    }
}
