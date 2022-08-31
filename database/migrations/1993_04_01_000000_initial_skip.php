<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InitialSkip extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if (Schema::hasTable('users')) {
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_absensi_device_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_absensi_item_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_absensi_item_temp_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_absensi_request_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_absensi_setting_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_absensi_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_airports_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_applicant_interviewers_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_asset_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_asset_tracking_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_asset_type_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_bank_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_branch_head_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_branch_staff_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_cabang_outlet_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_cabang_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_cabangpic_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_cabangpicmaster_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_career_history_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_compassionate_reason_business_trip_form_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_compassionate_reason_business_trip_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_create_clearance_accounting_finance_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_crm_module_admin_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_crm_module_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_cuti_bersama_history_karyawan_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_cuti_bersama_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_cuti_karyawan_dates_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_cuti_karyawan_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_cuti_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_cutilog_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_department_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_directorate_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_division_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_educations_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_employee_facility_recruitment_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_empore_organisasi_direktur_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_empore_organisasi_manager_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_empore_organisasi_staff_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_event_log_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_exit_clearance_document_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_exit_clearance_inventory_ga_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_exit_clearance_inventory_hrd_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_exit_clearance_inventory_it_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_exit_clearance_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_exit_interview_assets_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_exit_interview_form_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_exit_interview_inventaris_mobil_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_exit_interview_inventaris_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_exit_interview_reason_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_exit_interview_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_external_applications_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_failed_jobs_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_grade_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_history_approval_clearance_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_history_approval_exit_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_history_approval_leave_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_history_approval_medical_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_history_approval_overtime_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_history_approval_payment_request_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_history_approval_recruitment_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_history_approval_timesheet_note_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_history_approval_timesheet_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_history_approval_training_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_import_log_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_internal_applications_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_internal_memo_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_jabatan_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_jenis_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_job_categories_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_jobs_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_jobseeker_educations_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_jobseekers_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_jurusan_sma_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_kabupaten_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_kecamatan_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_kelurahan_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_kpi_employee_scoring_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_kpi_employee_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_kpi_items_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_kpi_modules_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_kpi_periods_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_kpi_setting_scoring_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_kpi_setting_status_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_landing_page_form_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_libur_nasional_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_master_category_visit_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_master_cuti_type_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_master_visit_type_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_medical_plafond_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_medical_reimbursement_form_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_medical_reimbursement_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_medical_type_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_news_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_note_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_organisasi_department_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_organisasi_directorate_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_organisasi_division_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_organisasi_job_role_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_organisasi_position_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_organisasi_unit_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_overtime_payroll_earnings_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_overtime_payroll_types_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_overtime_payrolls_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_overtime_sheet_form_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_overtime_sheet_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_payment_request_bensin_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_payment_request_form_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_payment_request_overtime_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_payment_request_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_payroll_country_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_payroll_cycle_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_payroll_deductions_employee_history_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_payroll_deductions_employee_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_payroll_deductions_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_payroll_earnings_employee_history_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_payroll_earnings_employee_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_payroll_earnings_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_payroll_history_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_payroll_npwp_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_payroll_others_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_payroll_pph_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_payroll_ptkp_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_payroll_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_payroll_umr_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_payrollgross_history_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_payrollgross_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_payrollnet_history_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_payrollnet_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_plafond_dinas_luar_negeri_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_plafond_dinas_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_product_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_program_studi_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_provinsi_detail_allowance_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_provinsi_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_recruitment_application_history_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_recruitment_application_status_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_recruitment_applications_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_recruitment_interviewers_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_recruitment_phases_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_recruitment_request_detail_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_recruitment_request_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_recruitment_type_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_related_search_karyawan_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_remote_attendance_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_request_pay_slip_item_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_request_pay_slip_itemnet_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_request_pay_slip_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_request_pay_slipgross_item_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_request_pay_slipgross_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_request_pay_slipnet_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_schedule_backup_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_seaports_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_section_staff_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_section_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_sekolah_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_setting_approval_clearance_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_setting_approval_exit_item_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_setting_approval_leave_item_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_setting_approval_level_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_setting_approval_medical_item_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_setting_approval_overtime_item_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_setting_approval_paymentrequest_item_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_setting_approval_recruitment_item_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_setting_approval_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_setting_approval_timesheet_item_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_setting_approval_timesheet_transaction_item_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_setting_approval_training_item_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_setting_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_setting_visit_activity_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_shift_detail_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_shift_schedule_change_employees_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_shift_schedule_change_temp_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_shift_schedule_changes_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_shift_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_spv_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_stations_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_status_approval_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_structure_organization_custom_employee_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_structure_organization_custom_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_sub_grade_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_test_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_timesheet_activities_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_timesheet_categories_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_timesheet_period_transactions_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_timesheet_periods_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_timesheet_transactions_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_training_allowance_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_training_biaya_lainnya_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_training_daily_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_training_other_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_training_penumpang_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_training_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_training_transportation_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_training_transportation_type_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_training_type_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_universitas_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_user_certification_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_user_certification_temp_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_user_cuti_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_user_education_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_user_education_temp_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_user_family_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_user_family_temp_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_user_inventaris_mobil_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_user_inventaris_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_users_branch_visit_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_users_branch_visit_temp_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_users_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_users_temp_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_visit_list_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212413_create_visit_pict_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212423_add_foreign_keys_to_applicant_interviewers_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212423_add_foreign_keys_to_cabangpic_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212423_add_foreign_keys_to_cabangpicmaster_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212423_add_foreign_keys_to_cuti_karyawan_dates_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212423_add_foreign_keys_to_cuti_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212423_add_foreign_keys_to_cutilog_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212423_add_foreign_keys_to_employee_facility_recruitment_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212423_add_foreign_keys_to_external_applications_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212423_add_foreign_keys_to_history_approval_timesheet_note_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212423_add_foreign_keys_to_history_approval_timesheet_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212423_add_foreign_keys_to_internal_applications_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212423_add_foreign_keys_to_jobseeker_educations_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212423_add_foreign_keys_to_kpi_employee_scoring_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212423_add_foreign_keys_to_kpi_employee_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212423_add_foreign_keys_to_kpi_items_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212423_add_foreign_keys_to_kpi_setting_scoring_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212423_add_foreign_keys_to_kpi_setting_status_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212423_add_foreign_keys_to_overtime_payroll_earnings_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212423_add_foreign_keys_to_overtime_payrolls_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212423_add_foreign_keys_to_overtime_sheet_form_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212423_add_foreign_keys_to_recruitment_application_history_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212423_add_foreign_keys_to_recruitment_applications_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212423_add_foreign_keys_to_recruitment_interviewers_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212423_add_foreign_keys_to_recruitment_phases_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212423_add_foreign_keys_to_recruitment_request_detail_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212423_add_foreign_keys_to_recruitment_request_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212423_add_foreign_keys_to_setting_approval_exit_item_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212423_add_foreign_keys_to_setting_approval_leave_item_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212423_add_foreign_keys_to_setting_approval_medical_item_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212423_add_foreign_keys_to_setting_approval_overtime_item_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212423_add_foreign_keys_to_setting_approval_paymentrequest_item_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212423_add_foreign_keys_to_setting_approval_recruitment_item_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212423_add_foreign_keys_to_setting_approval_timesheet_item_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212423_add_foreign_keys_to_setting_approval_timesheet_transaction_item_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212423_add_foreign_keys_to_setting_approval_training_item_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212423_add_foreign_keys_to_setting_visit_activity_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212423_add_foreign_keys_to_shift_schedule_change_employees_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212423_add_foreign_keys_to_shift_schedule_change_temp_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212423_add_foreign_keys_to_shift_schedule_changes_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212423_add_foreign_keys_to_timesheet_activities_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212423_add_foreign_keys_to_timesheet_period_transactions_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212423_add_foreign_keys_to_timesheet_periods_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212423_add_foreign_keys_to_timesheet_transactions_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212423_add_foreign_keys_to_users_branch_visit_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212423_add_foreign_keys_to_users_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212423_add_foreign_keys_to_visit_list_table', 0)");
            DB::STATEMENT("INSERT INTO migrations(migration, batch) VALUES ('1993_04_01_212423_add_foreign_keys_to_visit_pict_table', 0)");
        }
    }


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        // 
	}

}
