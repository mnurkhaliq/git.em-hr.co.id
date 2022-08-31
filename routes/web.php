<?php /*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


#Set Language
use App\Models\ConfigDB;
use App\Models\CutiKaryawan;
use Illuminate\Support\Facades\Config;
use App\Models\CrmProduct;
use App\Models\CrmSales;
use App\Models\CrmBlog;
use App\Models\CrmPromo;
use App\Models\CrmFAQ; 
use App\Models\CrmPrivacy; 
use App\Http\Controllers\ShareSocialController;
use App\Models\CrmBlogComment;

//$language = empty(get_setting('language')) ? 'en' : get_setting('language') ;
//
//App::setLocale($language);
//#End
//
//#Set Language
//$timezone = empty(get_setting('timezone')) ? "Asia/Bangkok" : get_setting('timezone') ;
//
//date_default_timezone_set($timezone);
#End

//Route::get('/', function ()
//{
//	return redirect()->route('landing-page1');
//});
// Route::get('blog/detail/{id}',  'LandingPageController@detail')->name('detail');

Route::get('google', 'GoogleController@redirect');
Route::get('google/callback', 'GoogleController@callback');

Route::get('feature', 'LandingPageController@feature', function($locale){
    App::setLocale($locale);
    $data = CrmProduct::where('is_published', true)->orderBy('name', 'ASC')->get();
    $data_blog = CrmBlog::where('is_publish', true)->where('is_publish_landing', true)->orderBy('create_date', 'DESC')->get();
    $data_blog_all = CrmBlog::where('is_publish', true)->orderBy('create_date', 'DESC')->get();
  
    $params['data'] = $data;
    $params['data_blog'] = $data_blog;
    $params['data_blog_all'] = $data_blog_all;
    return view('landing-page/feature')->with($params);
})->name('feature');
Route::get('{locale}/feature',function($locale){
    App::setLocale($locale);
    $data = CrmProduct::where('is_published', true)->orderBy('name', 'ASC')->get();
    $data_blog = CrmBlog::where('is_publish', true)->where('is_publish_landing', true)->orderBy('create_date', 'DESC')->get();
    $data_blog_all = CrmBlog::where('is_publish', true)->orderBy('create_date', 'DESC')->get();
    
    $params['lang'] = $locale;
    $params['data'] = $data;
    $params['data_blog'] = $data_blog;
    $params['data_blog_all'] = $data_blog_all;

    return view('landing-page/feature')->with($params);
});
Route::get('promo/{title}/{id}', 'LandingPageController@promo')->name('promo');
Route::get('{locale}/promo/{title}/{id}', function($locale, $title, $id){
    App::setLocale($locale);
    $params['promo'] = CrmPromo::where('id', $id)->first();
    return view('landing-page/promo_detail')->with($params);
})->name('promo');

Route::get('promo_list', 'LandingPageController@promo_list')->name('promo_list');
Route::get('{locale}/promo_list', function($locale){
    App::setLocale($locale);

    $data_promo = CrmPromo::where(function($query) {
        $query->whereNull('publish_end_date')->orWhere('publish_end_date', '>=', \Carbon\Carbon::now()->format('Y-m-d'));
    })->where(function($query) {
        $query->whereNull('publish_start_date')->orWhere('publish_start_date', '<=', \Carbon\Carbon::now()->format('Y-m-d'));
    })->orderBy('publish_start_date', 'ASC')->get();
    // dd($data_promo);
    $params['promos'] = $data_promo;
    return view('landing-page/promo_all')->with($params);
})->name('promo_list');


Route::get('article/{category}/{title}/{id}/{id_feature}', 'LandingPageController@detail')->name('detail');
Route::get('{locale}/article/{category}/{title}/{id}/{id_feature}', function($locale, $category, $title, $id, $id_article){
    App::setLocale($locale);
    $currentURL = url()->current();
    Session::put('redirectUrl', $currentURL);

    $params['data_feature']             = CrmProduct::where('id', $id_article)->first();
    $params['data']             = CrmBlog::where('id', $id)->first();
    $params['data_comments'] = CrmBlogComment::where('blog_id', $id)->where('is_publish', true)->orderBy('create_date', 'DESC')->get();
    $params['comment_count'] = $params['data_comments']->count();
    // $params['data_comments_reply'] = CrmBlogComment::where('parent_id', $id)->get();
    $datas = CrmBlog::where('is_publish', true)->where('id', '!=', $id)->where('category_id', $id_article)->orderBy('create_date', 'DESC')->get();
    $params['datas'] = $datas;
    // $params['news_list_right']  = CrmBlog::where('status', 1)->orderBy('id', 'DESC')->limit(10)->get();
    $params['section']          = 'news';
    $params['title']            = 'News';

    return view('landing-page/detail')->with($params);
})->name('detail');
Route::get('feature/{category}/{id_feature}', 'LandingPageController@feature_detail')->name('feature_detail');
Route::get('{locale}/feature/{category}/{id_feature}', function($locale, $category, $id_feature){

    App::setLocale($locale);
    
    $params['data']             = CrmProduct::where('id', $id_feature)->first();
        
    $datas = CrmBlog::where('is_publish', true)->where('category_id', $id_feature)->orderBy('create_date', 'DESC')->get();
    $params['datas'] = $datas;
    $params['section']          = 'news';
    $params['title']            = 'News';


    return view('landing-page/feature_detail')->with($params);
})->name('feature_detail');

Route::get('contact', 'LandingPageController@contact', function($locale){
    App::setLocale($locale);
    $data = CrmSales::where('is_publish_landingpage', true)->get();
    // dd($data);
    $params['data'] = $data;
    return view('landing-page/contact')->with($params);
});
Route::get('{locale}/contact',function($locale){
    App::setLocale($locale);
    $data = CrmSales::where('is_publish_landingpage', true)->get();
    // dd($data);
    $params['data'] = $data;
    return view('landing-page/contact')->with($params);
});
Route::get('how_to_subscribe', 'LandingPageController@subscribe', function($locale){
    App::setLocale($locale);
    return view('landing-page/how_to_subscribe');
});
Route::get('{locale}/how_to_subscribe',function($locale){
    App::setLocale($locale);
    return view('landing-page/how_to_subscribe');
});
Route::get('help_center', 'LandingPageController@helpcenter', function($locale){
    App::setLocale($locale);
    $data = CrmFAQ::orderBy('create_date', 'ASC')->get();
    $params['data'] = $data;
    return view('landing-page/help_center')->with($params);
});
Route::get('{locale}/help_center',function($locale){
    App::setLocale($locale);
    $data = CrmFAQ::orderBy('create_date', 'ASC')->get();
    $params['data'] = $data;
    return view('landing-page/help_center')->with($params);
});

Route::get('article/{title}/{id}')->name('detail_share');

// Route::get('reset-password/{id}/{company}', 'ResetPasswordController@reset')->name('reset-password.reset');

// Route::get('{locale}/blog/detail/{id}', function($locale){
//     App::setLocale($locale);
// })->name('detail');

Route::post('blog/comment/store', 'CommentController@store')->name('comment.add');
Route::post('blog/comment/saveUser', 'CommentController@saveUser')->name('comment.saveUser');
Route::post('blog/comment/loginUser', 'CommentController@loginUser')->name('comment.loginUser');
Route::post('blog/comment/reply', 'CommentController@reply')->name('reply.add');


// Route::get('{locale}/blog/detail/{id}', function($locale){
//     App::setLocale($locale);
//     $params['section']          = 'news';
//     $params['title']            = 'News';

//     return view('landing-page/detail')->with($params);
// })->name('detail');
Route::get('/', 'LandingPageController@page1', function($locale){
    App::setLocale($locale);
    $data = CrmBlog::where('is_publish', true)->where('is_publish_landing', true)->orderBy('create_date', 'DESC')->get();
   
    $data_promo = CrmPromo::where(function($query) {
        $query->whereNull('publish_end_date')->orWhere('publish_end_date', '>=', \Carbon\Carbon::now()->format('Y-m-d'));
    })->where(function($query) {
        $query->whereNull('publish_start_date')->orWhere('publish_start_date', '<=', \Carbon\Carbon::now()->format('Y-m-d'));
    })->orderBy('publish_start_date', 'ASC')->get();
    $params['data_promo'] = $data_promo;
    $params['data'] = $data;
    return view('landing-page/home')->with($params);
});
Route::get('/id', function(){
    App::setLocale("id");
    $data = CrmBlog::where('is_publish', true)->where('is_publish_landing', true)->orderBy('create_date', 'DESC')->get();
  
    $data_promo = CrmPromo::where(function($query) {
        $query->whereNull('publish_end_date')->orWhere('publish_end_date', '>=', \Carbon\Carbon::now()->format('Y-m-d'));
    })->where(function($query) {
        $query->whereNull('publish_start_date')->orWhere('publish_start_date', '<=', \Carbon\Carbon::now()->format('Y-m-d'));
    })->orderBy('publish_start_date', 'ASC')->get();
    $params['data_promo'] = $data_promo;
    $params['data'] = $data;
    return view('landing-page/home')->with($params);
});
Route::get('/en', function(){
    App::setLocale("en");
    $data = CrmBlog::where('is_publish', true)->where('is_publish_landing', true)->orderBy('create_date', 'DESC')->get();
  
    $data_promo = CrmPromo::where(function($query) {
        $query->whereNull('publish_end_date')->orWhere('publish_end_date', '>=', \Carbon\Carbon::now()->format('Y-m-d'));
    })->where(function($query) {
        $query->whereNull('publish_start_date')->orWhere('publish_start_date', '<=', \Carbon\Carbon::now()->format('Y-m-d'));
    })->orderBy('publish_start_date', 'ASC')->get();
    $params['data_promo'] = $data_promo;
    $params['data'] = $data;
    return view('landing-page/home')->with($params);
});


// Route::get('{locale}/', function($locale){
//     if ($locale == "feature"){
//         App::setLocale($locale);
//         $data = CrmProduct::where('is_published', true)->orderBy('name', 'ASC')->get();
//         // dd($data)
//         $params['data'] = $data;
//         return view('landing-page/feature')->with($params);
//     }else{
//         App::setLocale($locale);
//         return view('landing-page/home');
//     }
    
// });
Route::get('/privacy-policy', function ()
{
    return view('privacy-policy/emhr-mobile-attendance');
})->name('privacy-policy');

Route::get('/mobile/privacy-policy', function ()
{
    $privacy_id = CrmPrivacy::where('name', 'PRIVACY_POLICY_ID')->first();
    $privacy_en = CrmPrivacy::where('name', 'PRIVACY_POLICY_EN')->first();
    $params['privacy_id'] = $privacy_id;
    $params['privacy_en'] = $privacy_en;
    return view('privacy-policy/emhr-mobile')->with($params);
})->name('privacy-policy-mobile');

Route::get('/mobile/privacy-policy-id', function ()
{
    $privacy_id = CrmPrivacy::where('name', 'PRIVACY_POLICY_ID')->first();
    $privacy_en = CrmPrivacy::where('name', 'PRIVACY_POLICY_EN')->first();
    $params['privacy_id'] = $privacy_id;
    $params['privacy_en'] = $privacy_en;
    return view('privacy-policy/emhr-mobile_id')->with($params);
})->name('privacy-policy-mobile-id');

Route::get('tunnel/{path?}', 'AjaxController@fileTunnel')->where('path', '(.*)');

Auth::routes();

Route::get('asset-accept/{id}/{company}', 'IndexController@acceptAsset')->name('accept-asset');
Route::get('reject-accept/{id}/{company}', 'IndexController@rejectAsset')->name('reject-asset');
Route::get('/reset-password/{company}', 'ResetPasswordController@index')->name('reset-password');
Route::post('/reset-password', 'ResetPasswordController@resetPassword')->name('reset-password-store');
Route::post('/request-reset', 'ResetPasswordController@requestReset')->name('request-reset');
Route::get('reset-password/{id}/{company}', 'ResetPasswordController@reset')->name('reset-password.reset');
Route::get('em-hris-application-system', 'LandingPageController@page1')->name('landing-page1');
Route::post('check-code', 'LandingPageController@checkCode')->name('check-code');
Route::post('post-em-hris-application-system', 'LandingPageController@storePage1')->name('post-landing-page1');
Route::post('post-login-client', 'LandingPageController@loginClient')->name('post-login-client');
Route::get('send-email-end-contract', 'ExternalController@SendEmailEndContract')->name('send-email-end-contract');
Route::get('test-odoo', function () {
    foreach (ConfigDB::whereNotNull('db_name')->where('due_date', \Carbon\Carbon::now()->startOfDay()->addDays(6))->get() as $item) {
        $endpoint = env('URL_CRM').'project/mail/'.$item->id;
        $client = new \GuzzleHttp\Client([
            'verify' => false
        ]);
        $response = $client->request('GET', $endpoint, [
            'headers' => ['Authorization' => env('CRM_API_KEY')]
        ]);

        info("Send email license reminder db ".$item->db_name." with status code ".$response->getStatusCode());
    }
});
Route::get('list-debug', function () {
    foreach (ConfigDB::whereNotNull('db_name')->get() as $value) {
        if ($value->due_date < \Carbon\Carbon::now()->startOfDay()) {
            $list[$value->company_code] = 'Expired';
        } else {
            Config::set('database.default', $value->db_name);
            DB::purge($value->db_name);
            try {
                DB::connection()->getPdo();
                $list[$value->company_code] = \App\Models\Setting::where(['key' => 'app_debug', 'project_id' => $value->id])->first()->value == 'true' ? 'Development' : 'Production';
            } catch (\Exception $e) {
                $list[$value->company_code] = 'Not Configured';
            }
        }
    }
    Config::set('database.default', session('db_name', 'mysql'));
    DB::purge(session('db_name', 'mysql'));
    return view('debug')->with('list', $list);
});
Route::get('reset-database-config', function () {
    $content = file_get_contents(__DIR__ . '/../config/database_template.php');
    $search = "// Client DB";
    $replace = "";
    foreach (ConfigDB::whereNotNull('db_name')->get() as $value) {
        Config::set('database.connections.mysql.database', $value->db_name);
        DB::purge('mysql');
        try {
            DB::connection()->getPdo();
            $replace .= "'".$value->db_name."' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST'),
            'port' => env('DB_PORT'),
            'database' => '".$value->db_name."',
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'unix_socket' => env('DB_SOCKET'),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => false,
            'engine' => null,
            'options'   => [
                \PDO::ATTR_EMULATE_PREPARES => true
            ]
        ],
        ";
        } catch (\Exception $e) {}
    }
    Config::set('database.connections.mysql.database', session('db_name', env('DB_DATABASE')));
    DB::purge('mysql');
    $content = str_replace($search, $replace, $content);
    file_put_contents(__DIR__ . '/../config/database.php', $content);
    return redirect('/list-debug');
});
Route::get('/generate-new-database/{database}', function ($database) {
    $command_params['--database'] = $database;
    Artisan::call('new:database', $command_params);
    Artisan::call('new:config', $command_params);
    return redirect('/generate-new-database-continue/'.$database);
});
Route::get('/generate-new-database-continue/{database}', function ($database) {
    $command_params['--database'] = $database;
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('migrate', $command_params);
    Artisan::call('db:seed', $command_params);
    Artisan::call('supervisor:restart');
    return response()->json([
        'status' => 'success',
        'message' => 'New client database '.$database.' created'
    ], 200);
});
Route::get('/delete-database/{database}', function ($database) {
    $command_params['--database'] = $database;
    Artisan::call('database:backup', $command_params);

    $content = file_get_contents(__DIR__ . '/../config/database_template.php');
    $search = "// Client DB";
    $replace = "";
    foreach (ConfigDB::whereNotNull('db_name')->get() as $value) {
        Config::set('database.connections.mysql.database', $value->db_name);
        DB::purge('mysql');
        try {
            DB::connection()->getPdo();
            $replace .= "'".$value->db_name."' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST'),
            'port' => env('DB_PORT'),
            'database' => '".$value->db_name."',
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'unix_socket' => env('DB_SOCKET'),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => false,
            'engine' => null,
            'options'   => [
                \PDO::ATTR_EMULATE_PREPARES => true
            ]
        ],
        ";
        } catch (\Exception $e) {}
    }
    Config::set('database.connections.mysql.database', session('db_name', env('DB_DATABASE')));
    DB::purge('mysql');
    $content = str_replace($search, $replace, $content);
    file_put_contents(__DIR__ . '/../config/database.php', $content);

    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('supervisor:restart');

    return response()->json([
        'status' => 'success',
        'message' => 'Backup & delete database '.$database.' success'
    ], 200);
});
Route::get('change-debug/{company}/{mode}', function ($company, $mode){
    $mode = trim(strtolower($mode));
    $modes = ["production","prod","development","dev"];
    if(!in_array($mode,$modes)){
        return response()->json([
            'status' => 'error',
            'message' => 'Mode is invalid'
        ],404);
    }
    $mode = ($mode == 'development' || $mode == 'dev')?'true':'false';
    $mode_name = ($mode=='true')?"Development":"Production";
    $config = ConfigDB::where('company_code',strtolower($company))->where('due_date', '>=', \Carbon\Carbon::now()->startOfDay())->first();
    if(!$config){
        return response()->json([
            'status' => 'error',
            'message' => 'Company code is invalid'
        ],404);
    }
    else{
        Config::set('database.default',$config->db_name);
        $setting = \App\Models\Setting::where(['key'=>'app_debug','project_id'=>$config->id])->first();
        if(!$setting){
            $setting = new \App\Models\Setting();
            $setting->key        = 'app_debug';
            $setting->project_id = $config->id;
        }
        $setting->value = $mode;
        $setting->save();
        Config::set('database.default',session('db_name','mysql'));
        return response()->json([
            'status' => 'success',
            'message' => $company."'s debug mode is set to $mode_name"
        ],200);
    }

});


Route::post('get-price-list', 'LandingPageController@getPriceList')->name('get-price-list');

Route::group(['middleware' => ['auth']], function(){
//	Route::post('logout', 'LoginController@Logout')->name('logout');
    Route::get('/test-attendance/{shift}', function ($shift) {
        $config = [
            'title' => 'Test Attendance Reminder',
            'content' => 'Its about time to test attendance reminder',
            'app_type' => config('constants.apps.emhr_mobile_attendance'),
            'firebase_token' => App\User::whereHas('shift', function ($user) use ($shift) {
                $user->where('name', $shift);
            })->whereNotNull('firebase_token')->where('os_type', 'android')->pluck('firebase_token')->toArray()
        ];

        if (count($config['firebase_token'])) {
            \FCMHelper::sendAttendance($config);
            $config['app_type'] = config('constants.apps.emhr_mobile');
            \FCMHelper::sendAttendance($config);
        }
        
        $config['firebase_token'] = App\User::whereHas('shift', function ($user) use ($shift) {
            $user->where('name', $shift);
        })->whereNotNull('firebase_token')->where('os_type', '!=', 'android')->pluck('firebase_token')->toArray();
        
        if (count($config['firebase_token'])) {
            \FCMHelper::sendAttendanceIos($config);
            $config['app_type'] = config('constants.apps.emhr_mobile_attendance');
            \FCMHelper::sendAttendanceIos($config);
        }
    })->name('test-attendance');
	Route::post('ajax/get-division-by-directorate', 'AjaxController@getDivisionByDirectorate')->name('ajax.get-division-by-directorate');
	Route::post('ajax/get-department-by-division', 'AjaxController@getDepartmentByDivision')->name('ajax.get-department-by-division');
	Route::post('ajax/get-section-by-department', 'AjaxController@getSectionByDepartment')->name('ajax.get-section-by-department');
	Route::get('ajax/get-structure', 'AjaxController@getStructure')->name('ajax.get-stucture');
	Route::get('ajax/get-structure-custome', 'AjaxController@getStructureCustome')->name('ajax.get-stucture-custome');
	Route::get('ajax/get-structure-branch', 'AjaxController@getStructureBranch')->name('ajax.get-stucture-branch');
	Route::post('ajax/get-kabupaten-by-provinsi', 'AjaxController@getKabupatenByProvinsi')->name('ajax.get-kabupaten-by-provinsi');
	Route::post('ajax/get-kecamatan-by-kabupaten', 'AjaxController@getKecamatanByKabupaten')->name('ajax.get-kecamatan-by-kabupaten');
	Route::post('ajax/get-kelurahan-by-kecamatan', 'AjaxController@getKelurahanByKecamatan')->name('ajax.get-kelurahan-by-kecamatan');
	Route::get('ajax/get-shift-schedule', 'AjaxController@getShiftSchedule')->name('ajax.get-shift-schedule');
	Route::post('ajax/get-karyawan-by-id', 'AjaxController@getKaryawanById')->name('ajax.get-karyawan-by-id');
    Route::get('ajax/get-karyawan-calendar', 'AjaxController@getKaryawanCalendar')->name('ajax.get-karyawan-calendar');
    Route::get('ajax/get-leave-calendar', 'AjaxController@getLeaveCalendar')->name('ajax.get-leave-calendar');
    Route::get('ajax/get-collective-calendar', 'AjaxController@getCollectiveCalendar')->name('ajax.get-collective-calendar');
	Route::post('ajax/add-setting-cuti-personalia', 'AjaxController@addtSettingCutiPersonalia')->name('ajax.add-setting-cuti-personalia');
	Route::post('ajax/add-setting-cuti-atasan', 'AjaxController@addtSettingCutiAtasan')->name('ajax.add-setting-cuti-atasan');
	Route::post('ajax/add-setting-payment-request-approval', 'AjaxController@addtSettingPaymentRequestApproval')->name('ajax.add-setting-payment-request-approval');
	Route::post('ajax/add-setting-payment-request-verification', 'AjaxController@addtSettingPaymentRequestVerification')->name('ajax.add-setting-payment-request-verification');
	Route::post('ajax/add-setting-payment-request-payment', 'AjaxController@addtSettingPaymentRequestPayment')->name('ajax.add-setting-payment-request-payment');
	Route::post('ajax/add-inventaris-mobil', 'AjaxController@addInvetarisMobil')->name('ajax.add-inventaris-mobil');
	Route::post('ajax/add-setting-medical-hr-benefit', 'AjaxController@addSettingMedicalHRBenefit')->name('ajax.add-setting-medical-hr-benefit');
	Route::post('ajax/add-setting-medical-manager-hr', 'AjaxController@addSettingMedicalManagerHR')->name('ajax.add-setting-medical-manager-hr');
	Route::post('ajax/add-setting-medical-gm-hr', 'AjaxController@addSettingMedicalGMHR')->name('ajax.add-setting-medical-gm-hr');
	Route::post('ajax/add-setting-overtime-hr-operation', 'AjaxController@addSettingOvertimeHrOperation')->name('ajax.add-setting-overtime-hr-operation');
	Route::post('ajax/add-setting-overtime-manager-hr', 'AjaxController@addSettingOvertimeManagerHR')->name('ajax.add-setting-overtime-manager-hr');
	Route::post('ajax/add-setting-overtime-manager-department', 'AjaxController@addSettingOvertimeManagerDepartment')->name('ajax.add-setting-overtime-manager-department');
	Route::post('ajax/add-setting-exit-hr-manager', 'AjaxController@addSettingExitHRManager')->name('ajax.add-setting-exit-hr-manager');
	Route::post('ajax/add-setting-exit-hr-gm', 'AjaxController@addSettingExitHRGM')->name('ajax.add-setting-exit-hr-gm');
	Route::post('ajax/add-setting-exit-hr-director', 'AjaxController@addSettingExitHRDirector')->name('ajax.add-setting-exit-hr-director');
	Route::post('ajax/add-setting-training-ga-department-mengetahui', 'AjaxController@addSettingTrainingGaDepartment')->name('ajax.add-setting-training-ga-department-mengetahui');
	Route::post('ajax/add-setting-training-hrd', 'AjaxController@addSettingTrainingHRD')->name('ajax.add-setting-training-hrd');
	Route::post('ajax/add-setting-training-finance', 'AjaxController@addSettingTrainingFinance')->name('ajax.add-setting-training-finance');
	Route::post('ajax/add-setting-exit-hrd', 'AjaxController@addSettingExitHRD')->name('ajax.add-setting-exit-hrd');
	Route::post('ajax/add-setting-exit-ga', 'AjaxController@addSettingExitGA')->name('ajax.add-setting-exit-ga');
	Route::post('ajax/add-setting-exit-it', 'AjaxController@addSettingExitIT')->name('ajax.add-setting-exit-it');
	Route::post('ajax/add-setting-exit-accounting', 'AjaxController@addSettingExitAccounting')->name('ajax.add-setting-exit-accounting');
	
	Route::post('ajax/get-detail-setting-approval-leave-item', 'AjaxController@getDetailSettingApprovalLeaveItem')->name('ajax.get-detail-setting-approval-leave-item');
	Route::post('ajax/get-detail-setting-approval-paymentRequest-item', 'AjaxController@getDetailSettingApprovalPaymentRequestItem')->name('ajax.get-detail-setting-approval-paymentRequest-item');
	Route::post('ajax/get-detail-setting-approval-recruitment-item', 'AjaxController@getDetailSettingApprovalRecruitmentRequestItem')->name('ajax.get-detail-setting-approval-recruitment-item');
	Route::post('ajax/get-detail-setting-approval-overtime-item', 'AjaxController@getDetailSettingApprovalOvertimeItem')->name('ajax.get-detail-setting-approval-overtime-item');
	Route::post('ajax/get-detail-setting-approval-timesheet-item', 'AjaxController@getDetailSettingApprovalTimesheetItem')->name('ajax.get-detail-setting-approval-timesheet-item');
	Route::post('ajax/get-detail-setting-approval-training-item', 'AjaxController@getDetailSettingApprovalTrainingItem')->name('ajax.get-detail-setting-approval-training-item');
	Route::post('ajax/get-detail-setting-approval-medical-item', 'AjaxController@getDetailSettingApprovalMedicalItem')->name('ajax.get-detail-setting-approval-medical-item');
	Route::post('ajax/get-detail-setting-approval-loan-item', 'AjaxController@getDetailSettingApprovalLoanItem')->name('ajax.get-detail-setting-approval-loan-item');
	Route::post('ajax/get-detail-setting-approval-exit-item', 'AjaxController@getDetailSettingApprovalExitItem')->name('ajax.get-detail-setting-approval-exit-item');
    Route::post('ajax/get-detail-setting-approval-cashAdvance-item', 'AjaxController@getDetailSettingApprovalCashAdvanceItem')->name('ajax.get-detail-setting-approval-cashAdvance-item');

	Route::post('ajax/get-history-approval-leave-custom', 'AjaxController@getHistoryApprovalLeaveCustom')->name('ajax.get-history-approval-leave-custom');
	Route::post('ajax/get-history-approval-timesheet-custom', 'AjaxController@getHistoryApprovalTimesheetCustom')->name('ajax.get-history-approval-timesheet-custom');
	Route::post('ajax/get-history-approval-payment-request-custom', 'AjaxController@getHistoryApprovalPaymentRequestCustom')->name('ajax.get-history-approval-payment-request-custom');
	Route::post('ajax/get-history-approval-overtime-custom', 'AjaxController@getHistoryApprovalOvertimeCustom')->name('ajax.get-history-approval-overtime-custom');
	Route::post('ajax/get-history-approval-overtime-claim-custom', 'AjaxController@getHistoryApprovalOvertimeClaimCustom')->name('ajax.get-history-approval-overtime-claim-custom');
	Route::post('ajax/get-date-overtime-custom', 'AjaxController@chekDateOVertime')->name('ajax.get-date-overtime-custom');
	Route::post('ajax/get-in-out-overtime-custom', 'AjaxController@chekInOutOVertime')->name('ajax.get-in-out-overtime-custom');
	Route::post('ajax/get-history-approval-training-custom', 'AjaxController@getHistoryApprovalTrainingCustom')->name('ajax.get-history-approval-training-custom');
	Route::post('ajax/get-history-approval-training-claim-custom', 'AjaxController@getHistoryApprovalTrainingClaimCustom')->name('ajax.get-history-approval-training-claim-custom');
	Route::post('ajax/get-history-approval-medical-custom', 'AjaxController@getHistoryApprovalMedicalCustom')->name('ajax.get-history-approval-medical-custom');
	Route::post('ajax/get-history-approval-loan-custom', 'AjaxController@getHistoryApprovalLoanCustom')->name('ajax.get-history-approval-loan-custom');
	Route::post('ajax/get-history-approval-exit-custom', 'AjaxController@getHistoryApprovalExitCustom')->name('ajax.get-history-approval-exit-custom');
	Route::post('ajax/get-history-approval-clearance-custom', 'AjaxController@getHistoryApprovalClearanceCustom')->name('ajax.get-history-approval-clearance-custom');
    Route::post('ajax/get-history-approval-cash-advance', 'AjaxController@getHistoryApprovalCashAdvance')->name('ajax.get-history-approval-cash-advance');
    Route::post('ajax/get-history-approval-cash-advance-claim', 'AjaxController@getHistoryApprovalCashAdvanceClaim')->name('ajax.get-history-approval-cash-advance-claim');
    Route::post('ajax/get-history-approval-facilities', 'AjaxController@getHistoryApprovalFacilities')->name('ajax.get-history-approval-facilities');

	Route::post('ajax/get-karyawan-approval', 'AjaxController@getKaryawanApproval')->name('ajax.get-karyawan-approval');
	Route::post('ajax/add-setting-clearance-hrd', 'AjaxController@addSettingClearanceHrd')->name('ajax.add-setting-clearance-hrd');
	Route::post('ajax/add-setting-clearance-ga', 'AjaxController@addSettingClearanceGA')->name('ajax.add-setting-clearance-ga');
	Route::post('ajax/add-setting-clearance-it', 'AjaxController@addSettingClearanceIT')->name('ajax.add-setting-clearance-it');
	Route::post('ajax/add-setting-clearance-accounting', 'AjaxController@addSettingClearanceAccounting')->name('ajax.add-setting-clearance-accounting');

    Route::post('ajax/get-karyawan-transfer', 'AjaxController@getKaryawanTransfer')->name('ajax.get-karyawan-transfer');

	Route::post('ajax/get-city', 'AjaxController@getCity')->name('ajax.get-city');
	Route::post('ajax/get-university', 'AjaxController@getUniversity')->name('ajax.get-university');
	Route::post('ajax/get-history-approval', 'AjaxController@getHistoryApproval')->name('ajax.get-history-approval');
	Route::post('ajax/get-airports', 'AjaxController@getAirports')->name('ajax.get-airports');
	Route::post('ajax/get-history-approval-cuti', 'AjaxController@getHistoryApprovalCuti')->name('ajax.get-history-approval-cuti');	
	Route::post('ajax/get-history-approval-exit', 'AjaxController@getHistoryApprovalExit')->name('ajax.get-history-approval-exit');	
	Route::post('ajax/get-history-approval-training', 'AjaxController@getHistoryApprovalTraining')->name('ajax.get-history-approval-training');	
	Route::post('ajax/get-history-training-bill', 'AjaxController@getHistoryApprovalTrainingBill')->name('ajax.get-history-training-bill');	
	Route::post('ajax/get-history-approval-payment-request', 'AjaxController@getHistoryApprovalPaymentRequest')->name('ajax.get-history-approval-payment-request');		
	Route::post('ajax/get-history-approval-overtime', 'AjaxController@getHistoryApprovalOvertime')->name('ajax.get-history-approval-overtime');		
	Route::post('ajax/get-history-approval-medical', 'AjaxController@getHistoryApprovalMedical')->name('ajax.get-history-approval-medical');
	Route::post('ajax/get-administrator', 'AjaxController@getAdministrator')->name('ajax.get-administrator');		
	Route::post('ajax/get-karyawan', 'AjaxController@getKaryawan')->name('ajax.get-karyawan');
    Route::post('ajax/get-pic-asset', 'AjaxController@getPICAsset')->name('ajax.get-pic-asset');
	Route::post('ajax/get-karyawan-asset', 'AjaxController@getKaryawanAsset')->name('ajax.get-karyawan-asset');
	Route::post('ajax/get-karyawan-payroll', 'AjaxController@getKaryawanPayroll')->name('ajax.get-karyawan-payroll');	
	Route::post('ajax/get-calculate-payroll', 'AjaxController@getCalculatePayroll')->name('ajax.get-calculate-payroll');
	
	#Route::post('ajax/get-karyawan-payrollnet', 'AjaxController@getKaryawanPayrollNet')->name('ajax.get-karyawan-payrollnet');
	#Route::post('ajax/get-calculate-payrollnet', 'AjaxController@getCalculatePayrollNet')->name('ajax.get-calculate-payrollnet');

	Route::post('ajax/get-karyawan-payrollgross', 'AjaxController@getKaryawanPayrollGross')->name('ajax.get-karyawan-payrollgross');
	Route::post('ajax/get-calculate-payrollgross', 'AjaxController@getCalculatePayrollGross')->name('ajax.get-calculate-payrollgross');
	Route::post('ajax/get-bulan-pay-slip', 'AjaxController@getBulanPaySlip')->name('ajax.get-bulan-pay-slip');		
	Route::post('ajax/update-dependent', 'AjaxController@updateDependent')->name('ajax.update-dependent');		
	Route::post('ajax/update-education', 'AjaxController@updateEducation')->name('ajax.update-education');		
    Route::post('ajax/add-certification', 'AjaxController@addCertification')->name('ajax.add-certification');	
	Route::post('ajax/update-certification', 'AjaxController@updateCertification')->name('ajax.update-certification');	
    Route::post('ajax/add-contract', 'AjaxController@addContract')->name('ajax.add-contract');	
	Route::post('ajax/update-contract', 'AjaxController@updateContract')->name('ajax.update-contract');		
	Route::post('ajax/update-cuti', 'AjaxController@updateCuti')->name('ajax.update-cuti');		
	Route::post('ajax/update-inventaris-mobil', 'AjaxController@updateInventarisMobil')->name('ajax.update-inventaris-mobil');	
	Route::post('ajax/update-inventaris-lainnya', 'AjaxController@updateInventarisLainnya')->name('ajax.update-inventaris-lainnya');
	Route::post('ajax/get-manager-by-direktur', 'AjaxEmporeController@getManagerByDirektur')->name('ajax.get-manager-by-direktur');
	Route::post('ajax/get-staff-by-manager', 'AjaxEmporeController@getStaffByManager')->name('ajax.get-staff-by-manager');
	Route::post('ajax/update-first-password', 'AjaxController@updatePassword')->name('ajax.update-first-password');		
	Route::post('ajax/update-password-administrator', 'AjaxController@updatePasswordAdministrator')->name('ajax.update-password-administrator');		
	Route::post('ajax/structure-custome-add', 'AjaxController@structureCustomeAdd')->name('ajax.structure-custome-add');		
	Route::post('ajax/structure-custome-delete', 'AjaxController@structureCustomeDelete')->name('ajax.structure-custome-delete');		
	Route::post('ajax/structure-custome-edit', 'AjaxController@structureCustomeEdit')->name('ajax.structure-custome-edit');		
    Route::get('attendance/public-holiday', 'AttendanceController@ajaxHoliday')->name('attendance.ajax-holiday');
    Route::get('attendance/index', 'AttendanceController@index')->name('attendance.index');
    Route::post('attendance/index', 'AttendanceController@index')->name('attendance.index');
    Route::get('attendance/table', 'AttendanceController@table')->name('attendance.table');
	Route::get('attendance/detail-attendance/{SN}', 'AttendanceController@AttendanceList')->name('attendance.detail-attendance');
    Route::get('attendance/list', 'AttendanceController@list')->name('attendance.list');
    Route::post('attendance/list', 'AttendanceController@list')->name('attendance.list');
    Route::get('visit/public-holiday', 'VisitController@ajaxHoliday')->name('visit.ajax-holiday');
    Route::get('visit/index', 'VisitController@index')->name('visit.index');
    Route::post('visit/index', 'VisitController@index')->name('visit.index');
    Route::get('visit/table', 'VisitController@table')->name('visit.table');
    Route::get('visit/visit-pict/{visitid}', 'VisitController@getVisitPhotos')->name('administrator.visit.detail-pict');
    Route::get('timesheet/index', 'TimesheetController@index')->name('timesheet.index');
    Route::post('timesheet/index', 'TimesheetController@index')->name('timesheet.index');
    Route::get('timesheet/table', 'TimesheetController@table')->name('timesheet.table');
    Route::post('timesheet/store', 'TimesheetController@store')->name('timesheet.store');
    Route::get('timesheet/getActivity', 'TimesheetController@getActivity')->name('timesheet.get-activity');
    Route::post('ajax/get-year-pay-slip', 'AjaxController@getYearPaySlip')->name('ajax.get-year-pay-slip');		
	Route::post('ajax/get-year-pay-slip-all', 'AjaxController@getYearPaySlipAll')->name('ajax.get-year-pay-slip-all');	
	Route::post('ajax/get-bulan-pay-slip-all', 'AjaxController@getBulanPaySlipAll')->name('ajax.get-bulan-pay-slip-all');	
	
	Route::post('ajax/delete-karyawan', 'AjaxController@deleteKaryawan')->name('ajax.delete-karyawan');
	Route::get('ajax/get-libur-nasional', 'AjaxController@getLiburNasional')->name('ajax.get-libur-nasional');
	Route::get('ajax/get-note', 'AjaxController@getNote')->name('ajax.get-note');
	Route::post('ajax/get-detail-note', 'AjaxController@getDetailNote')->name('ajax.get-detail-note');
	Route::post('ajax/store-note', 'AjaxController@storeNote')->name('ajax.store-note');
	Route::post('ajax/get-filter-join-resign', 'AjaxController@getFilterJoinResign')->name('ajax.get-filter-join-resign');
	Route::post('ajax/get-filter-attrition', 'AjaxController@getFilterAttrition')->name('ajax.get-filter-attrition');
	Route::get('ajax/get-user-active', 'AjaxController@getUserActive')->name('ajax.get-user-active');
	Route::get('ajax/get-headcount-department', 'AjaxController@getHeadcountDepartment')->name('ajax.get-headcount-department');
	Route::get('ajax/get-employee-status', 'AjaxController@getDataStatus')->name('ajax.get-employee-status');
	Route::post('ajax/get-data-dashboard', 'AjaxController@getDataDashboard')->name('ajax.get-data-dashboard');
	Route::post('ajax/post-edit-inline', 'AjaxController@postEditInline')->name('ajax.post-edit-inline');
    Route::get('ajax/leave-list', 'AjaxController@leaveList')->name('ajax.leave.list');
    Route::get('shift-setting', 'AttendanceController@setting')->name('shift-setting.index');
    Route::get('shift-list', 'AttendanceController@shiftList')->name('shift.list');
	Route::post('attendance-setting-store', 'AttendanceController@settingStore')->name('attendance-setting.store');
	Route::get('attendance-setting-delete/{id}', 'AttendanceController@settingDelete')->name('attendance-setting.delete');
	Route::post('import-attendance', 'AttendanceController@importAttendance')->name('import-attendance');
	Route::post('attendance/import','AttendanceController@attendanceImport')->name('attendance.import');
	Route::post('attendance/import-all','AttendanceController@importAll')->name('attendance.import-all');
	Route::post('attendance/set-position','AttendanceController@setPosition')->name('attendance-setting.set-position');
	Route::get('attendance/preview','AttendanceController@attendancePreview')->name('attendance.preview');
    Route::post('export-attendance', 'AttendanceController@exportAttendance')->name('export-attendance');
    Route::post('export-visit', 'VisitController@exportVisit')->name('export-visit');
    Route::post('attendance/setting-save', 'AttendanceController@settingSave')->name('administrator.attendance.setting-save');
	Route::post('attendance/setting-remote-attendance', 'AttendanceController@settingRemoteAttendance')->name('administrator.attendance.setting-remote-attendance');
    Route::post('attendance/shift-save', 'AttendanceController@shiftSave')->name('administrator.attendance.shift-save');
    Route::get('attendance/shift-edit/{id}', 'AttendanceController@shiftEdit')->name('administrator.attendance.shift-edit');
    Route::post('attendance/shift-update', 'AttendanceController@shiftUpdate')->name('administrator.attendance.shift-update');
    Route::delete('attendance/shift-delete/{id}', 'AttendanceController@shiftDelete')->name('administrator.attendance.shift-delete');
    Route::get('attendance/user-list-for-assignment/{shift_id}', 'AttendanceController@userToBeAssigned')->name('administrator.attendance.user-list-assign');
    Route::post('attendance/assign-shift', 'AttendanceController@assignShift')->name('administrator.attendance.assign-shift');
	Route::resource('shift-schedule', 'ShiftScheduleController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
    Route::get('shift-schedule/users', 'ShiftScheduleController@getUsers');
	Route::get('shift-schedule/shifts/{id}', 'ShiftScheduleController@getShifts');
    Route::get('shift-schedule/display', 'ShiftScheduleController@getDisplay');
	Route::get('shift-schedule/assign/{id}', 'ShiftScheduleController@getAssign');
	Route::post('shift-schedule/assign/{id}', 'ShiftScheduleController@postAssign');
	Route::get('shift-schedule/preview', 'ShiftScheduleController@importPreview')->name('shift-schedule.preview');
	Route::post('shift-schedule/import', 'ShiftScheduleController@importTemp')->name('shift-schedule.import');
	Route::post('shift-schedule/import-all', 'ShiftScheduleController@importAll')->name('shift-schedule.import-all');
   
    Route::get('ajax/get_payroll_default', 'AjaxController@getKaryawanDefaultPayroll')->name('ajax.payroll.default');
    Route::get('ajax/get_payroll_attendance', 'AjaxController@getKaryawanPayrollAttendance')->name('ajax.payroll.attendance');
    Route::get('ajax/get_payroll_overtime', 'AjaxController@getKaryawanPayrollOvertime')->name('ajax.payroll.overtime');

    Route::get('ajax/kpi_period', 'AjaxController@getKpiItems')->name('ajax.get-kpi-item');
    Route::get('ajax/kpi_period_manager', 'AjaxController@getKpiItemsManager')->name('ajax.get-kpi-item-manager');
    Route::get('ajax/get_structure_organization_detail', 'AjaxController@getStructureOrganizationDetail')->name('ajax.get-structure-organization-detail');


    Route::get('ajax/get-recruitment-request-approval', 'AjaxController@getRecruitmentRequestApproval')->name('ajax.get-recruitment-request-approval');
    Route::get('ajax/get-recruitment-request-detail', 'AjaxController@getRecruitmentRequestDetail')->name('ajax.get-recruitment-request-detail');

    Route::get('ajax/get-report-training', 'AjaxController@reportTraining')->name('ajax.get-report-training');

    Route::get('ajax/get-karyawan-shift', 'Karyawan\AttendanceController@getOtherShift')->name('ajax.get-karyawan-shift');

    Route::post('change-notif-read-status', 'AjaxController@changeNotifReadStatus')->name('ajax.change-notif-read-status');
});

/**
 * Include Custom Routing
 */
foreach (File::allFiles(__DIR__ . '/custom') as $route_file) {
  require $route_file->getPathname();
}

Route::get('/checksession', function ()
{
    return "DB Name : ".session('db_name','mysql')."<br>Temp : ".session('user_db','login');

});

Route::get('/sendemail',function(){
    Config::set('database.default',session('db_name','mysql'));
    if(!empty(get_setting('backup_mail')))
    {
        Config::set('backup.notifications.mail.to', get_setting('backup_mail'));
    }

    Config::set('mail.driver', get_setting('mail_driver'));
    Config::set('mail.host', get_setting('mail_host'));
    Config::set('mail.port', get_setting('mail_port'));
    Config::set('mail.from', ['address' => get_setting('mail_username'), 'name' => get_setting('mail_name') ]);
    Config::set('mail.username', get_setting('mail_username'));
    Config::set('mail.password', get_setting('mail_password'));
    Config::set('mail.encryption', get_setting('mail_encryption'));

    $cuti = CutiKaryawan::first();
//    echo json_encode($cuti);
    $cuti->approve_direktur_date    = date('Y-m-d H:i:s');

    $params['data']     = $cuti;
    $params['text']     = '<p><strong>Dear Sir/Madam '. $cuti->user->name .'</strong>,</p> <p>  Submission of your Leave / Permit <strong style="color: green;">APPROVED</strong>.</p>';
    // send email
    info('Start sending email normal');
    echo 'Start sending email normal';
    for($i = 1; $i <= 50; $i++) {
        try {
            \Mail::send('email.cuti-approval', $params,
                function ($message) use ($cuti, $i) {
                    $message->to("baso@4nesia.com");
                    $message->subject(get_setting('mail_name') . ' - Submission of Leave / Permit ' . $i);
                }
            );
        }catch (\Swift_TransportException $e){
            return redirect()->back()->with('message-error', 'Email config is invalid!');
        }
    }
    info('Finishing sending email normal');
    echo 'Finishing sending email normal';
});
Route::get('/sendemailqueue',function(){

    Config::set('database.default',session('db_name','mysql'));

//
    $params['mail_driver'] = get_setting('mail_driver');
    $params['mail_host'] = get_setting('mail_host');
    $params['mail_port'] = get_setting('mail_port');
    $params['mail_from'] = ['address' => get_setting('mail_username'), 'name' => get_setting('mail_name')];
    $params['mail_username'] = get_setting('mail_username');
    $params['mail_password'] = get_setting('mail_password');
    $params['mail_encryption'] = get_setting('mail_encryption');
    $params['mail_name'] = get_setting('mail_name');

    $cuti = CutiKaryawan::first();
    $cuti->approve_direktur_date    = date('Y-m-d H:i:s');

    $params['view']     = 'email.cuti-approval';
    $params['subject']     = $params['mail_name'].' - Submission of Leave / Permit Queue'.$params['i'];
    $params['data']     = $cuti;
    // send email
    info('Start sending email normal');
    Config::set('database.default','mysql');

    for($i = 1; $i <= 50; $i++) {
        $params['email'] = 'baso@4nesia.com';
        $params['i'] = $i;
        $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
        dispatch($job);
    }

    info('Finishing sending email normal');
});

Route::get('/{id}', function ($id)
{
    $config = ConfigDB::where('company_code',strtolower($id))->where('due_date', '>=', \Carbon\Carbon::now()->startOfDay())->first();
    if(!$config){
        return abort(404);
    }
    else{
        $user = Auth::user();
        if($user){
            if($user->access_id == 1)
                return redirect('/administrator');
            else if($user->access_id == 2)
                return redirect('/karyawan');
        }
        session(['user_db'=>$config->db_name]);
        session(['temp_company_url'=>strtolower($id)]);
        return view('auth.login')->with('config',$config);
    }
});


