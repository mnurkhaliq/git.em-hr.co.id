<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ExitInterviewReason;

class AlasanPengunduranDiriSettingController extends Controller
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
        $this->middleware('module:9');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $params['data'] = ExitInterviewReason::orderBy('id', 'DESC')->get();

        return view('administrator.alasan-pengunduran-diri.index')->with($params);
    }

    /**
     * [create description]
     * @return [type] [description]
     */
    public function create()
    {   
        return view('administrator.alasan-pengunduran-diri.create');
    }

    /**
     * [edit description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function edit($id)
    {
        $params['data']         = ExitInterviewReason::where('id', $id)->first();

        return view('administrator.alasan-pengunduran-diri.edit')->with($params);
    }

    /**
     * [update description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function update(Request $request, $id)
    {
        $data                   = ExitInterviewReason::where('id', $id)->first();
        $data->label            = $request->label; 
        $data->save();

        return redirect()->route('administrator.alasan-pengunduran-diri.index')->with('message-success', 'Data saved successfully');
    }   

    /**
     * [desctroy description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function destroy($id)
    {
        $data = ExitInterviewReason::where('id', $id)->first();
        $data->delete();

        return redirect()->route('administrator.alasan-pengunduran-diri.index')->with('message-success', 'Data berhasi di hapus');
    } 

    /**
     * [store description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function store(Request $request)
    {
        $data                   = new ExitInterviewReason();
        $data->label            = $request->label;
        $data->save();

        return redirect()->route('administrator.alasan-pengunduran-diri.index')->with('message-success', 'Data saved successfully !');
    }
}
