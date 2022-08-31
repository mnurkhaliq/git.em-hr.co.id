<?php

namespace App\Http\Controllers\Administrator;

use App\Models\JobCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use DataTables;
use Illuminate\Support\Facades\Validator;

class JobCategoryController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
        $this->middleware('module:27');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function table(){
        $user = Auth::user();
        $requests = JobCategory::where(['project_id'=>$user->project_id]);
        return DataTables::of($requests)
            ->addColumn('action', function ($request) {
                return '<button type="button" onclick="edit('.$request->id.')" class="btn btn-info btn-xs m-r-5"><i class="fa fa-edit"></i> edit</button> <button type="button" onclick="remove('.$request->id.')" class="btn btn-danger btn-xs m-r-5"><i class="fa fa-trash"></i> delete</button>';
            })
            ->make(true);
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
        $user = Auth::user();
        $validator = Validator::make(request()->all(), [
            'name'  => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()]);
        }

        $jobCategory              = new JobCategory();
        $jobCategory->name        = $request->name;
        $jobCategory->project_id  = $user->project_id;
        $jobCategory->save();
        return response()->json(['status' => 'success', 'message' => "Job Category has been saved!"]);
    }

    public function get($id){
        $data = JobCategory::find($id);
        if($data)
            return response()->json(['status' => 'success', 'message' => "Data Found", 'data' => $data]);
        else
            return response()->json(['status' => 'failed', 'message' => "Data Not Found", 'data' => $data]);
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
        $user = Auth::user();
        $validator = Validator::make(request()->all(), [
            'name'  => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()]);
        }
        $jobCategory              = JobCategory::find($id);
        if($jobCategory) {
            $jobCategory->name    = $request->name;
            $jobCategory->save();
            return response()->json(['status' => 'success', 'message' => "Job Category has been updated!"]);
        }
        return response()->json(['status' => 'failed', 'message' => "Cannot update data!"]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(JobCategory::destroy($id)){
            return response()->json(['status' => 'success', 'message' => 'Data has been deleted']);
        }
        else{
            return response()->json(['status' => 'error', 'message' => 'Cannot delete data'],404);
        }
        //
    }
}
