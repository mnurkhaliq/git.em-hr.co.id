<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SettingApproval;
use App\Models\SettingApprovalLevel;
use App\Models\SettingApprovalExitItem;
use App\AjaxController;


class SettingApprovalExitController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
        $this->middleware('module:9');
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
        if($user->project_id != NULL)
        {   
            $params['data']  = SettingApproval::orderBy('setting_approval.id', 'DESC')->join('structure_organization_custom','structure_organization_custom.id','=','setting_approval.structure_organization_custom_id')->join('users','users.id','=','structure_organization_custom.user_created')->where('users.project_id', $user->project_id)->select('setting_approval.*')->get();
        }else{
            $params['data']  = SettingApproval::orderBy('id', 'DESC')->get();
        }
        return view('administrator.setting-approvalExit.index')->with($params);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function indexItem($id)
    {
        //
        $params['data'] = SettingApproval::where('id', $id)->first();
        $params['dataItem'] = SettingApprovalExitItem::where('setting_approval_id', $id)->orderBy('setting_approval_level_id')->get();
        if(!$params['data']){
            return redirect()->route('administrator.setting-approvalExit.index')->with('message-error', 'Data not found!');
        }
        return view('administrator.setting-approvalExit.indexItem')->with($params);
    }

     public function createItem($id)
    {
        $params['data'] = SettingApproval::where('id', $id)->first();
        $params['structure'] = getStructureName();
        $params['level'] = SettingApprovalLevel::all();

        return view('administrator.setting-approvalExit.createItem')->with($params);
    }

    public function storeItem(Request $request)
    {
        //cek data udah ada belum level segitu
        $checkdata = SettingApprovalExitItem::where('setting_approval_id', $request->setting_approval_id)->where('setting_approval_level_id', $request->setting_approval_level_id)->first();
        $checkDataStruktur = SettingApprovalExitItem::where('setting_approval_id', $request->setting_approval_id)->where('structure_organization_custom_id', $request->structure_organization_custom_id)->first();
        //
        if($request->setting_approval_level_id == NULL)
        {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors("Approval level is incomplete!");
        }elseif ($request->structure_organization_custom_id == NULL) {
            # code...
            return redirect()
                ->back()
                ->withInput()
                ->withErrors("Approval position is incomplete!");
        }else{


            if(isset($checkDataStruktur) || isset($checkdata))
            {
                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors("Data already exists!");
            }else
            {
                $data       = new SettingApprovalExitItem();
                $data->setting_approval_id  = $request->setting_approval_id;
                $data->setting_approval_level_id  = $request->setting_approval_level_id;
                $data->structure_organization_custom_id  = $request->structure_organization_custom_id;
                $data->description = $request->description;
                $data->save();

                return redirect()->route('administrator.setting-approvalExit.indexItem', $request->setting_approval_id)->with('message-success', 'Data successfully saved!');
            }
        }
        
       
    }

    public function editItem($id)
    {
        //
        $params['data'] = SettingApprovalExitItem::where('id', $id)->first();
        $params['structure'] = getStructureName();
        $params['level'] = SettingApprovalLevel::all();
        if(!$params['data']){
            return redirect()->route('administrator.setting-approvalExit.index')->with('message-error', 'Data not found!');
        }

        return view('administrator.setting-approvalExit.editItem')->with($params);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateItem(Request $request, $id)
    {
        // check data
        $data                       = SettingApprovalExitItem::where('id', $id)->first();
        $checkdata = SettingApprovalExitItem::where('setting_approval_id', $request->setting_approval_id)->where('setting_approval_level_id', $request->setting_approval_level_id)->where('id','!=',$id)->first();
        $checkDataStruktur = SettingApprovalExitItem::where('setting_approval_id', $request->setting_approval_id)->where('structure_organization_custom_id', $request->structure_organization_custom_id)->where('id','!=',$id)->first();
        if($checkdata){
            return redirect()
                ->back()
                ->withInput()
                ->withErrors("Approval level $request->setting_approval_level_id has already existed!");
        }
        if ($request->structure_organization_custom_id == NULL) {
            # code...
            return redirect()
                ->back()
                ->withInput()
                ->withErrors("Approval position is incomplete!");
        }else
        {
            if(isset($checkDataStruktur))
            {
                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors("Data already exists!");
            }else
            {
                 $data->setting_approval_level_id  = $request->setting_approval_level_id;
                 $data->structure_organization_custom_id  = $request->structure_organization_custom_id;
                 $data->description     = $request->description;
                 $data->save();

                return redirect()->route('administrator.setting-approvalExit.indexItem', $data->setting_approval_id)->with('message-success', 'Data successfully saved');
            }
        }
        
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyItem($id)
    {
        //
        $data = SettingApprovalExitItem::where('id', $id)->first();
        $id = $data->setting_approval_id;
        $data->delete();

        return redirect()->route('administrator.setting-approvalExit.indexItem',$id)->with('message-success', 'Data successfully deleted');
    }
}
