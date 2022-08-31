<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LoanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'number' => $this->number,
            'user_id' => $this->user_id,
            'user' => new UserMinResource($this->user),
            'loan_purpose' => $this->loan_purpose,
            'plafond' => $this->plafond,
            'available_plafond' => $this->available_plafond,
            'expected_disbursement_date' => $this->expected_disbursement_date,
            'disbursement_date' => $this->disbursement_date,
            'amount' => $this->amount,
            'calculated_amount' => $this->calculated_amount,
            'rate' => $this->rate,
            'interest' => $this->interest,
            'payment_type' => $this->payment_type == 1 ? 'Deduct Salary' : 'Transfer to Company',
            'assets' => LoanAssetResource::collection($this->asset),
            'status' => $this->status,
            'history_approval' => LoanHistoryApprovalResource::collection($this->historyApproval),
            'approval_collateral_receipt_status' => $this->approval_collateral_receipt_status,
            'approval_collateral_receipt_user_id' => $this->approval_collateral_receipt_user_id,
            'approval_collateral_receipt_user' => new UserMinResource($this->receiptApprover),
            'approval_collateral_receipt_date' => $this->approval_collateral_receipt_date,
            'approval_collateral_receipt_note' => $this->approval_collateral_receipt_note,
            'approval_collateral_physical_status' => $this->approval_collateral_physical_status,
            'approval_collateral_physical_user_id' => $this->approval_collateral_physical_user_id,
            'approval_collateral_physical_user' => new UserMinResource($this->physicalApprover),
            'approval_collateral_physical_date' => $this->approval_collateral_physical_date,
            'approval_collateral_physical_note' => $this->approval_collateral_physical_note,
            'approval_loan_status' => $this->approval_loan_status,
            'approval_loan_user_id' => $this->approval_loan_user_id,
            'approval_loan_user' => new UserMinResource($this->loanApprover),
            'approval_loan_date' => $this->approval_loan_date,
            'approval_loan_note' => $this->approval_loan_note,
            'first_due_date' => $this->first_due_date,
            'user_assign' => $this->user_assign ? "/storage/file-loan-assign/" . $this->user_assign : null,
            'collateral_assign' => $this->collateral_assign ? "/storage/file-loan-assign/" . $this->collateral_assign : null,
            'approver_assign' => $this->approver_assign ? "/storage/file-loan-assign/" . $this->approver_assign : null,
            'photo_assign' => $this->photo_assign ? "/storage/file-loan-assign/" . $this->photo_assign : null,
            'payments' => LoanPaymentResource::collection($this->payment),
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
        ];
    }
}
