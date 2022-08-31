<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\ProvinsiDetailAllowance;

class ProvinsiController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $params['data'] = Provinsi::orderBy('nama', 'ASC')->get();

        return view('administrator.provinsi.index')->with($params);

    }

    /**
     * Store 
     * @param  Request $request
     * @return redirect
     */
    public function store(Request $request)
    {
        $latest = Provinsi::orderBy('id_prov', 'DESC')->first();
        $data = new Provinsi();
        $data->id_prov =  $latest->id_prov + 1; 
        $data->nama = $request->nama;
        $data->type = $request->type;
        $data->project_id = \Auth::user()->project_id;
        $data->save();

        $check = ProvinsiDetailAllowance::where('project_id', \Auth::user()->project_id)->where('id_prov', $latest->id_prov + 1)->count();
        if($check < 1){
            $allowance = new ProvinsiDetailAllowance();
            $allowance->id_prov = $latest->id_prov + 1;
            $allowance->type = $request->type;
            $allowance->project_id = \Auth::user()->project_id;
            $allowance->save(); 
        }else{
            $allowance = ProvinsiDetailAllowance::where('project_id', \Auth::user()->project_id)->where('id_prov', $latest->id_prov + 1)->first();
            $allowance->type = $request->type;
            $allowance->save();
        }
        
        return redirect()->route('administrator.provinsi.index')->with('message-success', \Lang::get('setting.provinsi-message-success'));
    }

    /**
     * Update Store
     * @param  Request $request
     */
    public function update(Request $request, $id)
    {
        $data = Provinsi::where('id_prov', $id)->first();
        $data->nama = $request->nama;
        $data->save();
        
        $check = ProvinsiDetailAllowance::where('project_id', \Auth::user()->project_id)->where('id_prov', $id)->count();
        if($check < 1){
            $data = new ProvinsiDetailAllowance();
            $data->id_prov = $id;
            $data->type = $request->type;
            $data->project_id = \Auth::user()->project_id;
            $data->save(); 
        }else{
            $data = ProvinsiDetailAllowance::where('project_id', \Auth::user()->project_id)->where('id_prov', $id)->first();
            $data->type = $request->type;
            $data->save();
        }
        
        return redirect()->route('administrator.provinsi.index')->with('message-success', \Lang::get('setting.provinsi-message-success'));
    }

    /**
     * Destroy
     * @return redirect
     */
    public function destroy($id)
    {
        $cek = Kabupaten::where('id_prov', $id)->count();
        if($cek>0){
            return redirect()->route('administrator.provinsi.index')->with('message-error', 'You can\'t delete this Province, because this Province has been used in City!');
        }
        $data = Provinsi::where('id_prov', $id)->delete();
        $check = ProvinsiDetailAllowance::where('id_prov', $id)->delete();
        return redirect()->route('administrator.provinsi.index')->with('message-success', \Lang::get('setting.provinsi-message-delete'));
    } 
}
