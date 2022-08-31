<?php

namespace App\Http\Controllers\Administrator;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\AssetType;
use App\Models\AssetTracking;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Config;

class AssetController extends Controller
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
        $this->middleware('module:14');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = \Auth::user();
        if($user->project_id != NULL)
        {
            $data   = Asset::with(['history' => function($qry){
                $qry->orderBy('id', 'DESC');
            }])->orderBy('asset.id', 'DESC')->join('users','users.id','=','asset.user_id')->where('users.project_id', $user->project_id)->select('asset.*');
        }else {
            $data   = Asset::with(['history' => function($qry){
                $qry->orderBy('id', 'DESC');
            }])->orderBy('id', 'DESC');
        }
       
        $params['asset_type_id']    = null;
        $params['asset_condition']  = null;
        $params['assign_to']        = null;
        $params['asset_number']     = null;
        $params['serial_number']    = null;

        if(count(request()->all())) {
            \Session::put('a-asset_type_id', request()->asset_type_id);
            \Session::put('a-asset_condition', request()->asset_condition);
            \Session::put('a-assign_to', request()->assign_to);
            \Session::put('a-user_id', request()->user_id);
            \Session::put('a-asset_number', request()->asset_number);
            \Session::put('a-serial_number', request()->serial_number);
        }

        $params['asset_type_id']    = \Session::get('a-asset_type_id');
        $params['asset_condition']  = \Session::get('a-asset_condition');
        $params['assign_to']        = \Session::get('a-assign_to');
        $params['user_id']          = \Session::get('a-user_id');
        $params['asset_number']     = \Session::get('a-asset_number');
        $params['serial_number']    = \Session::get('a-serial_number');

        if(!empty($params['asset_type_id']))
        {
            $data = $data->where('asset_type_id', $params['asset_type_id']);
            $params['asset_type_id'] = $params['asset_type_id'];
        }

        if(!empty($params['asset_condition']))
        {
            $data = $data->where('asset_condition', $params['asset_condition']);
            $params['asset_condition'] = $params['asset_condition'];
        }

        if(!empty($params['assign_to']))
        {
            $data = $data->where('assign_to', $params['assign_to']);
            $params['assign_to'] = $params['assign_to'];
        }

        if(!empty($params['user_id']))
        {
            $data = $data->where('asset.user_id', $params['user_id']);
            $params['user'] = User::find($params['user_id']);
        }

        if(!empty($params['asset_number']))
        {
            $data = $data->where('asset_number', 'LIKE', '%'.$params['asset_number'].'%');
            $params['asset_number'] = $params['asset_number'];
        }

        if(!empty($params['serial_number']))
        {
            $data = $data->where('asset_sn', 'LIKE', '%'.$params['serial_number'].'%');
            $params['serial_number'] = $params['serial_number'];
        }

        $params['data'] = $data->get();

        return view('administrator.asset.index')->with($params);
    }

    /**
     * [create description]
     * @return [type] [description]
     */
    public function create()
    {   
        $user = \Auth::user();
        if($user->project_id != NULL)
        {
            $params['asset_type']       = AssetType::where('project_id', $user->project_id)->get();
        }else{
            $params['asset_type']       = AssetType::all();
        }
        $params['asset_number']     = $this->asset_number();
        
        return view('administrator.asset.create')->with($params);
    }

    /**
     * [asset_number description]
     * @return [type] [description]
     */
    public function asset_number()
    
    {
        $no = 0;
        $user = \Auth::user();
        if($user->project_id != NULL)
        {
            $count = Asset::join('users', 'asset.user_id', '=', 'users.id')->where('users.project_id', $user->project_id)->count()+1;
        }else{
            $count = Asset::count()+1;
        }

        if(strlen($count) == 1)
        {
            $no = "000". $count;
        }

        if(strlen($count) == 2)
        {
            $no = "00". $count;
        }

        if(strlen($count) == 3)
        {
            $no = "0". $count;
        }

        if(strlen($count) == 4)
        {
            $no = $count;
        }

        return $no;
    }

    /**
     * [edit description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function edit($id)
    {
        $user = \Auth::user();
        $params['data']         = Asset::where('id', $id)->first();
        $params['history'] = AssetTracking::where('asset_id', $id)->get();
        if($user->project_id != NULL)
        {
            $params['asset_type']       = AssetType::where('project_id', $user->project_id)->get();
        }else{
            $params['asset_type']       = AssetType::all();
        }
        $params['asset_number']     = $this->asset_number();
        
        return view('administrator.asset.edit')->with($params);
    }

    public function show($id)
    {
        $user = \Auth::user();
        $params['data']         = Asset::where('id', $id)->first();
        $params['history'] = AssetTracking::where('asset_id', $id)->get();
        
        return view('administrator.asset.detail')->with($params);
    }

    /**
     * [update description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function update(Request $request, $id)
    {
        $data                   = Asset::where('id', $id)->first();
        $pic_id_previous = $data->pic_id;
        $user_id_previous = $data->user_id;
        $status_previous = $data->status;
        $data->asset_name       = $request->asset_name;
        $data->asset_type_id    = $request->asset_type_id;
        $data->asset_sn         = $request->asset_sn;
        $data->purchase_date    = date('Y-m-d', strtotime($request->purchase_date));
        $data->asset_condition  = $request->asset_condition;
        $data->assign_to        = $request->assign_to;
        if($data->user_id != $request->user_id){
            $data->user_id          = $request->user_id;
            $data->handover_date    = NULL;
            $data->status           = 0;
        }
        if($data->user_id == $request->user_id && $data->status==3){
            $data->handover_date    = NULL;
            $data->status           = 0;
        }
        $data->pic_id           = $request->pic_id;
        $data->spesifikasi      = $request->spesifikasi;
        //$data->tipe_mobil       = $request->tipe_mobil;
        //$data->tahun            = $request->tahun;
        //$data->no_polisi        = $request->no_polisi;
        $data->status_mobil     = $request->status_mobil;
        $data->remark           = $request->remark;
        $data->admin_note       = $request->admin_note;
        $data->encrypted_key    = Str::random(32);
        //$data->rental_date      = $request->rental_date;
        $data->save();

        //dd($pic_id_previous);
        $tracking_other = AssetTracking::where('asset_id', $data->id)->where('pic_id', NULL)->get();
        foreach($tracking_other as $to){
            $cek = AssetTracking::find($to->id);
            $cek->pic_id = $pic_id_previous != $data->pic_id ? $pic_id_previous : NULL;
            $cek->save();
        }

        $tracking_user = AssetTracking::where('asset_id', $data->id)->orderBy('id', 'DESC')->first();
        //dd($tracking_user);
        if(($tracking_user) && $tracking_user->user_id != $data->user_id){
            $tracking                   = new AssetTracking();
            $tracking->asset_number     = $data->asset_number; 
            $tracking->asset_name       = $data->asset_name;
            $tracking->asset_type_id    = $data->asset_type_id;
            $tracking->asset_sn         = $data->asset_sn;
            $tracking->purchase_date    = date('Y-m-d', strtotime($data->purchase_date));
            $tracking->asset_condition  = $data->asset_condition;
            $tracking->assign_to        = $data->assign_to;
            $tracking->pic_id           = $pic_id_previous != $data->pic_id ? $data->pic_id : NULL ;
            $tracking->user_id          = $data->user_id;
            $tracking->asset_id         = $data->id;
            //$data->tipe_mobil           = $request->tipe_mobil;
            //$data->tahun                = $request->tahun;
            //$data->no_polisi            = $request->no_polisi;
            $data->status_mobil         = $request->status_mobil;
            $data->remark           = $request->remark;
            //$data->rental_date      = $request->rental_date;
            $tracking->save();
        }
        elseif($tracking_user){
            $tracking_user->pic_id = $data->pic_id;
            $tracking_user->save();
        }
        else{
            $tracking                   = new AssetTracking();
            $tracking->asset_number     = $data->asset_number; 
            $tracking->asset_name       = $data->asset_name;
            $tracking->asset_type_id    = $data->asset_type_id;
            $tracking->asset_sn         = $data->asset_sn;
            $tracking->purchase_date    = date('Y-m-d', strtotime($data->purchase_date));
            $tracking->asset_condition  = $data->asset_condition;
            $tracking->assign_to        = $data->assign_to;
            $tracking->pic_id           = $pic_id_previous != $data->pic_id ? $data->pic_id : NULL ;
            $tracking->user_id          = $data->user_id;
            $tracking->asset_id         = $data->id;
            //$data->tipe_mobil           = $request->tipe_mobil;
            //$data->tahun                = $request->tahun;
            //$data->no_polisi            = $request->no_polisi;
            $data->status_mobil         = $request->status_mobil;
            $data->remark           = $request->remark;
            //$data->rental_date      = $request->rental_date;
            $tracking->save();
        }

        $params['data']         = Asset::where('id', $data->id)->first();

        if ($user_id_previous != $request->user_id || ($user_id_previous == $request->user_id && $status_previous==3)) {

            \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $data->user->id, $data, 'asset');

            if($data->user->firebase_token){
                $content = "Dear Sir/Madam ".$data->user->name.", an asset is waiting for your acceptance";
                $config = [
                    'title' => "Asset Acceptance",
                    'content' => $content,
                    'type' => "asset",
                    'firebase_token' => [$data->user->firebase_token]
                ];
                $notifData = [
                    'id' => $data->id
                ];
                $db = Config::get('database.default', 'mysql');
                Config::set('database.default', 'mysql');
                dispatch((new \App\Jobs\SendPushTokens($notifData,$config))->onQueue('push'));
                Config::set('database.default', $db);
            }
        }

        if($data->user->email != "" && ($user_id_previous != $request->user_id || ($user_id_previous == $request->user_id && $status_previous==3)))
        { 
            try {
                \Mail::send('administrator.asset.acceptance-email', $params,
                    function ($message) use ($data) {
                        $message->to($data->user->email);
                        $message->subject('Empore - Asset Acceptance Confirmation');
                    }
                );
            }catch (\Swift_TransportException $e){
                return redirect()->back()->with('message-error', 'Email config is invalid!');
            }
        }
        
        if($data->pic->email && ($pic_id_previous != $data->pic_id)){
            try {
                \Mail::send('administrator.asset.pic-email', $params,
                    function ($message) use ($data) {
                        $message->to($data->pic->email);
                        $message->subject('Assiged as PIC Asset');
                    }
                );
            }catch (\Swift_TransportException $e){
                return redirect()->back()->with('message-error', 'Email config is invalid!');
            }
        }

        return redirect()->route('administrator.asset.index')->with('message-success', 'Data saved successfully');
    }   

    /**
     * [desctroy description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function destroy($id)
    {
        $data = Asset::where('id', $id)->first();
        $data->delete();

        return redirect()->route('administrator.asset.index')->with('message-success', 'Data deleted successfully');
    } 

    /**
     * [store description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function store(Request $request)
    {
        $data       = new Asset();
        $data->asset_number     = $request->asset_number; 
        $data->asset_name       = $request->asset_name;
        $data->asset_type_id    = $request->asset_type_id;
        $data->asset_sn         = $request->asset_sn;
        $data->purchase_date    = date('Y-m-d', strtotime($request->purchase_date));
        $data->handover_date    = NULL;
        $data->status           = 0;
        $data->asset_condition  = $request->asset_condition;
        $data->assign_to        = $request->assign_to;
        $data->pic_id           = $request->pic_id;
        $data->user_id          = $request->user_id;
        $data->spesifikasi      = $request->spesifikasi;
        //$data->tipe_mobil       = $request->tipe_mobil;
        //$data->tahun            = $request->tahun;
        //$data->no_polisi        = $request->no_polisi;
        $data->status_mobil     = $request->status_mobil;
        $data->remark           = $request->remark;
        $data->admin_note       = $request->admin_note;
        $data->encrypted_key    = Str::random(32);
        //$data->rental_date      = $request->rental_date;
        $data->save();

        $tracking                   = new AssetTracking();
        $tracking->asset_name       = $data->asset_name;
        $tracking->asset_type_id    = $data->asset_type_id;
        $tracking->asset_sn         = $data->asset_sn;
        $tracking->purchase_date    = date('Y-m-d', strtotime($data->purchase_date));
        $tracking->asset_condition  = $data->asset_condition;
        $tracking->assign_to        = $data->assign_to;
        $tracking->pic_id           = $data->pic_id;
        $tracking->user_id          = $data->user_id;
        $tracking->asset_id         = $data->id;
        //$data->tipe_mobil           = $request->tipe_mobil;
        //$data->tahun                = $request->tahun;
        //$data->no_polisi            = $request->no_polisi;
        $data->status_mobil         = $request->status_mobil;
        $data->remark               = $request->remark;
        //$data->rental_date          = $request->rental_date;
        $tracking->save();
        
        $params['data']         = Asset::where('id', $data->id)->first();

        \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $data->user->id, $data, 'asset');

        if($data->user->firebase_token){
            $content = "Dear Sir/Madam ".$data->user->name.", an asset is waiting for your acceptance";
            $config = [
                'title' => "Asset Acceptance",
                'content' => $content,
                'type' => "asset",
                'firebase_token' => [$data->user->firebase_token]
            ];
            $notifData = [
                'id' => $data->id
            ];
            $db = Config::get('database.default', 'mysql');
            Config::set('database.default', 'mysql');
            dispatch((new \App\Jobs\SendPushTokens($notifData,$config))->onQueue('push'));
            Config::set('database.default', $db);
        }

        if($data->user->email != "")
        {
            try {
                \Mail::send('administrator.asset.acceptance-email', $params,
                    function ($message) use ($data) {
                        $message->to($data->user->email);
                        $message->subject('Empore - Asset Acceptance Confirmation');
                    }
                );
            }catch (\Swift_TransportException $e){
                return redirect()->back()->with('message-error', 'Email config is invalid!');
            }
        }

        if($data->pic->email){
            try {
                \Mail::send('administrator.asset.pic-email', $params,
                    function ($message) use ($data) {
                        $message->to($data->pic->email);
                        $message->subject('Assiged as PIC Asset');
                    }
                );
            }catch (\Swift_TransportException $e){
                return redirect()->back()->with('message-error', 'Email config is invalid!');
            }
        }

        return redirect()->route('administrator.asset.index')->with('message-success', 'Data saved successfully !');
    }
}
