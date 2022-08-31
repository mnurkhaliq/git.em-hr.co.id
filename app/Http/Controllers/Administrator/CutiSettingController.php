<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Setting;

class CutiSettingController extends Controller
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
     * [index description]
     * @return [type] [description]
     */
    public function index()
    {
        $params['data'] = Setting::orderBy('id', 'DESC')->get();

        return view('administrator.setting.index')->with($params);
    }

     /**
     * [create description]
     * @return [type] [description]
     */
    public function create()
    {
        return view('administrator.setting.create');
    }

    /**
     * [edit description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function edit($id)
    {
        $data['data']   = Setting::where('id', $id)->first();;
        
        return view('administrator.setting.edit')->with($data);
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
        $this->validate($request,[
            'key'              => 'required',
            'value'              => 'required',
        ]);
        
        $data               = Setting::where('id', $id)->first();
        $data->key         = $request->key;
        $data->value         = $request->value;
        $data->save();

        return redirect()->route('administrator.setting.index')->with('message-success', 'Data saved successfully'); 
    }


    /**
     * [desctroy description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function destroy($id)
    {
        $data = Setting::where('id', $id)->first();
        $data->delete();

        return redirect()->route('administrator.setting.index')->with('message-success', 'Data berhasi di hapus');
    }

   /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        $this->validate($request,[
            'key'              => 'required',
            'value'              => 'required',
        ]);
        
        $data               =  new Setting();
        $data->key          = $request->key;
        $data->value        = $request->value;
        $data->save();

        return redirect()->route('administrator.setting.index')->with('message-success', 'Data saved successfully'); 
   }
}
