<?php

namespace App\Http\Controllers\Administrator;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\AssetType;
use App\Models\AssetTracking;
use Illuminate\Support\Str;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;

class AssetSettingController extends Controller
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
        $this->middleware('module:14');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('administrator.asset-setting.setting-term-agreement');
    }
    
    public function store(Request $request){
        // dd($request);
        $user = \Auth::user();

        if ($request->setting) {
            foreach ($request->setting as $key => $value) {
                if ($user->project_id != null) {
                    $setting = Setting::where('key', $key)->where('project_id', $user->project_id)->first();
                } else {
                    $setting = Setting::where('key', $key)->first();
                }
                if (!$setting) {
                    $setting = new Setting();
                    $setting->key = $key;
                }
                $setting->user_created = $user->id;
                $setting->project_id = $user->project_id;
                $setting->value = $value;
                $setting->save();
            }
        }

        return \Redirect::route('administrator.asset-setting.index')->with('message-success', 'Data saved successfully');
    }
}
