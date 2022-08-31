<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\HistoryApprovalLoan;
use App\Models\Loan;
use App\Models\LoanPayment;
use Carbon\Carbon;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class ApprovalLoanController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $params['data'] = cek_loan_approval();

        return view('karyawan.approval-loan.index')->with($params);
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

    public function detail($id)
    {
        $params['data'] = cek_loan_id_approval($id);

        if (!$params['data']) {
            return redirect()->route('karyawan.approval-loan.index')->with('message-error', 'You don\'t have permission to perform this action!');
        }

        $params['data'] = Loan::where('id', $id)->first();
        $params['history'] = HistoryApprovalLoan::where('loan_id', $id)->where('structure_organization_custom_id', \Auth::user()->structure_organization_custom_id)->first();
        $params['payment'] = checkModule(13) ? [1 => 'Deduct Salary', 2 => 'Transfer to Company'] : [2 => 'Transfer to Company'];

        return view('karyawan.approval-loan.detail')->with($params);
    }

    public function table($id)
    {
        $data = LoanPayment::where('loan_id', $id)->with('approver');

        return DataTables::of($data)
            ->addColumn('column_amount', function ($item) {
                return format_idr($item->amount);
            })
            ->addColumn('column_payment_type', function ($item) {
                return $item->payment_type == 1 ? 'Deduct Salary' : ($item->payment_type == 2 ? 'Transfer to Company' : '');
            })
            ->addColumn('column_status', function ($item) {
                return !$item->status ? '<button id="status" type="button" class="btn btn-default btn-xs">Not Yet Paid</button>' : ($item->status == 1 ? '<button id="status" type="button" class="btn btn-warning btn-xs">Waiting Approval</button>' : ($item->status == 2 ? '<button id="status" type="button" class="btn btn-success btn-xs">Approved</button>' : ($item->status == 3 ? '<button id="status" type="button" class="btn btn-danger btn-xs">Rejected</button>' : ($item->status == 4 ? '<button id="status" type="button" class="btn btn-warning btn-xs">Waiting Lock Payroll</button>' : '<button id="status" type="button" class="btn btn-success btn-xs">Payroll Locked</button>'))));
            })
            ->addColumn('column_action', function ($item) {
                return !$item->status || ($item->status && $item->status >= 4) ? '' : '<button id="action" type="button" class="btn btn-info btn-xs m-r-5"><i class="fa fa-search-plus"></i> Payment Detail</button>';
            })
            ->addColumn('column_payroll', function ($item) {
                return $item->payrollHistory ? Carbon::parse($item->payrollHistory->created_at)->format('Y-m') : null;
            })
            ->rawColumns(['column_amount', 'column_payment_type', 'column_status', 'column_action', 'column_payroll'])
            ->make(true);
    }

    public function proses(Request $request)
    {
        $request->validate([
            'noteApproval' => 'required',
        ], [
            'noteApproval.required' => 'the note field is required!',
        ]);

        $user = Auth::user();

        $loan = Loan::find($request->id);
        $params = getEmailConfig();
        $params['data'] = $loan;
        $params['value'] = $loan->historyApproval;
        $params['subject'] = get_setting('mail_name') . ' - Loan';
        $params['view'] = 'email.loan-approval';

        $approval = HistoryApprovalLoan::where(['loan_id' => $loan->id, 'structure_organization_custom_id' => $user->structure_organization_custom_id])->first();
        $approval->approval_id = $user->id;
        $approval->is_approved = $request->status;
        $approval->date_approved = date('Y-m-d H:i:s');
        $approval->note = $request->noteApproval;
        $approval->save();

        $db = Config::get('database.default', 'mysql');

        $notifTitle = "";
        $notifType = "";
        $userApprovalTokens = [];
        $userApprovalIds = [];

        if ($approval->is_approved == 0) { // Jika rejected
            $loan->status = 3;
            $params['text'] = '<p><strong>Dear Sir/Madam ' . $loan->user->name . '</strong>,</p> <p>  Submission of your Loan <strong style="color: red;">REJECTED</strong>.</p>';
            Config::set('database.default', 'mysql');
            if (!empty($loan->user->email)) {
                $params['email'] = $loan->user->email;
                $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                dispatch($job);
            }
            Config::set('database.default', $db);

            $notifTitle = "Loan";
            $notifType = "loan";
            if ($loan->user->firebase_token) {
                array_push($userApprovalTokens, $loan->user->firebase_token);
            }
            array_push($userApprovalIds, $loan->user->id);
        } else if ($approval->is_approved == 1) {
            $lastApproval = $loan->historyApproval->last();
            if ($approval->structure_organization_custom_id == $lastApproval->structure_organization_custom_id) {
                $loan->status = 1;
                $admins = getAdminByModule(33);
                Config::set('database.default', 'mysql');
                foreach ($admins as $key => $value) {
                    if (empty($value->email)) {
                        continue;
                    }

                    $params['text'] = '<p><strong>Dear Sir/Madam ' . $value->name . '</strong>,</p> <p> ' . $loan->user->name . '  / ' . $loan->user->nik . ' applied for Loan and currently waiting your approval.</p>';
                    $params['email'] = $value->email;
                    $params['value'] = [];
                    $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                    dispatch($job);
                }
                Config::set('database.default', $db);
            } else {
                $loan->status = 1;
                $nextApproval = HistoryApprovalLoan::where(['loan_id' => $loan->id, 'setting_approval_level_id' => ($approval->setting_approval_level_id + 1)])->first();
                if ($nextApproval) {
                    $userApproval = user_approval_custom($nextApproval->structure_organization_custom_id);
                    if ($userApproval) {
                        Config::set('database.default', 'mysql');
                        foreach ($userApproval as $key => $value) {
                            if (empty($value->email)) {
                                continue;
                            }

                            $params['text'] = '<p><strong>Dear Sir/Madam ' . $value->name . '</strong>,</p> <p> ' . $loan->user->name . '  / ' . $loan->user->nik . ' applied for Loan and currently waiting your approval.</p>';
                            $params['email'] = $value->email;
                            $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                            dispatch($job);
                        }
                        Config::set('database.default', $db);

                        $params['text'] = '<p> ' . $loan->user->name . '  / ' . $loan->user->nik . ' applied for Loan and currently waiting your approval.</p>';
                        $notifTitle = "Loan Approval";
                        $notifType = "loan_approval";
                        $userApprovalTokens = user_approval_firebase_tokens($nextApproval->structure_organization_custom_id);
                        $userApprovalIds = user_approval_id($nextApproval->structure_organization_custom_id);
                    }
                }
            }
        }
        $loan->save();

        foreach ($userApprovalIds as $value) {
            \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $value, $loan, $notifType);
        }

        if (count($userApprovalTokens) > 0) {
            $config = [
                'title' => $notifTitle,
                'content' => strip_tags($params['text']),
                'type' => $notifType,
                'firebase_token' => $userApprovalTokens,
            ];
            $notifData = [
                'id' => $loan->id,
            ];
            $db = Config::get('database.default', 'mysql');
            Config::set('database.default', 'mysql');
            dispatch((new \App\Jobs\SendPushTokens($notifData, $config))->onQueue('push'));
            Config::set('database.default', $db);
        }

        return redirect()->route('karyawan.approval-loan.index')->with('message-success', 'Form Loan Successfully Processed !');
    }

}
