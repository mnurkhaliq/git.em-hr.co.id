<?php

namespace App\Helper;

use Illuminate\Support\Facades\Config;

class FRDHelper
{
    public static function setNewData($company_code, $user_id, $item, $type)
    {
        $notifArray = [
            'id' => (int) $item->id,
            'time' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
            'read' => (int) 0,
            'type' => $type,
        ];

        switch ($type) {
            case 'payment_request':
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
                $notifArray = array_merge($notifArray, [
                    'notif' => 'Management Form - Payment Request',
                    'link' => '/karyawan/payment-request-custom/' . $item->id . '/edit',
                    'text' => $text,
                ]);
                break;
            case 'cash_advance':
                if ($item->status == 2) {
                    $text = 'Your request for Cash Advance has been approved';
                } else if ($item->status == 3) {
                    $text = 'Your request for Cash Advance has been declined';
                }
                $notifArray = array_merge($notifArray, [
                    'notif' => 'Management Form - Cash Advance',
                    'link' => '/karyawan/cash-advance/' . $item->id . '/edit',
                    'text' => $text,
                ]);
                break;
            case 'transfer_cash_advance':
                if ($item->disbursement == 'Transfer') {
                    $text = 'Your request for Cash Advance has been transfered';
                } else if ($item->disbursement == 'Next Payroll') {
                    $text = 'Your request for Cash Advance will be merged with the next payroll';
                }
                $notifArray = array_merge($notifArray, [
                    'notif' => 'Management Form - Cash Advance',
                    'link' => '/karyawan/cash-advance/' . $item->id . '/edit',
                    'text' => $text,
                ]);
                break;
            case 'claim_cash_advance':
                if ($item->status_claim == 2) {
                    $text = 'Your claim request for Cash Advance has been approved';
                } else if ($item->status_claim == 3) {
                    $text = 'Your claim request for Cash Advance has been declined';
                }
                $notifArray = array_merge($notifArray, [
                    'notif' => 'Management Form - Cash Advance',
                    'link' => '/karyawan/cash-advance/claim/' . $item->id,
                    'text' => $text,
                ]);
                break;
            case 'transfer_claim_cash_advance_less':
                if ($item->disbursement_claim == 'Transfer') {
                    $text = 'Your claim request for Cash Advance has been transfered';
                } else if ($item->disbursement_claim == 'Next Payroll') {
                    $text = 'Your claim request for Cash Advance will be merged with the next payroll';
                }
                $notifArray = array_merge($notifArray, [
                    'notif' => 'Management Form - Cash Advance',
                    'link' => '/karyawan/cash-advance/claim/' . $item->id,
                    'text' => $text,
                ]);
                break;
            case 'transfer_back_claim_cash_advance_more':
                $notifArray = array_merge($notifArray, [
                    'notif' => 'Management Form - Cash Advance',
                    'link' => '/karyawan/cash-advance/claim/' . $item->id,
                    'text' => 'Your claim request for Cash Advance less than total approved',
                ]);
                break;
            case 'leave':
                if ($item->status == 2) {
                    $text = 'Your request for Leave has been approved';
                } else if ($item->status == 3) {
                    $text = 'Your request for Leave has been declined';
                } else if ($item->status == 7) {
                    $text = 'Your request for Withdrawal Leave has been approved';
                } else if ($item->status == 8) {
                    $text = 'Your request for Withdrawal Leave has been declined';
                }
                $notifArray = array_merge($notifArray, [
                    'notif' => 'Management Form - Leave',
                    'link' => '/karyawan/leave/' . $item->id . '/edit',
                    'text' => $text,
                ]);
                break;
            case 'timesheet':
                if ($item->status == 2) {
                    $text = 'Your request for Timesheet has been approved';
                } else if ($item->status == 3) {
                    $text = 'Your request for Timesheet has been declined';
                }
                $notifArray = array_merge($notifArray, [
                    'notif' => 'Management Form - Timesheet',
                    'link' => '/karyawan/timesheet/' . $item->id . '/edit',
                    'text' => $text,
                ]);
                break;
            case 'overtime':
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
                $notifArray = array_merge($notifArray, [
                    'notif' => 'Management Form - Overtime Sheet',
                    'link' => $link,
                    'text' => $text,
                ]);
                break;
            case 'business_trip':
                if ($item->status == 2) {
                    $text = 'Your request for Business Trip has been approved';
                } else if ($item->status == 3) {
                    $text = 'Your request for Business Trip has been declined';
                }
                $notifArray = array_merge($notifArray, [
                    'notif' => 'Management Form - Business Trip',
                    'link' => '/karyawan/training-custom/' . $item->id . '/edit',
                    'text' => $text,
                ]);
                break;
            case 'transfer_business_trip':
                if ($item->disbursement == 'Transfer') {
                    $text = 'Your request for Business Trip has been transfered';
                } else if ($item->disbursement == 'Next Payroll') {
                    $text = 'Your request for Business Trip will be merged with the next payroll';
                }
                $notifArray = array_merge($notifArray, [
                    'notif' => 'Management Form - Business Trip',
                    'link' => '/karyawan/training-custom/' . $item->id . '/edit',
                    'text' => $text,
                ]);
                break;
            case 'training':
                $notifArray = array_merge($notifArray, [
                    'notif' => 'Management Form - Business Trip',
                    'link' => '/karyawan/training-custom/claim/' . $item->id,
                    'text' => 'Your actual bill request for Business Trip has been approved',
                ]);
                break;
            case 'training_reject':
                $notifArray = array_merge($notifArray, [
                    'notif' => 'Management Form - Business Trip',
                    'link' => '/karyawan/training-custom/claim/' . $item->id,
                    'text' => 'Your actual bill request for Business Trip has been declined',
                ]);
                break;
            case 'transfer_claim_business_trip_less':
                if ($item->disbursement_claim == 'Transfer') {
                    $text = 'Your actual bill request for Business Trip has been transfered';
                } else if ($item->disbursement_claim == 'Next Payroll') {
                    $text = 'Your actual bill request for Business Trip will be merged with the next payroll';
                }
                $notifArray = array_merge($notifArray, [
                    'notif' => 'Management Form - Business Trip',
                    'link' => '/karyawan/training-custom/claim/' . $item->id,
                    'text' => $text,
                ]);
                break;
            case 'transfer_back_claim_business_trip_more':
                $notifArray = array_merge($notifArray, [
                    'notif' => 'Management Form - Business Trip',
                    'link' => '/karyawan/training-custom/claim/' . $item->id,
                    'text' => 'Your actual bill request for Business Trip less than total approved',
                ]);
                break;
            case 'medical':
                if ($item->status == 2) {
                    $text = 'Your request for Medical Reimbursement has been approved';
                } else if ($item->status == 3) {
                    $text = 'Your request for Medical Reimbursement has been declined';
                }
                $notifArray = array_merge($notifArray, [
                    'notif' => 'Management Form - Medical Reimbursement',
                    'link' => '/karyawan/medical-custom/' . $item->id . '/edit',
                    'text' => $text,
                ]);
                break;
            case 'medical_reimbursement':
                if ($item->disbursement == 'Transfer') {
                    $text = 'Your request for Medical Reimbursement has been transfered';
                } else if ($item->disbursement == 'Next Payroll') {
                    $text = 'Your request for Medical Reimbursement will be merged with the next payroll';
                }
                $notifArray = array_merge($notifArray, [
                    'notif' => 'Management Form - Medical Reimbursement',
                    'link' => '/karyawan/medical-custom/' . $item->id . '/edit',
                    'text' => $text,
                ]);
                break;
            case 'exit_interview':
                if ($item->status == 2) {
                    $text = 'Your request for Exit Interview has been approved';
                } else if ($item->status == 3) {
                    $text = 'Your request for Exit Interview has been declined';
                }
                $notifArray = array_merge($notifArray, [
                    'notif' => 'Management Form - Exit Interview',
                    'link' => '/karyawan/exit-custom/' . $item->id . '/edit',
                    'text' => $text,
                ]);
                break;
            case 'exit_clearance':
                if ($item->status_clearance == 1) {
                    $text = 'Your request for Exit Clearance has been approved';
                } else if ($item->status_clearance == 2) {
                    $text = 'Your request for Exit Clearance has been declined';
                }
                $notifArray = array_merge($notifArray, [
                    'notif' => 'Management Form - Exit Clearance',
                    'link' => '/karyawan/exit-custom/clearance/' . $item->id,
                    'text' => $text,
                ]);
                break;
            case 'payslip':
                if ($item->status == 2) {
                    $text = 'Your request for Request PaySlip has been approved';
                } else if ($item->status == 3) {
                    $text = 'Your request for Request Payslip has been declined';
                }
                $notifArray = array_merge($notifArray, [
                    'notif' => 'Management Form - Request Payslip',
                    'link' => '/karyawan/request-pay-slip/' . $item->id . '/edit',
                    'text' => $text,
                ]);
                break;
            case 'facilities_return_approv':
                $notifArray = array_merge($notifArray, [
                    'notif' => 'Management Form - Facilities',
                    'link' => '/karyawan/facilities',
                    'text' => 'Your request return for Facilities has been approved',
                ]);
                $notifArray['id'] = (int) $item->asset_id;
                break;
            case 'facilities_term_agreement':
                $notifArray = array_merge($notifArray, [
                    'notif' => 'Management Form - Facilities',
                    'link' => '/karyawan/facilities/' . $item->asset_id . '/edit',
                    'text' => 'Facilities has been accepted, check your email for term and agreement',
                ]);
                $notifArray['id'] = (int) $item->asset_id;
                break;
            case 'asset':
                $notifArray = array_merge($notifArray, [
                    'notif' => 'Management Form - Facilities',
                    'link' => '/karyawan/facilities/' . $item->id,
                    'text' => 'New request acceptance for Facilities',
                ]);
                break;
            case 'recruitment':
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
                $notifArray = array_merge($notifArray, [
                    'notif' => 'Management Form - Recruitment Request',
                    'link' => '/karyawan/recruitment-request/' . $item->id . '/edit',
                    'text' => $text,
                ]);
                break;
            case 'loan':
                if ($item->status == 2) {
                    $text = 'Your request for Loan has been approved';
                } else if ($item->status == 3) {
                    $text = 'Your request for Loan has been declined';
                }
                $notifArray = array_merge($notifArray, [
                    'notif' => 'Management Form - Loan',
                    'link' => '/karyawan/loan/' . $item->id . '/edit',
                    'text' => $text,
                ]);
                break;
            case 'loan_payment':
                if ($item->status == 2) {
                    $text = 'Your request payment for Loan has been approved';
                } else if ($item->status == 3) {
                    $text = 'Your request payment for Loan has been declined';
                }
                $notifArray = array_merge($notifArray, [
                    'notif' => 'Management Form - Loan',
                    'link' => '/karyawan/loan/' . $item->loan->id . '/edit',
                    'text' => $text,
                ]);
                $notifArray['id'] = (int) $item->loan->id;
                break;
            case 'cash_advance_approval':
                $notifArray = array_merge($notifArray, [
                    'notif' => 'Management Approval - Cash Advance',
                    'link' => '/karyawan/approval-cash-advance/detail/' . $item->id,
                    'text' => 'New request for Cash Advance from ' . $item->user->name . ' / ' . $item->user->nik,
                ]);
                break;
            case 'transfer_cash_advance_approve':
                $notifArray = array_merge($notifArray, [
                    'notif' => 'Management Approval - Cash Advance',
                    'link' => '/karyawan/approval-cash-advance/detail/transfer/' . $item->id,
                    'text' => 'New request payment for Cash Advance from ' . $item->user->name . ' / ' . $item->user->nik,
                ]);
                break;
            case 'claim_cash_advance_approval':
                $notifArray = array_merge($notifArray, [
                    'notif' => 'Management Approval - Cash Advance',
                    'link' => '/karyawan/approval-cash-advance/claim/' . $item->id,
                    'text' => 'New request claim for Cash Advance from ' . $item->user->name . ' / ' . $item->user->nik,
                ]);
                break;
            case 'transfer_claim_cash_advance':
                $notifArray = array_merge($notifArray, [
                    'notif' => 'Management Approval - Cash Advance',
                    'link' => '/karyawan/approval-cash-advance/claim/transfer/' . $item->id,
                    'text' => 'New request payment claim for Cash Advance from ' . $item->user->name . ' / ' . $item->user->nik,
                ]);
                break;
            case 'transfer_claim_cash_advance_more':
                $notifArray = array_merge($notifArray, [
                    'notif' => 'Management Approval - Cash Advance',
                    'link' => '/karyawan/approval-cash-advance/claim/transfer/' . $item->id,
                    'text' => 'New transfer proof for Cash Advance from ' . $item->user->name . ' / ' . $item->user->nik,
                ]);
                break;
            case 'business_trip_approval':
                $notifArray = array_merge($notifArray, [
                    'notif' => 'Management Approval - Business Trip',
                    'link' => '/karyawan/approval-training-custom/detail/' . $item->id,
                    'text' => 'New request for Business Trip from ' . $item->user->name . ' / ' . $item->user->nik,
                ]);
                break;
            case 'transfer_business_trip_approve':
                $notifArray = array_merge($notifArray, [
                    'notif' => 'Management Approval - Business Trip',
                    'link' => '/karyawan/approval-training-custom/detail/transfer/' . $item->id,
                    'text' => 'New request payment for Business Trip from ' . $item->user->name . ' / ' . $item->user->nik,
                ]);
                break;
            case 'training_approval':
                $notifArray = array_merge($notifArray, [
                    'notif' => 'Management Approval - Business Trip',
                    'link' => '/karyawan/approval-training-custom/claim/' . $item->id,
                    'text' => 'New request actual bill for Business Trip from ' . $item->user->name . ' / ' . $item->user->nik,
                ]);
                break;
            case 'transfer_claim_business_trip':
                $notifArray = array_merge($notifArray, [
                    'notif' => 'Management Approval - Business Trip',
                    'link' => '/karyawan/approval-training-custom/claim/transfer/' . $item->id,
                    'text' => 'New request payment actual bill for Business Trip from ' . $item->user->name . ' / ' . $item->user->nik,
                ]);
                break;
            case 'transfer_claim_business_trip_more':
                $notifArray = array_merge($notifArray, [
                    'notif' => 'Management Approval - Business Trip',
                    'link' => '/karyawan/approval-training-custom/claim/transfer/' . $item->id,
                    'text' => 'New transfer proof for Business Trip from ' . $item->user->name . ' / ' . $item->user->nik,
                ]);
                break;
            case 'overtime_approval':
                if ($item->status == 1) {
                    $text = 'New request for Overtime Sheet from ' . $item->user->name . ' / ' . $item->user->nik;
                }
                if ($item->status_claim == 1) {
                    $text = 'New request claim for Overtime Sheet from ' . $item->user->name . ' / ' . $item->user->nik;
                }
                $notifArray = array_merge($notifArray, [
                    'notif' => 'Management Approval - Overtime Sheet',
                    'link' => '/karyawan/approval-overtime-custom/detail/' . $item->id,
                    'text' => $text,
                ]);
                break;
            case 'leave_approval':
                if ($item->status == 1) {
                    $text = 'New request for Leave from ' . $item->user->name . ' / ' . $item->user->nik;
                } else if ($item->status == 6) {
                    $text = 'New withdraw request for Leave from ' . $item->user->name . ' / ' . $item->user->nik;
                }
                $notifArray = array_merge($notifArray, [
                    'notif' => 'Management Approval - Leave',
                    'link' => '/karyawan/approval-leave-custom/detail/' . $item->id,
                    'text' => $text,
                ]);
                break;
            case 'timesheet_approval':
                $notifArray = array_merge($notifArray, [
                    'notif' => 'Management Approval - Timesheet',
                    'link' => '/karyawan/approval-timesheet-custom/detail/' . $item->id,
                    'text' => 'New request for Timesheet from ' . $item->user->name . ' / ' . $item->user->nik,
                ]);
                break;
            case 'recruitment_approval':
                $notifArray = array_merge($notifArray, [
                    'notif' => 'Management Approval - Recruitment Request',
                    'link' => '/karyawan/approval-recruitment-request/detail/' . $item->id,
                    'text' => 'New request for Recruitment Request from ' . $item->requestor->name . ' / ' . $item->requestor->nik,
                ]);
                break;
            case 'medical_approval':
                $notifArray = array_merge($notifArray, [
                    'notif' => 'Management Approval - Medical Reimbursement',
                    'link' => '/karyawan/approval-medical-custom/detail/' . $item->id,
                    'text' => 'New request for Medical Reimbursement from ' . $item->user->name . ' / ' . $item->user->nik,
                ]);
                break;
            case 'transfer_medical_approve':
                $notifArray = array_merge($notifArray, [
                    'notif' => 'Management Approval - Medical Reimbursement',
                    'link' => '/karyawan/approval-medical-custom/detail/' . $item->id,
                    'text' => 'New request payment for Medical Reimbursement from ' . $item->user->name . ' / ' . $item->user->nik,
                ]);
                break;
            case 'approval_payment_request':
                $notifArray = array_merge($notifArray, [
                    'notif' => 'Management Approval - Payment Request',
                    'link' => '/karyawan/approval-payment-request-custom/detail/' . $item->id,
                    'text' => 'New request for Payment Request from ' . $item->user->name . ' / ' . $item->user->nik,
                ]);
                break;
            case 'transfer_payment_request_approve':
                $notifArray = array_merge($notifArray, [
                    'notif' => 'Management Approval - Payment Request',
                    'link' => '/karyawan/approval-payment-request-custom/detail/' . $item->id,
                    'text' => 'New request payment for Payment Request from ' . $item->user->name . ' / ' . $item->user->nik,
                ]);
                break;
            case 'exit_clearance_approval':
                $notifArray = array_merge($notifArray, [
                    'notif' => 'Management Approval - Exit Clearance',
                    'link' => '/karyawan/approval-clearance-custom/detail/' . $item->id,
                    'text' => 'New request for Exit Clearance from ' . $item->user->name . ' / ' . $item->user->nik,
                ]);
                break;
            case 'exit_interview_approval':
                $notifArray = array_merge($notifArray, [
                    'notif' => 'Management Approval - Exit Interview',
                    'link' => '/karyawan/approval-exit-custom/detail/' . $item->id,
                    'text' => 'New request for Exit Interview from ' . $item->user->name . ' / ' . $item->user->nik,
                ]);
                break;
            case 'facilities_return':
                $notifArray = array_merge($notifArray, [
                    'notif' => 'Management Approval - Facilities',
                    'link' => '/karyawan/approval-facilities/detail/' . $item->id,
                    'text' => 'New request return for Facilities from ' . $item->user->name . ' / ' . $item->user->nik,
                ]);
                break;
            case 'facilities_term_agreement_pic':
                $notifArray = array_merge($notifArray, [
                    'notif' => 'Management Approval - Facilities',
                    'link' => '/karyawan/approval-facilities',
                    'text' => 'New acceptance for Facilities from ' . $item->user->name . ' / ' . $item->user->nik,
                ]);
                $notifArray['id'] = (int) $item->asset_id;
                break;
            case 'loan_approval':
                $notifArray = array_merge($notifArray, [
                    'notif' => 'Management Approval - Loan',
                    'link' => '/karyawan/approval-loan/detail/' . $item->id,
                    'text' => 'New request for Loan from ' . $item->user->name . ' / ' . $item->user->nik,
                ]);
                break;
            case 'news':
                $notifArray = array_merge($notifArray, [
                    'notif' => 'Home - News List',
                    'link' => '/karyawan/news/readmore/' . $item->id,
                    'text' => $item->title,
                ]);
                break;
            case 'memo':
                $notifArray = array_merge($notifArray, [
                    'notif' => 'Home - Internal Memo',
                    'link' => '/karyawan/internal-memo/readmore/' . $item->id,
                    'text' => $item->title,
                ]);
                break;
            case 'product':
                $notifArray = array_merge($notifArray, [
                    'notif' => 'Home - Product Information',
                    'link' => '/karyawan/product/readmore/' . $item->id,
                    'text' => $item->title,
                ]);
                break;
            case 'internal':
                $notifArray = array_merge($notifArray, [
                    'notif' => 'Home - Internal Recruitment',
                    'link' => '/karyawan/internal-recruitment/detail/' . $item->id,
                    'text' => 'Position ' . $item->job_position . ' is now open',
                ]);
                break;
        }

        $db = Config::get('database.default', 'mysql');
        Config::set('database.default', 'mysql');

        $params['company_code'] = $company_code;
        $params['user_id'] = $user_id;
        $params['notifArray'] = $notifArray;
        dispatch((new \App\Jobs\SendNotif($params))->onQueue('notif'));

        Config::set('database.default', $db);
    }

    public static function changeReadStatus($id, $company_code)
    {
        $endpoint = env('FIREBASE_DATABASE_URL') . env('SERVER') . '/' . strtolower($company_code) . '/' . \Auth::user()->id . '/' . $id . '.json';
        $client = new \GuzzleHttp\Client();
        return $client->request('PATCH', $endpoint, [
            'json' => json_decode(json_encode([
                'read' => 1,
            ])),
        ]);
    }
}
