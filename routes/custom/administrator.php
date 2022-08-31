<?php

/**
 * Administrator Routing
 */
Route::group(['prefix' => 'administrator', 'namespace'=>'Administrator', 'middleware' => ['auth', 'access:1']], function(){
	
//	Route::get('user-login', 'LoginController@user-login')->name('administrator.payroll.detail-history');

	Route::get('/', 'IndexController@index')->name('administrator.dashboard');
	Route::resource('karyawan', 'KaryawanController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
	Route::get('karyawan/{id}/ajax-edit', 'KaryawanController@ajaxEdit');
	Route::get('karyawan/{id}/rejoin', 'KaryawanController@rejoin')->name('administrator.karyawan.rejoin');
	Route::get('karyawan/table', 'KaryawanController@table')->name('administrator.karyawan.table');
	Route::resource('department', 'DepartmentController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
	Route::resource('provinsi', 'ProvinsiController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
	Route::resource('kabupaten', 'KabupatenController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
	Route::resource('kecamatan', 'KecamatanController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
	Route::resource('kelurahan', 'KelurahanController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
	Route::resource('training', 'TrainingController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
	Route::resource('training-type', 'TrainingTypeController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
	Route::resource('cuti', 'CutiController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
	Route::resource('overtime', 'OvertimeController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
	Route::resource('payment-request', 'PaymentRequestController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
	Route::resource('exit-clearance', 'ExitClearanceController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
	Route::resource('exit-interview', 'ExitInterviewController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
	Route::resource('directorate', 'DirectorateController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
	Route::resource('division', 'DivisionController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
	Route::post('check-division-name', 'DivisionController@checkName');
	Route::post('check-division-code', 'DivisionController@checkCode');
	Route::resource('position', 'PositionController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
	Route::post('check-position-name', 'PositionController@checkName');
	Route::post('check-position-code', 'PositionController@checkCode');
	Route::resource('title', 'TitleController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
	Route::post('check-title-name', 'TitleController@checkName');
	Route::post('check-title-code', 'TitleController@checkCode');
    Route::resource('project-setting', 'ProjectSettingController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
	Route::resource('section', 'SectionController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
	Route::resource('overtime', 'OvertimeController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
	Route::resource('cabang', 'CabangController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
	Route::resource('medical', 'MedicalController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
	Route::resource('bank', 'BankController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
	Route::resource('universitas', 'UniversitasController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
	Route::resource('program-studi', 'ProgramStudiController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
	Route::resource('jurusan', 'JurusanController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
	Route::resource('sekolah', 'SekolahController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
	Route::get('grade/check-sub-grade', 'GradeController@checkSubGrade')->name('administrator.grade.checksubgrade');
	Route::resource('grade', 'GradeController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
	Route::post('check-grade-name', 'GradeController@checkName');
	Route::resource('alasan-pengunduran-diri', 'AlasanPengunduranDiriSettingController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
	Route::get('training/detail/{id}',  'TrainingController@detail')->name('administrator.training.detail');
	Route::post('training/proses',  'TrainingController@proses')->name('administrator.training.proses');
	Route::get('training/biaya/{id}', 'TrainingController@biaya')->name('administrator.training.biaya');
	Route::post('training/proses-biaya', 'TrainingController@prosesBiaya')->name('administrator.training.proses-biaya');
	Route::resource('setting-cuti', 'SettingCutiController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
	Route::resource('setting-payment-request', 'SettingPaymentRequestController', ['only'=> ['index','destroy'], 'as' => 'administrator']);
	Route::resource('setting-medical', 'SettingMedicalController', ['only'=> ['index','destroy'], 'as' => 'administrator']);
	Route::resource('setting-overtime', 'SettingOvertimeController', ['only'=> ['index','destroy'], 'as' => 'administrator']);
	Route::resource('setting-overtime-sheet', 'SettingOvertimeSheetController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
	Route::resource('setting-exit', 'SettingExitController', ['only'=> ['index','destroy'], 'as' => 'administrator']);
	Route::resource('setting-training', 'SettingTrainingController', ['only'=> ['index','destroy'], 'as' => 'administrator']);
	Route::resource('setting-master-cuti', 'SettingMasterCutiController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
	Route::post('setting-master-cuti/store-range', 'SettingMasterCutiController@storeRange')->name('administrator.setting-master-cuti.store-range');
    Route::resource('setting-exit-clearance', 'SettingExitClearanceController', ['as' => 'administrator']);
	Route::resource('cuti-bersama', 'CutiBersamaController', ['as' => 'administrator']);
	Route::resource('setting-Visit', 'SettingMasterVisitController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
	Route::resource('setting-recruitment', 'SettingRecruitmentController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
	Route::resource('setting-bank-cv', 'SettingBankCVController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
    Route::get('bank-cv-option-values/{id}', 'SettingBankCVController@indexOption')->name('administrator.bank-cv-option-values.index');
    Route::post('bank-cv-option-values', 'SettingBankCVController@storeOption')->name('administrator.bank-cv-option-values.store');
    Route::patch('bank-cv-option-values/{id}', 'SettingBankCVController@updateOption')->name('administrator.bank-cv-option-values.update');
    Route::delete('bank-cv-option-values/{id}', 'SettingBankCVController@destroyOption')->name('administrator.bank-cv-option-values.destroy');
    Route::get('bank-cv-skill-index', 'SettingBankCVController@indexSkill')->name('administrator.bank-cv-skill-index');
    Route::post('bank-cv-skill-store', 'SettingBankCVController@storeSkill')->name('administrator.bank-cv-skill-store');
    Route::post('bank-cv-skill-destroy', 'SettingBankCVController@destroySkill')->name('administrator.bank-cv-skill-destroy');
    
    Route::resource('setting-timesheet', 'SettingMasterTimesheetController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
	Route::post('setting-timesheet/restore/{id}', 'SettingMasterTimesheetController@restore')->name('administrator.setting-timesheet.restore');
	Route::get('setting-timesheet/create-activity', 'SettingMasterTimesheetController@createActivity')->name('administrator.setting-timesheet.create-activity');
	Route::post('setting-timesheet/store-activity', 'SettingMasterTimesheetController@storeActivity')->name('administrator.setting-timesheet.store-activity');
	Route::get('setting-timesheet/edit-activity/{id}', 'SettingMasterTimesheetController@editActivity')->name('administrator.setting-timesheet.edit-activity');
	Route::put('setting-timesheet/update-activity/{id}', 'SettingMasterTimesheetController@updateActivity')->name('administrator.setting-timesheet.update-activity');
	Route::delete('setting-timesheet/destroy-activity/{id}', 'SettingMasterTimesheetController@destroyActivity')->name('administrator.setting-timesheet.destroy-activity');
	Route::post('setting-timesheet/restore-activity/{id}', 'SettingMasterTimesheetController@restoreActivity')->name('administrator.setting-timesheet.restore-activity');
	Route::get('setting-timesheet/user-list-for-assignment/{id}', 'SettingMasterTimesheetController@userToBeAssigned')->name('administrator.setting-timesheet.user-list-assign');
	Route::post('setting-timesheet/assign-approval', 'SettingMasterTimesheetController@assignApproval')->name('administrator.setting-timesheet.assign-approval');

    Route::get('structure', 'IndexController@structure')->name('administrator.structure');
	Route::resource('setting', 'SettingController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
	Route::resource('setting-approvalLeave', 'SettingApprovalLeaveController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
    Route::get('setting-overtime-sheet/user-list-for-assignment/{type}', 'SettingOvertimeSheetController@userToBeAssigned')->name('administrator.setting-overtime-sheet.user-list-for-assignment');
	Route::post('setting-overtime-sheet/assign-entitle', 'SettingOvertimeSheetController@assignEntitle')->name('administrator.setting-overtime-sheet.assign-entitle');
	Route::post('setting-overtime-sheet/assign-setting', 'SettingOvertimeSheetController@assignSetting')->name('administrator.setting-overtime-sheet.assign-setting');
	Route::get('setting-Visit/user-list-for-assignment/{master_visit_type_id}', 'SettingMasterVisitController@userToBeAssigned')->name('administrator.setting-Visit.user-list-assign');
	Route::post('setting-Visit/assign-visittype', 'SettingMasterVisitController@assignvisittype')->name('administrator.setting-Visit.assign-visittype');
	Route::get('setting-Visit/user-list-for-assignmentpic/{cabangpicmaster_id}', 'SettingMasterVisitController@userToBeAssigned2')->name('administrator.setting-Visit.user-list-assignpic');
	Route::post('setting-Visit/assign-branchpic', 'SettingMasterVisitController@assignbranchpic')->name('administrator.setting-Visit.assign-branchpic');
	Route::get('setting-approvalLeave/indexItem/{id}', 'SettingApprovalLeaveController@indexItem')->name('administrator.setting-approvalLeave.indexItem');
	Route::get('setting-approvalLeave/createItem/{id}', 'SettingApprovalLeaveController@createItem')->name('administrator.setting-approvalLeave.createItem');
	Route::post('setting-approvalLeave/storeItem', 'SettingApprovalLeaveController@storeItem')->name('administrator.setting-approvalLeave.storeItem');
	Route::get('setting-approvalLeave/editItem/{id}', 'SettingApprovalLeaveController@editItem')->name('administrator.setting-approvalLeave.editItem');
	Route::post('setting-approvalLeave/updateItem/{id}', 'SettingApprovalLeaveController@updateItem')->name('administrator.setting-approvalLeave.updateItem');
	Route::post('setting-approvalLeave/destroyItem/{id}', 'SettingApprovalLeaveController@destroyItem')->name('administrator.setting-approvalLeave.destroyItem');
    Route::get('setting-recruitment/user-list-for-assignment/{type}', 'SettingRecruitmentController@userToBeAssigned')->name('administrator.setting-recruitment.user-list-for-assignment');
	Route::post('setting-recruitment/assign-entitle', 'SettingRecruitmentController@assignEntitle')->name('administrator.setting-recruitment.assign-entitle');

	Route::resource('setting-approvalPaymentRequest', 'SettingApprovalPaymentRequestController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
	Route::get('setting-approvalPaymentRequest/indexItem/{id}', 'SettingApprovalPaymentRequestController@indexItem')->name('administrator.setting-approvalPaymentRequest.indexItem');
	Route::get('setting-approvalPaymentRequest/createItem/{id}', 'SettingApprovalPaymentRequestController@createItem')->name('administrator.setting-approvalPaymentRequest.createItem');
	Route::post('setting-approvalPaymentRequest/storeItem', 'SettingApprovalPaymentRequestController@storeItem')->name('administrator.setting-approvalPaymentRequest.storeItem');
	Route::get('setting-approvalPaymentRequest/editItem/{id}', 'SettingApprovalPaymentRequestController@editItem')->name('administrator.setting-approvalPaymentRequest.editItem');
	Route::post('setting-approvalPaymentRequest/updateItem/{id}', 'SettingApprovalPaymentRequestController@updateItem')->name('administrator.setting-approvalPaymentRequest.updateItem');
	Route::post('setting-approvalPaymentRequest/destroyItem/{id}', 'SettingApprovalPaymentRequestController@destroyItem')->name('administrator.setting-approvalPaymentRequest.destroyItem');

	Route::resource('setting-approvalTimesheet', 'SettingApprovalTimesheetController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
	Route::get('setting-approvalTimesheet/indexItem/{id}', 'SettingApprovalTimesheetController@indexItem')->name('administrator.setting-approvalTimesheet.indexItem');
	Route::get('setting-approvalTimesheet/createItem/{id}', 'SettingApprovalTimesheetController@createItem')->name('administrator.setting-approvalTimesheet.createItem');
	Route::post('setting-approvalTimesheet/storeItem', 'SettingApprovalTimesheetController@storeItem')->name('administrator.setting-approvalTimesheet.storeItem');
	Route::get('setting-approvalTimesheet/editItem/{id}', 'SettingApprovalTimesheetController@editItem')->name('administrator.setting-approvalTimesheet.editItem');
	Route::post('setting-approvalTimesheet/updateItem/{id}', 'SettingApprovalTimesheetController@updateItem')->name('administrator.setting-approvalTimesheet.updateItem');
	Route::post('setting-approvalTimesheet/destroyItem/{id}', 'SettingApprovalTimesheetController@destroyItem')->name('administrator.setting-approvalTimesheet.destroyItem');

	Route::resource('setting-approvalOvertime', 'SettingApprovalOvertimeController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
	Route::get('setting-approvalOvertime/indexItem/{id}', 'SettingApprovalOvertimeController@indexItem')->name('administrator.setting-approvalOvertime.indexItem');
	Route::get('setting-approvalOvertime/createItem/{id}', 'SettingApprovalOvertimeController@createItem')->name('administrator.setting-approvalOvertime.createItem');
	Route::post('setting-approvalOvertime/storeItem', 'SettingApprovalOvertimeController@storeItem')->name('administrator.setting-approvalOvertime.storeItem');
	Route::get('setting-approvalOvertime/editItem/{id}', 'SettingApprovalOvertimeController@editItem')->name('administrator.setting-approvalOvertime.editItem');
	Route::post('setting-approvalOvertime/updateItem/{id}', 'SettingApprovalOvertimeController@updateItem')->name('administrator.setting-approvalOvertime.updateItem');
	Route::post('setting-approvalOvertime/destroyItem/{id}', 'SettingApprovalOvertimeController@destroyItem')->name('administrator.setting-approvalOvertime.destroyItem');

	Route::resource('setting-approvalTraining', 'SettingApprovalTrainingController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
	Route::get('setting-approvalTraining/indexItem/{id}', 'SettingApprovalTrainingController@indexItem')->name('administrator.setting-approvalTraining.indexItem');
	Route::get('setting-approvalTraining/createItem/{id}', 'SettingApprovalTrainingController@createItem')->name('administrator.setting-approvalTraining.createItem');
	Route::post('setting-approvalTraining/storeItem', 'SettingApprovalTrainingController@storeItem')->name('administrator.setting-approvalTraining.storeItem');
	Route::get('setting-approvalTraining/editItem/{id}', 'SettingApprovalTrainingController@editItem')->name('administrator.setting-approvalTraining.editItem');
	Route::post('setting-approvalTraining/updateItem/{id}', 'SettingApprovalTrainingController@updateItem')->name('administrator.setting-approvalTraining.updateItem');
	Route::post('setting-approvalTraining/destroyItem/{id}', 'SettingApprovalTrainingController@destroyItem')->name('administrator.setting-approvalTraining.destroyItem');

    Route::resource('setting-approvalRecruitment', 'SettingApprovalRecruitmentRequestController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
    Route::get('setting-approvalRecruitment/indexItem/{id}', 'SettingApprovalRecruitmentRequestController@indexItem')->name('administrator.setting-approvalRecruitment.indexItem');
    Route::get('setting-approvalRecruitment/createItem/{id}', 'SettingApprovalRecruitmentRequestController@createItem')->name('administrator.setting-approvalRecruitment.createItem');
    Route::post('setting-approvalRecruitment/storeItem', 'SettingApprovalRecruitmentRequestController@storeItem')->name('administrator.setting-approvalRecruitment.storeItem');
    Route::get('setting-approvalRecruitment/editItem/{id}', 'SettingApprovalRecruitmentRequestController@editItem')->name('administrator.setting-approvalRecruitment.editItem');
    Route::post('setting-approvalRecruitment/updateItem/{id}', 'SettingApprovalRecruitmentRequestController@updateItem')->name('administrator.setting-approvalRecruitment.updateItem');
    Route::post('setting-approvalRecruitment/destroyItem/{id}', 'SettingApprovalRecruitmentRequestController@destroyItem')->name('administrator.setting-approvalRecruitment.destroyItem');

	Route::resource('setting-approvalCashAdvance', 'SettingApprovalCashAdvanceController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
	Route::get('setting-approvalCashAdvance/indexItem/{id}', 'SettingApprovalCashAdvanceController@indexItem')->name('administrator.setting-approvalCashAdvance.indexItem');
	Route::get('setting-approvalCashAdvance/createItem/{id}', 'SettingApprovalCashAdvanceController@createItem')->name('administrator.setting-approvalCashAdvance.createItem');
	Route::post('setting-approvalCashAdvance/storeItem', 'SettingApprovalCashAdvanceController@storeItem')->name('administrator.setting-approvalCashAdvance.storeItem');
	Route::get('setting-approvalCashAdvance/editItem/{id}', 'SettingApprovalCashAdvanceController@editItem')->name('administrator.setting-approvalCashAdvance.editItem');
	Route::post('setting-approvalCashAdvance/updateItem/{id}', 'SettingApprovalCashAdvanceController@updateItem')->name('administrator.setting-approvalCashAdvance.updateItem');
	Route::post('setting-approvalCashAdvance/destroyItem/{id}', 'SettingApprovalCashAdvanceController@destroyItem')->name('administrator.setting-approvalCashAdvance.destroyItem');

    Route::resource('medical-plafond', 'MedicalPlafondController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
	Route::get('medical-plafond/create-medical-plafond', 'MedicalPlafondController@createMedicalPlafond')->name('administrator.medical-plafond.create-medical-plafond');
	Route::post('medical-plafond/store-medical-plafond', 'MedicalPlafondController@storeMedicalPlafond')->name('administrator.medical-plafond.store-medical-plafond');
	Route::get('medical-plafond/edit-medical-plafond/{id}', 'MedicalPlafondController@editMedicalPlafond')->name('administrator.medical-plafond.edit-medical-plafond');
	Route::post('medical-plafond/update-lmedical-plafond/{id}', 'MedicalPlafondController@updateMedicalPlafond')->name('administrator.medical-plafond.update-medical-plafond');
	Route::get('medical-plafond/destroy-medical-plafond/{id}', 'MedicalPlafondController@deleteMedicalPlafond')->name('administrator.medical-plafond.destroy-medical-plafond');

	
	Route::resource('branch-pic', 'SettingMasterVisitController', ['only'=> ['index','create','store','destroy'], 'as' => 'administrator']);
	Route::get('setting-Visit/create-branch-pic', 'SettingMasterVisitController@createBranchPic')->name('administrator.setting-Visit.create-branch-pic');
	Route::post('setting-Visit/store-branch-pic', 'SettingMasterVisitController@storeBranchPic')->name('administrator.setting-Visit.store-branch-pic');
	Route::get('setting-Visit/destroy-branch-pic/{id}', 'SettingMasterVisitController@destroyBranchPic')->name('administrator.setting-Visit.destroy-branch-pic');

	Route::resource('setting-approvalMedical', 'SettingApprovalMedicalController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
	Route::get('setting-approvalMedical/indexItem/{id}', 'SettingApprovalMedicalController@indexItem')->name('administrator.setting-approvalMedical.indexItem');
	Route::get('setting-approvalMedical/createItem/{id}', 'SettingApprovalMedicalController@createItem')->name('administrator.setting-approvalMedical.createItem');
	Route::post('setting-approvalMedical/storeItem', 'SettingApprovalMedicalController@storeItem')->name('administrator.setting-approvalMedical.storeItem');
	Route::get('setting-approvalMedical/editItem/{id}', 'SettingApprovalMedicalController@editItem')->name('administrator.setting-approvalMedical.editItem');
	Route::post('setting-approvalMedical/updateItem/{id}', 'SettingApprovalMedicalController@updateItem')->name('administrator.setting-approvalMedical.updateItem');
	Route::post('setting-approvalMedical/destroyItem/{id}', 'SettingApprovalMedicalController@destroyItem')->name('administrator.setting-approvalMedical.destroyItem');

	Route::resource('setting-approvalLoan', 'SettingApprovalLoanController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
	Route::get('setting-approvalLoan/indexItem/{id}', 'SettingApprovalLoanController@indexItem')->name('administrator.setting-approvalLoan.indexItem');
	Route::get('setting-approvalLoan/createItem/{id}', 'SettingApprovalLoanController@createItem')->name('administrator.setting-approvalLoan.createItem');
	Route::post('setting-approvalLoan/storeItem', 'SettingApprovalLoanController@storeItem')->name('administrator.setting-approvalLoan.storeItem');
	Route::get('setting-approvalLoan/editItem/{id}', 'SettingApprovalLoanController@editItem')->name('administrator.setting-approvalLoan.editItem');
	Route::post('setting-approvalLoan/updateItem/{id}', 'SettingApprovalLoanController@updateItem')->name('administrator.setting-approvalLoan.updateItem');
	Route::post('setting-approvalLoan/destroyItem/{id}', 'SettingApprovalLoanController@destroyItem')->name('administrator.setting-approvalLoan.destroyItem');

	Route::resource('setting-approvalExit', 'SettingApprovalExitController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
	Route::get('setting-approvalExit/indexItem/{id}', 'SettingApprovalExitController@indexItem')->name('administrator.setting-approvalExit.indexItem');
	Route::get('setting-approvalExit/createItem/{id}', 'SettingApprovalExitController@createItem')->name('administrator.setting-approvalExit.createItem');
	Route::post('setting-approvalExit/storeItem', 'SettingApprovalExitController@storeItem')->name('administrator.setting-approvalExit.storeItem');
	Route::get('setting-approvalExit/editItem/{id}', 'SettingApprovalExitController@editItem')->name('administrator.setting-approvalExit.editItem');
	Route::post('setting-approvalExit/updateItem/{id}', 'SettingApprovalExitController@updateItem')->name('administrator.setting-approvalExit.updateItem');
	Route::post('setting-approvalExit/destroyItem/{id}', 'SettingApprovalExitController@destroyItem')->name('administrator.setting-approvalExit.destroyItem');

	Route::resource('setting-approvalClearance', 'SettingApprovalClearanceController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);

    Route::resource('setting-mobile-attendance', 'SettingMobileAttendanceController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);

	Route::resource('news', 'NewsController', ['only'=> ['index','create','store', 'edit','destroy'], 'as' => 'administrator']);
	Route::post('news/file', 'NewsController@storeFile')->name('administrator.news.store_file');
	Route::post('news/{id}', 'NewsController@update')->name('administrator.news.update', '{id}');
	Route::delete('news/file/destroy/{id}', 'NewsController@deleteFile')->name('administrator.news.file.destroy', '{id}');
	Route::resource('internal-memo', 'InternalMemoController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
	Route::get('internal-memo/download/{id}', 'InternalMemoController@download')->name('administrator.internal-memo.download');
	Route::resource('branch-organisasi', 'BranchOrganisasiController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
	Route::resource('branch-staff', 'BranchStaffController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
	Route::resource('libur-nasional', 'LiburNasionalController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
	Route::resource('plafond-dinas', 'PlafondDinasController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
	Route::resource('position', 'PositionController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
	Route::resource('job-rule', 'JobRuleController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
	Route::post('libur-nasional/import', 'LiburNasionalController@import')->name('administrator.libur-nasional.import');
	Route::post('cabang/import', 'CabangController@import')->name('administrator.cabang.import');
	Route::post('plafond-dinas/import', 'PlafondDinasController@import')->name('administrator.plafond-dinas.import');
	Route::get('plafond-dinas/create-luar-negeri', 'PlafondDinasController@createLuarNegeri')->name('administrator.plafond-dinas.create-luar-negeri');
	Route::post('plafond-dinas/store-luar-negeri', 'PlafondDinasController@storeLuarNegeri')->name('administrator.plafond-dinas.store-luar-negeri');
	Route::get('plafond-dinas/edit-luar-negeri/{id}', 'PlafondDinasController@editLuarNegeri')->name('administrator.plafond-dinas.edit-luar-negeri');
	Route::post('plafond-dinas/update-luar-negeri/{id}', 'PlafondDinasController@updateLuarNegeri')->name('administrator.plafond-dinas.update-luar-negeri');
	Route::get('plafond-dinas/destroy-luar-negeri/{id}', 'PlafondDinasController@deleteLuarNegeri')->name('administrator.plafond-dinas.destroy-luar-negeri');
	Route::post('plafond-dinas/settlement-duration', 'PlafondDinasController@settlementDuration')->name('administrator.plafond-dinas.settlement-duration');
	
	Route::get('branch-organisasi/tree', 'BranchOrganisasiController@tree')->name('administrator.branch-organisasi.tree');
	Route::get('karyawan/delete-cuti/{id}', 'KaryawanController@DeleteCuti')->name('administrator.karyawan.delete-cuti');
	Route::post('karyawan/import', 'KaryawanController@importData')->name('administrator.karyawan.import');
	Route::get('karyawan/preview-import', 'KaryawanController@previewImport')->name('administrator.karyawan.preview-import');
	Route::get('karyawan/delete-temp/{id}', 'KaryawanController@deleteTemp')->name('administrator.karyawan.delete-temp');
	Route::get('karyawan/detail-temp/{id}', 'KaryawanController@detailTemp')->name('administrator.karyawan.detail-temp');
	Route::get('karyawan/import-all', 'KaryawanController@importAll')->name('administrator.karyawan.import-all');
	Route::get('karyawan/print-profile/{id}', 'KaryawanController@printProfile')->name('administrator.karyawan.print-profile');
	Route::get('karyawan/delete-old-user/{id}', 'KaryawanController@deleteOldUser')->name('administrator.karyawan.delete-old-user');
	Route::get('karyawan/downloadExcel','KaryawanController@downloadExcel')->name('administrator.karyawan.downloadExcel');
	Route::get('karyawan/visit-pict/{visitid}', 'KaryawanController@getVisitPhotos')->name('administrator.karyawan.visit-pict');
	Route::post('karyawan', 'KaryawanController@index')->name('administrator.karyawan.index');
	Route::post('karyawan/store', 'KaryawanController@store')->name('administrator.karyawan.store');
	Route::get('karyawan/get-annual/{cuti_id}/{join_date?}', 'KaryawanController@getannualcutikouta')->name('administrator.karyawan.get-annual');
	Route::get('absensi/index', 'AbsensiController@index')->name('administrator.absensi.index');
	Route::get('absensi/import', 'AbsensiController@import')->name('administrator.absensi.import');
	Route::post('absensi/temp-import', 'AbsensiController@tempImport')->name('administrator.absensi.temp-import');
	Route::get('absensi/preview-temp', 'AbsensiController@previewTemp')->name('administrator.absensi.preview-temp');
	Route::get('absensi/import-all', 'AbsensiController@importAll')->name('administrator.absensi.import-all');
	Route::get('absensi/deletenew/{id}', 'AbsensiController@deleteNew')->name('administrator.absensi.deletenew');
	Route::get('absensi/deleteold/{id}', 'AbsensiController@deleteOld')->name('administrator.absensi.deleteold');
	Route::get('absensi/detail/{id}', 'AbsensiController@detail')->name('administrator.absensi.detail');
	Route::post('cuti/batal', 'CutiController@batal')->name('administrator.cuti.batal');
	Route::post('training/batal', 'TrainingController@batal')->name('administrator.training.batal');
	Route::get('cuti/proses/{id}', 'CutiController@proses')->name('administrator.cuti.proses');
	Route::post('cuti/submit-proses', 'CutiController@submitProses')->name('administrator.cuti.submit-proses');
	Route::post('payment-request/batal', 'PaymentRequestController@batal')->name('administrator.payment-request.batal');
	Route::get('exit-inteview/detail/{id}', 'ExitInterviewController@detail')->name('administrator.exit-interview.detail');
	Route::post('exit-interview/proses', 'ExitInterviewController@proses')->name('administrator.exit-interview.proses');
	Route::get('cuti/delete/{id}', 'CutiController@delete')->name('administrator.cuti.delete');
	Route::get('setting-master-cuti/delete/{id}', 'SettingMasterCutiController@delete')->name('administrator.setting-master-cuti.delete');
	// Route::get('setting-Visit/delete/{id}', 'SettingVisitController@delete')->name('administrator.setting-Visit.delete');
	Route::get('product/testDevice', 'ProductController@testSendDevice');
	Route::get('product/testGroup', 'ProductController@testSendGroup');
	Route::resource('product', 'ProductController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
	
    Route::resource('payroll', 'PayrollController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
	Route::get('payroll/index', 'PayrollController@index')->name('administrator.payroll.index');
	Route::post('payroll/index', 'PayrollController@index')->name('administrator.payroll.index');
	Route::get('payroll/detail-history/{id}', 'PayrollController@detailHistory')->name('administrator.payroll.detail-history');
    Route::get('payroll/delete-history/{id}','PayrollController@deleteHistory')->name('administrator.payroll.delete-history');
    Route::get('payroll/import', 'PayrollController@import')->name('administrator.payroll.import');
	Route::get('payroll/download', 'PayrollController@download')->name('administrator.payroll.download');
	Route::get('payroll/delete-earning-payroll/{id}', 'PayrollController@deleteEarningPayroll')->name('administrator.payroll.delete-earning-payroll');
	Route::get('payroll/delete-deduction-payroll/{id}', 'PayrollController@deleteDeductionPayroll')->name('administrator.payroll.delete-deduction-payroll');
	Route::post('payroll/temp-import', 'PayrollController@tempImport')->name('administrator.payroll.temp-import');
    Route::get('payroll/calculate', 'PayrollController@calculate')->name('administrator.payroll.calculate');
	Route::get('payroll/detail/{id}', 'PayrollController@detail')->name('administrator.payroll.detail');
    Route::get('payroll/create-by-payroll-id/{id}', 'PayrollController@createByPayrollId')->name('administrator.payroll.create-by-payroll-id');
	Route::get('payroll/detail-history/{id}', 'PayrollController@detailHistory')->name('administrator.payroll.detail-history');

    Route::resource('payroll-monthly', 'PayrollMonthlyController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
    Route::get('payroll-monthly/index', 'PayrollMonthlyController@index')->name('administrator.payroll-monthly.index');
	Route::post('payroll-monthly/index', 'PayrollMonthlyController@index')->name('administrator.payroll-monthly.index');
	Route::get('payroll-monthly/detail-history/{id}', 'PayrollMonthlyController@detailHistory')->name('administrator.payroll-monthly.detail-history');
    Route::get('payroll-monthly/delete-history/{id}','PayrollMonthlyController@deleteHistory')->name('administrator.payroll-monthly.delete-history');
	Route::get('payroll-monthly/import', 'PayrollMonthlyController@import')->name('administrator.payroll-monthly.import');
	Route::get('payroll-monthly/download', 'PayrollMonthlyController@download')->name('administrator.payroll-monthly.download');
	Route::get('payroll-monthly/delete-earning-payroll/{id}', 'PayrollMonthlyController@deleteEarningPayroll')->name('administrator.payroll-monthly.delete-earning-payroll');
	Route::get('payroll-monthly/delete-deduction-payroll/{id}', 'PayrollMonthlyController@deleteDeductionPayroll')->name('administrator.payroll-monthly.delete-deduction-payroll');
	Route::post('payroll-monthly/temp-import', 'PayrollMonthlyController@tempImport')->name('administrator.payroll-monthly.temp-import');
    Route::get('payroll-monthly/calculate', 'PayrollMonthlyController@calculate')->name('administrator.payroll-monthly.calculate');
	Route::get('payroll-monthly/detail/{id}', 'PayrollMonthlyController@detail')->name('administrator.payroll-monthly.detail');
    Route::get('payroll-monthly/create-by-payroll-id/{id}', 'PayrollMonthlyController@createByPayrollId')->name('administrator.payroll-monthly.create-by-payroll-id');
	Route::get('payroll-monthly/detail-history/{id}', 'PayrollMonthlyController@detailHistory')->name('administrator.payroll-monthly.detail-history');

    // Route::resource('payrollnet', 'PayrollNetController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
	// Route::resource('payrollgross', 'PayrollGrossController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
	// Route::get('payrollnet/import', 'PayrollNetController@import')->name('administrator.payrollnet.import');
	// Route::get('payrollnet/download', 'PayrollNetController@download')->name('administrator.payrollnet.download');
	// Route::post('payrollnet/temp-import', 'PayrollNetController@tempImport')->name('administrator.payrollnet.temp-import');
	// Route::get('payrollgross/import', 'PayrollGrossController@import')->name('administrator.payrollgross.import');
	// Route::get('payrollgross/download', 'PayrollGrossController@download')->name('administrator.payrollgross.download');
	// Route::post('payrollgross/temp-import', 'PayrollGrossController@tempImport')->name('administrator.payrollgross.temp-import');
	
    Route::get('payroll-setting', 'PayrollSettingController@index')->name('administrator.payroll-setting.index');
	Route::get('payroll-setting/delete-ptkp/{id}', 'PayrollSettingController@deletePtkp')->name('administrator.payroll-setting.delete-ptkp');
	Route::get('payroll-setting/add-pph', 'PayrollSettingController@addPPH')->name('administrator.payroll-setting.add-pph');
	Route::get('payroll-setting/edit-pph/{id}', 'PayrollSettingController@editPPH')->name('administrator.payroll-setting.edit-pph');
	Route::post('payroll-setting/store-pph', 'PayrollSettingController@storePPH')->name('administrator.payroll-setting.store-pph');
	Route::post('payroll-setting/update-pph/{id}', 'PayrollSettingController@updatePPH')->name('administrator.payroll-setting.update-pph');
	Route::post('payroll-setting/store-deductions', 'PayrollSettingController@storeDeductions')->name('administrator.payroll-setting.store-deductions');
	Route::post('payroll-setting/store-earnings', 'PayrollSettingController@storeEarnings')->name('administrator.payroll-setting.store-earnings');
	Route::get('payroll-setting/delete-pph/{id}', 'PayrollSettingController@deletePPH')->name('administrator.payroll-setting.delete-pph');
	Route::get('payroll-setting/delete-others/{id}', 'PayrollSettingController@deleteOthers')->name('administrator.payroll-setting.delete-others');
	Route::get('payroll-setting/add-others', 'PayrollSettingController@addOthers')->name('administrator.payroll-setting.add-others');
	Route::get('payroll-setting/edit-others/{id}', 'PayrollSettingController@editOthers')->name('administrator.payroll-setting.edit-others');
    Route::post('payroll-setting/update-others/{id}', 'PayrollSettingController@updateOthers')->name('administrator.payroll-setting.update-others');

	Route::get('payroll-setting/add-country', 'PayrollSettingController@addCountry')->name('administrator.payroll-setting.add-country');
	Route::post('payroll-setting/store-country', 'PayrollSettingController@storeCountry')->name('administrator.payroll-setting.store-country');
	Route::get('payroll-setting/edit-country/{id}', 'PayrollSettingController@editCountry')->name('administrator.payroll-setting.edit-country');
	Route::post('payroll-setting/update-country/{id}', 'PayrollSettingController@updateCountry')->name('administrator.payroll-setting.update-country');
    Route::delete('payroll-setting/delete-country/{id}', 'PayrollSettingController@deleteCountry')->name('administrator.payroll-setting.delete-country');
    Route::post('payroll-setting/import-country', 'PayrollSettingController@importCountry')->name('administrator.payroll-setting.import-country');

	Route::get('payroll-setting/edit-npwp/{id}', 'PayrollSettingController@editNpwp')->name('administrator.payroll-setting.edit-npwp');
	Route::post('payroll-setting/store-npwp', 'PayrollSettingController@storeNpwp')->name('administrator.payroll-setting.store-npwp');
	Route::post('payroll-setting/store-cycle', 'PayrollSettingController@storeCycle')->name('administrator.payroll-setting.store-cycle');
	Route::post('payroll-setting/store-lock', 'PayrollSettingController@storeLock')->name('administrator.payroll-setting.store-lock');
	Route::post('payroll-setting/store-prorate', 'PayrollSettingController@storeProrate')->name('administrator.payroll-setting.store-prorate');
	Route::post('payroll-setting/update-npwp/{id}', 'PayrollSettingController@updateNpwp')->name('administrator.payroll-setting.update-npwp');

    Route::get('payroll-setting/add-payroll-cycle', 'PayrollSettingController@addPayrollCycle')->name('administrator.payroll-setting.add-payroll-cycle');
	Route::post('payroll-setting/store-payroll-cycle', 'PayrollSettingController@storePayrollCycle')->name('administrator.payroll-setting.store-payroll-cycle');
	Route::get('payroll-setting/edit-payroll-cycle/{id}', 'PayrollSettingController@editPayrollCycle')->name('administrator.payroll-setting.edit-payroll-cycle');
	Route::post('payroll-setting/update-payroll-cycle/{id}', 'PayrollSettingController@updatePayrollCycle')->name('administrator.payroll-setting.update-payroll-cycle');
    Route::delete('payroll-setting/delete-payroll-cycle/{id}', 'PayrollSettingController@deletePayrollCycle')->name('administrator.payroll-setting.delete-payroll-cycle');
    Route::post('payroll-setting/import-payroll-cycle', 'PayrollSettingController@importPayrollCycle')->name('administrator.payroll-setting.import-payroll-cycle');
    Route::get('payroll-setting/user-list-for-assignment-payroll-cycle', 'PayrollSettingController@userToBeAssignedPayrollCycle')->name('administrator.payroll-setting.user-list-for-assignment-payroll-cycle');
	Route::post('payroll-setting/assign-payroll-cycle', 'PayrollSettingController@assignPayrollCycle')->name('administrator.payroll-setting.assign-payroll-cycle');

    Route::get('payroll-setting/add-attendance-cycle', 'PayrollSettingController@addAttendanceCycle')->name('administrator.payroll-setting.add-attendance-cycle');
	Route::post('payroll-setting/store-attendance-cycle', 'PayrollSettingController@storeAttendanceCycle')->name('administrator.payroll-setting.store-attendance-cycle');
	Route::get('payroll-setting/edit-attendance-cycle/{id}', 'PayrollSettingController@editAttendanceCycle')->name('administrator.payroll-setting.edit-attendance-cycle');
	Route::post('payroll-setting/update-attendance-cycle/{id}', 'PayrollSettingController@updateAttendanceCycle')->name('administrator.payroll-setting.update-attendance-cycle');
    Route::delete('payroll-setting/delete-attendance-cycle/{id}', 'PayrollSettingController@deleteAttendanceCycle')->name('administrator.payroll-setting.delete-attendance-cycle');
    Route::post('payroll-setting/import-attendance-cycle', 'PayrollSettingController@importAttendanceCycle')->name('administrator.payroll-setting.import-attendance-cycle');
    Route::get('payroll-setting/user-list-for-assignment-attendance-cycle', 'PayrollSettingController@userToBeAssignedAttendanceCycle')->name('administrator.payroll-setting.user-list-for-assignment-attendance-cycle');
	Route::post('payroll-setting/assign-attendance-cycle', 'PayrollSettingController@assignAttendanceCycle')->name('administrator.payroll-setting.assign-attendance-cycle');

    Route::get('payroll-setting/add-umr', 'PayrollSettingController@addUMR')->name('administrator.payroll-setting.add-umr');
	Route::post('payroll-setting/store-umr', 'PayrollSettingController@storeUMR')->name('administrator.payroll-setting.store-umr');
	Route::get('payroll-setting/edit-umr/{id}', 'PayrollSettingController@editUMR')->name('administrator.payroll-setting.edit-umr');
	Route::post('payroll-setting/update-umr/{id}', 'PayrollSettingController@updateUMR')->name('administrator.payroll-setting.update-umr');
    Route::delete('payroll-setting/delete-umr/{id}', 'PayrollSettingController@deleteUMR')->name('administrator.payroll-setting.delete-umr');
    Route::post('payroll-setting/import-umr', 'PayrollSettingController@importUMR')->name('administrator.payroll-setting.import-umr');
    Route::get('payroll-setting/user-list-for-assignment', 'PayrollSettingController@userToBeAssigned')->name('administrator.payroll-setting.user-list-for-assignment');
	Route::post('payroll-setting/assign-umr', 'PayrollSettingController@assignUMR')->name('administrator.payroll-setting.assign-umr');

	Route::get('payroll-setting/edit-ptkp/{id}', 'PayrollSettingController@editPtkp')->name('administrator.payroll-setting.edit-ptkp');
	Route::get('payroll-setting/delete-earnings/{id}', 'PayrollSettingController@deleteEarnings')->name('administrator.payroll-setting.delete-earnings');
	Route::get('payroll-setting/delete-deductions/{id}', 'PayrollSettingController@deleteDeductions')->name('administrator.payroll-setting.delete-deductions');
	Route::post('payroll-setting/update-ptkp/{id}', 'PayrollSettingController@updatePtkp')->name('administrator.payroll-setting.update-ptkp');
	Route::post('payroll-setting/store-others', 'PayrollSettingController@storeOthers')->name('administrator.payroll-setting.store-others');
	Route::post('payroll-setting/store-general', 'PayrollSettingController@storeGeneral')->name('administrator.payroll-setting.store-general');
	
	// Route::get('payrollnet/calculate', 'PayrollNetController@calculate')->name('administrator.payrollnet.calculate');
	// Route::get('payrollnet/detail/{id}', 'PayrollNetController@detail')->name('administrator.payrollnet.detail');
	// Route::get('payrollgross/calculate', 'PayrollGrossController@calculate')->name('administrator.payrollgross.calculate');
	// Route::get('payrollgross/detail/{id}', 'PayrollGrossController@detail')->name('administrator.payrollgross.detail');
	Route::resource('asset', 'AssetController', ['only'=> ['index','create','store', 'show', 'edit','destroy','update'], 'as' => 'administrator']);
	Route::get('asset-setting', 'AssetSettingController@index')->name('administrator.asset-setting.index');
	Route::post('asset-setting', 'AssetSettingController@store')->name('administrator.asset-setting.store');

    Route::resource('loan-setting', 'LoanSettingController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
    
    Route::get('loan-setting/add-purpose', 'LoanSettingController@addPurpose')->name('administrator.loan-setting.add-purpose');
	Route::post('loan-setting/store-purpose', 'LoanSettingController@storePurpose')->name('administrator.loan-setting.store-purpose');
	Route::get('loan-setting/edit-purpose/{id}', 'LoanSettingController@editPurpose')->name('administrator.loan-setting.edit-purpose');
	Route::put('loan-setting/update-purpose/{id}', 'LoanSettingController@updatePurpose')->name('administrator.loan-setting.update-purpose');
    Route::delete('loan-setting/delete-purpose/{id}', 'LoanSettingController@deletePurpose')->name('administrator.loan-setting.delete-purpose');
    
    Route::get('loan-setting/add-plafond', 'LoanSettingController@addPlafond')->name('administrator.loan-setting.add-plafond');
	Route::post('loan-setting/store-plafond', 'LoanSettingController@storePlafond')->name('administrator.loan-setting.store-plafond');
	Route::get('loan-setting/edit-plafond/{id}', 'LoanSettingController@editPlafond')->name('administrator.loan-setting.edit-plafond');
	Route::put('loan-setting/update-plafond/{id}', 'LoanSettingController@updatePlafond')->name('administrator.loan-setting.update-plafond');
    Route::delete('loan-setting/delete-plafond/{id}', 'LoanSettingController@deletePlafond')->name('administrator.loan-setting.delete-plafond');
    
    Route::get('loan-setting/add-rate', 'LoanSettingController@addRate')->name('administrator.loan-setting.add-rate');
	Route::post('loan-setting/store-rate', 'LoanSettingController@storeRate')->name('administrator.loan-setting.store-rate');
	Route::get('loan-setting/edit-rate/{id}', 'LoanSettingController@editRate')->name('administrator.loan-setting.edit-rate');
	Route::put('loan-setting/update-rate/{id}', 'LoanSettingController@updateRate')->name('administrator.loan-setting.update-rate');
    Route::delete('loan-setting/delete-rate/{id}', 'LoanSettingController@deleteRate')->name('administrator.loan-setting.delete-rate');
    
    Route::get('loan-setting/add-asset', 'LoanSettingController@addAsset')->name('administrator.loan-setting.add-asset');
	Route::post('loan-setting/store-asset', 'LoanSettingController@storeAsset')->name('administrator.loan-setting.store-asset');
	Route::get('loan-setting/edit-asset/{id}', 'LoanSettingController@editAsset')->name('administrator.loan-setting.edit-asset');
	Route::put('loan-setting/update-asset/{id}', 'LoanSettingController@updateAsset')->name('administrator.loan-setting.update-asset');
    Route::delete('loan-setting/delete-asset/{id}', 'LoanSettingController@deleteAsset')->name('administrator.loan-setting.delete-asset');
    
    Route::resource('asset', 'AssetController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
	Route::resource('asset-tracking', 'AssetTrackingController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
	Route::resource('asset-type', 'AssetTypeController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
	Route::get('setting', 'IndexController@setting')->name('administrator.setting.index');
	Route::post('karyawan/upload-dokument-file', 'KaryawanController@uploadDokumentFile')->name('administrator.karyawan.upload-dokument-file');
	Route::post('karyawan/generate-dokument-file', 'KaryawanController@generateDocumentFile')->name('administrator.karyawan.generate-dokument-file');
	Route::get('karyawan/print-payslip/{id}', 'KaryawanController@printPayslip')->name('administrator.karyawan.print-payslip');
    Route::post('request-pay-slip/index', 'RequestPaySlipController@index')->name('administrator.request-pay-slip.index');
	Route::get('request-pay-slip', 'RequestPaySlipController@index')->name('administrator.request-pay-slip.index');
	Route::get('request-pay-slip/index', 'RequestPaySlipController@index')->name('administrator.request-pay-slip.index');
	Route::get('request-pay-slip/proses/{id}', 'RequestPaySlipController@proses')->name('administrator.request-pay-slip.proses');
	Route::post('request-pay-slip/submit/{id}', 'RequestPaySlipController@submit')->name('administrator.request-pay-slip.submit');
	Route::get('karyawan/print-payslipnet/{id}', 'KaryawanController@printPayslipNet')->name('administrator.karyawan.print-payslipnet');
	
	// Route::get('request-pay-slipnet', 'RequestPaySlipNetController@index')->name('administrator.request-pay-slipnet.index');
	// Route::get('request-pay-slipnet/proses/{id}', 'RequestPaySlipNetController@proses')->name('administrator.request-pay-slipnet.proses');
	// Route::post('request-pay-slipnet/submit/{id}', 'RequestPaySlipNetController@submit')->name('administrator.request-pay-slipnet.submit');
	// Route::get('karyawan/print-payslipgross/{id}', 'KaryawanController@printPayslipGross')->name('administrator.karyawan.print-payslipgross');
	// Route::get('request-pay-slipgross', 'RequestPaySlipGrossController@index')->name('administrator.request-pay-slipgross.index');
	// Route::get('request-pay-slipgross/proses/{id}', 'RequestPaySlipGrossController@proses')->name('administrator.request-pay-slipgross.proses');
	// Route::post('request-pay-slipgross/submit/{id}', 'RequestPaySlipGrossController@submit')->name('administrator.request-pay-slipgross.submit');
	Route::get('karyawan/delete-dependent/{id}', 'KaryawanController@deleteDependent')->name('administrator.karyawan.delete-dependent');
	Route::get('karyawan/delete-education/{id}', 'KaryawanController@deleteEducation')->name('administrator.karyawan.delete-education');
	Route::get('karyawan/delete-certification/{id}', 'KaryawanController@deleteCertification')->name('administrator.karyawan.delete-certification');
	Route::get('karyawan/delete-inventaris/{id}', 'KaryawanController@deleteInventaris')->name('administrator.karyawan.delete-inventaris');
	Route::get('karyawan/delete-inventaris-mobil/{id}', 'KaryawanController@deleteInventarisMobil')->name('administrator.karyawan.delete-inventaris-mobil');
	Route::get('karyawan/delete-inventaris-lainnya/{id}', 'KaryawanController@deleteInventarisLainnya')->name('administrator.karyawan.delete-inventaris-lainnya');
	Route::get('karyawan/delete-contract/{id}', 'KaryawanController@deleteContract')->name('administrator.karyawan.delete-contract');
	Route::resource('empore-direktur', 'EmporeDirekturController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
	Route::resource('empore-manager', 'EmporeManagerController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
	Route::resource('empore-staff', 'EmporeStaffController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
	// Route::get('payrollnet/index', 'PayrollNetController@index')->name('administrator.payrollnet.index');
	// Route::post('payrollnet/index', 'PayrollNetController@index')->name('administrator.payrollnet.index');
	// Route::get('payrollgross/index', 'PayrollGrossController@index')->name('administrator.payrollgross.index');
	// Route::post('payrollgross/index', 'PayrollGrossController@index')->name('administrator.payrollgross.index');
	Route::get('karyawan/autologin/{id}', 'KaryawanController@autologin')->name('administrator.karyawan.autologin');
	Route::get('profile', 'IndexController@profile')->name('administrator.profile');
	Route::post('update-profile', 'IndexController@updateProfile')->name('administrator.update-profile');
	//custom
    Route::get('leaveCustom', 'LeaveCustomController@index')->name('administrator.leaveCustom');
	Route::post('leaveCustom/index', 'LeaveCustomController@index')->name('administrator.leaveCustom.index');
	Route::get('leaveCustom/index', 'LeaveCustomController@index')->name('administrator.leaveCustom.index');
	Route::get('leaveCustom/proses/{id}', 'LeaveCustomController@proses')->name('administrator.leaveCustom.proses');
	
	Route::post('paymentRequestCustom/index', 'PaymentRequestCustomController@index')->name('administrator.paymentRequestCustom.index');
	Route::get('paymentRequestCustom', 'PaymentRequestCustomController@index')->name('administrator.paymentRequestCustom.index');
	Route::get('paymentRequestCustom/index', 'PaymentRequestCustomController@index')->name('administrator.paymentRequestCustom.index');
	Route::get('paymentRequestCustom/proses/{id}', 'PaymentRequestCustomController@proses')->name('administrator.paymentRequestCustom.proses');
	Route::post('paymentRequestCustom/transfer/{id}',  'PaymentRequestCustomController@transfer')->name('administrator.paymentRequestCustom.transfer');

    Route::get('timesheetCustom', 'TimesheetCustomController@index')->name('administrator.timesheetCustom');
	Route::post('timesheetCustom/index', 'TimesheetCustomController@index')->name('administrator.timesheetCustom.index');
	Route::get('timesheetCustom/index', 'TimesheetCustomController@index')->name('administrator.timesheetCustom.index');
	Route::get('timesheetCustom/proses/{id}', 'TimesheetCustomController@proses')->name('administrator.timesheetCustom.proses');

	Route::post('overtimeCustom/index', 'OvertimeCustomController@index')->name('administrator.overtimeCustom.index');
	Route::get('overtimeCustom', 'OvertimeCustomController@index')->name('administrator.overtimeCustom.index');
	Route::get('overtimeCustom/index', 'OvertimeCustomController@index')->name('administrator.overtimeCustom.index');
	Route::get('overtimeCustom/proses/{id}', 'OvertimeCustomController@proses')->name('administrator.overtimeCustom.proses');
	Route::get('overtimeCustom/claim/{id}', 'OvertimeCustomController@claim')->name('administrator.overtimeCustom.claim');

	Route::post('trainingCustom/index', 'TrainingCustomController@index')->name('administrator.trainingCustom.index');
	Route::get('trainingCustom', 'TrainingCustomController@index')->name('administrator.trainingCustom.index');
	Route::get('trainingCustom/index', 'TrainingCustomController@index')->name('administrator.trainingCustom.index');
	Route::get('trainingCustom/proses/{id}', 'TrainingCustomController@proses')->name('administrator.trainingCustom.proses');
	Route::get('trainingCustom/claim/{id}', 'TrainingCustomController@claim')->name('administrator.trainingCustom.claim');

	Route::get('medicalCustom', 'MedicalCustomController@index')->name('administrator.medicalCustom');
	Route::post('medicalCustom/index', 'MedicalCustomController@index')->name('administrator.medicalCustom.index');
	Route::get('medicalCustom/index', 'MedicalCustomController@index')->name('administrator.medicalCustom.index');
	Route::get('medicalCustom/proses/{id}', 'MedicalCustomController@proses')->name('administrator.medicalCustom.proses');

    Route::get('loan', 'LoanController@index')->name('administrator.loan');
    Route::put('loan/update/{id}', 'LoanController@update')->name('administrator.loan.update');
	Route::post('loan/index', 'LoanController@index')->name('administrator.loan.index');
	Route::get('loan/index', 'LoanController@index')->name('administrator.loan.index');
	Route::get('loan/proses/{id}', 'LoanController@proses')->name('administrator.loan.proses');
    Route::get('loan/table/{id}',  'LoanController@table')->name('administrator.loan.table');

    Route::get('loan-payment', 'LoanPaymentController@index')->name('administrator.loan-payment');
    Route::put('loan-payment/update/{id}', 'LoanPaymentController@update')->name('administrator.loan-payment.update');
	Route::post('loan-payment/index', 'LoanPaymentController@index')->name('administrator.loan-payment.index');
	Route::get('loan-payment/index', 'LoanPaymentController@index')->name('administrator.loan-payment.index');
	Route::get('loan-payment/proses/{id}', 'LoanPaymentController@proses')->name('administrator.loan-payment.proses');

	Route::post('exitCustom/index', 'ExitInterviewClearanceCustomController@index')->name('administrator.exitCustom.index');
	Route::get('exitCustom', 'ExitInterviewClearanceCustomController@index')->name('administrator.exitCustom.index');
	Route::get('exitCustom/index', 'ExitInterviewClearanceCustomController@index')->name('administrator.exitCustom.index');
	Route::get('exitCustom/detail/{id}', 'ExitInterviewClearanceCustomController@detail')->name('administrator.exitCustom.detail');
	Route::get('exitCustom/clearance/{id}', 'ExitInterviewClearanceCustomController@clearance')->name('administrator.exitCustom.clearance');
	

	Route::post('cuti/index', 'CutiController@index')->name('administrator.cuti.index');
	Route::get('cuti/index', 'CutiController@index')->name('administrator.cuti.index');
	Route::post('payment-request/index', 'PaymentRequestController@index')->name('administrator.payment-request.index');
	Route::get('payment-request/index', 'PaymentRequestController@index')->name('administrator.payment-request.index');
	Route::post('medical-reimbursement/index', 'MedicalController@index')->name('administrator.medical-reimbursement.index');
	Route::get('medical-reimbursement/index', 'MedicalController@index')->name('administrator.medical-reimbursement.index');
	Route::post('overtime/index', 'OvertimeController@index')->name('administrator.overtime.index');
	Route::get('overtime/index', 'OvertimeController@index')->name('administrator.overtime.index');
	Route::post('training/index', 'TrainingController@index')->name('administrator.training.index');
	Route::get('training/index', 'TrainingController@index')->name('administrator.training.index');
	Route::post('exit-interview/index', 'ExitInterviewController@index')->name('administrator.exit-interview.index');
	Route::get('exit-interview/index', 'ExitInterviewController@index')->name('administrator.exit-interview.index');
	Route::get('setting/general', 'SettingController@index')->name('administrator.setting.general');
	Route::get('setting/email', 'SettingController@email')->name('administrator.setting.email');
	Route::get('setting/contract-email', 'SettingController@contractEmail')->name('administrator.setting.contract-email');
	Route::get('organization-structure-custom', 'StructureOrganizationCustomController@index')->name('administrator.organization-structure-custom.index');
    Route::get('organization-structure-custom/export', 'StructureOrganizationCustomController@export')->name('administrator.organization-structure-custom.export');
	Route::get('organization-structure-custom/{id}', 'StructureOrganizationCustomController@show')->name('administrator.organization-structure-custom.show');
	Route::get('organization-structure-custom/delete/{id}', 'StructureOrganizationCustomController@delete')->name('administrator.organization-structure-custom.delete');
	Route::get('setting/backup', 'SettingController@backup')->name('administrator.setting.backup');
	Route::post('setting/backup-save', 'SettingController@backupSave')->name('administrator.setting.backup-save');
	Route::post('setting/backup-delete',  'SettingController@backupDelete')->name('administrator.setting.backup-delete');
	Route::post('setting/backup-get',  'SettingController@backupGet')->name('administrator.setting.backup-get');
	Route::post('setting/save','SettingController@save')->name('administrator.setting.save');
	Route::get('setting/rollback','SettingController@rollback')->name('administrator.setting.rollback');
	Route::post('setting/email-save', 'SettingController@emailSave')->name('administrator.setting.email-save');
	Route::post('setting/email-test-send', 'SettingController@emailTestSend')->name('administrator.setting.email-test-send');
    Route::post('setting/contract-email-save', 'SettingController@contractEmailSave')->name('administrator.setting.contract-email-save');
	Route::post('setting/contract-email-test-send', 'SettingController@contractEmailTestSend')->name('administrator.setting.contract-email-test-send');
    Route::get('setting/user-list-for-assignment/{type}', 'SettingController@userToBeAssigned')->name('administrator.setting.user-list-for-assignment');
	Route::post('setting/assign-entitle', 'SettingController@assignEntitle')->name('administrator.setting.assign-entitle');
	Route::post('organization-structure-custom/store', 'StructureOrganizationCustomController@store')->name('administrator.organization-structure-custom.store');
	Route::post('organization-structure-custom/update', 'StructureOrganizationCustomController@update')->name('administrator.organization-structure-custom.update');
	Route::post('karyawan/send-pay-slip', 'KaryawanController@sendPaySlip')->name('administrator.karyawan.send-pay-slip');
	Route::post('setting/store-backup-schedule', 'SettingController@storeBackupSchedule')->name('administrator.setting.store-backup-schedule');
	Route::get('setting/delete-backup-schedule/{id}', 'SettingController@deleteBackupSchedule')->name('administrator.setting.delete-backup-schedule');
	
	Route::resource('leave', 'LeaveController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
	Route::get('approval-leave-custom',  'ApprovalLeaveCustomController@index')->name('administrator.approval.leave-custom.index');
	Route::get('approval-leave-custom/detail/{id}',  'ApprovalLeaveCustomController@detail')->name('administrator.approval.leave-custom.detail');
	Route::post('approval-leave-custom/proses',  'ApprovalLeaveCustomController@proses')->name('administrator.approval.leave-custom.proses');

	Route::resource('payment-request-custom', 'PaymentRequestKaryawanCustomController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
	Route::get('approval-payment-request-custom',  'ApprovalPaymentRequestCustomController@index')->name('administrator.approval.payment-request-custom.index');
	Route::get('approval-payment-request-custom/detail/{id}',  'ApprovalPaymentRequestCustomController@detail')->name('administrator.approval.payment-request-custom.detail');
	Route::post('approval-payment-request-custom/proses',  'ApprovalPaymentRequestCustomController@proses')->name('administrator.approval.payment-request-custom.proses');

	Route::resource('overtime-custom', 'OvertimeKaryawanCustomController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
	Route::get('overtime-custom/claim/{id}',  'OvertimeCustomController@claim')->name('administrator.overtime-custom.claim');
	Route::post('overtime-custom/prosesclaim',  'OvertimeCustomController@prosesclaim')->name('administrator.overtime-custom.prosesclaim');
	Route::get('approval-overtime-custom',  'ApprovalOvertimeCustomController@index')->name('administrator.approval.overtime-custom.index');
	Route::get('approval-overtime-custom/detail/{id}',  'ApprovalOvertimeCustomController@detail')->name('administrator.approval.overtime-custom.detail');
	Route::post('approval-overtime-custom/proses',  'ApprovalOvertimeCustomController@proses')->name('administrator.approval.overtime-custom.proses');
	Route::get('approval-overtime-custom/claim/{id}',  'ApprovalOvertimeCustomController@claim')->name('administrator.approval.overtime-custom.claim');
	Route::post('approval-overtime-custom/prosesClaim',  'ApprovalOvertimeCustomController@prosesClaim')->name('administrator.approval.overtime-custom.prosesClaim');

	Route::resource('training-custom', 'TrainingKaryawanCustomController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
	Route::get('training-custom/claim/{id}',  'TrainingCustomController@claim')->name('administrator.training-custom.claim');
	Route::post('training-custom/prosesclaim',  'TrainingCustomController@prosesclaim')->name('administrator.training-custom.prosesclaim');
	Route::get('approval-training-custom',  'ApprovalTrainingCustomController@index')->name('administrator.approval.training-custom.index');
	Route::get('approval-training-custom/detail/{id}',  'ApprovalTrainingCustomController@detail')->name('administrator.approval.training-custom.detail');
	Route::post('approval-training-custom/proses',  'ApprovalTrainingCustomController@proses')->name('administrator.approval.training-custom.proses');
	Route::get('approval-training-custom/claim/{id}',  'ApprovalTrainingCustomController@claim')->name('administrator.approval.training-custom.claim');
	Route::post('approval-training-custom/prosesClaim',  'ApprovalTrainingCustomController@prosesClaim')->name('administrator.approval.training-custom.prosesClaim');

	Route::resource('medical-custom', 'MedicalKaryawanCustomController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
	Route::get('approval-medical-custom',  'ApprovalMedicalCustomController@index')->name('administrator.approval.medical-custom.index');
	Route::get('approval-medical-custom/detail/{id}',  'ApprovalMedicalCustomController@detail')->name('administrator.approval.medical-custom.detail');
	Route::post('approval-medical-custom/proses',  'ApprovalMedicalCustomController@proses')->name('administrator.approval.medical-custom.proses');

	Route::resource('exit-custom', 'ExitInterviewKaryawanCustomController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
//	Route::get('exit-custom/clearance/{id}',  'ExitInterviewCustomController@clearance')->name('administrator.exit-custom.clearance');
//	Route::post('exit-custom/prosesclearance',  'ExitInterviewCustomController@prosesclearance')->name('administrator.exit-custom.prosesclearance');
	Route::get('approval-exit-custom',  'ApprovalExitInterviewCustomController@index')->name('administrator.approval.exit-custom.index');
	Route::get('approval-exit-custom/detail/{id}',  'ApprovalExitInterviewCustomController@detail')->name('administrator.approval.exit-custom.detail');
	Route::post('approval-exit-custom/proses',  'ApprovalExitInterviewCustomController@proses')->name('administrator.approval.exit-custom.proses');

	Route::get('approval-clearance-custom', 'ApprovalExitKaryawanClearanceCustomController@index')->name('administrator.approval.clearance-custom.index');
//	Route::get('approval-clearance-custom/detail/{id}', 'ApprovalExitClearanceCustomController@detail')->name('administrator.approval.clearance-custom.detail');
//	Route::post('approval-clearance-custom/proses', 'ApprovalExitClearanceCustomController@proses')->name('administrator.approval.clearance-custom.proses');

	//cash advance
	Route::post('approval-cash-advance', 'ApprovalCashAdvanceController@index')->name('administrator.approval.cash-advance.index');
	Route::get('approval-cash-advance',  'ApprovalCashAdvanceController@index')->name('administrator.approval.cash-advance.index');
	Route::get('approval-cash-advance/detail/{id}',  'ApprovalCashAdvanceController@detail')->name('administrator.approval.cash-advance.detail');
	Route::post('approval-cash-advance/transfer/{id}',  'ApprovalCashAdvanceController@transfer')->name('administrator.approval.cash-advance.transfer');
	Route::get('approval-cash-advance/claim/{id}',  'ApprovalCashAdvanceController@claim')->name('administrator.approval.cash-advance.claim');
	Route::post('approval-cash-advance/transferClaim/{id}',  'ApprovalCashAdvanceController@transferClaim')->name('administrator.approval.cash-advance.transferClaim');
	Route::post('approval-cash-advance/prosesClaim',  'ApprovalCashAdvanceController@prosesClaim')->name('administrator.approval.cash-advance.prosesClaim');
	Route::get('transfer-setting', 'TransferSettingController@index')->name('administrator.transfer-setting.index');
	Route::post('transfer-setting', 'TransferSettingController@store')->name('administrator.transfer-setting.store');
	Route::delete('transfer-setting/{id}', 'TransferSettingController@destroy')->name('administrator.transfer-setting.destroy');


	Route::resource('request-pay-slip-karyawan', 'RequestPaySlipKaryawanController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);

    Route::get('administrator/switchtoemployee', 'IndexController@switchToEmployee')->name('administrator.switch-to-employee');



    Route::resource('kpi-item', 'KpiItemController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
    Route::resource('kpi-survey', 'KpiSurveyController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
    Route::post('kpi-survey/add-employee',  'KpiSurveyController@addEmployee')->name('kpi-survey.add-employee');
    Route::get('kpi-survey/download',  'KpiSurveyController@download')->name('kpi-survey.download');
    Route::get('kpi-survey/download-detail',  'KpiSurveyController@downloadDetail')->name('kpi-survey.download-detail');
    Route::get('ajax/kpi_period', 'SettingPerformanceController@table')->name('ajax.table.kpi_period');
    Route::resource('setting-performance', 'SettingPerformanceController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
    Route::get('setting-performance/lock/{id}',  'SettingPerformanceController@lock')->name('setting-performance.lock');
    Route::get('ajax/kpi_item', 'KpiItemController@table')->name('ajax.table.kpi_item.admin');
	Route::get('ajax/kpi_surveys', 'KpiSurveyController@table')->name('ajax.table.kpi_survey.admin');

    Route::resource('job-category', 'JobCategoryController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
    Route::get('job-category/get/{id}', 'JobCategoryController@get')->name('job-category.get');
    Route::get('ajax/job_category', 'JobCategoryController@table')->name('ajax.table.job_category');
    Route::resource('recruitment-request', 'RecruitmentRequestController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
    Route::get('ajax/recruitment_request', 'RecruitmentRequestController@table')->name('ajax.table.recruitment_request.admin');
    Route::put('recruitment-request/update-post/{id}', 'RecruitmentRequestController@updatePost')->name('administrator.recruitment-request.update-post');

    Route::resource('remote-attendance', 'RemoteAttendanceController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
    Route::get('ajax/remote-attendance', 'RemoteAttendanceController@table')->name('ajax.table.remote_attendance');

    Route::resource('recruitment', 'RecruitmentController', ['only'=> ['index','create','store', 'edit','destroy','update'], 'as' => 'administrator']);
    Route::get('recruitment/{id}', 'RecruitmentController@detail')->name('administrator.recruitment.detail');

	Route::resource('bank-cv', 'BankCvController', ['only'=> ['index','create','store','edit','destroy','update'], 'as' => 'administrator']);
	Route::get('bank-cv/table', 'BankCvController@table')->name('administrator.bank-cv.table');
	Route::post('bank-cv/import', 'BankCvController@import')->name('administrator.bank-cv.import');
    Route::get('bank-cv/index', 'BankCvController@index')->name('administrator.bank-cv.index');
	Route::post('bank-cv/index', 'BankCvController@index')->name('administrator.bank-cv.index');
	Route::get('bank-cv/download', 'BankCvController@download')->name('administrator.bank-cv.download');
	Route::get('bank-cv/tag', 'BankCvController@tag')->name('administrator.bank-cv.tag');

    Route::get('ajax/recruitment', 'RecruitmentController@table')->name('ajax.table.recruitment.admin');
    Route::get('recruitment/detail-internal/{id}', 'RecruitmentController@getInternalData')->name('recruitment.detail-internal');
    Route::get('recruitment/detail-external/{id}', 'RecruitmentController@getExternalData')->name('recruitment.detail-external');
    Route::get('recruitment/detail-history-external/{id}', 'RecruitmentController@detailHistoryExternal')->name('recruitment.detail-history-external');
    Route::get('recruitment/detail-move/{id}', 'RecruitmentController@getMoveDetail')->name('recruitment.detail-move');
    Route::get('recruitment/detail-edit/{id}', 'RecruitmentController@getEditDetail')->name('recruitment.detail-edit');
    Route::get('recruitment/detail-onboard/{id}', 'RecruitmentController@getOnboardDetail')->name('recruitment.detail-onboard');
    Route::post('recruitment/move', 'RecruitmentController@move')->name('recruitment.move');
    Route::post('recruitment/email-interviewer', 'RecruitmentController@emailInterviewer')->name('recruitment.email-interviewer');
    Route::post('recruitment/update-board', 'RecruitmentController@updateBoard')->name('recruitment.update-board');
    Route::post('recruitment/update-onboard', 'RecruitmentController@updateOnboard')->name('recruitment.update-onboard');
    Route::get('recruitment/download/{id}',  'RecruitmentController@download')->name('recruitment.download');
	Route::get('career', 'CareerController@index')->name('career.index');
	Route::get('ajax/career', 'CareerController@table')->name('ajax.table.career.admin');
	Route::get('career/{id}', 'CareerController@detail')->name('administrator.career.detail');
	Route::get('career/file/download', 'CareerController@download')->name('career.download');
	Route::post('career/add-history', 'CareerController@addHistory')->name('career.add-history');
	Route::get('ajax/career/detail', 'CareerController@tableDetail')->name('ajax.table.career.detail');
	Route::get('career/history/{id}', 'CareerController@detailHistory')->name('administrator.career.history');
	Route::post('career/history', 'CareerController@updateHistory')->name('administrator.career.update');
	Route::delete('career/history/{id}', 'CareerController@destroyHistory');
	Route::get('career/file/download-detail', 'CareerController@downloadDetail')->name('career.download.detail');
	Route::post('career/import', 'CareerController@importData')->name('administrator.career.import');
	Route::get('test', 'CareerController@tableDetail');

	Route::resource('birthday-wording', 'BirthdayWordingController', ['only'=> ['index','create','store','edit','destroy','update'], 'as' => 'administrator']);
	Route::resource('payment-request-type', 'PaymentRequestTypeController', ['only'=> ['index','create','store','edit','destroy','update'], 'as' => 'administrator']);
	Route::post('payment-request-type/period', 'PaymentRequestTypeController@period')->name('administrator.payment-request-type.period.store');
});