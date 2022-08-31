<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LoanPaymentResource extends JsonResource
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
            'loan_id' => $this->loan_id,
            'tenor' => $this->tenor,
            'due_date' => $this->due_date,
            'amount' => $this->amount,
            'status' => $this->status,
            'payment_type' => $this->payment_type == 1 ? 'Deduct Salary' : 'Transfer to Company',
            'payroll' => $this->payrollHistory ? (string) $this->payrollHistory->created_at : null,
            'photo' => $this->photo ? "/storage/file-loan-payment/" . $this->photo : null,
            'user_note' => $this->user_note,
            'payment_date' => $this->payment_date,
            'submit_date' => $this->submit_date,
            'approval_user_id' => $this->approval_user_id,
            'approval_user' => new UserMinResource($this->approver),
            'approval_note' => $this->approval_note,
            'approval_date' => $this->approval_date,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
        ];
    }
}
