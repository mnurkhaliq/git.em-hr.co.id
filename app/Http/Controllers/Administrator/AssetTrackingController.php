<?php

namespace App\Http\Controllers\Administrator;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AssetTracking;

class AssetTrackingController extends Controller
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
            $data   = AssetTracking::orderBy('asset_tracking.id', 'DESC')->join('users','users.id','=','asset_tracking.user_id')->where('users.project_id', $user->project_id)->select('asset_tracking.*');
        }else {
           $data   = AssetTracking::orderBy('id', 'DESC');
        }

        $params['asset_type_id']    = null;
        $params['asset_condition']  = null;
        $params['assign_to']        = null;
        $params['asset_number']     = null;
        $params['serial_number']    = null;

        if(count(request()->all())) {
            \Session::put('at-asset_type_id', request()->asset_type_id);
            \Session::put('at-asset_condition', request()->asset_condition);
            \Session::put('at-assign_to', request()->assign_to);
            \Session::put('at-user_id', request()->user_id);
            \Session::put('at-asset_number', request()->asset_number);
            \Session::put('at-serial_number', request()->serial_number);
        }

        $params['asset_type_id']    = \Session::get('at-asset_type_id');
        $params['asset_condition']  = \Session::get('at-asset_condition');
        $params['assign_to']        = \Session::get('at-assign_to');
        $params['user_id']          = \Session::get('at-user_id');
        $params['asset_number']     = \Session::get('at-asset_number');
        $params['serial_number']    = \Session::get('at-serial_number');

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
            $params['assign_to'] = $_GET['assign_to'];
        }

        if(!empty($params['user_id']))
        {
            $data = $data->where('asset_tracking.user_id', $params['user_id']);
            $params['user'] = User::find($params['user_id']);
        }

        if(!empty($_GET['asset_number']))
        {
            $data = $data->whereHas('asset', function($qry) use($params){
                $qry->where('asset_number', 'LIKE', '%'.$params['asset_number'].'%');
            });
            $params['asset_number'] = $params['asset_number'];
        }

        if(!empty($_GET['serial_number']))
        {
            $data = $data->whereHas('asset', function($qry) use($params){
                $qry->where('asset_sn', 'LIKE', '%'.$params['serial_number'].'%');
            });
            $params['serial_number'] = $params['serial_number'];
        }

        $params['data'] = $data->get();

        return view('administrator.asset-tracking.index')->with($params);
    }
}
