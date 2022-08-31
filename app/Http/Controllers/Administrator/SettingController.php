<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\ScheduleBackup;
use App\User;
use Illuminate\Support\Facades\Config;

use Storage;

class SettingController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }
    /**
     * [index description]
     * @return [type] [description]
     */
    public function index()
    {
        $params['data'] = Setting::orderBy('id', 'DESC')->get();

        return view('administrator.setting.index')->with($params);
    }

    /**
     * Setting Email
     * @return view
     */
    public function email()
    {
        return view('administrator.setting.email');
    }
    
    /**
     * Email Save
     * @param  Request $request
     * @return void
     */
    public function emailSave(Request $request)
    {
        $user = \Auth::user();
        if($request->setting)
        {
            foreach($request->setting as $key => $value)
            {
                if($user->project_id != NULL)
                {
                    $setting = Setting::where('key', $key)->where('project_id',$user->project_id)->first();
                }else{
                    $setting = Setting::where('key', $key)->first();
                }
                if(!$setting)
                {
                    $setting = new Setting();
                    $setting->key = $key;
                }
                $setting->project_id = $user->project_id;
                $setting->value = $value;
                $setting->save();
            }
        }

        return redirect()->route('administrator.setting.email')->with('message-success', 'Setting saved');
    }

    /**
     * Email Test Send
     * @param  Request $request
     */
    public function emailTestSend(Request $request)
    {
//        try {
            \Mail::send('email.blank', ['data' => $request->test_message],
                function ($message) use ($request) {
                    $message->to($request->to);
                    $message->subject($request->subject." (Direct E-mail)");
                }
            );

            $db = Config::get('database.default', 'mysql');
            Config::set('database.default', 'mysql');
            $params = getEmailConfig();
            $params['data'] = $request->test_message;
            $params['view'] = 'email.blank';
            $params['subject'] = $request->subject." (Queue E-mail)";
            $params['email'] = $request->to;
            $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
            dispatch($job);
            Config::set('database.default', $db);
//        }catch (\Swift_TransportException $e){
//            return redirect()->route('administrator.setting.email')->with('message-error', 'Email config is invalid!');
//        }
        
        return redirect()->route('administrator.setting.email')->with('message-success', 'Email berhasil dikirim');
    }

    /**
     * Setting Email
     * @return view
     */
    public function contractEmail()
    {
        return view('administrator.setting.contract-email');
    }
    
    /**
     * Email Save
     * @param  Request $request
     * @return void
     */
    public function contractEmailSave(Request $request)
    {
        $user = \Auth::user();
        if($request->setting)
        {
            foreach($request->setting as $key => $value)
            {
                if($user->project_id != NULL)
                {
                    $setting = Setting::where('key', $key)->where('project_id',$user->project_id)->first();
                }else{
                    $setting = Setting::where('key', $key)->first();
                }
                if(!$setting)
                {
                    $setting = new Setting();
                    $setting->key = $key;
                }
                $setting->project_id = $user->project_id;
                $setting->value = $value;
                $setting->save();
            }
        }

        return redirect()->route('administrator.setting.contract-email')->with('message-success', 'Setting saved');
    }

    /**
     * Email Test Send
     * @param  Request $request
     */
    public function contractEmailTestSend(Request $request)
    {
        $params = getEmailConfig();
        $params['view']     = 'email.end-contract';
        $params['subject']  = $params['mail_name'].' - (Test Mail) '.get_setting('contract_mail_subject');
        $params['email']    = $request->to;
        $params['body']     = str_replace('$name', \Auth::user()->name, str_replace('$date', \Carbon\Carbon::now()->startOfDay()->addDays(get_setting('contract_mail_before') ?: 0)->format('d F Y'), get_setting('contract_mail_body')));
        Config::set('database.default','mysql');
        $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
        dispatch($job);
        Config::set('database.default', session('db_name','mysql'));
        
        return redirect()->route('administrator.setting.contract-email')->with('message-success', 'Email berhasil dikirim');
    }

    public function userToBeAssigned($type)
    {
        $data = User::whereIn('access_id', [1, 2])
            ->leftJoin('structure_organization_custom', 'users.structure_organization_custom_id', '=', 'structure_organization_custom.id')
            ->leftJoin('organisasi_position', 'structure_organization_custom.organisasi_position_id', '=', 'organisasi_position.id')
            ->leftJoin('organisasi_division', 'structure_organization_custom.organisasi_division_id', '=', 'organisasi_division.id')
            ->select(
                'users.id',
                'users.contract_mail_cc_entitle as contract_mail_cc_entitle',
                'users.nik',
                'users.name',
                'organisasi_division.name as division',
                'organisasi_position.name as position'
            )->get();

        if (count($data) > 0) {
            return \Response::json([
                'message' => 'success',
                'data' => $data,
            ]);
        } else {
            return \Response::json([
                'message' => 'failed',
            ]);
        }
    }

    public function assignEntitle(Request $request)
    {
        if ($request->user_id) {
            User::whereIn('id', $request->user_id)->update([
                'contract_mail_cc_entitle' => $request->contract_mail_cc_entitle,
            ]);
        }

        if ($request->user_id_uncheck) {
            User::whereIn('id', $request->user_id_uncheck)->update([
                'contract_mail_cc_entitle' => $request->contract_mail_cc_entitle == 1 ? null : 1,
            ]);
        }

        return response()->json(['status' => 'success', 'message' => 'Assign entitlement success']);
    }

    /**
     * [create description]
     * @return [type] [description]
     */
    public function save(Request $request)
    {
        $user = \Auth::user();

        if($request->setting)
        {
            foreach($request->setting as $key => $value)
            {
                if($user->project_id != NULL)
                {
                    $setting = Setting::where('key', $key)->where('project_id',$user->project_id)->first();
                }else{
                    $setting = Setting::where('key', $key)->first();
                }
                if(!$setting)
                {
                    $setting = new Setting();
                    $setting->key = $key;
                }
                $setting->user_created = $user->id;
                $setting->project_id = $user->project_id;
                $setting->value = $value;
                $setting->save();
            }
        }
        
        if ($request->hasFile('logo'))
        {
            $file = $request->file('logo');
            $fileName = md5(rand() . $file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();

            $destinationPath = public_path('/upload/setting');
            $file->move($destinationPath, $fileName);

            if($user->project_id != NULL)
            {
                $setting = Setting::where('key', 'logo')->where('project_id',$user->project_id)->first();
            } else{
                $setting = Setting::where('key', 'logo')->first();
            }
            if(!$setting)
            {
                $setting = new Setting();
                $setting->key = 'logo';
            }
            $setting->user_created = $user->id;
            $setting->project_id = $user->project_id;
            $setting->value = '/upload/setting/' . $fileName;
            $setting->save();
        }

        if ($request->hasFile('favicon'))
        {
            $file = $request->file('favicon');
            $fileName = md5(rand() . $file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();

            $destinationPath = public_path('/upload/setting');
            $file->move($destinationPath, $fileName);

            if($user->project_id != NULL)
            {
                $setting = Setting::where('key', 'favicon')->where('project_id',$user->project_id)->first();
            } else{
                $setting = Setting::where('key', 'favicon')->first();
            }
            
            if(!$setting)
            {
                $setting = new Setting();
                $setting->key = 'favicon';
            }
            $setting->user_created = $user->id;
            $setting->project_id = $user->project_id;
            $setting->value = '/upload/setting/' . $fileName;
            $setting->save();
        }

        if ($request->hasFile('logo_footer'))
        {
            $file = $request->file('logo_footer');
            $fileName = md5(rand() . $file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();

            $destinationPath = public_path('/upload/setting');
            $file->move($destinationPath, $fileName);

            if($user->project_id != NULL)
            {
                $setting = Setting::where('key', 'logo_footer')->where('project_id',$user->project_id)->first();
            }else{
                $setting = Setting::where('key', 'logo_footer')->first();
            }
            
            if(!$setting)
            {
                $setting = new Setting();
                $setting->key = 'logo_footer';
            }
            $setting->user_created = $user->id;
            $setting->project_id = $user->project_id;
            $setting->value = '/upload/setting/' . $fileName;
            $setting->save();
        }
        
        return redirect()->route('administrator.setting.general')->with('message-success', 'Setting saved');
    }

    /**
     * Setting Backup
     * @return view
     */
    public function backup()
    {
        $params['data'] = Storage::allFiles(env('APP_NAME'));
        
        return view('administrator.setting.backup')->with($params);
    }

    /**
     * Backup Delete
     * @param  Request $request
     * @return void
     */
    public function backupDelete(Request $request)
    {
        $file = storage_path() .'/app/'. $request->file;
        #unlink($file);
        $result = Storage::delete( $request->file );

        return redirect()->route('administrator.setting.backup')->with('message-success','Files deleted.');
    }

    /**
     * Backup Delete
     * @param  Request $request
     * @return void
     */
    public function backupGet(Request $request)
    {
        return Storage::download( $request->file );
    }

    /**
     * Backup Save
     * @param  Request $request
     * @return redirect     
     */
    public function backupSave(Request $request)
    {
        if($request->setting)
        {
            foreach($request->setting as $key => $value)
            {
                $setting = Setting::where('key', $key)->first();
                if(!$setting)
                {
                    $setting = new Setting();
                    $setting->key = $key;
                }
                $setting->value = $value;
                $setting->save();
            }
        }

        return redirect()->route('administrator.setting.backup')->with('message-success', 'Setting saved');
    }

    public function storeBackupSchedule(Request $request)
    {
        $data               = new ScheduleBackup();
        $data->backup_type  = $request->backup_type;
        $data->time         = $request->time;
        $data->recurring    = $request->recurring;
        $data->date         = $request->date;
        $data->save();

        return redirect()->route('administrator.setting.backup')->with('message-success', 'Setting saved');
    }
    
     public function deleteBackupSchedule($id)
    {
        $data = ScheduleBackup::where('id', $id)->first();
        $data->delete();

        return redirect()->route('administrator.setting.backup')->with('message-success', 'Setting delete');
    }

    public function rollback(){
        $setting = [];
        $setting['web_font'] = 'unset';
        $setting['table_weight'] = '300';
        $setting['header_text_weight'] = '300';
        $setting['header_text_color'] = '#000000';
        $setting['table_color'] = '#000000';
        $setting['header_color'] = '#0E9A88';
        $setting['top_header_color'] = '#ACCE22';
        $setting['menu_color'] = '#0E9A88';
        $user = \Auth::user();
        if($setting)
        {
            foreach($setting as $key => $value)
            {
                if($user->project_id != NULL)
                {
                    $setting = Setting::where('key', $key)->where('project_id',$user->project_id)->first();
                }else{
                    $setting = Setting::where('key', $key)->first();
                }
                if(!$setting)
                {
                    $setting = new Setting();
                    $setting->key = $key;
                }
                $setting->user_created = $user->id;
                $setting->project_id = $user->project_id;
                $setting->value = $value;
                $setting->save();
            }
        }

        return redirect()->route('administrator.setting.general')->with('message-success', 'Setting back to default');
    }
    
}