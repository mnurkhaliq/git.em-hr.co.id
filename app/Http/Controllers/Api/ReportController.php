<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseApiController;
use App\Http\Middleware\ConnectionMiddleware;
use App\Models\CrmClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class ReportController extends BaseApiController
{
    //
    public function __construct()
    {
        $this->middleware(ConnectionMiddleware::class);
        $this->middleware('jwt.auth');
        parent::__construct();
    }

    public function report(Request $request)
    {
        $rules = [
//            'email' => 'sometimes|email',
            'type' => 'required|string',
            'description' => 'required',
            'platform' => 'required',
            'device_name' => 'required',
            'os_version' => 'required',
            'company' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = [];
            foreach ($rules as $key => $rule) {
                if ($validator->errors()->has($key)) {
                    array_push($errors, ['field' => $key, 'message' => $validator->errors()->first($key)]);
                }
            }
            return response()->json([
                'status' => 'error',
                'message' => 'Failed',
                'errors' => $errors,
            ], 401);
        } else {
            $user = Auth::user();
            $company = null;
            $project = getCompany($request->company);
            if ($project) {
                $company = CrmClient::find($project->partner_id);
            }

            $data = [
                'company' => ($company ? $company->name : ''),
                'user' => $user->nik . " - " . $user->name,
                'type' => $request->type,
                'optional_email' => $request->email,
                'phone_number' => $request->phone_number,
                'desc' => $request->description,
                'platform' => $request->platform,
                'app_version' => $request->app_version,
                'device_name' => $request->device_name,
                'os_version' => $request->os_version,
                'email' => ['support@empore.co.id', 'baso@empore.co.id', 'fajar@empore.co.id', 'hendra@empore.co.id'],
            ];

            \Mail::send('email.report', $data,
                function($message) use($request,$data) {
                    $message->to($data['email']);
                    $message->subject('Report Em-HR Mobile');
                    if($request->file('image')) {
                        $img = $request->file('image');
                        $message->attach($img->getRealPath(), array(
                            'as' => $img->getClientOriginalName(),
                            'mime' => $img->getMimeType())
                        );
                    }
                }
            );

            // $data = getEmailConfig();
            // $data = array_merge($data, [
            //     'company' => ($company ? $company->name : ''),
            //     'user' => $user->nik . " - " . $user->name,
            //     'type' => $request->type,
            //     'optional_email' => $request->email,
            //     'phone_number' => $request->phone_number,
            //     'desc' => $request->description,
            //     'platform' => $request->platform,
            //     'app_version' => $request->app_version,
            //     'device_name' => $request->device_name,
            //     'os_version' => $request->os_version,
            //     'email' => ['support@empore.co.id', 'baso@empore.co.id', 'fajar@empore.co.id', 'hendra@empore.co.id'],
            //     'subject' => 'Report Em-HR Mobile',
            //     'view' => 'email.report',
            //     'mail_username' => env('MAIL_USERNAME'),
            //     'mail_name' => 'Em-HR Mobile',
            // ]);

            // if ($request->file('image')) {
            //     $data['image_as'] = $request->file('image')->getClientOriginalName();
            //     $data['image_mime'] = $request->file('image')->getMimeType();
            //     $data['image'] = base64_encode(file_get_contents($request->file('image')));
            // }

            // $db = Config::get('database.default', 'mysql');
            // Config::set('database.default', 'mysql');
            // $job = (new \App\Jobs\SendEmail($data))->onQueue('email');
            // dispatch($job);
            // Config::set('database.default', $db);

            return response()->json([
                'status' => 'success',
                'message' => 'Thanks for submitting report/suggestion!',
                'request' => $request->all(),
                'data' => $data
            ], 200);
        }
    }
}
