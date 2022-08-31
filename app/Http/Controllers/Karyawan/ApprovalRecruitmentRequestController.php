<?php

namespace App\Http\Controllers\Karyawan;

use App\Models\HistoryApprovalRecruitment;
use App\Models\RecruitmentRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;

class ApprovalRecruitmentRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }

    public function index()
    {
        //
        $params['datas'] = cek_recruitment_approval();
//        return \GuzzleHttp\json_encode($params);
        return view('karyawan.approval-recruitment-request.index')->with($params);
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

    public function detail($id)
    {
        //
        $param['recruitment'] = RecruitmentRequest::find($id);
        if(!$param['recruitment'])
            return redirect()->route('karyawan.approval.recruitment-request.index')->with('message-error', 'You don\'t have permission to perform this action!');
        $param['history'] = HistoryApprovalRecruitment::where('recruitment_request_id', $param['recruitment']->id)->where('structure_organization_custom_id', \Auth::user()->structure_organization_custom_id)->first();
        if(!$param['history'])
            return redirect()->route('karyawan.approval.recruitment-request.index')->with('message-error', 'You don\'t have permission to perform this action!');
        return view('karyawan.approval-recruitment-request.detail')->with($param);
    }
    /**
     * [proses description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function proses(Request $request)
    {
        $data = RecruitmentRequest::where('id', $request->id)->first();
        $params['data']     = $data;
        $history =  HistoryApprovalRecruitment::where('recruitment_request_id',$request->id)->where('history_approval_recruitment.structure_organization_custom_id', \Auth::user()->structure_organization_custom_id)->first();
        $recruitmentHistory = $data->approvals;
        $strukturlast = $recruitmentHistory->last();

        $params = array_merge($params,getEmailConfig());
        $params['view']     = 'email.recruitment-request-approval';
        $params['subject']  = get_setting('mail_name').' - Recruitment Request';

        $notifTitle = "";
        $notifType = "";
        $userApprovalTokens = [];
        $userApprovalIds = [];

        if($request->status == 0)
        {
            $data->status = 3;
            $data->approval_user = $request->status;
            $history->approval_id    = \Auth::user()->id;
            $history->is_approved    = 0;
            $history->date_approved  = date('Y-m-d H:i:s');
            $history->save();

            $params['text']     = '<p><strong>Dear Sir/Madam '. $data->requestor->name .'</strong>,</p> <p>  Submission of your Recruitment Request <strong style="color: red;">REJECTED</strong>.</p>';
            $params['email']    = $data->requestor->email;
            $params['value']    = HistoryApprovalRecruitment::where('recruitment_request_id',$data->id)->get();

            Config::set('database.default','mysql');
            $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
            dispatch($job);

            $notifTitle = "Recruitment Request";
            $notifType = "recruitment";
            if ($data->requestor->firebase_token) {
                array_push($userApprovalTokens, $data->requestor->firebase_token);
            }
            array_push($userApprovalIds, $recruitment->requestor->id);
        } else {
            $history->approval_id    = \Auth::user()->id;
            $history->is_approved    = 1;
            $history->date_approved  = date('Y-m-d H:i:s');
            $history->save();

            if($strukturlast->structure_organization_custom_id == \Auth::user()->structure_organization_custom_id)
            {
                $data->status = 2;
                $data->approval_user = $request->status;
                $params['text']     = '<p><strong>Dear Sir/Madam '. $data->requestor->name .'</strong>,</p> <p>  Submission of your Recruitment Request <strong style="color: green;">APPROVED</strong>.</p>';
                $params['email']    = $data->requestor->email;
                $params['value']    = HistoryApprovalRecruitment::where('recruitment_request_id',$data->id)->get();

                Config::set('database.default','mysql');
                $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                dispatch($job);

                $notifTitle = "Recruitment Request";
                $notifType = "recruitment";
                if ($data->requestor->firebase_token) {
                    array_push($userApprovalTokens, $data->requestor->firebase_token);
                }
                array_push($userApprovalIds, $recruitment->requestor->id);
            } else{
                $data->status = 1;
                $userLevelNext = $history->setting_approval_level_id + 1;
                $userDataNext = HistoryApprovalRecruitment::where('recruitment_request_id',$history->recruitment_request_id)->where('setting_approval_level_id', $userLevelNext)->first();
                $userStructure = $userDataNext["structure_organization_custom_id"];
                $userApproval = user_approval_custom($userStructure);

                $params['value']    = HistoryApprovalRecruitment::where('recruitment_request_id',$data->id)->get();
                Config::set('database.default','mysql');
                foreach ($userApproval as $key => $items) {
                    if($items->email == "") continue;
                    $params['text']     = '<p><strong>Dear Sir/Madam '. $items->name .'</strong>,</p> <p> '. $data->requestor->name .'  / '.  $data->requestor->nik .' applied for Recruitment Request and currently waiting your approval.</p>';
                    $params['email']    = $items->email;

                    $job                = (new \App\Jobs\SendEmail($params))->onQueue('email');
                    dispatch($job);
                }

                $params['text'] = '<p> ' . $data->requestor->name . '  / ' . $data->requestor->nik . ' applied for Recruitment Request and currently waiting your approval.</p>';
                $notifTitle = "Recruitment Request Approval";
                $notifType = "recruitment_approval";
                $userApprovalIds = user_approval_id($nextApproval->structure_organization_custom_id);
                $userApprovalTokens = user_approval_firebase_tokens($nextApproval->structure_organization_custom_id);
            }

        }
        Config::set('database.default',session('db_name','mysql'));
        $data->save();

        foreach ($userApprovalIds as $value) {
            \FRDHelper::setNewData(strtolower(session('company_url', 'umum')), $value, $recruitment, $notifType);
        }

        if (count($userApprovalTokens) > 0) {
            $config = [
                'title' => $notifTitle,
                'content' => strip_tags($params['text']),
                'type' => $notifType,
                'firebase_token' => $userApprovalTokens,
            ];
            $notifData = [
                'id' => $data->id,
            ];
            $db = Config::get('database.default', 'mysql');
            Config::set('database.default', 'mysql');
            dispatch((new \App\Jobs\SendPushTokens($notifData, $config))->onQueue('push'));
            Config::set('database.default', $db);
        }

        return response()->json(['status'=>'success','message'=>'Form Recruitment Request Successfully Processed !']);
//        return redirect()->route('karyawan.approval.recruitment-request.index')->with('message-success', 'Form Recruitment Request Successfully Processed !');
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
}
