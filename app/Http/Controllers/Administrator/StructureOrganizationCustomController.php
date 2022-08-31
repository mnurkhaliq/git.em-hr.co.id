<?php

namespace App\Http\Controllers\Administrator;

use App\Models\SettingApproval;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\StructureOrganizationCustom;
use App\Models\SettingApprovalLeave;
use App\Models\OrganisasiTitle;
use App\Models\OrganisasiDivision;
use App\Models\OrganisasiPosition;
use App\Models\Grade;
use DB;
use Illuminate\Support\Facades\Auth;

class StructureOrganizationCustomController extends Controller
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
            $params['data']   = StructureOrganizationCustom::orderBy('structure_organization_custom.id', 'DESC')->where('structure_organization_custom.project_id', $user->project_id)->select('structure_organization_custom.*')->get();

            $params['division'] = OrganisasiDivision::where('organisasi_division.project_id', $user->project_id)->select('organisasi_division.*')->orderBy('organisasi_division.name','asc')->get();
            $params['position'] = OrganisasiPosition::where('organisasi_position.project_id', $user->project_id)->select('organisasi_position.*')->orderBy('organisasi_position.name','asc')->get();
            //dd($params['data']);
        } else
        {
            $params['data']   = StructureOrganizationCustom::orderBy('id', 'DESC');
            $params['division'] = OrganisasiDivision::all();
            $params['position'] = OrganisasiPosition::all();
        }
        $params['title'] = OrganisasiTitle::all();

        return view('administrator.structure-organization-custom.index')->with($params);
    }

    /**
     * Store
     * @param  Request $request
     */
    public function store(Request $request)
    {
        $user = \Auth::user();
        if($request->organisasi_position_id == null){
            return redirect()->route('administrator.organization-structure-custom.index', $request->setting_approval_id)->with('message-error', 'Position must be selected!');
        } else if($request->organisasi_division_id == null && $request->organisasi_title_id != null){
            return redirect()->route('administrator.organization-structure-custom.index', $request->setting_approval_id)->with('message-error', 'Division must be selected if title was selected!');
        }
        if($user->project_id != NULL)
        {
            $checkExist = StructureOrganizationCustom::where('organisasi_title_id',$request->organisasi_title_id)->where('organisasi_division_id',$request->organisasi_division_id)->where('organisasi_position_id',$request->organisasi_position_id)->where('project_id', $user->project_id)->first();
            if(isset($checkExist))
            {
                return redirect()->route('administrator.organization-structure-custom.index', $request->setting_approval_id)->with('message-error', 'Data already exists!');
            }else{
                $data               = new StructureOrganizationCustom();
                $data->organisasi_title_id   = $request->organisasi_title_id;
                $data->organisasi_division_id   = $request->organisasi_division_id;
                $data->organisasi_position_id   = $request->organisasi_position_id;
                $data->user_created = $user->id;
                $data->project_id   = $user->project_id;
                if($request->grade_id != NULL){
                    $data->grade_id = $request->grade_id;
                }
                $data->description = htmlspecialchars($request->job_desc);
                $data->requirement = $request->requirement;
                $data->save();
                if($request->structure == 'below') {
                    $data->parent_id = $request->parent_id;
                }
                else{
                    $parent             = StructureOrganizationCustom::find($request->parent_id);
                    $data->parent_id    = $parent->parent_id;
                    $parent->parent_id  = $data->id;
                    $parent->save();
                }
                $data->save();

                $settingApproval = new SettingApproval();
                $settingApproval->structure_organization_custom_id = $data->id;
                $settingApproval->save();
                return redirect()->route('administrator.organization-structure-custom.index');
            }
        } 
        else
        {
            $checkExist = StructureOrganizationCustom::where('organisasi_title_id',$request->organisasi_title_id)->where('organisasi_division_id',$request->organisasi_division_id)->where('organisasi_position_id',$request->organisasi_position_id)->first();
            if(isset($checkExist))
            {
                return redirect()->route('administrator.organization-structure-custom.index', $request->setting_approval_id)->with('message-error', 'Data already exists!');
            }else{
                $data               = new StructureOrganizationCustom();
                $data->parent_id    = $request->parent_id;
                $data->organisasi_title_id   = $request->organisasi_title_id;
                $data->organisasi_division_id   = $request->organisasi_division_id;
                $data->organisasi_position_id   = $request->organisasi_position_id;
                if($request->grade_id != NULL){
                    $data->grade_id = $request->grade_id;
                }
                $data->description = $request->job_desc;
                $data->requirement = $request->requirement;
                $data->save();
                if($request->structure == 'below') {
                    $data->parent_id = $request->parent_id;
                }
                else{
                    $parent             = StructureOrganizationCustom::find($request->parent_id);
                    $data->parent_id    = $parent->parent_id;
                    $parent->parent_id  = $data->id;
                    $parent->save();
                }
                $data->save();

                $settingApproval = new SettingApproval();
                $settingApproval->structure_organization_custom_id = $data->id;
                $settingApproval->save();
                return redirect()->route('administrator.organization-structure-custom.index');
            }
        }
    }

    /**
     * Delete
     * @param  $id
     */
    public function delete($id)
    {

        $data = StructureOrganizationCustom::where('id', $id)->first();
        $data->delete();

        $settingApproval = SettingApproval::where('structure_organization_custom_id', $id)->first();
        $settingApproval->delete();

        $data = StructureOrganizationCustom::where('parent_id', $id);
        if($data)
        {
            $settingApproval = SettingApproval::where('structure_organization_custom_id', $data->id);
            if($settingApproval)
            {
               $settingApproval->delete();   
            }
            $data->delete();    
        }
        return redirect()->route('administrator.organization-structure-custom.index');
    }

    public function show($id){
        $user = Auth::user();
        $data = DB::select(
            "select soc.id,soc.parent_id, p.name as position, p.code as pcode, p.id as pid, d.name as division, d.code as dcode, d.id as did, t.name as title, t.code as tcode, t.id as tid, g.name as grade, g.id as grade_id, soc.description, soc.requirement
            from structure_organization_custom soc
            left join organisasi_position p on soc.organisasi_position_id = p.id
            left join organisasi_division d on soc.organisasi_division_id = d.id
            left join organisasi_title t on soc.organisasi_title_id = t.id
            left join grade g on soc.grade_id = g.id
            where soc.id = '".$id."'"
        );

        $data[0]->description = htmlspecialchars_decode($data[0]->description);
        $data[0]->requirement = htmlspecialchars_decode($data[0]->requirement);

        $allData['data'] = $data;
        $structures = StructureOrganizationCustom::leftJoin('organisasi_position as op','structure_organization_custom.organisasi_position_id','=','op.id')
            ->leftJoin('organisasi_division as od','structure_organization_custom.organisasi_division_id','=','od.id')
            ->leftJoin('organisasi_title as ot','structure_organization_custom.organisasi_title_id','=','ot.id')
            ->where('structure_organization_custom.project_id',$user->project_id)
            ->where('structure_organization_custom.id','!=',$id)
            ->select(['structure_organization_custom.*',DB::raw("CONCAT(COALESCE(op.name,''),IF(od.name IS NOT NULL, CONCAT(' - ',COALESCE(od.name,'')), ''),IF(ot.name IS NOT NULL, CONCAT(' - ',COALESCE(ot.name,'')), ''),' (',COALESCE(op.code,''),IF(od.code IS NOT NULL, CONCAT('-',COALESCE(od.code,'')), ''),IF(ot.code IS NOT NULL, CONCAT('-',COALESCE(ot.code,'')), ''),')') as position")])
            ->get();
        $allData['parent'] = [];
        foreach($structures as $parent){
            $cek = true;
            $currParent = clone $parent;

            while($currParent && $currParent->parent_id!=null){
                if($currParent->parent_id == $id){
                    $cek = false;
                    break;
                }
                else{
                    $currParent = StructureOrganizationCustom::where('id',$currParent->parent_id)->first();
                }
            }

            if($cek){
                array_push($allData['parent'],$parent);
            }
        }

        $res['status']  = 'success';
        $res['data']    = $allData;

        return response($res);
    }

    public function update(Request $request){
        $user = \Auth::user();
        if($request->pid == null){
            return redirect()->route('administrator.organization-structure-custom.index', $request->setting_approval_id)->with('message-error', 'Position must be selected!');
        } else if($request->did == null && $request->tid != null){
            return redirect()->route('administrator.organization-structure-custom.index', $request->setting_approval_id)->with('message-error', 'Division must be selected if title was selected!');
        }
        if($user->project_id != NULL)
        {
            $checkExist = StructureOrganizationCustom::where('organisasi_title_id',$request->tid)->where('organisasi_division_id',$request->did)->where('organisasi_position_id',$request->pid)->whereNotIn('id', [$request->structure_id])->where('project_id', $user->project_id)->first();
            if(isset($checkExist))
            {
                return redirect()->route('administrator.organization-structure-custom.index', $request->setting_approval_id)->with('message-error', 'Data already exists!');
            }else{
                $data               = StructureOrganizationCustom::where('id', $request->structure_id)->first();
                $data->description = htmlspecialchars($request->job_desc_edit);
                $data->requirement = htmlspecialchars($request->requirement_edit);
                $data->organisasi_title_id = $request->tid;
                $data->organisasi_division_id = $request->did;
                $data->organisasi_position_id = $request->pid;
                $data->grade_id = $request->grade_edit;
                if($request->parent_id !=''){
                    $data->parent_id = $request->parent_id;
                }

                $data->save();

                $settingApproval = SettingApproval::where('structure_organization_custom_id', $request->structure_id)->first();
                $settingApproval->structure_organization_custom_id = $data->id;
                $settingApproval->save();
                return redirect()->route('administrator.organization-structure-custom.index');
            }
        } 
        else
        {
            $checkExist = StructureOrganizationCustom::where('organisasi_title_id',$request->tid)->where('organisasi_division_id',$request->did)->where('organisasi_position_id',$request->pid)->whereNotIn('id', [$request->structure_id])->first();
            if(isset($checkExist))
            {
                return redirect()->route('administrator.organization-structure-custom.index', $request->setting_approval_id)->with('message-error', 'Data already exists!');
            }else{
                $data               = StructureOrganizationCustom::where('id', $request->structure_id)->first();
                $data->description = htmlspecialchars($request->job_desc_edit);
                $data->requirement = htmlspecialchars($request->requirement_edit);
                $data->organisasi_title_id = $request->tid;
                $data->organisasi_division_id = $request->did;
                $data->organisasi_position_id = $request->pid;
                $data->grade_id = $request->grade_edit;
                if($request->parent_id !=''){
                    $data->parent_id = $request->parent_id;
                }
                $data->save();

                $settingApproval = SettingApproval::where('structure_organization_custom_id', $request->structure_id)->first();
                $settingApproval->structure_organization_custom_id = $data->id;
                $settingApproval->save();
                return redirect()->route('administrator.organization-structure-custom.index');
            }
        }
    }
    public function export(){
        $user = Auth::user();
        $structures = StructureOrganizationCustom::leftJoin('organisasi_position as op','structure_organization_custom.organisasi_position_id','=','op.id')
            ->leftJoin('organisasi_division as od','structure_organization_custom.organisasi_division_id','=','od.id')
            ->leftJoin('organisasi_title as ot','structure_organization_custom.organisasi_title_id','=','ot.id')
            ->where('structure_organization_custom.project_id', $user->project_id)
            ->select(['op.code as pos_code','od.code as div_code','ot.code as title_code','op.name as pos_name','od.name as div_name','ot.name as title_name'])
            ->orderBy(\DB::raw("CONCAT(COALESCE(op.name,''),IF(od.name IS NOT NULL, CONCAT(' - ',COALESCE(od.name,'')), ''),IF(ot.name IS NOT NULL, CONCAT(' - ',COALESCE(ot.name,'')), ''))"),'ASC')
            ->get();
        foreach($structures as $no =>  $item)
        {
            $code = $item->pos_code;
            $name = $item->pos_name;
            if($item->div_code) {
                $code .= '-'.$item->div_code;
            }
            if($item->div_name) {
                $name .= '-'.$item->div_name;
            }
            if($item->title_code) {
                $code .= '-'.$item->title_code;
            }
            if($item->title_name) {
                $name .= '-'.$item->title_name;
            }
            $params[$no]['NO']                  = $no+1;
            $params[$no]['POSITION']            = $name;
            $params[$no]['CODE']                = $code;
        }
        return (new \App\Models\KaryawanExport($params, 'Structure Organization List' ))->download('EM-HR.Structure Organization'.date('d-m-Y') .'.xlsx');
    }
}
