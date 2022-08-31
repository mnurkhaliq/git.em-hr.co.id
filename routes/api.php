<?php

use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Route
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


#Route::middleware('auth:api')->get('/get-request', function (Request $request) {
Route::get('/get-request', function (Request $request) {

    $params['code']     = 200;
    $params['message']     = 'success';

    return $params;
});


Route::post('/post-request', function (Request $request) {

    $params['code']     = 200;
    $params['message']     = 'success';
    $params['data']     = $request;


    $store                     = new App\Models\AbsensiRequest();
    $store->value              = $request->input();
    $store->save();

    $absen                  = new App\Models\AbsensiItem();
    $absen->absensi_id         = $request->absensi_id;

    $user                     = \App\User::where('employee_number', $request->emp_no)->first();
    if ($user) {
        $absen->user_id         = $user->id;
    }

    $absen->date             = $request->date;
    $absen->timetable         = $request->timetable;
    $absen->on_dutty         = $request->on_dutty;
    $absen->off_dutty         = $request->off_dutty;
    $absen->clock_in         = $request->clock_in;
    $absen->clock_out         = $request->clock_out;
    $absen->work_time         = $request->work_time;
    $absen->save();

    return $params;
});

Route::group(['middleware' => ['cors']], function () {
    Route::post('request-free-trial', 'Api\FreeTrialController@create')->name('request-free-trial');
    Route::post('recruitment-login', 'Api\RecruitmentController@login')->name('api-recruitment-login');
    Route::post('recruitment-register', 'Api\RecruitmentController@register')->name('api-recruitment-register');
    Route::post('recruitment-apply/{rec_req_id}', 'Api\RecruitmentController@apply')->name('api-recruitment-apply');
    Route::get('job-vacancy-list', 'Api\RecruitmentController@jobVacancyList')->name('api-job-vacancy-list');
    Route::get('job-vacancy-search', 'Api\RecruitmentController@jobVacancySearch')->name('api-job-vacancy-search');
    Route::get('vacancy-param', 'Api\RecruitmentController@getVacancyParams')->name('api-vacancy-param');
    Route::get('job-detail/{rec_req_id}', 'Api\RecruitmentController@jobDetail')->name('api-job-detail');
    Route::post('send-email', 'Api\RecruitmentController@sendEmail')->name('api-send-email');
    Route::post('apply', 'Api\RecruitmentController@applyJob')->name('api-apply');
});

Route::group(['prefix' => 'mobile', 'namespace' => 'Api'], function () {
    Route::post('check-code', 'AuthController@checkCode')->name('check-code');
    Route::post('login', 'AuthController@login')->name('login');
    Route::post('register', 'AuthController@register');
    Route::post('request-reset', 'AuthController@requestReset');
    Route::get('profile', 'AuthController@getProfile')->name('profile');
    Route::put('update_password', 'AuthController@updatePassword');
    Route::put('update-firebase-token', 'AuthController@updateFirebaseToken');
    Route::post('validate', 'AuthController@validateToken');
    Route::get('shift_detail', 'ShiftController@getShiftDetail');
    Route::post('report', 'ReportController@report');
    Route::get('get-modules', 'AuthController@getModules')->name('get-modules');
    Route::get('notification', 'AuthController@notification');
    Route::get('news/{id}', 'InfoController@getNewsDetail');
    Route::get('news', 'InfoController@getNews');
    Route::get('memo/{id}', 'InfoController@getMemoDetail');
    Route::get('memo', 'InfoController@getMemo');
    Route::get('product/{id}', 'InfoController@getProductDetail');
    Route::get('product', 'InfoController@getProduct');
    Route::get('recruitment/{id}', 'InfoController@getRecruitmentDetail');
    Route::get('recruitment', 'InfoController@getRecruitment');
    Route::post('recruitment', 'RecruitmentController@applyInternalRecruitment')->name('post-recruitment');
    Route::get('vacancies', 'RecruitmentController@getInternalVacancies')->name('get-vacancies');
    Route::get('application', 'RecruitmentController@getApplications')->name('get-applications');
    Route::get('application/{id}', 'RecruitmentController@getApplicationDetail')->name('get-application');

    Route::get('birthday', 'BirthdayController@birthday');
    Route::get('birthday/{id}', 'BirthdayController@birthdayDetail');
    Route::get('birthday-wording', 'BirthdayController@wording');
    Route::post('/birthday/like', 'BirthdayController@like');
	Route::post('/birthday/comment', 'BirthdayController@comment');
	Route::post('/birthday/comment/like', 'BirthdayController@commentLike');
	Route::post('/birthday/comment/reply', 'BirthdayController@commentReply');

    Route::get('calendar', 'CalendarController@getCalendar');
    Route::get('calendar/date', 'CalendarController@getDate');
    Route::get('calendar/dates', 'CalendarController@getDates');

    Route::get('attendance/clock-status', 'AttendanceController@getClockStatus');
    Route::get('attendance/dashboard/filter', 'AttendanceController@getDashboardFilter');
    Route::get('attendance/dashboard', 'AttendanceController@getDashboardData');
    Route::get('attendance/dashboard-range', 'AttendanceController@getDashboardDataRange');
    Route::post('attendance/clock', 'AttendanceController@clock');
    Route::get('attendance/other-shift', 'AttendanceController@getOtherShift');
    Route::resource('attendance', 'AttendanceController', ['only' => ['index', 'create', 'store', 'edit', 'destroy', 'update', 'show']]);
    // Route::resource('visit', 'VisitController', ['only' => ['index', 'create', 'store', 'edit', 'destroy', 'update', 'show']]);
    Route::get('leave/params', 'LeaveController@getParams');
    Route::get('leave/approval', 'LeaveController@getApproval');
    Route::post('leave/approve', 'LeaveController@approve');
    Route::post('leave/cancel', 'LeaveController@cancel');
    Route::get('leave/backup-person', 'LeaveController@getBackupPerson');
    Route::get('leave/history', 'LeaveController@getHistory');
    Route::resource('leave', 'LeaveController', ['only' => ['index', 'create', 'store', 'edit', 'destroy', 'update', 'show']]);
    Route::get('overtime/params', 'OvertimeController@getParams');
    Route::get('overtime/approval', 'OvertimeController@getApproval');
    Route::post('overtime/approve', 'OvertimeController@approve');
    Route::post('overtime/claim', 'OvertimeController@claim');
    Route::post('overtime/claim/approve', 'OvertimeController@approveClaim');
    Route::get('overtime/claim/calculation', 'OvertimeController@getCalculation');
    Route::resource('overtime', 'OvertimeController', ['only' => ['index', 'create', 'store', 'edit', 'destroy', 'update', 'show']]);

    Route::get('timesheet/info', 'TimesheetController@getInfo');
    Route::get('timesheet/params', 'TimesheetController@getParams');
    Route::get('timesheet/approval', 'TimesheetController@getApproval');
    Route::post('timesheet/approve', 'TimesheetController@approve');
    Route::get('timesheet/list-weeks', 'TimesheetController@getListWeeks');
    Route::resource('timesheet', 'TimesheetController', ['only' => ['index', 'create', 'store', 'edit', 'destroy', 'update', 'show']]);

    Route::get('payment-request/params', 'PaymentRequestController@getParams');
    Route::get('payment-request/approval', 'PaymentRequestController@getApproval');
    Route::post('payment-request/approve', 'PaymentRequestController@approve');
    Route::get('payment-request/transfer/{id}', 'PaymentRequestController@detailTransfer');
    Route::post('payment-request/transfer', 'PaymentRequestController@transfer');
    Route::post('payment-request/update', 'PaymentRequestController@update');
    Route::resource('payment-request', 'PaymentRequestController', ['only' => ['index', 'create', 'store', 'edit', 'destroy', 'show']]);

    Route::get('cash-advance/params', 'CashAdvanceController@getParams');
    Route::get('cash-advance/approval', 'CashAdvanceController@getApproval');
    Route::post('cash-advance/approve', 'CashAdvanceController@approve');
    Route::get('cash-advance/transfer/{id}', 'CashAdvanceController@detailTransfer');
    Route::post('cash-advance/transfer', 'CashAdvanceController@transfer');
    Route::post('cash-advance/claim', 'CashAdvanceController@claim');
    Route::post('cash-advance/claim/approve', 'CashAdvanceController@approveClaim');
    Route::post('cash-advance/claim/transfer', 'CashAdvanceController@transferClaim');
    Route::get('cash-advance/claim/transfer/user/{id}', 'CashAdvanceController@transferUser');
    Route::post('cash-advance/claim/transfer/user', 'CashAdvanceController@prosesTransferUser');
    Route::resource('cash-advance', 'CashAdvanceController', ['only' => ['index', 'create', 'store', 'edit','show']]);

    Route::get('medical/params', 'MedicalController@getParams');
    Route::get('medical/approval', 'MedicalController@getApproval');
    Route::post('medical/approve', 'MedicalController@approve');
    Route::get('medical/transfer/{id}', 'MedicalController@detailTransfer');
    Route::post('medical/transfer', 'MedicalController@transfer');
    Route::post('medical/update', 'MedicalController@update');
    Route::resource('medical', 'MedicalController', ['only' => ['index', 'create', 'store', 'edit', 'destroy', 'show']]);

    Route::get('loan/params', 'LoanController@getParams');
    Route::get('loan/term', 'LoanController@getTerm');
    Route::get('loan/approval', 'LoanController@getApproval');
    Route::post('loan/approve', 'LoanController@approve');
    Route::post('loan/payment', 'LoanController@payment');
    Route::resource('loan', 'LoanController', ['only' => ['index', 'create', 'store', 'edit', 'destroy', 'update', 'show']]);

    Route::get('recruitment-request/params', 'RecruitmentRequestController@getParams');
    Route::get('recruitment-request/approval', 'RecruitmentRequestController@getApproval');
    Route::post('recruitment-request/approve', 'RecruitmentRequestController@approve');
    Route::resource('recruitment-request', 'RecruitmentRequestController', ['only' => ['index', 'create', 'store', 'edit', 'destroy', 'update', 'show']]);
    
    Route::get('training/params', 'TrainingController@getParams');
    Route::get('training/claim-params/{id}', 'TrainingController@getClaimParams');
    Route::get('training/airports', 'TrainingController@getAirports');
    Route::get('training/city', 'TrainingController@getCity');
    Route::get('training/plafond', 'TrainingController@getPlafond');
    Route::get('training/approval', 'TrainingController@getApproval');
    Route::post('training/approve', 'TrainingController@approve');
    Route::get('training/transfer/{id}', 'TrainingController@detailTransfer');
    Route::post('training/transfer', 'TrainingController@transfer');
    Route::post('training/claim', 'TrainingController@claim');
    Route::post('training/claim/approve', 'TrainingController@approveClaim');
    Route::post('training/claim/approve', 'TrainingController@approveClaim');
    Route::post('training/claim/transfer', 'TrainingController@transferClaim');
    Route::get('training/claim/transfer/user/{id}', 'TrainingController@transferUser');
    Route::post('training/claim/transfer/user', 'TrainingController@prosesTransferUser');
    Route::resource('training', 'TrainingController', ['only' => ['index', 'create', 'store', 'edit', 'destroy', 'update', 'show']]);
    
    Route::get('exit-interview/params', 'ExitInterviewController@getParams');
    Route::get('exit-interview/approval', 'ExitInterviewController@getApproval');
    Route::post('exit-interview/approve', 'ExitInterviewController@approve');
    Route::get('exit-clearance/approval', 'ExitInterviewController@getApprovalClearance');
    Route::post('exit-clearance/approve', 'ExitInterviewController@approveClearance');
    Route::resource('exit-interview', 'ExitInterviewController', ['only' => ['index', 'create', 'store', 'edit', 'destroy', 'update', 'show']]);
    Route::get('request-payslip/data', 'RequestPaySlipController@data');
    Route::resource('request-payslip', 'RequestPaySlipController', ['only' => ['index', 'create', 'store', 'edit', 'destroy', 'update', 'show']]);
    Route::resource('performance-evaluation', 'PerformanceEvaluationController', ['only' => ['index', 'create', 'store', 'edit', 'destroy', 'update', 'show']]);
    Route::get('performance-evaluation/detail/{id}', 'PerformanceEvaluationController@getDetail');
    Route::resource('kpi-survey', 'KpiSurveyController', ['only' => ['index', 'create', 'store', 'edit', 'destroy', 'update', 'show']]);
    Route::get('kpi-survey/detail/{id}', 'KpiSurveyController@getDetail');
    Route::get('approval-facility',  'FacilityManagementController@indexApproval');
	Route::get('approval-facility/detail/{id}',  'FacilityManagementController@showApproval');
	Route::get('approval-facility/history/{asset_id}',  'FacilityManagementController@historyAsset');
	Route::post('approval-facility/proses',  'FacilityManagementController@prosesApproval');
    Route::post('facility/return', 'FacilityManagementController@returnAsset');
    Route::put('facility/reject', 'FacilityManagementController@reject');
    Route::put('facility/confirm', 'FacilityManagementController@confirm');
    Route::put('facility/user-note', 'FacilityManagementController@userNote');
    Route::resource('facility', 'FacilityManagementController', ['only' => ['index', 'destroy', 'update', 'show']]);
    Route::get('visit/params', 'VisitController@getVisitParams');
    Route::get('visit/filter-params', 'VisitController@getVisitFilterParams');
    Route::resource('visit', 'VisitController', ['only' => ['index', 'create', 'store', 'edit', 'destroy', 'update', 'show']]);
});
