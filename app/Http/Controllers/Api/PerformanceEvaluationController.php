<?php

namespace App\Http\Controllers\Api;

use App\Http\Middleware\ConnectionMiddleware;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PerformanceEvaluationController extends Controller
{
    public function __construct()
    {
        $this->middleware(ConnectionMiddleware::class);
        $this->middleware('jwt.auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $user = Auth::user();
        $data['surveys'] = getKpiList($user->id);
        return response()->json(
            [
                'status' => 'success',
                'message' => 'Successfully',
                'data' => $data
            ], 200);
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

    public function getDetail($id)
    {
        $user = Auth::user();
        $data['details'] = getKpiDetail($user,null,$id);
        if($data['details']){
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Successfully',
                    'data' => $data
                ], 200);
        }
        else{
            return response()->json(
                [
                    'status' => 'failed',
                    'message' => 'Data not found',
                    'data' => null
                ], 200);
        }
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
}
