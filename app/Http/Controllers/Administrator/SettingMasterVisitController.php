<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SettingActivityVisit;
use App\User;
use App\Models\MasterCategoryVisit;
use App\Models\MasterVisitType;
use App\Models\CabangPicMaster;
use App\Models\CabangPic;
use App\Models\Cabang;
use App\Models\UsersBranchVisit;

class SettingMasterVisitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
        $this->middleware('module:28');
    }
    public function index()
    {
        //$result=$this->userToBeAssigned2(7);
        //dd ($result);
        $params['type'] = MasterCategoryVisit::orderBy('id', 'ASC')->get();
        $params['cabangpicmaster'] = CabangPicMaster::where('isactive', true)->with('branchname')->orderBy('id', 'asc')->get();
        $params['listtype'] = MasterVisitType::orderBy('id', 'ASC')->get();
        $params['data'] = SettingActivityVisit::where('isactive', true)->with('CategoryActivityVisit')->orderBy('id', 'ASC')->get();

        return view('administrator.setting-Visit.index')->with($params);
    }

    public function userToBeAssigned($master_visit_type_id)
    {
        if ($master_visit_type_id==1) {
            $data = User::whereIn('access_id',[1,2])
                ->leftJoin('structure_organization_custom', 'users.structure_organization_custom_id', '=', 'structure_organization_custom.id')
                ->leftJoin('organisasi_position', 'structure_organization_custom.organisasi_position_id', '=', 'organisasi_position.id')
                ->leftJoin('organisasi_division', 'structure_organization_custom.organisasi_division_id', '=', 'organisasi_division.id')
                ->select(
                    'users.id',
                    'users.master_visit_type_id as master_visit_type_id',
                    'users.nik',
                    'users.name',
                    'organisasi_division.name as division',
                    'organisasi_position.name as position'
                )
                ->orderByRaw('-master_visit_type_id desc')
                ->get();

            if (count($data) > 0) {
                $res['message'] = 'success';
                $res['data']    = $data;
            } else {
                $res['message'] = 'failed';
            }
        } 
        else if ($master_visit_type_id==2) {
            $data = User::whereIn('access_id',[1,2])
                ->leftJoin('structure_organization_custom', 'users.structure_organization_custom_id', '=', 'structure_organization_custom.id')
                ->leftJoin('organisasi_position', 'structure_organization_custom.organisasi_position_id', '=', 'organisasi_position.id')
                ->leftJoin('organisasi_division', 'structure_organization_custom.organisasi_division_id', '=', 'organisasi_division.id')
                ->select(
                    'users.id',
                    'users.master_visit_type_id as master_visit_type_id',
                    'users.nik',
                    'users.name',
                    'organisasi_division.name as division',
                    'organisasi_position.name as position'
                )
                ->orderBy('users.master_visit_type_id', 'desc')
                ->get();

            if (count($data) > 0) {
                $res['message'] = 'success';
                $res['data']    = $data;
            } else {
                $res['message'] = 'failed';
            }
        }
        else {
            $res['message'] = 'failed';
        }

        return response($res);
    }

    public function assignvisittype(Request $r)
    {
        if ($r->user_id) {
            for ($i = 0; $i < count($r->user_id); $i++) {
                if($r->master_visit_type_id==2) //if unlock
                {
                    $del_item = CabangPic::where('user_id', '=', $r->user_id[$i])->delete();
                    $del_item2 = UsersBranchVisit::where('user_id', '=', $r->user_id[$i])->delete();
                }
                $user = User::where('id', $r->user_id[$i])->first();
                $user->master_visit_type_id = $r->master_visit_type_id;
                $user->save();
            }
        }
        if ($r->user_id_uncheck) {
            for ($x = 0; $x < count($r->user_id_uncheck); $x++) {
                if($r->master_visit_type_id==1) //if unlock
                {
                    $del_item = CabangPic::where('user_id', '=', $r->user_id_uncheck[$x])->delete();
                    $del_item2 = UsersBranchVisit::where('user_id', '=', $r->user_id_uncheck[$x])->delete();
                }
                $edit = User::where('id', $r->user_id_uncheck[$x])->first();
                if ((int)$edit->master_visit_type_id == (int)$r->master_visit_type_id) {
                    $edit->master_visit_type_id = null;
                    $edit->save();
                }
            }
        }
        return response()->json(['status' => 'success', 'message' => 'Visit Type assigned successfully']);
    }


    public function userToBeAssigned2($cabangpicmaster_id)
    {

        $picmaster = CabangPicMaster::where('id', $cabangpicmaster_id)->first();
        if ($picmaster) {
            $first = User::whereIn('access_id',[1,2])
                ->leftJoin('structure_organization_custom', 'users.structure_organization_custom_id', '=', 'structure_organization_custom.id')
                ->leftJoin('organisasi_position', 'structure_organization_custom.organisasi_position_id', '=', 'organisasi_position.id')
                ->leftJoin('organisasi_division', 'structure_organization_custom.organisasi_division_id', '=', 'organisasi_division.id')
                ->leftjoin('cabangpic', function ($join) use ($cabangpicmaster_id) {
                    $join->on('users.id', '=', 'cabangpic.user_id')
                        ->where('cabangpic.cabangpicmaster_id', $cabangpicmaster_id);
                })
                ->select(
                    'users.id',
                    'cabangpic.cabangpicmaster_id as cabangpicmaster_id',
                    'users.nik',
                    'users.name',
                    'organisasi_division.name as division',
                    'organisasi_position.name as position'
                )
                ->where('users.master_visit_type_id', '=', 1)
                ->whereNull('cabangpic.cabangpicmaster_id');

            $data = User::whereIn('access_id',[1,2])
                ->leftJoin('structure_organization_custom', 'users.structure_organization_custom_id', '=', 'structure_organization_custom.id')
                ->leftJoin('organisasi_position', 'structure_organization_custom.organisasi_position_id', '=', 'organisasi_position.id')
                ->leftJoin('organisasi_division', 'structure_organization_custom.organisasi_division_id', '=', 'organisasi_division.id')
                ->leftJoin('cabangpic', 'users.id', '=', 'cabangpic.user_id')
                ->select(
                    'users.id',
                    'cabangpic.cabangpicmaster_id as cabangpicmaster_id',
                    'users.nik',
                    'users.name',
                    'organisasi_division.name as division',
                    'organisasi_position.name as position'
                )
                ->where('users.master_visit_type_id', '=', 1)
                ->where('cabangpicmaster_id', '=', $cabangpicmaster_id)
                ->union($first)
                ->distinct()
                ->get();

            if (count($data) > 0) {
                $res['message'] = 'success';
                $res['data']    = $data;
            } else {
                $res['message'] = 'failed';
            }
        } else {
            $res['message'] = 'failed';
        }

        return response($res);
    }

    public function assignbranchpic(Request $r)
    {
        if ($r->user_id2) {
            for ($i = 0; $i < count($r->user_id2); $i++) {
                $user = CabangPic::where('user_id', $r->user_id2[$i])->where('cabangpicmaster_id', $r->cabangpicmaster_id)->first();
                $cabang = CabangPicMaster::where('id', $r->cabangpicmaster_id)->first()->cabang_id;
                $userbranchvisitlist = UsersBranchVisit::where('user_id', $r->user_id2[$i])->where('cabang_id', $cabang)->first();
                if (!$user) {
                    $data   = new CabangPic();
                    $data->cabangpicmaster_id = $r->cabangpicmaster_id;
                    $data->user_id = $r->user_id2[$i];
                    $data->save();
                }
                if (!$userbranchvisitlist) {
                    $add_item = new UsersBranchVisit;
                    $add_item->cabang_id = (int)$cabang;
                    $add_item->user_id = $r->user_id2[$i];
                    $add_item->save();
                }
            }
        }

        if ($r->user_id_uncheck2) {
            for ($x = 0; $x < count($r->user_id_uncheck2); $x++) {
                $edit = CabangPic::where('user_id', $r->user_id_uncheck2[$x])->where('cabangpicmaster_id', $r->cabangpicmaster_id)->first();
                $cabang = CabangPicMaster::where('id', $r->cabangpicmaster_id)->first()->cabang_id;
                
                if ($edit) {
                    $del_item = CabangPic::where('user_id', $r->user_id_uncheck2[$x])->where('cabangpicmaster_id', $r->cabangpicmaster_id)->first();
                    if ($del_item != null) {
                        $del_item->delete();
                    }
                }
                $countuserpic = CabangPic::where('user_id', $r->user_id_uncheck2[$x])->first();
                //$countpic=0;
                $userbranchvisitlist = UsersBranchVisit::where('user_id', $r->user_id_uncheck2[$x])->where('cabang_id', $cabang)->first();
                if ($userbranchvisitlist && !$countuserpic) {
                    $userbranchvisitlist->delete();
                }
                
            }
        }

        return response()->json(['status' => 'success', 'message' => 'Branch Pic assigned successfully']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $params['type'] = MasterCategoryVisit::orderBy('id', 'DESC')->get();
        ///$params['cabang'] = cabang::orderBy('id', 'asc')->get();
        return view('administrator.setting-Visit.create')->with($params);;
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
            'master_category_visit_id'           => 'required',
            'activityname'             => 'required',
            'point'             => 'required',
         ]);
        $data       = new SettingActivityVisit();
        $data->master_category_visit_id = $request->master_category_visit_id;
        $data->activityname      = $request->activityname;
        $data->point      = $request->point;
        $data->isactive      = true;
        $data->save();

        return redirect()->route('administrator.setting-Visit.index')->with('message-success', 'Data saved successfully !');
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

        $params['data'] = SettingActivityVisit::where('id', $id)->first();
        $params['type'] = MasterCategoryVisit::orderBy('id', 'DESC')->get();
        return view('administrator.setting-Visit.edit')->with($params);
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
            'master_category_visit_id'           => 'required',
            'activityname'             => 'required',
            'point'             => 'required',
         ]);
        $data = SettingActivityVisit::where('id', $id)->first();
        $data->activityname      = $request->activityname;
        $data->point      = $request->point;
        $data->save();

        return redirect()->route('administrator.setting-Visit.index')->with('message-success', 'Data saved successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = SettingActivityVisit::where('id', $id)->first();
        $data->isactive      = false;
        $data->save();

        return redirect()->route('administrator.setting-Visit.index')->with('message-success', 'Data berhasi di hapus');
    }

    /**
     * [create description]
     * @return [type] [description]
     */
    public function createBranchPic()
    {
        $params['cabang'] = Cabang::all();
        $params['picmaster'] = CabangPicMaster::all();

        return view('administrator.setting-Visit.createbranchpic')->with($params);
    }

    public function storeBranchPic(Request $request)
    {
        $this->validate($request,[
            'cabang_id'           => 'required',
            'picname'             => 'required',
         ]);

        $data = new CabangPicMaster();
        $data->cabang_id     = $request->cabang_id;
        $data->picname         = $request->picname;
        $data->isactive         = true;
        $data->save();

        return redirect()->route('administrator.setting-Visit.index')->with('message-success', 'Data successfully saved');
    }

    public function destroyBranchPic($id)
    {
        $data = CabangPicMaster::where('id', $id)->first();
        $data->isactive      = false;
        $data->save();
        return redirect()->route('administrator.setting-Visit.index')->with('message-success', 'Data successfully deleted');
    }
}
