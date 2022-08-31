<?php

namespace App\Http\Controllers\Api;

use App\Http\Middleware\ConnectionMiddleware;
use App\Http\Resources\VacancyResource;
use App\Models\Cabang;
use App\Models\InternalApplication;
use App\Models\JobCategory;
use App\Models\RecruitmentApplicationHistory;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use App\Models\RecruitmentRequest;
use App\Models\RecruitmentRequestDetail;
use App\Models\RecruitmentApplication;
use App\Models\RecruitmentPhase;
use App\Models\ExternalApplication;
use App\Models\Jobseeker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;

class RecruitmentController extends Controller
{
    public function __construct()
    {
        $this->middleware(ConnectionMiddleware::class);
        $this->middleware('jwt.auth')->only(['getInternalVacancies','getApplications','applyInternalRecruitment']);
    }

    public function jobVacancyList(Request $request){

        $dataAll       = RecruitmentRequest::with(['branch', 'structure.position', 'structure.division'])
                        ->with(['branch', 'structure.position', 'structure.division'])
                        ->where(['approval_user'=> 1,'approval_hr'=>1])
                        ->join('recruitment_request_detail', 'recruitment_request.id', '=', 'recruitment_request_detail.recruitment_request_id')
                        ->where('recruitment_request_detail.recruitment_type_id', 2)
                        ->where('recruitment_request_detail.status_post', 1)
                        ->select(['*','recruitment_request.id as id']);
        if($request->limit)
            $dataAll->limit($request->limit);
        $dataAll = $dataAll->get();

        if($dataAll){
            $vacancies = [];
            foreach ($dataAll as $data){
                $position = ($data->structure->position)?$data->structure->position->name:'';
                $data->structure->division ? $position.=' - '.$data->structure->division->name:'';
                $data->structure->title ? $position.=' - '.$data->structure->title->name:'';
                
                $employment_name = '';
                switch ($data->employment_type) {
                    case '1':
                        $employment_name = 'Permanent';
                        break;
                    case '2':
                        $employment_name = 'Contract';
                        break;
                    case '3':
                        $employment_name = 'Internship';
                        break;
                    case '4':
                        $employment_name = 'Outsource';
                        break;
                    default:
                        $employment_name = 'Freelance';
                }

                $new = [
                    'id'                => $data->id,
                    'recruitment_name'  => $position,
                    'posting_date'      => date('d F Y',strtotime($data->posting_date)),
                    'last_posted_date'  => $data->last_posted_date ? date('d F Y',strtotime($data->last_posted_date)) : '',
                    'expired_date'      => $data->expired_date ? date('d F Y',strtotime($data->expired_date)) : '',
                    'branch'            => $data->branch->name,
                    'address'           => $data->branch->address,
                    'recruitment_type'  => $employment_name,
                    'show_salary'       => $data->show_salary_range,
                    'created_at'        => $data->created_at->format('Y-m-d H:i:s'),
                    'updated_at'        => $data->updated_at->format('Y-m-d H:i:s')
                ];
                if($new['show_salary'] == '1')
                {
                    $new['min_salary'] = $data->min_salary;
                    $new['max_salary'] = $data->max_salary;
                }
                array_push($vacancies,$new);
            }


            $res['status']  = 'success';
            $res['data']    = $vacancies;
        }
        else{
            $res['status']  = 'failed';
            $res['data']    = [];
        }


        return response($res);
    }

    public function jobVacancySearch(Request $request){
            $dataAll = RecruitmentRequest::with(['branch'])
            ->where(['approval_user'=> 1,'approval_hr'=>1])
            ->join('recruitment_request_detail', 'recruitment_request.id', '=', 'recruitment_request_detail.recruitment_request_id')
            ->where('recruitment_request_detail.recruitment_type_id', 2)
            ->where('recruitment_request_detail.status_post', 1)
            ->select(['recruitment_request.*','recruitment_request_detail.*','recruitment_request.id as id']);

        // Filtering

        if($request->q){
            $dataAll->where('recruitment_request.job_position', 'LIKE', "%$request->q%");
        }
        if($request->category){
            $dataAll->where('recruitment_request.job_category_id', $request->category);
        }
        if($request->location){
            $dataAll->where('recruitment_request.branch_id', $request->location);
        }
        if($request->type){
            $dataAll->where('recruitment_request.employment_type', $request->type);
        }

        // Ordering

        if($request->order_by == 'A-Z'){
            $dataAll->orderBy('recruitment_request.job_position', 'ASC');
        }
        else{
            $dataAll->orderBy("recruitment_request_detail.posting_date", "DESC");
        }

        $dataAll = $dataAll->paginate(5);
        if($dataAll){
            $dataAll->getCollection()->transform(function ($data) {

                $employment_name = '';
                switch ($data->employment_type) {
                    case '1':
                        $employment_name = 'Permanent';
                        break;
                    case '2':
                        $employment_name = 'Contract';
                        break;
                    case '3':
                        $employment_name = 'Internship';
                        break;
                    case '4':
                        $employment_name = 'Outsource';
                        break;
                    default:
                        $employment_name = 'Freelance';
                }

                $new = [
                    'id'                => $data->id,
                    'recruitment_name'  => $data->job_position,
                    'posting_date'      => date('d F Y',strtotime($data->posting_date)),
                    'last_posted_date'  => $data->last_posted_date ? date('d F Y',strtotime($data->last_posted_date)) : '',
                    'expired_date'      => $data->expired_date ? date('d F Y',strtotime($data->expired_date)) : '',
                    'branch'            => $data->branch->name,
                    'address'           => $data->branch->address,
                    'recruitment_type'  => $employment_name,
                    'show_salary'       => $data->show_salary_range,
                    'created_at'        => $data->created_at->format('Y-m-d H:i:s'),
                    'updated_at'        => $data->updated_at->format('Y-m-d H:i:s')
                ];
                if($new['show_salary'] == '1')
                {
                    $new['min_salary'] = $data->min_salary;
                    $new['max_salary'] = $data->max_salary;
                }
                return $new;
            });

            $res['status']  = 'success';
            $res['data']    = $dataAll;
        }
        else{
            $res['status']  = 'failed';
            $res['data']    = [];
        }


        return response($res);
    }

    public function getVacancyParams(){
        $data['category'] = JobCategory::all();
        $data['location'] = Cabang::select(['id','name'])->get();
        $data['type'] = [
            ['id' => 1,'name' => 'Permanent'],
            ['id' => 2,'name' => 'Contract'],
            ['id' => 3,'name' => 'Internship'],
            ['id' => 4,'name' => 'Outsource'],
            ['id' => 5,'name' => 'Freelance']
        ];
        $data['job_available'] = RecruitmentRequest::where(['approval_user'=>1, 'approval_hr'=>1])->whereHas('external',function($query){
            $query->where('status_post', 1);
        })->count();

        $res['status']  = 'success';
        $res['data']    = $data;

        return response($res);
    }

    public function sendEmail(Request $request){
        $validation = Validator::make($request->all(), [
            'name'          => 'required',
            'email'         => 'required|email',
            'send_to'       => 'required|email',
            'subject'       => 'required',
            'message'       => 'required',
        ]);

        if($validation->fails()){
            $res = [
                'status'    => 'failed',
                'message'   => $validation->errors()->first()
            ];
            return response($res);
        }

        $params = getEmailConfig();
        Config::set('database.default','mysql');
        $params['view']     = 'email.contact-career';

        $params['subject']  = 'Career Contact - '.$request->subject;
        $params['email']    = $request->send_to;
        $params['text']     = $request->message;
        $params['data']     = ['name'=>$request->name,'email'=>$request->email];
        $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
        dispatch($job);

        $res = [
            'status'    => 'success',
            'message'   => 'Email has been sent successfully'
        ];
        return response($res);
    }

    public function jobDetail($rec_req_id){
        $data       = RecruitmentRequest::where('recruitment_request.id', $rec_req_id)
                        ->where('approval_hr', 1)
                        ->with(['external','branch','category'])
                        ->where('approval_user', 1)
                        ->join('recruitment_request_detail', 'recruitment_request.id', '=', 'recruitment_request_detail.recruitment_request_id')
                        ->where('recruitment_request_detail.recruitment_type_id', 2)
                        ->where('recruitment_request_detail.status_post', 1)
                        ->select('recruitment_request.*')
                        ->first();
        
        if($data){
            $employment_name = '';
            switch ($data->employment_type) {
                case '1':
                    $employment_name = 'Permanent';
                    break;
                case '2':
                    $employment_name = 'Contract';
                    break;
                case '3':
                    $employment_name = 'Internship';
                    break;
                case '4':
                    $employment_name = 'Outsource';
                    break;
                default:
                    $employment_name = 'Freelance';
            }

            $data['position']          = $data->job_position;
            $data['recruitment_type']  = $employment_name;
            $data['posting_date']      = date('d F Y',strtotime($data->external->posting_date));
            $data['last_posted_date']  = $data->external->last_posted_date ? date('d F Y',strtotime($data->external->last_posted_date)) : '';
            $data['expired_date']      = $data->external->expired_date ? date('d F Y',strtotime($data->external->expired_date)) : '';
            if($data->job_desc)
                $data->job_desc        = htmlspecialchars_decode($data->job_desc);
            if($data->job_requirement)
                $data->job_requirement = htmlspecialchars_decode($data->job_requirement);
            if($data->benefit)
                $data->benefit         = htmlspecialchars_decode($data->benefit);

            $res['status']  = 'success';
            $res['message'] = 'data found';
            $res['data']    = $data;
        }
        else{
            $res['status']  = 'failed';
            $res['message'] = 'data not found';
            $res['data']    = null;
        }

//        return response(RecruitmentRequest::with('details')->get());
        return response($res);
    }

    public function register(Request $r){
        $validation = Validator::make($r->all(), [
            'name'          => 'required',
            'email'         => 'required|email',
            'password'      => 'required|min:8',
            'cv'            => 'required',
            'address'       => 'required',
            'phone_number'  => 'required'
        ]);

        if($validation->fails()){
            $res['status']  = 'failed';
            $res['message'] = 'error found';
            $res['data']    = $validation->errors();

            return response($res);
        }

        $checkEmail = Jobseeker::where('email', $r->email)->first();
        if($checkEmail){
            $res['status']  = 'failed';
            $res['message'] = 'email has been used';
            $res['data']    = [];

            return response($res);
        }
        else{
            $newUser                = new Jobseeker();
            $newUser->name          = $r->name;
            $newUser->email         = $r->email;
            $newUser->password      = Hash::make($r->password);
            if($r->file('cv')){
                $file                   = $r->file('cv');
                $fileName               = time() . $file->getClientOriginalName();
                $dest                   = 'jobseeker_cv';
                $file->move($dest, $fileName);
                $newUser->cv            = $fileName;
            }
            if($r->file('portofolio')){
                $filePortofolio                   = $r->file('portofolio');
                $fileNamePortofolio               = time() . $filePortofolio->getClientOriginalName();
                $destPortofolio                   = 'jobseeker_portofolio';
                $filePortofolio->move($destPortofolio, $fileNamePortofolio);
                $newUser->portofolio              = $fileNamePortofolio;
            }
            $newUser->address       = $r->address;
            $newUser->phone_number  = $r->phone_number;
            if($r->file('photo')){
                $filePhoto                   = $r->file('photo');
                $fileNamePhoto               = time() . $filePhoto->getClientOriginalName();
                $destPhoto                   = 'jobseeker_photo';
                $filePhoto->move($destPhoto, $fileNamePhoto);
                $newUser->photo              = $fileNamePhoto;
            }
            $newUser->save();

            $res['status']  = 'success';
            $res['message'] = 'data successfully saved';
            $res['data']    = $newUser;

            return response($res);
        }
    }

    public function apply(Request $r, $rec_req_id){
        $phase  = RecruitmentPhase::where('recruitment_type_id', 2)
                    ->where('order', 1)
                    ->first();

        $rec_application                           = new RecruitmentApplication();
        $rec_application->recruitment_request_id   = $rec_req_id;
        $rec_application->current_phase_id         = $phase->id;
        $rec_application->application_status       = 0;
        $rec_application->cover_letter             = $r->cover_letter;
        $rec_application->save();

        $ext_application                                = new ExternalApplication();
        $ext_application->recruitment_application_id    = $rec_application->id;
        $ext_application->jobseeker_id                  = $r->jobseeker_id;
        $ext_application->save();

        $res['status']  = 'success';
        $res['message'] = 'you successfully applied the job';
        $res['data']    = [];

        return response($res);
    }

    public function applyJob(Request $request){
        $validation = Validator::make($request->all(), [
            'recruitment_id'=> 'required|exists:recruitment_request_detail,recruitment_request_id',
            'name'          => 'required',
            'email'         => 'required|email',
            'phone_number'  => 'nullable',
            'portfolio'     => 'nullable',
            'cv'            => 'required|mimes:pdf',
            'cover_letter'  => 'nullable',
        ]);

        if($validation->fails()){
            $res = [
                'status'    => 'failed',
                'message'   => $validation->errors()->first()
            ];
            return response($res);
        }
        $jobseeker = Jobseeker::where(['email'=>$request->email])->first();
        if(!$jobseeker){
            $jobseeker           = new Jobseeker();
        }
        else{
            $cekApply = getExternalApplicationByUser($jobseeker->id,$request->recruitment_id);
            if($cekApply){
                return response()->json(['status' => 'failed', 'message' => 'You already have applied to this vacancy']);
            }
        }

        $jobseeker->name         = $request->name;
        $jobseeker->email        = $request->email;
        $jobseeker->phone_number = $request->phone_number;
        $jobseeker->portfolio    = $request->portfolio;
        $jobseeker->save();




        $firstPhase = RecruitmentPhase::where('recruitment_type_id',2)->orderBy('order','asc')->first();
        $application = new RecruitmentApplication();
        $application->recruitment_request_id    = $request->recruitment_id;
        $application->current_phase_id          = $firstPhase->id;
        $application->application_status        = 0;
        $application->cover_letter              = $request->cover_letter?$request->cover_letter:"";
        $application->save();

        $applicationHistory = new RecruitmentApplicationHistory();
        $applicationHistory->recruitment_application_id = $application->id;
        $applicationHistory->recruitment_phase_id       = $firstPhase->id;
        $applicationHistory->application_status         = 0;
        $applicationHistory->save();

        $cv = $request->cv;

        $name = $application->id.'.'.$cv->getClientOriginalExtension();
        $company_url = (($request->company)?$request->company:'umum').'/';
        $destinationPath = public_path('storage/file-cv/').$company_url;
        $cv->move($destinationPath, $name);

        $jobseeker->cv      = $company_url.$name;
        $jobseeker->save();

        $externalApplication = new ExternalApplication();
        $externalApplication->recruitment_application_id = $application->id;
        $externalApplication->jobseeker_id               = $jobseeker->id;
        $externalApplication->save();


        $message = "Dear Mr/Ms. $jobseeker->name, we have received your job application below : ";

        $recruitment = $application->recruitmentRequest;
        $position    = $recruitment->job_position;

        $params = getEmailConfig();
        Config::set('database.default','mysql');
        $params['view']     = 'email.recruitment-apply';
        $params['subject']  = 'Job Application';
        $params['email']    = $jobseeker->email;
        $params['text']     = $message;
        $params['data']     = ['position'=>$position,'created_at'=>date('d F Y',strtotime($application->created_at))];
        $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
        dispatch($job);

        return response()->json(['status' => 'success', 'message' => 'Your application has been submitted!']);
    }

    public function login(Request $r){
        $validation = Validator::make($r->all(), [
            'email'         => 'required|email',
            'password'      => 'required|min:8'
        ]);

        if($validation->fails()){
            $res['status']  = 'failed';
            $res['message'] = 'error found';
            $res['data']    = $validation->errors();

            return response($res);
        }

        $checkJobseeker = Jobseeker::where('email', $r->email)->first();
        if($checkJobseeker){
            if(Hash::check($r->password, $checkJobseeker->password)){
                $res['status']  = 'success';
                $res['message'] = 'you have been successfully logged in';
                $res['data']    = $checkJobseeker;

                return response($res);
            }
            else{
                $res['status']  = 'failed';
                $res['message'] = 'wrong password';
                $res['data']    = [];

                return response($res);
            }
        }
        else{
            $res['status']  = 'failed';
            $res['message'] = 'email not found';
            $res['data']    = [];

            return response($res);
        }
    }
    public function getInternalVacancies(){
        $user = Auth::user();
        $data['vacancies']    = RecruitmentRequestDetail::join('recruitment_request as rr','recruitment_request_id','=','rr.id')
            ->leftJoin('cabang as c', 'rr.branch_id','=','c.id')
            ->where([
                'recruitment_request_detail.status_post' => 1,
                'recruitment_request_detail.recruitment_type_id' => 1,
                'rr.approval_hr' => 1,
                'rr.approval_user' => 1,
                'rr.project_id' => $user->project_id
            ])
            ->select(['recruitment_request_detail.*','rr.id as recruitment_id', 'rr.min_salary', 'rr.max_salary','c.name as branch','rr.job_desc','rr.job_requirement','rr.job_position']);

        if(isset($_GET['keyword']) and !empty($_GET['keyword']))
        {
            $q = $_GET['keyword'];
            $data['vacancies'] = $data['vacancies']->where(function ($query) use ($q){
                $query->where('rr.job_desc', 'LIKE', "%$q%")
                    ->orWhere('rr.job_requirement', 'LIKE', "%$q%")
                    ->orWhere('rr.job_position', 'LIKE', "%$q%");
            });
        }
        $data = VacancyResource::collection($data['vacancies']->paginate(5));
        return response()->json(
            [
                'status' => 'success',
                'message' => 'Successfully',
                'data' => $data
            ], 200);
    }
    public function applyInternalRecruitment(Request $request){

        $validator = Validator::make(request()->all(), [
            'recruitment_id'  => 'required|exists:recruitment_request_detail,recruitment_request_id',
            'cv' => 'required|mimes:pdf'
        ]);
        $cekApply = getInternalApplicationByUser($request->recruitment_id);
        if($cekApply){
            return response()->json(['status' => 'failed', 'message' => 'You already have applied to this internal recruitment'], 403);
        }
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()], 401);
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
    public function getApplications(Request $request){
        $user = Auth::user();
        $status = $request->input('status', '[0,1,2,3,4]');
        if (!isJsonValid($status)) {
            return response()->json(['status' => 'error', 'message' => 'Status is invalid'], 404);
        }

        $status = json_decode($status);
        $histories = InternalApplication::with('application')->where('user_id', $user->id)->whereHas('application', function ($query) use ($status) {
            $query->whereIn('application_status', $status);
        })->orderBy('created_at', 'desc');
        $totalData = $histories->get()->count();
        $histories = $histories->paginate(10);
        $data = [
            'current_page' => $histories->currentPage(), // get current page number
            'total_page' => $histories->total() ? $histories->lastPage() : $histories->total(), // get last page number
            'total_data' => $totalData,
            'applications' => getInternalApplicationDetails($histories->items()),
        ];
        return response()->json(
            [
                'status' => 'success',
                'message' => 'Successfully',
                'data' => $data
            ], 200);
    }
    public function getApplicationDetail($id){
        return response()->json(
            [
                'status' => 'success',
                'message' => 'Successfully',
                'data' => getInternalApplicationDetail($id)
            ], 200);
    }
}
