<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Kecamatan;
use App\Models\Kabupaten;

class KecamatanController extends Controller
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
        $params['data'] = Kecamatan::orderBy('nama', 'ASC')->get();

        return view('administrator.kecamatan.index')->with($params);
    }

    public function store(Request $request)
    {
        $latest = Kecamatan::where('id_kab', $request->kabupaten_id)->count();
        if($latest < 9){
            $id= $request->kabupaten_id.'0'.$latest + 1;
        } 
        else{
            $id= $request->kabupaten_id.$latest + 1;
        }

        $data               = new Kecamatan();
        $data->id_kec       = $id;  
        $data->nama         = $request->nama;
        $data->id_kab       = $request->kabupaten_id;
        $data->save();
        
        return redirect()->route('administrator.kecamatan.index')->with('message-success', 'success to save a new sub-district!');
    }

    public function update(Request $request, $id)
    {
        $data               = Kecamatan::where('id_kec', $id)->first();
        $data->nama         = $request->nama;
        $data->id_kab       = $request->kabupaten_id;
        $data->save();
        
        return redirect()->route('administrator.kecamatan.index')->with('message-success', 'success edit data sub-district!');
    }

    public function destroy($id)
    {
        $data = Kecamatan::where('id_kec', $id)->delete();

        return redirect()->route('administrator.kecamatan.index')->with('message-success','success delete data sub-district!');
    } 
}
