<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\HistoryApprovalLoan;
use App\Models\Loan;
use App\Models\LoanAsset;
use App\Models\LoanAssetType;
use App\Models\LoanPayment;
use App\Models\LoanPaymentRate;
use App\Models\LoanPlafond;
use App\Models\LoanPurpose;
use App\Models\Payroll;
use App\User;
use Carbon\Carbon;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class LoanController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $params['data'] = Loan::where('user_id', \Auth::user()->id)->orderBy('id', 'DESC')->get();

        return view('karyawan.loan.index')->with($params);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $checkApproval = \Auth::user()->approval;
        if ($checkApproval == null) {
            return redirect()->route('karyawan.loan.index')->with('message-error', 'Your position is not defined yet. Please contact your admin !');
        } else {
            if (count($checkApproval->itemsLoan) == 0) {
                return redirect()->route('karyawan.loan.index')->with('message-error', 'Setting approval is not defined yet. Please contact your admin !');
            }
        }

        $user = \Auth::user();
        $params['plafond'] = LoanPlafond::where('organisasi_position_id', $user->structure->organisasi_position_id)->first();

        if (!$params['plafond']) {
            return redirect()->route('karyawan.loan.index')->with('message-error', 'Your position plafond setting is not defined yet. Please contact your admin !');
        }

        $params['payment'] = checkModule(13) && ($payroll = Payroll::where('user_id', $user->id)->first()) ? [1 => 'Deduct Salary', 2 => 'Transfer to Company'] : [2 => 'Transfer to Company'];
        $params['plafond'] = checkModule(13) && $payroll && $params['plafond']->type == 1 ? $payroll->salary : $params['plafond']->plafond;
        $params['available_plafond'] = $params['plafond'] - Loan::where('user_id', $user->id)->where('status', 1)->sum('calculated_amount') - LoanPayment::where(function ($query) {
            $query->whereNotIn('status', [2, 5])->orWhereNull('status');
        })->whereHas('loan', function ($query) use ($user) {
            $query->where('user_id', $user->id)->where('status', 2);
        })->sum('amount');

        $params['purpose'] = LoanPurpose::all();
        $params['rate'] = LoanPaymentRate::all();
        $params['asset'] = LoanAssetType::all();
        $params['history'] = Loan::where('user_id', $user->id)->where('status', '!=', 3)->get();

        return view('karyawan.loan.create')->with($params);
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
        $checkApproval = $user->approval;
        if ($checkApproval == null) {
            return redirect()->route('karyawan.loan.index')->with('message-error', 'Your position is not defined yet. Please contact your admin !');
        } else {
            if (count($checkApproval->itemsLoan) == 0) {
                return redirect()->route('karyawan.loan.index')->with('message-error', 'Setting approval is not defined yet. Please contact your admin !');
            }

            $data = new Loan();
            $data->user_id = $user->id;
            $data->loan_purpose = $request->loan_purpose;
            $data->plafond = LoanPlafond::where('organisasi_position_id', $user->structure->organisasi_position_id)->first();

            if (!$data->plafond) {
                return redirect()->route('karyawan.loan.index')->with('message-error', 'Your position plafond setting is not defined yet. Please contact your admin !');
            }

            $data->plafond = checkModule(13) && ($payroll = Payroll::where('user_id', $user->id)->first()) && $data->plafond->type == 1 ? $payroll->salary : $data->plafond->plafond;
            $data->available_plafond = $data->plafond - Loan::where('user_id', $user->id)->where('status', 1)->sum('calculated_amount') - LoanPayment::where(function ($query) {
                $query->whereNotIn('status', [2, 5])->orWhereNull('status');
            })->whereHas('loan', function ($query) use ($user) {
                $query->where('user_id', $user->id)->where('status', 2);
            })->sum('amount');

            $data->expected_disbursement_date = $request->expected_disbursement_date;
            $data->amount = preg_replace('/[^0-9]/', '', $request->amount);
            $data->rate = LoanPaymentRate::find($request->rate)->rate;
            $data->interest = LoanPaymentRate::find($request->rate)->interest;
            $data->calculated_amount = preg_replace('/[^0-9]/', '', $request->amount) + (preg_replace('/[^0-9]/', '', $request->amount) * $data->interest / 100);
            $data->payment_type = $request->payment_type;
            $data->status = 1;

            $company_url = session('company_url', 'umum') . '/';
            $destinationPath = public_path('/storage/file-loan-assign/') . $company_url;
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            if (isset($request->user_assign)) {
                $fname = md5(rand() . time()) . '-user.png';
                \Image::make(file_get_contents($request->user_assign))->save($destinationPath . $fname);
                $data->user_assign = $company_url . $fname;
            }

            if (isset($request->collateral_assign)) {
                $fname = md5(rand() . time()) . '-collateral.png';
                \Image::make(file_get_contents($request->collateral_assign))->save($destinationPath . $fname);
                $data->collateral_assign = $company_url . $fname;
            }

            $data->save();

            if (request()->hasFile('photos')) {
                foreach ($request->file('photos') as $key => $item) {
                    $form = new LoanAsset();
                    $form->loan_id = $data->id;
                    $form->asset_name = $key;

                    $fname = md5(rand() . $item->getClientOriginalName() . time()) . "." . $item->getClientOriginalExtension();
                    $company_url = session('company_url', 'umum') . '/';
                    $destinationPath = public_path('/storage/file-loan/') . $company_url;
                    $item->move($destinationPath, $fname);
                    $form->photo = $company_url . $fname;

                    $form->save();
                }
            }
            $historyApproval = $user->approval->itemsLoan;
            $settingApprovalItem = $historyApproval[0]->structure_organization_custom_id;
            foreach ($historyApproval as $level => $value) {
                $history = new HistoryApprovalLoan();
                $history->loan_id = $data->id;
                $history->setting_approval_level_id = ($level + 1);
                $history->structure_organization_custom_id = $value->structure_organization_custom_id;
                $history->save();
            }
            $historyApprov = HistoryApprovalLoan::where('loan_id', $data->id)->orderBy('setting_approval_level_id', 'asc')->get();

            $userApproval = user_approval_custom($settingApprovalItem);
            $db = Config::get('database.default', 'mysql');

            $params = getEmailConfig();
            $params['data'] = $data;
            $params['value'] = $historyApprov;
            $params['view'] = 'email.loan-approval';
            $params['subject'] = get_setting('mail_name') . ' - Loan';
            if ($userApproval) {
                Config::set('database.default', 'mysql');
                foreach ($userApproval as $key => $value) {
                    if (empty($value->email)) {
                        continue;
                    }

                    $params['email'] = $value->email;
                    $params['text'] = '<p><strong>Dear Sir/Madam ' . $value->name . '</strong>,</p> <p> ' . $data->user->name . '  / ' . $data->user->nik . ' applied for Loan and currently waiting your approval.</p>';
                    $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                    dispatch($job);
                }
                Config::set('database.default', $db);
                $params['text'] = '<p> ' . $data->user->name . '  / ' . $data->user->nik . ' applied for Loan and currently waiting your approval.</p>';
            }

            $userApprovalTokens = user_approval_firebase_tokens($settingApprovalItem);
            info("structure id " . $settingApprovalItem);
            info($userApprovalTokens);

            foreach (user_approval_id($settingApprovalItem) as $value) {
                \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $value, $data, 'loan_approval');
            }

            if (count($userApprovalTokens) > 0) {
                $config = [
                    'title' => "Loan Approval",
                    'content' => strip_tags($params['text']),
                    'type' => 'loan_approval',
                    'firebase_token' => $userApprovalTokens,
                ];
                $notifData = [
                    'id' => $data->id,
                ];
                info($userApprovalTokens);
                $db = Config::get('database.default', 'mysql');
                Config::set('database.default', 'mysql');
                dispatch((new \App\Jobs\SendPushTokens($notifData, $config))->onQueue('push'));
                Config::set('database.default', $db);
            }

            return redirect()->route('karyawan.loan.index')->with('message-success', 'Loan succesfully process');
        }

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
        $params['data'] = Loan::where('id', $id)->first();

        if (!IsAccess($params['data'])) {
            return redirect()->route('karyawan.loan.index')->with('message-error', 'You don\'t have permission to perform this action!');
        }

        $params['payment'] = checkModule(13) ? [1 => 'Deduct Salary', 2 => 'Transfer to Company'] : [2 => 'Transfer to Company'];

        return view('karyawan.loan.edit')->with($params);
    }

    public function table($id = null)
    {
        $data = LoanPayment::with(['approver', 'loan']);

        if ($id) {
            $data = $data->where('loan_id', $id);
        } else {
            $data = $data->whereHas('loan', function ($query) {
                $query->where('user_id', \Auth::user()->id);
            });
        }

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
                return !$item->status ? '<button id="action" type="button" class="btn btn-info btn-xs m-r-5"><i class="fa fa-arrow-right"></i> Pay</button>' : ($item->status && $item->status <= 3 ? '<button id="action" type="button" class="btn btn-info btn-xs m-r-5"><i class="fa fa-search-plus"></i> Payment Detail</button>' : '');
            })
            ->addColumn('column_payroll', function ($item) {
                return $item->payrollHistory ? Carbon::parse($item->payrollHistory->created_at)->format('Y-m') : null;
            })
            ->rawColumns(['column_amount','column_payment_type', 'column_status', 'column_action', 'column_payroll'])
            ->make(true);
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

    public function pay(Request $request, $id)
    {
        $params = getEmailConfig();
        $params['data'] = LoanPayment::find($id);

        if ($request->hasFile('photo')) {
            $fname = md5(rand() . $request->photo->getClientOriginalName() . time()) . '.' . $request->photo->getClientOriginalExtension();
            $company_url = session('company_url', 'umum') . '/';
            $destinationPath = public_path('/storage/file-loan-payment/') . $company_url;
            $request->photo->move($destinationPath, $fname);
            $params['data']->photo = $company_url . $fname;
        }

        $params['data']->user_note = $request->user_note;
        $params['data']->status = 1;
        $params['data']->payment_type = 2;
        $params['data']->payment_date = $request->payment_date;
        $params['data']->submit_date = now();
        $params['data']->save();

        $admins = getAdminByModule(33);
        Config::set('database.default', 'mysql');
        foreach ($admins as $key => $value) {
            if (empty($value->email)) {
                continue;
            }
            $params['subject'] = get_setting('mail_name') . ' - Loan Payment';
            $params['view'] = 'email.loan-payment-approval';
            $params['text'] = '<p><strong>Dear Sir/Madam ' . $value->name . '</strong>,</p> <p> ' . $params['data']->loan->user->name . '  / ' . $params['data']->loan->user->nik . ' applied for Loan Payment and currently waiting your approval.</p>';
            $params['email'] = $value->email;
            $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
            dispatch($job);
        }
        Config::set('database.default', session('db_name', 'mysql'));

        return \Response::json(array(
            'type' => 'success',
            'title' => 'Loan payment submitted',
        ));
    }

    public function paymentIndex()
    {
        return view('karyawan.loan.payment');
    }
}
