<?php

use Illuminate\Database\Migrations\Migration;

class BatchFirebaseRealtimeDatabase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if ($project = \App\Models\ConfigDB::where('db_name', \DB::getDatabaseName())->first()) {
            $client = new \GuzzleHttp\Client();
            $client->request('DELETE', env('FIREBASE_DATABASE_URL') . env('SERVER') . '/' . strtolower($project->company_code) . '.json');

            if (\Carbon\Carbon::parse($project->due_date)->gte(\Carbon\Carbon::now()->startOfDay())) {
                foreach (\App\User::whereNull('inactive_date')->orWhere('inactive_date', '>', \Carbon\Carbon::now())->pluck('id')->toArray() as $id) {
                    \Auth::loginUsingId($id);

                    $endpoint = env('FIREBASE_DATABASE_URL') . env('SERVER') . '/' . strtolower($project->company_code) . '/' . $id . '.json';

                    $data = [];

                    $paymentRequest = \App\Models\PaymentRequest::where('user_id', $id)->whereIn('status', [2, 3])->orderBy('updated_at', 'DESC')->get();
                    foreach ($paymentRequest as $item) {
                        if ($item->status == 2) {
                            $text = 'Your request for Payment Request has been approved';
                        } else if ($item->status == 3) {
                            $text = 'Your request for Payment Request has been declined';
                        }
                        if ($item->disbursement == 'Transfer') {
                            $text = 'Your request for Payment Request has been transfered';
                        } else if ($item->disbursement == 'Next Payroll') {
                            $text = 'Your request for Payment Request will be merged with the next payroll';
                        }
                        $data[] = [
                            'id' => (int) $item->id,
                            'time' => $item->updated_at->format('Y-m-d H:i:s'),
                            'read' => (int) 0,
                            'notif' => 'Management Form - Payment Request',
                            'link' => '/karyawan/payment-request-custom/' . $item->id . '/edit',
                            'text' => $text,
                            'type' => 'payment_request',
                        ];
                    }

                    $cashAdvance = \App\Models\CashAdvance::where('user_id', $id)->where(function ($query) {
                        $query->whereIn('status', [2, 3])->orWhereIn('status_claim', [2, 3]);
                    })->orderBy('updated_at', 'DESC')->get();
                    foreach ($cashAdvance as $item) {
                        if ($item->status == 2) {
                            $text = 'Your request for Cash Advance has been approved';
                            $link = '/karyawan/cash-advance/' . $item->id . '/edit';
                            $type = 'cash_advance';
                        } else if ($item->status == 3) {
                            $text = 'Your request for Cash Advance has been declined';
                            $link = '/karyawan/cash-advance/' . $item->id . '/edit';
                            $type = 'cash_advance';
                        }
                        if ($item->disbursement == 'Transfer') {
                            $text = 'Your request for Cash Advance has been transfered';
                            $link = '/karyawan/cash-advance/' . $item->id . '/edit';
                            $type = 'transfer_cash_advance';
                        } else if ($item->disbursement == 'Next Payroll') {
                            $text = 'Your request for Cash Advance will be merged with the next payroll';
                            $link = '/karyawan/cash-advance/' . $item->id . '/edit';
                            $type = 'transfer_cash_advance';
                        }
                        if ($item->status_claim == 2) {
                            $text = 'Your claim request for Cash Advance has been approved';
                            $link = '/karyawan/cash-advance/claim/' . $item->id;
                            $type = 'claim_cash_advance';
                            if (total_cash_advance_nominal_claimed($item->id) < total_cash_advance_nominal_approved($item->id) && $item->payment_method == 'Bank Transfer') {
                                $text = 'Your claim request for Cash Advance less than total approved';
                                $link = '/karyawan/cash-advance/claim/' . $item->id;
                                $type = 'transfer_back_claim_cash_advance_more';
                            }
                        } else if ($item->status_claim == 3) {
                            $text = 'Your claim request for Cash Advance has been declined';
                            $link = '/karyawan/cash-advance/claim/' . $item->id;
                            $type = 'claim_cash_advance';
                        }
                        if ($item->disbursement_claim == 'Transfer') {
                            $text = 'Your claim request for Cash Advance has been transfered';
                            $link = '/karyawan/cash-advance/claim/' . $item->id;
                            $type = 'transfer_claim_cash_advance_less';
                        } else if ($item->disbursement_claim == 'Next Payroll') {
                            $text = 'Your claim request for Cash Advance will be merged with the next payroll';
                            $link = '/karyawan/cash-advance/claim/' . $item->id;
                            $type = 'transfer_claim_cash_advance_less';
                        }
                        $data[] = [
                            'id' => (int) $item->id,
                            'time' => $item->updated_at->format('Y-m-d H:i:s'),
                            'read' => (int) 0,
                            'notif' => 'Management Form - Cash Advance',
                            'link' => $link,
                            'text' => $text,
                            'type' => $type,
                        ];
                    }

                    $leave = \App\Models\CutiKaryawan::where('user_id', $id)->whereIn('status', [2, 3, 7, 8])->orderBy('updated_at', 'DESC')->get();
                    foreach ($leave as $item) {
                        if ($item->status == 2) {
                            $text = 'Your request for Leave has been approved';
                        } else if ($item->status == 3) {
                            $text = 'Your request for Leave has been declined';
                        } else if ($item->status == 7) {
                            $text = 'Your request for Withdrawal Leave has been approved';
                        } else if ($item->status == 8) {
                            $text = 'Your request for Withdrawal Leave has been declined';
                        }
                        $data[] = [
                            'id' => (int) $item->id,
                            'time' => $item->updated_at->format('Y-m-d H:i:s'),
                            'read' => (int) 0,
                            'notif' => 'Management Form - Leave',
                            'link' => '/karyawan/leave/' . $item->id . '/edit',
                            'text' => $text,
                            'type' => 'leave',
                        ];
                    }

                    $timesheet = \App\Models\TimesheetPeriod::where('user_id', $id)->whereIn('status', [2, 3])->orderBy('updated_at', 'DESC')->get();
                    foreach ($timesheet as $item) {
                        if ($item->status == 2) {
                            $text = 'Your request for Timesheet has been approved';
                        } else if ($item->status == 3) {
                            $text = 'Your request for Timesheet has been declined';
                        }
                        $data[] = [
                            'id' => (int) $item->id,
                            'time' => $item->updated_at->format('Y-m-d H:i:s'),
                            'read' => (int) 0,
                            'notif' => 'Management Form - Timesheet',
                            'link' => '/karyawan/timesheet/' . $item->id . '/edit',
                            'text' => $text,
                            'type' => 'timesheet',
                        ];
                    }

                    $overtime = \App\Models\OvertimeSheet::where('user_id', $id)->where(function ($query) {
                        $query->whereIn('status', [2, 3])->orWhereIn('status_claim', [2, 3]);
                    })->orderBy('updated_at', 'DESC')->get();
                    foreach ($overtime as $item) {
                        if ($item->status == 2) {
                            $text = 'Your request for Overtime Sheet has been approved';
                            $link = '/karyawan/overtime-custom/' . $item->id . '/edit';
                        } else if ($item->status == 3) {
                            $text = 'Your request for Overtime Sheet has been declined';
                            $link = '/karyawan/overtime-custom/' . $item->id . '/edit';
                        }
                        if ($item->status_claim == 2) {
                            $text = 'Your claim request for Overtime Sheet has been approved';
                            $link = '/karyawan/overtime-custom/claim/' . $item->id;
                        } else if ($item->status_claim == 3) {
                            $text = 'Your claim request for Overtime Sheet has been declined';
                            $link = '/karyawan/overtime-custom/claim/' . $item->id;
                        }
                        $data[] = [
                            'id' => (int) $item->id,
                            'time' => $item->updated_at->format('Y-m-d H:i:s'),
                            'read' => (int) 0,
                            'notif' => 'Management Form - Overtime Sheet',
                            'link' => $link,
                            'text' => $text,
                            'type' => 'overtime',
                        ];
                    }

                    $businessTrip = \App\Models\Training::where('user_id', $id)->where(function ($query) {
                        $query->whereIn('status', [2, 3])->orWhereIn('status_actual_bill', [2, 3]);
                    })->orderBy('updated_at', 'DESC')->get();
                    foreach ($businessTrip as $item) {
                        if ($item->status == 2) {
                            $text = 'Your request for Business Trip has been approved';
                            $link = '/karyawan/training-custom/' . $item->id . '/edit';
                            $type = 'business_trip';
                        } else if ($item->status == 3) {
                            $text = 'Your request for Business Trip has been declined';
                            $link = '/karyawan/training-custom/' . $item->id . '/edit';
                            $type = 'business_trip';
                        }
                        if ($item->disbursement == 'Transfer') {
                            $text = 'Your request for Business Trip has been transfered';
                            $link = '/karyawan/training-custom/' . $item->id . '/edit';
                            $type = 'transfer_business_trip';
                        } else if ($item->disbursement == 'Next Payroll') {
                            $text = 'Your request for Business Trip will be merged with the next payroll';
                            $link = '/karyawan/training-custom/' . $item->id . '/edit';
                            $type = 'transfer_business_trip';
                        }
                        if ($item->status_actual_bill == 2) {
                            $text = 'Your actual bill request for Business Trip has been approved';
                            $link = '/karyawan/training-custom/claim/' . $item->id;
                            $type = 'training';
                            if (($item->sub_total_1_disetujui + $item->sub_total_2_disetujui + $item->sub_total_3_disetujui + $item->sub_total_4_disetujui) < $item->pengambilan_uang_muka) {
                                $text = 'Your actual bill request for Business Trip less than total approved';
                                $link = '/karyawan/training-custom/claim/' . $item->id;
                                $type = 'transfer_back_claim_business_trip_more';
                            }
                        } else if ($item->status_actual_bill == 3) {
                            $text = 'Your actual bill request for Business Trip has been declined';
                            $link = '/karyawan/training-custom/claim/' . $item->id;
                            $type = 'training_reject';
                        }
                        if ($item->disbursement_claim == 'Transfer') {
                            $text = 'Your actual bill request for Business Trip has been transfered';
                            $link = '/karyawan/training-custom/claim/' . $item->id;
                            $type = 'transfer_claim_business_trip_less';
                        } else if ($item->disbursement_claim == 'Next Payroll') {
                            $text = 'Your actual bill request for Business Trip will be merged with the next payroll';
                            $link = '/karyawan/training-custom/claim/' . $item->id;
                            $type = 'transfer_claim_business_trip_less';
                        }
                        $data[] = [
                            'id' => (int) $item->id,
                            'time' => $item->updated_at->format('Y-m-d H:i:s'),
                            'read' => (int) 0,
                            'notif' => 'Management Form - Business Trip',
                            'link' => $link,
                            'text' => $text,
                            'type' => $type,
                        ];
                    }

                    $medical = \App\Models\MedicalReimbursement::where('user_id', $id)->whereIn('status', [2, 3])->orderBy('updated_at', 'DESC')->get();
                    foreach ($medical as $item) {
                        if ($item->status == 2) {
                            $text = 'Your request for Medical Reimbursement has been approved';
                            $type = 'medical';
                        } else if ($item->status == 3) {
                            $text = 'Your request for Medical Reimbursement has been declined';
                            $type = 'medical';
                        }
                        if ($item->disbursement == 'Transfer') {
                            $text = 'Your request for Medical Reimbursement has been transfered';
                            $type = 'medical_reimbursement';
                        } else if ($item->disbursement == 'Next Payroll') {
                            $text = 'Your request for Medical Reimbursement will be merged with the next payroll';
                            $type = 'medical_reimbursement';
                        }
                        $data[] = [
                            'id' => (int) $item->id,
                            'time' => $item->updated_at->format('Y-m-d H:i:s'),
                            'read' => (int) 0,
                            'notif' => 'Management Form - Medical Reimbursement',
                            'link' => '/karyawan/medical-custom/' . $item->id . '/edit',
                            'text' => $text,
                            'type' => $type,
                        ];
                    }

                    $exitInterview = \App\Models\ExitInterview::where('user_id', $id)->whereIn('status', [2, 3])->orderBy('updated_at', 'DESC')->get();
                    foreach ($exitInterview as $item) {
                        if ($item->status == 2) {
                            $text = 'Your request for Exit Interview has been approved';
                        } else if ($item->status == 3) {
                            $text = 'Your request for Exit Interview has been declined';
                        }
                        $data[] = [
                            'id' => (int) $item->id,
                            'time' => $item->updated_at->format('Y-m-d H:i:s'),
                            'read' => (int) 0,
                            'notif' => 'Management Form - Exit Interview',
                            'link' => '/karyawan/exit-custom/' . $item->id . '/edit',
                            'text' => $text,
                            'type' => 'exit_interview',
                        ];
                    }

                    $exitClearance = \App\Models\ExitInterview::where('user_id', $id)->whereIn('status_clearance', [1, 2])->orderBy('updated_at', 'DESC')->get();
                    foreach ($exitClearance as $item) {
                        if ($item->status_clearance == 1) {
                            $text = 'Your request for Exit Clearance has been approved';
                        } else if ($item->status_clearance == 2) {
                            $text = 'Your request for Exit Clearance has been declined';
                        }
                        $data[] = [
                            'id' => (int) $item->id,
                            'time' => $item->updated_at->format('Y-m-d H:i:s'),
                            'read' => (int) 0,
                            'notif' => 'Management Form - Exit Clearance',
                            'link' => '/karyawan/exit-custom/clearance/' . $item->id,
                            'text' => $text,
                            'type' => 'exit_clearance',
                        ];
                    }

                    $payslip = \App\Models\RequestPaySlip::where('user_id', $id)->whereIn('status', [2, 3])->orderBy('updated_at', 'DESC')->get();
                    foreach ($payslip as $item) {
                        if ($item->status == 2) {
                            $text = 'Your request for Request PaySlip has been approved';
                        } else if ($item->status == 3) {
                            $text = 'Your request for Request Payslip has been declined';
                        }
                        $data[] = [
                            'id' => (int) $item->id,
                            'time' => $item->updated_at->format('Y-m-d H:i:s'),
                            'read' => (int) 0,
                            'notif' => 'Management Form - Request Payslip',
                            'link' => '/karyawan/request-pay-slip/' . $item->id . '/edit',
                            'text' => $text,
                            'type' => 'payslip',
                        ];
                    }

                    $facilities = \App\Models\AssetTracking::where('user_id', $id)->where('status_return', 1)->orderBy('updated_at', 'DESC')->get();
                    foreach ($facilities as $item) {
                        $data[] = [
                            'id' => (int) $item->asset_id,
                            'time' => $item->updated_at->format('Y-m-d H:i:s'),
                            'read' => (int) 0,
                            'notif' => 'Management Form - Facilities',
                            'link' => '/karyawan/facilities',
                            'text' => 'Your request return for Facilities has been approved',
                            'type' => 'facilities_return_approv',
                        ];
                    }

                    $asset = \App\Models\Asset::where('user_id', $id)->whereIn('status', [0, 1])->orderBy('updated_at', 'DESC')->get();
                    foreach ($facilities as $item) {
                        if ($item->status == 1) {
                            $text = 'Facilities has been accepted, check your email for term and agreement';
                            $link = '/karyawan/facilities/' . $item->id . '/edit';
                            $type = 'facilities_term_agreement';
                            if (\App\Models\SettingApprovalClearance::where('nama_approval', $item->asset_type->pic_department)->where('user_id', $id)->first()) {
                                $data[] = [
                                    'id' => (int) $item->id,
                                    'time' => $item->updated_at->format('Y-m-d H:i:s'),
                                    'read' => (int) 0,
                                    'notif' => 'Management Approval - Facilities',
                                    'link' => '/karyawan/approval-facilities',
                                    'text' => 'New acceptance for Facilities from ' . $item->user->name . ' / ' . $item->user->nik,
                                    'type' => 'facilities_term_agreement_pic',
                                ];
                            }
                        } else if ($item->status == 0) {
                            $text = 'New request acceptance for Facilities';
                            $link = '/karyawan/facilities/' . $item->id;
                            $type = 'asset';
                        }
                        $data[] = [
                            'id' => (int) $item->id,
                            'time' => $item->updated_at->format('Y-m-d H:i:s'),
                            'read' => (int) 0,
                            'notif' => 'Management Form - Facilities',
                            'link' => $link,
                            'text' => $text,
                            'type' => $type,
                        ];
                    }

                    $recruitment = \App\Models\RecruitmentRequest::where('requestor_id', $id)->where(function ($query) {
                        $query->whereIn('approval_hr', [0, 1])->orWhereIn('approval_user', [0, 1]);
                    })->orderBy('updated_at', 'DESC')->get();
                    foreach ($recruitment as $item) {
                        if ($item->approval_hr == 1) {
                            $text = 'Your request for Recruitment Request has been approved by HR';
                        } else if ($item->approval_hr == 0) {
                            $text = 'Your request for Recruitment Request has been declined by HR';
                        }
                        if ($item->approval_user == 1) {
                            $text = 'Your request for Recruitment Request has been approved by User';
                        } else if ($item->approval_user == 0) {
                            $text = 'Your request for Recruitment Request has been declined by User';
                        }
                        $data[] = [
                            'id' => (int) $item->id,
                            'time' => $item->updated_at->format('Y-m-d H:i:s'),
                            'read' => (int) 0,
                            'notif' => 'Management Form - Recruitment Request',
                            'link' => '/karyawan/recruitment-request/' . $item->id . '/edit',
                            'text' => $text,
                            'type' => 'recruitment',
                        ];
                    }

                    $loan = \App\Models\Loan::where('user_id', $id)->whereIn('status', [2, 3])->orderBy('updated_at', 'DESC')->get();
                    foreach ($loan as $item) {
                        if ($item->status == 2) {
                            $text = 'Your request for Loan has been approved';
                        } else if ($item->status == 3) {
                            $text = 'Your request for Loan has been declined';
                        }
                        $data[] = [
                            'id' => (int) $item->id,
                            'time' => $item->updated_at->format('Y-m-d H:i:s'),
                            'read' => (int) 0,
                            'notif' => 'Management Form - Loan',
                            'link' => '/karyawan/loan/' . $item->id . '/edit',
                            'text' => $text,
                            'type' => 'loan',
                        ];
                    }

                    $loanPayment = \App\Models\LoanPayment::whereHas('loan', function ($query) use ($id) {
                        $query->where('user_id', $id);
                    })->whereIn('status', [2, 3])->orderBy('updated_at', 'DESC')->get();
                    foreach ($loan as $item) {
                        if (isset($item->loan)) {
                            if ($item->status == 2) {
                                $text = 'Your request payment for Loan has been approved';
                            } else if ($item->status == 3) {
                                $text = 'Your request payment for Loan has been declined';
                            }
                            $data[] = [
                                'id' => (int) $item->loan->id,
                                'time' => $item->updated_at->format('Y-m-d H:i:s'),
                                'read' => (int) 0,
                                'notif' => 'Management Form - Loan',
                                'link' => '/karyawan/loan/' . $item->loan->id . '/edit',
                                'text' => $text,
                                'type' => 'loan',
                            ];
                        }
                    }

                    $approval = notif();
                    if ($approval['cash_advance']['waiting'] != 0 && isset($approval['cash_advance']['data'])) {
                        foreach ($approval['cash_advance']['data'] as $item) {
                            if (isset($item->cashAdvance->user)) {
                                if ($item->status == 1) {
                                    $text = 'New request for Cash Advance from ' . $item->cashAdvance->user->name . ' / ' . $item->cashAdvance->user->nik;
                                    $link = '/karyawan/approval-cash-advance/detail/' . $item->id;
                                    $type = 'cash_advance_approval';
                                }
                                if ($item->status == 2 && $item->payment_method == 'Bank Transfer' && cek_transfer_setting_user()) {
                                    $text = 'New request payment for Cash Advance from ' . $item->cashAdvance->user->name . ' / ' . $item->cashAdvance->user->nik;
                                    $link = '/karyawan/approval-cash-advance/detail/transfer/' . $item->id;
                                    $type = 'transfer_cash_advance_approve';
                                }
                                if ($item->status_claim == 1) {
                                    $text = 'New request claim for Cash Advance from ' . $item->cashAdvance->user->name . ' / ' . $item->cashAdvance->user->nik;
                                    $link = '/karyawan/approval-cash-advance/claim/' . $item->id;
                                    $type = 'claim_cash_advance_approval';
                                }
                                if ($item->status_claim == 2 && total_cash_advance_nominal_claimed($cash_advance->id) != total_cash_advance_nominal_approved($cash_advance->id) && $item->payment_method == 'Bank Transfer' && cek_transfer_setting_user()) {
                                    $text = 'New request payment claim for Cash Advance from ' . $item->cashAdvance->user->name . ' / ' . $item->cashAdvance->user->nik;
                                    $link = '/karyawan/approval-cash-advance/claim/transfer/' . $item->id;
                                    $type = 'transfer_claim_cash_advance';
                                }
                                $data[] = [
                                    'id' => (int) $item->id,
                                    'time' => $item->updated_at->format('Y-m-d H:i:s'),
                                    'read' => (int) 0,
                                    'notif' => 'Management Approval - Cash Advance',
                                    'link' => $link,
                                    'text' => $text,
                                    'type' => $type,
                                ];
                            }
                        }
                    }

                    if ($approval['overtime']['waiting'] != 0 && isset($approval['overtime']['data'])) {
                        foreach ($approval['overtime']['data'] as $item) {
                            if (isset($item->overtimeSheet->user)) {
                                if ($item->status == 1) {
                                    $text = 'New request for Overtime Sheet from ' . $item->overtimeSheet->user->name . ' / ' . $item->overtimeSheet->user->nik;
                                }
                                if ($item->status_claim == 1) {
                                    $text = 'New request claim for Overtime Sheet from ' . $item->overtimeSheet->user->name . ' / ' . $item->overtimeSheet->user->nik;
                                }
                                $data[] = [
                                    'id' => (int) $item->id,
                                    'time' => $item->updated_at->format('Y-m-d H:i:s'),
                                    'read' => (int) 0,
                                    'notif' => 'Management Approval - Overtime Sheet',
                                    'link' => '/karyawan/approval-overtime-custom/detail/' . $item->id,
                                    'text' => $text,
                                    'type' => 'overtime_approval',
                                ];
                            }
                        }
                    }

                    if ($approval['leave']['waiting'] != 0 && isset($approval['leave']['data'])) {
                        foreach ($approval['leave']['data'] as $item) {
                            if (isset($item->cutiKaryawan->user)) {
                                if ($item->status == 1) {
                                    $text = 'New request for Leave from ' . $item->cutiKaryawan->user->name . ' / ' . $item->cutiKaryawan->user->nik;
                                } else if ($item->status == 6) {
                                    $text = 'New withdraw request for Leave from ' . $item->cutiKaryawan->user->name . ' / ' . $item->cutiKaryawan->user->nik;
                                }
                                $data[] = [
                                    'id' => (int) $item->id,
                                    'time' => $item->updated_at->format('Y-m-d H:i:s'),
                                    'read' => (int) 0,
                                    'notif' => 'Management Approval - Leave',
                                    'link' => '/karyawan/approval-leave-custom/detail/' . $item->id,
                                    'text' => $text,
                                    'type' => 'leave_approval',
                                ];
                            }
                        }
                    }

                    if ($approval['timesheet']['waiting'] != 0 && isset($approval['timesheet']['data'])) {
                        foreach ($approval['timesheet']['data'] as $item) {
                            $data[] = [
                                'id' => (int) $item->id,
                                'time' => $item->updated_at->format('Y-m-d H:i:s'),
                                'read' => (int) 0,
                                'notif' => 'Management Approval - Timesheet',
                                'link' => '/karyawan/approval-timesheet-custom/detail/' . $item->id,
                                'text' => 'New request for Timesheet from ' . $item->user->name . ' / ' . $item->user->nik,
                                'type' => 'timesheet_approval',
                            ];
                        }
                    }

                    if ($approval['payment']['waiting'] != 0 && isset($approval['payment']['data'])) {
                        foreach ($approval['payment']['data'] as $item) {
                            if (isset($item->paymentRequest->user)) {
                                if ($item->status == 1) {
                                    $text = 'New request for Payment Request from ' . $item->paymentRequest->user->name . ' / ' . $item->paymentRequest->user->nik;
                                    $type = 'approval_payment_request';
                                }
                                if ($item->status == 2 && $item->payment_method == 'Bank Transfer' && cek_transfer_setting_user()) {
                                    $text = 'New request payment for Payment Request from ' . $item->paymentRequest->user->name . ' / ' . $item->paymentRequest->user->nik;
                                    $type = 'transfer_payment_request_approve';
                                }
                                $data[] = [
                                    'id' => (int) $item->id,
                                    'time' => $item->updated_at->format('Y-m-d H:i:s'),
                                    'read' => (int) 0,
                                    'notif' => 'Management Approval - Payment Request',
                                    'link' => '/karyawan/approval-payment-request-custom/detail/' . $item->id,
                                    'text' => $text,
                                    'type' => $type,
                                ];
                            }
                        }
                    }

                    if ($approval['recruitment']['waiting'] != 0 && isset($approval['recruitment']['data'])) {
                        foreach ($approval['recruitment']['data'] as $item) {
                            if (isset($item->recruitmentRequest->requestor)) {
                                $data[] = [
                                    'id' => (int) $item->id,
                                    'time' => $item->updated_at->format('Y-m-d H:i:s'),
                                    'read' => (int) 0,
                                    'notif' => 'Management Approval - Recruitment Request',
                                    'link' => '/karyawan/approval-recruitment-request/detail/' . $item->id,
                                    'text' => 'New request for Recruitment Request from ' . $item->recruitmentRequest->requestor->name . ' / ' . $item->recruitmentRequest->requestor->nik,
                                    'type' => 'recruitment_approval',
                                ];
                            }
                        }
                    }

                    if ($approval['training']['waiting'] != 0 && isset($approval['training']['data'])) {
                        foreach ($approval['training']['data'] as $item) {
                            if (isset($item->training->user)) {
                                if ($item->status == 1) {
                                    $text = 'New request for Business Trip from ' . $item->training->user->name . ' / ' . $item->training->user->nik;
                                    $link = '/karyawan/approval-training-custom/detail/' . $item->id;
                                    $type = 'business_trip_approval';
                                }
                                if ($item->status == 2 && cek_transfer_setting_user()) {
                                    $text = 'New request payment for Business Trip from ' . $item->training->user->name . ' / ' . $item->training->user->nik;
                                    $link = '/karyawan/approval-training-custom/detail/transfer/' . $item->id;
                                    $type = 'transfer_business_trip_approve';
                                }
                                if ($item->status_actual_bill == 1) {
                                    $text = 'New request actual bill for Business Trip from ' . $item->training->user->name . ' / ' . $item->training->user->nik;
                                    $link = '/karyawan/approval-training-custom/claim/' . $item->id;
                                    $type = 'training_approval';
                                }
                                if ($item->status_actual_bill == 2 && ($item->sub_total_1_disetujui + $item->sub_total_2_disetujui + $item->sub_total_3_disetujui + $item->sub_total_4_disetujui) != $item->pengambilan_uang_muka && cek_transfer_setting_user()) {
                                    $text = 'New request payment actual bill for Business Trip from ' . $item->training->user->name . ' / ' . $item->training->user->nik;
                                    $link = '/karyawan/approval-training-custom/claim/transfer/' . $item->id;
                                    $type = 'transfer_claim_business_trip';
                                }
                                $data[] = [
                                    'id' => (int) $item->id,
                                    'time' => $item->updated_at->format('Y-m-d H:i:s'),
                                    'read' => (int) 0,
                                    'notif' => 'Management Approval - Business Trip',
                                    'link' => $link,
                                    'text' => $text,
                                    'type' => $type,
                                ];
                            }
                        }
                    }

                    if ($approval['medical']['waiting'] != 0 && isset($approval['medical']['data'])) {
                        foreach ($approval['medical']['data'] as $item) {
                            if (isset($item->medicalReimbursement->user)) {
                                if ($item->status == 1) {
                                    $text = 'New request for Medical Reimbursement from ' . $item->medicalReimbursement->user->name . ' / ' . $item->medicalReimbursement->user->nik;
                                    $type = 'medical_approval';
                                }
                                if ($item->status == 2 && cek_transfer_setting_user()) {
                                    $text = 'New request payment for Medical Reimbursement from ' . $item->medicalReimbursement->user->name . ' / ' . $item->medicalReimbursement->user->nik;
                                    $type = 'transfer_medical_approve';
                                }
                                $data[] = [
                                    'id' => (int) $item->id,
                                    'time' => $item->updated_at->format('Y-m-d H:i:s'),
                                    'read' => (int) 0,
                                    'notif' => 'Management Approval - Medical Reimbursement',
                                    'link' => '/karyawan/approval-medical-custom/detail/' . $item->id,
                                    'text' => $text,
                                    'type' => $type,
                                ];
                            }
                        }
                    }

                    if ($approval['exit']['waiting'] != 0 && isset($approval['exit']['data'])) {
                        foreach ($approval['exit']['data'] as $item) {
                            if (isset($item->exitInterview->user)) {
                                $data[] = [
                                    'id' => (int) $item->id,
                                    'time' => $item->updated_at->format('Y-m-d H:i:s'),
                                    'read' => (int) 0,
                                    'notif' => 'Management Approval - Exit Interview',
                                    'link' => '/karyawan/approval-exit-custom/detail/' . $item->id,
                                    'text' => 'New request for Exit Interview from ' . $item->exitInterview->user->name . ' / ' . $item->exitInterview->user->nik,
                                    'type' => 'exit_interview_approval',
                                ];
                            }
                        }
                    }

                    if ($approval['clearance']['waiting'] != 0 && isset($approval['clearance']['data'])) {
                        foreach ($approval['clearance']['data'] as $item) {
                            $data[] = [
                                'id' => (int) $item->id,
                                'time' => $item->updated_at->format('Y-m-d H:i:s'),
                                'read' => (int) 0,
                                'notif' => 'Management Approval - Exit Clearance',
                                'link' => '/karyawan/approval-clearance-custom/detail/' . $item->id,
                                'text' => 'New request for Exit Clearance from ' . $item->user->name . ' / ' . $item->user->nik,
                                'type' => 'exit_clearance_approval',
                            ];
                        }
                    }

                    if ($approval['facilities']['waiting'] != 0 && isset($approval['facilities']['data'])) {
                        foreach ($approval['facilities']['data'] as $item) {
                            $data[] = [
                                'id' => (int) $item->id,
                                'time' => $item->updated_at->format('Y-m-d H:i:s'),
                                'read' => (int) 0,
                                'notif' => 'Management Approval - Facilities',
                                'link' => '/karyawan/approval-facilities/detail/' . $item->id,
                                'text' => 'New request return for Facilities from ' . $item->user->name . ' / ' . $item->user->nik,
                                'type' => 'facilities_return',
                            ];
                        }
                    }

                    if ($approval['loan']['waiting'] != 0 && isset($approval['loan']['data'])) {
                        foreach ($approval['loan']['data'] as $item) {
                            if (isset($item->loan->user)) {
                                $data[] = [
                                    'id' => (int) $item->id,
                                    'time' => $item->updated_at->format('Y-m-d H:i:s'),
                                    'read' => (int) 0,
                                    'notif' => 'Management Approval - Loan',
                                    'link' => '/karyawan/approval-loan/detail/' . $item->id,
                                    'text' => 'New request for Loan from ' . $item->loan->user->name . ' / ' . $item->loan->user->nik,
                                    'type' => 'loan_approval',
                                ];
                            }
                        }
                    }

                    // $news = \App\Models\News::where('news.status', 1)->orderBy('news.id', 'DESC')->get();
                    // foreach ($news as $item) {
                    //     $data[] = [
                    //         'id' => (int) $item->id,
                    //         'time' => $item->updated_at->format('Y-m-d H:i:s'),
                    //         'read' => (int) 0,
                    //         'notif' => 'Home - News List',
                    //         'link' => '/karyawan/news/readmore/' . $item->id,
                    //         'text' => 'New News posted',
                    //         'type' => 'news',
                    //     ];
                    // }

                    // $memo = \App\Models\InternalMemo::where('internal_memo.status', 1)->orderBy('internal_memo.id', 'DESC')->get();
                    // foreach ($memo as $item) {
                    //     $data[] = [
                    //         'id' => (int) $item->id,
                    //         'time' => $item->updated_at->format('Y-m-d H:i:s'),
                    //         'read' => (int) 0,
                    //         'notif' => 'Home - Internal Memo',
                    //         'link' => '/karyawan/internal-memo/readmore/' . $item->id,
                    //         'text' => 'New Internal Memo posted',
                    //         'type' => 'memo',
                    //     ];
                    // }

                    // $product = \App\Models\Product::where('product.status', 1)->orderBy('product.id', 'DESC')->get();
                    // foreach ($product as $item) {
                    //     $data[] = [
                    //         'id' => (int) $item->id,
                    //         'time' => $item->updated_at->format('Y-m-d H:i:s'),
                    //         'read' => (int) 0,
                    //         'notif' => 'Home - Product Information',
                    //         'link' => '/karyawan/product/readmore/' . $item->id,
                    //         'text' => 'New Product Information posted',
                    //         'type' => 'product',
                    //     ];
                    // }

                    // $vacancy = \App\Models\RecruitmentRequestDetail::join('recruitment_request as rr', 'recruitment_request_id', '=', 'rr.id')
                    //     ->leftJoin('cabang as c', 'rr.branch_id', '=', 'c.id')
                    //     ->where([
                    //         'recruitment_request_detail.status_post' => 1,
                    //         'recruitment_request_detail.recruitment_type_id' => 1,
                    //         'rr.approval_hr' => 1,
                    //         'rr.approval_user' => 1,
                    //     ])
                    //     ->select(['recruitment_request_detail.*', 'rr.id as recruitment_id', 'rr.min_salary', 'rr.max_salary', 'c.name as branch', 'rr.job_desc', 'rr.job_requirement', 'rr.job_position'])
                    //     ->orderBy('recruitment_request_detail.posting_date', 'desc')->get();
                    // foreach ($vacancy as $item) {
                    //     $data[] = [
                    //         'id' => (int) $item->id,
                    //         'time' => $item->updated_at->format('Y-m-d H:i:s'),
                    //         'read' => (int) 0,
                    //         'notif' => 'Home - Internal Recruitment',
                    //         'link' => '/karyawan/internal-recruitment/detail/' . $item->id,
                    //         'text' => 'New Internal Recruitment posted',
                    //         'type' => 'internal',
                    //     ];
                    // }

                    $data = collect($data)->sortByDesc('time')->skip(0)->take(env('FIREBASE_LIMIT', 30))->sortBy('time');

                    foreach ($data as $key => $value) {
                        $client->request('POST', $endpoint, [
                            'json' => json_decode(json_encode($value)),
                        ]);
                    }

                    \Auth::logout();
                }
            }
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
