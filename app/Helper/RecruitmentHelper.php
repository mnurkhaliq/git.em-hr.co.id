<?php

function getRecruitmentId(){
    $date = "RR".date("Y").date("m").date("d");
    $recruitment_code = \App\Models\RecruitmentRequest::where('request_number','like',$date.'%')->orderBy('request_number', 'desc')->first();
    if(!$recruitment_code){
        $id = $date."001";
    }
    else{
        $lastId = (int)substr($recruitment_code->request_number,-3);
        $currentId = $lastId+1;
        $id = $date.sprintf("%'03d", $currentId);
    }
    return $id;
}

function getRecruiters(){
    $karyawan = \App\User::whereIn('access_id', ['1', '2'])
        ->where('project_id', \Auth::user()->project_id)
        ->where(function($query) {
            $query->whereNull('non_active_date')->orWhere('non_active_date', '>', \Carbon\Carbon::now());
        })->where(function($query) {
            $query->whereNull('join_date')->orWhere('join_date', '<=', \Carbon\Carbon::now());
        })
        ->join('crm_module_admin','users.id','=','crm_module_admin.user_id')
        ->where('crm_module_admin.product_id','27')
        ->select('users.*')
        ->get();
    return $karyawan;
}
function getInterviewers(){
    $karyawan = \App\User::whereIn('access_id', ['1', '2'])
        ->where('project_id', \Auth::user()->project_id)
        ->where(function($query) {
            $query->whereNull('non_active_date')->orWhere('non_active_date', '>', \Carbon\Carbon::now());
        })->where(function($query) {
            $query->whereNull('join_date')->orWhere('join_date', '<=', \Carbon\Carbon::now());
        })
        ->select('users.*')
        ->get();
    return $karyawan;
}

function getApplicants($recruitment_id){
    $applications['all']      = \App\Models\RecruitmentApplication::where('recruitment_request_id',$recruitment_id)->count();
    $applications['internal'] = \App\Models\RecruitmentApplication::where('recruitment_request_id',$recruitment_id)->where('application_status','!=','4')->whereHas('internal')->count();
    $applications['external'] = \App\Models\RecruitmentApplication::where('recruitment_request_id',$recruitment_id)->where('application_status','!=','4')->whereHas('external')->count();
    return $applications;
}

function getInternalApplicationByUser($recruitment_id){
    $internalApplication = \App\Models\InternalApplication::join('recruitment_applications as ra','recruitment_application_id','=','ra.id')
                            ->where(['ra.recruitment_request_id' => $recruitment_id, 'internal_applications.user_id'=>\Auth::user()->id])
                            ->first();
    return $internalApplication;
}

function getExternalApplicationByUser($jobseeker_id,$recruitment_id){
    $externalApplication = \App\Models\ExternalApplication::join('recruitment_applications as ra','recruitment_application_id','=','ra.id')
        ->where(['ra.recruitment_request_id' => $recruitment_id, 'external_applications.jobseeker_id'=>$jobseeker_id])
        ->first();
    return $externalApplication;
}

function getJobCategories(){
    $user = \Auth::user();
    return \App\Models\JobCategory::where(['project_id'=>$user->project_id])->get();
}

function getWaitingHRCount(){
    $user = \Auth::user();
    return \App\Models\RecruitmentRequest::where(['project_id'=>$user->project_id,'approval_hr'=>null])->count();
}

function getInternalApplicationDetail($id){
    $data = [];
    $int_application = \App\Models\InternalApplication::where(['id'=>$id])->with('application')->first();
    if($int_application){
        $application         = $int_application->application;
        $recruitment         = $application->recruitmentRequest;
        $data['application'] = [
            'id'           => $int_application->id,
            'name'         => $int_application->applicant->name,
            'photo'        => !empty($int_application->applicant->foto)?asset('storage/foto/'. $int_application->applicant->foto):asset('admin-css/images/user.png'),
            'position'     => $recruitment->job_position,
            'branch'       => $recruitment->branch?$recruitment->branch->name:"",
            'date_request' => date('d F Y', strtotime($int_application->created_at)),
            'current_phase'=> $application->currentPhase->name,
            'last_edit'    => date('d F Y', strtotime($application->updated_at)),
            'status'       => $application->application_status,
            'status_name'  => $application->status->status
        ];

        $histories          = $application->histories;
        $data['histories']  = [];
        foreach ($histories as $history){
            $newHistory       = [
                'phase'       => $history->phase->name,
                'last_edit'   => date('d F Y', strtotime($history->updated_at)),
                'status'      => $history->application_status,
                'status_name' => $history->status->status
            ];
            $details = [];
            if($history->recruitment_phase_id == 1){ // Screening
                if($int_application->cv) {
                    array_push($details, [
                        'title' => 'Download CV',
                        'type' => 'url',
                        'data' => asset('storage/file-cv') . "/" . $int_application->cv
                    ]);
                }
                if($application->cover_letter) {
                    array_push($details, [
                        'title' => 'Show Cover Letter',
                        'type' => 'collapse',
                        'data' => $application->cover_letter
                    ]);
                }
            }
            if($history->recruitment_phase_id == 2){ // Technical Exam
                if($int_application->technical_test_schedule) {
                    array_push($details, [
                        'title' => 'Test Schedule',
                        'type' => 'text',
                        'data' => date('d F Y H:i', strtotime($int_application->technical_test_schedule)),
                    ]);
                }
                if($int_application->technical_test_result) {
                    array_push($details, [
                        'title' => 'Test Result',
                        'type' => 'text',
                        'data' => $int_application->technical_test_result
                    ]);
                }
                if($int_application->technical_test_remark) {
                    array_push($details, [
                        'title' => 'Remark',
                        'type' => 'text',
                        'data' => $int_application->technical_test_remark
                    ]);
                }
            }
            if($history->recruitment_phase_id == 3){ // Interview HR & User
                if($int_application->interview_test_schedule) {
                    array_push($details, [
                        'title' => 'Interview Schedule',
                        'type' => 'text',
                        'data' => date('d F Y H:i', strtotime($int_application->interview_test_schedule)),
                    ]);
                }
                if($int_application->interview_test_location) {
                    array_push($details, [
                        'title' => 'Interview Location',
                        'type' => 'text',
                        'data' => $int_application->interview_test_location
                    ]);
                }
                if($int_application->interview_test_result) {
                    array_push($details, [
                        'title' => 'Interview Result',
                        'type' => 'text',
                        'data' => $int_application->interview_test_result
                    ]);
                }
                if($int_application->interview_test_remark) {
                    array_push($details, [
                        'title' => 'Remark',
                        'type' => 'text',
                        'data' => $int_application->interview_test_remark
                    ]);
                }

            }

            if($history->recruitment_phase_id == 4){ // Transfer / Promotion
                if($int_application->memo_number) {
                    array_push($details, [
                        'title' => 'Memo Number',
                        'type' => 'text',
                        'data' => $int_application->memo_number
                    ]);
                }
                if($int_application->memo_date) {
                    array_push($details, [
                        'title' => 'Memo Date',
                        'type' => 'text',
                        'data' => date('d F Y', strtotime($int_application->memo_date))
                    ]);
                }
                if($int_application->onboard_date) {
                    array_push($details, [
                        'title' => 'Onboard Date',
                        'type' => 'text',
                        'data' => date('d F Y', strtotime($int_application->onboard_date))
                    ]);
                }
            }

            $newHistory['details'] = $details;

            array_push($data['histories'],$newHistory);
        }
    }

    return $data;
}

function getInternalApplicationDetails($datas){
    $results = [];
    foreach ($datas as $key => $value) {
        $data = [];
        $int_application = $value;
        if($int_application){
            $application         = $int_application->application;
            $recruitment         = $application->recruitmentRequest;
            $data['application'] = [
                'id'           => $int_application->id,
                'name'         => $int_application->applicant->name,
                'photo'        => !empty($int_application->applicant->foto)?asset('storage/foto/'. $int_application->applicant->foto):asset('admin-css/images/user.png'),
                'position'     => $recruitment->job_position,
                'branch'       => $recruitment->branch?$recruitment->branch->name:"",
                'date_request' => date('d F Y', strtotime($int_application->created_at)),
                'current_phase'=> $application->currentPhase->name,
                'last_edit'    => date('d F Y', strtotime($application->updated_at)),
                'status'       => $application->application_status,
                'status_name'  => $application->status->status
            ];

            $histories          = $application->histories;
            $data['histories']  = [];
            foreach ($histories as $history){
                $newHistory       = [
                    'phase'       => $history->phase->name,
                    'last_edit'   => date('d F Y', strtotime($history->updated_at)),
                    'status'      => $history->application_status,
                    'status_name' => $history->status->status
                ];
                $details = [];
                if($history->recruitment_phase_id == 1){ // Screening
                    if($int_application->cv) {
                        array_push($details, [
                            'title' => 'Download CV',
                            'type' => 'url',
                            'data' => asset('storage/file-cv') . "/" . $int_application->cv
                        ]);
                    }
                    if($application->cover_letter) {
                        array_push($details, [
                            'title' => 'Show Cover Letter',
                            'type' => 'collapse',
                            'data' => $application->cover_letter
                        ]);
                    }
                }
                if($history->recruitment_phase_id == 2){ // Technical Exam
                    if($int_application->technical_test_schedule) {
                        array_push($details, [
                            'title' => 'Test Schedule',
                            'type' => 'text',
                            'data' => date('d F Y H:i', strtotime($int_application->technical_test_schedule)),
                        ]);
                    }
                    if($int_application->technical_test_result) {
                        array_push($details, [
                            'title' => 'Test Result',
                            'type' => 'text',
                            'data' => $int_application->technical_test_result
                        ]);
                    }
                    if($int_application->technical_test_remark) {
                        array_push($details, [
                            'title' => 'Remark',
                            'type' => 'text',
                            'data' => $int_application->technical_test_remark
                        ]);
                    }
                }
                if($history->recruitment_phase_id == 3){ // Interview HR & User
                    if($int_application->interview_test_schedule) {
                        array_push($details, [
                            'title' => 'Interview Schedule',
                            'type' => 'text',
                            'data' => date('d F Y H:i', strtotime($int_application->interview_test_schedule)),
                        ]);
                    }
                    if($int_application->interview_test_location) {
                        array_push($details, [
                            'title' => 'Interview Location',
                            'type' => 'text',
                            'data' => $int_application->interview_test_location
                        ]);
                    }
                    if($int_application->interview_test_result) {
                        array_push($details, [
                            'title' => 'Interview Result',
                            'type' => 'text',
                            'data' => $int_application->interview_test_result
                        ]);
                    }
                    if($int_application->interview_test_remark) {
                        array_push($details, [
                            'title' => 'Remark',
                            'type' => 'text',
                            'data' => $int_application->interview_test_remark
                        ]);
                    }

                }

                if($history->recruitment_phase_id == 4){ // Transfer / Promotion
                    if($int_application->memo_number) {
                        array_push($details, [
                            'title' => 'Memo Number',
                            'type' => 'text',
                            'data' => $int_application->memo_number
                        ]);
                    }
                    if($int_application->memo_date) {
                        array_push($details, [
                            'title' => 'Memo Date',
                            'type' => 'text',
                            'data' => date('d F Y', strtotime($int_application->memo_date))
                        ]);
                    }
                    if($int_application->onboard_date) {
                        array_push($details, [
                            'title' => 'Onboard Date',
                            'type' => 'text',
                            'data' => date('d F Y', strtotime($int_application->onboard_date))
                        ]);
                    }
                }

                $newHistory['details'] = $details;

                array_push($data['histories'],$newHistory);
            }
        }
    
        $results[] = $data;
    }

    return $results;
}