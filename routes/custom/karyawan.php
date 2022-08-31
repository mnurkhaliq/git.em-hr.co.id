<?php 
// ROUTING KARYAWAN
Route::group(['prefix' => 'karyawan', 'namespace'=>'Karyawan', 'middleware' => ['auth', 'access:2']], function(){
	Route::get('/', 'IndexController@index')->name('karyawan.dashboard');
	Route::get('/clock-in', 'AttendanceController@clockIn')->name('karyawan.clock-in');
	Route::get('/clock-out', 'AttendanceController@clockOut')->name('karyawan.clock-out');
	Route::get('/detail/clock-in', 'AttendanceController@detailClockIn')->name('karyawan.detail.clock-in');
	Route::get('/detail/clock-out', 'AttendanceController@detailClockOut')->name('karyawan.detail.clock-out');
	Route::post('/clock-in', 'AttendanceController@clock')->name('karyawan.clock');
	Route::get('ajax-get-statistic', 'AttendanceController@ajaxStatistic')->name('karyawan.ajax-get-statistic');
	Route::resource('medical', 'MedicalController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'karyawan']);
	Route::resource('medical', 'MedicalController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'karyawan']);
	Route::resource('overtime', 'OvertimeController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'karyawan']);
	Route::resource('payment-request', 'PaymentRequestController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'karyawan']);
	Route::resource('exit-clearance', 'ExitClearanceController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'karyawan']);
	Route::resource('exit-interview', 'ExitInterviewController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'karyawan']);
	Route::get('exit-inteview/detail/{id}',  'ExitInterviewController@detail')->name('karyawan.exit-interview.detail');
	#Route::resource('compassionate-reason', 'CompassionateReasonController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'karyawan']);
	Route::resource('training', 'TrainingController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'karyawan']);
	Route::get('training/biaya/{id}', 'TrainingController@biaya')->name('karyawan.training.biaya');
	Route::get('training/detail/{id}', 'TrainingController@detailTraining')->name('karyawan.training.detail');
	Route::post('training/submit-biaya', 'TrainingController@submitBiaya')->name('karyawan.training.submit-biaya');
	Route::resource('cuti', 'CutiController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'karyawan']);
	
    Route::resource('leave', 'LeaveController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'karyawan']);
    Route::get('getListDay/{id}', 'LeaveController@getListDay')->name('karyawan.leave.get-list-day');
	Route::get('approval-leave-custom',  'ApprovalLeaveCustomController@index')->name('karyawan.approval.leave-custom.index');
	Route::get('approval-leave-custom/detail/{id}',  'ApprovalLeaveCustomController@detail')->name('karyawan.approval.leave-custom.detail');
	Route::post('approval-leave-custom/proses',  'ApprovalLeaveCustomController@proses')->name('karyawan.approval.leave-custom.proses');

    Route::resource('timesheet', 'TimesheetController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'karyawan']);
	Route::post('search',  'TimesheetController@search')->name('karyawan.timesheet.search');
	Route::get('get-category',  'TimesheetController@getTimesheetCategory')->name('karyawan.timesheet.get-category');
    Route::get('get-activity',  'TimesheetController@getTimesheetActivity')->name('karyawan.timesheet.get-activity');
    Route::get('get-list-weeks',  'TimesheetController@getListWeeks')->name('karyawan.timesheet.get-list-weeks');
	Route::get('approval-timesheet-custom',  'ApprovalTimesheetCustomController@index')->name('karyawan.approval.timesheet-custom.index');
	Route::post('approval-timesheet-custom',  'ApprovalTimesheetCustomController@index')->name('karyawan.approval.timesheet-custom.index');
	Route::get('approval-timesheet-custom/detail/{id}',  'ApprovalTimesheetCustomController@detail')->name('karyawan.approval.timesheet-custom.detail');
	Route::post('approval-timesheet-custom/proses',  'ApprovalTimesheetCustomController@proses')->name('karyawan.approval.timesheet-custom.proses');

	Route::resource('payment-request-custom', 'PaymentRequestCustomController', ['only'=> ['index','create','store', 'edit','update','destroy'], 'as' => 'karyawan']);
	Route::get('payment-request-type/get-plafond', 'PaymentRequestCustomController@getPlafond');
	Route::get('approval-payment-request-custom/index',  'ApprovalPaymentRequestCustomController@index')->name('karyawan.approval.payment-request-custom.index');
	Route::post('approval-payment-request-custom/index', 'ApprovalPaymentRequestCustomController@index')->name('karyawan.approval.payment-request-custom.index');
	Route::get('approval-payment-request-custom/detail/{id}',  'ApprovalPaymentRequestCustomController@detail')->name('karyawan.approval.payment-request-custom.detail');
	Route::post('approval-payment-request-custom/proses',  'ApprovalPaymentRequestCustomController@proses')->name('karyawan.approval.payment-request-custom.proses');
	Route::get('approval-payment-request-custom/detail/transfer/{id}',  'ApprovalPaymentRequestCustomController@detailTransfer')->name('karyawan.approval.payment-request-custom.transfer');
	Route::post('approval-payment-request-custom/transfer/{id}',  'ApprovalPaymentRequestCustomController@transfer')->name('karyawan.approval.payment-request-custom.prosesTransfer');

    Route::get('approval-recruitment-request',  'ApprovalRecruitmentRequestController@index')->name('karyawan.approval.recruitment-request.index');
    Route::get('approval-recruitment-request/detail/{id}',  'ApprovalRecruitmentRequestController@detail')->name('karyawan.approval.recruitment-request.detail');
    Route::post('approval-recruitment-request/proses',  'ApprovalRecruitmentRequestController@proses')->name('karyawan.approval.recruitment-request.proses');

	Route::resource('overtime-custom', 'OvertimeCustomController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'karyawan']);
	Route::get('overtime-custom/claim/{id}',  'OvertimeCustomController@claim')->name('karyawan.overtime-custom.claim');
	Route::post('overtime-custom/prosesclaim',  'OvertimeCustomController@prosesclaim')->name('karyawan.overtime-custom.prosesclaim');
	Route::get('approval-overtime-custom',  'ApprovalOvertimeCustomController@index')->name('karyawan.approval.overtime-custom.index');
	Route::get('approval-overtime-custom/detail/{id}',  'ApprovalOvertimeCustomController@detail')->name('karyawan.approval.overtime-custom.detail');
	Route::post('approval-overtime-custom/proses',  'ApprovalOvertimeCustomController@proses')->name('karyawan.approval.overtime-custom.proses');
	Route::get('approval-overtime-custom/claim/{id}',  'ApprovalOvertimeCustomController@claim')->name('karyawan.approval.overtime-custom.claim');
	Route::post('approval-overtime-custom/prosesClaim',  'ApprovalOvertimeCustomController@prosesClaim')->name('karyawan.approval.overtime-custom.prosesClaim');

	Route::resource('training-custom', 'TrainingCustomController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'karyawan']);
	Route::get('training-custom/claim/{id}',  'TrainingCustomController@claim')->name('karyawan.training-custom.claim');
	Route::post('training-custom/prosesclaim',  'TrainingCustomController@prosesclaim')->name('karyawan.training-custom.prosesclaim');
	Route::get('training-custom/prosesTransfer/{id}',  'TrainingCustomController@transfer')->name('karyawan.training-custom.transfer');
	Route::post('training-custom/prosesTransfer/{id}',  'TrainingCustomController@prosesTransfer')->name('karyawan.training-custom.prosesTransfer');
	Route::get('approval-training-custom/index',  'ApprovalTrainingCustomController@index')->name('karyawan.approval.training-custom.index');
	Route::post('approval-training-custom/index',  'ApprovalTrainingCustomController@index')->name('karyawan.approval.training-custom.index');
	Route::get('approval-training-custom/detail/{id}',  'ApprovalTrainingCustomController@detail')->name('karyawan.approval.training-custom.detail');
	Route::post('approval-training-custom/proses',  'ApprovalTrainingCustomController@proses')->name('karyawan.approval.training-custom.proses');
	Route::get('approval-training-custom/claim/{id}',  'ApprovalTrainingCustomController@claim')->name('karyawan.approval.training-custom.claim');
	Route::post('approval-training-custom/prosesClaim',  'ApprovalTrainingCustomController@prosesClaim')->name('karyawan.approval.training-custom.prosesClaim');
	Route::get('approval-training-custom/detail/transfer/{id}',  'ApprovalTrainingCustomController@detailTransfer')->name('karyawan.approval.training-custom.transfer');
	Route::post('approval-training-custom/transfer/{id}',  'ApprovalTrainingCustomController@transfer')->name('karyawan.approval.training-custom.prosesTransfer');
	Route::get('approval-training-custom/claim/transfer/{id}',  'ApprovalTrainingCustomController@detailTransferClaim')->name('karyawan.approval.training-custom.transferClaim');
	Route::post('approval-training-custom/transferClaim/{id}',  'ApprovalTrainingCustomController@transferClaim')->name('karyawan.approval.training-custom.prosesTransferClaim');

	Route::resource('medical-custom', 'MedicalCustomController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'karyawan']);
	Route::get('approval-medical-custom/index',  'ApprovalMedicalCustomController@index')->name('karyawan.approval.medical-custom.index');
	Route::post('approval-medical-custom/index', 'ApprovalMedicalCustomController@index')->name('karyawan.approval.medical-custom.index');
	Route::get('approval-medical-custom/detail/{id}',  'ApprovalMedicalCustomController@detail')->name('karyawan.approval.medical-custom.detail');
	Route::post('approval-medical-custom/proses',  'ApprovalMedicalCustomController@proses')->name('karyawan.approval.medical-custom.proses');
	Route::get('approval-medical-custom/detail/transfer/{id}',  'ApprovalMedicalCustomController@detailTransfer')->name('karyawan.approval.medical-custom.transfer');
	Route::post('approval-medical-custom/transfer/{id}',  'ApprovalMedicalCustomController@transfer')->name('karyawan.approval.medical-custom.prosesTransfer');

	Route::resource('loan', 'LoanController', ['only'=> ['index','create','store','edit','destroy','update'], 'as' => 'karyawan']);
    Route::get('loan/table/{id}',  'LoanController@table')->name('karyawan.loan.table');
    Route::post('loan/pay/{id}',  'LoanController@pay')->name('karyawan.loan.pay');
    Route::get('approval-loan',  'ApprovalLoanController@index')->name('karyawan.approval-loan.index');
	Route::get('approval-loan/detail/{id}',  'ApprovalLoanController@detail')->name('karyawan.approval-loan.detail');
	Route::post('approval-loan/proses',  'ApprovalLoanController@proses')->name('karyawan.approval-loan.proses');
    Route::get('approval-loan/table/{id}',  'ApprovalLoanController@table')->name('karyawan.approval-loan.table');

    Route::get('loan-payment/table',  'LoanController@table')->name('karyawan.loan-payment.table');
    Route::get('loan-payment/index',  'LoanController@paymentIndex')->name('karyawan.loan-payment.index');

	Route::resource('exit-custom', 'ExitInterviewCustomController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'karyawan']);
	Route::get('exit-custom/clearance/{id}',  'ExitInterviewCustomController@clearance')->name('karyawan.exit-custom.clearance');
	Route::post('exit-custom/prosesclearance',  'ExitInterviewCustomController@prosesclearance')->name('karyawan.exit-custom.prosesclearance');
	Route::get('approval-exit-custom',  'ApprovalExitInterviewCustomController@index')->name('karyawan.approval.exit-custom.index');
	Route::get('approval-exit-custom/detail/{id}',  'ApprovalExitInterviewCustomController@detail')->name('karyawan.approval.exit-custom.detail');
	Route::post('approval-exit-custom/proses',  'ApprovalExitInterviewCustomController@proses')->name('karyawan.approval.exit-custom.proses');

	Route::resource('cash-advance', 'CashAdvanceController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'karyawan']);
	Route::get('cash-advance/get-plafond', 'CashAdvanceController@getPlafond');
	Route::get('cash-advance/claim/{id}',  'CashAdvanceController@claim')->name('karyawan.cash-advance.claim');
	Route::post('cash-advance/prosesclaim/{id}',  'CashAdvanceController@prosesclaim')->name('karyawan.cash-advance.prosesclaim');
	Route::get('cash-advance/prosesTransfer/{id}',  'CashAdvanceController@transfer')->name('karyawan.cash-advance.transfer');
	Route::post('cash-advance/prosesTransfer/{id}',  'CashAdvanceController@prosesTransfer')->name('karyawan.cash-advance.prosesTransfer');
	Route::get('approval-cash-advance/index',  'ApprovalCashAdvanceController@index')->name('karyawan.approval.cash-advance.index');
	Route::post('approval-cash-advance/index', 'ApprovalCashAdvanceController@index')->name('karyawan.approval.cash-advance.index');
	Route::get('approval-cash-advance/detail/{id}',  'ApprovalCashAdvanceController@detail')->name('karyawan.approval.cash-advance.detail');
	Route::post('approval-cash-advance/proses',  'ApprovalCashAdvanceController@proses')->name('karyawan.approval.cash-advance.proses');
	Route::get('approval-cash-advance/claim/{id}',  'ApprovalCashAdvanceController@claim')->name('karyawan.approval.cash-advance.claim');
	Route::post('approval-cash-advance/prosesClaim/{id}',  'ApprovalCashAdvanceController@prosesClaim')->name('karyawan.approval.cash-advance.prosesClaim');
	Route::get('approval-cash-advance/detail/transfer/{id}',  'ApprovalCashAdvanceController@detailTransfer')->name('karyawan.approval.cash-advance.transfer');
	Route::post('approval-cash-advance/transfer/{id}',  'ApprovalCashAdvanceController@transfer')->name('karyawan.approval.cash-advance.prosesTransfer');
	Route::get('approval-cash-advance/claim/transfer/{id}',  'ApprovalCashAdvanceController@detailTransferClaim')->name('karyawan.approval.cash-advance.transferClaim');
	Route::post('approval-cash-advance/transferClaim/{id}',  'ApprovalCashAdvanceController@transferClaim')->name('karyawan.approval.cash-advance.prosesTransferClaim');

	Route::resource('facilities', 'InventoryController', ['only'=> ['index','show', 'edit','update'], 'as' => 'karyawan']);
	Route::post('asset/confirm/{id}', 'InventoryController@confirmAsset')->name('karyawan.asset.confirm');
	Route::post('asset/reject/{id}', 'InventoryController@rejectAsset')->name('karyawan.asset.reject');
    Route::post('asset/update-note', 'InventoryController@updateNote')->name('karyawan.update-note');
	Route::get('approval-facilities',  'ApprovalFacilitiesController@index')->name('karyawan.approval.facilities.index');
	Route::get('approval-facilities/detail/{id}',  'ApprovalFacilitiesController@detail')->name('karyawan.approval.facilities.detail');
	Route::get('approval-facilities/history/{id}',  'ApprovalFacilitiesController@history')->name('karyawan.approval.facilities.history');
	Route::post('approval-facilities/proses',  'ApprovalFacilitiesController@proses')->name('karyawan.approval.facilities.proses');
	
	Route::get('approval-clearance-custom', 'ApprovalExitClearanceCustomController@index')->name('karyawan.approval.clearance-custom.index');
	Route::get('approval-clearance-custom/detail/{id}', 'ApprovalExitClearanceCustomController@detail')->name('karyawan.approval.clearance-custom.detail');
	Route::post('approval-clearance-custom/proses', 'ApprovalExitClearanceCustomController@proses')->name('karyawan.approval.clearance-custom.proses');
	

	Route::get('approval-cuti',  'ApprovalCutiController@index')->name('karyawan.approval.cuti.index');
	Route::get('approval-cuti/detail/{id}',  'ApprovalCutiController@detail')->name('karyawan.approval.cuti.detail');
	Route::post('approval-cuti/proses',  'ApprovalCutiController@proses')->name('karyawan.approval.cuti.proses');
	Route::get('approval-cuti-atasan',  'ApprovalCutiAtasanController@index')->name('karyawan.approval.cuti-atasan.index');
	Route::get('approval-cuti-atasan/detail/{id}',  'ApprovalCutiAtasanController@detail')->name('karyawan.approval.cuti-atasan.detail');
	Route::post('approval-cuti-atasan/proses',  'ApprovalCutiAtasanController@proses')->name('karyawan.approval.cuti-atasan.proses');
	Route::get('approval-payment-request',  'ApprovalPaymentRequestController@index')->name('karyawan.approval.payment_request.index');
	Route::get('approval-payment-request/detail/{id}',  'ApprovalPaymentRequestController@detail')->name('karyawan.approval.payment_request.detail');
	Route::post('approval-payment-request/proses',  'ApprovalPaymentRequestController@proses')->name('karyawan.approval.payment_request.proses');
	Route::get('approval-payment-request-atasan',  'ApprovalPaymentRequestAtasanController@index')->name('karyawan.approval.payment-request-atasan.index');
	Route::get('approval-payment-request-atasan/detail/{id}',  'ApprovalPaymentRequestAtasanController@detail')->name('karyawan.approval.payment-request-atasan.detail');
	Route::post('approval-payment-request-atasan/proses',  'ApprovalPaymentRequestAtasanController@proses')->name('karyawan.approval.payment-request-atasan.proses');
	Route::get('approval-medical',  'ApprovalMedicalController@index')->name('karyawan.approval.medical.index');
	Route::get('approval-medical/detail/{id}',  'ApprovalMedicalController@detail')->name('karyawan.approval.medical.detail');
	Route::post('approval-medical/proses',  'ApprovalMedicalController@proses')->name('karyawan.approval.medical.proses');
	Route::get('approval-exit',  'ApprovalExitController@index')->name('karyawan.approval.exit.index');
	Route::get('approval-exit/detail/{id}',  'ApprovalExitController@detail')->name('karyawan.approval.exit.detail');
	Route::post('approval-exit/proses',  'ApprovalExitController@proses')->name('karyawan.approval.exit.proses');
	Route::get('approval-exit-clearance',  'ApprovalExitController@index')->name('karyawan.approval.exit_clearance.index');
	Route::get('approval-exit-clearance/detail/{id}',  'ApprovalExitController@detail')->name('karyawan.approval.exit_clearance.detail');
	Route::post('approval-exit-clearance/proses',  'ApprovalExitController@proses')->name('karyawan.approval.exit_clearance.proses');
	Route::get('approval-training',  'ApprovalTrainingController@index')->name('karyawan.approval.training.index');
	Route::get('approval-training/detail/{id}',  'ApprovalTrainingController@detail')->name('karyawan.approval.training.detail');
	Route::post('approval-training/proses',  'ApprovalTrainingController@proses')->name('karyawan.approval.training.proses');
	Route::get('approval-training/biaya/{id}',  'ApprovalTrainingController@biaya')->name('karyawan.approval.training.biaya');
	Route::post('approval-training/proses-biaya',  'ApprovalTrainingController@prosesBiaya')->name('karyawan.approval.training.proses-biaya');
	Route::get('approval-training-atasan',  'ApprovalTrainingAtasanController@index')->name('karyawan.approval.training-atasan.index');
	Route::get('approval-training-atasan/detail/{id}',  'ApprovalTrainingAtasanController@detail')->name('karyawan.approval.training-atasan.detail');
	Route::post('approval-training-atasan/proses',  'ApprovalTrainingAtasanController@proses')->name('karyawan.approval.training-atasan.proses');
	Route::post('approval-training-atasan/biaya',  'ApprovalTrainingAtasanController@biaya')->name('karyawan.approval.training-atasan.biaya');
	Route::get('approval-training-atasan/biaya/{id}',  'ApprovalTrainingAtasanController@biaya')->name('karyawan.approval.training-atasan.biaya');
	Route::post('approval-training-atasan/proses-biaya',  'ApprovalTrainingAtasanController@prosesBiaya')->name('karyawan.approval.training-atasan.proses-biaya');
	Route::get('approval-overtime',  'ApprovalOvertimeController@index')->name('karyawan.approval.overtime.index');
	Route::get('approval-overtime/detail/{id}',  'ApprovalOvertimeController@detail')->name('karyawan.approval.overtime.detail');
	Route::post('approval-overtime/proses',  'ApprovalOvertimeController@proses')->name('karyawan.approval.overtime.proses');
	Route::get('approval-overtime-atasan',  'ApprovalOvertimeAtasanController@index')->name('karyawan.approval.overtime-atasan.index');
	Route::get('approval-overtime-atasan/detail/{id}',  'ApprovalOvertimeAtasanController@detail')->name('karyawan.approval.overtime-atasan.detail');
	Route::post('approval-overtime-atasan/proses',  'ApprovalOvertimeAtasanController@proses')->name('karyawan.approval.overtime-atasan.proses');
	Route::get('approval-medical-atasan',  'ApprovalMedicalAtasanController@index')->name('karyawan.approval.medical-atasan.index');
	Route::get('approval-medical-atasan/detail/{id}',  'ApprovalMedicalAtasanController@detail')->name('karyawan.approval.medical-atasan.detail');
	Route::post('approval-medical-atasan/proses',  'ApprovalMedicalAtasanController@proses')->name('karyawan.approval.medical-atasan.proses');
	Route::get('approval-exit-atasan',  'ApprovalExitAtasanController@index')->name('karyawan.approval.exit-atasan.index');
	Route::get('approval-exit-atasan/detail/{id}',  'ApprovalExitAtasanController@detail')->name('karyawan.approval.exit-atasan.detail');
	Route::post('approval-exit-atasan/proses',  'ApprovalExitAtasanController@proses')->name('karyawan.approval.exit-atasan.proses');
	Route::get('karyawanvisit/pict/{visitid}',  'IndexController@getVisitPhotos')->name('karyawan.visit.pictlist');
	Route::get('find', 'IndexController@find')->name('karyawan.karyawan.find');
	Route::get('profile', 'IndexController@profile')->name('karyawan.profile');
	Route::get('ajax-attendance', 'IndexController@ajaxAttendance')->name('karyawan.ajax-attendance');
	Route::get('traning/detail-all/{id}', 'TrainingController@detailAll')->name('karyawan.training.detail-all');
    
    Route::get('news/readmore/{id}',  'IndexController@readmoreNews')->name('karyawan.news.readmore');
	Route::get('news/more', 'IndexController@newsmore')->name('karyawan.news.more');
    Route::get('internal-memo/readmore/{id}',  'IndexController@readmoreInternalMemo')->name('karyawan.internal-memo.readmore');
	Route::get('internal-memo/more', 'IndexController@internalMemoMore')->name('karyawan.internal-memo.more');
	Route::get('download-internal-memo/{id}', 'IndexController@downloadInternalMemo')->name('karyawan.download-internal-memo');
    Route::get('product/readmore/{id}',  'IndexController@readmoreProduct')->name('karyawan.product.readmore');
	Route::get('download-product/{id}', 'IndexController@downloadProduct')->name('karyawan.download-product');
	Route::get('internal-recruitment/more', 'IndexController@internalRecruitmentMore')->name('karyawan.internal-recruitment.more');
    Route::get('internal-recruitment/detail/{id}', 'IndexController@internalRecruitmentDetail')->name('karyawan.internal-recruitment.detail');
	Route::get('notification/more', 'IndexController@notificationMore')->name('karyawan.notification.more');
	Route::get('/birthday/like/{id}', 'BirthdayController@like');
	Route::get('/birthday/unlike/{id}', 'BirthdayController@unlike');
	Route::post('/birthday/comment/{id}', 'BirthdayController@comment');
	Route::get('/birthday/comment/like/{id}', 'BirthdayController@commentLike');
	Route::get('/birthday/comment/unlike/{id}', 'BirthdayController@commentUnlike');
	Route::post('/birthday/comment/reply/{id}', 'BirthdayController@commentReply');

    Route::post('internal-recruitment/apply', 'IndexController@applyRecruitment')->name('karyawan.internal-recruitment.apply');
	Route::resource('request-pay-slip', 'RequestPaySlipController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'karyawan']);
	Route::resource('request-pay-slipnet', 'RequestPaySlipNetController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'karyawan']);
	Route::resource('request-pay-slipgross', 'RequestPaySlipGrossController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'karyawan']);
	Route::get('karyawan/backtoadministrator', 'IndexController@backtoadministrator')->name('karyawan.back-to-administrator');
	Route::get('karyawan/switchtoadministrator', 'IndexController@switchToAdmin')->name('karyawan.switch-to-administrator');

    Route::resource('kpi-item', 'KpiItemManagerController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'karyawan']);
    Route::resource('kpi-survey', 'KpiSurveyManagerController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'karyawan']);
    Route::get('kpi-survey/download',  'KpiSurveyManagerController@download')->name('karyawan.kpi-survey.download');
    Route::get('kpi-survey/download-detail',  'KpiSurveyManagerController@downloadDetail')->name('karyawan.kpi-survey.download-detail');
    Route::get('kpi-survey/download-import',  'KpiSurveyManagerController@downloadImport')->name('karyawan.kpi-survey.download-import');
    Route::post('kpi-survey/import',  'KpiSurveyManagerController@import')->name('karyawan.kpi-survey.import');
    Route::resource('performance-evaluation', 'PerformanceEvaluationController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'karyawan']);

    Route::resource('recruitment-request', 'RecruitmentRequestController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'karyawan']);
    Route::resource('recruitment-application', 'RecruitmentApplicationController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'karyawan']);

    Route::get('recruitment_application/detail-history-internal/{id}', 'RecruitmentApplicationController@getApplicationDetail')->name('recruitment-application.detail-history');
    Route::get('ajax/recruitment_application', 'RecruitmentApplicationController@table')->name('ajax.table.recruitment_application');


    Route::get('ajax/kpi_item', 'KpiItemManagerController@table')->name('ajax.table.kpi_item');
    Route::get('ajax/kpi_surveys', 'KpiSurveyManagerController@table')->name('ajax.table.kpi_survey');
	Route::get('ajax/performance_evaluation', 'PerformanceEvaluationController@table')->name('ajax.table.performance_evaluation');

    Route::get('ajax/recruitment_request', 'RecruitmentRequestController@table')->name('ajax.table.recruitment_request');
});