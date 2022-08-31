<?php

namespace App\Http\Controllers\Api;

use App\Http\Middleware\ConnectionMiddleware;
use App\Models\Asset;
use App\Models\AssetType;
use App\Models\AssetTracking;
use App\Models\ConfigDB;
use App\Models\CrmModule;
use App\Models\CrmModuleAdmin;
use App\Models\CutiKaryawan;
use App\Models\ExitInterview;
use App\Models\MedicalReimbursement;
use App\Models\Loan;
use App\Models\News;
use App\Models\InternalMemo;
use App\Models\Product;
use App\Models\RecruitmentRequestDetail;
use App\Models\TimesheetPeriod;
use App\Models\OvertimeSheet;
use App\Models\PaymentRequest;
use App\Models\RequestPaySlip;
use App\Models\Setting;
use App\Models\SettingApprovalClearance;
use App\Models\StructureOrganizationCustom;
use App\Models\Training;
use App\Models\RecruitmentRequest;
use App\Models\CashAdvance;
use App\Http\Resources\UserMinResource;
use App\Services\CreateFreeTrialService;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use \Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Resources\{MedicalResource,TrainingResource,RecruitmentRequestResource,CashAdvanceResource,ExitInterviewResource,AssetTrackingResource};
use App\Http\Resources\{AssetResource,LeaveResource,LoanResource,OvertimeResource,PaymentRequestResource,RequestPaySlipResource};


class AuthController extends Controller
{
    private $MENU_ATTENDANCE = 15;
    private $MENU_VISIT = 28;
    private $MENU_TIMESHEET = 29;
    private $MENU_LEAVE = 4;
    private $MENU_BUSINESS_TRIP = 8;
    private $MENU_PAYSLIP = 13;
    private $MENU_PAYMENT_REQUEST = 6;
    private $MENU_OVERTIME = 7;
    private $MENU_MEDICAL = 5;
    private $MENU_LOAN = 33;
    private $MENU_EXIT_INTERVIEW = 9;
    private $MENU_FACILITIES_MANAGEMENT = 14;
    private $MENU_RECRUITMENT = 27;
    private $MENU_CASH_ADVANCE = 32;

    //
    public function __construct()
    {
        parent::__construct();
        $this->middleware(ConnectionMiddleware::class);
        $this->middleware('jwt.auth')->except(['checkCode','requestReset','login','register']);

    }

    public function checkCode(Request $request){
        $company = getCompany(strtolower($request->company_code));
        if(!$company){
            return response()->json([
                'status' => 'error',
                'message'=>'Company code is invalid'
            ], 401);
        }
        else{
            return response()->json([
                'status' => 'success',
                'data' => [
                    'base_url' => $base_url = env('APP_URL').'/',
                    'base_url_api' => $base_url.'api/mobile/'
                ]
            ], 200);
        }
    }

    public function requestReset(Request $request){

        $validator = Validator::make($request->all(), [
            'nik' => "required"
        ]);

        if($validator->fails())
            return response()->json(['status' => 'error','message'=>$validator->errors()->first()], 401);

        $connection = 'mysql';
        if(!empty($request->company)){
            $config = ConfigDB::where('company_code',strtolower($request->company))->where('due_date', '>=', \Carbon\Carbon::now()->startOfDay())->first();
            if($config){
                $connection = $config->db_name;
            }
            else {
                return abort(404);
            }
        }
        $user      = (new User())->on($connection)->where(['nik'=> $request->nik])->first();
        if(!$user)
            return response()->json(['status' => 'error','message'=>'Employee ID is Invalid'], 401);
        else if(empty($user->email))
            return response()->json(['status' => 'error','message'=>'Your email has not been registered yet! Please contact admin!'], 401);

        $user->password_reset_token = Str::random(32);
        $user->save();
        $params['user']    = $user;
        $params['company'] = $request->company;
        if($user->email != "")
        {
//            try {
                \Mail::send('email.reset-password', $params,
                    function ($message) use ($user) {
                        $message->to($user->email);
                        $message->subject('Em-HR Password Reset');
                    }
                );
//            }catch (\Swift_TransportException $e) {
//                return response()->json(['status' => 'error','message'=>'Oops, Please try again later.'], 403);
//            }
        }
        return response()->json(['status' => 'success','message'=>'Your password reset request has been sent to your email!'], 200);
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(),[
            'nik' => 'required',
            'password' => 'required'
        ]);
        if($validator->fails()){ // Jika parameter tidak sesuai
            return response()->json(['status' => 'error','message'=>$validator->getMessageBag()->first()], 403);
        }
        $credentials = $request->only('nik', 'password');
        try {
            // attempt to verify the credentials and create a token for the user
            $myTTL = 60*24*7; //minutes

            JWTAuth::factory()->setTTL($myTTL);
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['status' => 'error','message'=>'Invalid username or password'], 401);
            }
            $currentUser = Auth::user();

            if($currentUser->inactive_date && Carbon::now() >= $currentUser->inactive_date){
                Auth::logout();
                return response()->json(['status' => 'error','message'=>'Your account has been deactivated'], 401);
            }
            
            $currentUser->last_logged_in_mobile = date('Y-m-d H:i:s');
            $currentUser->apikey = $token;
            $currentUser->os_type = $request->os_type ? strtolower($request->os_type) : 'android';
            $currentUser->app_version = $request->app_version;
            $currentUser->device_name = $request->device_name;
            $currentUser->os_version = $request->os_version;
            $currentUser->save();

            $user = User::with(['cabang','shift.details', 'bank'])->find($currentUser->id);

            $user->division = "";
            $user->position = "";

            $companyName = Setting::where('key','title')->first();
            $user->company_name = $companyName?$companyName->value:"";



            if($currentUser->structure_organization_custom_id != null){
                $structure = StructureOrganizationCustom::leftJoin('organisasi_position as op','structure_organization_custom.organisasi_position_id','=','op.id')
                    ->leftJoin('organisasi_division as od','structure_organization_custom.organisasi_division_id','=','od.id')
                    ->leftJoin('organisasi_title as ot','structure_organization_custom.organisasi_title_id','=','ot.id')
                    ->where('structure_organization_custom.id',$currentUser->structure_organization_custom_id)
                    ->select(['op.name as position','od.name as division','ot.name as title'])
                    ->first();
                if($structure) {
                    $user->position = $structure->position;
                    $user->division = $structure->division;
                    $user->title = $structure->title;
                }
                else{
                    $user->position = "";
                    $user->division = "";
                    $user->title = "";
                }

            }

//            $company = [
//                'title' => null,
//                'attendance_company' => null,
//                'attendance_news' => null,
//                'attendance_logo' => null
//            ];
//            $settings = Setting::where(function ($query){
//                        $query->where('key','like','attendance%')
//                            ->orWhere('key','=','title');
//                    })->where('project_id',$currentUser->project_id)
//                    ->get();
//            foreach ($settings as $setting){
//                $company[$setting->key] = $setting->value;
//            }
            $admin = CrmModuleAdmin::where('user_id',$user->id)->get();
            $data = [
                'user' => $user,
                'admins' => $admin
//                'settings' => $company
            ];
            return response()->json(
                [
                    'status' => 'success',
                    'message'=>'Successfully login',
                    'data' => $data
                ],
                200
            );
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['status' => 'error','message'=>'Could not create token'], 401);
        }
    }

    public function getProfile(Request $request){
        $currentUser = Auth::user();

        $user = User::with(['cabang','shift', 'bank'])->find($currentUser->id);
        $user->division     = "";
        $user->position     = "";

        $companyName = Setting::where('key','title')->first();
        $user->company_name = $companyName?$companyName->value:"";

        if($currentUser->structure_organization_custom_id != null){
            $structure = StructureOrganizationCustom::leftJoin('organisasi_position as op','structure_organization_custom.organisasi_position_id','=','op.id')
                ->leftJoin('organisasi_division as od','structure_organization_custom.organisasi_division_id','=','od.id')
                ->leftJoin('organisasi_title as ot','structure_organization_custom.organisasi_title_id','=','ot.id')
                ->where('structure_organization_custom.id',$currentUser->structure_organization_custom_id)
                ->select(['op.name as position','od.name as division','ot.name as title'])
                ->first();
            if($structure) {
                $user->position = $structure->position;
                $user->division = $structure->division;
                $user->title = $structure->title;
            }
        }
//        $company = [
//            'title' => null,
//            'attendance_company' => null,
//            'attendance_news' => null,
//            'attendance_logo' => null
//        ];
//        $settings = Setting::where(function ($query){
//            $query->where('key','like','attendance%')
//                ->orWhere('key','=','title');
//        })->where('project_id',$currentUser->project_id)
//            ->get();
//        foreach ($settings as $setting){
//            $company[$setting->key] = $setting->value;
//        }
        $admin = CrmModuleAdmin::where('user_id',$user->id)->get();
        $data = [
            'user' => $user,
            'admins' => $admin
//            'settings' => $company
        ];
        return response()->json(
            [
                'status' => 'success',
                'message'=>'Data is collected',
                'data' => $data
            ],
            200
        );
    }

    public function getModules(Request $request){
        $user = Auth::user();
        $data['modules'] = CrmModule::where(['project_id'=>$user->project_id])->get();
        if($data['modules']) {
            foreach ($data['modules'] as $module) {
                if($module->crm_product_id == $this->MENU_TIMESHEET){
                    $module->waiting_request  = TimesheetPeriod::where(['user_id'=>$user->id,'status'=>1])->count();
                    $module->waiting_approval = TimesheetPeriod::whereHas('timesheetPeriodTransaction', function ($query) use ($user) {
                        $query->join('timesheet_categories as tc', function ($join) {
                            $join->on('tc.id', '=', 'timesheet_period_transactions.timesheet_category_id');
                        })->join('setting_approval_timesheet_transaction_item as satti', function ($join) {
                            $join->on('tc.id', '=', 'satti.timesheet_category_id');
                        })->where('status', '=', 1)->where('satti.user_id', '=', $user->id);
                    })->count();
                }
                else if($module->crm_product_id == $this->MENU_LEAVE){
                    $module->waiting_request  = CutiKaryawan::where('user_id', $user->id)->whereIn('status', [1, 6])->count();

//                    SELECT c.id, c.user_id, h.id, h.setting_approval_level_id, h.cuti_karyawan_id, h.structure_organization_custom_id, h.is_approved
//                    FROM cuti_karyawan c
//                    join history_approval_leave h on c.id = h.cuti_karyawan_id and h.setting_approval_level_id = (select min(setting_approval_level_id) from history_approval_leave where cuti_karyawan_id = c.id and is_approved is null) and h.structure_organization_custom_id = 1
//                    where c.status = 1;
                    $module->waiting_approval = DB::table('cuti_karyawan as c')
                        ->join('history_approval_leave as h', function ($join) use ($user) {
                            $join->on('c.id', '=', 'h.cuti_karyawan_id')
                                ->where('h.setting_approval_level_id','=', DB::raw('(select min(setting_approval_level_id) from history_approval_leave where cuti_karyawan_id = c.id and is_approved is null)'))
                                ->where('h.structure_organization_custom_id','=',$user->structure_organization_custom_id);
                        })
                        ->whereIn('c.status', [1, 6])
                        ->groupBy('c.id')
                        ->select('c.id')
                        ->get()
                        ->count();
                }
                else if($module->crm_product_id == $this->MENU_BUSINESS_TRIP){
                    $module->waiting_request  = Training::where(['user_id'=>$user->id])->where(function ($query){
                        $query->where('status',1)->orWhere('status_actual_bill',1);
                    })->count();

                    if(cek_transfer_setting_user()){
                        $module->waiting_approval = DB::table('training as t')
                            ->join('history_approval_training as h', function ($join) use ($user) {
                                $join->on('t.id', '=', 'h.training_id')
                                    ->where('h.setting_approval_level_id','=', DB::raw('(select min(setting_approval_level_id) from history_approval_training where training_id = t.id and (is_approved is null or (is_approved_claim is null and t.status = 2)))'))
                                    ->where('h.structure_organization_custom_id','=',$user->structure_organization_custom_id);
                            })
                            ->where(function ($query){
                                $query->where('t.status',1)->orWhere('t.status_actual_bill',1);
                            })
                            ->count() + getTrainingWaitingTransferCount();;
                    }
                    else{
                        $module->waiting_approval = DB::table('training as t')
                            ->join('history_approval_training as h', function ($join) use ($user) {
                                $join->on('t.id', '=', 'h.training_id')
                                    ->where('h.setting_approval_level_id','=', DB::raw('(select min(setting_approval_level_id) from history_approval_training where training_id = t.id and (is_approved is null or (is_approved_claim is null and t.status = 2)))'))
                                    ->where('h.structure_organization_custom_id','=',$user->structure_organization_custom_id);
                            })
                            ->where(function ($query){
                                $query->where('t.status',1)->orWhere('t.status_actual_bill',1);
                            })
                            ->count();
                    }
                }
                else if($module->crm_product_id == $this->MENU_PAYSLIP){
                    $module->waiting_request  = RequestPaySlip::where(['user_id'=>$user->id,'status'=>1])->count();
                    $module->waiting_approval = 0;
                }
                else if($module->crm_product_id == $this->MENU_PAYMENT_REQUEST){
                    $module->waiting_request  = PaymentRequest::where(['user_id'=>$user->id,'status'=>1])->count();
                    if(cek_transfer_setting_user()){
                        $module->waiting_approval = DB::table('payment_request as p')
                            ->join('history_approval_payment_request as h', function ($join) use ($user) {
                                $join->on('p.id', '=', 'h.payment_request_id')
                                    ->where('h.setting_approval_level_id','=', DB::raw('(select min(setting_approval_level_id) from history_approval_payment_request where payment_request_id = p.id and is_approved is null)'))
                                    ->where('h.structure_organization_custom_id','=',$user->structure_organization_custom_id);
                            })
                            ->where('p.status','=',1)
                            ->count() + getPaymentRequestCount();
                    }
                    else{
                        $module->waiting_approval = DB::table('payment_request as p')
                        ->join('history_approval_payment_request as h', function ($join) use ($user) {
                            $join->on('p.id', '=', 'h.payment_request_id')
                                ->where('h.setting_approval_level_id','=', DB::raw('(select min(setting_approval_level_id) from history_approval_payment_request where payment_request_id = p.id and is_approved is null)'))
                                ->where('h.structure_organization_custom_id','=',$user->structure_organization_custom_id);
                        })
                        ->where('p.status','=',1)
                        ->count();
                    }
                }
                else if($module->crm_product_id == $this->MENU_OVERTIME){
                    $module->waiting_request  = OvertimeSheet::where(['user_id'=>$user->id])->where(function ($query){
                        $query->where('status',1)->orWhere('status_claim',1);
                    })->count();
                    $module->waiting_approval = DB::table('overtime_sheet as o')
                        ->join('history_approval_overtime as h', function ($join) use ($user) {
                            $join->on('o.id', '=', 'h.overtime_sheet_id')
                                ->where('h.setting_approval_level_id','=', DB::raw('(select min(setting_approval_level_id) from history_approval_overtime where overtime_sheet_id = o.id and (is_approved is null or (is_approved_claim is null and o.status = 2)))'))
                                ->where('h.structure_organization_custom_id','=',$user->structure_organization_custom_id);
                        })
                        ->where(function ($query){
                            $query->where('o.status',1)->orWhere('o.status_claim',1);
                        })
                        ->count();
                }
                else if($module->crm_product_id == $this->MENU_CASH_ADVANCE){
                    $module->waiting_request  = CashAdvance::where(['user_id'=>$user->id])->where(function ($query){
                            $query->where('status',1)->orWhere('status_claim',1);
                        })->count();
                    if(cek_transfer_setting_user()){
                        $module->waiting_approval = DB::table('cash_advance as o')
                            ->join('history_approval_cash_advance as h', function ($join) use ($user) {
                                $join->on('o.id', '=', 'h.cash_advance_id')
                                    ->where('h.setting_approval_level_id','=', DB::raw('(select min(setting_approval_level_id) from history_approval_cash_advance where cash_advance_id = o.id and (is_approved is null or (is_approved_claim is null and o.status = 2)))'))
                                    ->where('h.structure_organization_custom_id','=',$user->structure_organization_custom_id);
                            })
                            ->where(function ($query){
                                $query->where('o.status',1)->orWhere('o.status_claim',1);
                            })
                            ->count() + getCashAdvanceWaitingTransferCount();
                    }
                    else{
                        $module->waiting_approval = DB::table('cash_advance as o')
                            ->join('history_approval_cash_advance as h', function ($join) use ($user) {
                                $join->on('o.id', '=', 'h.cash_advance_id')
                                    ->where('h.setting_approval_level_id','=', DB::raw('(select min(setting_approval_level_id) from history_approval_cash_advance where cash_advance_id = o.id and (is_approved is null or (is_approved_claim is null and o.status = 2)))'))
                                    ->where('h.structure_organization_custom_id','=',$user->structure_organization_custom_id);
                            })
                            ->where(function ($query){
                                $query->where('o.status',1)->orWhere('o.status_claim',1);
                            })
                            ->count();
                    }
                }
                else if($module->crm_product_id == $this->MENU_MEDICAL){
                    $module->waiting_request  = MedicalReimbursement::where(['user_id'=>$user->id,'status'=>1])->count();
                    if(cek_transfer_setting_user()){
                        $module->waiting_approval = DB::table('medical_reimbursement as r')
                            ->join('history_approval_medical as h', function ($join) use ($user) {
                                $join->on('r.id', '=', 'h.medical_reimbursement_id')
                                    ->where('h.setting_approval_level_id','=', DB::raw('(select min(setting_approval_level_id) from history_approval_medical where medical_reimbursement_id = r.id and is_approved is null)'))
                                    ->where('h.structure_organization_custom_id','=',$user->structure_organization_custom_id);
                            })
                            ->where('r.status','=',1)
                            ->count() + getMedicalCount();
                    }
                    else{
                        $module->waiting_approval = DB::table('medical_reimbursement as r')
                            ->join('history_approval_medical as h', function ($join) use ($user) {
                                $join->on('r.id', '=', 'h.medical_reimbursement_id')
                                    ->where('h.setting_approval_level_id','=', DB::raw('(select min(setting_approval_level_id) from history_approval_medical where medical_reimbursement_id = r.id and is_approved is null)'))
                                    ->where('h.structure_organization_custom_id','=',$user->structure_organization_custom_id);
                            })
                            ->where('r.status','=',1)
                            ->count();
                    }

                }
                else if($module->crm_product_id == $this->MENU_LOAN){
                    $module->waiting_request  = Loan::where(['user_id'=>$user->id,'status'=>1])->count();
                    $module->waiting_approval = DB::table('loan as l')
                        ->join('history_approval_loan as h', function ($join) use ($user) {
                            $join->on('l.id', '=', 'h.loan_id')
                                ->where('h.setting_approval_level_id','=', DB::raw('(select min(setting_approval_level_id) from history_approval_loan where loan_id = l.id and is_approved is null)'))
                                ->where('h.structure_organization_custom_id','=',$user->structure_organization_custom_id);
                        })
                        ->where('l.status','=',1)
                        ->count();
                }
                else if($module->crm_product_id == $this->MENU_EXIT_INTERVIEW){
                    $clearanceModule = clone($module);
                    $module->waiting_request  = ExitInterview::where(['user_id'=>$user->id,'status'=>1])->count();
                    $module->waiting_approval = DB::table('exit_interview as e')
                        ->join('history_approval_exit as h', function ($join) use ($user) {
                            $join->on('e.id', '=', 'h.exit_interview_id')
                                ->where('h.setting_approval_level_id','=', DB::raw('(select min(setting_approval_level_id) from history_approval_exit where exit_interview_id = e.id and is_approved is null)'))
                                ->where('h.structure_organization_custom_id','=',$user->structure_organization_custom_id);
                        })
                        ->where('e.status','=',1)
                        ->count();
                    $clearanceModule->id               = ((String)$clearanceModule->id)."b";
                    $clearanceModule->crm_product_id   = ((String)$clearanceModule->crm_product_id).'b';
                    $clearanceModule->modul_name       = 'Exit Clearance';
                    $clearanceModule->waiting_request  = 0;
                    $clearanceModule->waiting_approval = ExitInterview::whereRaw("(select count(*) from exit_interview_assets ea
                    join asset a on ea.asset_id = a.id
                    join asset_type as at on a.asset_type_id = at.id
                    where exit_interview.id = ea.exit_interview_id and ea.approval_check is null and exit_interview.status_clearance = 0
                           and at.pic_department in (SELECT sc.nama_approval from setting_approval_clearance sc where sc.user_id = ?)) > ?",[$user->id, 0])->count();
                    $data['modules']->push($clearanceModule);

                    /*select e.* from exit_interview e
                    where (select count(*) from exit_interview_assets ea
                    join asset a on ea.asset_id = a.id
                    join asset_type as at on a.asset_type_id = at.id
                    where e.id = ea.exit_interview_id and ea.approval_check is null
                           and at.pic_department in (SELECT sc.nama_approval from setting_approval_clearance sc where sc.user_id = 17)) > 0;*/

                }
                else if($module->crm_product_id == $this->MENU_FACILITIES_MANAGEMENT){
                    $module->waiting_request  = Asset::where(['user_id'=>$user->id,'status'=>0])->count();
                    $pics = SettingApprovalClearance::where('user_id',$user->id)->pluck('nama_approval')->toArray();
                    if($pics != null){
                        $type = AssetType::whereIn('pic_department', $pics)->pluck('id')->toArray();
                        $module->waiting_approval = AssetTracking::whereHas('asset', function($qry) use($type){
                            $qry->whereIn('asset_type_id', $type)->where('status', 2);
                        })->where('is_return', '1')->where('status_return', '0')->count();; 
                    }
                    else{
                        $module->waiting_approval = 0;
                    }
                    
                }
                else if($module->crm_product_id == $this->MENU_RECRUITMENT){
                    $module->waiting_request  = RecruitmentRequest::where('requestor_id',$user->id)->whereIn('status',[1,4])->count();
                    $module->waiting_approval = DB::table('recruitment_request as rr')
                        ->join('history_approval_recruitment as h', function ($join) use ($user) {
                            $join->on('rr.id', '=', 'h.recruitment_request_id')
                                ->where('h.setting_approval_level_id','=', DB::raw('(select min(setting_approval_level_id) from history_approval_recruitment where recruitment_request_id = rr.id and is_approved is null)'))
                                ->where('h.structure_organization_custom_id','=',$user->structure_organization_custom_id);
                        })
                        ->where('rr.status','=',1)
                        ->count();
                }
            }

            $calendar["id"] = 0;
            $calendar["project_id"] = $data['modules'][0]->project_id;
            $calendar["project_name"] = $data['modules'][0]->project_name;
            $calendar["client_name"] = $data['modules'][0]->client_name;
            $calendar["user_name"] = $data['modules'][0]->user_name;
            $calendar["password"] = $data['modules'][0]->password;
            $calendar["crm_product_id"] = "0";
            $calendar["limit_user"] = null;
            $calendar["modul_name"] = "Calendar";
            $calendar["created_at"] = $data['modules'][0]->created_at->format('Y-m-d H:i:s');
            $calendar["updated_at"] = $data['modules'][0]->updated_at->format('Y-m-d H:i:s');
            $calendar["waiting_request"] = 0;
            $calendar["waiting_approval"] = 0;

            $data['modules'][count($data['modules'])] = $calendar;
        }
        return response()->json(
            [
                'status' => 'success',
                'message'=> 'Data',
                'data' => $data
            ],
            200
        );
    }

    public function validateToken(Request $request){
        $user = Auth::user();
        $api_key = str_ireplace('Bearer', '', $request->header('Authorization', null));
        info("Validate by ".$api_key);
        if($user->apikey != $api_key){
            return response()->json(['status' => 'error','message'=>'Your session has expired'], 401);
        }
        else{
            return response()->json(['status' => 'success','message'=>'Your session is still valid'], 200);
        }
    }

    function updatePassword(Request $request){
        $rules = [
            'password' => 'required|string',
            'new_password' => 'required|string|min:8|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[_#?!@$%^&*-]).{8,}$/',
        ];
        $validator = Validator::make($request->all(), $rules,
            [
                'new_password.regex' => 'Password should contain : Lowercase(s), Uppercase(s), Number(s), Symbol!'
            ]);

        if ($validator->fails()) {
            $errors = [];
            foreach ($rules as $key => $rule){
                if($validator->errors()->has($key)){
                    array_push($errors,['field'=>$key,'message'=>$validator->errors()->first($key)]);
                }
            }
            return response()->json([
                'status' => 'error',
                'message'=> 'Failed',
                'errors'  => $errors
            ],401);
        }

        else {
            $user = Auth::user();
            if(!Hash::check($request->password, $user->password))
            {
                return response()->json(['status' => 'error','message'=>'Failed','errors'=>[[
                    'field' => 'password',
                    'message'=> 'Your current password is invalid'
                ]]], 403);
            }
            $user->password = Hash::make($request->new_password);
            $user->last_change_password = date('Y-m-d H:i:s');
            $user->is_reset_first_password = 1;
            $user->save();
            return response()->json(['status' => 'success','message'=>'Your password has succesfully changed!'], 200);
        }
    }

    function updateFirebaseToken(Request $request){
        $rules = [
            'firebase_token' => 'required|string',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['status' => 'error','message'=>$validator->getMessageBag()->first()], 403);
        }

        else {
            $user = Auth::user();
            $user->firebase_token = $request->firebase_token;
            $user->save();
            return response()->json(['status' => 'success','message'=>'Your firebase token has succesfully updated!'], 200);
        }
    }

    public function register(Request $request, CreateFreeTrialService $createFreeTrialService){
        $validator = Validator::make(request()->all(), [
            'nama' => 'required',
            'jabatan' => 'required',
            'email' => 'email|required',
            'nama_perusahaan' => 'required',
            'bidang_usaha' => 'required',
            'handphone' => 'required',
        ]);
        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->getMessages() as $key => $error){
                array_push($errors,['field'=>$key,'message'=>$error[0]]);
            }
            return response()->json(['status' => 'failed', 'message' => 'The given data was invalid', 'errors'=> $errors],401);
        }
        $createFreeTrialService->handle($request);
        return response()->json(
            [
                'status'    => 'success',
                'message'   => 'Thank you for register, please check your email!',
                'error'     => null
            ],
            200
        );
    }

    public function notification()
    {
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 10;

        $data = null;
        $link = null;
        $text = null;

        if (\Cache::has('notification') && $currentPage != 1){
            $data = \Cache::get('notification');
        } else {
            $payment = PaymentRequest::where('user_id', auth()->user()->id)->where('status', '!=', 1)->orderBy('updated_at', 'DESC')->get();
            foreach ($payment as $item) {
                if ($item->status == 2) {
                    $text = "Your request for payment request has been approved";
                } else if ($item->status == 3) {
                    $text = "Your request for payment request has been declined";
                }
                $data[] = [
                    'notif' => 'Management Form - Payment Request',
                    'time' => $item->updated_at,
                    'link' => 'payment-request/' . $item->id,
                    'type' => 'payment_request',
                    'id'   => $item->id,
                    'text' => $text,
                    'data' => $item,
                ];
            }

            $ca = CashAdvance::where('user_id', auth()->user()->id)->where(function ($qry) {
                $qry->where('status', '!=', 1)->orWhere('status_claim', '!=', 1);
            })->orderBy('updated_at', 'DESC')->get();
            foreach ($ca as $item) {
                if ($item->status == 2) {
                    $text = "Your request for cash advance has been approved";
                    $link = 'cash-advance/' . $item->id;
                    $type = 'cash_advance';
                } else if ($item->status == 3) {
                    $text = "Your request for cash advance has been declined";
                    $link = 'cash-advance/' . $item->id;
                    $type = 'cash_advance';
                }
                if ($item->status_claim == 2) {
                    $text = "Your claim request for cash advance has been approved";
                    $link = 'cash-advance/' . $item->id;
                    $type = 'claim_cash_advance';
                } else if ($item->status_claim == 3) {
                    $text = "Your claim request for cash advance has been declined";
                    $link = 'cash-advance/' . $item->id;
                    $type = 'claim_cash_advance';
                }
                if ($text != null && $link != null) {
                    $data[] = [
                        'notif' => 'Management Form - Cash Advance',
                        'time' => $item->updated_at,
                        'link' => $link != null ? $link : '/cash-advance',
                        'type' => $type,
                        'id'   => $item->id,
                        'text' => $text,
                        'data' => $item,
                    ];
                }
            }

            $cuti = CutiKaryawan::where('user_id', auth()->user()->id)->where('status', '!=', 1)->where('status', '!=', 5)->orderBy('updated_at', 'DESC')->get();
            foreach ($cuti as $item) {
                if ($item->status == 2) {
                    $text = "Your request for Leave has been approved";
                } else if ($item->status == 3) {
                    $text = "Your request for Leave has been declined";
                } else if ($item->status == 7) {
                    $text = "Your request for Withdrawal Leave has been approved";
                } else if ($item->status == 8) {
                    $text = "Your request for Withdrawal Leave has been declined";
                }
                if ($text != null) {
                    $data[] = [
                        'notif' => 'Management Form - Leave',
                        'time' => $item->updated_at,
                        'link' => 'leave/' . $item->id,
                        'type' => 'leave',
                        'id'   => $item->id,
                        'text' => $text,
                        'data' => $item,
                    ];
                }
            }
            
            $ts = TimesheetPeriod::where('user_id', auth()->user()->id)->where('status', '!=', 1)->where('status', '!=', 4)->orderBy('updated_at', 'DESC')->get();
            foreach ($ts as $item) {
                if ($item->status == 2) {
                    $text = "Your request for Timesheet Period has been approved";
                } else if ($item->status == 3) {
                    $text = "Your request for Timesheet Period has been declined";
                }
                if ($text != null) {
                    $data[] = [
                        'notif' => 'Management Form - Timesheet',
                        'time' => $item->updated_at,
                        'link' => 'timesheet/' . $item->id,
                        'type' => 'timesheet',
                        'id'   => $item->id,
                        'text' => $text,
                        'data' => $item,
                    ];
                }
            }
            
            $os = OvertimeSheet::where('user_id', auth()->user()->id)->where(function ($qry) {
                $qry->where('status', '!=', 1)->orWhere('status_claim', '!=', 1);
            })->orderBy('updated_at', 'DESC')->get();
            foreach ($os as $item) {
                if ($item->status == 2) {
                    $text = "Your request for overtime has been approved";
                    $link = 'overtime/' . $item->id;
                    $type = 'overtime';
                } else if ($item->status == 3) {
                    $text = "Your request for overtime has been declined";
                    $link = 'overtime/' . $item->id;
                    $type = 'overtime';
                }
                if ($item->status_claim == 2) {
                    $text = "Your claim request for overtime has been approved";
                    $link = 'overtime/' . $item->id;
                    $type = 'overtime';
                } else if ($item->status_claim == 3) {
                    $text = "Your claim request for overtime has been declined";
                    $link = 'overtime/' . $item->id;
                    $type = 'overtime';
                }
                if ($text != null && $link != null) {
                    $data[] = [
                        'notif' => 'Management Form - Overtime Sheet',
                        'time' => $item->updated_at,
                        'link' => $link != null ? $link : '/overtime',
                        'type' => $type,
                        'id'   => $item->id,
                        'text' => $text,
                        'data' => $item,
                    ];
                }
            }
            
            $tr = Training::where('user_id', auth()->user()->id)->where('status', '!=', 1)->where('status', '!=', 4)->where('status_actual_bill', '!=', 1)->where('status_actual_bill', '!=', 4)->orderBy('updated_at', 'DESC')->get();
            foreach ($tr as $item) {
                if ($item->status == 2) {
                    $text = "Your request for Business Trip has been approved";
                    $link = 'training/' . $item->id;
                    $type = 'business_trip';
                } else if ($item->status == 3) {
                    $text = "Your request for Business Trip has been declined";
                    $link = 'training/' . $item->id;
                    $type = 'business_trip';
                }
                if ($item->status_actual_bill == 2) {
                    $text = "Your request for Business Trip Actual Bill has been approved";
                    $link = 'training/' . $item->id;
                    $type = 'training';
                } else if ($item->status_actual_bill == 3) {
                    $text = "Your request for Business Trip Actual Bill has been declined";
                    $link = 'training/' . $item->id;
                    $type = 'training_reject';
                }
                if ($text != null && $link != null) {
                    $data[] = [
                        'notif' => 'Management Form - Business Trip',
                        'time' => $item->updated_at,
                        'link' => $link != null ? $link : '/training',
                        'type' => $type,
                        'id'   => $item->id,
                        'text' => $text,
                        'data' => $item,
                    ];
                }
            }
            
            $medical = MedicalReimbursement::where('user_id', auth()->user()->id)->where('status', '!=', 1)->orderBy('updated_at', 'DESC')->get();
            foreach ($medical as $item) {
                if ($item->status == 2) {
                    $text = "Your request for Medical Reimbursement has been approved";
                } else if ($item->status == 3) {
                    $text = "Your request for Medical Reimbursement has been declined";
                }
                if ($text != null) {
                    $data[] = [
                        'notif' => 'Management Form - Medical Reimbursement',
                        'time' => $item->updated_at,
                        'link' => 'medical/' . $item->id,
                        'type' => 'medical',
                        'id'   => $item->id,
                        'text' => $text,
                        'data' => $item,
                    ];
                }
            }
            
            $exit = ExitInterview::where('user_id', auth()->user()->id)->where('status', '!=', 1)->orderBy('updated_at', 'DESC')->get();
            foreach ($exit as $item) {
                if ($item->status == 2) {
                    $text = "Your request for Exit Interview has been approved";
                } else if ($item->status == 3) {
                    $text = "Your request for Exit Interview has been declined";
                }
                if ($text != null) {
                    $data[] = [
                        'notif' => 'Management Form - Exit Interview',
                        'time' => $item->updated_at,
                        'link' => 'exit-interview/' . $item->id,
                        'type' => 'exit_interview',
                        'id'   => $item->id,
                        'text' => $text,
                        'data' => $item,
                    ];
                }
            }
            
            $asset = ExitInterview::where('user_id', auth()->user()->id)->where('status_clearance', '!=', 0)->orderBy('updated_at', 'DESC')->get();
            foreach ($asset as $item) {
                if ($item->status_clearance == 1) {
                    $text = "Your request for Exit Clearance has been approved";
                } else if ($item->status_clearance == 2) {
                    $text = "Your request for Exit Clearance has been declined";
                }
                if ($text != null) {
                    $data[] = [
                        'notif' => 'Management Form - Exit Clearance',
                        'time' => $item->updated_at,
                        'link' => 'exit-interview/' . $item->id,
                        'type' => 'exit_interview',
                        'id'   => $item->id,
                        'text' => $text,
                        'data' => $item,
                    ];
                }
            }

            $payslip = RequestPaySlip::where('user_id', auth()->user()->id)->where('status', '!=', 1)->orderBy('updated_at', 'DESC')->get();
            foreach ($payslip as $item) {
                if ($item->status == 2) {
                    $text = "Your request for PaySlip has been approved";
                } else if ($item->status == 3) {
                    $text = "Your request for Payslip has been declined";
                }
                if ($text != null) {
                    $data[] = [
                        'notif' => 'Management Form - Request Payslip',
                        'time' => $item->updated_at,
                        'link' => 'request-payslip/' . $item->id,
                        'type' => 'payslip',
                        'id'   => $item->id,
                        'text' => $text,
                        'data' => $item,
                    ];
                }
            }

            $tracking = AssetTracking::where('user_id', auth()->user()->id)->where('status_return', '!=', 0)->orderBy('updated_at', 'DESC')->get();
            foreach ($tracking as $item) {
                if ($item->status_return == 1) {
                    $text = "Your request for asset return has been approved";
                    $data[] = [
                        'notif' => 'Management Form - Facilities Return',
                        'time' => $item->updated_at,
                        'link' => '/facilities',
                        'type' => 'facilities_return_approv',
                        'id'   => $item->id,
                        'text' => $text,
                        'data' => $item,
                    ];
                }
            }

            
            $req = RecruitmentRequest::where('requestor_id', auth()->user()->id)->where('approval_hr', '!=', null)->where('approval_user', '!=', null)->orderBy('updated_at', 'DESC')->get();
            foreach ($req as $item) {
                if ($item->approval_hr == 1) {
                    $text = "Your request for Recruitment has been approved by HR";
                } else if ($item->approval_hr == 0) {
                    $text = "Your request for Recruitment has been declined by HR";
                }
                if ($item->approval_user == 1) {
                    $text = "Your request for Recruitment has been approved by User";
                } else if ($item->approval_user == 0) {
                    $text = "Your request for Recruitment has been declined by User";
                }
                if ($text != null) {
                    $data[] = [
                        'notif' => 'Management Form - Recruitment Request',
                        'time' => $item->updated_at,
                        'link' => 'recruitment-request/' . $item->id,
                        'type' => 'recruitment',
                        'id'   => $item->id,
                        'text' => $text,
                        'data' => $item,
                    ];
                }
            }

            $approval = notif();
            if ($approval['cash_advance']['waiting'] != 0 && isset($approval['cash_advance']['data'])) {
                foreach ($approval['cash_advance']['data'] as $item) {
                    if ($item->status == 1) {
                        $text = "New request for cash advance";
                        $link = 'cash-advance/' . $item->id;
                        $type = 'cash_advance_approval';
                    }
                    if ($item->status_claim == 1) {
                        $text = "New request claim for cash advance";
                        $link = 'cash-advance/' . $item->id;
                        $type = 'claim_cash_advance_approval';
                    }
                    if ($text != null && $link != null) {
                        $data[] = [
                            'notif' => 'Management Approval - Cash Advance',
                            'time' => $item->updated_at,
                            'link' => $link != null ? $link : '/cash-advance',
                            'type' => $type,
                            'id'   => $item->id,
                            'text' => $text,
                            'data' => $item,
                        ];
                    }
                }
            }

            if ($approval['overtime']['waiting'] != 0) {
                foreach ($approval['overtime']['data'] as $item) {
                    if ($item->status == 1) {
                        $text = "New request for overtime sheet";
                        $link = 'overtime/' . $item->id;
                        $type = 'overtime_approval';
                    }
                    if ($item->status_claim == 1) {
                        $text = "New request claim for overtime sheet";
                        $link = 'overtime/' . $item->id;
                        $type = 'overtime_approval';
                    }
                    if ($text != null && $link != null) {
                        $data[] = [
                            'notif' => 'Management Approval - Overtime Sheet',
                            'time' => $item->updated_at,
                            'link' => $link != null ? $link : 'overtime',
                            'type' => $type,
                            'id'   => $item->id,
                            'text' => $text,
                            'data' => $item,
                        ];
                    }
                }
            }

            if ($approval['leave']['waiting'] != 0) {
                foreach ($approval['leave']['data'] as $item) {
                    if ($item->status == 1) {
                        $text = "New request for leave";
                        $link = 'leave/' . $item->id;
                    } else if ($item->status == 6) {
                        $text = "New withdraw request for leave";
                        $link = 'leave/' . $item->id;
                    }
                    if ($text != null && $link != null) {
                        $data[] = [
                            'notif' => 'Management Approval - Leave',
                            'time' => $item->updated_at,
                            'link' => $link != null ? $link : '/leave',
                            'type' => 'leave_approval',
                            'id'   => $item->id,
                            'text' => $text,
                            'data' => $item,
                        ];
                    }
                }
            }

            if ($approval['timesheet']['waiting'] != 0) {
                foreach ($approval['timesheet']['data'] as $item) {
                    $text = "New request for timesheet";
                    $link = 'timesheet/' . $item->id;
                    $data[] = [
                        'notif' => 'Management Approval - Timesheet',
                        'time' => $item->updated_at,
                        'link' => $link != null ? $link : '/timesheet',
                        'type' => 'timesheet_approval',
                        'id'   => $item->id,
                        'text' => $text,
                        'data' => $item,
                    ];
                }
            }

            if ($approval['payment']['waiting'] != 0 && isset($approval['payment']['data'])) {
                foreach ($approval['payment']['data'] as $item) {
                    if ($item->status == 1) {
                        $text = "New request for payment";
                        $link = 'payment-request/' . $item->id;
                        $data[] = [
                            'notif' => 'Management Approval - Payment Request',
                            'time' => $item->updated_at,
                            'link' => $link != null ? $link : '/payment-request',
                            'type' => 'approval_payment_request',
                            'id'   => $item->id,
                            'text' => $text,
                            'data' => $item,
                        ];
                    }
                }
            }

            if ($approval['recruitment']['waiting'] != 0) {
                foreach ($approval['recruitment']['data'] as $item) {
                    $text = "New request for recruitment";
                    $link = 'recruitment-request/' . $item->id;
                    $data[] = [
                        'notif' => 'Management Approval - Recruitment Request',
                        'time' => $item->updated_at,
                        'link' => $link != null ? $link : '/recruitment-request',
                        'type' => 'recruitment_approval',
                        'id'   => $item->id,
                        'text' => $text,
                        'data' => $item,
                    ];
                }
            }

            if ($approval['training']['waiting'] != 0 && isset($approval['training']['data'])) {
                foreach ($approval['training']['data'] as $item) {
                    if ($item->status == 1) {
                        $text = "New request for Business Trip";
                        $link = 'training/' . $item->id;
                        $type = 'business_trip_approval';
                    }
                    if ($item->status_actual_bill == 1) {
                        $text = "New request actual bill for Business Trip";
                        $link = 'training/' . $item->id;
                        $type = 'training_approval';
                    }
                    if ($text != null && $link != null) {
                        $data[] = [
                            'notif' => 'Management Approval - Business Trip Request',
                            'time' => $item->updated_at,
                            'link' => $link != null ? $link : '/training',
                            'type' => $type,
                            'id'   => $item->id,
                            'text' => $text,
                            'data' => $item,
                        ];
                    }
                }
            }

            if ($approval['medical']['waiting'] != 0) {
                foreach ($approval['medical']['data'] as $item) {
                    if ($item->status == 1) {
                        $text = "New request for medical";
                        $link = 'medical/' . $item->id;
                        $data[] = [
                            'notif' => 'Management Approval - Medical Request',
                            'time' => $item->updated_at,
                            'link' => $link,
                            'type' => 'medical_approval',
                            'id'   => $item->id,
                            'text' => $text,
                            'data' => $item,
                        ];
                    }
                }
            }

            if ($approval['exit']['waiting'] != 0) {
                foreach ($approval['exit']['data'] as $item) {
                    $text = "New request for exit clearance";
                    $link = 'exit-interview/' . $item->id;
                    $data[] = [
                        'notif' => 'Management Approval - Exit Clearance',
                        'time' => $item->updated_at,
                        'link' => $link,
                        'type' => 'exit_interview_approval',
                        'id'   => $item->id,
                        'text' => $text,
                        'data' => $item,
                    ];
                }
            }

            if ($approval['facilities']['waiting'] != 0) {
                foreach ($approval['facilities']['data'] as $item) {
                    $text = "New request for facilities";
                    $link = 'approval-facility/detail/' . $item->id;
                    $data[] = [
                        'notif' => 'Management Approval - Facilities',
                        'time' => $item->updated_at,
                        'link' => $link,
                        'type' => 'facilities_return',
                        'id'   => $item->id,
                        'text' => $text,
                        'data' => $item,
                    ];
                }
            }

            $user = \Auth::user();
            if ($user != null) {
                $news = News::where('news.status', 1)->orderBy('news.id', 'DESC')->get();
                if ($news != null) {
                    foreach ($news as $item) {
                        $data[] = [
                            'notif' => 'Home - News List',
                            'time' => $item->updated_at,
                            'link' => 'news/' . $item->id,
                            'type' => 'news',
                            'id'   => $item->id,
                            'text' => 'New news posted',
                            'data' => $item,
                        ];
                    }
                }

                $memo = InternalMemo::where('internal_memo.status', 1)->orderBy('internal_memo.id', 'DESC')->get();
                if ($memo != null) {
                    foreach ($memo as $item) {
                        $data[] = [
                            'notif' => 'Home - New List',
                            'time' => $item->updated_at,
                            'link' => 'memo/' . $item->id,
                            'type' => 'memo',
                            'id'   => $item->id,
                            'text' => 'New internal memo posted',
                            'data' => $item,
                        ];
                    }
                }

                $product = Product::where('product.status', 1)->orderBy('product.id', 'DESC')->get();
                if ($product != null) {
                    foreach ($product as $item) {
                        $data[] = [
                            'notif' => 'Home - New List',
                            'time' => $item->updated_at,
                            'link' => 'product/' . $item->id,
                            'type' => 'product',
                            'id'   => $item->id,
                            'text' => 'New product posted',
                            'data' => $item,
                        ];
                    }
                }

                $internal_vacancy = RecruitmentRequestDetail::join('recruitment_request as rr', 'recruitment_request_id', '=', 'rr.id')
                    ->leftJoin('cabang as c', 'rr.branch_id', '=', 'c.id')
                    ->where([
                        'recruitment_request_detail.status_post' => 1,
                        'recruitment_request_detail.recruitment_type_id' => 1,
                        'rr.approval_hr' => 1,
                        'rr.approval_user' => 1,
                        'rr.project_id' => Auth::user()->project_id,
                    ])
                    ->select(['recruitment_request_detail.*', 'rr.id as recruitment_id', 'rr.min_salary', 'rr.max_salary', 'c.name as branch', 'rr.job_desc', 'rr.job_requirement', 'rr.job_position'])
                    ->orderBy('recruitment_request_detail.posting_date', 'desc')->get();
                if ($internal_vacancy != null) {
                    foreach ($internal_vacancy as $item) {
                        $data[] = [
                            'notif' => 'Home - New List',
                            'time' => $item->updated_at,
                            'link' => 'recruitment/' . $item->id,
                            'type' => 'internal',
                            'id'   => $item->id,
                            'text' => 'New internal recruitment posted',
                            'data' => $item,
                        ];
                    }
                }
            }

            $data = collect($data)->sortByDesc('time');

            \Cache::put('notification', $data, 3600);
        }

        $totalData = count($data);
        $data = $data->slice(($currentPage * $perPage) - $perPage, $perPage);

        $data = [
            'current_page' => $currentPage,
            'total_page' => count($data),
            'total_data' => $totalData,
            'notifications' => array_values($data->all()),
        ];

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Successfully get notification!',
                'data' => $data,
            ], 200);
    }

}
