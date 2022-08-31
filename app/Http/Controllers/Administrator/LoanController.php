<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\LoanPayment;
use App\Models\OrganisasiDivision;
use App\Models\OrganisasiPosition;
use App\User;
use Carbon\Carbon;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class LoanController extends Controller
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
            $data = Loan::select('loan.*')->orderBy('id', 'DESC')->join('users', 'users.id', '=', 'loan.user_id')->where('users.project_id', $user->project_id);
            $params['division'] = OrganisasiDivision::where('project_id', $user->project_id)->select('organisasi_division.*')->get();
            $params['position'] = OrganisasiPosition::where('project_id', $user->project_id)->select('organisasi_position.*')->get();
        } else {
            $data = Loan::select('loan.*')->orderBy('id', 'DESC')->join('users', 'users.id', '=', 'loan.user_id');
            $params['division'] = OrganisasiDivision::all();
            $params['position'] = OrganisasiPosition::all();
        }

        $params['structure'] = getStructureName();

        if(count(request()->all())) {
            \Session::put('loan-employee_status', request()->employee_status);
            \Session::put('loan-position_id', request()->position_id);
            \Session::put('loan-division_id', request()->division_id);
            \Session::put('loan-name', request()->name);
        }

        $employee_status    = \Session::get('loan-employee_status');
        $position_id        = \Session::get('loan-position_id');
        $division_id        = \Session::get('loan-division_id');
        $name               = \Session::get('loan-name');

        if (request()) {
            if (!empty($name)) {
                $data = $data->where(function ($table) use($name) {
                    $table->where('users.name', 'LIKE', '%' . $name . '%')
                        ->orWhere('users.nik', 'LIKE', '%' . $name . '%');
                });
            }

            if (!empty($employee_status)) {
                $data = $data->where('users.organisasi_status', $employee_status);
            }

            if ((!empty($division_id)) and (empty($position_id))) {
                $data = $data->join('structure_organization_custom', 'users.structure_organization_custom_id', '=', 'structure_organization_custom.id')->where('structure_organization_custom.organisasi_division_id', $division_id);
            }
            if ((!empty($position_id)) and (empty($division_id))) {
                $data = $data->join('structure_organization_custom', 'users.structure_organization_custom_id', '=', 'structure_organization_custom.id')->where('structure_organization_custom.organisasi_position_id', $position_id);
            }
            if ((!empty($position_id)) and (!empty($division_id))) {
                $data = $data->join('structure_organization_custom', 'users.structure_organization_custom_id', '=', 'structure_organization_custom.id')->where('structure_organization_custom.organisasi_position_id', $position_id)->where('structure_organization_custom.organisasi_division_id', $division_id);
            }

            if (request()->action == 'download') {
                return $this->downloadExcel($data->get());
            }
        }

        if(request()->reset == 1)
        {
            \Session::forget('loan-employee_status');
            \Session::forget('loan-position_id');
            \Session::forget('loan-division_id');
            \Session::forget('loan-name');

            return redirect()->route('administrator.loan.index');
        }

        $params['data'] = $data->get();

        return view('administrator.loan.index')->with($params);
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
        $params['data'] = Loan::where('id', $id)->first();
        if (isset($request->approval_1)) {
            if ($request->approval_1 == 0) {
                $params['text'] = '<p><strong>Dear Sir/Madam ' . $params['data']->user->name . '</strong>,</p> <p>  Submission of your Loan <strong style="color: red;">REJECTED</strong>.</p>';
                $params['data']->status = 3;
            } else {
                $fname = md5(rand() . time()) . '-photo.png';
                $company_url = session('company_url', 'umum') . '/';
                $destinationPath = public_path('/storage/file-loan-assign/') . $company_url;
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
                if (isset($request->photo_assign)) {
                    \Image::make(file_get_contents($request->photo_assign))->save($destinationPath . $fname);
                    $params['data']->photo_assign = $company_url . $fname;
                }
            }
            $params['data']->approval_collateral_receipt_status = $request->approval_1;
            $params['data']->approval_collateral_receipt_user_id = $user->id;
            $params['data']->approval_collateral_receipt_date = now();
            $params['data']->approval_collateral_receipt_note = $request->note_1;
        }
        if (isset($request->approval_2)) {
            if ($request->approval_2 == 0) {
                $params['text'] = '<p><strong>Dear Sir/Madam ' . $params['data']->user->name . '</strong>,</p> <p>  Submission of your Loan <strong style="color: red;">REJECTED</strong>.</p>';
                $params['data']->status = 3;
            }
            $params['data']->approval_collateral_physical_status = $request->approval_2;
            $params['data']->approval_collateral_physical_user_id = $user->id;
            $params['data']->approval_collateral_physical_date = now();
            $params['data']->approval_collateral_physical_note = $request->note_2;
        }
        if (isset($request->approval_3)) {
            if ($request->approval_3 == 0) {
                $params['text'] = '<p><strong>Dear Sir/Madam ' . $params['data']->user->name . '</strong>,</p> <p>  Submission of your Loan <strong style="color: red;">REJECTED</strong>.</p>';
                $params['data']->status = 3;
            } else {
                $params['text'] = '<p><strong>Dear Sir/Madam ' . $params['data']->user->name . '</strong>,</p> <p>  Submission of your Loan <strong style="color: green;">APPROVED</strong>.</p>';
                $params['data']->status = 2;
                $params['data']->number = 'L-' . Carbon::now()->format('dmY') . '/' . $params['data']->user->nik . '-' . (Loan::where('user_id', $params['data']->user->id)->whereNotNull('number')->count() + 1);

                $fname = md5(rand() . time()) . '-approver.png';
                $company_url = session('company_url', 'umum') . '/';
                $destinationPath = public_path('/storage/file-loan-assign/') . $company_url;
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
                if (isset($request->approver_assign)) {
                    \Image::make(file_get_contents($request->approver_assign))->save($destinationPath . $fname);
                    $params['data']->approver_assign = $company_url . $fname;
                }

                if (isset($request->disbursement_date)) {
                    $params['data']->disbursement_date = $request->disbursement_date;
                }
                if (isset($request->first_due_date)) {
                    $params['data']->first_due_date = $request->first_due_date;

                    for ($tenor = 1; $tenor <= $params['data']->rate; $tenor++) {
                        $payment = new LoanPayment();
                        $payment->loan_id = $params['data']->id;
                        $payment->tenor = $tenor;
                        $payment->due_date = Carbon::parse($params['data']->first_due_date)->startOfDay()->addMonthsNoOverflow($tenor - 1)->format("Y-m-d");
                        $payment->amount = ceil($params['data']->calculated_amount / $params['data']->rate);
                        $payment->save();
                    }
                }
            }
            $params['data']->approval_loan_status = $request->approval_3;
            $params['data']->approval_loan_user_id = $user->id;
            $params['data']->approval_loan_date = now();
            $params['data']->approval_loan_note = $request->note_3;
        }
        $params['data']->save();

        if (isset($params['text'])) {
            Config::set('database.default', 'mysql');
            if (!empty($params['data']->user->email)) {
                $params['value'] = $params['data']->historyApproval;
                $params['subject'] = get_setting('mail_name') . ' - Loan';
                $params['view'] = 'email.loan-approval';
                $params['email'] = $params['data']->user->email;
                $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                dispatch($job);
            }
            Config::set('database.default', session('db_name', 'mysql'));

            \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $params['data']->user->id, $params['data'], 'loan');

            if ($params['data']->user->firebase_token) {
                $config = [
                    'title' => "Loan",
                    'content' => strip_tags($params['text']),
                    'type' => "loan",
                    'firebase_token' => [$params['data']->user->firebase_token],
                ];
                $notifData = [
                    'id' => $params['data']->id,
                ];
                $db = Config::get('database.default', 'mysql');
                Config::set('database.default', 'mysql');
                dispatch((new \App\Jobs\SendPushTokens($notifData, $config))->onQueue('push'));
                Config::set('database.default', $db);
            }
        }

        return redirect()->route('administrator.loan.index')->with('message-success', 'Form Loan Successfully Processed!');
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
        $params['data'] = Loan::where('id', $id)->first();
        $params['payment'] = checkModule(13) ? [1 => 'Deduct Salary', 2 => 'Transfer to Company'] : [2 => 'Transfer to Company'];

        return view('administrator.loan.edit')->with($params);
    }

    public function downloadExcel($data)
    {
        $params = [];

        foreach ($data as $no => $item) {
            $params[$no]['NO'] = $no + 1;
            $params[$no]['EMPLOYEE ID(NIK)'] = $item->user->nik;
            $params[$no]['EMPLOYEE NAME'] = $item->user->name;
            $params[$no]['POSITION'] = (isset($item->user->structure->position) ? $item->user->structure->position->name : '') . (isset($item->user->structure->division) ? ' - ' . $item->user->structure->division->name : '') . (isset($item->user->structure->title) ? ' - ' . $item->user->structure->title->name : '');
            $params[$no]['PURPOSE'] = $item->loan_purpose;
            $params[$no]['EXPECTED CASH DISBURSEMENT DATE'] = date('d F Y', strtotime($item->expected_disbursement_date));
            $params[$no]['MAX PLAFOND'] = number_format($item->plafond);
            $params[$no]['AVAILABLE PLAFOND'] = number_format($item->available_plafond);
            $params[$no]['AMOUNT'] = number_format($item->amount);
            $params[$no]['TOTAL TENOR(MONTH)'] = $item->rate;
            $params[$no]['INTEREST(%)'] = $item->interest . '%';
            $params[$no]['CALCULATED AMOUNT'] = number_format($item->calculated_amount);
            $params[$no]['REFUND METHOD'] = $item->payment_type == 1 ? 'Deduct Salary' : 'Transfer to Company';

            // SET HEADER LEVEL APPROVAL
            $level_header = get_loan_header();
            for ($a = 0; $a < $level_header; $a++) {
                $params[$no]['APPROVAL STATUS ' . ($a + 1)] = '-';
                $params[$no]['APPROVAL NAME ' . ($a + 1)] = '-';
                $params[$no]['APPROVAL DATE ' . ($a + 1)] = '-';
            }

            foreach ($item->historyApproval as $key => $value) {
                if ($value->is_approved == 1) {
                    $params[$no]['APPROVAL STATUS ' . ($key + 1)] = 'Approved';
                } else if ($value->is_approved == 0) {
                    $params[$no]['APPROVAL STATUS ' . ($key + 1)] = 'Rejected';
                } else {
                    $params[$no]['APPROVAL STATUS ' . ($key + 1)] = '-';
                }

                $params[$no]['APPROVAL NAME ' . ($key + 1)] = isset($value->userApproved) ? $value->userApproved->name : '';
                $params[$no]['APPROVAL DATE ' . ($key + 1)] = $value->date_approved != null ? date('d F Y', strtotime($value->date_approved)) : '';
            }
        }

        return (new \App\Models\KaryawanExport($params, 'Report Loan Employee'))->download('EM-HR.Report-Loan-' . date('d-m-Y') . '.xlsx');
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
}
