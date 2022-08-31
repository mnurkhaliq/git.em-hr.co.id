<?php

namespace App\Http\Controllers\Administrator;

use App\Models\RemoteAttendance;
use function foo\func;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use DataTables;
use Illuminate\Support\Facades\Validator;

class RemoteAttendanceController extends Controller
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
        $this->middleware('module:15');
    }

    public function index()
    {
        //
        return view('administrator.remote-attendance.index');
    }

    public function table(){
        $user = Auth::user();
        $requests = RemoteAttendance::join('users as u','user_id','=','u.id')->select(['remote_attendance.*','u.nik','u.name']);
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
        return view('administrator.remote-attendance.create');
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
        $validator = Validator::make(request()->all(), [
            'user_id'  => 'required|exists:users,id',
            'location_name'  => 'required',
            'timezone' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator->errors());
        }
        $start_date = date('Y-m-d' , strtotime($request->start_date));
        $end_date   = date('Y-m-d' , strtotime($request->end_date));
        if($start_date > $end_date){
            return redirect()
                ->back()
                ->withInput()
                ->with('message-error','Date is invalid!');
        }

        $cek = RemoteAttendance::where(['user_id'=>$request->user_id])
            ->whereRaw("((start_date <= '$start_date' and end_date >= '$start_date') OR (start_date <= '$end_date' and end_date >= '$end_date') OR (start_date > '$start_date' and end_date < '$end_date'))")->count();
        if($cek > 0){
            return redirect()
                ->back()
                ->withInput()
                ->with('message-error','This employee already has remote attendance at these date');
        }
        $data                   = new RemoteAttendance();
        $data->user_id          = $request->user_id;
        $data->location_name    = $request->location_name;
        $data->latitude         = $request->latitude;
        $data->longitude        = $request->longitude;
        $data->radius           = $request->radius;
        $data->timezone         = $request->timezone;
        $data->start_date       = $start_date;
        $data->end_date         = $end_date;

        $data->save();

        return redirect()->route('administrator.remote-attendance.index')->with('message-success', 'Data saved successfully !');
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
        $params['data'] = RemoteAttendance::where('id', $id)->first();

        return view('administrator.remote-attendance.edit')->with($params);
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
        $validator = Validator::make(request()->all(), [

            'location_name'  => 'required',
            'timezone' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius' => 'required|integer',
        ]);



        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator->errors());
        }
        $data                   = RemoteAttendance::find($id);
        if(!$data){
            return redirect()
                ->back()
                ->withInput()
                ->with('message-error','Data not found!');
        }
        $start_date = date('Y-m-d' , strtotime($request->start_date));
        $end_date   = date('Y-m-d' , strtotime($request->end_date));
        if($start_date > $end_date){
            return redirect()
                ->back()
                ->withInput()
                ->with('message-error','Date is invalid!');
        }

        $cek = RemoteAttendance::where(['user_id'=>$data->user_id])
            ->where('id','!=',$id)
            ->whereRaw("((start_date <= '$start_date' and end_date >= '$start_date') OR (start_date <= '$end_date' and end_date >= '$end_date') OR (start_date > '$start_date' and end_date < '$end_date'))")->count();
        if($cek > 0){
            return redirect()
                ->back()
                ->withInput()
                ->with('message-error','This employee already has remote attendance at these date');
        }

        $data->location_name    = $request->location_name;
        $data->latitude         = $request->latitude;
        $data->longitude        = $request->longitude;
        $data->radius           = $request->radius;
        $data->timezone         = $request->timezone;
        $data->start_date       = $start_date;
        $data->end_date         = $end_date;

        $data->save();

        return redirect()->route('administrator.remote-attendance.index')->with('message-success', 'Data berhasil diupdate!');
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
        if(RemoteAttendance::destroy($id)){
            return response()->json(['status' => 'success', 'message' => 'Data has been deleted']);
        }
        else{
            return response()->json(['status' => 'error', 'message' => 'Cannot delete data'],404);
        }
    }
}
