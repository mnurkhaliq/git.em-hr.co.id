<?php

namespace App\Http\Controllers\Api;

use App\Http\Middleware\ConnectionMiddleware;
use App\Models\Shift;
use App\Models\ShiftDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ShiftController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(ConnectionMiddleware::class);
        $this->middleware('jwt.auth');
    }

    public function getShiftDetail(Request $request){
        $currentUser = Auth::user();
        $shift = Shift::find($currentUser->shift_id);
        $newDetails = [];
        if($shift) {
            $days    = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];

            for ($i = 0; $i < 7; $i++){
                $newDetail = new ShiftDetail();
                $newDetail->id          = null;
                $newDetail->shift_id    = $shift->id;
                $newDetail->day         = $days[$i];
                $newDetail->clock_in    = null;
                $newDetail->clock_out   = null;
                $newDetail->created_at  = null;
                $newDetail->updated_at  = null;
                array_push($newDetails,$newDetail);
            }
            $details = $shift->details;
            foreach ($details as $detail){
                $check = array_search($detail->day,$days);
                if($check !== false)
                    $newDetails[$check] = $detail;
            }
            unset($shift->details);
            $shift->details = $newDetails;
        }
        return response()->json(
            [
                'status' => 'success',
                'message'=>'Data is collected',
                'data' => $shift
            ],
            200
        );
    }
}
