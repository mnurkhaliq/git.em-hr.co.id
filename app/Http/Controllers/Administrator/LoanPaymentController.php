<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\LoanPayment;
use App\Models\OrganisasiDivision;
use App\Models\OrganisasiPosition;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class LoanPaymentController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
        $this->middleware('module:33');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = \Auth::user();
        if ($user->project_id != null) {
            $params['division'] = OrganisasiDivision::where('project_id', $user->project_id)->select('organisasi_division.*')->get();
            $params['position'] = OrganisasiPosition::where('project_id', $user->project_id)->select('organisasi_position.*')->get();
        } else {
            $params['division'] = OrganisasiDivision::all();
            $params['position'] = OrganisasiPosition::all();
        }
        $params['structure'] = getStructureName();

        $data = LoanPayment::whereNotNull('status')->orderBy('id', 'DESC');

        if(count(request()->all())) {
            \Session::put('lp-employee_status', request()->employee_status);
            \Session::put('lp-position_id', request()->position_id);
            \Session::put('lp-division_id', request()->division_id);
            \Session::put('lp-name', request()->name);
            \Session::put('lp-number', request()->number);
        }

        $employee_status    = \Session::get('lp-employee_status');
        $position_id        = \Session::get('lp-position_id');
        $division_id        = \Session::get('lp-division_id');
        $name               = \Session::get('lp-name');
        $number             = \Session::get('lp-number');

        if (request()) {
            $data = $data->whereHas('loan', function ($table) {
                if (!empty($number)) {
                    $table->where('number', 'LIKE', '%' . $number . '%');
                }
                if (!empty($name) || !empty($position_id) || !empty($division_id)) {
                    $table->whereHas('user', function ($table) use($name, $position_id, $division_id) {
                        if (!empty($name)) {
                            $table->where(function ($table) use($name) {
                                $table->where('name', 'LIKE', '%' . $name . '%')->orWhere('nik', 'LIKE', '%' . $name . '%');
                            });
                        }
                        if (!empty($position_id) || !empty($division_id)) {
                            $table->whereHas('structure', function ($table) use($position_id, $division_id) {
                                if (!empty($position_id)) {
                                    $table->where('organisasi_position_id', $position_id);
                                }
                                if (!empty($division_id)) {
                                    $table->where('organisasi_division_id', $division_id);
                                }
                            });
                        }
                    });
                }
            });

            if (request()->action == 'download') {
                return $this->downloadExcel($data->get());
            }
        }

        if(request()->reset == 1)
        {
            \Session::forget('lp-employee_status');
            \Session::forget('lp-position_id');
            \Session::forget('lp-division_id');
            \Session::forget('lp-name');
            \Session::forget('lp-number');

            return redirect()->route('administrator.loan-payment.index');
        }

        $params['data'] = $data->with('approver')->get();

        return view('administrator.loan-payment.index')->with($params);
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
        $user = \Auth::user();
        $params = getEmailConfig();
        $params['data'] = LoanPayment::where('id', $id)->first();
        if ($request->status == 0) {
            $params['text'] = '<p><strong>Dear Sir/Madam ' . $params['data']->loan->user->name . '</strong>,</p> <p>  Submission of your Loan Payment <strong style="color: red;">REJECTED</strong>.</p>';
            $params['data']->status = 3;
        } else {
            $params['text'] = '<p><strong>Dear Sir/Madam ' . $params['data']->loan->user->name . '</strong>,</p> <p>  Submission of your Loan Payment <strong style="color: green;">APPROVED</strong>.</p>';
            $params['data']->status = 2;
        }
        $params['data']->approval_user_id = $user->id;
        $params['data']->approval_note = $request->approval_note;
        $params['data']->approval_date = now();
        $params['data']->save();

        Config::set('database.default', 'mysql');
        if (!empty($params['data']->loan->user->email)) {
            $params['subject'] = get_setting('mail_name') . ' - Loan Payment';
            $params['view'] = 'email.loan-payment-approval';
            $params['email'] = $params['data']->loan->user->email;
            $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
            dispatch($job);
        }
        Config::set('database.default', session('db_name', 'mysql'));

        \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $params['data']->loan->user->id, $params['data'], 'loan_payment');

        if ($params['data']->loan->user->firebase_token) {
            $config = [
                'title' => "Loan Payment",
                'content' => strip_tags($params['text']),
                'type' => "loan_payment",
                'firebase_token' => [$params['data']->loan->user->firebase_token],
            ];
            $notifData = [
                'id' => $params['data']->loan->id,
            ];
            $db = Config::get('database.default', 'mysql');
            Config::set('database.default', 'mysql');
            dispatch((new \App\Jobs\SendPushTokens($notifData, $config))->onQueue('push'));
            Config::set('database.default', $db);
        }

        return redirect()->route('administrator.loan-payment.index')->with('message-success', 'Form Loan Successfully Processed!');
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

    public function proses($id)
    {
        $params['data'] = LoanPayment::where('id', $id)->first();

        return view('administrator.loan-payment.edit')->with($params);
    }

    public function downloadExcel($data)
    {
        $params = [];

        foreach ($data as $no => $item) {
            $params[$no]['NO'] = $no + 1;
            $params[$no]['LOAN NUMBER'] = $item->loan->number;
            $params[$no]['EMPLOYEE'] = $item->loan->user->nik . ' - ' . $item->loan->user->name;
            $params[$no]['POSITION'] = (isset($item->loan->user->structure->position->name) ? $item->loan->user->structure->position->name : '') . (isset($item->loan->user->structure->division->name) ? ' - ' . $item->loan->user->structure->division->name : '') . (isset($item->loan->user->structure->title->name) ? ' - ' . $item->loan->user->structure->title->name : '');
            $params[$no]['TENOR'] = $item->tenor;
            $params[$no]['DUE DATE'] = $item->due_date ? date('Y-m-d', strtotime($item->due_date)) : '';
            $params[$no]['AMOUNT'] = $item->amount;
            $params[$no]['REFUND METHOD'] = $item->payment_type == 1 ? 'Deduct Salary' : ($item->payment_type == 2 ? 'Transfer to Company' : '');
            $params[$no]['PAYMENT NOTE'] = $item->user_note;
            $params[$no]['PAYMENT DATE'] = $item->payment_date ? date('Y-m-d', strtotime($item->payment_date)) : '';
            $params[$no]['SUBMIT DATE'] = $item->submit_date ? date('Y-m-d', strtotime($item->submit_date)) : '';
            $params[$no]['PAYROLL'] = $item->payrollHistory ? date('Y-m', strtotime($item->payrollHistory->created_at)) : '';
            $params[$no]['STATUS'] = !$item->status ? 'Not Yet Paid' : ($item->status == 1 ? 'Waiting Approval' : ($item->status == 2 ? 'Approved' : 'Rejected'));
            $params[$no]['APPROVER'] = $item->approver ? $item->approver->nik . ' - ' . $item->approver->name : '';
            $params[$no]['APPROVAL NOTE'] = $item->approval_note;
            $params[$no]['APPROVAL DATE'] = $item->approval_date ? date('Y-m-d', strtotime($item->approval_date)) : '';
        }

        return (new \App\Models\KaryawanExport($params, 'Report Loan Payment'))->download('EM-HR.Report-Loan-Payment-' . date('d-m-Y') . '.xlsx');
    }

}
