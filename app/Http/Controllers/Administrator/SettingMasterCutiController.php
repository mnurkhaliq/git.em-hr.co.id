<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cuti;
use App\Models\MasterCutiType;
use App\User;
use App\Models\UserCuti;

class SettingMasterCutiController extends Controller
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
        $this->middleware('module:4');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $params['type'] = MasterCutiType::orderBy('id', 'ASC')->get();
        $user = \Auth::user();
        if($user->project_id != NULL)
        {   
            $params['data'] = Cuti::with('cutiname')->get();
        }else{
            $params['data'] = Cuti::with('cutiname')->get();
        }

        $params['range'] = [
            'max_leave_range' => get_setting('max_leave_range'),
            'min_leave_range' => get_setting('min_leave_range'),
        ];

        return view('administrator.setting-master-cuti.index')->with($params);
    }

    /**
     * [create description]
     * @return [type] [description]
     */
    public function create()
    {   
        $params['type']    = MasterCutiType::all();
        //dd($type);
        return view('administrator.setting-master-cuti.create')->with($params);
    }

    /**
     * [edit description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function edit($id)
    {
        $params['type']    = MasterCutiType::all();
        $params['data']         = Cuti::where('id', $id)->first();

        return view('administrator.setting-master-cuti.edit')->with($params);
    }

    /**
     * [update description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function update(Request $request, $id)
    {
        $data                       = Cuti::where('id', $id)->first();
        $data->kuota                = $request->kuota;
        $data->master_cuti_type_id  = $request->metodeperhitungan_cuti;
        $data->cutoffmonth          = $request->cutoffmonth;
        $data->iscarryforward       = $request->has('iscarryforward');
        $data->carryforwardleave    = $request->carryforwardleave;
        $data->is_attachment        = $request->is_attachment;
              
        $data->save();


        return redirect()->route('administrator.setting-master-cuti.index')->with('message-success', 'Data successfully saved');
    }   

    /**
     * [delete description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function delete($id)
    {
        $data = Cuti::where('id', $id)->first();
        $data->delete();

        return redirect()->route('administrator.setting-master-cuti.index')->with('message-success', 'Data successfully deleted');
    } 

    /**
     * [desctroy description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function destroy($id)
    {
        $data = Cuti::where('id', $id)->first();
        $data->delete();

        return redirect()->route('administrator.setting-master-cuti.index')->with('message-success', 'Data successfully deleted');
    } 

    /**
     * [store description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function store(Request $request)
    {
        $data                       = new Cuti();
        $data->jenis_cuti           = $request->jenis_cuti;
        $data->kuota                = $request->kuota;
        $data->master_cuti_type_id  = $request->metodeperhitungan_cuti;
        $data->cutoffmonth          = $request->cutoffmonth;
        $data->iscarryforward       = $request->has('iscarryforward');
        $data->carryforwardleave    = $request->carryforwardleave;
        $data->description          = $request->description;
        $data->is_attachment        = $request->is_attachment;
        
        $user = \Auth::user();
        if($user->project_id != NULL)
        {
            $data->user_created = $user->id;
        } 
        $data->save();

        if($data->jenis_cuti == 'Special Leave')
          {
                if($user->project_id != NULL)
                {
                    $dataUser = User::whereIn('access_id',[1,2])->where(function($query) {
                        $query->whereNull('non_active_date')->orWhere('non_active_date', '>', \Carbon\Carbon::now());
                    })->where(function($query) {
                        $query->whereNull('join_date')->orWhere('join_date', '<=', \Carbon\Carbon::now());
                    })->where('project_id',$user->project_id)->get();
                }else{
                    $dataUser = User::whereIn('access_id',[1,2])->where(function($query) {
                        $query->whereNull('non_active_date')->orWhere('non_active_date', '>', \Carbon\Carbon::now());
                    })->where(function($query) {
                        $query->whereNull('join_date')->orWhere('join_date', '<=', \Carbon\Carbon::now());
                    })->get();
                }
                foreach ($dataUser as $key => $value) {
                    # code...
                    $userCuti = UserCuti::where('user_id',$value->id)->where('cuti_id',$data->id)->first();
                    if(!$userCuti)
                    {
                        $c = new UserCuti();
                        $c->user_id     = $value->id;
                        $c->cuti_id     = $data->id;
                        $c->kuota       = $data->kuota;
                        $c->sisa_cuti   = $data->kuota;
                        $c->save();
                    }
                }
            }
        return redirect()->route('administrator.setting-master-cuti.index')->with('message-success', 'Data successfully saved !');
    }

    public function storeRange(Request $request)
    {
        update_setting('max_leave_range', $request->max_leave_range);
        update_setting('min_leave_range', $request->min_leave_range);

        return redirect()->route('administrator.setting-master-cuti.index')->with('message-success', 'Data saved successfully');
    }
}
