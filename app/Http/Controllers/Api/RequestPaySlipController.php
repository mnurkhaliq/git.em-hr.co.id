<?php

namespace App\Http\Controllers\Api;

use App\Http\Middleware\ConnectionMiddleware;
use App\Http\Resources\RequestPaySlipResource;
use App\Models\PayrollHistory;
use App\Models\RequestPaySlip;
use App\Models\RequestPaySlipItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use App\User;
use Illuminate\Support\Facades\Config;

class RequestPaySlipController extends Controller
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
    public function index(Request $request)
    {
        //
        $user = Auth::user();
        $status = $request->input('status','[1,2,3]');
        if(!isJsonValid($status))
            return response()->json(['status' => 'error','message'=>'Status is invalid'], 404);
        $status = json_decode($status);
        $histories = RequestPaySlip::with(['items'])->where(['user_id'=>$user->id])->whereIn('status',$status)->orderBy('created_at','DESC');
        $totalData = $histories->get()->count();
        $histories = $histories->paginate(10);
        $data = [
            'current_page' => $histories->currentPage(), // get current page number
            'total_page' => $histories->total() ? $histories->lastPage() : $histories->total(), // get last page number
            'total_data' => $totalData,
            'request_payslips' => RequestPaySlipResource::collection($histories)
        ];
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
        $user = Auth::user();
        $validator = Validator::make(request()->all(), [
            'months'  => 'required|array',
            'months.*' => 'integer|digits_between:5,6'
        ],[
            'months.required' => 'Months are required!',
            'months.*' => 'Month format is wrong',
        ]);
        if ($validator->fails())
            return response()->json(['status' => 'error','message'=>$validator->errors()->first()], 403);
        $data                 = new RequestPaySlip();
        $data->user_id        = $user->id;
        $data->status         = 1;
        $data->save();

        foreach($request->months as $month)
        {
            $item               = new RequestPaySlipItem();
            $item->tahun        = substr($month,0,4);
            $item->request_pay_slip_id = $data->id;
            $item->bulan        = substr($month,4);
            $item->status       = 1;
            $item->user_id      = $user->id;
            $item->save();
        }

        $admins = getAdminByModule(13);
        $params = getEmailConfig();
        Config::set('database.default', 'mysql');
        foreach ($admins as $key => $value) {
            if($value->email != null){
                $params['view']     = 'email.request-payslip';
                $params['subject']  = get_setting('mail_name') . ' - Request Pay-Slip';
                $params['email']    = $value->email;
                $params['data']     = $request->months;
                $params['text']     = '<p><strong>Dear Sir/Madam ' . $value->name . '</strong>,</p> <p> ' . $item->user->name . '  / ' . $item->user->nik . ' applied for Request Pay Slip and currently waiting your approval.</p>';
                $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                dispatch($job);
            }
        }
        Config::set('database.default', session('db_name', 'mysql'));

        $params['dataArray']    = RequestPaySlipItem::where('request_pay_slip_id', $data->id)->get();
        $params['months']       = [1=>'January',2=>'February',3=>'March',4=>'April',5=>'May',6=>'June',7=>'July',8=>'Agust',9=>'September',10=>'October',11=>'November',12=>'December'];
        $bulan = [];
        $j= 0;
        foreach($params['dataArray']  as $k => $i) {
            $item = PayrollHistory::select(['payroll_history.*', \DB::raw("month(created_at) as bulan")])
                    ->where(['user_id' => auth()->user()->id])
                    ->whereMonth('created_at', $i->bulan)
                    ->whereYear('created_at', $i->tahun)
                    ->orderBy('id', 'DESC')
                    ->first();

            if($item==null){
                $params['cek'][$k] = 'checked';
                $bulan[$j] = $params['months'][$i->bulan].' '.$i->tahun;
                $j = $j+1;
            }
        }

        if($bulan != []){
            $params = getEmailConfig();
            Config::set('database.default', 'mysql');
            if(auth()->user()->email != null){
                $params['view']     = 'email.request-payslip-user';
                $params['subject']  = get_setting('mail_name') . ' - Request Pay-Slip';
                $params['email']    = auth()->user()->email;
                $params['data']     = $bulan;
                $params['text']     = '<p><strong>Dear Sir/Madam ' . auth()->user()->name . '</strong>,</p> <p>  Your Request Pay Slip has been sent, for the month below it will be emailed manually by admin/hr.</p>';
                $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                dispatch($job);
            }
            Config::set('database.default', session('db_name', 'mysql'));
        }

        return response()->json(
            [
                'status' => 'success',
                'message' => 'You have Successfully Request Pay Slip !',
                'data' => new RequestPaySlipResource($data)
            ], 200);
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
        $user = Auth::user();
        $data['request_payslip'] = new RequestPaySlipResource(RequestPaySlip::where(['user_id'=>$user->id])->findOrFail($id));
        return response()->json(
            [
                'status' => 'success',
                'message' => 'Successfully',
                'data' => $data
            ], 200);
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

    public function data(){
        $user = Auth::user();
        $options = PayrollHistory::select(DB::raw('year(created_at) as year'))->where('user_id', $user->id)->groupBy('year')->get();
        $data['options'] = [];
        $monthArr = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];
        foreach ($options as $option){
            $newOption = [
                'year' => $option->year,
                'months' => []
            ];
            $l = 0;
            if($option->year < date('Y'))
                $l = 12;
            else if($option->year == date('Y'))
                $l = date('m');
            for($b = 1; $b <= $l; $b++)
            {
                $newMonth['id'] = $b;
                $newMonth['name'] = $monthArr[$b];
                array_push($newOption['months'],$newMonth);
            }
            array_push($data['options'],$newOption);
        }
        return response()->json(
            [
                'status' => 'success',
                'message' => 'Successfully',
                'data' => $data
            ], 200);
    }
}
