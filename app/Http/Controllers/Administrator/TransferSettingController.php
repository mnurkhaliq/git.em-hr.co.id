<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TransferSetting;
use Session;

class TransferSettingController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
        //$this->middleware('module:8');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $params['data'] = TransferSetting::get();
        return view('administrator.cash-advance-setting.index')->with($params);
    }

    public function store(Request $request)
    {
        if($request->ajax())
        {
            $cek = TransferSetting::where('user_id', $request->id)->first();
            if($cek==null){
                $data               = new TransferSetting();
                $data->user_id      = $request->id;
                $user = \Auth::user();
                if($user->project_id != NULL)
                {
                    $data->user_created = $user->id;
                }
                $data->save();
            }
            else{
                Session::flash('message-error', 'User is already on the list, please select another user.');
                $params['message']  = 'error';
                $params['data']     = 'User is already on the list, please select another user';
                return response()->json(['message' => 'error', 'data' => $params]);
            }

            Session::flash('message-success', 'User Transfer successfully add');

            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TransferSetting  $transferSetting
     * @return \Illuminate\Http\Response
     */
    public function show(TransferSetting $transferSetting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TransferSetting  $transferSetting
     * @return \Illuminate\Http\Response
     */
    public function edit(TransferSetting $transferSetting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TransferSetting  $transferSetting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TransferSetting $transferSetting)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TransferSetting  $transferSetting
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = TransferSetting::where('id', $id)->first();
        $data->delete();
        return redirect()->route('administrator.transfer-setting.index')->with('message-success', 'Data successfully deleted');
    }
}
