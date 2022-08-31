<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Middleware\ConnectionMiddleware;
use App\Http\Resources\LoanMinResource;
use App\Http\Resources\LoanResource;
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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class LoanController extends Controller
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
        $user = Auth::user();
        $status = $request->input('status', '[1,2,3]');
        if (!isJsonValid($status)) {
            return response()->json(['status' => 'error', 'message' => 'Status is invalid'], 404);
        }

        $status = json_decode($status);
        $histories = Loan::where(['user_id' => $user->id])->whereIn('status', $status)->orderBy('created_at', 'DESC');
        $totalData = $histories->get()->count();
        $histories = $histories->paginate(10);
        $data = [
            'current_page' => $histories->currentPage(), // get current page number
            'total_page' => $histories->total() ? $histories->lastPage() : $histories->total(), // get last page number
            'total_data' => $totalData,
            'loans' => LoanResource::collection($histories),
        ];
        return response()->json(
            [
                'status' => 'success',
                'message' => 'Successfully',
                'data' => $data,
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
        $approval = $user->approval;
        if ($approval == null) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Your position is not defined yet. Please contact your admin!',
                ], 403);
        } else if (count($approval->itemsLoan) == 0) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Setting approval is not defined yet. Please contact your admin!',
                ], 403);
        }
        $validator = Validator::make($request->all(), [
            'loan_purpose' => "required",
            'expected_disbursement_date' => "required|date",
            'amount' => "required|numeric",
            'rate_id' => "required|exists:loan_payment_rate,id",
            'payment_type' => "required|in:1,2",
            'user_assign' => "image",
            'collateral_assign' => "image",
            'assets' => "array",
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()], 401);
        }
        if (!isset($request->user_assign)) {
            return response()->json(['status' => 'error', 'message' => 'Term & Condition agreement required'], 404);
        }

        $data = new Loan();
        $data->user_id = $user->id;
        $data->loan_purpose = $request->loan_purpose;
        $data->plafond = LoanPlafond::where('organisasi_position_id', $user->structure->organisasi_position_id)->first();

        if (!$data->plafond) {
            return response()->json(['status' => 'error', 'message' => 'Your position plafond setting is not defined yet. Please contact your admin!'], 403);
        }

        $data->plafond = checkModule(13) && ($payroll = Payroll::where('user_id', $user->id)->first()) && $data->plafond->type == 1 ? $payroll->salary : $data->plafond->plafond;
        $data->available_plafond = $data->plafond - Loan::where('user_id', $user->id)->where('status', 1)->sum('calculated_amount') - LoanPayment::where(function ($query) {
            $query->whereNotIn('status', [2, 5])->orWhereNull('status');
        })->whereHas('loan', function ($query) use ($user) {
            $query->where('user_id', $user->id)->where('status', 2);
        })->sum('amount');

        if ($request->amount > $data->available_plafond) {
            if (!isset($request->assets) || !isset($request->files)) {
                return response()->json(['status' => 'error', 'message' => '1 or more collateral data must be uploaded!'], 404);
            } else if (!isset($request->collateral_assign)) {
                return response()->json(['status' => 'error', 'message' => 'Collateral Receipt agreement required'], 404);
            }
        }

        $data->expected_disbursement_date = $request->expected_disbursement_date;
        $data->amount = $request->amount;
        $data->rate = LoanPaymentRate::find($request->rate_id)->rate;
        $data->interest = LoanPaymentRate::find($request->rate_id)->interest;
        $data->calculated_amount = $request->amount + ($request->amount * $data->interest / 100);
        $data->payment_type = $request->payment_type;
        $data->status = 1;

        $company_url = ($request->company ? $request->company : "umum") . '/';
        $destinationPath = public_path('/storage/file-loan-assign/') . $company_url;

        if ($request->hasFile('user_assign')) {
            $fname = md5(rand() . time()) . '-user.png';
            $request->file('user_assign')->move($destinationPath, $fname);
            $data->user_assign = $company_url . $fname;
        }

        if ($request->hasFile('collateral_assign')) {
            $fname = md5(rand() . time()) . '-collateral.png';
            $request->file('collateral_assign')->move($destinationPath, $fname);
            $data->collateral_assign = $company_url . $fname;
        }

        $data->save();

        if ($request->assets) {
            foreach ($request->assets as $key => $asset) {
                $form = new LoanAsset();
                $form->loan_id = $data->id;
                $form->asset_name = $asset['name'];

                if ($request->file('photos') && isset($request->file('photos')[$key])) {
                    $image = $request->file('photos')[$key];
                    $fname = md5(rand() . $image->getClientOriginalName() . time()) . "." . $image->getClientOriginalExtension();
                    $company_url = ($request->company ? $request->company : "umum") . '/';
                    $destinationPath = public_path('/storage/file-loan/') . $company_url;
                    $image->move($destinationPath, $fname);
                    $form->photo = $company_url . $fname;
                }

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
            \FRDHelper::setNewData(strtolower($request->company), $value, $data, 'loan_approval');
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

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Your loan request has successfully submitted',
            ], 201);
    }

    public function getParams(Request $request)
    {
        if ($request->type == 'create') {
            $user = Auth::user();
            $approval = $user->approval;
            if ($approval == null) {
                return response()->json(['status' => 'error', 'message' => 'Your position is not defined yet. Please contact your admin!'], 403);
            } else if (count($approval->itemsLoan) == 0) {
                return response()->json(['status' => 'error', 'message' => 'Setting approval is not defined yet. Please contact your admin!'], 403);
            }

            $data['plafond'] = LoanPlafond::where('organisasi_position_id', $user->structure->organisasi_position_id)->first();

            if (!$data['plafond']) {
                return response()->json(['status' => 'error', 'message' => 'Your position plafond setting is not defined yet. Please contact your admin!'], 403);
            }

            $data['payment'] = checkModule(13) && ($payroll = Payroll::where('user_id', $user->id)->first()) ? [['id' => 1, 'name' => 'Deduct Salary'], ['id' => 2, 'name' => 'Transfer to Company']] : [['id' => 2, 'name' => 'Transfer to Company']];
            $data['plafond'] = checkModule(13) && $payroll && $data['plafond']->type == 1 ? $payroll->salary : $data['plafond']->plafond;
            $data['available_plafond'] = $data['plafond'] - Loan::where('user_id', $user->id)->where('status', 1)->sum('calculated_amount') - LoanPayment::where(function ($query) {
                $query->whereNotIn('status', [2, 5])->orWhereNull('status');
            })->whereHas('loan', function ($query) use ($user) {
                $query->where('user_id', $user->id)->where('status', 2);
            })->sum('amount');

            $data['purposes'] = LoanPurpose::all();
            $data['rates'] = LoanPaymentRate::all();
            $data['assets'] = LoanAssetType::all();
            $data['histories'] = LoanMinResource::collection(Loan::withSum(
                ['payment' => function ($query) {
                    $query->whereNotIn('status', [2, 5])->orWhereNull('status');
                }],
                'amount'
            )->where('user_id', $user->id)->where(function ($query) {
                $query->where('status', 1)->orWhere(function ($query) {
                    $query->where('status', 2)->whereHas('payment', function ($query) {
                        $query->whereNotIn('status', [2, 5])->orWhereNull('status');
                    });
                });
            })->get());
        } else {
            if (!$request->user_id) {
                return response()->json(['status' => 'error', 'message' => 'User ID is required!'], 403);
            }

            $user = User::find($request->user_id);
            if (!$user) {
                return response()->json(['status' => 'error', 'message' => 'User is not found!'], 404);
            }

            $data = [];
        }

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Successfully',
                'data' => $data,
            ], 200);
    }

    public function getTerm(Request $request)
    {
        return response()->json(
            [
                'status' => 'success',
                'message' => 'Successfully',
                'data' => [
                    'term' => get_setting('term_condition'),
                    'collateral' => get_setting('collateral_receipt'),
                ],
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
        $user = Auth::user();
        $data['loan'] = new LoanResource(Loan::findOrFail($id));
        return response()->json(
            [
                'status' => 'success',
                'message' => 'Successfully',
                'data' => $data,
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

    public function getApproval(Request $request)
    {
        $status = $request->status ? $request->status : "all";
        $user = Auth::user();
        $approval = null;
        if ($status == 'ongoing') {
            $approval = Loan::join('history_approval_loan as h', function ($join) use ($user) {
                $join->on('loan.id', '=', 'h.loan_id')
                    ->where('h.setting_approval_level_id', '=', DB::raw('(select min(setting_approval_level_id) from history_approval_loan where loan_id = loan.id and is_approved is null)'))
                    ->where('h.structure_organization_custom_id', '=', $user->structure_organization_custom_id);
            })
                ->where('loan.status', '=', 1)
                ->orderBy('created_at', 'DESC')
                ->select('loan.*');
        } else if ($status == 'history') {
            $approval = Loan::join('history_approval_loan as h', function ($join) use ($user) {
                $join->on('loan.id', '=', 'h.loan_id')
                    ->whereNotNull('h.is_approved')
                    ->where('h.structure_organization_custom_id', '=', $user->structure_organization_custom_id);
            })
                ->orderBy('created_at', 'DESC')
                ->select('loan.*');
        } else if ($status == 'all') {
            $approval = Loan::join('history_approval_loan as h', function ($join) use ($user) {
                $join->on('loan.id', '=', 'h.loan_id')
                    ->where('h.structure_organization_custom_id', '=', $user->structure_organization_custom_id);
            })
                ->orderBy('created_at', 'DESC')
                ->select('loan.*');
        }
        $totalData = $approval->get()->count();
        $approval = $approval->paginate(10);
        $data = [
            'current_page' => $approval->currentPage(), // get current page number
            'total_page' => $approval->total() ? $approval->lastPage() : $approval->total(), // get last page number
            'total_data' => $totalData,
            'loans' => LoanResource::collection($approval),
        ];
        return response()->json(
            [
                'status' => 'success',
                'message' => 'Successfully',
                'data' => $data,
            ], 200);
    }

    public function approve(Request $request)
    {
        info($request->all());
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'loan.id' => 'required|exists:loan,id',
            'approval.note' => "required",
            'approval.is_approved' => "required|in:1,0",
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()], 401);
        }

        $loan = Loan::find($request->loan['id']);
        $params = getEmailConfig();
        $params['data'] = $loan;
        $params['value'] = $loan->historyApproval;
        $params['subject'] = get_setting('mail_name') . ' - Loan';
        $params['view'] = 'email.loan-approval';

        $approval = HistoryApprovalLoan::where(['loan_id' => $loan->id, 'structure_organization_custom_id' => $user->structure_organization_custom_id])->first();
        $approval->approval_id = $user->id;
        $approval->is_approved = $request->approval['is_approved'];
        $approval->date_approved = date('Y-m-d H:i:s');
        $approval->note = $request->approval['note'];
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
            \FRDHelper::setNewData(strtolower($request->company), $value, $loan, $notifType);
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

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Loan Successfully Processed !',
            ], 200);
    }

    public function payment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => "required|exists:loan_payment,id",
            'photo' => "required",
            'user_note' => "required",
            'payment_date' => "required|date",
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()], 401);
        }

        $params = getEmailConfig();
        $params['data'] = LoanPayment::find($request->id);
        $params['subject'] = get_setting('mail_name') . ' - Loan Payment';
        $params['view'] = 'email.loan-payment-approval';

        if ($request->hasFile('photo')) {
            $fname = md5(rand() . $request->file('photo')->getClientOriginalName() . time()) . '.' . $request->file('photo')->getClientOriginalExtension();
            $company_url = ($request->company ? $request->company : "umum") . '/';
            $destinationPath = public_path('/storage/file-loan-payment/') . $company_url;
            $request->file('photo')->move($destinationPath, $fname);
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

            $params['text'] = '<p><strong>Dear Sir/Madam ' . $value->name . '</strong>,</p> <p> ' . $params['data']->loan->user->name . '  / ' . $params['data']->loan->user->nik . ' applied for Loan Payment and currently waiting your approval.</p>';
            $params['email'] = $value->email;
            $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
            dispatch($job);
        }
        Config::set('database.default', session('db_name', 'mysql'));

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Loan payment has successfully submitted',
            ], 200);
    }
}
