<?php

namespace App\Http\Controllers\Karyawan;

use App\Models\Asset;
use App\Models\AssetTracking;
use App\Models\InternalApplication;
use App\Models\RecruitmentApplication;
use App\Models\RecruitmentApplicationHistory;
use App\Models\RecruitmentPhase;
use App\Models\RecruitmentRequestDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use DB;
use Carbon\Carbon;
use App\Models\OrganisasiDirectorate;
use App\Models\OrganisasiDepartment;
use App\Models\Provinsi;
use App\Models\UserFamily;
use App\Models\UserEducation;
use App\Models\UserCertification;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\OrganisasiDivision;
use App\Models\OrganisasiSection;
use App\Models\News;
use App\Models\InternalMemo;
use App\Models\Product;
use App\Models\RelatedSearchKaryawan;
use App\Models\RequestPaySlip;
use App\Models\CareerHistory;
use App\Models\AbsensiItem;
use App\Models\StructureOrganizationCustom;
use App\Models\Shift;
use App\Models\ShiftDetail;
use App\Models\LiburNasional;
use App\Models\VisitList;
use App\Models\VisitPict;
use App\Models\ShiftScheduleChange;
use App\Models\CutiBersama;
use App\Models\Loan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use ZipArchive;
use DateTime;
use DateTimeZone;
use App\Models\RemoteAttendance;
use App\Models\Setting;
use App\Models\PaymentRequest;
use App\Models\{CashAdvance,RecruitmentRequest,BirthdayComment,BirthdayWording};
use App\Models\{CutiKaryawan,TimesheetPeriod,OvertimeSheet,Training,MedicalReimbursement,ExitInterview};

class IndexController extends Controller
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
        $params['data'] = User::where('id', \Auth::user()->id)->first();
        $params['department']       = OrganisasiDepartment::where('organisasi_division_id', $params['data']['division_id'])->get();
        $params['provinces']        = Provinsi::all();
        $params['dependent']        = UserFamily::where('user_id', \Auth::user()->id)->first();
        $params['certification']    = UserCertification::where('user_id', \Auth::user()->id)->first();
        $params['education']        = UserEducation::where('user_id', \Auth::user()->id)->first();
        $params['kabupaten']        = Kabupaten::where('id_prov', $params['data']['provinsi_id'])->get();
        $params['kecamatan']        = Kecamatan::where('id_kab', $params['data']['kabupaten_id'])->get();
        $params['kelurahan']        = Kelurahan::where('id_kec', $params['data']['kecamatan_id'])->get();
        $params['division']         = OrganisasiDivision::all();
        $params['section']          = OrganisasiSection::where('division_id', $params['data']['division_id'])->get();

        $user = \Auth::user();
        if($user->project_id != NULL)
        {
            $params['news']             = News::where('news.status', 1)
                                                ->orderBy('news.id', 'DESC')
                                                ->join('users','users.id','=','news.user_created')
                                                ->where('users.project_id', $user->project_id)
                                                ->select('news.*')
                                                ->limit(4)->get();
            $params['internal_memo']    = InternalMemo::where('internal_memo.status', 1)
                                                        ->orderBy('internal_memo.id', 'DESC')
                                                        ->join('users','users.id','=','internal_memo.user_created')
                                                        ->where('users.project_id', $user->project_id)
                                                        ->select('internal_memo.*')
                                                        ->limit(5)->get();
            $params['product']    = Product::where('product.status', 1)
                                                                        ->orderBy('product.id', 'DESC')
                                                                        ->join('users','users.id','=','product.user_created')
                                                                        ->where('users.project_id', $user->project_id)
                                                                        ->select('product.*')->limit(5)->get();
//            return json_encode($params['internal_vacancy']);
            $params['ulang_tahun']      = User::where('project_id',$user->project_id)->whereMonth('tanggal_lahir', date('m'))->whereDay('tanggal_lahir', date('d'))
                                        ->where(function($query) {
                                            $query->whereNull('non_active_date')->orWhere('non_active_date', '>', \Carbon\Carbon::now());
                                        })->orderByRaw('IF(id = '.auth()->user()->id.', 0,1)')->get();

        } else
        {
            $params['news']                     = News::where('status', 1)->orderBy('id', 'DESC')->limit(4)->get();
            $params['internal_memo']            = InternalMemo::where('status', 1)->orderBy('id', 'DESC')->limit(5)->get();
            $params['product']     = Product::where('status', 1)->orderBy('id', 'DESC')->limit(5)->get();
            $params['ulang_tahun']              = User::whereMonth('tanggal_lahir', date('m'))->whereDay('tanggal_lahir', date('d'))->where(function($query) {
                $query->whereNull('non_active_date')->orWhere('non_active_date', '>', \Carbon\Carbon::now());
            })->orderByRaw('IF(id = '.auth()->user()->id.', 0,1)')->get();
        }

        $params['internal_vacancy']    = RecruitmentRequestDetail::join('recruitment_request as rr','recruitment_request_id','=','rr.id')
            ->leftJoin('cabang as c', 'rr.branch_id','=','c.id')
            ->where([
                'recruitment_request_detail.status_post' => 1,
                'recruitment_request_detail.recruitment_type_id' => 1,
                'rr.approval_hr' => 1,
                'rr.approval_user' => 1,
                'rr.project_id' => Auth::user()->project_id
            ])
            ->select(['recruitment_request_detail.*', 'rr.id as recruitment_id', 'rr.min_salary', 'rr.max_salary', 'c.name as branch', 'rr.job_desc', 'rr.job_requirement', 'rr.job_position'])
            ->orderBy('recruitment_request_detail.posting_date', 'desc')
            ->limit(4)->get();

        $data = User::orderBy('name', 'ASC')->limit(1); 

        if(isset($_GET['name']))
        {
            $data = $data->where('name', 'LIKE', '%'. $_GET['name'] .'%');

            if(!empty($_GET['name']))
            {
                $related            = new RelatedSearchKaryawan();
                $related->user_id   = \Auth::user()->id;
                $related->keyword   = $_GET['name'];
                $related->save();
            }
        }
        else
        {
            $related = RelatedSearchKaryawan::where('user_id', \Auth::user()->id)->orderBy('id', 'DESC')->first();

            if(isset($related))
            {
                $data = $data->where('name', 'LIKE', '%'. $related->keyword .'%');
            }
        }

        $params['datasearch'] = $data->get();

        if(!isset($_GET['name']) and !isset($_GET['nik']))
        {
            $params['datasearch'] = $data->get();
        }

        $params['absensiData'] = $this->absensiToday();
        // $params['notification'] = $this->notification()->take(3);
        //dd($params['notif']);
        // $i= 0;
        // $time = $user->last_logged_in_at;
        // foreach($this->notification() as $item){
        //     if($item['time'] >= $time){
        //         $i = $i+1;
        //     }
        // }
        // $params['new_notification'] = $i;

        return view('karyawan.index')->with($params);
    }

    public function notification(){
        $data = NULL;
        $link = NULL;
        $text = NULL;
        $payment = PaymentRequest::where('user_id', auth()->user()->id)->where('status', '!=', 1)->orderBy('updated_at', 'DESC')->get();
        foreach($payment as $no => $item){
            if($item->status==2){
                $text = "Your request for payment request has been approved";
            }
            else if($item->status==3){
                $text = "Your request for payment request has been declined";
            }
            $data[$no] = [
                            'notif' => 'Management Form - Payment Request',
                            'time' => $item->updated_at,
                            'link' => '/karyawan/payment-request-custom/'.$item->id.'/edit',
                            'text' => $text,
                            'data' => $item
                        ];
        }

        $ca = CashAdvance::where('user_id', auth()->user()->id)->where(function($qry){
            $qry->where('status', '!=', 1)->orWhere('status_claim', '!=', 1);
        })->orderBy('updated_at', 'DESC')->get();
        //dd(count($ca)+count($data));

        $count = $data != null ? count($data) : 0;
        $link = NULL;
        $text = NULL;
        foreach($ca as $no => $item){
            if($item->status==2){
                $text = "Your request for cash advance has been approved";
                $link =  '/karyawan/cash-advance/'.$item->id.'/edit';
            }
            else if($item->status==3){
                $text = "Your request for cash advance has been declined";
                $link =  '/karyawan/cash-advance/'.$item->id.'/edit';
            }
            if($item->status_claim==2){
                $text = "Your claim request for cash advance has been approved";
                $link =  '/karyawan/cash-advance/claim/'.$item->id;
            }
            else if($item->status_claim==3){
                $text = "Your claim request for cash advance has been declined";
                $link =  '/karyawan/cash-advance/claim/'.$item->id;
            }
            if($text != null && $link != null) {
                $data[$no+$count] = [
                        'notif' => 'Management Form - Cash Advance',
                        'time' => $item->updated_at,
                        'link' => $link != null ? $link : '/karyawan/cash-advance/',
                        'text' => $text,
                        'data' => $item
                ];
            }
        }
        
        $count = $data != null ? count($data) : 0;
        $link = NULL;
        $text = NULL;
        $cuti = CutiKaryawan::where('user_id', auth()->user()->id)->where('status', '!=', 1)->where('status', '!=', 5)->orderBy('updated_at', 'DESC')->get();
        foreach($cuti as $no => $item){
            if($item->status==2){
                $text = "Your request for Leave has been approved";
            }
            else if($item->status==3){
                $text = "Your request for Leave has been declined";
            }
            else if($item->status==7){
                $text = "Your request for Withdrawal Leave has been approved";
            }
            else if($item->status==8){
                $text = "Your request for Withdrawal Leave has been declined";
            }
            if($text != null) {
                $data[$no+$count] = [
                    'notif' => 'Management Form - Leave',
                    'time' => $item->updated_at,
                    'link' => '/karyawan/leave/'.$item->id.'/edit',
                    'text' => $text,
                    'data' => $item
                ];
            }
        }
        //dd($data);
        $count = $data != null ? count($data) : 0;
        $link = NULL;
        $text = NULL;
        $ts = TimesheetPeriod::where('user_id', auth()->user()->id)->where('status', '!=', 1)->where('status', '!=', 4)->orderBy('updated_at', 'DESC')->get();
        //dd($ts);
        foreach($ts as $no => $item){
            if($item->status==2){
                $text = "Your request for Timesheet Period has been approved";
            }
            else if($item->status==3){
                $text = "Your request for Timesheet Period has been declined";
            }
            if($text != null) {
                $data[$no+$count] = [
                    'notif' => 'Management Form - Timesheet',
                    'time' => $item->updated_at,
                    'link' => '/karyawan/timesheet/'.$item->id.'/edit',
                    'text' => $text,
                    'data' => $item
                ];    
            }
        }
        //dd($data);
        $count = $data != null ? count($data) : 0;
        $link = NULL;
        $text = NULL;
        $os = OvertimeSheet::where('user_id', auth()->user()->id)->where(function($qry){
            $qry->where('status', '!=', 1)->orWhere('status_claim', '!=', 1);
        })->orderBy('updated_at', 'DESC')->get();
        foreach($os as $no => $item){
            if($item->status==2){
                $text = "Your request for overtime has been approved";
                $link =  '/karyawan/overtime-custom/'.$item->id.'/edit';
            }
            else if($item->status==3){
                $text = "Your request for overtime has been declined";
                $link =  '/karyawan/overtime-custom/'.$item->id.'/edit';
            }
            if($item->status_claim==2){
                $text = "Your claim request for overtime has been approved";
                $link =  '/karyawan/overtime-custom/claim/'.$item->id;
            }
            else if($item->status_claim==3){
                $text = "Your claim request for overtime has been declined";
                $link =  '/karyawan/overtime-custom/claim/'.$item->id;
            }
            if($text != null && $link != null) {
                $data[$no+$count] = [
                    'notif' => 'Management Form - Overtime Sheet',
                    'time' => $item->updated_at,
                    'link' =>  $link != null ? $link : '/karyawan/overtime-custom/',
                    'text' => $text,
                    'data' => $item
                ];
            }
        }
        $count = $data != null ? count($data) : 0;
        $link = NULL;
        $text = NULL;
        $tr = Training::where('user_id', auth()->user()->id)->where('status', '!=', 1)->where('status', '!=', 4)->where('status_actual_bill', '!=', 1)->where('status_actual_bill', '!=', 4)->orderBy('updated_at', 'DESC')->get();
        foreach($tr as $no => $item){
            if($item->status==2){
                $text = "Your request for Business Trip has been approved";
                $link = '/karyawan/training-custom/'.$item->id.'/edit';
            }
            else if($item->status==3){
                $text = "Your request for Business Trip has been declined";
                $link = '/karyawan/training-custom/'.$item->id.'/edit';
            }

            if($item->status_actual_bill==2){
                $text = "Your request for Business Trip Actual Bill has been approved";
                $link = '/karyawan/training-custom/claim/'.$item->id;
            }
            else if($item->status_actual_bill==3){
                $text = "Your request for Business Trip Actual Bill has been declined";
                $link = '/karyawan/training-custom/claim/'.$item->id;
            }
            if($text != null && $link != null) {
                $data[$no+$count] = [
                    'notif' => 'Management Form - Business Trip',
                    'time' => $item->updated_at,
                    'link' => $link != null ? $link : '/karyawan/training-custom/',
                    'text' => $text,
                    'data' => $item
                ];
            }
        }
        
        $count = $data != null ? count($data) : 0;
        $link = NULL;
        $text = NULL;
        $medical = MedicalReimbursement::where('user_id', auth()->user()->id)->where('status', '!=', 1)->orderBy('updated_at', 'DESC')->get();
        foreach($medical as $no => $item){
            if($item->status==2){
                $text = "Your request for Medical Reimbursement has been approved";
            }
            else if($item->status==3){
                $text = "Your request for Medical Reimbursement has been declined";
            }
            if($text != null) {
                $data[$no+$count] = [
                    'notif' => 'Management Form - Medical Reimbursement',
                    'time' => $item->updated_at,
                    'link' => '/karyawan/medical-custom/'.$item->id.'/edit',
                    'text' => $text,
                    'data' => $item
                ];
            }
        }
        
        $count = $data != null ? count($data) : 0;
        $link = NULL;
        $text = NULL;
        $exit = ExitInterview::where('user_id', auth()->user()->id)->where('status', '!=', 1)->orderBy('updated_at', 'DESC')->get();
        foreach($exit as $no => $item){
            if($item->status==2){
                $text = "Your request for Exit Interview has been approved";
            }
            else if($item->status==3){
                $text = "Your request for Exit Interview has been declined";
            }
            if($text != null) {
                $data[$no+$count] = [
                    'notif' => 'Management Form - Exit Interview',
                    'time' => $item->updated_at,
                    'link' => '/karyawan/exit-custom/'.$item->id.'/edit',
                    'text' => $text,
                    'data' => $item
                ];
            }
            
        }
        
        $count = $data != null ? count($data) : 0;
        $link = NULL;
        $text = NULL;
        $asset = ExitInterview::where('user_id', auth()->user()->id)->where('status_clearance', '!=', 1)->orderBy('updated_at', 'DESC')->get();
        foreach($asset as $no => $item){
            if($item->status_clearance==2){
                $text = "Your request for Exit Clearance has been approved";
            }
            else if($item->status_clearance==3){
                $text = "Your request for Exit Clearance has been declined";
            }

            if($text != null) {
                $data[$no+$count] = [
                    'notif' => 'Management Form - Exit Clearance',
                    'time' => $item->updated_at,
                    'link' => '/karyawan/exit-custom/clearance/'.$item->id,
                    'text' => $text,
                    'data' => $item
                ];
            }
        }

        $count = $data != null ? count($data) : 0;
        $link = NULL;
        $text = NULL;
        $payslip = RequestPaySlip::where('user_id', auth()->user()->id)->where('status', '!=', 1)->orderBy('updated_at', 'DESC')->get();
        foreach($payslip as $no => $item){
            if($item->status==2){
                $text = "Your request for PaySlip has been approved";
            }
            else if($item->status==3){
                $text = "Your request for Payslip has been declined";
            }
            if($text != null) {
                $data[$no+$count] = [
                    'notif' => 'Management Form - Request Payslip',
                    'time' => $item->updated_at,
                    'link' => '/karyawan/request-pay-slip/'.$item->id.'/edit',
                    'text' => $text,
                    'data' => $item
                ];
            }
        }

        $count = $data != null ? count($data) : 0;
        $link = NULL;
        $text = NULL;
        $tracking = AssetTracking::where('user_id', auth()->user()->id)->where('status_return', '!=', 0)->orderBy('updated_at', 'DESC')->get();
        foreach($tracking as $no => $item){
            if($item->status_return==1){
                $text = "Your request for asset return has been approved";
                $data[$no+$count] = [
                    'notif' => 'Management Form - Facilities Return',
                    'time' => $item->updated_at,
                    'link' => '/karyawan/facilities',
                    'text' => $text,
                    'data' => $item
                ];
            }
        }
        
        $count = $data != null ? count($data) : 0;
        $link = NULL;
        $text = NULL;
        $req = RecruitmentRequest::where('requestor_id', auth()->user()->id)->where('approval_hr', '!=', NULL)->where('approval_user', '!=', NULL)->orderBy('updated_at', 'DESC')->get();
        foreach($req as $no => $item){
            if($item->approval_hr==1){
                $text = "Your request for Recruitment has been approved by HR";
            }
            else if($item->approval_hr==0){
                $text = "Your request for Recruitment has been declined by HR";
            }

            if($item->approval_user==1){
                $text = "Your request for Recruitment has been approved by User";
            }
            else if($item->approval_user==0){
                $text = "Your request for Recruitment has been declined by User";
            }
            if($text != null) {
                $data[$no+$count] = [
                    'notif' => 'Management Form - Recruitment Request',
                    'time' => $item->updated_at,
                    'link' => '/karyawan/recruitment-request/'.$item->id.'/edit',
                    'text' => $text,
                    'data' => $item
                ];
            }
            
        }

        $count = $data != null ? count($data) : 0;
        $link = NULL;
        $text = NULL;
        $payslip = Loan::where('user_id', auth()->user()->id)->where('status', '!=', 1)->where('status', '!=', 4)->orderBy('updated_at', 'DESC')->get();
        foreach($payslip as $no => $item){
            if($item->status==2){
                $text = "Your request for Loan has been approved";
            }
            else if($item->status==3){
                $text = "Your request for Loan has been declined";
            }
            if($text != null) {
                $data[$no+$count] = [
                    'notif' => 'Management Form - Request Loan',
                    'time' => $item->updated_at,
                    'link' => '/karyawan/loan/'.$item->id.'/edit',
                    'text' => $text,
                    'data' => $item
                ];
            }
        }

        $approval = notif();
        $count = $data != null ? count($data) : 0;
        $link = NULL;
        $text = NULL;
        if($approval['cash_advance']['waiting'] != 0 && isset($approval['cash_advance']['data'])){
            foreach($approval['cash_advance']['data'] as $no => $item){
                if($item->status==1){
                    $text = "New request for cash advance";
                    $link = '/karyawan/approval-cash-advance/detail/'.$item->id;
                }
    
                if($item->status_claim==1){
                    $text = "New request claim for cash advance";
                    $link = '/karyawan/approval-cash-advance/claim/'.$item->id;
                }

                if($text != null && $link != null) {
                    $data[$no+$count] = [
                        'notif' => 'Management Approval - Cash Advance',
                        'time' => $item->updated_at,
                        'link' => $link != null ? $link : '/karyawan/approval-cash-advance',
                        'text' => $text,
                        'data' => $item
                    ];
                }
            }
        }

        $count = $data != null ? count($data) : 0;
        $link = NULL;
        $text = NULL;
        if($approval['overtime']['waiting'] != 0){
            foreach($approval['overtime']['data'] as $no => $item){
                // dd($item->status);
                if($item->status==1){
                    $text = "New request for overtime sheet";
                    $link = '/karyawan/approval-overtime-custom/detail/'.$item->id;
                }

                if($item->status_claim==1){
                    $text = "New request claim for overtime sheet";
                    $link = '/karyawan/approval-overtime-custom/claim/'.$item->id;
                }
                if($text != null && $link != null) {
                    $data[$no+$count] = [
                        'notif' => 'Management Approval - Overtime Sheet',
                        'time' => $item->updated_at,
                        'link' => $link != null ? $link : '/karyawan/approval-overtime-custom',
                        'text' => $text,
                        'data' => $item
                    ];
                }
            }
        }
        // dd($data);
        $count = $data != null ? count($data) : 0;
        $link = NULL;
        $text = NULL;
        if($approval['leave']['waiting'] != 0){
            foreach($approval['leave']['data'] as $no => $item){
                if($item->status==1){
                    $text = "New request for leave";
                    $link = '/karyawan/approval-leave-custom/detail/'.$item->id;
                }
                else if($item->status==6){
                    $text = "New withdraw request for leave";
                    $link = '/karyawan/approval-leave-custom/detail/'.$item->id;
                }
                if($text != null && $link != null) {
                    $data[$no+$count] = [
                        'notif' => 'Management Approval - Leave',
                        'time' => $item->updated_at,
                        'link' => $link != null ? $link : '/karyawan/approval-leave-custom',
                        'text' => $text,
                        'data' => $item
                    ];
                }
            }
        }

        $count = $data != null ? count($data) : 0;
        $link = NULL;
        $text = NULL;
        if($approval['timesheet']['waiting'] != 0){
            foreach($approval['timesheet']['data'] as $no => $item){
                if($item->status==1){
                    $text = "New request for timesheet";
                    $link = '/karyawan/approval-timesheet-custom/detail/'.$item->id;
                
                    $data[$no+$count] = [
                        'notif' => 'Management Approval - Timesheet',
                        'time' => $item->updated_at,
                        'link' => $link != null ? $link : '/karyawan/approval-timesheet-custom',
                        'text' => $text,
                        'data' => $item
                    ];
                }
            }
        }

        $count = $data != null ? count($data) : 0;
        $link = NULL;
        $text = NULL;
        if($approval['payment']['waiting'] != 0 && isset($approval['payment']['data'])){
            foreach($approval['payment']['data'] as $no => $item){
                if($item->status==1){
                    $text = "New request for payment";
                    $link = '/karyawan/approval-payment-request-custom/detail/'.$item->id;

                    $data[$no+$count] = [
                        'notif' => 'Management Approval - Payment Request',
                        'time' => $item->updated_at,
                        'link' => $link != null ? $link : '/karyawan/approval-payment-request-custom',
                        'text' => $text,
                        'data' => $item
                    ];
                }
            }
        }

        $count = $data != null ? count($data) : 0;
        $link = NULL;
        $text = NULL;
        if($approval['recruitment']['waiting'] != 0){
            foreach($approval['recruitment']['data'] as $no => $item){
                if($item->approval_user==NULL){
                    $text = "New request for recruitment";
                    $link = '/karyawan/approval-recruitment-request/detail/'.$item->id;

                    $data[$no+$count] = [
                        'notif' => 'Management Approval - Recruitment Request',
                        'time' => $item->updated_at,
                        'link' => $link != null ? $link : '/karyawan/approval-recruitment-request',
                        'text' => $text,
                        'data' => $item
                    ];
                }
            }
        }

        $count = $data != null ? count($data) : 0;
        $link = NULL;
        $text = NULL;
        if($approval['training']['waiting'] != 0 && isset($approval['training']['data'])){
            foreach($approval['training']['data'] as $no => $item){
                if($item->status==1){
                    $text = "New request for Business Trip";
                    $link = '/karyawan/approval-training-custom/detail/'.$item->id;
                }

                if($item->status_actual_bill==1){
                    $text = "New request actual bill for Business Trip";
                    $link = '/karyawan/approval-training-custom/claim/'.$item->id;
                }

                if($text != null && $link != null) {
                    $data[$no+$count] = [
                        'notif' => 'Management Approval - Business Trip Request',
                        'time' => $item->updated_at,
                        'link' => $link != null ? $link : '/karyawan/approval-training-custom',
                        'text' => $text,
                        'data' => $item
                    ];
                }
            }
        }

        $count = $data != null ? count($data) : 0;
        $link = NULL;
        $text = NULL;
        if($approval['medical']['waiting'] != 0 && isset($approval['medical']['data'])){
            foreach($approval['medical']['data'] as $no => $item){
                if($item->status==1){
                    $text = "New request for medical";
                    $link = '/karyawan/approval-medical-custom/detail/'.$item->id;

                    $data[$no+$count] = [
                        'notif' => 'Management Approval - Medical Request',
                        'time' => $item->updated_at,
                        'link' => $link,
                        'text' => $text,
                        'data' => $item
                    ];
                }
            }
        }

        $count = $data != null ? count($data) : 0;
        $link = NULL;
        $text = NULL;
        if($approval['exit']['waiting'] != 0){
            foreach($approval['exit']['data'] as $no => $item){
                if($item->status_clearance==1){
                    $text = "New request for exit clearance";
                    $link = '/karyawan/approval-clearance-custom/detail/'.$item->id;

                    $data[$no+$count] = [
                        'notif' => 'Management Approval - Exit Clearance',
                        'time' => $item->updated_at,
                        'link' => $link,
                        'text' => $text,
                        'data' => $item
                    ];
                }
            }
        }

        $count = $data != null ? count($data) : 0;
        $link = NULL;
        $text = NULL;
        if($approval['facilities']['waiting'] != 0){
            foreach($approval['facilities']['data'] as $no => $item){
                if($item->status_return==0){
                    $text = "New request for facilities";
                    $link = '/karyawan/approval-facilities/detail/'.$item->id;

                    $data[$no+$count] = [
                        'notif' => 'Management Approval - Facilities',
                        'time' => $item->updated_at,
                        'link' => $link,
                        'text' => $text,
                        'data' => $item
                    ];
                }
            }
        }

        $count = $data != null ? count($data) : 0;
        $link = NULL;
        $text = NULL;
        if($approval['loan']['waiting'] != 0){
            foreach($approval['loan']['data'] as $no => $item){
                if($item->status==1){
                    $text = "New request for loan";
                    $link = '/karyawan/approval-loan/detail/'.$item->id;

                    $data[$no+$count] = [
                        'notif' => 'Management Approval - Loan',
                        'time' => $item->updated_at,
                        'link' => $link,
                        'text' => $text,
                        'data' => $item
                    ];
                }
            }
        }
        $user = \Auth::user();

        if($user != null){
            $news = News::where('news.status', 1)->orderBy('news.id', 'DESC')->get();

            $count = $data != null ? count($data) : 0;
            if($news != NULL){
                foreach($news as $no => $item){
                    $data[$no+$count] = [
                        'notif' => 'Home - News List',
                        'time' => $item->updated_at,
                        'link' => '/karyawan/news/readmore/'.$item->id,
                        'text' => 'New news posted',
                        'data' => $item
                    ];
                }
            }
            $memo = InternalMemo::where('internal_memo.status', 1)->orderBy('internal_memo.id', 'DESC')->get();
            $count = $data != null ? count($data) : 0;        
            if($memo != NULL){
                foreach($memo as $no => $item){
                    $data[$no+$count] = [
                        'notif' => 'Home - New List',
                        'time' => $item->updated_at,
                        'link' => '/karyawan/internal-memo/readmore/'.$item->id,
                        'text' => 'New internal memo posted',
                        'data' => $item
                    ];
                }
            }

            $product = Product::where('product.status', 1)->orderBy('product.id', 'DESC')->get();
            $count = $data != null ? count($data) : 0;
            if($product != NULL){
                foreach($product as $no => $item){
                    $data[$no+$count] = [
                        'notif' => 'Home - New List',
                        'time' => $item->updated_at,
                        'link' => '/karyawan/product/readmore/'.$item->id,
                        'text' => 'New product posted',
                        'data' => $item
                    ];
                }
            }

            $internal_vacancy    = RecruitmentRequestDetail::join('recruitment_request as rr','recruitment_request_id','=','rr.id')
                                    ->leftJoin('cabang as c', 'rr.branch_id','=','c.id')
                                    ->where([
                                        'recruitment_request_detail.status_post' => 1,
                                        'recruitment_request_detail.recruitment_type_id' => 1,
                                        'rr.approval_hr' => 1,
                                        'rr.approval_user' => 1,
                                        'rr.project_id' => Auth::user()->project_id
                                    ])
                                    ->select(['recruitment_request_detail.*', 'rr.id as recruitment_id', 'rr.min_salary', 'rr.max_salary', 'c.name as branch', 'rr.job_desc', 'rr.job_requirement', 'rr.job_position'])
                                    ->orderBy('recruitment_request_detail.posting_date', 'desc')->get();
            $count = $data != null ? count($data) : 0;
            if($internal_vacancy != NULL){
                foreach($internal_vacancy as $no => $item){
                    $data[$no+$count] = [
                        'notif' => 'Home - New List',
                        'time' => $item->updated_at,
                        'link' => '/karyawan/internal-recruitment/detail/'.$item->id,
                        'text' => 'New internal recruitment posted',
                        'data' => $item
                    ];
                }
            }
        }

        $c = collect($data);
        $sorted = $c->sortByDesc('time');

        return $sorted;
    }

    public function absensiToday(){
        $currentUser = Auth::user();
        $data = [
            'server_time' => date("Y-m-d H:i:s"),
            'out_of_office' => $currentUser->structure?$currentUser->structure->remote_attendance:0
        ];
        $date = null;
        $data['timezone'] = null;
        $data['type']     = 'server';
        if($currentUser->branch && $currentUser->branch->timezone != null){
            $data['timezone'] = $currentUser->branch->timezone;
            $data['branch_time']  = $this->getDatetime($currentUser->branch->timezone);
            $data['type']         = 'branch';
        }
        else{
            $data['branch_time']  = null;
        }
        $currentDate      = date('Y-m-d');
        $remoteAttendance = RemoteAttendance::where('user_id',$currentUser->id)
            ->where('start_date','<=',$currentDate)
            ->where('end_date','>=',$currentDate)
            ->first();

        if($remoteAttendance){
            $data['remote_time']  = $this->getDatetime($remoteAttendance->timezone);
            $data['type']         = 'remote';
            $data['timezone']     = $remoteAttendance->timezone;
        }else{
            $data['remote_time']  = null;
        }
        $data['remote_attendance']   = $remoteAttendance;

        if($data['timezone'] == null){
            $data['timezone'] = $this->getServerTimezone();
        }
        $today = $this->getDate($data['timezone']);
        $todayDateTime = $this->getDatetime($data['timezone']);
        $previousDateTime = date_create($todayDateTime)->modify('-1 days')->format('Y-m-d H:i:s');
        $previousDay = date_create($todayDateTime)->modify('-1 days')->format('Y-m-d');
        $absensi = AbsensiItem::where('user_id', $currentUser->id)->whereRaw("CONCAT(`date`, ' ', `clock_in`,':00') >= '".$previousDateTime."'")->orderByRaw("CONCAT(`date`, ' ', `clock_in`,':00') desc");
        
        $normal_absensi = clone $absensi;
        $normal_absensi = $normal_absensi->where('shift_type', 'normal')->first();

        $absensi = $absensi->first();

        $shift = Shift::find($currentUser->shift_id);

        if((!$shift && $absensi && !is_null($absensi->clock_out)) || ($shift && $absensi && !is_null($absensi->clock_out) && (!$normal_absensi || ($normal_absensi && $normal_absensi->date_shift != $today)))) { // Jika dia tidak punya shift dan sudah clock out || dia punya shift dan absensinya sudah lewat kemarin
            $absensi = null;
        }

        $data['absensi'] = $absensi;
        $data['date_shift'] = $today;
        if($absensi) {
            $data['date_shift'] = $absensi->date_shift;
        } else if($shift && !$normal_absensi) { // Kalau dia tidak absen dalam 24 jam terakhir namun punya shift
            $shiftDetail = ShiftDetail::where('shift_id', $shift->id)->whereRaw("day = '".date('l', strtotime($previousDateTime))."'")->first();  // Cek dulu shift previous day
            $timeNow = $this->getDatetime($data['timezone'],true);
            if($shiftDetail && $shiftDetail->clock_in > $shiftDetail->clock_out && $shiftDetail->clock_out >= $timeNow){ // jika previous day overlap & masih masuk shiftnya...
                $data['date_shift'] = $previousDay;
            }
        }
        
        $data['shift'] = !$shift ? $shift : (count($shiftRoaster = $shift->details->where('day', date('l'))) && ($shift->is_holiday || !count(hari_libur(\Carbon\Carbon::parse($data['date_shift'])->startOfDay(), \Carbon\Carbon::parse($data['date_shift'])->endOfDay()))) && ($shift->is_collective || !CutiBersama::where('dari_tanggal', \Carbon\Carbon::parse($data['date_shift'])->format('Y-m-d'))->where('impacttoleave', 0)->first()) ? [$shiftRoaster->first()] : []);

        $company = [
            'title' => null,
            'attendance_company' => null,
            'attendance_news' => null,
            'attendance_logo' => null
        ];
        $settings = Setting::where(function ($query){
            $query->where('key','like','attendance%')
                ->orWhere('key','=','title');
        })->where('project_id',$currentUser->project_id)
            ->get();
        foreach ($settings as $setting){
            $company[$setting->key] = $setting->value;
        }
        $data['settings'] = $company;

        return $data;
    }

    private function getDatetime($timezone,$hour = false){
        if($timezone == 'WIB'){
            $date = new DateTime("now", new DateTimeZone('Asia/Jakarta'));
        }
        else if($timezone == 'WITA'){
            $date = new DateTime("now", new DateTimeZone('Asia/Shanghai'));
        }
        else if($timezone == 'WIT'){
            $date = new DateTime("now", new DateTimeZone('Asia/Tokyo'));
        }
        else{
            $date = new DateTime("now");
        }
        if(!$hour)
            return $date->format('Y-m-d H:i:s');
        else
            return $date->format('H:i');
    }

    public function getDate($timezone, $format = 'Y-m-d'){
        if($timezone == 'WIB'){
            $date = new DateTime("now", new DateTimeZone('Asia/Jakarta'));
        }
        else if($timezone == 'WITA'){
            $date = new DateTime("now", new DateTimeZone('Asia/Shanghai'));
        }
        else if($timezone == 'WIT'){
            $date = new DateTime("now", new DateTimeZone('Asia/Tokyo'));
        }
        else{
            $date = new DateTime("now");
        }
        return $date->format($format);
    }

    public function getServerTimezone(){
        $timezone = null;
        $utc =  date('Z') / 3600;
        if($utc == '7')
            $timezone = 'WIB';
        else if($utc == '8')
            $timezone = 'WITA';
        else if($utc == '9')
            $timezone = 'WIT';
        return $timezone;
    }

    /**
     * [requestPaySlip description]
     * @return [type] [description]
     */
    public function requestPaySlip()
    {
        $params['data'] = RequestPaySlip::where('user_id', \Auth::user()->id)->get();

        return view('karyawan.request-pay-slip')->with($params);
    }

    /**
     * [profile description]
     * @return [type] [description]
     */
    public function profile(Request $request)
    {
        $params['tab'] = $request->tab ?: false;
        $params['visitlistkaryawan']=VisitList::join('users', 'users.id', '=', 'visit_list.user_id')
                            ->leftJoin('cabang', 'cabang.id', '=', 'visit_list.cabang_id')
                            ->leftJoin('master_visit_type', 'visit_list.master_visit_type_id', '=', 'master_visit_type.id')
                            ->leftJoin('master_category_visit', 'visit_list.master_category_visit_id', '=', 'master_category_visit.id')
                            ->select(
                                'users.nik as nik',
                                'users.name as username',
                                'master_visit_type.master_visit_type_name as master_visit_type_name',
                                'cabang.name as cabang_name',
                                'master_category_visit.master_category_name as master_category_name',
                                'visit_list.*'
                            )
                            ->where('users.id', \Auth::user()->id)
                            ->orderBy('visit_list.visit_time', 'DESC')
                            ->get();
        
//        synchronize_career(\Auth::user()->id);
        $params['data']             = User::where('id', \Auth::user()->id)->first();
        for ($i = 1; $i <= date('t'); $i++) {
            $dates[] = date('Y') . "-" . date('m') . "-" . str_pad($i, 2, '0', STR_PAD_LEFT);
            $ShiftScheduleChange = ShiftScheduleChange::where('change_date', '<=', $dates[count($dates)-1])->whereHas('shiftScheduleChangeEmployees', function($query) {
                $query->where('user_id', \Auth::user()->id);
            })->orderBy('change_date', 'DESC')->first();
            $ShiftScheduleChange = $ShiftScheduleChange ? $ShiftScheduleChange->shift : $params['data']->shift;
            $shiftSchedule['shift'][] = $ShiftScheduleChange ? $ShiftScheduleChange->name : null;
            $ShiftScheduleChangeDetail = $ShiftScheduleChange ? $ShiftScheduleChange->details->where('day', date('l', strtotime($dates[count($dates)-1])))->first() : null;
            $shiftSchedule['shift_in'][] = $ShiftScheduleChangeDetail ? $ShiftScheduleChangeDetail->clock_in : null;
            $shiftSchedule['shift_out'][] = $ShiftScheduleChangeDetail ? $ShiftScheduleChangeDetail->clock_out : null;
            $shiftDay[] = $ShiftScheduleChangeDetail;
        }
        
        $hol = LiburNasional::all();

        $params['holidays']         = $hol;
        $params['shiftSchedule']    = $shiftSchedule;
        $params['shiftScheduleChange'] = ShiftScheduleChange::whereHas('shiftScheduleChangeEmployees', function($query) {
            $query->where('user_id', \Auth::user()->id);
        })->with('shift')->orderBy('change_date', 'DESC')->orderBy('shift_id', 'ASC')->get();
        $params['dates']            = $dates;
        $params['shiftDay']         = $shiftDay;
        $params['absensi_item']     = AbsensiItem::where('user_id', \Auth::user()->id)
                                        ->orderBy('date', 'DESC')
                                        ->orderBy('clock_in', 'DESC')
                                        ->get();
        $params['department']       = OrganisasiDepartment::where('organisasi_division_id', $params['data']['division_id'])->get();
        $params['provinces']        = Provinsi::all();
        $params['dependent']        = UserFamily::where('user_id', \Auth::user()->id)->first();
        $params['certification']    = UserCertification::where('user_id', \Auth::user()->id)->first();
        $params['education']        = UserEducation::where('user_id', \Auth::user()->id)->first();
        $params['kabupaten']        = Kabupaten::where('id_prov', $params['data']['provinsi_id'])->get();
        $params['kecamatan']        = Kecamatan::where('id_kab', $params['data']['kabupaten_id'])->get();
        $params['kelurahan']        = Kelurahan::where('id_kec', $params['data']['kecamatan_id'])->get();
        $params['division']         = OrganisasiDivision::all();
        $params['section']          = OrganisasiSection::where('division_id', $params['data']['division_id'])->get();
        $params['absensi']          = AbsensiItem::whereMonth('date', '=', date('m'))
                                                    ->whereYear('date', '=', date('Y'))
                                                    ->where('user_id', \Auth::user()->id)
                                                    ->orderBy('date', 'DESC')
                                                    ->orderBy('clock_in', 'DESC')
                                                    ->get();
        $params['career']           = CareerHistory::orderBy('effective_date', 'DESC')
                                                    ->orderBy('id', 'DESC')
                                                    ->leftJoin('users as u', 'career_history.user_id', '=', 'u.id')
                                                    ->leftJoin('cabang as c', 'career_history.cabang_id', '=', 'c.id')
                                                    ->leftJoin('structure_organization_custom as so', 'career_history.structure_organization_custom_id', '=', 'so.id')
                                                    ->leftJoin('organisasi_position as op', 'so.organisasi_position_id', '=', 'op.id')
                                                    ->leftJoin('organisasi_division as od', 'so.organisasi_division_id', '=', 'od.id')
                                                    ->leftJoin('organisasi_title as ot', 'so.organisasi_title_id', '=', 'ot.id')
                                                    ->where('career_history.user_id', \Auth::user()->id)
                                                    ->where('effective_date', '<=', Carbon::now()->format('Y-m-d'))
                                                    ->select([
                                                        'career_history.status',
                                                        'career_history.start_date as start',
                                                        'career_history.end_date as end',
                                                        'career_history.id',
                                                        'u.id as user_id',
                                                        'u.name',
                                                        'u.nik',
                                                        'c.name as branch',
                                                        'c.alamat as branch_address',
                                                        \DB::raw("CONCAT(COALESCE(op.name,''),IF(od.name IS NOT NULL, CONCAT(' - ',COALESCE(od.name,'')), ''),IF(ot.name IS NOT NULL, CONCAT(' - ',COALESCE(ot.name,'')), '')) as position"),
                                                        'effective_date',
                                                        'job_desc'
                                                    ])
                                                    ->get();
        $params['type']             = 'exist';
        $params['current']          = '';
        $params['future']           = '';
        $params['join_date']        = \Auth::user()->join_date;
        $params['emp_status']       = \Auth::user()->organisasi_status;
        $params['end_date']         = \Auth::user()->end_date;

        $userData = CareerHistory::orderBy('effective_date', 'DESC')
                        ->where('user_id', \Auth::user()->id)
                        ->where('structure_organization_custom_id', \Auth::user()->structure_organization_custom_id)
                        ->where('effective_date', '<=', Carbon::now()->format('Y-m-d'))
                        ->first();

        $params['general'] = '-';
        $params['additional'] = '-';

        if($userData){
            $params['current'] = $userData->id;
            $params['additional'] = $userData->job_desc ? htmlspecialchars_decode($userData->job_desc) : $userData->job_desc;
            if ($cek = StructureOrganizationCustom::where('id', $userData->structure_organization_custom_id)->first()) {
                $params['general'] = $cek->description ? htmlspecialchars_decode($cek->description) : $cek->description;
            }
        }

        $future = StructureOrganizationCustom::where('id', \Auth::user()->structure_organization_custom_id)->first();
        if($future){
            $futureParent = StructureOrganizationCustom::where('structure_organization_custom.id', $future->parent_id)
                            ->leftJoin('organisasi_position as op', 'structure_organization_custom.organisasi_position_id', '=', 'op.id')
                            ->leftJoin('organisasi_division as od', 'structure_organization_custom.organisasi_division_id', '=', 'od.id')
                            ->leftJoin('organisasi_title as ot', 'structure_organization_custom.organisasi_title_id', '=', 'ot.id')
                            ->select([
                                \DB::raw("CONCAT(COALESCE(op.name,''),IF(od.name IS NOT NULL, CONCAT(' - ',COALESCE(od.name,'')), ''),IF(ot.name IS NOT NULL, CONCAT(' - ',COALESCE(ot.name,'')), '')) as position")
                            ])
                            ->first();

            if($futureParent){
                $params['future'] = $futureParent->position;
            }
        }
        
        if(count($params['career']) == 0){
            $params['type'] = 'not exist';
        }

        $params['grade'] = 'Grade is not available.';
        $params['sub_grade'] = 'Sub Grade is not available.';

        if(\Auth::user()->structure_organization_id != null){
            $params['grade'] = 'Grade is not available.';
            $params['sub_grade'] = 'Sub Grade is not available.';
            $str = StructureOrganizationCustom::where('id', \Auth::user()->structure_organization_id)->first();
            $is_grade = Grade::where('id', $str->grade_id)->first();
            if($is_grade){
                $params['grade'] = $is_grade->name;
                $params['sub_grade'] = 'Sub Grade is not available.';
                if($userData->sub_grade_id != null){
                    $currentCareerGrade = SubGrade::where('id', $userData->sub_grade_id)->first();
                    $params['sub_grade'] = $currentCareerGrade->name;
                }
            }
        }

        return view('karyawan.profile')->with($params);
    }

    public function getVisitPhotos($visitid)
    {   
        $data= VisitPict::select(
            'visit_list_id',
            DB::raw("CONCAT('/', photo) AS photo"),
            'photocaption'
        )
        ->where('visit_list_id', $visitid)->get();
        if($data){
            if(count($data) > 0){
                $res['message'] = 'success';
                $res['data']    = $data;
            }
            else{
                $res['message'] = 'failed';
            }
        }
        else{
            $res['message'] = 'failed';
        }

        return response($res);
    }

    public function ajaxAttendance(){
        $params['data']             = User::where('id', \Auth::user()->id)->first();
        
        $params['holidays'] = [];

        $holiday = Shift::where('id', $params['data']->shift_id)->first();
        if($holiday){
            if($holiday->is_holiday == '0'){
                $hol = LiburNasional::all();
                $params['holidays'] = $hol;
            }
            else{
                $params['holidays'] = [];
            }
        }
        
        $params['absensi_item']     = AbsensiItem::where('user_id', \Auth::user()->id)
                                        ->leftJoin('cabang as ci','cabang_id_in','=','ci.id')
                                        ->leftJoin('cabang as co','cabang_id_out','=','co.id')
                                        ->leftJoin('shift', 'shift.id', '=', 'absensi_item.shift_id')
                                        ->leftJoin('shift_detail', 'absensi_item.shift_id', '=', 'shift_detail.shift_id')
                                        ->orderBy('date', 'DESC')
                                        ->orderBy('clock_in', 'DESC')
                                        ->select(['absensi_item.*','ci.name as cabang_in','co.name as cabang_out', 'shift.name as shift_name', 'shift_detail.id as shift_detail_id', 'shift_detail.clock_in as shift_in', 'shift_detail.clock_out as shift_out'])
                                        ->where(function($query) {
                                            $query->whereColumn('shift_detail.day', 'absensi_item.timetable')
                                                ->orWhereNull('absensi_item.shift_id')                                   
                                                ->orWhere('absensi_item.shift_id', 0);
                                        })
                                        ->get();
        $params['message']          = 'success';

        return response($params);
    }

    /**
     * [find description]
     * @return [type] [description]
     */
    public function find()
    {       
        $data = User::orderBy('id', 'DESC'); 

        if(isset($_GET['name']))
            $data = $data->where('name', 'LIKE', '%'. $_GET['name'] .'%');

        if(isset($_GET['nik']))
            $data = $data->where('nik', 'LIKE', '%'. $_GET['nik'] .'%');

        $params['data'] = $data->get();

        if(!isset($_GET['name']) and !isset($_GET['nik']))
            $params['data'] = [];

        return view('karyawan.find')->with($params);
    }

    /**
     * [readmore description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function readmoreNews($id)
    {
        $params['data']             = News::where('id', $id)->first();
        $params['news_list_right']  = News::where('status', 1)->orderBy('id', 'DESC')->limit(10)->get();
        $params['section']          = 'news';
        $params['title']            = 'News';

        return view('karyawan.news.readmore')->with($params);
    }

    public function readmoreInternalMemo($id)
    {
        $params['data']             = InternalMemo::where('id', $id)->first();
        $params['news_list_right']  = InternalMemo::where('status', 1)->orderBy('id', 'DESC')->limit(10)->get();
        $params['section']          = 'internal-memo';
        $params['title']            = 'Internal Memo';
        //dd($params['data']['image']);
        return view('karyawan.news.readmore')->with($params);
    }

    public function readmoreProduct($id)
    {
        $params['data']             = Product::where('id', $id)->first();
        $params['news_list_right']  = Product::where('status', 1)->orderBy('id', 'DESC')->limit(10)->get();
        $params['section']          = 'product';
        $params['title']            = 'Product';

        return view('karyawan.news.readmore')->with($params);
    }

    /**
     * [downloadInternalMemo description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function downloadInternalMemo($id)
    {   
        $im = InternalMemo::where('id', $id)->first();
        if($im) {
            if($im->file == null || $im->file == '' || !file_exists(public_path('storage/internal-memo/') . $im->file)) {
                return redirect()->back()->with('message-error', 'File is not found!');
            }
            if (count($im->files) < 2) {
                return \Response::download(public_path('storage/internal-memo/').$im->file, $im->title.'.'.pathinfo(public_path('storage/internal-memo/').$im->file, PATHINFO_EXTENSION), []);
            } else {
                $zip = new ZipArchive;
                if ($zip->open(public_path('storage/internal-memo/').$im->title.'.zip', ZipArchive::CREATE) === TRUE) {
                    for($i = 0; $i < $zip->numFiles; $i++) {
                        $zip->deleteIndex($i);
                    }
                    foreach ($im->files as $key => $value) {
                        $zip->addFile(public_path('storage/internal-memo/').$value->file, $value->file);  
                    }
                    $zip->close();
                }
                return \Response::download(public_path('storage/internal-memo/').$im->title.'.zip', $im->title.'.zip', ['Content-Type' => 'application/octet-stream']);
            }
        } else {
            return redirect()->back()->with('message-error', 'Invalid id!');
        }
    }

    /**
     * [downloadProduct description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function downloadProduct($id)
    {   
        $pr = Product::where('id', $id)->first();
        if($pr) {
            if($pr->file == null || $pr->file == '' || !file_exists(public_path('storage/product/') . $pr->file)) {
                return redirect()->back()->with('message-error', 'File is not found!');
            }
            return \Response::download(public_path('storage/product/').$pr->file, $pr->title.'.'.pathinfo(public_path('storage/product/').$pr->file, PATHINFO_EXTENSION), []);
        } else {
            return redirect()->back()->with('message-error', 'Invalid id!');
        }
    }

    /**
     * [newsmore description]
     * @return [type] [description]
     */
    public function newsmore()
    {
        if(\Auth::user()->project_id != Null){
            $params['list']             = News::select('news.*')
                                                ->join('users','users.id','=','news.user_created')
                                                ->where('users.project_id', \Auth::user()->project_id)
                                                ->orderBy('news.id', 'DESC')->get();
            $params['news_list_right']  = News::select('news.*')
                                                ->join('users','users.id','=','news.user_created')
                                                ->where('news.status', 1)
                                                ->where('users.project_id', \Auth::user()->project_id)
                                                ->orderBy('news.id', 'DESC')->get();
        }else{
            $params['list']             = News::orderBy('id', 'DESC')->get();
            $params['news_list_right']  = News::where('news.status', 1)->orderBy('id', 'DESC')->get();
        }
        

        if(isset($_GET['keyword-news']) and !empty($_GET['keyword-news']))
        {
            if(\Auth::user()->project_id != Null){
                $params['news_list_right'] = News::select('news.*')
                                                    ->join('users','users.id','=','news.user_created')
                                                    ->where('news.status', 1)
                                                    ->where('users.project_id', \Auth::user()->project_id)
                                                    ->where('news.title', 'LIKE', '%'. $_GET['keyword-news'] .'%')
                                                    ->orderBy('news.id', 'DESC')->get();
            }else{
                $params['news_list_right'] = News::where('news.status', 1)->where('title', 'LIKE', '%'. $_GET['keyword-news'] .'%')->orderBy('id', 'DESC')->get();
            }
            
        }

        return view('karyawan.more-news')->with($params);
    }

    /**
     * [internalMemoMore description]
     * @return [type] [description]
     */
    public function internalMemoMore()
    {
        if(\Auth::user()->project_id != Null){
            $params['data']                 = InternalMemo::select('internal_memo.*')
                                                            ->join('users','users.id','=','internal_memo.user_created')
                                                            ->where('internal_memo.status', 1)
                                                            ->where('users.project_id', \Auth::user()->project_id)
                                                            ->orderBy('internal_memo.id', 'DESC')->get();
            $params['internal_memo']        = InternalMemo::select('internal_memo.*')
                                                            ->join('users','users.id','=','internal_memo.user_created')
                                                            ->where('internal_memo.status', 1)
                                                            ->where('users.project_id', \Auth::user()->project_id)
                                                            ->orderBy('internal_memo.id', 'DESC')->get();
            $params['product'] = Product::select('product.*')
                                                                    ->join('users','users.id','=','product.user_created')
                                                                    ->where('product.status', 1)
                                                                    ->where('users.project_id', \Auth::user()->project_id)
                                                                    ->orderBy('product.id', 'DESC')->get();
        }else{
            $params['data']                 = InternalMemo::where('status', 1)->orderBy('id', 'DESC')->get();
            $params['internal_memo']        = InternalMemo::where('status', 1)->orderBy('id', 'DESC')->get();
            $params['product'] = Product::where('status', 1)->orderBy('id', 'DESC')->get();
        }
        

        if(isset($_GET['keyword-internal-memo']) and !empty($_GET['keyword-internal-memo']))
        {
            if(\Auth::user()->project_id != Null){
                $params['internal_memo'] = InternalMemo::select('internal_memo.*')
                                                        ->join('users','users.id','=','internal_memo.user_created')
                                                        ->where('internal_memo.status', 1)
                                                        ->where('users.project_id', \Auth::user()->project_id)
                                                        ->where('internal_memo.title', 'LIKE', '%'. $_GET['keyword-internal-memo'] .'%')
                                                        ->orderBy('internal_memo.id', 'DESC')->get();
            }else{
                $params['internal_memo'] = InternalMemo::where('status', 1)->where('title', 'LIKE', '%'. $_GET['keyword-internal-memo'] .'%')->orderBy('id', 'DESC')->get();
            }
        }

        if(isset($_GET['keyword-peraturan']) and !empty($_GET['keyword-peraturan']))
        {
            if(\Auth::user()->project_id != Null){
                $params['product'] = Product::select('product.*')
                                                                        ->join('users','users.id','=','product.user_created')
                                                                        ->where('product.status', 1)
                                                                        ->where('users.project_id', \Auth::user()->project_id)
                                                                        ->where('product.title', 'LIKE', '%'. $_GET['keyword-peraturan'] .'%')
                                                                        ->orderBy('product.id', 'DESC')->get();
            }else{
                $params['product'] = Product::where('status', 1)->where('title', 'LIKE', '%'. $_GET['keyword-peraturan'] .'%')->orderBy('id', 'DESC')->get();
            }
        }

        return view('karyawan.more-internal-memo')->with($params);
    }

    /**
     * [internalRecruitmentMore description]
     * @return [type] [description]
     */
    public function internalRecruitmentMore()
    {
        $params['internal_vacancy']    = RecruitmentRequestDetail::join('recruitment_request as rr','recruitment_request_id','=','rr.id')
            ->leftJoin('cabang as c', 'rr.branch_id','=','c.id')
            ->where([
                'recruitment_request_detail.status_post' => 1,
                'recruitment_request_detail.recruitment_type_id' => 1,
                'rr.approval_hr' => 1,
                'rr.approval_user' => 1,
                'rr.project_id' => Auth::user()->project_id
            ])
            ->select(['recruitment_request_detail.*', 'rr.id as recruitment_id', 'rr.min_salary', 'rr.max_salary', 'c.name as branch', 'rr.job_desc', 'rr.job_requirement', 'rr.job_position'])
            ->orderBy('recruitment_request_detail.posting_date', 'desc');

        if(isset($_GET['keyword']) and !empty($_GET['keyword']))
        {
            $q = $_GET['keyword'];
            $params['internal_vacancy'] = $params['internal_vacancy']->where(function ($query) use ($q){
                $query->where('rr.job_desc', 'LIKE', "%$q%")
                    ->orWhere('rr.job_requirement', 'LIKE', "%$q%")
                    ->orWhere('rr.job_position', 'LIKE', "%$q%");
            });

        }
        $params['internal_vacancy'] = $params['internal_vacancy']->get();
        return view('karyawan.more-internal-recruitment')->with($params);
    }
    public function internalRecruitmentDetail($id)
    {
        $params['vacancy']    = RecruitmentRequestDetail::join('recruitment_request as rr','recruitment_request_id','=','rr.id')
            ->leftJoin('cabang as c', 'rr.branch_id','=','c.id')
            ->where([
                'recruitment_request_detail.status_post' => 1,
                'recruitment_request_detail.recruitment_type_id' => 1,
                'rr.approval_hr' => 1,
                'rr.approval_user' => 1,
                'rr.project_id' => Auth::user()->project_id,
                'rr.id' => $id
            ])
            ->select(['recruitment_request_detail.*', 'rr.id as recruitment_id', 'rr.min_salary', 'rr.max_salary', 'c.name as branch', 'rr.job_desc', 'rr.job_requirement', 'rr.job_position'])->first();
        if($params['vacancy'])
            return view('karyawan.detail-internal-recruitment')->with($params);
        else
            return redirect()->back()->with('message-error', 'Job vacancy is not found !');
    }

    public function applyRecruitment(Request $request){

        $validator = Validator::make(request()->all(), [
            'recruitment_id'  => 'required|exists:recruitment_request_detail,recruitment_request_id',
            'cv' => 'required|mimes:pdf'
        ]);
        $cekApply = getInternalApplicationByUser($request->recruitment_id);
//        return json_encode($cekApply);
        if($cekApply){
            return response()->json(['status' => 'failed', 'message' => 'You already have applied to this vacancy']);
        }
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()]);
        }
        $firstPhase = RecruitmentPhase::where('recruitment_type_id',1)->orderBy('order','asc')->first();
        $application = new RecruitmentApplication();
        $application->recruitment_request_id    = $request->recruitment_id;
        $application->current_phase_id          = $firstPhase->id;
        $application->application_status        = 0;
        $application->cover_letter              = $request->cover_letter;
        $application->save();

        $applicationHistory = new RecruitmentApplicationHistory();
        $applicationHistory->recruitment_application_id = $application->id;
        $applicationHistory->recruitment_phase_id       = $firstPhase->id;
        $applicationHistory->application_status         = 0;

        $applicationHistory->save();

        $cv = $request->cv;

        $name = $application->id.'.'.$cv->getClientOriginalExtension();
        $company_url = session('company_url','umum').'/';
        $destinationPath = public_path('storage/file-cv/').$company_url;

        $cv->move($destinationPath, $name);

        $internalApplication = new InternalApplication();
        $internalApplication->recruitment_application_id = $application->id;
        $internalApplication->user_id                    = Auth::user()->id;
        $internalApplication->cv                         = $company_url.$name;

        $internalApplication->save();

        return response()->json(['status' => 'success', 'message' => 'Your application has been submitted!']);
    }

    /**
     * [autologin description]
     * @return [type] [description]
     */
    public function backtoadministrator()
    {   
        if(\Session::get('is_login_administrator'))
        {
            $user = User::where('id', \Auth::user()->id)->first();
            $user->last_logged_out_at = date('Y-m-d H:i:s');
            $user->save();

            \Auth::loginUsingId(\Session::get('is_login_administrator'));
            \Session::put('is_login_administrator', false);
            return redirect()->route('administrator.dashboard')->with('message-success', 'Welcome Back Administrator');
        }
        else
        {
            return redirect()->route('karyawan.dashboard')->with('message-error', 'Access denied !');
        }
    }

    public function switchToAdmin()
    {
        if(Auth::user()->access_id == 1 && !\Session::get('is_login_administrator'))
        {
            session(['access'=>'admin']);
            return redirect()->route('administrator.dashboard')->with('message-success', 'Welcome Back Administrator');
        }
        else
        {
            return redirect()->route('karyawan.dashboard')->with('message-error', 'Access denied !');
        }
    }

    public function notificationMore(Request $request){
        $notif['tab'] = $request->tab ?: false;
        // $notif['data'] = $this->notification();
        $notif['wording'] = BirthdayWording::get();
        $notif['birthday'] = User::whereMonth('tanggal_lahir', date('m'))->whereDay('tanggal_lahir', date('d'))->where(function($query) {
                                $query->whereNull('non_active_date')->orWhere('non_active_date', '>', \Carbon\Carbon::now());
                            })->with(['birthdayComment' => function($qry){
                                $qry->where('parent_id', NULL)->where('date', date('Y-m-d'))->orderBy('id', 'DESC');
                            }])->where('id', '!=', auth()->user()->id)->get();
        $notif['comment_birthday'] = BirthdayComment::where('user_id', auth()->user()->id)->where('date', date('Y-m-d'))->where('parent_id', NULL)->orderBy('id', 'DESC')->get();
        return view('karyawan.notification')->with($notif);
    }
    
}
