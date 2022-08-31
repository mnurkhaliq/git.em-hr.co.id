<?php

namespace App\Http\Controllers\Karyawan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\RequestPaySlip;
use App\Models\RequestPaySlipItem;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Facades\Config;
use App\Models\PayrollHistory;

class RequestPaySlipController extends Controller
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
        $params['data'] = RequestPaySlip::where('user_id', \Auth::user()->id)->orderBy('id', 'DESC')->get();

        return view('karyawan.request-pay-slip.index')->with($params);
    }

    /**
     * [create description]
     * @return [type] [description]
     */
    public function create()
    {
        $user = Auth::user();
        if($user->join_date == null)
            return redirect()->route('karyawan.request-pay-slip.index')->with('message-error','Join Date is not defined yet, please contact your admin!');
        $data['join_year'] = (int)date('Y',strtotime($user->join_date));
        $data['join_month'] = (int)date('m',strtotime($user->join_date));
        $data['now_year'] = (int)date('Y');
        $data['now_month'] =(int)date('m');
        $data['months'] = [1=>'January',2=>'February',3=>'March',4=>'April',5=>'May',6=>'June',7=>'July',8=>'Agust',9=>'September',10=>'October',11=>'November',12=>'December'];
        return view('karyawan.request-pay-slip.create')->with($data);
    }

    /**
     * [edit description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function edit($id)
    {
        $data = RequestPaySlip::where('id', $id)->first();

        if (!IsAccess($data)) {
            return redirect()->route('karyawan.request-pay-slip.index')->with('message-error', 'You don\'t have permission to perform this action!');
        }

        $params['dataArray']    = RequestPaySlipItem::where('request_pay_slip_id', $id)->get();
        $params['months']       = [1=>'January',2=>'February',3=>'March',4=>'April',5=>'May',6=>'June',7=>'July',8=>'Agust',9=>'September',10=>'October',11=>'November',12=>'December'];
        return view('karyawan.request-pay-slip.edit')->with($params);
    }

    /**
     * [desctroy description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function destroy($id)
    {
        $data = RequestPaySlip::where('id', $id)->first();
        $data->delete();

        return redirect()->route('karyawan.request-pay-slip.index')->with('message-success', 'Data berhasi di hapus');
    }

    /**
     * [store description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function store(Request $request)
    {
        if(is_null($request->bulan)){
            return redirect()->route('karyawan.request-pay-slip.create')->with('message-error', 'Your request not completed. Please fill year and month!');
        } else{
            $data                       = new RequestPaySlip();
            $data->user_id              = \Auth::user()->id;
            $data->status               = 1;
            $data->save();

            foreach($request->bulan as $bulan)
            {
                $item               = new RequestPaySlipItem();
                $item->tahun        = substr($bulan,0,4);
                $item->request_pay_slip_id = $data->id;
                $item->bulan        = substr($bulan,4);
                $item->status       = 1;
                $item->user_id      = \Auth::user()->id;
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
                    $params['data']     = $request->bulan;
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

            return redirect()->route('karyawan.request-pay-slip.index')->with('message-success', 'You have Successfully Request Pay Slip !');
        }
    }
}
