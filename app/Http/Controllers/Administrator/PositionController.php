<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\OrganisasiPosition;
use App\Models\OrganisasiDirectorate;
use App\Models\OrganisasiDivision;
use App\Models\OrganisasiDepartment;
use App\Models\OrganisasiUnit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PositionController extends Controller
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
        $user = \Auth::user();
        if($user->project_id != NULL)
        {   
            $params['data'] = OrganisasiPosition::orderBy('organisasi_position.id', 'DESC')->where('project_id',$user->project_id)->get();
        }else{
            $params['data'] = OrganisasiPosition::orderBy('id', 'DESC')->get();
        }
        return view('administrator.position.index')->with($params);
    }

    /**
     * [create description]
     * @return [type] [description]
     */
    public function create()
    {   
        return view('administrator.position.create');
    }

    /**
     * [edit description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function edit($id)
    {
        $user = \Auth::user();
        $params['data']         = OrganisasiPosition::where(['id'=> $id,'project_id'=>$user->project_id])->first();
        if(!$params['data'])
            return redirect()->route('administrator.position.index')->with('message-error', 'Data is not found!');
        return view('administrator.position.edit')->with($params);
    }

    /**
     * [update description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function update(Request $request, $id)
    {
        $user = \Auth::user();
        $validation = Validator::make($request->all(),[
            'code' => 'required',
            'name' => 'required'
        ]);
        if($validation->fails())
            return redirect()->back()->withInput()->withErrors($validation->errors());

        $data       = OrganisasiPosition::where('id', $id)->first();
        $checkExistName = OrganisasiPosition::where(['name'=> $request->name,'project_id'=>$user->project_id])->whereNotIn('id', [$data->id])->first();
        $checkExistCode = OrganisasiPosition::where(['code'=> $request->code,'project_id'=>$user->project_id])->whereNotIn('id', [$data->id])->first();
        if(isset($checkExistName) || isset($checkExistCode)){
            return redirect()->back()->withInput()->withErrors(['Data is not updated']);
        }
        else{
            $data->name = $request->name;
            $data->code = $request->code;
            $data->save();

            return redirect()->route('administrator.position.index')->with('message-success', 'Data updated successfully!');
        }
    }   

    /**
     * [desctroy description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function destroy($id)
    {
        $data = OrganisasiPosition::where('id', $id)->first();
        $data->delete();

        return redirect()->route('administrator.position.index')->with('message-success', 'Data successfully deleted');
    } 

    /**
     * [store description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function store(Request $request)
    {
        $user = \Auth::user();

        $validation = Validator::make($request->all(),[
            'code' => 'required',
            'name' => 'required'
        ]);
        if($validation->fails())
            return redirect()->back()->withInput()->withErrors($validation->errors());

        $checkExistName = OrganisasiPosition::where(['name'=> $request->name,'project_id'=>$user->project_id])->first();
        $checkExistCode = OrganisasiPosition::where(['code'=> $request->code,'project_id'=>$user->project_id])->first();
        if(isset($checkExistName) || isset($checkExistCode)){
            return redirect()->back()->withInput()->withErrors(['Data already existed']);
        }
        else{
            $data = new OrganisasiPosition();
            $data->name = $request->name;
            $data->code = $request->code;
            $data->user_created = $user->id;
            $data->project_id = $user->project_id;
            $data->save();

            return redirect()->route('administrator.position.index')->with('message-success', 'Data successfully saved!');
        }

    }

    public function checkCode(Request $r){
        $user = Auth::user();
        if($r->type == 'store'){
            $checkExistCode = OrganisasiPosition::where(['code'=> $r->code,'project_id'=>$user->project_id])->first();
            if(isset($checkExistCode)){
                $status = 'false';
            }
            else{
                $status = 'true';
            }
        }
        else{
            $data           = OrganisasiPosition::where('id', $r->id)->first();
            $checkExistCode = OrganisasiPosition::where(['code'=> $r->code,'project_id'=>$user->project_id])->whereNotIn('id', [$data->id])->first();
            if(isset($checkExistCode)){
                $status = 'false';
            }
            else{
                $status = 'true';
            }
        }

        return response($status);
    }

    public function checkName(Request $r){
        $user = Auth::user();
        if($r->type == 'store'){
            $checkExistName = OrganisasiPosition::where(['name'=> $r->name,'project_id'=>$user->project_id])->first();
            if(isset($checkExistName)){
                $status = 'false';
            }
            else{
                $status = 'true';
            }
        }
        else{
            $data           = OrganisasiPosition::where('id', $r->id)->first();
            $checkExistName = OrganisasiPosition::where(['name'=> $r->name,'project_id'=>$user->project_id])->whereNotIn('id', [$data->id])->first();
            if(isset($checkExistName)){
                $status = 'false';
            }
            else{
                $status = 'true';
            }
        }

        return response($status);
    }
}
