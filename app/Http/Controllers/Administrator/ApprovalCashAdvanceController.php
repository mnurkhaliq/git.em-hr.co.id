<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CashAdvance;
use App\Models\HistoryApprovalCashAdvance;
use App\Models\CashAdvanceForm;
use App\Models\StructureOrganizationCustom;
use App\Models\OrganisasiDivision;
use App\Models\OrganisasiPosition;
use Illuminate\Support\Facades\Config;

class ApprovalCashAdvanceController extends Controller
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
        //dd(request());
        $user = \Auth::user();
        if($user->project_id != NULL)
        {
            $all = \App\Models\HistoryApprovalCashAdvance::join('cash_advance','cash_advance.id','=','history_approval_cash_advance.cash_advance_id')->orderBy('cash_advance_id', 'DESC')->get();
            $cash_advance = CashAdvance::select('cash_advance.*', \DB::raw('SUM(cash_advance_form.nominal_approved) as total_amount_approved'), \DB::raw('SUM(cash_advance_form.nominal_claimed) as total_amount_claimed'))
                        ->join('users','users.id','=','cash_advance.user_id')
                        ->join('cash_advance_form','cash_advance_form.cash_advance_id', '=', 'cash_advance.id')->groupBy('cash_advance.id')
                        ->orderBy('cash_advance.id', 'DESC');
            $params['division'] = OrganisasiDivision::where('project_id', $user->project_id)->select('organisasi_division.*')->get();
            $params['position'] = OrganisasiPosition::where('project_id', $user->project_id)->select('organisasi_position.*')->get();
        } 
        else
        {
            $all = \App\Models\HistoryApprovalCashAdvance::join('cash_advance','cash_advance.id','=','history_approval_cash_advance.cash_advance_id')->orderBy('cash_advance_id', 'DESC')->get();
            $cash_advance = CashAdvance::select('cash_advance.*', \DB::raw('SUM(cash_advance_form.nominal_approved) as total_amount_approved'), \DB::raw('SUM(cash_advance_form.nominal_claimed) as total_amount_claimed'))
                ->join('users','users.id','=','cash_advance.user_id')
                ->join('cash_advance_form','cash_advance_form.cash_advance_id', '=', 'cash_advance.id')->groupBy('cash_advance.id')
                ->orderBy('cash_advance.id', 'DESC');
            $params['division'] = OrganisasiDivision::all();
            $params['position'] = OrganisasiPosition::all();
        }

        if(count(request()->all())) {
            \Session::put('ca-employee_status', request()->employee_status);
            \Session::put('ca-position_id', request()->position_id);
            \Session::put('ca-division_id', request()->division_id);
            \Session::put('ca-name', request()->name);
            \Session::put('ca-bt_approval', request()->bt_approval);
            \Session::put('ca-bt_claim', request()->bt_claim);
            \Session::put('ca-employee_resign', request()->employee_resign);
        }

        $employee_status    = \Session::get('ca-employee_status');
        $position_id        = \Session::get('ca-position_id');
        $division_id        = \Session::get('ca-division_id');
        $name               = \Session::get('ca-name');
        $bt_approval        = \Session::get('ca-bt_approval');
        $bt_claim           = \Session::get('ca-bt_claim');

        if($user->project_id != NULL)
        {
            if (!empty($name)) {
                $cash_advance = $cash_advance->where(function ($table) use($name) {
                    $table->where('users.name', 'LIKE', '%' . $name . '%')
                        ->orWhere('users.nik', 'LIKE', '%' . $name . '%');
                });
            }
            
            if(!empty($employee_status))
            {
                $cash_advance = $cash_advance->where('users.organisasi_status', $employee_status);
            }
            if(!empty($bt_approval))
            {
                $cash_advance = $cash_advance->where('cash_advance.status', $bt_approval);
            }
            if(!empty($bt_claim))
            {
                $cash_advance = $cash_advance->where('cash_advance.status_claim', $bt_claim);
            }
            if((!empty($division_id)) and (empty($position_id))) 
            {   
                $cash_advance = $cash_advance->join('structure_organization_custom','users.structure_organization_custom_id','=','structure_organization_custom.id')->where('structure_organization_custom.organisasi_division_id',$division_id);
            }
            if((!empty($position_id)) and (empty($division_id)))
            {   
                $cash_advance = $cash_advance->join('structure_organization_custom','users.structure_organization_custom_id','=','structure_organization_custom.id')->where('structure_organization_custom.organisasi_position_id',$position_id);
            }
            if((!empty($position_id)) and (!empty($division_id)))
            {
                $cash_advance = $cash_advance->join('structure_organization_custom','users.structure_organization_custom_id','=','structure_organization_custom.id')->where('structure_organization_custom.organisasi_position_id',request()->position_id)->where('structure_organization_custom.organisasi_division_id',request()->division_id);
            }
            if(request()->action == 'download')
            {
                return $this->downloadExcel($cash_advance->get());
            }
        }

        if(request()->reset == 1)
        {
            \Session::forget('ca-employee_status');
            \Session::forget('ca-position_id');
            \Session::forget('ca-division_id');
            \Session::forget('ca-name');
            \Session::forget('ca-bt_approval');
            \Session::forget('ca-bt_claim');
            \Session::forget('ca-employee_resign');

            return redirect()->route('administrator.approval-cash-advance.index');
        }

        $params['data'] = $cash_advance->get();

        return view('administrator.approval-cash-advance.index')->with($params);
    }

    
    public function detail($id)
    {   
        $params['data'] = CashAdvance::where('id', $id)->first();
        $params['history'] = HistoryApprovalCashAdvance::where('cash_advance_id',$id)->first();

        return view('administrator.approval-cash-advance.detail')->with($params);
    }

    public function claim($id)
    {   
        
        $params['data'] = CashAdvance::where('id', $id)->first();
        $params['history'] = HistoryApprovalCashAdvance::where('cash_advance_id',$id)->first();
        
        return view('administrator.approval-cash-advance.claim')->with($params);
    }

    public function transfer(Request $request, $id){
        //dd($request);
        $data = CashAdvance::find($id);
        $data->is_transfer= $request->is_transfer;
        $data->is_transfer_by = auth()->user()->id;
        if($request->hasFile('transfer_proof_by_admin'))
        {
            $image = $request->transfer_proof_by_admin;
            $name = $id.'transfer_proof.'.$image->getClientOriginalExtension();
            $company_url = session('company_url','umum').'/';
            $destinationPath = public_path('storage/cash-advance/transfer-proof/').$company_url;
            $image->move($destinationPath, $name);
            $data->transfer_proof = $company_url.$name;
        }
        $data->save();

        $userApprovalTokens = [];

        $db = Config::get('database.default','mysql');
        $params             = getEmailConfig();
        $params['data']     = $data;
        $params['value']    = $data->historyApproval;
        $params['subject']  = get_setting('mail_name') . ' - Transfer Cash Advance';
        $params['view']     = 'email.cash-advance';
        $params['total']    = total_cash_advance_nominal_approved($data->id);
        $params['text']     = '<p><strong>Dear Sir/Madam '. $data->user->name .'</strong>,</p> <p>  Submission of your Cash Advance has been transfered.</p>';

        Config::set('database.default', 'mysql');

        if(!empty($data->user->email)) {
            $params['email'] = $data->user->email;
            $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
            dispatch($job);
        }
        Config::set('database.default', $db);
        
        $notifTitle = "Cash Advance";
        $notifType  = "cash_advance";
        if($data->user->firebase_token) {
            array_push($userApprovalTokens, $data->user->firebase_token);
        }

        return redirect()->route('administrator.approval.cash-advance.index')->with('message-success', 'Transfer Proof Successfully Sent!');
    }

    public function transferClaim(Request $request, $id){
        //dd($request);
        $data = CashAdvance::find($id);
        $data->is_transfer_claim = $request->is_transfer_claim;
        $data->is_transfer_claim_by = auth()->user()->id;
        if($request->hasFile('transfer_proof_claim_by_admin'))
        {
            $image = $request->transfer_proof_claim_by_admin;
            $name = $id.'transfer_proof_claim.'.$image->getClientOriginalExtension();
            $company_url = session('company_url','umum').'/';
            $destinationPath = public_path('storage/cash-advance/transfer-proof/').$company_url;
            $image->move($destinationPath, $name);
            $data->transfer_proof_claim = $company_url.$name;
        }
        $data->save();

        $userApprovalTokens = [];

        $db = Config::get('database.default','mysql');
        $params             = getEmailConfig();
        $params['data']     = $data;
        $params['value']    = $data->historyApproval;
        $params['subject']  = get_setting('mail_name') . ' - Transfer Claim Cash Advance';
        $params['view']     = 'email.cash-advance';
        $params['total']    =  (total_cash_advance_nominal_claimed($data->id) - total_cash_advance_nominal_approved($data->id));
        $params['text']     = '<p><strong>Dear Sir/Madam '. $data->user->name .'</strong>,</p> <p>  Submission of your Cash Advance Claim has been transfered by Admin.</p>';

        Config::set('database.default', 'mysql');

        if(!empty($data->user->email)) {
            $params['email'] = $data->user->email;
            $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
            dispatch($job);
        }
        Config::set('database.default', $db);
        
        $notifTitle = "Cash Advance";
        $notifType  = "cash_advance";
        if($data->user->firebase_token) {
            array_push($userApprovalTokens, $data->user->firebase_token);
        }

        return redirect()->route('administrator.approval.cash-advance.index')->with('message-success', 'Transfer Proof Claim Successfully Sent!');
    }

    public function downloadExcel($data)
    {
        $params = [];

        $total_loop_header = [];
        foreach($data as $no =>  $item)
        {
            $total = 0;
            foreach($item->cashAdvance->cash_advance_form as $type => $form)
            {
                $total++;
            }
            $total_loop_header[] = $total;
        }

        foreach($data as $no =>  $item)
        {
            $params[$no]['NO']               = $no+1;
            $params[$no]['NIK']              = $item->cashAdvance->user->nik;
            $params[$no]['NAME']    = $item->cashAdvance->user->name;
            $params[$no]['POSITION']         = (isset($item->cashAdvance->structure->position) ? $item->cashAdvance->structure->position->name:'').(isset($item->cashAdvance->structure->division) ? ' - '.$item->cashAdvance->structure->division->name:'').(isset($item->cashAdvance->structure->title) ? ' - '.$item->cashAdvance->structure->title->name:'');
            $params[$no]['DATE REQUEST']    = date('d F Y', strtotime($item->cashAdvance->created_at));
            $params[$no]['PURPOSE']           = $item->cashAdvance->tujuan;
            $params[$no]['PAYMENT METHOD']  = $item->cashAdvance->payment_method;


            $total=0;
            $total_amount = 0;
            $total_amount_approved = 0;
            $total_actual_amount = 0;
            $total_amount_claimed = 0;
            foreach($item->cashAdvance->cash_advance_form as $type => $form)
            {
                $type = $type+1;
                $params[$no]['TYPE '.$type]             = $form->type_form;
                $params[$no]['DESCRIPTION '.$type]      = $form->description;
                $params[$no]['QUANTITY '.$type]         = $form->quantity;
                $params[$no]['AMOUNT '.$type]           = $form->amount;
                $params[$no]['AMOUNT APPROVED '.$type]  = $form->nominal_approved;
                $params[$no]['NOTE '.$type]  = $form->note;
                $params[$no]['ACTUAL AMOUNT '.$type]    = $form->actual_amount;
                $params[$no]['AMOUNT CLAIMED '.$type]  = $form->nominal_claimed;
                $params[$no]['NOTE CLAIMED '.$type]  = $form->note_claimed;
                $total++;

                $total_amount +=$form->amount;
                $total_amount_approved +=$form->nominal_approved;
                $total_actual_amount +=$form->actual_amount;
                $total_amount_claimed +=$form->nominal_claimed;
            }
            if($total ==0 ) $total++;
            for($v=$total; $v < max($total_loop_header); $v++)
            {
                $params[$no]['TYPE '. ($v+1)]             = "-";
                $params[$no]['DESCRIPTION '.($v+1)]      = "-";
                $params[$no]['QUANTITY '.($v+1)]         = "-";
                $params[$no]['AMOUNT '.($v+1)]           = "-";
                $params[$no]['AMOUNT APPROVED '.($v+1)]  = "-";
                $params[$no]['NOTE '.($v+1)]  = "-";
                $params[$no]['ACTUAL AMOUNT '.($v+1)]           = "-";
                $params[$no]['AMOUNT CLAIMED '.($v+1)]  = "-";
                $params[$no]['NOTE CLAIMED '.($v+1)]  = "-";
            }
            $params[$no]['TOTAL AMOUNT']  = $total_amount;
            $params[$no]['TOTAL AMOUNT APPROVED']  = $total_amount_approved;
            $params[$no]['TOTAL ACTUAL AMOUNT']  = $total_actual_amount;
            $params[$no]['TOTAL AMOUNT CLAIMED']  = $total_amount_claimed;
            // SET HEADER LEVEL APPROVAL
            $level_header = get_payment_header();
            for($a=0; $a < $level_header  ; $a++)
            {
                $params[$no]['APPROVAL STATUS '. ($a+1)]           = '-';
                $params[$no]['APPROVAL NAME '. ($a+1)]           = '-';
                $params[$no]['APPROVAL DATE '. ($a+1)]           = '-';
                $params[$no]['SETTLEMENT STATUS '. ($a+1)]           = '-';
                $params[$no]['SETTLEMENT NAME '. ($a+1)]           = '-';
                $params[$no]['SETTLEMENT DATE '. ($a+1)]           = '-';

            }

            foreach ($item->cashAdvance->historyApproval as $key => $value) {
                //$params[$no]['Approval '. ($key+1)]           = $value->id;

                if($value->is_approved == 1)
                {
                    $params[$no]['APPROVAL STATUS '. ($key+1)]           = 'Approved';
                }elseif($value->is_approved == 0)
                {
                    $params[$no]['APPROVAL STATUS '. ($key+1)]           = 'Rejected';
                }else
                {
                    $params[$no]['APPROVAL STATUS '. ($key+1)]           = '-';
                }

                $params[$no]['APPROVAL NAME '. ($key+1)]           = isset($value->userApproved) ? $value->userApproved->name:'';

                $params[$no]['APPROVAL DATE '. ($key+1)]           = $value->date_approved != NULL ? date('d F Y', strtotime($value->date_approved)) : '';

                if($value->is_approved_claim == 1)
                {
                    $params[$no]['SETTLEMENT STATUS '. ($key+1)]           = 'Approved';
                }elseif($value->is_approved_claim == 0)
                {
                    $params[$no]['SETTLEMENT STATUS '. ($key+1)]           = 'Rejected';
                }else
                {
                    $params[$no]['SETTLEMENT STATUS '. ($key+1)]           = '-';
                }

                $params[$no]['SETTLEMENT NAME '. ($key+1)]           = isset($value->userApprovedClaim) ? $value->userApprovedClaim->name:'';

                $params[$no]['SETTLEMENT DATE '. ($key+1)]           = $value->date_approved_claim != NULL ? date('d F Y', strtotime($value->date_approved_claim)) : '';
            }

        }

        return (new \App\Models\KaryawanExport($params, 'Report Cash Advance Employee ' ))->download('EM-HR.Report-Cash-Advance-'.date('d-m-Y') .'.xlsx');
    }

}
