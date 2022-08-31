<?php

namespace App\Http\Controllers\Administrator;

use App\Models\Payroll;
use App\Models\PayrollHistory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\RequestPaySlip;
use App\Models\RequestPaySlipItem;
use App\Models\StructureOrganizationCustom; 
use App\Models\OrganisasiDivision;
use App\Models\OrganisasiPosition;
use App\Models\LoanPayment;
use App\Models\Training;
use App\Models\CashAdvance;
use App\User;
use File;
use Illuminate\Support\Facades\Config;

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
        $this->middleware('module:13');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $user = \Auth::user();
        if($user->project_id != NULL)
        {
            $data = RequestPaySlip::select('request_pay_slip.*')->join('users', 'users.id','=','request_pay_slip.user_id')->where('users.project_id', $user->project_id)->orderBy('id', 'DESC');
            $params['division'] = OrganisasiDivision::where('project_id', $user->project_id)->select('organisasi_division.*')->get();
            $params['position'] = OrganisasiPosition::where('project_id', $user->project_id)->select('organisasi_position.*')->get();
        } else
        {
            $data = RequestPaySlip::select('request_pay_slip.*')->join('users', 'users.id','=','request_pay_slip.user_id')->orderBy('id', 'DESC');
            $params['division'] = OrganisasiDivision::all();
            $params['position'] = OrganisasiPosition::all();
        }
        $params['structure'] = getStructureName();
        
        if(count(request()->all())) {
            \Session::put('rps-employee_status', request()->employee_status);
            \Session::put('rps-position_id', request()->position_id);
            \Session::put('rps-division_id', request()->division_id);
            \Session::put('rps-name', request()->name);
        }

        $employee_status    = \Session::get('rps-employee_status');
        $position_id        = \Session::get('rps-position_id');
        $division_id        = \Session::get('rps-division_id');
        $name               = \Session::get('rps-name');
        
        if(request())
        {
            if (!empty($name)) {
                $data = $data->where(function ($table) use($name) {
                    $table->where('users.name', 'LIKE', '%' . $name . '%')
                        ->orWhere('users.nik', 'LIKE', '%' . $name . '%');
                });
            }

            if(!empty($employee_status))
            {
                $data = $data->where('users.organisasi_status', $employee_status);
            }

            if((!empty($division_id)) and (empty($position_id))) 
            {   
                $data = $data->join('structure_organization_custom','users.structure_organization_custom_id','=','structure_organization_custom.id')->where('structure_organization_custom.organisasi_division_id',$division_id);
            }
            if((!empty($position_id)) and (empty($division_id)))
            {   
                $data = $data->join('structure_organization_custom','users.structure_organization_custom_id','=','structure_organization_custom.id')->where('structure_organization_custom.organisasi_position_id',$position_id);
            }
            if((!empty($position_id)) and (!empty($division_id)))
            {
                $data = $data->join('structure_organization_custom','users.structure_organization_custom_id','=','structure_organization_custom.id')->where('structure_organization_custom.organisasi_position_id',$position_id)->where('structure_organization_custom.organisasi_division_id',$division_id);
            }
        }

        if(request()->reset == 1)
        {
            \Session::forget('rps-employee_status');
            \Session::forget('rps-position_id');
            \Session::forget('rps-division_id');
            \Session::forget('rps-name');

            return redirect()->route('administrator.request-pay-slip.index');
        }

        $params['data'] = $data->get();
        return view('administrator.request-pay-slip.index')->with($params);
    }

    /**
     * [proses description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function proses($id)
    {
        $params['datamaster']   = RequestPaySlip::where('id', $id)->first();
        $params['dataArray']    = RequestPaySlipItem::where('request_pay_slip_id', $id)->get();
        $params['months']       = [1=>'January',2=>'February',3=>'March',4=>'April',5=>'May',6=>'June',7=>'July',8=>'Agust',9=>'September',10=>'October',11=>'November',12=>'December'];
        $bulan = [];
        $params['cek'] = [];
        foreach($params['dataArray']  as $k => $i) {
            $bulan[$k] = $params['months'][$i->bulan];

            //$items   = \DB::select(\DB::raw("SELECT payroll_history.*, month(created_at) as bulan FROM payroll_history WHERE MONTH(created_at)=". $i->bulan ." and user_id=". $data->user_id ." and YEAR(created_at) =". $request->tahun. ' ORDER BY id DESC'));
            $item = PayrollHistory::select(['payroll_history.*', \DB::raw("month(created_at) as bulan")])
                ->where(['user_id' => $params['datamaster']->user_id])
                ->whereMonth('created_at', $i->bulan)
                ->whereYear('created_at', $i->tahun)
                ->orderBy('id', 'DESC')
                ->first();
            if($item!= null){
                $params['cek'][$k] = 'not checked';
            }
            elseif($item==null){
                $params['cek'][$k] = 'checked';
            }
        }

        return view('administrator.request-pay-slip.proses')->with($params);
    }

    /**
     * [submit description]
     * @param  Request $request [description]
     * @param  [type]  $id      [description]
     * @return [type]           [description]
     */
    public function submit(Request $request, $id)
    {
        $request->validate([
            'note' => 'required'
        ],
        [
            'note.required' => 'the note field is required!',
        ]); 
        $data = RequestPaySlip::where('id', $id)->first();
        $data->note     = $request->note;
        $data->status   = 2;
        $data->save();

        // $bulanItem = RequestPaySlipItem::where('request_pay_slip_id', $id)->get();
        $bulanItem = $data->items;
        $bulan = [];
        $tahun = [];
        $total = 0;
        $dataArray = [];
        $bulanArray = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];
        $skip = false;
        $payrollHistory = [];

        // cek dulu
        foreach($bulanItem as $k => $i) {
            // $bulan[$k] = $bulanArray[$i->bulan];

            //$items   = \DB::select(\DB::raw("SELECT payroll_history.*, month(created_at) as bulan FROM payroll_history WHERE MONTH(created_at)=". $i->bulan ." and user_id=". $data->user_id ." and YEAR(created_at) =". $request->tahun. ' ORDER BY id DESC'));
            $item = PayrollHistory::select(['payroll_history.*', \DB::raw("month(created_at) as bulan")])
                ->where(['user_id' => $data->user_id])
                ->whereMonth('created_at', $i->bulan)
                ->whereYear('created_at', $i->tahun)
                ->orderBy('id', 'DESC')
                ->first();
            
            if(!$item){
                continue;
            } else if(!get_setting('payslip_lock') && ($item->is_lock == 0 || empty($item->is_lock) || $item->is_lock == null)) {
                $skip = true;
                break;
            } else {
                $payrollHistory[$k] = $item;
            }
        }
        if(!$skip) {
            if (count($payrollHistory)) {
                foreach ($bulanItem as $k => $i) {
    
                    //$items   = \DB::select(\DB::raw("SELECT payroll_history.*, month(created_at) as bulan FROM payroll_history WHERE MONTH(created_at)=". $i->bulan ." and user_id=". $data->user_id ." and YEAR(created_at) =". $request->tahun. ' ORDER BY id DESC'));
                    // $item = PayrollHistory::select(['payroll_history.*', \DB::raw("month(created_at) as bulan")])
                    //     ->where(['user_id' => $data->user_id])
                    //     ->whereMonth('created_at', $i->bulan)
                    //     ->whereYear('created_at', $i->tahun)
                    //     ->orderBy('id', 'DESC')
                    //     ->first();
    
                    if (!isset($payrollHistory[$k])) {
                        continue;
                    } else {
                        $total++;
                        $bulan[$k] = $bulanArray[$i->bulan];
                        $tahun[$k] = $i->tahun;
                        $dataArray[$k] = $payrollHistory[$k];
    
                    }
                }
                info("PREPARING SENDING PAYSLIP MAIL : ");
    
                $params['total'] = $total;
                $params['dataArray'] = $dataArray;
                $params['data'] = $data;
                $params['bulan'] = $bulan;
                $params['tahun'] = $tahun;
                $params['user'] = $data->user;
                info($dataArray);
                $payroll         = Payroll::where('user_id',$data->user_id)->first();
                $view = view('administrator.request-pay-slip.print-pay-slip')->with($params);
    
                $pdf = \App::make('dompdf.wrapper');
                $pdf->loadHTML($view);
                info("Loading View to PDF : ");
                $pdf->stream();
                info("PDF Streamed : ");
                if($payroll && !empty($payroll->pdf_password))
                    $pdf->setEncryption($payroll->pdf_password);
                $output = $pdf->output();
                info("PDF CREATED : ");
                $destinationPath = public_path('/storage/temp/');
    
                if (!File::exists($destinationPath)) {
                    $path = public_path() . '/storage/temp/';
                    File::makeDirectory($destinationPath, $mode = 0777, true, true);
                }
    
                file_put_contents($destinationPath . $data->user->nik . '.pdf', $output);
    
                $file = $destinationPath . $data->user->nik . '.pdf';
                info("PAYSLIP PDF WRITTEN : ".$bulanArray[$i->bulan]);
                // send email
                $objDemo = new \stdClass();
                $objDemo->content = view('administrator.request-pay-slip.email-pay-slip');
    
                if ($data->user->email != "") {
                    try {
                        \Mail::send('administrator.request-pay-slip.email-pay-slip', $params,
                            function ($message) use ($file, $data, $bulan) {
                                $message->to($data->user->email);
                                $message->subject('Request Pay-Slip Bulan (' . implode('/', $bulan) . ')');
                                $message->attach($file, array(
                                        'as' => 'Payslip-' . $data->user->nik . '(' . implode('/', $bulan) . ').pdf',
                                        'mime' => 'application/pdf')
                                );
                                $message->setBody('');
                            }
                        );
    
                        if(get_setting('payslip_lock')) {
                            foreach ($dataArray as $key => $value) {
                                if($value->is_lock == 0 || empty($value->is_lock) || $value->is_lock == null) {
                                    $value->is_lock = 1;
                                    $value->save();

                                    LoanPayment::where('payroll_history_id', $value->id)->update([
                                        'status' => 5,
                                        'approval_user_id' => \Auth::user()->id
                                    ]);

                                    Training::where('payroll_history_id', $value->id)->update([
                                        'status_payroll' => 1,
                                        'payroll_approval_user_id' => \Auth::user()->id
                                    ]);
                                    CashAdvance::where('payroll_history_id', $value->id)->update([
                                        'status_payroll' => 1,
                                        'payroll_approval_user_id' => \Auth::user()->id
                                    ]);
                                }
                            }
                        }
                        info("PAYSLIP MAIL HAS SENT  : ");
                    }catch (\Swift_TransportException $e){
                        return redirect()->back()->with('message-error', 'Email config is invalid!');
                    }
                }
            }

            \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $data->user->id, $data, 'payslip');

            if($data->user->firebase_token){
                $config = [
                    'title' => "Request Pay Slip",
                    'content' => strip_tags('<p><strong>Dear Sir/Madam ' . $data->user->name . '</strong>,</p> <p>  Your payslip request has been processed by admin. Please check your email.</p>'),
                    'type' => "payslip",
                    'firebase_token' => $data->user->firebase_token
                ];
                $notifData = [
                    'id' => $data->id
                ];
                $db = Config::get('database.default', 'mysql');
                Config::set('database.default', 'mysql');
                dispatch((new \App\Jobs\SendPushTokens($notifData,$config))->onQueue('push'));
                Config::set('database.default', $db);
            }
            return redirect()->route('administrator.request-pay-slip.index')->with('message-success', 'Request Pay Slip successfully processed');
        }
        return redirect()->route('administrator.request-pay-slip.index')->with('message-error', 'Payroll not locked yet!');
    }
}
